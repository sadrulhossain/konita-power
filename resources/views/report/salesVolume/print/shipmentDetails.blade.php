<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />

        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            @page { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    </head>
    <body>
        <!--        <div class="header">
                    @if(Request::get('view') == 'pdf')
                    <img src="{!! base_path() !!}/public/img/logo_small_print.png" alt="RTMS Logo" /> @else
                    <img src="{!! asset('public/img/logo_small_print.png') !!}" alt="RTMS Logo" /> @endif
                    <p>@lang('label.ACADEMIC_REPORT')</p>
                </div>-->
        <!--Endof_BL_history data-->

        <?php
        $basePath = URL::to('/');
        if (Request::get('view') == 'pdf') {
            $basePath = base_path();
        }
        ?>
        <div class="portlet-body">

            <div class="row margin-bottom-10">
                <!--header-->
                <div class="col-md-12">
                    <table class="table borderless">
                        <tr>
                            <td width='40%'>
                                <span>
                                    <img src="{{$basePath}}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                </span>
                            </td>
                            <td class="text-right font-size-11" width='60%'>
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.', ':''}}</span>
                                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End of Header-->
            </div>
            <div class="row margin-bottom-20">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header">
                            {{ ($shipmentInfo->shipment_status == 1) ? __('label.SHIPMENT_DETAILS_DRAFT') : __('label.SHIPMENT_DETAILS') }}

                        </span>
                    </div>
                </div>
            </div>
            <!--BASIC ORDER INFORMATION-->
            <div class="row div-box-default">
                <table>
                    <tr>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.BASIC_ORDER_INFO')</strong></h4>
                            </div>
                        </div>
                        <td class="no-border v-top" width='50%'>
                            <table class="table borderless">
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
                                        @lang('label.CONFIRMED')
                                        @elseif($shipmentInfo->order_status == '3')
                                        @lang('label.PROCESSING_DELIVERY')
                                        @elseif($shipmentInfo->order_status == '4')
                                        @lang('label.ACCOMPLISHED')
                                        @elseif($shipmentInfo->order_status == '6')
                                        @lang('label.CANCELLED')
                                        @endif
                                    </td>
                                </tr>     
                            </table>
                        </td>
                        <td class="no-border v-top" width='50%'>
                            <table class="table borderless">
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
                        </td>
                    </div>
                    </tr>
                </table>
            </div>


            <!--END OF BASIC ORDER INFORMATION-->

            <!--LC INFORMATION-->
            <div class="row div-box-default">
                <table>
                    <tr>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.LC_INFORMATION')</strong></h4>
                            </div>
                        </div>
                        <td class="no-border v-top" width='50%'>
                            <table class="table borderless">
                                <tr>                          
                                    <td class="bold" width="50%">@lang('label.LC_NO')</td>
                                    <td width="50%">{!! !empty($shipmentInfo->lc_no)?$shipmentInfo->lc_no:__('label.N_A') !!}</td>
                                </tr>

                                <tr>
                                    <td class="bold" width="50%">@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                                    <td width="50%">
                                        @if($shipmentInfo->lc_transmitted_copy_done == '1')
                                        @lang('label.YES')
                                        @elseif($shipmentInfo->lc_transmitted_copy_done == '0')
                                        @lang('label.NO')
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
                        </td>
                        <td class="no-border v-top" width='50%'>
                            <table class="table borderless">
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
                        </td>
                    </div>
                    </tr>
                    <tr>
                    <table class="table borderless">
                        <tr>
                            <td class="bold" width="25%">@lang('label.NOTE_')</td>
                            <td width="75%">{!! !empty($shipmentInfo->note)?$shipmentInfo->note:__('label.N_A') !!}</td>
                        </tr>
                    </table>
                    </tr>
                </table>
            </div>
            <!--END OF LC INFORMATION-->

            <!-- BL Information -->
            <table>
                <tr>
                    <td class="no-border v-top" width='50%'>
                        <div class="row div-box-default">
                            <table>
                                <tr>
                                <div class="col-md-12">
                                    <div class="col-md-12 border-bottom-1-green-seagreen">
                                        <h4>
                                            <strong>@lang('label.BL_INFORMATION')</strong>
                                            @if ($shipmentInfo->buyer_payment_status == '1')
                                            &nbsp;<span class="label label-sm"><strong>@lang('label.PAID')</strong></span>
                                            @endif
                                        </h4>
                                    </div>
                                    <td class="no-border v-top" width='50%'>
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

                                                    </div>
                                                    @else
                                                    <div class="">
                                                        {!! !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no:__('label.N_A') !!}
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="no-border v-top" width='50%'>
                                        <table class="table table-borderless">
                                            <tr>                          
                                                <td class="bold" width="50%">@lang('label.DATE_OF_BL')</td>
                                                <td width="50%">{!! !empty($shipmentInfo->bl_date)?Helper::formatDate($shipmentInfo->bl_date):__('label.N_A') !!}</td>
                                            </tr>

                                            <tr>
                                                <td class="bold" width="50%">@lang('label.LAST_SHIPMENT')</td>
                                                <td width="50%">
                                                    @if($shipmentInfo->last_shipment == '1')
                                                    @lang('label.YES')
                                                    @elseif($shipmentInfo->last_shipment == '0')
                                                    @lang('label.NO')
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </div>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td class="no-border v-top" width='40%'>
                        <div class="row div-box-default">
                            <table>
                                <tr>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 border-bottom-1-green-seagreen">
                                            <h4><strong>@lang('label.CARRIER_INFORMATION')</strong></h4>
                                        </div>
                                    </div>
                                    <td class="no-border v-top" width='100%'>
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
                                    </td>
                                </div>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <!-- End BL Information -->

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
                            <div class="">
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




            <!-- ETS Information -->

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
            <!-- End of :: ETS Information -->

            <!-- ETA Information -->
            <div class="row div-box-default margin-top-200">
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
            <!-- End of :: ETA Information -->


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

        <!--footer-->
        <table class="table borderless">
            <tr>
                <td class="no-border text-left font-size-11">
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}.
                </td>
                <td class="no-border text-right font-size-11">
                    @lang('label.GENERATED_FROM_KTI')
                </td>
            </tr>
        </table>

        <!--//end of footer-->
        <script type="text/javascript">
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>