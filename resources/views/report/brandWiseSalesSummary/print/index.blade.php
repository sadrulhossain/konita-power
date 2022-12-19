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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.SALES_SUMMARY_REPORT')
                            <?php
                            echo ' (' . Helper::formatDate($fromDate) . ' - ' . Helper::formatDate($toDate) . ')';
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                        <thead>
                            <tr>
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                                <th class="text-center vcenter">@lang('label.SALES_VOLUME')</th>
                                <th class="text-center vcenter">@lang('label.NET_INCOME')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($brandList))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($brandList as $brandId => $brandName)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter" width="40px">
                                    @if(!empty($brandLogoList[$brandId]) && File::exists('public/uploads/brand/'. $brandLogoList[$brandId]))
                                    <img class="pictogram-min-space tooltips" width="40" height="40" src="{{$basePath}}/public/uploads/brand/{{ $brandLogoList[$brandId] }}" alt="{{ $brandName }}" title="{{ $brandName }}"/>
                                    @else 
                                    <img width="40" height="40" src="{{$basePath}}/public/img/no_image.png" alt="{{$brandName}}"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $brandName !!}</td>
                                <td class="text-right vcenter">{!! (!empty($salesSummaryArr[$brandId]['volume']) ? Helper::numberFormat2Digit($salesSummaryArr[$brandId]['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</td>
                                <td class="text-right vcenter">{!! '$' . (!empty($salesSummaryArr[$brandId]['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr[$brandId]['net_income']) : Helper::numberFormat2Digit(0)) !!}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <th class="text-right vcenter" colspan="3">@lang('label.TOTAL')</th>
                                <th class="text-right vcenter">{!! (!empty($salesSummaryArr['total']['volume']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</th>
                                <th class="text-right vcenter">{!! '$' . (!empty($salesSummaryArr['total']['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['net_income']) : Helper::numberFormat2Digit(0)) !!}</th>
                            </tr>
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="5">@lang('label.NO_DATA_FOUND')</td>
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