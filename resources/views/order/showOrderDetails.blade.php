<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title"><strong>@lang('label.ORDER_DETAILS')</strong></h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

        </div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr >
                            <td class="fit bold info">@lang('label.ORDER_NO')</td>
                            <td colspan='5'>{!! $orderInfo->order_no !!}</td>
                            <td class="fit bold info">@lang('label.BUYER')</td>
                            <td colspan='5'>{!! $orderInfo->buyer_name !!}</td>
                        </tr>
                        <tr >
                            <td class="fit bold info">@lang('label.PRODUCT')</td>
                            <td colspan='5'>{!! $orderInfo->product_name !!}</td>
                            <td class="fit bold info">@lang('label.BRAND')</td>
                            <td colspan='5'>{!! $orderInfo->brand_name !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.LC_VALUE')</td>
                            <td colspan='5'>{!! $orderInfo->lc_value !!}</td>
                            <td class="fit bold info">@lang('label.LC_NO')</td>
                            <td colspan='5'>{!! $orderInfo->lc_no !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.LC_DATE')</td>
                            <td colspan='5'>{!! Helper::formatDate($orderInfo->lc_date) !!}</td>
                            <td class="fit bold info">@lang('label.EXPRESSION_TRACKING_NO')</td>
                            <td colspan='5'>{!! $orderInfo->expression_tracking_no !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.LC_DRAFT_DONE')</td>
                            <td colspan='5'>
                                @if($orderInfo->lc_draft_done == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($orderInfo->lc_draft_done == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="fit bold info">@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                            <td colspan='5'>
                                @if($orderInfo->lc_transmitted_copy_done == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($orderInfo->lc_transmitted_copy_done == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td class="fit bold info">@lang('label.STATUS')</td>
                            <td colspan='5'>
                                @if($orderInfo->status == '1')
                                <span class="label label-primary">@lang('label.PENDING')</span>
                                @elseif($orderInfo->status == '2')
                                <span class="label label-danger">@lang('label.DISCARDED')</span>
                                @elseif($orderInfo->status == '3')
                                <span class="label label-info">@lang('label.PROCESSING')</span>
                                @elseif($orderInfo->status == '4')
                                <span class="label label-success">@lang('label.ACCOMPLISHED')</span>
                                @elseif($orderInfo->status == '5')
                                <span class="label label-warning">@lang('label.PAYMENT_DONE')</span>
                                @endif
                            </td>
                            <td class="fit bold info"></td>
                            <td colspan='5'></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($userAccessArr[26][13]) && in_array($orderInfo->status, ['1', '3']))
        <button class="btn btn-danger cancel tooltips vcenter" data-id="{!! $orderInfo->id !!}">
            <i class="fa fa-ban"></i>&nbsp;@lang('label.CANCEL_ORDER')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //cancel order
    $('.cancel').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');
        swal({
            title: "Are you sure?",
            text: "Your will not be able to edit this order!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, cancel it",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null,
                };

                // data
                var orderId = $(".cancel").attr("data-id");
                $.ajax({
                    url: "{{ URL::to('/order/cancel')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        order_id: orderId
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
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
                    }
                }); //ajax
            }
        });
    });
});
</script>