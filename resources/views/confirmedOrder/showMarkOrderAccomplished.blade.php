<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.MARK_ORDER_AS_ACCOMPLISHED')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'orderAccomplishForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="orderAccomplishRemarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::textarea('order_accomplish_remarks', null, ['id'=> 'orderCancelRemarks', 'class' => 'form-control','autocomplete' => 'off','rows' => 4, 'cols' => 40,]) !!} 
                                    <span class="text-danger">{{ $errors->first('order_accomplish_remarks') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-top-20 first-followup-block">
                        <div class="col-md-12 border-bottom-1-green-seagreen">
                            <h4><strong>@lang('label.BUYER_FOLLOWUP')</strong></h4>
                        </div>
                        <div class="col-md-12 margin-top-20">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12">
                                    {!! Form::select('status', $statusList, null, ['class' => 'form-control js-source-states ','id'=>'status']) !!}
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                            {!! Form::hidden('order_no', !empty($target->order_no)?$target->order_no:'') !!}
                            <div class="form-group">
                                <label class="control-label col-md-4" for="remarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12">
                                    {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn green-seagreen tooltips vcenter" type="button" id='submitOrderAccomplished'>
                <i class="fa fa-check-circle"></i>&nbsp;@lang('label.MARK_AS_ACCOMPLISHED')
            </button>
            <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //cancel order
    $("#submitOrderAccomplished").on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "This order will be marked as accomplished!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Mark",
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

                var formData = new FormData($("#orderAccomplishForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/confirmedOrder/accomplish')}}",
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
<?php if (!empty($userAccessArr[31][1])) { ?>
                            setTimeout(window.location.replace('{{ route("accomplishedOrder.index")}}'), 3000);
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
