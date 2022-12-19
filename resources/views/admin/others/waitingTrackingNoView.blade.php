<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red modal-close pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.WAITING_FOR_TRACKING_NO_LIST')
        </h3>
    </div>
    <div class="modal-body">
        @php $canAddTrack = (!empty($userAccessArr[27][16]) || !empty($userAccessArr[31][16])) ? 1 : 0; @endphp
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="text-center vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    <th class="text-center vcenter">@lang('label.BUYER')</th>
                                    <th class="text-center vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="text-center vcenter">@lang('label.BL_NO')</th>
                                    @if($canAddTrack == 1)
                                    <th class="text-center vcenter">@lang('label.ADD_TRACKING_NO')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($targetArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $inquiryId=>$target)
                                <?php
                                $rowspan = !empty($blNoArr[$inquiryId]) ? count($blNoArr[$inquiryId]) : 0;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['order_no'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['purchase_order_no'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['buyer_name'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['supplier_name'] !!}</td>
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($blNoArr[$inquiryId] as $shipmentId => $blNO)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{!! $blNO !!}</td>
                                    @if($canAddTrack == 1)
                                    <td class="vcenter width-300">
                                        <div class="input-group bootstrap-touchspin width-inherit">
                                            {!! Form::text('express_tracking_no['.$shipmentId.']', null, ['id'=> 'expressTrackingNo_' . $shipmentId, 'class' => 'form-control track-no','autocomplete' => 'off']) !!} 
                                            <span class="input-group-addon label-green-seagreen padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                <button class="btn btn-sm green-seagreen update-track margin-0 tooltips vcenter" data-shipment-id="{{ $shipmentId }}" title="@lang('label.ADD_TRACKING_NO')" type="button">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                <?php
                                $i++;
                                ?>
                                @endforeach
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="{!! $canAddTrack == 1 ? 7 : 6 !!}" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline modal-close grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(document).on('click', '.modal-close', function (e){
        location.reload();
    });
    $(document).on('click', 'body', function (e){
        if(!$(e.target).closest('#trackingNoViewModal').length && !$(e.target).is('#trackingNoViewModal')){
            location.reload();
        }
    });
    
    $(".update-track").on("click", function (e) {
        e.preventDefault();

        var shipmentId = $(this).attr("data-shipment-id");
        var trackingNo = $("#expressTrackingNo").val();
//        alert(shipmentId+trackingNo);
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: "{{URL::to('dashboard/updateTrackingNo')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                shipment_id: shipmentId,
                tracking_no: trackingNo,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                toastr.success(res.message, res.heading, options);
                $.ajax({
                    url: "{{ URL::to('dashboard/waitingTrackingNo')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                    },
                    beforeSend: function () {
                        $("#trackingNoViewModal").html('');
                    },
                    success: function (res) {
                        $("#trackingNoViewModal").html(res.html);
                        $('.tooltips').tooltip();
                        //table header fix
                        $(".table-head-fixer-color").tableHeadFixer();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    }
                }); //ajax
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }

                App.unblockUI();
            }
        }); //ajax
    });
    //end :: editable tracking no.
});
</script>
