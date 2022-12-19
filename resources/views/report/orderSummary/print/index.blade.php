<html>
    <?php
    $basePath = URL::to('/');
    if (Request::get('view') == 'pdf') {
        $basePath = base_path();
    }
    ?>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{$basePath}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!--<link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">-->
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <!--<link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <!--<link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />-->
        <!--<link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />-->
        <!--<link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />-->




        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/custom.css'}}" rel="stylesheet" type="text/css"/>
        <!--<link href="{{ base_path().'/public/assets/global/plugins/font-awesome/css/font-awesome.css'}}" rel="stylesheet" type="text/css"/>-->
        <!--<link href="{{ base_path().'/public/assets/global/plugins/bootstrap/css/bootstrap.min.css'}}" rel="stylesheet" type="text/css"/>-->
        <link href="{{ base_path().'/public/assets/global/css/components.min.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'}}" rel="stylesheet" type="text/css"/>
        <!--<link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css"/>--> 
        @endif

        <style type="text/css" media="print">
            @page { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

    </head>
    <body>
        <!--        <div class="header">
                    @if(Request::get('view') == 'pdf')
                    <img src="{!! base_path() !!}/public/img/logo_small_print.png" alt="RTMS Logo" /> @else
                    <img src="{!! asset('public/img/logo_small_print.png') !!}" alt="RTMS Logo" /> @endif
                    <p>@lang('label.ACADEMIC_REPORT')</p>
                </div>-->
        <!--Endof_BL_history data-->


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
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="inv-border-bottom font-size-11 header">@lang('label.ORDER_SUMMARY_REPORT')</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 margin-bottom-10">
                    <span class="bold">
                        @lang('label.FROM_DATE'): {{$request->pi_from_date}} &nbsp;|&nbsp;
                        @lang('label.TO_DATE'): {{$request->pi_to_date}}
                    </span>

                </div>

                <!--SUMMARY-->
                <div class="col-md-12">

                    <table class="table no-border">
                        <tr class="no-border ">
                            <!--step 1-->
                            <td class="no-border v-top" width="50%">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="vcenter text-center bold font-size-11">@lang('label.STATUS')</td>
                                            <td class="vcenter text-center bold font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.PURCHASE_VOLUME')</td>
                                            <td class="vcenter text-center bold font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.EXPENDITURE')</td>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.CONFIRMED')</td>
                                            <td class="text-right vcenter font-size-11">{{!empty($purchaseSummary['volume']['confirmed']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['confirmed']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                            <td class="text-right vcenter font-size-11">${{!empty($purchaseSummary['amount']['confirmed']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['confirmed']) : 0.00 }}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.IN_PROGRESS')</td>
                                            <td class="text-right vcenter font-size-11">{{!empty($purchaseSummary['volume']['in_progress']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['in_progress']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                            <td class="text-right vcenter font-size-11">${{!empty($purchaseSummary['amount']['in_progress']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['in_progress']) : 0.00 }}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.ACCOMPLISHED')</td>
                                            <td class="text-right vcenter font-size-11">{{!empty($purchaseSummary['volume']['accomplished']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['accomplished']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                            <td class="text-right vcenter font-size-11">${{!empty($purchaseSummary['amount']['accomplished']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['accomplished']) : 0.00 }}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.CANCELLED')</td>
                                            <td class="text-right vcenter font-size-11">{{!empty($purchaseSummary['volume']['cancelled']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['cancelled']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                            <td class="text-right vcenter font-size-11">${{!empty($purchaseSummary['amount']['cancelled']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['cancelled']) : 0.00 }}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter bold">@lang('label.TOTAL')</td>
                                            <td class="text-right bold vcenter font-size-11">{{!empty($purchaseSummary['volume']['total']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['total']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                            <td class="text-right bold vcenter font-size-11">${{!empty($purchaseSummary['amount']['total']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['total']) : 0.00 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <!--step 2-->
                            <td class="no-border v-top">
                            </td>
                            <!--step 3-->
                            <td class="no-border v-top">
                            </td>
                        </tr>
                    </table>



                </div>
                <!--END OF SUMMARY-->
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.ORDER_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.PURCHASE_ORDER_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.SUPPLIER')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.PI_DATE')</th>
                                <th class="vcenter font-size-11">@lang('label.LC_NO')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.STATUS')</th>
                                @if(Request::get('view') == 'print')
                                <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                                @endif
                                <th class="vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="vcenter font-size-11">@lang('label.BRAND')</th>
                                <th class="vcenter font-size-11">@lang('label.GRADE')</th>
                                <th class="vcenter font-size-11">@lang('label.GSM')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = $totalSalesVolume = $totalSalesAmount = 0;
                            ?>
                            @foreach($targetArr as $key=>$target)
                            <?php
                            //inquiry rowspan
                            $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                            ?>
                            <tr>
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->order_no)?$target->order_no:'' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->purchase_order_no)?$target->purchase_order_no:'' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! $target->supplier !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->pi_date) ? Helper::formatDate($target->pi_date) : '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! $target->lc_no !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    {!! !empty($target->lc_date) ? Helper::formatDate($target->lc_date) : '' !!}
                                </td>
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($target->lc_transmitted_copy_done == '1')
                                    <span class="label label-sm label-info">@lang('label.YES')</span>
                                    @elseif($target->lc_transmitted_copy_done == '0')
                                    <span class="label label-sm label-warning">@lang('label.NO')</span>
                                    @endif
                                </td>
                                <td class="vcenter text-center font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($target->order_status == '2')
                                    <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                    @elseif($target->order_status == '3')
                                    <span class="label label-sm label-purple">@lang('label.IN_PROGRESS')</span>
                                    @elseif($target->order_status == '4')
                                    <span class="label label-sm label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                                    @elseif($target->order_status == '6')
                                    <span class="label label-sm label-red-intense">@lang('label.CANCELLED')</span>
                                    @endif
                                </td>
                                @if(Request::get('view') == 'print')
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if(in_array($target->order_status, ['2', '3', '4']))
                                    @if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr))
                                    @foreach($deliveryArr[$target->id] as $deliveryId => $delivery)

                                    <button class="btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                            title="
                                            <div class='text-left'>
                                            @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                            @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                            @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                            @lang('label.CLICK_TO_SEE_DETAILS')
                                            </div>
                                            " 
                                            href="#modalShipmentDetails" data-id="{!! $deliveryId !!}" data-toggle="modal">
                                        <i class="fa fa-{{$delivery['icon']}}"></i>
                                    </button>
                                    @endforeach
                                    @else
                                    <button type="button" class="btn btn-xs cursor-default btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    @endif
                                    @else
                                    <button type="button" class="btn btn-xs cursor-default btn-circle grey-cascade tooltips vcenter" title="@lang('label.CANCELLED')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    @endif
                                </td>
                                @endif

                                @if(!empty($target->inquiryDetails))
                                <?php $i = 0; ?>
                                @foreach($target->inquiryDetails as $productId=> $productData)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['product']}}">
                                    {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                                </td>
                                @if(!empty($productData))
                                <?php $j = 0; ?>
                                @foreach($productData as $brandId=> $brandData)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['brand']}}">
                                    {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                </td>
                                @if(!empty($brandData))
                                <?php $k = 0; ?>
                                @foreach($brandData as $gradeId=> $gradeData)
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['grade']}}">
                                    {{!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''}}
                                </td>
                                @if(!empty($gradeData))
                                <?php $l = 0; ?>
                                @foreach($gradeData as $gsm=> $item)
                                <?php
                                if ($l > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter font-size-11">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                <td class="vcenter text-right font-size-11">{{Helper::numberFormat2Digit($item['sales_volume'])}}&nbsp;{{$item['unit_name']}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($item['sales_amount'])}}</td>
                                <?php
                                if ($l < ($rowSpan['grade'] - 1)) {
                                    echo '</tr>';
                                }

                                $i++;
                                $j++;
                                $k++;
                                $l++;
                                ?>
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td class="vcenter bold text-right font-size-11" colspan="{{Request::get('view') == 'print' ? 14 : 13 }}">@lang('label.TOTAL')</td>
                                <td class="vcenter bold text-right font-size-11">{{!empty($purchaseSummary['volume']['total']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['total']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                <td class="vcenter bold text-right font-size-11">${{!empty($purchaseSummary['amount']['total']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['total']) : 0.00 }}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="{{Request::get('view') == 'print' ? 16 : 15 }}" class="vcenter font-size-11">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
        <table class="table borderless">
            <tr>
                <td class="no-border text-left font-size-11">
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
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