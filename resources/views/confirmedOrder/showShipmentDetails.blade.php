<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('confirmedOrder/getShipmentDetailsPrint?shipment_id='.Request::get('shipment_id').'&view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
        </a>
        <h4 class="modal-title text-center">
            {{ ($shipmentInfo->shipment_status == 1) ? __('label.SHIPMENT_DETAILS_DRAFT') : __('label.SHIPMENT_DETAILS') }}
        </h4>
    </div>
    <div class="modal-body order-details-row">
        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BASIC_ORDER_INFO')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.ORDER_NO')</td>
                                <td width="50%">{!! !empty($shipmentInfo->order_no)?$shipmentInfo->order_no:'' !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.BUYER')</td>
                                <td width="50%">{!! !empty($shipmentInfo->buyer_name)?$shipmentInfo->buyer_name:'' !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.SALES_PERSON')</td>
                                <td width="50%">{!! !empty($shipmentInfo->salesPersonName)?$shipmentInfo->salesPersonName:'' !!}</td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.SUPPLIER')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->supplier_name)?$shipmentInfo->supplier_name:'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.SHIPPING_TREMS')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->shipping_terms)?$shipmentInfo->shipping_terms:'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($shipmentInfo->order_status == '2')
                                    <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                    @elseif($shipmentInfo->order_status == '3')
                                    <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                    @elseif($shipmentInfo->order_status == '4')
                                    <span class="label label-sm label-success">@lang('label.ACCOMPLISHED')</span>
                                    @elseif($shipmentInfo->order_status == '6')
                                    <span class="label label-sm label-danger">@lang('label.CANCELLED')</span>
                                    @endif
                                </td>
                            </tr>     
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.PURCHASE_ORDER_NO')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->purchase_order_no)?$shipmentInfo->purchase_order_no:'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.PO_DATE')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->po_date)?Helper::formatDate($shipmentInfo->po_date):'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.CREATION_DATE')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->creation_date)?Helper::formatDate($shipmentInfo->creation_date):'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.PI_DATE')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->pi_date)?Helper::formatDate($shipmentInfo->pi_date):'' !!}
                                </td>
                            </tr>        
                            <tr>
                                <td class="bold" width="50%">@lang('label.DESTINATION_PORT')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->destination_port)?$shipmentInfo->destination_port:'' !!}
                                </td>
                            </tr>       
                            <tr>
                                <td class="bold" width="50%">@lang('label.BENEFICIARY_BANK')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->beneficiary_bank_name)?$shipmentInfo->beneficiary_bank_name: __('label.N_A') !!}
                                </td>
                            </tr>       
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.LC_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                        <table class="table table-borderless">
                            <tr>                          
                                <td class="bold" width="50%">@lang('label.LC_NO')</td>
                                <td width="50%">{!! !empty($shipmentInfo->lc_no)?$shipmentInfo->lc_no:__('label.N_A') !!}</td>
                            </tr>

                            <tr>
                                <td class="bold" width="50%">@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                                <td width="50%">
                                    @if($shipmentInfo->lc_transmitted_copy_done == '1')
                                    <span class="label label-sm label-info">@lang('label.YES')</span>
                                    @elseif($shipmentInfo->lc_transmitted_copy_done == '0')
                                    <span class="label label-sm label-warning">@lang('label.NO')</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.BANK')</td>
                                <td width="50%">
                                    {!! !empty($shipmentInfo->lc_opening_bank)?$shipmentInfo->lc_opening_bank:__('label.N_A') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                        <table class="table table-borderless">
                            <tr>                          
                                <td class="bold" width="50%">@lang('label.LC_DATE')</td>
                                <td width="50%">{!! !empty($shipmentInfo->lc_date)?Helper::formatDate($shipmentInfo->lc_date):__('label.N_A') !!}</td>
                            </tr>

                            <tr>
                                <td class="bold" width="50%">@lang('label.LC_ISSUE_DATE')</td>
                                <td width="50%">{!! !empty($shipmentInfo->lc_issue_date)?Helper::formatDate($shipmentInfo->lc_issue_date):__('label.N_A') !!}</td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.BRANCH')</td>
                                <td width="50%">{!! !empty($shipmentInfo->bank_barnch)?$shipmentInfo->bank_barnch:__('label.N_A') !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <table class="table table-borderless">
                            <tr>
                                <td class="bold" width="25%">@lang('label.NOTE_')</td>
                                <td width="75%">{!! !empty($shipmentInfo->note)?$shipmentInfo->note:__('label.N_A') !!}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF LC INFORMATION-->

        <div class="row">
            <!-- BL Information -->
            <div class="col-md-8">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4>
                                    <strong>@lang('label.BL_INFORMATION')</strong>
                                    @if ($shipmentInfo->buyer_payment_status == '1')
                                    &nbsp;<span class="label label-sm label-green-seagreen"><strong>@lang('label.PAID')</strong></span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-borderless">
                                    <tr>                          
                                        <td class="bold" width="50%">@lang('label.BL_NO')</td>
                                        <td width="50%">{!! !empty($shipmentInfo->bl_no)?$shipmentInfo->bl_no:__('label.N_A') !!}</td>
                                    </tr>

                                    <tr>
                                        <td class="bold" width="50%">@lang('label.EXPRESS_TRACKING_NO')</td>
                                        <td width="50%">
                                            @if(!empty($userAccessArr[27][16]) && $shipmentInfo->shipment_status == '2')
                                            <div class="plain-track">
                                                <span class="track-no" id="trackingNo">{!! !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no: __('label.N_A') !!}</span> &nbsp;
                                                <button class="btn btn-xs btn-primary edit-track tooltips vcenter" title="Edit Tracking No." type="button">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                            <div class="editable-track">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('express_tracking_no', !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no:null, ['id'=> 'editableExpressTrackingNo', 'class' => 'form-control editable-track-no','autocomplete' => 'off', 'style' => 'width: 100px']) !!} 
                                                    <span class="input-group-addon label-green-seagreen padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                        <button class="btn btn-sm green-seagreen update-track margin-0 tooltips vcenter" data-shipment-id="{{ $request->shipment_id }}" title="Update Tracking No." type="button">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            @else
                                            <div class="">
                                                {!! !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no:__('label.N_A') !!}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-borderless">
                                    <tr>                          
                                        <td class="bold" width="50%">@lang('label.DATE_OF_BL')</td>
                                        <td width="50%">{!! !empty($shipmentInfo->bl_date)?Helper::formatDate($shipmentInfo->bl_date):__('label.N_A') !!}</td>
                                    </tr>

                                    <tr>
                                        <td class="bold" width="50%">@lang('label.LAST_SHIPMENT')</td>
                                        <td width="50%">
                                            @if($shipmentInfo->last_shipment == '1')
                                            <span class="label label-sm label-info">@lang('label.YES')</span>
                                            @elseif($shipmentInfo->last_shipment == '0')
                                            <span class="label label-sm label-warning">@lang('label.NO')</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: BL Information -->

            <!-- Carrier Information -->
            <div class="col-md-4">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.CARRIER_INFORMATION')</strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <table class="table table-borderless">
                                <tr>                          
                                    <td class="bold" width="50%">@lang('label.SHIPPING_LINE')</td>
                                    <td width="50%">{!! !empty($shipmentInfo->shipping_line_name)?$shipmentInfo->shipping_line_name:__('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="bold" width="50%">@lang('label.CONTAINER_NO')</td>
                                    <td width="50%">
                                        @if(!empty($containerNo))
                                        <?php $c = 0; ?>
                                        @foreach($containerNo as $contNo)
                                        {{ $contNo }}{!! $c < (count($containerNo)-1) ? ', <br/>'  : '' !!}
                                        <?php ++$c; ?>
                                        @endforeach
                                        @else
                                        @lang('label.N_A')
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End fo :: Carrier Information -->
        </div>

        <!-- Product information-->
        @if(!$inquiryDetails->isEmpty())
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.PRODUCT_N_SHIPMENT_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.BRAND')</th>
                                        <th class="vcenter">@lang('label.GRADE')</th>
                                        <th class="vcenter">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.ALREADY_DELIVERED')</th>
                                        <th class="text-center vcenter">@lang('label.AMOUNT') (@lang('label.ALREADY_DELIVERED'))</th>
                                        <th class="text-center vcenter">@lang('label.DUE_DELIVERY')</th>
                                        <th class="text-center vcenter">@lang('label.SHIPMENT_QTY')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>
                                    @foreach($inquiryDetails as $item)
                                    <tr>
                                        <?php
                                        $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                        $perUnit = !empty($item->unit_name) ? ' /' . $item->unit_name : '';
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

                                        $alredyDeliveredAmount = 0;
                                        if (!empty($quantitySumArr[$item->id]) && $quantitySumArr[$item->id] != 0) {
                                            if (!empty($item->unit_price)) {
                                                $alredyDeliveredAmount = $quantitySumArr[$item->id] * $item->unit_price;
                                            }
                                        }
                                        ?>
                                        <td class="text-center vcenter">{!! ++$countItem !!}</td>
                                        <td class="vcenter">{!! $item->product_name ?? '' !!}</td>
                                        <td class="vcenter">{!! $item->brand_name ?? '' !!}</td>
                                        <td class="vcenter">{!! $item->grade_name ?? '' !!}</td>
                                        <td class="vcenter">{!! !empty($item->gsm) ? $item->gsm : '' !!}</td>
                                        <td class="text-right vcenter">{!! '$'.(!empty($item->unit_price) ? $item->unit_price : 0.00).$perUnit !!}</td>
                                        <td class="text-right vcenter">{!! (!empty($item->quantity) ? $item->quantity : 0.00).$unit !!}</td>
                                        <td class="text-right vcenter">{!! '$'.(!empty($item->total_price) ? $item->total_price : 0.00)!!}</td>
                                        <td class="text-right vcenter">
                                            {!! ((!empty($quantitySumArr[$item->id]) && $quantitySumArr[$item->id] != 0) ? Helper::numberFormat2Digit($quantitySumArr[$item->id]) : Helper::numberFormat2Digit(0)).$unit !!}
                                            {!! (!empty($surplusQuantityArr[$item->id]) && $surplusQuantityArr[$item->id] > 0) ? '<br/><span class="text-danger">(Additional ' . Helper::numberFormat2Digit($surplusQuantityArr[$item->id]).$unit . ')</span>' : '' !!}
                                        </td>
                                        <td class="text-right vcenter">
                                            {!! '$' . Helper::numberFormat2Digit($alredyDeliveredAmount) !!}
                                        </td>
                                        <td class="{{ $textAlignDueQty }} vcenter">{!! $dueQuantity !!}</td>
                                        <td class="{{ $textAlignshipmentQty }} vcenter">{!! $shipmentQuantity !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!--END OF BASIC ORDER INFORMATION-->

        <!-- commission information-->
        @if(!empty($commissionInfo))
        <!--        <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.COMMISSION_INFORMATION')</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter">@lang('label.KONITA_CMSN')</th>
                                                <th class="text-center vcenter">@lang('label.SALES_PERSON_COMMISSION')</th>
                                                <th class="text-center vcenter">@lang('label.BUYER_COMMISSION')</th>
                                                <th class="text-center vcenter">@lang('label.REBATE_COMMISSION')</th>
                                                <th class="text-center vcenter">@lang('label.PRINCIPLE_COMMISSION')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right vcenter">{!! '$' . (!empty($commissionInfo->konita_cmsn) ? Helper::numberFormat2Digit($commissionInfo->konita_cmsn) : '0.00') . "<span class='bold'>/unit</span>" !!}</td>
                                                <td class="text-right vcenter">{!! '$' . (!empty($commissionInfo->sales_person_cmsn) ? Helper::numberFormat2Digit($commissionInfo->sales_person_cmsn) : '0.00') . "<span class='bold'>/unit</span>" !!}</td>
                                                <td class="text-right vcenter">{!! '$' . (!empty($commissionInfo->buyer_cmsn) ? Helper::numberFormat2Digit($commissionInfo->buyer_cmsn) : '0.00') . "<span class='bold'>/unit</span>" !!}</td>
                                                <td class="text-right vcenter">{!! '$' . (!empty($commissionInfo->rebate_cmsn) ? Helper::numberFormat2Digit($commissionInfo->rebate_cmsn) : '0.00') . "<span class='bold'>/unit</span>" !!}</td>
                                                <td class="text-right vcenter">{!! '$' . (!empty($commissionInfo->principle_cmsn) ? Helper::numberFormat2Digit($commissionInfo->principle_cmsn) : '0.00') . "<span class='bold'>/unit</span>" !!}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
        @endif
        <!--END OF commission information-->

        <div class="row">
            <!-- ETS Information -->
            <div class="col-md-6">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.ETS_INFO')</strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                <th class="text-center vcenter">@lang('label.ETS_DATE')</th>
                                                <th class="text-center vcenter">@lang('label.ETS_NOTIFICATION_DATE')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($etsInfo))
                                            <?php
                                            $slets = 0;
                                            ?>
                                            @foreach($etsInfo as $ets)
                                            <tr>
                                                <td class="text-center vcenter"> {{ ++$slets }}</td>
                                                <td class="text-center vcenter">{!! !empty($ets['ets_date'])?Helper::formatDate($ets['ets_date']):__('label.N_A') !!}</td>
                                                <td class="text-center vcenter">{!! !empty($ets['ets_notification_date'])?Helper::formatDate($ets['ets_notification_date']):__('label.N_A') !!}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td class="vcenter" colspan="2">@lang('label.NO_ETS_INFO_FOUND')</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: ETS Information -->

            <!-- ETA Information -->
            <div class="col-md-6">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.ETA_INFO')</strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                <th class="text-center vcenter">@lang('label.ETA_DATE')</th>
                                                <th class="text-center vcenter">@lang('label.ETA_NOTIFICATION_DATE')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($etaInfo))
                                            <?php
                                            $sleta = 0;
                                            ?>
                                            @foreach($etaInfo as $eta)
                                            <tr>
                                                <td class="text-center vcenter"> {{ ++$sleta }}</td>
                                                <td class="text-center vcenter">{!! !empty($eta['eta_date'])?Helper::formatDate($eta['eta_date']):__('label.N_A') !!}</td>
                                                <td class="text-center vcenter">{!! !empty($eta['eta_notification_date'])?Helper::formatDate($eta['eta_notification_date']):__('label.N_A') !!}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td class="vcenter" colspan="2">@lang('label.NO_ETA_INFO_FOUND')</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: ETA Information -->
        </div>

        <!-- Start of :: Lead Time Information -->
        @if(!empty($leadTimeArr))
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.LEAD_TIME_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.DELIVERY_TIME')</th>
                                        <th class="text-center vcenter">@lang('label.TRANSIT_TIME')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_LEAD_TIME')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center vcenter">{!! $leadTimeArr['delivery_time'] !!}</td>
                                        <td class="text-center vcenter">{!! $leadTimeArr['transit_time'] !!}</td>
                                        <td class="text-center vcenter">{!! $leadTimeArr['total_lead_time'] !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- End of :: Lead Time Information -->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

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
        var trackingNo = $("#editableExpressTrackingNo").val();
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
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                toastr.success(res.message, res.heading, options);
                $("#trackingNo").text(trackingNo != '' ? trackingNo : 'N/A');
                $("#editableExpressTrackingNo").val(trackingNo);
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
            }
        }); //ajax
    });
    //end :: editable tracking no.
});
</script>