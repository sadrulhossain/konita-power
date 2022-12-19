<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css"/> 
        @endif
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
            @if(!empty($poInfo))
            <div class="row">
                <!--header-->
                <div class="col-md-12">
                    <table class="table borderless">
                        <tr>
                            <td width='40%'>
                                <span> 
                                    @if(Request::get('view') == 'pdf')
                                    <img src="{!! base_path() !!}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                    @else
                                    <img src="{{URL::to('/')}}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                    @endif

                                </span>
                            </td>
                            <td class="text-right font-size-11" width='60%'>
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.',':''}}</span>
                                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End of Header-->
                <!--COMPANY INFORAMTION-->
                <div class="col-md-12">
                    <div class="text-center bold uppercase margin-bottom-20">
                        <span class="inv-border-bottom font-size-14">@lang('label.PURCHASE_ORDER')</span>
                    </div>
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="vcenter bold font-size-11">@lang('label.DEALER_AGENT')</td>
                                <td class="vcenter font-size-11">
                                    <span>@lang('label.DATE'):</span>
                                    <span>{{!empty($poInfo->po_date) ? Helper::formatDate($poInfo->po_date) : ''}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">
                                    <span class="bold">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                    <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                    <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                    <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.'.':''}}</span>
                                </td>
                                <td class="vcenter font-size-11">@lang('label.PO_NO'): {{!empty($target->purchase_order_no)?$target->purchase_order_no:''}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <!--SUPPLIER AND BUYER INFORMATION-->
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="vcenter font-size-11">@lang('label.SUPPLIER')</th>
                                <th class="vcenter font-size-11">@lang('label.BUYER')</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="vcenter font-size-11">
                                    <span>{{!empty($supplierInfo->supplier_name)?$supplierInfo->supplier_name:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->country_name)?$supplierInfo->country_name.'.':''}}</span>
                                </td>
                                <td class="vcenter font-size-11">
                                    <span>{{!empty($buyerInfo->name)?$buyerInfo->name:''}}</span><br/>
                                    <span>{{$buyerOfficeAddress}}</span>
                                </td>  
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!--DATA INPUT INFO-->
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <!--step 1-->
                            <tr>
                                <td class="vcenter bold font-size-11">@lang('label.PRECARRIER')</td>
                                <td class="vcenter bold font-size-11">@lang('label.SHIPPINGTERMS')</td>
                                <td class="vcenter bold font-size-11">@lang('label.FINAL_DESTINATION')</td>
                                <td class="vcenter bold font-size-11">@lang('label.TERMS_OF_PAYMENT')</td>
                                <td class="vcenter bold font-size-11">@lang('label.ESTIMATED_DELIVERY')</td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">{{!empty($poInfo->pre_carrier_name)?$poInfo->pre_carrier_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($poInfo->shipping_terms_name)?$poInfo->shipping_terms_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($poInfo->final_destination)?$poInfo->final_destination:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($poInfo->payment_terms_name)?$poInfo->payment_terms_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($poInfo->delivery_date) ? Helper::formatDate($poInfo->delivery_date):''}}</td> 
                            </tr>
                            <!--step 2-->
                        </tbody>
                    </table>
                </div>

                <!--RW BREAKDOWN DATA-->






                <!--final loop-->
                @if(!empty($targetArr))
                @foreach($targetArr as $id=>$target)
                @if(!empty($target['gsm_details']))
                @if($target['format'] == '1')
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="vcenter font-size-11">@lang('label.DESCRIPTION_OF_GOODS')</th>
                                <th class="vcenter font-size-11">@lang('label.HS_CODE')</th>
                                <th class="vcenter font-size-11">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                <th class="vcenter font-size-11">@lang('label.CORE_AND_DIA')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                <th class="text-center vcenter font-size-11">@lang('label.GSM')</th>
                                @if(!empty($rwParameter[$id]))
                                @foreach($rwParameter[$id] as $rwId=>$rwName)
                                <th class="text-center vcenter font-size-11">@lang('label.RW')&nbsp;({{$rwName}})</th>
                                @endforeach
                                @endif
                                <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')&nbsp;($)</th>
                                <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')&nbsp;($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">
                                    <span class="bold">{{$target['product_name']}}</span><br/>
                                    <span class="bold"> @lang('label.BRAND'):</span> <span>{{$target['brand_name']}}</span><br/>
                                    @if(!empty($target['grade_name']))
                                    <span class="bold">@lang('label.GRADE'):</span> <span>{{$target['grade_name']}}</span>
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">
                                    @if(!empty($hsCodeArr[$id]))
                                    @if(is_array($hsCodeArr[$id]))
                                    <?php
                                    $lastValue = end($hsCodeArr[$id]);
                                    ?>
                                    @foreach($hsCodeArr as $key => $hsCode )
                                    @foreach($hsCode as $val)
                                    {{!empty($val)?$val:''}}
                                    @if($lastValue !=$val)
                                    <span>,</span>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @else
                                    {{!empty($hsCodeArr[$id])?$hsCodeArr[$id]:''}}
                                    @endif
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['country_name']}}</td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['core_and_dia']}}&nbsp;{{!empty($rwInfo[$id])?$rwInfo[$id]:''}}</td>



                                @if(!empty($target['gsm_details']))
                                <?php
                                $i = 0;
                                ?>
                                @foreach($target['gsm_details'] as $gsmId=>$gsmVal)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }

                                $j = 0;
                                ?>

                                @foreach($gsmVal as $values)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter text-right font-size-11">
                                    {{!empty($values['quantity'])?$values['quantity']:''}}
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                                </td>
                                @if(!empty($rwParameter[$id]))
                                @foreach($rwParameter[$id] as $rwId=>$rwName)
                                <td class="vcenter text-right font-size-11">
                                    {{!empty($values[$rwId])?$values[$rwId]:''}}
                                </td>
                                @endforeach
                                @endif
                                <?php
                                $gradeId = !empty($target['grade_id']) ? $target['grade_id'] : 0;
                                $unitPrice = !empty($finalPriceArr[$id][$target['product_id']][$target['brand_id']][$gradeId]['unit_price'][$target['gsm_info'][$gsmId]]) ? $finalPriceArr[$id][$target['product_id']][$target['brand_id']][$gradeId]['unit_price'][$target['gsm_info'][$gsmId]] : 0;
                                ?>
                                <td class="vcenter text-right font-size-11">${{$unitPrice}}</td>
                                <?php
                                $totalPrice = (!empty($values['quantity']) ? $values['quantity'] : 0) * (!empty($unitPrice) ? $unitPrice : 0);
                                $grandTotal = !empty($grandTotal) ? $grandTotal : 0;
                                $grandTotal += $totalPrice;
                                ?>
                                <td class="vcenter text-right font-size-11">${{$totalPrice}}</td>

                                <?php
                                if (($j + 1) < count($gsmVal)) {
                                    echo '</tr>';
                                }
                                $j++;
                                ?>
                                @endforeach

                                <?php
                                if (($i + 1) < count($target['gsm_details'])) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>

                                @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td class="bold text-right font-size-11" colspan="4">@lang('label.TOTAL')</td>
                                <td class="bold text-right font-size-11">{{!empty($totalQuantity[$id])?$totalQuantity[$id]:''}} &nbsp;{{$target['unit_name']}}</td>
                                <td class="bold text-right font-size-11" colspan="{{3+count($rwParameter[$id])}}">${{ Helper::numberFormat2Digit($grandTotal) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @elseif($target['format'] == '2')
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="vcenter font-size-11">@lang('label.DESCRIPTION_OF_GOODS')</th>
                                <th class="vcenter font-size-11">@lang('label.HS_CODE')</th>
                                <th class="vcenter font-size-11">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                <th class="vcenter font-size-11">@lang('label.CORE_AND_DIA')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.GSM')</th>
                                @if(!empty($rwParameter[$id]))
                                @foreach($rwParameter[$id] as $rwId=>$rwName)
                                <th class="text-center vcenter font-size-11">@lang('label.RW')&nbsp;({{$rwName}})</th>
                                @endforeach
                                @endif
                                <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')&nbsp;($)</th>
                                <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')&nbsp;($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">
                                    <span class="bold">{{$target['product_name']}}</span><br/>
                                    <span class="bold"> @lang('label.BRAND'):</span> <span>{{$target['brand_name']}}</span><br/>
                                    @if(!empty($target['grade_name']))
                                    <span class="bold">@lang('label.GRADE'):</span> <span>{{$target['grade_name']}}</span>
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">
                                    @if(!empty($hsCodeArr[$id]))
                                    @if(is_array($hsCodeArr[$id]))
                                    <?php
                                    $lastValue = end($hsCodeArr[$id]);
                                    ?>
                                    @foreach($hsCodeArr as $key => $hsCode )
                                    @foreach($hsCode as $val)
                                    {{!empty($val)?$val:''}}
                                    @if($lastValue !=$val)
                                    <span>,</span>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @else
                                    {{!empty($hsCodeArr[$id])?$hsCodeArr[$id]:''}}
                                    @endif
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['country_name']}}</td>
                                <td class="vcenter font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['core_and_dia']}}&nbsp;{{!empty($rwInfo[$id])?$rwInfo[$id]:''}}</td>



                                @if(!empty($target['gsm_details']))
                                <?php
                                $i = 0;
                                ?>
                                @foreach($target['gsm_details'] as $gsmId=>$gsmVal)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }

                                $j = 0;
                                ?>

                                <td class="vcenter text-right font-size-11" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                    {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                                </td>

                                @foreach($gsmVal as $values)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                @if(!empty($rwParameter[$id]))
                                @foreach($rwParameter[$id] as $rwId=>$rwName)
                                <td class="vcenter text-right font-size-11">
                                    {{!empty($values[$rwId])?$values[$rwId]:''}}
                                </td>
                                @endforeach
                                @endif
                                <td class="vcenter text-right font-size-11">
                                    {{!empty($values['quantity'])?$values['quantity']:''}}
                                </td>


                                <!--unit Price and Total price-->
                                @if($j == 0 && $i==0)
                                <td class="vcenter text-right font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">${{$target['unit_price']}}</td>
                                <td class="vcenter text-right font-size-11" rowspan="{{$gsmDataCountSum[$id]}}">${{$target['total_price']}}</td>
                                @endif
                                <!--End of td unit Price and Total price-->
                                <?php
                                if (($j + 1) < count($gsmVal)) {
                                    echo '</tr>';
                                }
                                $j++;
                                ?>
                                @endforeach

                                <?php
                                if (($i + 1) < count($target['gsm_details'])) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>

                                @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td class="bold text-right font-size-11" colspan="{{5+count($rwParameter[$id])}}">@lang('label.TOTAL')</td>
                                <td class="bold text-right font-size-11">{{!empty($totalQuantity[$id])?$totalQuantity[$id]:''}} &nbsp;{{$target['unit_name']}}</td>
                                <td class="bold text-right font-size-11">${{$target['unit_price']}}</td>
                                <td class="bold text-right font-size-11">${{$target['total_price']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
                @endforeach
                @endif
                <!--ENDOF RW BREAKDOWN DATA-->

            </div>
            @if($poInfo->summary_status == '1')
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="font-size-11">@lang('label.PRODUCT')</th>   
                                <th class="font-size-11">@lang('label.REALIZATION_PRICE')</th>   
                                <th class="font-size-11">@lang('label.KONITA_CMSN')</th>   
                                <th class="font-size-11">@lang('label.REBATE_BUYER_COMMISSION')</th>   
                                <th class="font-size-11">@lang('label.TOTAL_PRICE')</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($summaryArr))
                            @foreach($summaryArr as $inquiryDetailsId=>$item)
                            <tr>
                                <td class="vcenter font-size-11 width-250">
                                    <div class="width-inherit">
                                        <span>{{$item['product_name']}}</span><br/>
                                        <span>@lang('label.BRAND') :{{$item['brand_name']}}</span><br/>
                                        <span>@lang('label.GRADE') :{{!empty($item['grade_name'])?$item['grade_name']:''}}</span><br/>
                                        <span>@lang('label.GSM') :{{!empty($item['gsm'])?$item['gsm']:''}}</span>
                                    </div>
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['realization_price'])?$poSummaryArr[$inquiryDetailsId]['realization_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['konita_commission'])?$poSummaryArr[$inquiryDetailsId]['konita_commission']:0}}&nbsp;/{{$item['unit_name']}}</span>

                                </td>
                                <td class="vcenter text-right font-size-11">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission'])?$poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission']:0}}&nbsp;/{{$item['unit_name']}}</span>

                                </td>
                                <td class="vcenter text-right font-size-11">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['unit_price'])?$poSummaryArr[$inquiryDetailsId]['unit_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            <div class="row margin-left-5 margin-bottom-25">
                <div class="col-md-5 font-size-11">
                    <span>@lang('label.NOTE_') :</span><span class="font-size-11 remarks">{!! !empty($poInfo->note)?$poInfo->note:''!!}</span>
                </div>
            </div>
            <!--            <div class="row margin-top-10 margin-left-5">
                            <div class="col-md-5">
                                <span>@lang('label.FOR')</span><br/><br/>
                                <span>
                                    @if(!empty($signatoryInfo->seal))
                                    @if(Request::get('view') == 'pdf')
                                    <img src="{!! base_path() !!}/public/img/signatoryInfo/{{$signatoryInfo->seal }}" style="width:100px; height: 100px;">
                                    @else
                                    <img src="{{URL::to('/')}}/public/img/signatoryInfo/{{$signatoryInfo->seal }}" style="width:100px; height: 100px;">          
                                    @endif
                                    @else
                                    @if(Request::get('view') == 'pdf')
                                    <img src="{!! base_path() !!}/public/img/no_image.png" style="width:100px; height: 100px;">
                                    @else
                                    <img src="{{URL::to('/')}}/public/img/no_image.png" style="width:100px; height: 100px;">
                                    @endif
                                    @endif
                                </span><br/>
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span>
                            </div>
                        </div>-->
            @endif
        </div>
        <!--footer-->
        <table class="table borderless">
            <tr>
                <td class="no-border text-left font-size-11 ">@lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
            </tr>
            <tr>
                <td class="no-border text-left font-size-11 ">@lang('label.PRINT_FOOTER_TITLE_PO')
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