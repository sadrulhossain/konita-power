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
            @if(!empty($piInfo))
            <div class="row">
                <!--COMPANY INFORAMTION-->
                <div class="col-md-12">
                    <!--header img part-->
                    <div>
                        <span> 
                            @if(!empty($supplierInfo->header_image))
                            @if(Request::get('view') == 'pdf')
                            <img src="{!! base_path() !!}/public/uploads/supplier/PIFormat/headerImage/{{$supplierInfo->header_image }}" style="width: 100%; height: 100px;"> 
                            @else
                            <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/headerImage/{{$supplierInfo->header_image }}" style="width: 100%; height: 100px;">
                            @endif
                            @endif
                        </span>
                    </div>
                    <div class="pi-border-style margin-bottom-30 margin-top-10"></div>
                    <!--end of header part-->
                    <div class="col-md-10 margin-bottom-10">
                        <div class="text-center bold font-size-14">
                            <span>@lang('label.PRO_FORMA_INVOICE')</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="vcenter font-size-11">
                                    <span>@lang('label.DATE'):</span>
                                    <span>{{!empty($piInfo->po_date) ? Helper::formatDate($piInfo->po_date) : ''}}</span>
                                </td>

                                <td class="vcenter font-size-11">
                                    <span> @lang('label.PI_NO'): {{!empty($target->order_no)?$target->order_no:''}}</span>
                                </td>
                                <td>
                                    <span>@lang('label.BUYER') @lang('label.PO_NO'): 
                                        {{!empty($piInfo->buyer_po_no) ? $piInfo->buyer_po_no:''}}
                                    </span>
                                </td>
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
                                    <span>{{!empty($buyerOfficeAddress)?$buyerOfficeAddress:''}}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="vcenter bold font-size-11">@lang('label.DEALER_AGENT')</td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">
                                    <span class="bold">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                    <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                    <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                    <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.'.':''}}</span>
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
                                <td class="vcenter bold font-size-11">@lang('label.SHIPPING_MARKS')</td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">{{!empty($piInfo->pre_carrier_name)?$piInfo->pre_carrier_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($piInfo->shipping_terms_name)?$piInfo->shipping_terms_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($piInfo->final_destination)?$piInfo->final_destination:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($piInfo->payment_terms_name)?$piInfo->payment_terms_name:''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($piInfo->delivery_date) ? Helper::formatDate($piInfo->delivery_date):''}}</td> 
                                <td class="vcenter font-size-11">{{!empty($piInfo->shipping_marks) ? $piInfo->shipping_marks:''}}</td> 
                            </tr>
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
                                <th class="vcenter font-size-11">@lang('label.BF')</th>
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


                                <td class="vcenter text-right font-size-11" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                    {{!empty($target['bf_info'][$gsmId])?$target['bf_info'][$gsmId]:''}}
                                </td>


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
                            </tr>
                            <?php
                            $j++;
                            ?>
                            @endforeach
                            </tr>
                            <?php
                            $i++;
                            ?>
                            @endforeach
                            @endif
                            </tr>
                            <tr>
                                <td class="bold text-right font-size-11" colspan="5">@lang('label.TOTAL')</td>
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
                                <th class="vcenter font-size-11">@lang('label.BF')</th>
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
                                    {{!empty($target['bf_info'][$gsmId])?$target['bf_info'][$gsmId]:''}}
                                </td>
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
                            </tr>
                            <?php
                            $j++;
                            ?>
                            @endforeach
                            </tr>
                            <?php
                            $i++;
                            ?>
                            @endforeach
                            @endif
                            </tr>
                            <tr>
                                <td class="bold text-right font-size-11" colspan="{{6+count($rwParameter[$id])}}">@lang('label.TOTAL')</td>
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

            <!--2nd part-->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="vcenter font-size-11">@lang('label.TOTAL_QUANTITY')</th>
                                <th class="vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter font-size-11">@lang('label.TOTAL_INVOICE_VALUE')</th>
                                <th class="vcenter font-size-11">@lang('label.PRICE_FOB')</th>
                                <th class="vcenter font-size-11">@lang('label.FREIGHT_CHARGE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$inquiryDetailsInfo->isEmpty())
                            @foreach($inquiryDetailsInfo  as $item)
                            <tr>
                                <td class="vcenter font-size-11">
                                    <span class="bold">{{$item->productName}}</span><br/>
                                    <span class="bold"> @lang('label.BRAND'):</span> <span>{{$item->brandName}}</span><br/>
                                    @if(!empty($item->gradeName))
                                    <span class="bold">@lang('label.GRADE'):</span> <span>{{$item->gradeName}}</span>
                                    @endif
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    {{$item->quantity}}&nbsp;{{$item->unit_name}}
                                    {{!empty($summaryArr[$item->id]['total_quantity_title'])?'('.$summaryArr[$item->id]['total_quantity_title'].')':''}}
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    <span>@lang('label.USD') {{$item->unit_price}}&nbsp;/{{$item->unit_name}}</span>
                                </td>

                                <td class="vcenter text-right font-size-11">
                                    @lang('label.USD')
                                    {{$item->total_price}}
                                    {{!empty($summaryArr[$item->id]['invoice_value_title'])?'('.$summaryArr[$item->id]['invoice_value_title'].')':''}}
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    @lang('label.USD')
                                    <span>{{!empty($summaryArr[$item->id]['price_fob'])?$summaryArr[$item->id]['price_fob']:''}}</span>
                                </td>
                                <td class="vcenter text-right font-size-11">
                                    @lang('label.USD')
                                    <span>{{!empty($summaryArr[$item->id]['freight_charge'])?$summaryArr[$item->id]['freight_charge']:''}}</span>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12">
                    <table class="table borderless">
                        <tbody>
<!--                            <tr>
                                <td class="vcenter font-size-11">@lang('label.PAYMENT_TERMS')</td>
                                <td class="vcenter font-size-11">:</td>
                                <td class="vcenter font-size-11">
                                    {{!empty($PaymentTermList[$piInfo->payment_terms_id_2])?$PaymentTermList[$piInfo->payment_terms_id_2]:''}}
                                </td>
                            </tr>-->
                            <tr>
                                <td class="vcenter font-size-11">@lang('label.BENEFICIARY_NAME_AND_ADDRESS')</td>
                                <td class="vcenter font-size-11">:</td>
                                <td class="vcenter font-size-11">
                                    <span>{{!empty($supplierInfo->supplier_name)?$supplierInfo->supplier_name:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->country_name)?$supplierInfo->country_name.'.':''}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">@lang('label.BENEFICIARY_BANK')</td>
                                <td class="vcenter font-size-11">:</td>
                                <td class="vcenter font-size-11">
                                    <span>@lang('label.BANK_NAME')&nbsp;-&nbsp;{{!empty($piInfo->beneficiaryBank_name)?$piInfo->beneficiaryBank_name:''}}</span><br/>
                                    <span>@lang('label.ACCOUNT_NO')&nbsp;-&nbsp;{{!empty($piInfo->account_no)?$piInfo->account_no:''}}</span><br/>
                                    <span>@lang('label.CUSTOMER_ID')&nbsp;-&nbsp;{{!empty($piInfo->customer_id)?$piInfo->customer_id:''}}</span><br/>
                                    <span>@lang('label.BRANCH_ADDRESS')&nbsp;-&nbsp;{{!empty($piInfo->branch)?$piInfo->branch:''}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">@lang('label.REMARKS')</td>
                                <td class="vcenter font-size-11">:</td>
                                <td class="vcenter font-size-11 remarks">
                                    {!! !empty($piInfo->remarks)?$piInfo->remarks:''!!}    
                                </td>
                            </tr>
                            <tr>
                                <td class="vcenter font-size-11">@lang('label.LATEST_DATA_OF_SHIPMENT')</td>
                                <td class="vcenter font-size-11">:</td>
                                <td class="vcenter font-size-11">
                                    {{!empty($piInfo->latest_date_shipment) ? Helper::formatDate($piInfo->latest_date_shipment) : ''}}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--endof 2nd part-->

            <div class="row margin-top-10 margin-bottom-25">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="margin-bottom-10">
                            @if(!empty($supplierInfo->signature_image))
                            @if(Request::get('view') == 'pdf')
                            <img src="{!! base_path() !!}/public/uploads/supplier/PIFormat/signatureImage/{{$supplierInfo->signature_image }}" style="width:250px; height: 150px;">
                            @else
                            <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/signatureImage/{{$supplierInfo->signature_image }}" style="width:250px; height: 150px;">          
                            @endif

                            @endif      
                        </div>
                        <span class="border-top-signature margin-left-30 font-size-11">
                            @lang('label.SELLERS_SIGNATURE')  
                        </span>
                    </div>
                    <div class="col-md-6 text-right">
                        <span class="border-top-signature margin-right-30 font-size-11">
                            @lang('label.BUYERS_SIGNATURE') 
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <table class="table borderless">
            <tr>
                <td class="no-border text-left font-size-11">@lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
            </tr>
            <tr>
                <td class="no-border text-left font-size-11">@lang('label.PRINT_FOOTER_TITLE_PI')
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>