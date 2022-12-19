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
        <div class="row">
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
            <div class="col-md-12">
                <h4 class="text-center bold uppercase">@lang('label.INVOICE')</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class=" col-md-12 margin-top-10 margin-bottom-10">
                                <div class="margin-bottom-20">
                                    <span>@lang('label.DATE'): {{!empty($invoiceInfo->date)?Helper::formatdate($invoiceInfo->date):''}}</span><br/>
                                    <span>@lang('label.INVOICE_NO'): {{!empty($invoiceInfo->invoice_no)?$invoiceInfo->invoice_no:''}}</span><br/>
                                </div>
                                <div>
                                    <span>@lang('label.ATTN'):</span><br/>
                                    <span>{{!empty($invoiceInfo->supplier_contact_person)?$invoiceInfo->supplier_contact_person:''}}</span><br/>
                                    <span class="bold">{{!empty($supplierInfo->name)?$supplierInfo->name:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->countryName)?$supplierInfo->countryName:''}}</span><br/>   
                                </div>
                                <div class="margin-top-20">
                                    <span>{{!empty($invoiceInfo->subject)?$invoiceInfo->subject:''}}</span> 
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="vcenter">@lang('label.SL_NO')</th>
                                                <th class="vcenter">@lang('label.BUYER')</th>
                                                <th class="vcenter text-center">@lang('label.ORDER_NO')</th>
                                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                <!--<th class="vcenter text-center">@lang('label.COMMISSION')</th>-->
                                                <th class="vcenter text-center">@lang('label.TOTAL')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($orderNoHistoryArr))
                                            <?php
                                            $sl = 0;
                                            ?>
                                            @foreach($orderNoHistoryArr as $inqueryId=>$item)
                                            <tr>
                                                <td class="vcenter text-center">{{++$sl}}</td>  
                                                <td class="vcenter">{{!empty($orderWiseBuyerList[$inqueryId])?$orderWiseBuyerList[$inqueryId]:''}}</td>  
                                                <td class="vcenter text-center">{{!empty($orderNoList[$inqueryId])?$orderNoList[$inqueryId]:''}}</td> 
                                                <td class="vcenter text-right">
                                                    {{!empty($item['qty'])?$item['qty']:null}}&nbsp;@lang('label.UNIT')
                                                </td>  
<!--                                                <td class="vcenter text-right">
                                                    <span>$</span>{{!empty($item['konita_cmsn'])?$item['konita_cmsn']:0}}&nbsp;<span>/</span>@lang('label.UNIT')
                                                </td>  -->
                                                <td class="vcenter text-right">
                                                    <span>$</span>{{!empty($item['total_konita_cmsn'])?Helper::numberFormat2Digit($item['total_konita_cmsn']):Helper::numberFormat2Digit(0)}}  
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!--sub_total-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4">@lang('label.SUBTOTAL')</td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span>{{!empty($invoiceInfo->sub_total)?Helper::numberFormat2Digit($invoiceInfo->sub_total):Helper::numberFormat2Digit(0)}}
                                                </td>
                                            </tr>
                                            <!--admon cost-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4">@lang('label.ADMIN_COST')</td>
                                                <td class="vcenter bold text-right">
                                                    <span>- $</span>{{!empty($invoiceInfo->admin_cost)?Helper::numberFormat2Digit($invoiceInfo->admin_cost):Helper::numberFormat2Digit(0)}}
                                                </td>
                                            </tr>
                                            <!--net_receivable-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4">@lang('label.NET_RECEIVABLE')</td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span>{{!empty($invoiceInfo->net_receivable)?Helper::numberFormat2Digit($invoiceInfo->net_receivable):Helper::numberFormat2Digit(0)}}
                                                </td>
                                            </tr>
                                            <!--gift-->
                                            <tr>
                                                <?php
                                                $gift = '--';
                                                $textAlignment = 'center';
                                                if (($invoiceInfo->gift != 0.00) && (!empty($invoiceInfo->gift))) {
                                                    $gift = '$' . Helper::numberFormat2Digit($invoiceInfo->gift);
                                                    $textAlignment = 'right';
                                                }
                                                ?>
                                                <td class="vcenter text-right bold" colspan="4">@lang('label.GIFT')&nbsp;{{!empty($invoiceInfo->gift_title)?'('.$invoiceInfo->gift_title.')':''}}</td>
                                                <td class="vcenter bold text-{{ $textAlignment }}">{!! $gift !!}</td>
                                            </tr>
                                            <!--total amount-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4">@lang('label.TOTAL_AMOUNT')</td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span>{{!empty($invoiceInfo->total_amount)?Helper::numberFormat2Digit($invoiceInfo->total_amount):Helper::numberFormat2Digit(0)}}
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--koniat bank information-->
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="col-md-6">
                            <div>
                                <span class="bold">@lang('label.BANK_INFORMATION')</span>
                            </div>
                            <div>
                                <span class="bold">@lang('label.BANK_NAME'): </span>{{!empty($konitaBankInfo->bank_name)?$konitaBankInfo->bank_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NO'): </span>{{!empty($konitaBankInfo->account_no)?$konitaBankInfo->account_no:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NAME'): </span>{{!empty($konitaBankInfo->account_name)?$konitaBankInfo->account_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.BRANCH'): </span>{{!empty($konitaBankInfo->branch)?$konitaBankInfo->branch:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.SWIFT'): </span>{{!empty($konitaBankInfo->swift)?$konitaBankInfo->swift:''}}
                            </div>

                            <!--Signatory part-->
                            <!--                            <div class="margin-top-20">
                                                            <h5>@lang('label.REGARDS')</h5>
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
                                                            <span>{{!empty($signatoryInfo->name)?$signatoryInfo->name:''}}</span><br/> 
                                                            <span>{{!empty($signatoryInfo->designation)?$signatoryInfo->designation:''}}</span>
                                                        </div>-->
                        </div>
                    </div>
                </div>
                <!--end of koniat bank information-->
            </div>
        </div>
        <table class="table borderless">
            <tr>
                <td class="no-border text-left ">@lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
            </tr>
            <tr>
                <td class="no-border text-left ">@lang('label.PRINT_FOOTER_TITLE_INVOICE')</td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>