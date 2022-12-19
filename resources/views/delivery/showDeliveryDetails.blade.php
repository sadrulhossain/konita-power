<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title"><strong>@lang('label.DELIVERY_DETAILS')</strong></h4>
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
                            <td colspan='5'>{!! $deliveryInfo->order_no !!}</td>
                            <td class="fit bold info">@lang('label.BUYER')</td>
                            <td colspan='5'>{!! $deliveryInfo->buyer_name !!}</td>
                        </tr>
                        <tr >
                            <td class="fit bold info">@lang('label.PRODUCT')</td>
                            <td colspan='5'>{!! $deliveryInfo->product_name !!}</td>
                            <td class="fit bold info">@lang('label.BRAND')</td>
                            <td colspan='5'>{!! $deliveryInfo->brand_name !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.TOTAL_QUANTITY')</td>
                            <td colspan='5'>{!! $deliveryInfo->total_quantity !!}</td>
                            <td class="fit bold info">@lang('label.DELIVERY_QUANTITY')</td>
                            <td colspan='5'>{!! $deliveryInfo->quantity !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.LATEST_SHIPMENT_DATE')</td>
                            <td colspan='5'>{!! Helper::formatDate($deliveryInfo->latest_shipment_date) !!}</td>
                            <td class="fit bold info">@lang('label.NOTIFICATION_DATE')</td>
                            <td colspan='5'>{!! Helper::formatDate($deliveryInfo->notification_date) !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.ESTIMATED_TIME_OF_SHIPMENT')</td>
                            <td colspan='5'>{!! Helper::formatDate($deliveryInfo->ets) !!}</td>
                            <td class="fit bold info">@lang('label.ETS_NOTIFICATION_DATE')</td>
                            <td colspan='5'>{!! Helper::formatDate($deliveryInfo->ets_notification_date) !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold info">@lang('label.ESTIMATED_TIME_OF_ARRIVAL')</td>
                            <td colspan='5'>{!! Helper::formatDate($deliveryInfo->eta) !!}</td>
                            <td class="fit bold info">@lang('label.STATUS')</td>
                            <td colspan='5'>
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
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($userAccessArr[27][10]) && $deliveryInfo->status == '1')
        <button class="btn green-soft lock tooltips vcenter" data-id="{!! $deliveryInfo->id !!}">
            <i class="fa fa-lock"></i>&nbsp;@lang('label.LOCK_DELIVERY')
        </button>
        @endif
        @if(!empty($userAccessArr[27][14]) && in_array($deliveryInfo->status, ['5']))
        <button class="btn purple-sharp tooltips vcenter deliver" data-id="{!! $deliveryInfo->id !!}" >
            <i class="fa fa-shopping-cart"></i>&nbsp;@lang('label.DELIVER')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //lock delivery
    $('.lock').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Your will not be able to edit or delete this delivery!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, lock it",
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
                var deliveryId = $(".lock").attr("data-id");
                $.ajax({
                    url: "{{ URL::to('/delivery/lock')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        delivery_id: deliveryId
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
    
    //deliver delivery
        $('.deliver').on('click', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "The order will be partially delivered!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deliver it",
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
                    var deliveryId = $(".deliver").attr("data-id");
                    $.ajax({
                        url: "{{ URL::to('/delivery/deliver')}}",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            delivery_id: deliveryId
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