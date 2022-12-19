<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />

        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            @page { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    </head>
    <body>
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
                        <span class="header">@lang('label.BUYER_PROFILE')</span>
                    </div>
                </div>
            </div>

            <div class="row margin-bottom-20 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.BASIC_INFORMATION')</strong></h5>
                </div>
            </div>


            <table class="no-border width-full">
                <tr>
                    <td class="no-border text-center v-top font-size-11" width="30%">
                        @if (!empty($target->logo) && File::exists('public/uploads/buyer/' . $target->logo))
                        <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$target->logo}}" width="150" height="150"/>
                        @else
                        <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="150" height="150"/>
                        @endif
                        @if(!empty($target->name))
                        <h5 class="bold text-center margin-top-10 margin-bottom-20">
                            {!! $target->name . (!empty($target->code) ? ' (' . $target->code . ')' : '') !!}
                        </h5>
                        @endif
                        @if(!empty($latestFollowupArr))
                        @if($latestFollowupArr['status'] == '1')
                        <span class="label text-center bold label-rounded label-sm bold label-yellow">@lang('label.NORMAL')</span>
                        @elseif($latestFollowupArr['status'] == '2')
                        <span class="label text-center bold label-rounded label-sm bold label-green-seagreen">@lang('label.HAPPY')</span>
                        @elseif($latestFollowupArr['status'] == '3')
                        <span class="label text-center bold label-rounded label-sm bold label-red-soft">@lang('label.UNHAPPY')</span>
                        @endif
                        @else
                        <span class="label bold label-rounded label-sm bold label-gray-mint">@lang('label.NO_FOLLOWUP_YET')</span>
                        @endif
                    </td>
                    <td class="no-border text-right v-top font-size-11" width="70%">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.CATEGORY')</td>
                                    <td class="active font-size-11"colspan="5">{!! $target->category ?? __('label.N_A') !!}</td>
                                    <td class="fit font-size-11 bold info">@lang('label.FSC_CERTIFIED')</td>
                                    <td class="active font-size-11"colspan="5">
                                        @if($target->fsc_certified == '1')
                                        <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.COUNTRY')</td>
                                    <td class="active font-size-11"colspan="5">{!! $target->country ?? __('label.N_A') !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.ISO_CERTIFIED')</td>
                                    <td class="active vcenter font-size-11"colspan="5">
                                        @if($target->iso_certified == '1')
                                        <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.DIVISION')</td>
                                    <td class="active font-size-11"colspan="5">{!! $target->division ?? __('label.N_A') !!}</td>

                                    <td class="fit bold info font-size-11">@lang('label.STATUS')</td>
                                    <td class="active vcenter font-size-11"colspan="5">
                                        @if($target->status == '1')
                                        <span class="label label-sm bold label-success">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm bold label-warning">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.DATE_OF_ENTRY')</td>
                                    <td class="active font-size-11"colspan="5">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : __('label.N_A') !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.TYPE')</td>
                                    <td class="active vcenter font-size-11"colspan="5">
                                        @if(!empty($typeArr))
                                        @foreach($typeArr as $type)
                                        @if($type == 1)
                                        <span class="label bold label-sm bold label-yellow-casablanca">@lang('label.BONDED')</span>
                                        @elseif($type == 2)
                                        <span class="label bold label-sm bold label-purple-sharp">@lang('label.COMMERCIAL')</span>
                                        @endif
                                        @endforeach
                                        @else
                                        <span class="label bold label-sm bold label-red-soft">@lang('label.N_A')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.DATE_OF_BUSINESS_STARTED')</td>
                                    <td class="active font-size-11"colspan="5">{!! !empty($businessInitationDate->start) ? Helper::formatDate($businessInitationDate->start) : __('label.N_A') !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.BRAND_OF_MACHINE')</td>
                                    <td class="active font-size-11"colspan="5">{!! $target->machine_brand ?? __('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.HEAD_OFFICE')</td>
                                    <td class="active font-size-11"colspan="11">{!! $target->head_office_address ?? __('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.PRIMARY_FACTORY')</td>
                                    <td class="active font-size-11"colspan="11">
                                        @if(!empty($primaryFactory->name))
                                        <span class="bold">{!! $primaryFactory->name !!}</span>
                                        @if(!empty($primaryFactory->address))
                                        <br/><span>{!! $primaryFactory->address !!}</span>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr  class="info">
                        <th class="vcenter text-center font-size-11">@lang('label.SL')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.NAME')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.DESIGNATION')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.EMAIL')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.PHONE')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.SPECIAL_NOTE')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($contactPersonArr))
                    <?php $sl = 0; ?>
                    @foreach($contactPersonArr as $key => $contact)
                    <tr>
                        <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11">{!! $contact['name'] ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11">{!! !empty($contact['designation']) && !empty($contactDesignationList[$contact['designation']]) ? $contactDesignationList[$contact['designation']] : __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11 text-primary">{!! $contact['email'] ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11">
                            @if(is_array($contact['phone']))

                            <?php
                            $lastValue = end($contact['phone']);
                            ?>
                            @foreach($contact['phone'] as $keyP => $p)
                            {{$p ?? __('label.N_A') }}
                            @if($lastValue !=$p)
                            <span>,</span>
                            @endif
                            @endforeach
                            @else
                            {!! $contact['phone'] ?? __('label.N_A') !!}
                            @endif
                        </td>
                        <td class="vcenter font-size-11">{!! $contact['specoal_note'] ?? __('label.N_A') !!}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="font-size-11" colspan="6"> @lang('label.NO_DATA_FOUND')</td>
                    </tr>                    
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.ACTIVELY_ENGAGED_SALES_PERSON_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr  class="info">
                        <th class="vcenter text-center font-size-11">@lang('label.SL')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.PHOTO')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.NAME')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.EMPLOYEE_ID')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.DESIGNATION')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.EMAIL')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.PHONE')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.TOTAL_ORDER_INVOLVEMENT')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$activelyEngagedSalesPersonArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($activelyEngagedSalesPersonArr as $sp)
                    <tr>
                        <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                        <td class="text-center vcenter font-size-11" width="30px">
                            @if(!empty($sp->photo) && File::exists('public/uploads/user/' . $sp->photo))
                            <img width="30" height="30" src="{{URL::to('/')}}/public/uploads/user/{{$sp->photo}}" alt="{{ $sp->name}}"/>
                            @else
                            <img width="30" height="30" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $sp->name}}"/>
                            @endif
                        </td>
                        <td class="vcenter font-size-11">{!! $sp->name ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11">{!! $sp->employee_id ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11">{!! $sp->designation ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11 text-primary">{!! $sp->email ?? __('label.N_A') !!}</td>
                        <td class="vcenter font-size-11">{!! $sp->phone ?? __('label.N_A') !!}</td>
                        <td class="text-center vcenter font-size-11" width="100px">{!! !empty($activelyEngagedSalesPersonOrderList[$sp->id]) ? $activelyEngagedSalesPersonOrderList[$sp->id] : 0 !!}</td>
<!--                        <td class="text-center vcenter width-100">
                            @if(!empty($activelyEngagedSalesPersonOrderList[$sp->id]))
                            <button class="btn btn-xs bold green-seagreen tooltips vcenter involved-order-list"  
                                    title="@lang('label.CLICK_TO_VIEW_INVOLVED_ORDER_LIST')" 
                                    href="#modalInvolvedOrderList" data-buyer-id="{!! $target->id !!}" 
                                    data-sales-person-id="{!! $sp->id !!}" data-toggle="modal">
                                {!! $activelyEngagedSalesPersonOrderList[$sp->id] !!}
                            </button>
                            @else
                            <span class="label label-sm bold bold label-gray-mint tooltips" title="@lang('label.NO_ORDER_INVOLVEMENT')">{!! 0 !!}</span>
                            @endif
                        </td>-->
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class=" font-size-11" colspan="8"> @lang('label.NO_DATA_FOUND')</td>
                    </tr>                    
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.PRODUCT_INFORMATION')</strong></h5>
                </div>
                @if(!empty($productInfoArr))
                <div class="col-md-12 margin-top-10 text-center">
                    <span class="text-green font-size-11 bold">
                        (@lang('label.ASTERIC_SIGN_REFERS_TO_BRAND_IN_BUSINESS'))
                    </span>
                </div>
                @endif
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr  class="info">
                        <th class="vcenter text-center font-size-11">@lang('label.SL')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.PRODUCT')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.IMPORT_VOLUME')</th>
                        <th class="vcenter text-center font-size-11" colspan="2">@lang('label.BRAND')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.MACHINE_TYPE')</th>
                        <th class="vcenter text-center font-size-11">@lang('label.MACHINE_LENGTH')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($productInfoArr))
                    <?php $sl = 0; ?>
                    @foreach($productInfoArr as $productId => $product)
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $product['product_name'] ?? __('label.N_A') !!}</td>

                        <?php
                        $volume = __('label.N_A');
                        $textAlignment = 'center';
                        if (!empty($importVolArr[$productId]['volume']) && $importVolArr[$productId]['volume'] != 0) {
                            $unit = !empty($importVolArr[$productId]['unit']) ? ' ' . $importVolArr[$productId]['unit'] : '';
                            $volume = Helper::numberFormat2Digit($importVolArr[$productId]['volume']) . $unit;
                            $textAlignment = 'right';
                        }
                        ?>
                        <td class="text-{{$textAlignment}} vcenter font-size-11" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $volume !!}</td>

                        @if(!empty($product['brand']))
                        <?php $i = 0; ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="text-center vcenter font-size-11" width="30px">
                            @if(!empty($brand['logo']) && File::exists('public/uploads/brand/' . $brand['logo']))
                            <img class="pictogram-min-space tooltips" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['brand_name']}}" title="{{ $brand['brand_name'] }}"/>
                            @else 
                            <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                            @endif
                        </td>
                        <td class="vcenter font-size-11">
                            {!! $brand['brand_name'] ?? __('label.N_A') !!}
                            @if(!empty($brandWiseVolumeRateArr[$productId]))
                            @if(array_key_exists($brandId, $brandWiseVolumeRateArr[$productId]))
                            <span class="text-green bold">*</span><br/>
                            <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]) : '0.00'; ?>
                            <span class="text-green bold">
                                (@lang('label.PERCENTAGE_OF_TOTAL_SALES_VOLUME', ['percentage' => $percentage]))
                            </span>
                            @endif
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11">
                            @if(!empty($brand['machine_type']))
                            @if($brand['machine_type'] == '1')
                            <span class="label label-sm bold label-yellow">@lang('label.MANUAL')</span>
                            @elseif($brand['machine_type'] == '2')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.AUTOMATIC')</span>
                            @elseif($brand['machine_type'] == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.BOTH_MANUAL_N_AUTOMATIC')</span>
                            @endif
                            @else
                            <span class="label label-sm bold label-red-soft">@lang('label.N_A')</span>
                            @endif
                        </td>
                        <td class="vcenter font-size-11">{!! $brand['machine_length'] ?? __('label.N_A') !!}</td>
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
                        <td class=" font-size-11" colspan="7"> @lang('label.NO_DATA_FOUND')</td>
                    </tr>                    
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.OTHERS_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="no-border width-full margin-top-20 ">
                <tr>
                    <td class="no-border v-top padding-right-10" width="50%">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center font-size-11">@lang('label.SL')</th>
                                    <th class="vcenter text-center font-size-11">@lang('label.FINISHED_GOODS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($finishedGoodsArr))
                                <?php $sl = 0; ?>
                                @foreach($finishedGoodsArr as $key => $goods)
                                <tr>
                                    <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                                    <td class="vcenter font-size-11">{!! $finishedGoodsList[$goods] ?? __('label.N_A') !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class=" font-size-11" colspan="2"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </td>
                    <td class="no-border v-top padding-left-10" width="50%">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center font-size-11">@lang('label.SL')</th>
                                    <th class="vcenter text-center font-size-11">@lang('label.COMPETITORS_PRODUCT')</th>
                                    <th class="vcenter text-center font-size-11">@lang('label.IMPORT_VOLUME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($competitorsProductArr))
                                <?php $sl = 0; ?>
                                @foreach($competitorsProductArr as $key => $product)
                                <tr>
                                    <td class="text-center vcenter font-size-11">{!! ++$sl !!}</td>
                                    <td class="vcenter font-size-11">{!! $competitorsProductList[$product] ?? __('label.N_A') !!}</td>
                                    <?php
                                    $volume = __('label.N_A');
                                    $textAlignment = 'center';
                                    if (!empty($importVolArr[$product]['volume']) && $importVolArr[$product]['volume'] != 0) {
                                        $unit = !empty($importVolArr[$product]['unit']) ? ' ' . $importVolArr[$product]['unit'] : '';
                                        $volume = Helper::numberFormat2Digit($importVolArr[$product]['volume']) . $unit;
                                        $textAlignment = 'right';
                                    }
                                    ?>
                                    <td class="text-{{$textAlignment}} vcenter font-size-11">{!! $volume !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class=" font-size-11" colspan="3"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>



            <div class="row margin-bottom-20 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.BUSINESS_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered margin-top-10">
                <tbody>
                    <tr>
                        <td class="fit bold info font-size-11">@lang('label.TOTAL_ORDERS')</td>
                        <td class="active text-center font-size-11"colspan="5">{!! $inquiryCountArr['total'] ?? 0 !!}</td>
                        <td class="fit bold info font-size-11" colspan="1">@lang('label.MOST_FREQUENT_CAUSE_OF_FAILURE')</td>
                        <td class="active font-size-11"colspan="11">
                            @if(!empty($mostFrequentCancelCauseArr))
                            @foreach($mostFrequentCancelCauseArr as $key => $causeId)
                            <?php
                            $labelColor = ($key == 0 || $key % 2 == 0) ? 'red-soft' : 'red-mint';
                            ?>
                            <span class="label margin-2 bold label-sm label-{{$labelColor}}">{!! $cancelCauseList[$causeId] !!}</span>
                            @endforeach
                            @else
                            <span class="label margin-2 bold label-sm label-gray-mint">@lang('label.N_A')</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="no-border width-full margin-top-10">
                <tr>
                    <td class="no-border v-top" width="25%">
                        <div id="orderSummaryPie"></div>
                    </td>
                    <td class="no-border v-top text-left" width="15%">
                        <ul class="padding-left-0 width-inherit">
                            <li class="list-style-item-none margin-top-2 width-inherit">
                                <span class="label label-sm bold label-blue-soft width-inherit">
                                    {!! __('label.UPCOMING') . ': ' . ($inquiryCountArr['upcoming'] ?? 0) !!} 
                                </span>
                            </li>
                            <li class="list-style-item-none margin-top-2 width-inherit">
                                <span class="label label-sm bold label-purple width-inherit">
                                    {!! __('label.PIPE_LINE') . ': ' . ($inquiryCountArr['pipeline'] ?? 0) !!} 
                                </span>
                            </li>
                            <li class="list-style-item-none margin-top-2 width-inherit">
                                <span class="label label-sm bold label-yellow-casablanca width-inherit">
                                    {!! __('label.CONFIRMED') . ': ' . ($inquiryCountArr['confirmed'] ?? 0) !!} 
                                </span>
                            </li>
                            <li class="list-style-item-none margin-top-2 width-inherit">
                                <span class="label label-sm bold label-green-seagreen width-inherit">
                                    {!! __('label.ACCOMPLISHED') . ': ' . ($inquiryCountArr['accomplished'] ?? 0) !!} 
                                </span>
                            </li>
                            <li class="list-style-item-none margin-top-2 width-inherit">
                                <span class="label label-sm bold label-red-flamingo width-inherit">
                                    {!! __('label.CANCELLED') . ': ' . ($inquiryCountArr['failed'] ?? 0) !!} 
                                </span>
                            </li>
                        </ul>

                    </td>
                    <td class="no-border v-top font-size-11" width="60%">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info font-size-11" colspan="12">
                                        @lang('label.SALES_N_SHIPMENT_SUMMARY')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.SALES_VOLUME')</td>
                                    <td class="active text-right font-size-11"colspan="5">{!! !empty($overAllSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_volume) : '0.00' !!} @lang('label.UNIT')</td>
                                    <td class="fit bold info font-size-11">@lang('label.SALES_AMOUNT')</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($overAllSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_amount) : '0.00' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.SALES_VOLUME') (@lang('label.LAST_1_YEAR'))</td>
                                    <td class="active text-right font-size-11"colspan="5">{!! !empty($lastOneYearSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_volume) : '0.00' !!} @lang('label.UNIT')</td>
                                    <td class="fit bold info font-size-11">@lang('label.SALES_AMOUNT') (@lang('label.LAST_1_YEAR'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($lastOneYearSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_amount) : '0.00' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.SHIPPED_VOLUME')</td>
                                    <td class="active text-right font-size-11"colspan="5">{!! !empty($buyerPaymentArr['shipped_quantity']) ? Helper::numberFormat2Digit($buyerPaymentArr['shipped_quantity']) : '0.00' !!} @lang('label.UNIT')</td>
                                    <td class="fit bold info font-size-11">@lang('label.SHIPMENT_PAYABLE')</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($buyerPaymentArr['payable']) ? Helper::numberFormat2Digit($buyerPaymentArr['payable']) : '0.00' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>


            <table class="no-border width-full margin-top-20 ">
                <tr>
                    <td class="no-border" width="50%" style="padding-right: 20px; padding-left: 20px;">
                        <div id="salesVolumeLastFiveYears"></div>
                    </td>
                    <td class="no-border" width="50%" style="padding-left: 20px; padding-right: 20px;">
                        <div id="salesAmountLastFiveYears"></div>
                    </td>
                </tr>
            </table>
            <table class="no-border width-full margin-top-20 ">
                <tr>
                    <td class="no-border v-top" width="30%" style="padding-right: 20px">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info font-size-11" colspan="6">
                                        @lang('label.PAYMENT_SUMMARY')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.PAID_AMOUNT') (@lang('label.FROM_BUYER_TO_SUPPLIER'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($buyerPaymentArr['paid']) ? Helper::numberFormat2Digit($buyerPaymentArr['paid']) : '0.00' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.PAYMENT_DUE') (@lang('label.FROM_BUYER_TO_SUPPLIER'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($buyerPaymentArr['due']) ? Helper::numberFormat2Digit($buyerPaymentArr['due']) : '0.00' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.INVOICED_AMOUNT') (@lang('label.TO_SUPPLIER'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($invoicedAmount) ? Helper::numberFormat2Digit($invoicedAmount) : '0.00' !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.RECEIVED_AMOUNT') (@lang('label.FROM_SUPPLIER'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($received->total_collection) ? Helper::numberFormat2Digit($received->total_collection) : '0.00' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="no-border v-top" width="70%" style="padding-left: 20px">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info font-size-11" colspan="12">
                                        @lang('label.INCOME_SUMMARY')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.NET_INCOME') (@lang('label.EXPECTED'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($netIncome) ? Helper::numberFormat2Digit($netIncome) : '0.00' !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.NET_INCOME') (@lang('label.RECEIVED_AT_ACTUAL'))</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($received->net_income) ? Helper::numberFormat2Digit($received->net_income) : '0.00' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info font-size-11" colspan="18">
                                        @lang('label.BUYER_COMMISSION_SUMMARY')
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info font-size-11">@lang('label.TOTAL_AMOUNT')</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($commissionReceived) ? Helper::numberFormat2Digit($commissionReceived) : '0.00' !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.PAID')</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($commissionPaid) ? Helper::numberFormat2Digit($commissionPaid) : '0.00' !!}</td>
                                    <td class="fit bold info font-size-11">@lang('label.DUE')</td>
                                    <td class="active text-right font-size-11"colspan="5">${!! !empty($commissionDue) ? Helper::numberFormat2Digit($commissionDue) : '0.00' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.UPCOMING_ORDER_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SUPPLIER')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryCountArr['upcoming']) && $inquiryCountArr['upcoming'] != 0)
                    @if(!$inquiryInfoArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($inquiryInfoArr as $inquiry)
                    @if($inquiry->status == '1')
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->supplier ?? '' !!}</td>
                        @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                        <?php $i = 0 ?>
                        @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr2[$inquiry->id][$productId] !!}">
                            {!! $product['product_name'] ?? '' !!}
                        </td>

                        @if(!empty($product['brand']))
                        <?php $j = 0 ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                            {!! $brand['brand_name'] ?? '' !!}
                        </td>

                        @if(!empty($brand['grade']))
                        <?php $k = 0 ?>
                        @foreach($brand['grade'] as $gradeId => $grade)
                        <?php
                        if ($k > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                        @if($i == 0 && $j == 0 && $k == 0)
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->lc_transmitted_copy_done == '1')
                            <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                            @else
                            <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                            @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                            <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                    title="
                                    <div class='text-left'>
                                    @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                    @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                    @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                    </div>
                                    " 
                                    >
                                <i class="fa fa-{{$delivery['icon']}}"></i>
                            </button>
                            @endforeach
                            @else
                            <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                <i class="fa fa-minus"></i>
                            </button>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->status == '1')
                            <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                            @elseif($inquiry->status == '2')
                            @if($inquiry->order_status == '1')
                            <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                            @elseif($inquiry->order_status == '2')
                            <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                            @elseif($inquiry->order_status == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                            @elseif($inquiry->order_status == '4')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                            @elseif($inquiry->order_status == '5')
                            <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                            @elseif($inquiry->order_status == '6')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                            @elseif($inquiry->status == '3')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                        </td>
                        @endif

                        <?php
                        if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                            echo '</tr>';
                        }
                        $k++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($j < ($productRowSpanArr2[$inquiry->id][$productId] - 1)) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach
                        @endif


                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="vcenter text-danger font-size-11" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.PIPELINE_ORDER_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SUPPLIER')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryCountArr['pipeline']) && $inquiryCountArr['pipeline'] != 0)
                    @if(!$inquiryInfoArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($inquiryInfoArr as $inquiry)
                    @if($inquiry->order_status == '1')
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->supplier ?? '' !!}</td>
                        @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                        <?php $i = 0 ?>
                        @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr2[$inquiry->id][$productId] !!}">
                            {!! $product['product_name'] ?? '' !!}
                        </td>

                        @if(!empty($product['brand']))
                        <?php $j = 0 ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                            {!! $brand['brand_name'] ?? '' !!}
                        </td>

                        @if(!empty($brand['grade']))
                        <?php $k = 0 ?>
                        @foreach($brand['grade'] as $gradeId => $grade)
                        <?php
                        if ($k > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                        @if($i == 0 && $j == 0 && $k == 0)
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->lc_transmitted_copy_done == '1')
                            <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                            @else
                            <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                            @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                            <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                    title="
                                    <div class='text-left'>
                                    @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                    @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                    @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                    </div>
                                    " 
                                    >
                                <i class="fa fa-{{$delivery['icon']}}"></i>
                            </button>
                            @endforeach
                            @else
                            <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                <i class="fa fa-minus"></i>
                            </button>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->status == '1')
                            <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                            @elseif($inquiry->status == '2')
                            @if($inquiry->order_status == '1')
                            <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                            @elseif($inquiry->order_status == '2')
                            <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                            @elseif($inquiry->order_status == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                            @elseif($inquiry->order_status == '4')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                            @elseif($inquiry->order_status == '5')
                            <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                            @elseif($inquiry->order_status == '6')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                            @elseif($inquiry->status == '3')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                        </td>
                        @endif

                        <?php
                        if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                            echo '</tr>';
                        }
                        $k++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($j < ($productRowSpanArr2[$inquiry->id][$productId] - 1)) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach
                        @endif


                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="vcenter text-danger font-size-11" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.CONFIRMED_ORDER_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SUPPLIER')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryCountArr['confirmed']) && $inquiryCountArr['confirmed'] != 0)
                    @if(!$inquiryInfoArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($inquiryInfoArr as $inquiry)
                    @if(in_array($inquiry->order_status, ['2', '3']))
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->supplier ?? '' !!}</td>
                        @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                        <?php $i = 0 ?>
                        @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr2[$inquiry->id][$productId] !!}">
                            {!! $product['product_name'] ?? '' !!}
                        </td>

                        @if(!empty($product['brand']))
                        <?php $j = 0 ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                            {!! $brand['brand_name'] ?? '' !!}
                        </td>

                        @if(!empty($brand['grade']))
                        <?php $k = 0 ?>
                        @foreach($brand['grade'] as $gradeId => $grade)
                        <?php
                        if ($k > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                        @if($i == 0 && $j == 0 && $k == 0)
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->lc_transmitted_copy_done == '1')
                            <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                            @else
                            <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                            @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                            <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                    title="
                                    <div class='text-left'>
                                    @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                    @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                    @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                    </div>
                                    " 
                                    >
                                <i class="fa fa-{{$delivery['icon']}}"></i>
                            </button>
                            @endforeach
                            @else
                            <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                <i class="fa fa-minus"></i>
                            </button>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->status == '1')
                            <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                            @elseif($inquiry->status == '2')
                            @if($inquiry->order_status == '1')
                            <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                            @elseif($inquiry->order_status == '2')
                            <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                            @elseif($inquiry->order_status == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                            @elseif($inquiry->order_status == '4')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                            @elseif($inquiry->order_status == '5')
                            <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                            @elseif($inquiry->order_status == '6')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                            @elseif($inquiry->status == '3')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                        </td>
                        @endif

                        <?php
                        if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                            echo '</tr>';
                        }
                        $k++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($j < ($productRowSpanArr2[$inquiry->id][$productId] - 1)) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach
                        @endif


                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="vcenter text-danger font-size-11" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.ACCOMPLISHED_ORDER_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SUPPLIER')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryCountArr['accomplished']) && $inquiryCountArr['accomplished'] != 0)
                    @if(!$inquiryInfoArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($inquiryInfoArr as $inquiry)
                    @if(in_array($inquiry->order_status, ['4', '5']))
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->supplier ?? '' !!}</td>
                        @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                        <?php $i = 0 ?>
                        @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr2[$inquiry->id][$productId] !!}">
                            {!! $product['product_name'] ?? '' !!}
                        </td>

                        @if(!empty($product['brand']))
                        <?php $j = 0 ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                            {!! $brand['brand_name'] ?? '' !!}
                        </td>

                        @if(!empty($brand['grade']))
                        <?php $k = 0 ?>
                        @foreach($brand['grade'] as $gradeId => $grade)
                        <?php
                        if ($k > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                        @if($i == 0 && $j == 0 && $k == 0)
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->lc_transmitted_copy_done == '1')
                            <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                            @else
                            <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                            @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                            <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                    title="
                                    <div class='text-left'>
                                    @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                    @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                    @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                    </div>
                                    " 
                                    >
                                <i class="fa fa-{{$delivery['icon']}}"></i>
                            </button>
                            @endforeach
                            @else
                            <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                <i class="fa fa-minus"></i>
                            </button>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->status == '1')
                            <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                            @elseif($inquiry->status == '2')
                            @if($inquiry->order_status == '1')
                            <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                            @elseif($inquiry->order_status == '2')
                            <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                            @elseif($inquiry->order_status == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                            @elseif($inquiry->order_status == '4')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                            @elseif($inquiry->order_status == '5')
                            <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                            @elseif($inquiry->order_status == '6')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                            @elseif($inquiry->status == '3')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                        </td>
                        @endif

                        <?php
                        if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                            echo '</tr>';
                        }
                        $k++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($j < ($productRowSpanArr2[$inquiry->id][$productId] - 1)) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach
                        @endif


                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="vcenter text-danger font-size-11" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="row margin-bottom-10 margin-top-20">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h5><strong>@lang('label.CANCELLED_ORDER_INFORMATION')</strong></h5>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SUPPLIER')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                        <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryCountArr['failed']) && $inquiryCountArr['failed'] != 0)
                    @if(!$inquiryInfoArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($inquiryInfoArr as $inquiry)
                    @if($inquiry->status == '3' || $inquiry->order_status == '6')
                    <tr>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                        <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->supplier ?? '' !!}</td>
                        @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                        <?php $i = 0 ?>
                        @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr2[$inquiry->id][$productId] !!}">
                            {!! $product['product_name'] ?? '' !!}
                        </td>

                        @if(!empty($product['brand']))
                        <?php $j = 0 ?>
                        @foreach($product['brand'] as $brandId => $brand)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                            {!! $brand['brand_name'] ?? '' !!}
                        </td>

                        @if(!empty($brand['grade']))
                        <?php $k = 0 ?>
                        @foreach($brand['grade'] as $gradeId => $grade)
                        <?php
                        if ($k > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                        <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                        @if($i == 0 && $j == 0 && $k == 0)
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->lc_transmitted_copy_done == '1')
                            <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                            @else
                            <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                            @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                            <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                    title="
                                    <div class='text-left'>
                                    @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                    @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                    @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                    </div>
                                    " 
                                    >
                                <i class="fa fa-{{$delivery['icon']}}"></i>
                            </button>
                            @endforeach
                            @else
                            <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                <i class="fa fa-minus"></i>
                            </button>
                            @endif
                        </td>
                        <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                            @if($inquiry->status == '1')
                            <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                            @elseif($inquiry->status == '2')
                            @if($inquiry->order_status == '1')
                            <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                            @elseif($inquiry->order_status == '2')
                            <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                            @elseif($inquiry->order_status == '3')
                            <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                            @elseif($inquiry->order_status == '4')
                            <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                            @elseif($inquiry->order_status == '5')
                            <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                            @elseif($inquiry->order_status == '6')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                            @elseif($inquiry->status == '3')
                            <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                            @endif
                        </td>
                        @endif

                        <?php
                        if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                            echo '</tr>';
                        }
                        $k++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($j < ($productRowSpanArr2[$inquiry->id][$productId] - 1)) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach
                        @endif


                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="vcenter text-danger font-size-11" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
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
        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>
        <script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!--<script src="{{asset('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>-->



        <script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
        <script type="text/javascript">

