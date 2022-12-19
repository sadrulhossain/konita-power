<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header clone-modal-header">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            <h4 class="modal-title text-center">
                {!! ($request->shipment_status == 1) ? __('label.BL_INFORMATION') : __('label.VIEW_BL_INFO')!!}
            </h4>
        </div>
        <div class="modal-body">
            {!! Form::open(array('group' => 'form', 'url' => '', 'id' => 'setBlInfoFrom', 'class' => 'form-horizontal','files' => true)) !!}
            {{csrf_field()}}
            {!! Form::hidden('shipment_id', $request->shipment_id) !!}
            {!! Form::hidden('inquiry_id', $target->inquiry_id) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-2 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-5" for="blNo">@lang('label.BL_NO') :<span class="text-danger">{{$request->shipment_status == 1?' *':''}}</span></label>
                            @if($request->shipment_status == 1)
                            <div class="col-md-7">
                                {!! Form::text('bl_no', null, ['id'=> 'blNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('bl_no') }}</span>
                            </div>
                            @else
                            <div class="col-md-7 bold margin-top-8">
                                {!! $target->bl_no ?? __('label.N_A') !!} 
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-5" for="blDate">@lang('label.DATE_OF_BL') :<span class="text-danger">{{$request->shipment_status == 1?' *':''}}</span></label>
                            @if($request->shipment_status == 1)
                            <div class="col-md-7">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('bl_date', date('d F Y'), ['id'=> 'blDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="blDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('bl_date') }}</span>
                                </div>
                            </div>
                            @else
                            <div class="col-md-7 bold margin-top-8">
                                {!! !empty($target->bl_date)?Helper::formatDate($target->bl_date):'' !!}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-5" for="expressTrackingNo">@lang('label.EXPRESS_TRACKING_NO') :</label>
                            @if($request->shipment_status == 1)
                            <div class="col-md-7">
                                {!! Form::text('express_tracking_no', null, ['id'=> 'expressTrackingNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('express_tracking_no') }}</span>
                            </div>
                            @else
                            @if(!empty($userAccessArr[27][16]))
                            <div class="col-md-7 bold margin-top-8 plain-track">
                                <span class="track-no" id="trackingNoSpan">{!! !empty($target->express_tracking_no)?$target->express_tracking_no: __('label.N_A') !!}</span> &nbsp;
                                <button class="btn btn-xs btn-primary edit-track tooltips vcenter" title="Edit Tracking No." type="button">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                            <div class="col-md-7 editable-track">
                                <div class="input-group bootstrap-touchspin">
                                    {!! Form::text('express_tracking_no', !empty($target->express_tracking_no)?$target->express_tracking_no:null, ['id'=> 'editableExpressTrackingNoInput', 'class' => 'form-control editable-track-no','autocomplete' => 'off']) !!} 
                                    <span class="input-group-addon label-green-seagreen padding-0 border-0 bootstrap-touchspin-postfix bold">
                                        <button class="btn btn-sm green-seagreen update-track margin-0 tooltips vcenter" data-shipment-id="{{ $request->shipment_id }}" title="Update Tracking No." type="button">
                                            <i class="fa fa-save"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="col-md-7 bold margin-top-8">
                                {!! !empty($target->express_tracking_no)?$target->express_tracking_no: __('label.N_A') !!}
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-5" for="lastShipment">@lang('label.LAST_SHIPMENT') :</label>
                            @if($request->shipment_status == 1)
                            <div class="col-md-7 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('last_shipment',1,null, ['id'=> 'lastShipment', 'class'=> 'md-check']) !!} 
                                <label for="lastShipment">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_IF_LAST_SHIPMENT')</span>
                            </div>
                            @else
                            <div class="col-md-7 bold margin-top-8">
                                @if($target->last_shipment == '1')
                                <span class="label label-sm label-primary">@lang('label.YES')</span>
                                @elseif($target->last_shipment == '0')
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <!--product details-->
                    @if(!$inquiryDetails->isEmpty())
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.BRAND')</th>
                                        <th class="vcenter">@lang('label.GRADE')</th>
                                        <th class="vcenter">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.ALREADY_DELIVERED')</th>
                                        <th class="text-center vcenter">@lang('label.DUE_DELIVERY')</th>
                                        <th class="text-center vcenter">@lang('label.SHIPMENT_QTY')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>

                                    {!! Form::hidden('no_due', 0, ['id' => 'noDue']) !!}
                                    {!! Form::hidden('no_of_item', count($inquiryDetails), ['id' => 'noOfItem']) !!}
                                    @foreach($inquiryDetails as $item)
                                    <tr>
                                        <?php
                                        $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                        $textAlignDueQty = 'text-center';
                                        $dueQuantity = '--';
                                        if (!empty($dueQuantityArr[$item->id]) && $dueQuantityArr[$item->id] > 0) {
                                            $textAlignDueQty = 'text-right';
                                            $dueQuantity = Helper::numberFormat2Digit($dueQuantityArr[$item->id]) . $unit;
                                        }

                                        $textAlignshipmentQty = 'text-center';
                                        $shipmentQuantity = '--';
                                        if (!empty($shipmentQuantityArr[$item->id][$request->shipment_id])) {
                                            $textAlignshipmentQty = 'text-right';
                                            $shipmentQuantity = Helper::numberFormat2Digit($shipmentQuantityArr[$item->id][$request->shipment_id]) . $unit;
                                        }
                                        ?>
                                        <td class="text-center vcenter">{!! ++$countItem !!}</td>
                                        <td class="vcenter">{!! $item->product_name ?? '' !!}</td>
                                        <td class="vcenter">{!! $item->brand_name ?? '' !!}</td>
                                        <td class="vcenter">{!! $item->grade_name ?? '' !!}</td>
                                        <td class="vcenter">{!! !empty($item->gsm) ? $item->gsm : '' !!}</td>
                                        <td class="text-right vcenter">{!! (!empty($item->quantity) ? $item->quantity : 0.00).$unit !!}</td>
                                        <td class="text-right vcenter">
                                            {!! ((!empty($quantitySumArr[$item->id]) && $quantitySumArr[$item->id] != 0) ? Helper::numberFormat2Digit($quantitySumArr[$item->id]) : Helper::numberFormat2Digit(0)).$unit !!}
                                            {!! (!empty($surplusQuantityArr[$item->id]) && $surplusQuantityArr[$item->id] > 0) ? '<br/><span class="text-danger">(Additional ' . Helper::numberFormat2Digit($surplusQuantityArr[$item->id]).$unit . ')</span>' : '' !!}
                                        </td>
                                        <td class="{{ $textAlignDueQty }} vcenter">{!! $dueQuantity !!}</td>
                                        @if($request->shipment_status == 1)
                                        <td class="text-right vcenter w-200">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <?php
                                                $dueQty = !empty($dueQuantityArr[$item->id]) ? $dueQuantityArr[$item->id] : 0.00;
                                                $readOnly = $dueQty <= 0 ? 'readonly' : '';
                                                ?>
                                                {!! Form::hidden('due_quantity['.$item->id.']', $dueQty) !!} 
                                                {!! Form::hidden('remaining_quantity['.$item->id.']', $dueQty, ['id' => 'remainingQuantity_'.$item->id, 'class' => 'remaining-quantity']) !!} 
                                                {!! Form::text('shipment_quantity['.$item->id.']', null, ['id'=> 'shipmentQuantity_'.$item->id, 'data-id' => $item->id, 'data-unit' => $unit, 'data-due-qty' => $dueQty, 'class' => 'form-control integer-decimal-only text-right text-input-width-100-per shipment-quantity', $readOnly]) !!} 
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">{!! $unit !!}</span>
                                            </div>
                                            <span class="remaining-qty-{{ $item->id }}"></span>
                                        </td>
                                        @else
                                        <td class="{{ $textAlignshipmentQty }} vcenter">{!! $shipmentQuantity !!}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
                @if($request->shipment_status == 1)
                <div class="row margin-top-20 first-followup-block">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BUYER_FOLLOWUP')</strong></h4>
                    </div>
                    <div class="col-md-12 margin-top-20">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                {!! Form::select('status', $statusList, null, ['class' => 'form-control js-source-states ','id'=>'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        {!! Form::hidden('order_no', !empty($target->order_no)?$target->order_no:'') !!}
                        {!! Form::hidden('buyer_id', $target->buyer_id) !!}
                        <div class="form-group">
                            <label class="control-label col-md-4" for="remarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control']) !!} 
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
        <div class="modal-footer">
            @if($request->shipment_status == 1)
            <button class="btn green-seagreen tooltips vcenter" id="setBlInfoSubmit">
                <i class="fa fa-lock"></i>&nbsp;@lang('label.SAVE_AND_LOCK')
            </button>
            @endif
            <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $('.first-followup-block').hide();
    $(document).on('click', '#lastShipment', function () {
        if ($(this).prop('checked')) {
            $('.first-followup-block').slideDown(800);
            $(".first-followup-block span.select2").css("width", "100%");
        } else {
            $('.first-followup-block').slideUp(800);
        }
    });
    //editable tracking no.
    $(".plain-track").show();
    $(".editable-track").hide();
    $(".edit-track").on("click", function () {
        $(".plain-track").hide();
        $(".editable-track").show();
    });
    $(".update-track").on("click", function (e) {
//        $(".plain-track").show();
//        $(".editable-track").hide();

        e.preventDefault();
        var shipmentId = $(this).attr("data-shipment-id");
        var trackingNo = $("#editableExpressTrackingNoInput").val();
//        alert(shipmentId+trackingNo);
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "{{URL::to('confirmedOrder/updateTrackingNo')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                shipment_id: shipmentId,
                tracking_no: trackingNo,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                toastr.success(res.message, res.heading, options);
                $("#trackingNoSpan").text(trackingNo != '' ? trackingNo : 'N/A');
                $("#editableExpressTrackingNoInput").val(trackingNo);
                $(".plain-track").show();
                $(".editable-track").hide();
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
                $("#setBlInfoSubmit").prop('disabled', false);
            }
        }); //ajax
    });
    //end :: editable tracking no.

    $('.shipment-quantity').keyup(function (e) {
        e.preventDefault();
        var qty = $(this).val();
        var noOfItem = $('#noOfItem').val();
        var itemId = $(this).attr("data-id");
        var dueQty = $(this).attr("data-due-qty");
        var unit = $(this).attr("data-unit");
        if (qty == '') {
            $('span.remaining-qty-' + itemId).text('');
            $('#remainingQuantity_' + itemId).val(dueQty);
            $('#lastShipment').prop('disabled', false);
            $('.first-followup-block').hide();
            $('#noDue').val(0);
            return false;
        }

        var noDue = 0;
        var remainingQty = dueQty - qty;

        if (qty.length > 0) {
            remainingQty = parseFloat(remainingQty).toFixed(2);

            $('#remainingQuantity_' + itemId).val(remainingQty);
            $('.remaining-quantity').each(function () {
                if ($(this).val() <= 0) {
                    noDue += 1;
                }
            });
            $('#noDue').val(noDue);

            if (noDue == noOfItem) {
                $('#lastShipment').prop('disabled', true);
                $('.first-followup-block').show();

            } else {
                $('#lastShipment').prop('disabled', false);
                $('.first-followup-block').hide();
            }

            if (remainingQty >= 0) {
                $('span.remaining-qty-' + itemId).text("Due : " + remainingQty + " " + unit);
                $('span.remaining-qty-' + itemId).css("color", "green");
                return false;
            } else {
                remainingQty = remainingQty * (-1);
                $('span.remaining-qty-' + itemId).text("Surplus : " + remainingQty + " " + unit);
                $('span.remaining-qty-' + itemId).css("color", "red");
                return false;
            }
        }
    });
    //set order delivery
    $('#setBlInfoSubmit').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "this shipment will be marked as shipped!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Confirm",
            cancelButtonText: "No, Cancel",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null,
                };
                // data
                var formData = new FormData($("#setBlInfoFrom")[0]);
                $.ajax({
                    url: "{{URL::to('confirmedOrder/setBlInfo')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $("#setBlInfoSubmit").prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
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
                        $("#setBlInfoSubmit").prop('disabled', false);
                    }
                }); //ajax
            }
        });
    });
});
</script>