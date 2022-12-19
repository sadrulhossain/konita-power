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
                    <span class="bold uppercase inv-border-bottom header">@lang('label.REQUEST_FOR_QUOTATION')</span>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <table class="table no-border margin-bottom-0">
                            @if(Auth::user()->group_id != '0')
                            <tr class="no-border">
                                <td class="bold no-border" width="1%"></td>
                                <td class="bold no-border" width="25%">@lang('label.BUYER')</td>
                                <td width="50%" class="no-border">{!! !empty($buyerInfo->name) ? $buyerInfo->name : __('label.N_A') !!}</td>
                            </tr>    
                            <tr class="no-border">
                                <td class="bold no-border" width="1%"></td>
                                <td class="bold no-border" width="25%">@lang('label.ADDRESS')</td>
                                <td width="50%" class="no-border">{!! !empty($buyerInfo->head_office_address) ? $buyerInfo->head_office_address :__('label.N_A') !!}</td>
                            </tr>   
                            @endif
                            <tr class="no-border">
                                <td class="no-border bold" width="1%"></td>
                                <td class="no-border bold" width="25%">@lang('label.QUOTATION_REQUEST')</td>
                                <td width="50%" class="no-border text-justify">
                                    {!! !empty($quotationInfoArr->description)?$quotationInfoArr->description:__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr class="no-border">
                                <td class="bold no-border" width="1%"></td>
                                <td class="bold no-border" width="25%">@lang('label.STATUS')</td>
                                <td width="50%" class="no-border">
                                    @if($quotationInfoArr->read_status == '1')
                                    <span class="label label-sm label-blue-madison">{!! __('label.READ') !!}</span>
                                    @else
                                    <span class="label label-sm label-grey-cascade">{!! __('label.PENDING') !!}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div> 
            <div class="row div-box-default">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 border-bottom-1-green-seagreen">
                            <h4><strong>@lang('label.PRODUCT_INFORMATION')</strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 margin-top-20">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                            <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                            <th class="text-center vcenter width-50">@lang('label.GSM')</th>
                                            <th class="text-center vcenter width-50">@lang('label.QUANTITY')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($productArr))
                                        <?php $sl = 0; ?>
                                        @foreach($productArr as $pKey => $pInfo)
                                        <?php
                                        $product = !empty($productListArr[$pInfo['product_id']]) && $pInfo['product_id'] != 0 ? $productListArr[$pInfo['product_id']] : '';
                                        ?>
                                        <tr>
                                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                                            <td class="vcenter">{!! $product ?? __('label.N_A') !!}</td>
                                            <td class="vcenter">{!! $pInfo['gsm'] ?? '' !!}</td>
                                            <td class="text-right vcenter width-50">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') .' '. $pInfo['unit']!!}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="vcenter" colspan="4">@lang('label.NO_DATA_FOUND')</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
        @endif
    </body>
</html>