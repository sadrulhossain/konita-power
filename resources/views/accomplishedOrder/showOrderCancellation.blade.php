<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ORDER_CANCELLATION')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'orderCancelForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-8">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="cancelCause">@lang('label.CAUSE_OF_FAILURE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('order_cancel_cause', $causeList, null, ['class' => 'form-control js-source-states ','id'=>'cancelCause']) !!}
                                    <span class="text-danger">{{ $errors->first('order_cancel_cause') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="orderCancelRemarks">@lang('label.REMARKS') :</label>
                                <div class="col-md-8">
                                    {!! Form::textarea('order_cancel_remarks', null, ['id'=> 'orderCancelRemarks', 'class' => 'form-control','autocomplete' => 'off','rows' => 4, 'cols' => 40,]) !!} 
                                    <span class="text-danger">{{ $errors->first('order_cancel_remarks') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn red-intense tooltips vcenter" type="button" id='submitOrderCancel'>
                <i class="fa fa-ban"></i>&nbsp;@lang('label.SUBMIT_CANCEL')
            </button>
            <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    
    $(".js-source-states").select2({dropdownParent: $('body')});

    //cancel order
    $("#submitOrderCancel").on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "No delivery or payment will be done on this order!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Cancel",
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

                var formData = new FormData($("#orderCancelForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/accomplishedOrder/cancel')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        <?php if(!empty($userAccessArr[30][1])) { ?>
                        setTimeout(window.location.replace('{{ route("cancelledOrder.index")}}'), 3000);
                        <?php } else { ?>
                        location.reload();
                        <?php } ?>
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
            }
        });
    });
});
</script>
