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
                                            <span class="font-size-11">
                                                {!! $target->buyer_name ?? '' !!}
                                            </span><br/>
                                            <span class="font-size-11">@lang('label.OFFICE_ADDRESS') : </span>
                                            <span class="font-size-11">{!! $target->office_address ?? '' !!}</span>

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
                                            <span class="font-size-11">{!! $quotationNo !!}</span>
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
                                            <span class="font-size-11">@lang('label.SALES_PERSON') : </span>
                                            <span class="font-size-11">
                                                {!! $target->sales_person_name ?? '' !!}
                                            </span>
                                        </td>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.DESIGNATION') : </span>
                                            <span class="font-size-11">
                                                {!! $target->designation ?? '' !!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.EMAIL') : </span>
                                            <span class="font-size-11">
                                                {!! $target->email ?? '' !!}
                                            </span>
                                        </td>
                                        <td class="vcenter">
                                            <span class="font-size-11">@lang('label.CONTACT_NO') : </span>
                                            <span class="font-size-11">
                                                {!! $target->contact_no ?? '' !!}
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
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.PRODUCT_DESCRIPTION')</th>
                                        <th class="text-center vcenter font-size-11" colspan="2">@lang('label.BRAND')</th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.GRADE')</th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.GSM') </th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.QUANTITY')</th>
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.UNIT_PRICE')</th>
                                        @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                        <th class="text-center vcenter font-size-11" rowspan="2">@lang('label.TOTAL_PRICE')</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="text-center vcenter font-size-11">@lang('label.LOGO')</th>
                                        <th class="text-center vcenter font-size-11">@lang('label.NAME')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$inquiryDetailsArr->isEmpty())
                                    <?php
                                    $sl = 0;
                                    ?>
                                    @foreach($inquiryDetailsArr as $item)

                                    <tr>
                                        <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                                        <td class="text-center vcenter font-size-11">{!! $item->product_name ?? '' !!}</td>
                                        <td class="text-center vcenter font-size-11" width="40px">
                                            @if(!empty($item->logo) && File::exists('public/uploads/brand/' . $item->logo))
                                            <img class="pictogram-min-space tooltips" width="40" height="40" src="{{$basePath}}/public/uploads/brand/{{ $item->logo }}" alt="{{ $item->brand_name}}"/>
                                            @else 
                                            <img width="40" height="40" src="{{$basePath}}/public/img/no_image.png" alt="{{ $item->brand_name}}"/>
                                            @endif
                                        </td>
                                        <td class="text-center vcenter font-size-11">{!! $item->brand_name ?? '' !!}</td>
                                        <td class="text-center vcenter font-size-11">{!! $item->grade_name ?? '' !!}</td>
                                        <td class="text-center vcenter font-size-11">{!! $item->country_of_origin ?? '' !!}</td>
                                        <td class="text-center vcenter font-size-11">{!! $item->gsm ?? '' !!}</td>
                                        <td class="text-right vcenter font-size-11">{!! (!empty($item->quantity) ? Helper::numberFormat2Digit($item->quantity) : Helper::numberFormat2Digit(0)).(!empty($item->unit_name) ? ' /'.$item->unit_name : '') !!}</td>
                                        <td class="text-right vcenter font-size-11">${!! !empty($item->unit_price) ? Helper::numberFormat2Digit($item->unit_price) : Helper::numberFormat2Digit(0) !!}</td>
                                        @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                        <td class="text-right vcenter font-size-11">${!! !empty($item->total_price) ? Helper::numberFormat2Digit($item->total_price) : Helper::numberFormat2Digit(0) !!}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @if(empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0')
                                    <tr>
                                        <td class="vcenter bold text-right" colspan="9">@lang('label.SUBTOTAL')</td>
                                        <td class="vcenter bold text-right">${!! !empty($subtotal) ? Helper::numberFormat2Digit($subtotal) : Helper::numberFormat2Digit(0) !!}</td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr>
                                        <td class="vcenter text-danger font-size-11" colspan="{!! (empty($quotationInfo->remove_total) || $quotationInfo->remove_total == '0') ? 10 : 9 !!}">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                                <tbody>
                                    <tr>
                                        <th class="text-center vcenter font-size-11">@lang('label.PAYMENT_TERMS') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.SHIPPING_TERMS') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PORT_OF_LOADING') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.PORT_OF_DISCHARGE') </th>
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
                                    </tr>
                                </tbody> 
                            </table>
                            <table class="table table-bordered table-hover"> 
                                <tbody>   
                                    <tr>
                                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_LEAD_TIME') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.CARRIER') </th>
                                        <th class="text-center vcenter font-size-11">@lang('label.ESTIMATED_SHIPMENT_DATE') </th>
                                    </tr>
                                    <tr>
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