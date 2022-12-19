<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            {!! ($request->shipment_status == 1) ? __('label.CARRIER_INFORMATION') : __('label.VIEW_CARRIER_INFO')!!}
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            @if($request->shipment_status == 1)
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'setCarrierInfoForm')) !!}
                {!! Form::hidden('shipment_id', $previousCarrierInfo->id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="shippingLine">@lang('label.SHIPPING_LINE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-6 col-sm-6">
                            {!! Form::select('shipping_line', $shippingLineList, !empty($previousCarrierInfo->shipping_line)?$previousCarrierInfo->shipping_line:null, ['id'=> 'shippingLine', 'class' => 'form-control js-source-states']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="containerNo">@lang('label.CONTAINER_NO') :<span class="text-danger"> *</span></label>
                        <div class="col-md-6 col-sm-6">
                            @if(!empty($previousContainerNoArr))
                            <?php $counter = 0; ?>
                            @foreach($previousContainerNoArr as  $key => $containerNo)
                            <div class="row margin-bottom-10">
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    {!! Form::text('container_no['.$key.']',  $containerNo, ['class'=>'form-control', 'id' => 'containerNo_'.$key,'autocomplete' => 'off']) !!}
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    @if($counter == 0)
                                    <button class="btn btn-inline green-haze add-new-container-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-inline btn-danger remove-container-row tooltips" title="Remove" type="button">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <?php $counter++; ?>
                            @endforeach
                            @else
                            <?php $v3 = 'a' . uniqid(); ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    {!! Form::text('container_no['.$v3.']',  null, ['class'=>'form-control', 'id' => 'containerNo_'.$v3,'autocomplete' => 'off']) !!}
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <button class="btn btn-inline green-haze add-new-container-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                            <div id="newContainerNoRow"></div>
                        </div>
                    </div>              
                </div>
                {!! Form::close() !!}
            </div>
            @else
            <!-- carrier data -->
            <div class=" col-md-offset-1 col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="info">
                                    <th class="vcenter">@lang('label.SHIPPING_LINE')</th>
                                    <th class="vcenter">@lang('label.CONTAINER_NO')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="vcenter">{!! !empty($previousCarrierInfo->shipping_line) && !empty($shippingLineList)?$shippingLineList[$previousCarrierInfo->shipping_line]:'--' !!}</td>
                                    <td class="vcenter">
                                        @if(!empty($previousContainerNoArr))
                                        <?php $c = 0; ?>
                                        @foreach($previousContainerNoArr as $contNo)
                                        {{ $contNo }}{!! $c != count($previousContainerNoArr)-1 ? '<br/>'  : '' !!}
                                        <?php ++$c; ?>
                                        @endforeach
                                        @else
                                        @lang('label.N_A')
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </table>
                </div>
            </div>
            <!-- end: carrier data -->
            @endif
        </div>
        <div class="modal-footer">
            @if($request->shipment_status == 1)
            <button class="btn green-seagreen tooltips vcenter" type="button" id="submitCarrierInfo">
                @lang('label.SUBMIT')
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
    $(".js-source-states").select2({dropdownParent: $('body')});

    //add new ets row
    $(".add-new-container-row").on("click", function (e) {
        e.preventDefault();
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


        $.ajax({
            url: "{{URL::to('confirmedOrder/newContainerNoRow')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $("#newContainerNoRow").prepend(res.html);
            },
        });
    });
    //remove ets row
    $('.remove-container-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });

    //cancel order
    $("#submitCarrierInfo").on("click", function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Confirm",
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

                var formData = new FormData($("#setCarrierInfoForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/confirmedOrder/setCarrierInfo')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('#submitCarrierInfo').prop('disabled', true);
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
                        $('#submitCarrierInfo').prop('disabled', false);
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});
</script>
