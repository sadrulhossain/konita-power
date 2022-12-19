@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i>@lang('label.DELIVERY_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[27][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('delivery/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_DELIVERY')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'delivery/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderId">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('order_id',  $orderArr, Request::get('order_id'), ['class' => 'form-control js-source-states','id'=>'orderId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status',  $statusArr, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="latestShipmentDate">@lang('label.LSD')</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker" style="z-index: 9994 !important;">
                                {!! Form::text('latest_shipment_date', null, ['id'=> 'latestShipmentDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="latestShipmentDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="text-center vcenter">@lang('label.TOTAL_QUANTITY')</th>
                            <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                            <th class="text-center vcenter">@lang('label.LATEST_SHIPMENT_DATE')</th>
                            <th class="text-center vcenter">@lang('label.NOTIFICATION_DATE')</th>
                            <th class="text-center vcenter">@lang('label.ESTIMATED_TIME_OF_SHIPMENT')</th>
                            <th class="text-center vcenter">@lang('label.ETS_NOTIFICATION_DATE')</th>
                            <th class="text-center vcenter">@lang('label.ESTIMATED_TIME_OF_ARRIVAL')</th>
                            <th class="text-center vcenter">@lang('label.LC_DOC')</th>
                            <th class="text-center vcenter">@lang('label.SHIPMENT_DOC')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->order_no !!}</td>
                            <td class="text-center vcenter">{!! $target->total_quantity !!}</td>
                            <td class="text-center vcenter">{!! $target->quantity !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->latest_shipment_date) !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->notification_date) !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->ets) !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->ets_notification_date) !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->eta) !!}</td>
                            <td class="text-center vcenter">
                                @if($target->lc_doc == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($target->lc_doc == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->shipment_doc == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($target->shipment_doc == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-primary">@lang('label.PROCESSING')</span>
                                @elseif($target->status == '2')
                                <span class="label label-success">@lang('label.DELIVERED')</span>
                                @elseif($target->status == '3')
                                <span class="label label-info">@lang('label.PAYMENT_DONE')</span>
                                @elseif($target->status == '4')
                                <span class="label label-danger">@lang('label.FAILED')</span>
                                @elseif($target->status == '5')
                                <span class="label label-warning">@lang('label.LOCKED')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[27][3]) && $target->status == '1')
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('delivery/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[27][4]) && $target->status == '1')
                                    {!! Form::open(array('url' => 'delivery/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
                                    @if(!empty($userAccessArr[27][10]) && $target->status == '1')
                                    <button class="btn btn-xs green-soft lock tooltips vcenter" title="Lock Delivery" data-id="{!! $target->id !!}" data-placement="top" data-rel="tooltip" data-original-title="Lock Delivery" style="padding-left: 6px; padding-right: 6px;">
                                        <i class="fa fa-lock"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[27][14]) && in_array($target->status, ['5']))
                                    <button class="btn btn-xs purple-sharp tooltips vcenter deliver" title="Deliver" href="#modaldeliver" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-shopping-cart"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[27][15]) && in_array($target->status, ['1', '5']))
                                    <button class="btn btn-xs red-mint tooltips vcenter mark-failed-delivery" title="Mark as Failed Delivery" href="#modalMarkFailedDelivery" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-stop"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[27][5]))
                                    <button class="btn btn-xs yellow tooltips vcenter delivery-details" title="Veiw Delivery Details" href="#modalDeliveryDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="13" class="vcenter">@lang('label.NO_DELIVERY_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- Modal start -->


<!--delivery details-->
<div class="modal fade" id="modalDeliveryDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDeliveryDetails"></div>
    </div>
</div>

<!--mark failed delivery-->
<div class="modal fade" id="modalMarkFailedDelivery" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showMarkFailedDelivery"></div>
    </div>
</div>

<!-- Modal end-->


<script type="text/javascript">
    $(function () {
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

        //mark failed delivery modal
        $(".mark-failed-delivery").on("click", function (e) {
            e.preventDefault();
            var deliveryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/delivery/getFailedDelivery')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    delivery_id: deliveryId
                },
                success: function (res) {
                    $("#showMarkFailedDelivery").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //delivery details modal
        $(".delivery-details").on("click", function (e) {
            e.preventDefault();
            var deliveryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/delivery/getDeliveryDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    delivery_id: deliveryId
                },
                success: function (res) {
                    $("#showDeliveryDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
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

@stop