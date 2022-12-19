@extends('layouts.default.master')
@section('data_count')
<div class="shipment-info-page col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i>@lang('label.SET_SHIPMENT_INFO')&nbsp;&nbsp;
                <h6 class="caption-sub-caption bold">@lang('label.ORDER_NO') : {!! !empty($target->order_no)?$target->order_no:'' !!}&nbsp;|&nbsp;@lang('label.PURCHASE_ORDER_NO'): {!! !empty($target->purchase_order_no)?$target->purchase_order_no:'' !!}</h6>
            </div>
            <div class="actions">
                <a href="{{ URL::to('/confirmedOrder') }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row margin-bottom-10">
                <div class="col-md-12 add-new-shipment-col">
                    @if(empty($draftDeliveryList) && in_array($target->order_status, ['2', '3']))
                    <button class="btn btn-lg btn-radius-50 green-seagreen add-new-shipment tooltips" href="#modalAddNewShipment" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.ADD_NEW_SHIPMENT')">
                        <i class="fa fa-plus"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($draftDeliveryList))
                    <?php
                    $crActive = 'active';
                    $blActive = '';
                    $tooltipText = __('label.ADD_CARRIER_INFORMATION');
                    $blTooltipText = __('label.ADD_CARRIER_INFO_BEFORE_PROCCEED_TO_BL_INFO');
                    $blAddHide = 'disabled';
                    if (!empty($draftDeliveryList->shipping_line) && !empty($draftDeliveryList->container_no)) {
                        $crActive = 'done';
                        $blActive = 'active';
                        $tooltipText = __('label.EDIT_CARRIER_INFORMATION');
                        $blTooltipText = __('label.ADD_BL_INFORMATION');
                        $blAddHide = '';
                    }
                    ?>
                    <div class="row margin-bottom-30">
                        <div class="col-md-11 col-lg-11 col-sm-12 col-xs-12">
                            <div class="mt-element-step">
                                <div class="row step-line">
                                    <div class="col-md-4 mt-step-col first done">
                                        <a class="a-tag-decoration-none edit-ets-eta-info tooltips vcenter" href="#modalEditEtsEtaInfo" data-id="{!! $draftDeliveryList->id !!}" data-shipment-status="{!! $draftDeliveryList->shipment_status !!}" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-calendar"></i> 
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font">@lang('label.ETS_ETA_INFORMATION')</div>
                                        <div class="mt-step-content font-grey-cascade">@lang('label.EDIT_ETS_ETA_INFORMATION')</div>
                                    </div>
                                    <div class="col-md-4 mt-step-col {{ $crActive}}">
                                        <a class="a-tag-decoration-none set-carrier-info tooltips vcenter" href="#modalSetCarrierInfo" data-id="{!! $draftDeliveryList->id !!}" data-shipment-status="{!! $draftDeliveryList->shipment_status !!}" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-ship"></i>
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font">@lang('label.CARRIER_INFORMATION')</div>
                                        <div class="mt-step-content font-grey-cascade">{{$tooltipText}}</div>
                                    </div>
                                    <div class="col-md-4 mt-step-col last {{ $blActive}}">
                                        <a class="a-tag-decoration-none {{ $blAddHide }} set-bl-info tooltips vcenter" href="#modalSetBlInfo" data-id="{!! $draftDeliveryList->id !!}" data-shipment-status="{!! $draftDeliveryList->shipment_status !!}" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-th-large"></i>
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font">@lang('label.BL_INFORMATION')</div>
                                        <div class="mt-step-content font-grey-cascade">{{$blTooltipText}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 draft-shipment-details">
                            <button class="btn btn-lg btn-radius-50 yellow view-shipment-full-detail tooltips" href="#modalViewShipmentFullDetail" data-id="{!! $draftDeliveryList->id !!}" data-toggle="modal" title="@lang('label.VIEW_SHIPMENT_DETAILS_DRAFT')">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!--order info btn-->
            <a class="btn order-details-btn grey-mint btn-lg tooltips" title="@lang('label.CLICK_TO_VIEW_ORDER_INFORMATON')">
                <i class="fa fa-th"></i>
            </a>
            <!--order info btn-->
            <div class="order-details-div">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless">
                            <tr class="border-bottom-1-green-seagreen">
                                <td class="bold"><h4>@lang('label.ORDER_INFO')</h4></td>
                                <td class="text-right">
                                    <a class="order-detail-close btn btn-danger btn-sm tooltips" title="@lang('label.CLOSE')"><i class="fa fa-close"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="order-details-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fit bold info">@lang('label.CLIENT')</td>
                                    <td colspan="5" class="active">{!! !empty($target->buyer_name)?$target->buyer_name:'' !!}</td>
                                    <td class="fit bold info">@lang('label.SUPPLIER')</td>
                                    <td colspan="5" class="active">{!! !empty($target->supplier_name)?$target->supplier_name:'' !!}</td>
                                </tr>
                                <tr >
                                    <td class="fit bold info">@lang('label.SALES_PERSON')</td>
                                    <td colspan="5" class="active">{!! !empty($target->salesPersonName)?$target->salesPersonName:'' !!}</td>
                                    <td class="fit bold info">@lang('label.STATUS')</td>
                                    <td colspan="5" class="active">
                                        @if($target->order_status == '2')
                                        <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                        @elseif($target->order_status == '3')
                                        <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                        @elseif($target->order_status == '4')
                                        <span class="label label-sm label-success">@lang('label.ACCOMPLISHED')</span>
                                        @elseif($target->order_status == '5')
                                        <span class="label label-sm label-warning">@lang('label.PAYMENT_DONE')</span>
                                        @elseif($target->order_status == '6')
                                        <span class="label label-sm label-danger">@lang('label.CANCELLED')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr >
                                    <td class="fit bold info">@lang('label.CREATION_DATE')</td>
                                    <td colspan="5" class="active">{!! !empty($target->creation_date) ? Helper::formatDate($target->creation_date) : __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.PO_DATE')</td>
                                    <td colspan="5" class="active">{!! !empty($target->po_date) ? Helper::formatDate($target->po_date) : __('label.N_A') !!}</td>
                                </tr>
                            </table>
                            @if(!$inquiryDetails->isEmpty())
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="info">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.BRAND')</th>
                                        <th class="vcenter">@lang('label.GRADE')</th>
                                        <th class="vcenter">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.ALREADY_DELIVERED')</th>
                                        <th class="text-center vcenter">@lang('label.DUE_DELIVERY')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>
                                    @foreach($inquiryDetails as $item)
                                    <?php
                                    $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                    $textAlignDueQty = 'text-center';
                                    $dueQuantity = '--';
                                    if (!empty($dueQuantityArr[$item->id]) && $dueQuantityArr[$item->id] > 0) {
                                        $textAlignDueQty = 'text-right';
                                        $dueQuantity = Helper::numberFormat2Digit($dueQuantityArr[$item->id]) . $unit;
                                    }
                                    ?>
                                    <tr class="active">
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--order info btn : end-->
            
            <div class="row">
                <div class="col-md-12">
                    <h4 class="border-bottom-1-green-seagreen bold">@lang('label.SHIPMENT_LIST_OF_THIS_ORDER')</h4>
                    @if(!$shippedDeliveryList->isEmpty())
                    @foreach($shippedDeliveryList as $shippedDelivery)
                    <div class="row margin-bottom-10">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 padding-17 col-padding-default">
                                <span class="text-center vcenter bold">@lang('label.BL_NO') : {!! !empty($shippedDelivery->bl_no)?$shippedDelivery->bl_no:'--' !!}</span>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 padding-17 col-padding-default">
                                <span class="text-center vcenter bold">@lang('label.DATE_OF_BL') : {!! !empty($shippedDelivery->bl_date)?Helper::formatDate($shippedDelivery->bl_date):'' !!}</span>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-chambray edit-ets-eta-info tooltips" href="#modalEditEtsEtaInfo" data-id="{!! $shippedDelivery->id !!}" data-shipment-status="{!! $shippedDelivery->shipment_status !!}" data-toggle="modal" title="@lang('label.VIEW_ETS_ETA_INFO')">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-dark set-carrier-info tooltips" href="#modalSetCarrierInfo" data-id="{!! $shippedDelivery->id !!}" data-shipment-status="{!! $shippedDelivery->shipment_status !!}" data-toggle="modal" title="@lang('label.VIEW_CARRIER_INFO')">
                                    <i class="fa fa-ship"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-madison set-bl-info tooltips" href="#modalSetBlInfo" data-id="{!! $shippedDelivery->id !!}" data-shipment-status="{!! $shippedDelivery->shipment_status !!}" data-toggle="modal" title="@lang('label.VIEW_BL_INFO')">
                                    <i class="fa fa-th-large"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-steel view-shipment-full-detail tooltips" href="#modalViewShipmentFullDetail" data-id="{!! $shippedDelivery->id !!}" data-toggle="modal" title="@lang('label.VIEW_SHIPMENT_DETAILS')">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-12 div-padding-default text-danger">
                        @lang('label.NO_SHIPMENT_HAS_BEEN_MADE_YET')
                    </div>
                    @endif
                </div>
            </div>
        </div>	
    </div>
</div>

<!-- Modal start -->

<!--add new shipment-->
<div class="modal fade" id="modalAddNewShipment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAddnewShipment"></div>
    </div>
</div>

<!--edit ets eta info-->
<div class="modal fade" id="modalEditEtsEtaInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showEditEtsEtaInfo"></div>
    </div>
</div>

<!--set carrier info-->
<div class="modal fade" id="modalSetCarrierInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showSetCarrierInfo"></div>
    </div>
</div>

<!--set bl info-->
<div class="modal fade" id="modalSetBlInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetBlInfo"></div>
    </div>
</div>

<!--view shipment full detail-->
<div class="modal fade" id="modalViewShipmentFullDetail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentFullDetail"></div>
    </div>
</div>


<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //initially hide order information
        $(".order-details-div").hide();

        //show order information
        $(".order-details-btn").on("click", function () {
            $(".order-details-div").show(1000);
            //$(".shipment-info-page").css("cursor", "pointer");
        });

        // if order information visible,
        // hide on click page body
        $(".order-details-div, .order-detail-close").on("click", function () {
            $(".order-details-div").hide(1000);
        });


        //add new shipment modal
        $(".add-new-shipment").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/confirmedOrder/getNewShipmentAdd')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showAddnewShipment").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //edit ets eta info modal
        $(".edit-ets-eta-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "{{ URL::to('/confirmedOrder/editEtsEtaInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showEditEtsEtaInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set carrier info modal
        $(".set-carrier-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "{{ URL::to('/confirmedOrder/getCarrierInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showSetCarrierInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set bl info modal
        $(".set-bl-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "{{ URL::to('/confirmedOrder/getBlInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showSetBlInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //shipment full details modal
        $(".view-shipment-full-detail").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/confirmedOrder/getShipmentFullDetail')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentFullDetail").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });

</script>

@stop