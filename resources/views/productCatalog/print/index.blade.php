<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
       
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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.PRODUCT_CATALOG')
                        </span>
                    </div>
                </div>
            </div>
            @if(!empty($target->show_all_brands))
            @if(!empty($productInfoArr))
            <div class="row margin-top-10">
                <div class="col-md-12 text-center">
                    <div class="alert alert-info bold">
                        <p>
                            <i class="fa fa-info-circle"></i>
                            @lang('label.ASTERIC_SIGN_REFERS_TO_ALREADY_PURCHASED_BRANDS')
                        </p>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                        <thead>
                            <tr  class="info">
                                <th class="vcenter text-center">@lang('label.SL')</th>
                                <th class="vcenter text-center">@lang('label.PRODUCT')</th>
                                <th class="vcenter text-center" colspan="5">@lang('label.BRAND')</th>
                                <th class="vcenter text-center">@lang('label.PURCHASED_VOLUME')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($productInfoArr))
                            <?php $sl = 0; ?>
                            @foreach($productInfoArr as $productId => $product)
                            <tr>
                                <td class="text-center vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $product['product_name'] ?? __('label.N_A') !!}</td>

                                @if(!empty($product['brand']))
                                <?php $i = 0; ?>
                                @foreach($product['brand'] as $brandId => $brand)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="text-center vcenter" width="30px">
                                    @if(!empty($brand['logo']) && File::exists('public/uploads/brand/' . $brand['logo']))
                                    <img class="pictogram-min-space tooltips" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['brand_name']}}" title="{{ $brand['brand_name'] }}"/>
                                    @else 
                                    <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                    @endif
                                </td>
                                <td class="vcenter">
                                    {!! $brand['brand_name'] ?? __('label.N_A') !!}
                                    @if(!empty($brandWiseVolumeRateArr[$productId]))
                                    @if(array_key_exists($brandId, $brandWiseVolumeRateArr[$productId]))
                                    @if(!empty($target->show_all_brands))
                                    <span class="text-green bold">*</span>
                                    @endif
                                    <br/>
                                    <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) : '0.00'; ?>
                                    <span class="text-green bold">
                                        (@lang('label.PERCENTAGE_OF_TOTAL_PURCHASED_VOLUME', ['percentage' => $percentage]))
                                    </span>
                                    @endif
                                    @endif
                                </td>
                                <td class="vcenter">
                                    {!! $brand['origin'] ?? __('label.N_A') !!}
                                </td>
                                <td class="vcenter">
                                        <div class="width-inherit">
                                            @if(!empty($brand['certificate']))
                                            @foreach($brand['certificate'] as $key => $file)
                                            @if (Request::get('view') == 'print')
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            @else
                                            <div>
                                                <a class="btn btn-sm bg-yellow-casablanca bg-font-yellow-casablanca tooltips" href="{{URL::to('public/uploads/brandCertificate/'.$file)}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW') {{(!empty($certificateArr[$key])) && (!empty($file)) ? $certificateArr[$key].' '.__('label.CERTIFICATE') :'' }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            @endif
                                            <label>&nbsp;</label>
                                            @endforeach
                                            @else
                                            <span class="label label-warning purple-stripe">
                                                @lang('label.N/A')
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="vcenter">
                                        <div class="width-inherit">
                                            @if(!empty($previousDataSheetArr[$productId][$brandId]))
                                            @foreach($previousDataSheetArr[$productId][$brandId] as $dataSheetId => $dataSheet)
                                             @if (Request::get('view') == 'print')
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            @else
                                            <a class="btn btn-sm bg-blue bg-font-blue tooltips " href="{{URL::to('public/uploads/techDataSheet/'.$dataSheet['file'])}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW') {{(!empty($dataSheet['title'])) && (!empty($dataSheet['file'])) ? $dataSheet['title'] :'' }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                            <label>&nbsp;</label>
                                            @endforeach
                                            @else
                                            <span class="label label-warning purple-stripe">
                                                @lang('label.N/A')
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                <?php
                                $volume = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? Helper::numberFormat2Digit($brandWiseVolumeRateArr[$productId][$brandId]['volume']) . (!empty($product['unit']) ? ' ' . $product['unit'] : '') : '--';
                                $volumeAlign = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? 'right' : 'center';
                                $volumeColor = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? 'green' : 'danger';
                                ?>
                                <td class="vcenter text-{{$volumeAlign}}"><span class="text-{{$volumeColor}}">{!! $volume !!}</span></td>
                                <?php
                                if ($i < ($productRowSpanArr[$productId]['brand'] - 1)) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8"> @lang('label.NO_DATA_FOUND')</td>
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