$(function () {

//start :: order summary pie
    var orderSummaryPieOptions = {
<?php
$upcoming = $inquiryCountArr['upcoming'] ?? 0;
$pipeline = $inquiryCountArr['pipeline'] ?? 0;
$confirmed = $inquiryCountArr['confirmed'] ?? 0;
$accomplished = $inquiryCountArr['accomplished'] ?? 0;
$cancelled = $inquiryCountArr['failed'] ?? 0;
?>
        series: [
<?php
echo $upcoming . ', ' . $pipeline . ', ' . $confirmed . ', ' . $accomplished . ', ' . $cancelled;
?>
        ],
        labels: ["@lang('label.UPCOMING')", "@lang('label.PIPE_LINE')"
                    , "@lang('label.CONFIRMED')", "@lang('label.ACCOMPLISHED')"
                    , "@lang('label.CANCELLED')"],
        chart: {
            width: 300,
            type: 'donut',
            animations: {
                enabled: false,
//                animateGradually: {
//                    enabled: false,
//                },
//                dynamicAnimation: {
//                    enabled: false,
//                }
            }
        },
        dataLabels: {
            enabled: true,
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#EF4836"],
        fill: {
            type: 'gradient',
        },
        legend: {
            show: false,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  val
                },

            }
        },
        responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
    };
    var orderSummaryPie = new ApexCharts(document.querySelector("#orderSummaryPie"), orderSummaryPieOptions);
    orderSummaryPie.render();
//end :: order summary pie

//start :: sales volume last five years
    var salesVolumeLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_VOLUME')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $volume = $salesSummaryArr[$year]['volume'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            animations: {
                enabled: false,
                animateGradually: {
                    enabled: false,
                },
                dynamicAnimation: {
                    enabled: false,
                }
            },
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#1BA39C'],
        dataLabels: {
            enabled: true,

        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_VOLUME') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.VOLUME') (@lang('label.UNIT'))",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return val + " @lang('label.UNIT')"
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesVolumeLastFiveYears = new ApexCharts(document.querySelector("#salesVolumeLastFiveYears"), salesVolumeLastFiveYearsOptions);
    salesVolumeLastFiveYears.render();
