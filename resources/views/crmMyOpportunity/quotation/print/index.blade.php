<?php
$basePath = URL::to('/');
if (Request::get('view') == 'pdf') {
    $basePath = base_path();
}
?>

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
        @if($request->view == 'print' || $request->view == 'pdf')
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12" style="margin-right: 20px">
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


                    <div class="row margin-top-10">
                        <div class="col-md-12 text-center">
                            <span class="bold uppercase inv-border-bottom header">@lang('label.QUOTATION')</span>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td rowspan="3" width="60%">
                                            <span class="bold font-size-11">@lang('label.QUOTATION_FOR')</span><br/>
                                            <span class="font-size-11">@lang('label.COMPANY_NAME') : </span>
                                            <?php
                                            if ($target->buyer_has_id == '0') {
                                                $buyer = $target->buyer;
                                            } elseif ($target->buyer_has_id == '1') {
                                                $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                                            }
                                            ?>
                                            <span class="font-size-11">
                                                {!! $buyer ?? '' !!}
                                            </span><br/>
                                            <span class="font-size-11">@lang('label.ADDRESS') : </span>
                                            <span class="font-size-11">{!! $target->address ?? '' !!}</span>

                                            @if(!empty($attentionList[$quotationInfo->attention_id]))
                                            @if($quotationInfo->attention_id != 'N/A')
                                            <br/>
                                            <span class="font-size-11">@lang('label.ATTENTION') : </span>
                                            <span class="font-size-11">{!! $attentionList[$quotationInfo->attention_id] !!}</span>
                                            @endif
                                            @endif
                                        </td>
                                        <td class="vcenter" width="40%">
                                            <span class="font-size-11">@lang('label.DATE') : </span>
                                            <span class="font-size-11">
                                                {!! !empty($quotationInfo->quotation_date) ? Helper::formatDate($quotationInfo->quotation_date) : date('d F Y') !!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter" width="40%">
                                            <span class="font-size-11">@lang('label.QUOTATION_NO') : </span>
                                            <span class="font-size-11 bold">{!! $quotationNo !!}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter" width="40%">
                                            <span class="font-size-11">@lang('label.QUOTATION_VALID_TILL') : </span>
                                            <span class="font-size-11">
                                                {!! !empty($quotationInfo->quotation_valid_till) ? Helper::formatDate($quotationInfo->quotation_valid_till) : '' !!}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.RESPONSIBLE_AGENT') : </span>
                                            <span class="font-size-11">
                                                {!! $responsibleAgent->name ?? '' !!}
                                            </span>
                                        </td>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.DESIGNATION') : </span>
                                            <span class="font-size-11">
                                                {!! $responsibleAgent->designation ?? '' !!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.EMAIL') : </span>
                                            <span class="font-size-11">
                                                {!! $responsibleAgent->email ?? '' !!}
                                            </span>
                                        </td>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.CONTACT_NO') : </span>
                                            <span class="font-size-11">
                                                {!! $responsibleAgent->contact_no ?? '' !!}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.ORIGIN')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.GSM')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                                        @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productDataList))
                                    <?php $sl = 0; ?>
                                    @foreach($productDataList as $pKey => $pInfo)
                                    <?php
                                    //product
                                    if ($pInfo['product_has_id'] == '0') {
                                        $product = $pInfo['product'] ?? '';
                                    } elseif ($pInfo['product_has_id'] == '1') {
                                        $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                    }
                                    //brand
                                    if ($pInfo['brand_has_id'] == '0') {
                                        $brand = $pInfo['brand'] ?? '';
                                    } elseif ($pInfo['brand_has_id'] == '1') {
                                        $brand = !empty($brandList[$pInfo['product']][$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['product']][$pInfo['brand']] : '';
                                    }
                                    //grade
                                    if ($pInfo['grade_has_id'] == '0') {
                                        $grade = $pInfo['grade'] ?? '';
                                    } elseif ($pInfo['grade_has_id'] == '1') {
                                        $grade = !empty($gradeList[$pInfo['product']][$pInfo['brand']][$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['product']][$pInfo['brand']][$pInfo['grade']] : '';
                                    }

                                    //Origin
                                    $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                    $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                    $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                                        <td class="vcenter font-size-11">{!! $product ?? __('label.N_A') !!}</td>
                                        <td class="vcenter font-size-11">{!! $brand ?? __('label.N_A') !!}</td>
                                        <td class="vcenter font-size-11">{!! $grade ?? __('label.N_A') !!}</td>
                                        <td class="text-center vcenter font-size-11">{!! $country ?? __('label.N_A') !!}</td>
                                        <td class="vcenter font-size-11">{!! $pInfo['gsm'] ?? '' !!}</td>
                                        <td class="text-right vcenter font-size-11">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                        <td class="text-right vcenter font-size-11">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                        @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                        <td class="text-right vcenter font-size-11">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                    <tr>
                                        <td class="vcenter bold text-right font-size-11" colspan="8">@lang('label.SUBTOTAL')</td>
                                        <td class="vcenter bold text-right sub-total font-size-11">${!! !empty($subtotal) ? Helper::numberFormat2Digit($subtotal) : Helper::numberFormat2Digit(0) !!}</td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr>
                                        <td class="vcenter bold font-size-11" colspan="{!! (empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0') ? 7 : 6 !!}">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PAYMENT_TERMS') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.SHIPPING_TERMS') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PORT_OF_LOADING') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PORT_OF_DISCHARGE') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_LEAD_TIME') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.CARRIER') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.ESTIMATED_SHIPMENT_DATE') </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($quotationTermArr))
                                    <?php $sl = 0; ?>
                                    @foreach($quotationTermArr as $qkey => $qtInfo)

                                    <?php
                                    $product = $brand = $grade = '';
                                    if (!empty($productDataList[$qkey])) {
                                        if ($productDataList[$qkey]['product_has_id'] == '0') {
                                            $product = $productDataList[$qkey]['product'] ?? '';
                                        } elseif ($productDataList[$qkey]['product_has_id'] == '1') {
                                            $product = !empty($productList[$productDataList[$qkey]['product']]) && $productDataList[$qkey]['product'] != 0 ? $productList[$productDataList[$qkey]['product']] : '';
                                        }
                                        //brand
                                        if ($productDataList[$qkey]['brand_has_id'] == '0') {
                                            $brand = $productDataList[$qkey]['brand'] ?? '';
                                        } elseif ($productDataList[$qkey]['brand_has_id'] == '1') {
                                            $brand = !empty($brandList[$productDataList[$qkey]['product']][$productDataList[$qkey]['brand']]) && $productDataList[$qkey]['brand'] != 0 ? $brandList[$productDataList[$qkey]['product']][$productDataList[$qkey]['brand']] : '';
                                        }
                                        //grade
                                        if ($productDataList[$qkey]['grade_has_id'] == '0') {
                                            $grade = $productDataList[$qkey]['grade'] ?? '';
                                        } elseif ($productDataList[$qkey]['grade_has_id'] == '1') {
                                            $grade = !empty($gradeList[$productDataList[$qkey]['product']][$productDataList[$qkey]['brand']][$productDataList[$qkey]['grade']]) && $productDataList[$qkey]['grade'] != 0 ? $gradeList[$productDataList[$qkey]['product']][$productDataList[$qkey]['brand']][$productDataList[$qkey]['grade']] : '';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter font-size-11" rowspan="2">{{ ++$sl }}</td>
                                        <td colspan="7" class=" vcenter font-size-11">
                                            @lang('label.PRODUCT_NAME'): <span class="bold">{!! $product ?? '' !!}</span> | @lang('label.BRAND_NAME'): <span class="bold">{!! $brand ?? '' !!}</span> | @lang('label.GRADE_NAME'): <span class="bold">{!! $grade ?? '' !!}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $paymentTermList[$qtInfo['payment_term_id']] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $shippingTermList[$qtInfo['shipping_term_id']] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $qtInfo['port_of_loading'] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $qtInfo['port_of_discharge'] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! !empty($qtInfo['total_lead_time']) ? $qtInfo['total_lead_time'] . ' ' . __('label.DAY_S') : '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $preCarrierList[$qtInfo['pre_carrier_id']] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! !empty($qtInfo['estimated_shipment_date']) ? Helper::formatDate($qtInfo['estimated_shipment_date']) : '' !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @if(!empty($productDataList))

                                    @foreach($productDataList as $pKey => $pInfo)
                                    <?php
                                    //product
                                    if ($pInfo['product_has_id'] == '0') {
                                        $product = $pInfo['product'] ?? '';
                                    } elseif ($pInfo['product_has_id'] == '1') {
                                        $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                    }
                                    //brand
                                    if ($pInfo['brand_has_id'] == '0') {
                                        $brand = $pInfo['brand'] ?? '';
                                    } elseif ($pInfo['brand_has_id'] == '1') {
                                        $brand = !empty($brandList[$pInfo['product']][$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['product']][$pInfo['brand']] : '';
                                    }
                                    //grade
                                    if ($pInfo['grade_has_id'] == '0') {
                                        $grade = $pInfo['grade'] ?? '';
                                    } elseif ($pInfo['grade_has_id'] == '1') {
                                        $grade = !empty($gradeList[$pInfo['product']][$pInfo['brand']][$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['product']][$pInfo['brand']][$pInfo['grade']] : '';
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter font-size-11" rowspan="2">{{ $loop->index+1 }}</td>
                                        <td colspan="7" class=" vcenter font-size-11">
                                            @lang('label.PRODUCT_NAME'): <span class="bold">{!! $product ?? '' !!}</span> | @lang('label.BRAND_NAME'): <span class="bold">{!! $brand ?? '' !!}</span> | @lang('label.GRADE_NAME'): <span class="bold">{!! $grade ?? '' !!}</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $paymentTermList[$quotationInfo->payment_term_id] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $shippingTermList[$quotationInfo->shipping_term_id] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $quotationInfo->port_of_loading ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $quotationInfo->port_of_discharge ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! !empty($quotationInfo->total_lead_time) ? $quotationInfo->total_lead_time . ' ' . __('label.DAY_S') : '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! $preCarrierList[$quotationInfo->pre_carrier_id] ?? '' !!}
                                        </td>
                                        <td class="text-center vcenter font-size-11">
                                            {!! !empty($quotationInfo->estimated_shipment_date) ? Helper::formatDate($quotationInfo->estimated_shipment_date) : '' !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endif
                                </tbody> 
                            </table>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th class="vcenter font-size-11">@lang('label.ADDITIONAL_NOTES')</th>
                                    </tr>
                                    <tr>
                                        <td class="vcenter font-size-11">{!! $quotationInfo->note ?? '<p>none</p>' !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>     
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table borderless">
                                <tr>
                                    <td class="no-border text-left font-size-11">
                                        @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="no-border text-left font-size-11">@lang('label.PRINT_FOOTER_TITLE_QUOTATION')
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
        @endif
    </body>
</html>