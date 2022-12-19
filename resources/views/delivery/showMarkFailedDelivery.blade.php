<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.MARK_AS_FAILED_DELIVERY')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.CURRENT_STATUS'): 
                @if($deliveryInfo->status == '1')
                <span class="label label-primary">@lang('label.PROCESSING')</span>
                @elseif($deliveryInfo->status == '2')
                <span class="label label-success">@lang('label.DELIVERED')</span>
                @elseif($deliveryInfo->status == '3')
                <span class="label label-info">@lang('label.PAYMENT_DONE')</span>
                @elseif($deliveryInfo->status == '4')
                <span class="label label-danger">@lang('label.FAILED')</span>
                @elseif($deliveryInfo->status == '5')
                <span class="label label-warning">@lang('label.LOCKED')</span>
                @endif
            </div>
        </div>
        <hr/>
        {!! Form::model($deliveryInfo, ['url' => '', 'id' => 'markFailedDeliveryFrom', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
        {{csrf_field()}}
        {!! Form::hidden('delivery_id', $deliveryInfo->id) !!}
        <div class="form-body">
            <div class="form-group">
                <label class="control-label col-md-5" for="failureCauseId">@lang('label.TARGET_SELLING_PRICE') :<span class="text-danger"> *</span></label>
                <div class="col-md-6">
                    {!! Form::select('failure_cause_id', $failureCauseArr, null, ['class' => 'form-control js-source-states', 'id' => 'failureCauseId']) !!}
                    <span class="text-danger">{{ $errors->first('failure_cause_id') }}</span>
                </div>
            </div>      
        </div>
        {!! Form::close() !!}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveFailedDelivery">@lang('label.MARK_AS_FAILED_DELIVERY')</button>
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    
    //mark failed delivery & save cause of failure
    $("#saveFailedDelivery").on("click", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You can not undo this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, mark as failure',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: true,
            closeOnCancel: false},
                function (isConfirm) {
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

                        // Serialize the form data
                        var formData = new FormData($('#markFailedDeliveryFrom')[0]);
                        $.ajax({
                            url: "{{URL::to('delivery/markFailedDelivery')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
                                location.reload();
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
                        });
                    } else {
                        swal('Cancelled', '', 'error');
                    }
                });

    });

//    
});
</script>