//end :: sales volume last five years

//start :: sales amount last five years
    var salesAmountLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_AMOUNT')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $netIncome = $salesSummaryArr[$year]['amount'] ?? 0;
        echo "'$netIncome',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            animations: {
                enabled: false,
                animateGradually: {
                    enabled: false,
                },
                dynamicAnimation: {
                    enabled: false,
                }
            },
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#8E44AD'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_AMOUNT') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.SALES_AMOUNT') ($)",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return "$" + val
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesAmountLastFiveYears = new ApexCharts(document.querySelector("#salesAmountLastFiveYears"), salesAmountLastFiveYearsOptions);
    salesAmountLastFiveYears.render();
//end :: sales amount last five years

});

function growthOrDecline(thisYear, prevYear) {
    var rateText = '';
    var rate = 0;
    var defaultPrevYear = 1;

    if (thisYear >= prevYear) {
        if (prevYear > 0) {
            defaultPrevYear = prevYear;
        }
        rate = ((thisYear - prevYear) * 100) / defaultPrevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-green-seagreen'>&nbsp;(+" + rate + "% form previous year)</span>";
    } else if (thisYear < prevYear) {
        rate = ((prevYear - thisYear) * 100) / prevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-danger'>&nbsp;(-" + rate + "% form previous year)</span>";
    } else {
        rateText = "";
    }

    return rateText;
}

document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>