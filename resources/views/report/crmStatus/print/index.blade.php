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
                        <span class="header">@lang('label.CRM_STATUS_REPORT')</span>
                    </div>
                </div>
            </div>
            <div class="row margin-bottom-20">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} |</strong> 
                            {{__('label.BUYER')}} : <strong>{{  !empty($buyerArr[Request::get('buyer')]) && Request::get('buyer') != 0 ? $buyerArr[Request::get('buyer')] : __('label.N_A') }} |</strong> 
                            {{__('label.SOURCE')}} : <strong>{{  !empty($sourceList[Request::get('source_id')]) && Request::get('source_id') != 0 ? $sourceList[Request::get('source_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.CREATED_BY')}} : <strong>{{  !empty($employeeList[Request::get('created_by')]) && Request::get('created_by') != 0 ? $employeeList[Request::get('created_by')] : __('label.N_A') }} |</strong> 
                            {{__('label.ASSIGNED_TO')}} : <strong>{{  !empty($memberList[Request::get('assigned_to')]) && Request::get('assigned_to') != 0 ? $memberList[Request::get('assigned_to')] : __('label.N_A') }} |</strong> 
                            {{__('label.STATUS')}} : <strong>{{  !empty($statusList[Request::get('status')]) && Request::get('status') != 0 ? $statusList[Request::get('status')] : __('label.N_A') }} </strong> 
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <ul class="padding-left-0">
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') .': ' . ($opportunityCountArr['new'] ?? 0) !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-steel">{!! __('label.IN_PROGRESS') .': ' . ($opportunityCountArr['in_progress'] ?? 0) !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-soft">{!! $activityStatusList['1'] .': ' . ($opportunityCountArr['dead'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-chambray">{!! $activityStatusList['2'] .': ' . ($opportunityCountArr['unreachable'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-hoki">{!! $activityStatusList['3'] .': ' . ($opportunityCountArr['answering_machine'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-soft">{!! $activityStatusList['4'] .': ' . ($opportunityCountArr['sdc'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-steel">{!! $activityStatusList['5'] .': ' . ($opportunityCountArr['reached'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm  label-yellow-mint">{!! $activityStatusList['6'] .': ' . ($opportunityCountArr['not_interested'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-pink">{!! $activityStatusList['8'] .': ' . ($opportunityCountArr['not_booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple-sharp">{!! $activityStatusList['9'] .': ' . ($opportunityCountArr['halt'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-sharp">{!! $activityStatusList['10'] .': ' . ($opportunityCountArr['prospective'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-mint">{!! $activityStatusList['11'] .': ' . ($opportunityCountArr['none'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-yellow-casablanca">{!! $activityStatusList['12'] .': ' . ($opportunityCountArr['irrelevant'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-soft">{!! __('label.BOOKED') .': ' . ($opportunityCountArr['booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple">{!! __('label.DISPATCHED') .': ' . ($opportunityCountArr['dispatched'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-seagreen">{!! __('label.APPROVED') .': ' . ($opportunityCountArr['approved'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-mint">{!! __('label.DENIED') .': ' . ($opportunityCountArr['denied'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-flamingo">{!! __('label.CANCELLED') .': ' . ($opportunityCountArr['cancelled'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-cascade">{!! __('label.VOID') .': ' . ($opportunityCountArr['void'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-thunderbird">{!! __('label.REVOKED') .': ' . ($opportunityCountArr['revoked'] ?? 0)  !!}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                        <thead>
                            <tr>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.BUYER')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.SOURCE')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.DATE_OF_CREATION')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.LAST_UPDATED')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.CREATED_BY')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.ASSIGNED_TO')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.REMARKS')</th>
                                <th class="vcenter text-center font-size-11" colspan="3">@lang('label.BUYER_CONTACT_PERSON')</th>
                                <th class="vcenter text-center font-size-11" colspan="8">@lang('label.PRODUCT_INFORMATION')</th>
                                <th class="vcenter text-center font-size-11" rowspan="2">@lang('label.STATUS')</th>
                            </tr>
                            <tr>
                                <th class="vcenter text-center font-size-11">@lang('label.NAME')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.DESIGNATION')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.PHONE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.PRODUCT')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.BRAND')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.GRADE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.ORIGIN')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.GSM')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $page = Request::get('page');
                            $page = empty($page) ? 1 : $page;
                            $sl = ($page - 1) * Session::get('paginatorCount');
                            ?>
                            @foreach($targetArr as $target)
                            <?php
                            if ($target->buyer_has_id == '0') {
                                $buyer = $target->buyer;
                            } elseif ($target->buyer_has_id == '1') {
                                $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                            }

                            $iconActivityLog = '';
                            if (!empty($hasActivityLog)) {
                                if (in_array($target->id, $hasActivityLog)) {
                                    $iconActivityLog = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips set-activity-log vcenter"'
                                            . ' title="' . __('label.CLICK_TO_VIEW_ACTIVITY_LOG') . '" 
                                            data-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                                }
                            }
                            ?>
                            <tr>
                                <td class="vcenter font-size-11 text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! ++$sl.$iconActivityLog !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $buyer ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->source ?? '' !!}</td>
                                <td class="vcenter font-size-11 text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</td>
                                <td class="vcenter font-size-11 text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! !empty($target->updated_at) ? Helper::formatDate($target->updated_at) : '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->opportunity_creator ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $assignedPersonList[$target->member_id] ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->remarks ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['name'] ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['designation'] ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['phone'] ?? '' !!}</td>

                                @if(!empty($productArr[$target->id]))
                                <?php $i = 0; ?>

                                @foreach($productArr[$target->id] as $pKey => $pInfo)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                //product
                                if ($pInfo['product_has_id'] == '0') {
                                    $product = $pInfo['product'];
                                } elseif ($pInfo['product_has_id'] == '1') {
                                    $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                }
                                //brand
                                if ($pInfo['brand_has_id'] == '0') {
                                    $brand = $pInfo['brand'];
                                } elseif ($pInfo['brand_has_id'] == '1') {
                                    $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                }
                                //grade
                                if ($pInfo['grade_has_id'] == '0') {
                                    $grade = $pInfo['grade'];
                                } elseif ($pInfo['grade_has_id'] == '1') {
                                    $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                }

                                $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                $statusTitle = __('label.FINAL_PRODUCT');
                                ?>
                                <td class="vcenter font-size-11">
                                    {!! $product ?? __('label.N_A') !!}
                                    @if(!empty($pInfo['final']) && $pInfo['final'] == '1')
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ $statusTitle }}">

                                    </button>
                                    @endif
                                </td>
                                <td class="vcenter font-size-11">{!! $brand ?? __('label.N_A') !!}</td>
                                <td class="vcenter font-size-11">{!! $grade ?? __('label.N_A') !!}</td>
                                <td class="text-center font-size-11 vcenter">{!! $country !!}</td>
                                <td class="vcenter font-size-11">{!! $pInfo['gsm'] ?? '' !!}</td>
                                <td class="text-right font-size-11 vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                <td class="text-right font-size-11 vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                <td class="text-right font-size-11 vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                @if($i == 0)
                                <td class="text-center font-size-11 vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">
                                    @if($target->status == '0')
                                    <span class="label bold font-size-11 label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                    @elseif($target->status == '1')
                                    @if($target->revoked_status == '0')
                                    @if($target->last_activity_status == '0')
                                    <span class="label bold font-size-11 label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                    @elseif($target->last_activity_status == '1')
                                    <span class="label bold font-size-11 label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '2')
                                    <span class="label bold font-size-11 label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '3')
                                    <span class="label bold font-size-11 label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '4')
                                    <span class="label bold font-size-11 label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '5')
                                    <span class="label bold font-size-11 label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '6')
                                    <span class="label bold font-size-11 font-size-11 label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '8')
                                    <span class="label bold font-size-11 label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '9')
                                    <span class="label bold font-size-11 label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '10')
                                    <span class="label bold font-size-11 label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '11')
                                    <span class="label bold font-size-11 label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '12')
                                    <span class="label bold font-size-11 label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @endif
                                    @elseif($target->revoked_status == '1')
                                    <span class="label bold font-size-11 label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                    @endif
                                    @elseif($target->status == '2')
                                    @if($target->dispatch_status == '0')
                                    <span class="label bold font-size-11 label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                    @elseif($target->dispatch_status == '1')
                                    @if($target->approval_status == '0')
                                    <span class="label bold font-size-11 label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                    @elseif($target->approval_status == '1')
                                    <span class="label bold font-size-11 label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                    @elseif($target->approval_status == '2')
                                    <span class="label bold font-size-11 label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                    @endif
                                    @endif
                                    @elseif($target->status == '3')
                                    <span class="label bold font-size-11 label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                    @elseif($target->status == '4')
                                    <span class="label bold label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                    @endif
                                </td>
                                @endif

                                <?php
                                if ($i > ($productRowspanArr[$target->id] - 1)) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @else
                                <td class="vcenter font-size-11"></td>
                                <td class="vcenter font-size-11"></td>
                                <td class="vcenter font-size-11"></td>
                                <td class="text-center vcenter font-size-11"></td>
                                <td class="vcenter font-size-11"></td>
                                <td class="text-right vcenter font-size-11"></td>
                                <td class="text-right vcenter font-size-11"></td>
                                <td class="text-right vcenter font-size-11"></td>
                                <td class="text-center font-size-11 vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">
                                    @if($target->status == '0')
                                    <span class="label bold font-size-11 label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                    @elseif($target->status == '1')
                                    @if($target->revoked_status == '0')
                                    @if($target->last_activity_status == '0')
                                    <span class="label bold font-size-11 label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                    @elseif($target->last_activity_status == '1')
                                    <span class="label bold font-size-11 label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '2')
                                    <span class="label bold font-size-11 label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '3')
                                    <span class="label bold font-size-11 label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '4')
                                    <span class="label bold font-size-11 label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '5')
                                    <span class="label bold font-size-11 label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '6')
                                    <span class="label bold font-size-11 font-size-11 label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '8')
                                    <span class="label bold font-size-11 label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '9')
                                    <span class="label bold font-size-11 label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '10')
                                    <span class="label bold font-size-11 label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '11')
                                    <span class="label bold font-size-11 label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '12')
                                    <span class="label bold font-size-11 label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @endif
                                    @elseif($target->revoked_status == '1')
                                    <span class="label bold font-size-11 label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                    @endif
                                    @elseif($target->status == '2')
                                    @if($target->dispatch_status == '0')
                                    <span class="label bold font-size-11 label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                    @elseif($target->dispatch_status == '1')
                                    @if($target->approval_status == '0')
                                    <span class="label bold font-size-11 label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                    @elseif($target->approval_status == '1')
                                    <span class="label bold font-size-11 label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                    @elseif($target->approval_status == '2')
                                    <span class="label bold font-size-11 label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                    @endif
                                    @endif
                                    @elseif($target->status == '3')
                                    <span class="label bold font-size-11 label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                    @elseif($target->status == '4')
                                    <span class="label bold label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="19" class="font-size-11">@lang('label.NO_DATA_FOUND')</td>
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
        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>
        <script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <script type="text/javascript">
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>