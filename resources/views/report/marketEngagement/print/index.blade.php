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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.MARKET_ENGAGEMENT')
                            <?php
                            echo ' (' . Helper::formatDate($oneYearAgo) . ' - ' . Helper::formatDate($today) . ')';
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.TOTAL_NUM_OF_BUYERS_FROM_WHOM_DEMAND_COLLECTED')}} : <strong>{{ !empty($importBuyerList)?count($importBuyerList):0 }} |</strong> 
                            {{__('label.TOTAL_NUM_OF_BUYERS_TO_WHOM_PRODUCTS_SOLD')}} : <strong>{{ !empty($salesBuyerList)?count($salesBuyerList):0 }} </strong>
                        </h5>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.MARKET_VOLUME')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.OUR_VOLUME')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.ENGAGEMENT')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.OPPORTUNITY')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($productList))
                            <?php $sl = 0; ?>
                            @foreach($productList as $productId => $productName)
                            <?php
                            $importVolAlignment = 'text-center';
                            $importVol = __('label.N_A');
                            if (!empty($importVolumeArr[$productId])) {
                                $importVolAlignment = 'text-right';
                                $importVol = Helper::numberFormat2Digit($importVolumeArr[$productId]) . ' ' . ($productUnitList[$productId] ?? __('label.UNIT'));
                            }

                            $salesVolAlignment = 'text-center';
                            $salesVol = __('label.N_A');
                            if (!empty($salesVolumeArr[$productId])) {
                                $salesVolAlignment = 'text-right';
                                $salesVol = Helper::numberFormat2Digit($salesVolumeArr[$productId]) . ' ' . ($productUnitList[$productId] ?? __('label.UNIT'));
                            }
                            ?>
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $productName ?? '' !!}</td>
                                <td class="{{$importVolAlignment}} vcenter">{!! $importVol !!}</td>
                                <td class="{{$salesVolAlignment}} vcenter">{!! $salesVol !!}</td>
                                <td class="text-right vcenter">{!! Helper::numberFormat2Digit($engagementArr[$productId]) !!}%</td>
                                <td class="text-right vcenter">{!! Helper::numberFormat2Digit($opportunityArr[$productId]) !!}%</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-danger vcenter">@lang('label.NO_DATA_FOUND')</td>
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