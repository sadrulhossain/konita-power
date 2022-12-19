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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.BUYER_SUMMARY_REPORT')
                        </span>
                    </div>
                </div>
            </div>

            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.BUYER_CATEGORY')}} : <strong>{{  !empty($buyerCategoryList[Request::get('buyer_category_id')]) && Request::get('buyer_category_id') != 0 ? $buyerCategoryList[Request::get('buyer_category_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.PRODUCT_CATEGORY')}} : <strong>{{  !empty($productCategoryList[Request::get('product_category_id')]) && Request::get('product_category_id') != 0 ? $productCategoryList[Request::get('product_category_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.PRODUCT')}} : <strong>{{  !empty($productList[Request::get('product_id')]) && Request::get('product_id') != 0 ? $productList[Request::get('product_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.BRAND')}} : <strong>{{  !empty($brandList[Request::get('brand_id')]) && Request::get('brand_id') != 0 ? $brandList[Request::get('brand_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.MACHINE_TYPE')}} : <strong>{{  !empty($machineTypeList[Request::get('machine_type_id')]) && Request::get('machine_type_id') != 0 ? $machineTypeList[Request::get('machine_type_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.BUSINESS_STATUS')}} : <strong>{{  !empty($businessStatusList[Request::get('business_status_id')]) ? $businessStatusList[Request::get('business_status_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.SALES_STATUS')}} : <strong>{{  !empty($salesStatusList[Request::get('sales_status_id')]) ? $salesStatusList[Request::get('sales_status_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.COUNTRY')}} : <strong>{{  !empty($countryList[Request::get('country_id')]) && Request::get('country_id') != 0 ? $countryList[Request::get('country_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.DIVISION')}} : <strong>{{  !empty($divisionList[Request::get('division_id')]) && Request::get('division_id') != 0 ? $divisionList[Request::get('division_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.BUYER')}} : <strong>{{  !empty($buyerSearchList[Request::get('buyer_id')]) && Request::get('buyer_id') != 0 ? $buyerSearchList[Request::get('buyer_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.TOTAL_NO_OF_BUYERS')}} : <strong>{{  !empty($buyerInfoArr) ? count($buyerInfoArr) : 0 }} </strong> 
                        </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.LOGO')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.BUYER_NAME')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.COUNTRY')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.DIVISION')</th>
                                <th class="text-center vcenter" colspan="2">@lang('label.PRIMARY_CONTACT_PERSON')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.STATUS')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.IDLE_TIME')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.LATEST_BUYER_FOLLOWUP')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.NO_OF_RELATED_SALES_PERSONS')</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter">@lang('label.NAME')</th>
                                <th class="text-center vcenter">@lang('label.PHONE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($buyerInfoArr))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($buyerInfoArr as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter" width="40px">
                                    @if (!empty($item['logo']) && File::exists('public/uploads/buyer/' . $item['logo']))
                                    <img alt="{{$item['name']}}" src="{{$basePath}}/public/uploads/buyer/{{$item['logo']}}" width="40" height="40"/>
                                    @else
                                    <img alt="unknown" src="{{$basePath}}/public/img/no_image.png" width="40" height="40"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $item['name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $item['country_name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $item['division_name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $contactArr[$item['id']]['name'] ?? '' !!}</td>

                                @if(is_array($contactArr[$item['id']]['phone']))
                                <td class="vcenter">
                                    <?php
                                    $lastValue = end($contactArr[$item['id']]['phone']);
                                    ?>
                                    @foreach($contactArr[$item['id']]['phone'] as $key => $contact)
                                    {{$contact}}
                                    @if($lastValue !=$contact)
                                    <span>,</span>
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td class="vcenter">{!! $contactArr[$item['id']]['phone'] ?? '' !!}</td>
                                @endif
                                <td class="text-center vcenter">
                                    @if($item['status'] == '1')
                                    <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                    @else
                                    <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                    @endif
                                </td>
                                <td class="vcenter text-center">{!! $idleTimeArr[$item['id']] ?? '' !!}</td>
                                <td class="text-center vcenter">
                                    @if(array_key_exists($item['id'], $latestFollowupArr))
                                    @if($latestFollowupArr[$item['id']]['status'] == '1')
                                    <span class="label label-sm label-yellow">@lang('label.NORMAL')</span>
                                    @elseif($latestFollowupArr[$item['id']]['status'] == '2')
                                    <span class="label label-sm label-green-seagreen">@lang('label.HAPPY')</span>
                                    @elseif($latestFollowupArr[$item['id']]['status'] == '3')
                                    <span class="label label-sm label-red-soft">@lang('label.UNHAPPY')</span>
                                    @endif
                                    @else
                                    <span class="label label-sm label-gray-mint">@lang('label.NO_FOLLOWUP_YET')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter">{!! !empty($salesPersonToBuyerCountList[$item['id']]) ? $salesPersonToBuyerCountList[$item['id']] : 0 !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="{!! 11 !!}">@lang('label.NO_DATA_FOUND')</td>
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