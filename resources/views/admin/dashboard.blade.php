@extends('layouts.default.master')

@section('data_count')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}

</div>
@endif


<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb margin-top-10">
            <li>
                <a href="{{url('dashboard')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Dashboard</span>
            </li>
        </ul>
        <div class="page-toolbar margin-top-15">
            <h5 class="dashboard-date font-blue-madison"><span class="icon-calendar"></span> Today is <span class="font-blue-madison">{!! date('d F Y') !!}</span> </h5>   
        </div>
    </div>
    <div class="row margin-top-10">
        <!--PRODUCT_PRICING BOX-->
        @if(Auth::user()->group_id == 1 || !empty($userAccessArr[15][1]) || !empty($hasRelationWithProduct))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat green-meadow tooltips" href="#productPricingModal" id="productPricingId" data-toggle="modal" title="@lang('label.PRODUCT_PRICING')">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <div class="desc">@lang('label.PRODUCT_PRICING')</div>
                </div>
            </a>
        </div> 
        @endif
        <!--END :: PRODUCT_PRICING BOX-->

        <!--PRODUCT PRICE SETUP-->
        @if(!empty($userAccessArr[15][8]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat yellow-mint tooltips" href="#setProductPricingModal" id="setProductPricingId" data-toggle="modal" title="@lang('label.SET_PRODUCT_PRICING')">
                <div class="visual">
                    <i class="fa fa-money"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-calculator"></i>
                    </div>
                    <div class="desc">@lang('label.SET_PRODUCT_PRICING')</div>
                </div>
            </a>
        </div>
        @endif
        <!--END :: PRODUCT PRICE SETUP-->

        <!--MONTHLY NET INCOME-->
        @if(Auth::user()->group_id == '1')
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat-v2  dashboard-stat blue-dark tooltips"  title="@lang('label.KONITA_MONTHLY_NET_INCOME')">
                <div class="visual">
                    <i class="fa fa-dollar"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-dollar"></i>
                        <span class="bold" data-counter="counterup" data-value="{{!empty($monthlyNetIncome)?Helper::numberFormat2Digit($monthlyNetIncome):Helper::numberFormat2Digit(0)}}">
                            {{!empty($monthlyNetIncome)?Helper::numberFormat2Digit($monthlyNetIncome):Helper::numberFormat2Digit(0)}}
                        </span>
                    </div>
                    <div class="desc font-size-15">@lang('label.KONITA_MONTHLY_NET_INCOME')</div>
                </div>
            </div>
        </div> 
        @endif
        <!--END :: MONTHLY NET INCOME-->

        <!--*******************SALES PERSONS PART(CART)****************-->
        <!--SALES PERSONS CURRENT MONTH COMMISSION-->
        @if(Auth::user()->allowed_for_sales=='1')
        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="dashboard-stat-v2  dashboard-stat blue-dark tooltips"  title="@lang('label.MY_SALES_COMMISSION')">
                        <div class="visual">
                            <i class="fa fa-dollar"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <i class="fa fa-dollar"></i>
                                <span class="bold" data-counter="counterup" data-value="{{!empty($myCurrentMonthCmsnData)?Helper::numberFormat2Digit($myCurrentMonthCmsnData):Helper::numberFormat2Digit(0)}}">
                                    {{!empty($myCurrentMonthCmsnData)?Helper::numberFormat2Digit($myCurrentMonthCmsnData):Helper::numberFormat2Digit(0)}}
                                </span>
                            </div>
                            <div class="desc font-size-15">@lang('label.MY_SALES_COMMISSION')</div>
                        </div>
                    </div>
                </div> -->
        @endif
        <!--END OF SALES PERSONS CURRENT MONTH COMMISSION-->
        <!--*******************END OF SALES PERSONS PART(CART)****************-->

        <!--******************** SERVICE PERSON PART (CART) **********************-->

        <!--**** PENDING FOR LC-->
        @if(!empty($userAccessArr[27][5]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat yellow-gold tooltips" href="#pendingLcModal" id="pendingLcId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PENDING_FOR_LC')">
                <div class="visual">
                    <i class="fa fa-hourglass-start"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$pendingForLc}}">
                            {{$pendingForLc}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PENDING_FOR_LC')</div>
                </div>
            </a>
        </div>
        <!--**** END OF PENDING FOR LC--> 

        <!--*** START PENDING FOR SHIPMENT-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat purple-studio tooltips" href="#pendingShipmentModal" id="pendingShipmentId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PENDING_FOR_SHIPMENT')">
                <div class="visual">
                    <i class="fa fa-ship"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$pendingForShipment}}">
                            {{$pendingForShipment}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PENDING_FOR_SHIPMENT')</div>
                </div>
            </a>
        </div>
        <!--*** END OF PENDING FOR SHIPMENT-->

        <!--*** PARTIALLY SHIPPED-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat blue-madison tooltips" href="#partiallyShippedModal" id="partiallyShippedId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PARTIALLY_SHIPPED')">
                <div class="visual">
                    <i class="fa fa-ship"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$partiallyShippedCount}}">
                            {{$partiallyShippedCount}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PARTIALLY_SHIPPED')</div>
                </div>
            </a>
        </div>
        <!--*** END OF PARTIALLY SHIPPED-->
        @endif

        @if(!empty($userAccessArr[27][5]) || !empty($userAccessArr[31][5]))
        <!--*** Waiting for Tracking No-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat red-haze tooltips" href="#trackingNoModal" id="trackingNoId" data-toggle="modal"  title="@lang('label.CLICK_HERE_TO_VIEW_WAITING_FOR_TRACKING_NO')">
                <div class="visual">
                    <i class="fa fa-sun-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$waitingTrackingNoCount}}">
                            {{$waitingTrackingNoCount}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.WAITING_FOR_TRACKING_NO')</div>
                </div>
            </a>
        </div>
        <!--*** END of Waiting for Tracking No-->
        @endif

        <!--Expected INCOME-->
        @if(Auth::user()->group_id == '1')
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat-v2  dashboard-stat blue-dark tooltips"  title="@lang('label.EXPECTED_INCOME')">
                <div class="visual">
                    <i class="fa fa-dollar"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-dollar"></i>
                        <span class="bold" data-counter="counterup" data-value="{{!empty($expectedIncome)?Helper::numberFormat2Digit($expectedIncome):Helper::numberFormat2Digit(0)}}">
                            {{!empty($expectedIncome)?Helper::numberFormat2Digit($expectedIncome):Helper::numberFormat2Digit(0)}}
                        </span>
                    </div>
                    <div class="desc font-size-15">@lang('label.EXPECTED_INCOME')</div>
                </div>
            </div>
        </div> 
        @endif
        <!--END :: Expected INCOME-->

        <!--********************END OF SERVICE PERSON PART (CART) **********************-->
    </div>
    <!--*************************** Start :: CRM *****************************-->
    <div class="row">

        <!--**************************SALES PERSONS PART START******************************-->
        @if((Auth::user()->allowed_for_crm == '1' && Auth::user()->allowed_for_sales != '1') || Auth::user()->for_crm_leader == '1' || Auth::user()->group_id == '1')
        <!-- START :: CRM Status Summary -->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.CRM_STATUS_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        @if(Auth::user()->for_crm_leader == '1' || Auth::user()->group_id=='1')
                        <a class="btn btn-circle btn-default" href="{{ URL::to('crmAllOpportunity') }}"> See All </a>
                        @endif
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="crmStatusSummary" class="row crm-status-summary" style="width: 100%; height: 400px; margin: 0 auto;">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar" style="max-height: 400px;">
                                <table class="table table-hover table-head-fixer-color-grey-mint">
                                    <thead>
                                        <tr>
                                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                            <th class="vcenter text-center">@lang('label.CRM_STATUS')</th>
                                            <th class="vcenter text-center">@lang('label.THIS_WEEK')</th>
                                            <th class="vcenter text-center">@lang('label.TODAY')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $crmStatusSummarySl = 0; ?>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '0', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '0', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'new',
                                            'statusTextCap' => __('label.NEW'), 'crmColor' => 'blue-madison'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '0', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'in_progress',
                                            'statusTextCap' => __('label.IN_PROGRESS'), 'crmColor' => 'blue-steel'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '1', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'dead',
                                            'statusTextCap' => $activityStatusList['1'], 'crmColor' => 'red-soft'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '2', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'unreachable',
                                            'statusTextCap' => $activityStatusList['2'], 'crmColor' => 'blue-chambray'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '3', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'answering_machine',
                                            'statusTextCap' => $activityStatusList['3'], 'crmColor' => 'blue-hoki'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '4', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'sdc',
                                            'statusTextCap' => $activityStatusList['4'], 'crmColor' => 'blue-soft'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '5', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'reached',
                                            'statusTextCap' => $activityStatusList['5'], 'crmColor' => 'green-steel'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '6', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'not_interested',
                                            'statusTextCap' => $activityStatusList['6'], 'crmColor' => 'yellow-mint'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '8', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'not_booked',
                                            'statusTextCap' => $activityStatusList['8'], 'crmColor' => 'red-pink'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '9', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'halt',
                                            'statusTextCap' => $activityStatusList['9'], 'crmColor' => 'purple-sharp'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '10', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'prospective',
                                            'statusTextCap' => $activityStatusList['10'], 'crmColor' => 'green-sharp'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '11', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'none',
                                            'statusTextCap' => $activityStatusList['11'], 'crmColor' => 'grey-mint'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '12', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'irrelevant',
                                            'statusTextCap' => $activityStatusList['12'], 'crmColor' => 'yellow-casablanca'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '2', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '7', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'booked',
                                            'statusTextCap' => __('label.BOOKED'), 'crmColor' => 'green-soft'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '2', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '7', 'dispatchStatus' => '1',
                                            'approvalStatus' => '0', 'statusText' => 'dispatched',
                                            'statusTextCap' => __('label.DISPATCHED'), 'crmColor' => 'purple'
                                            ])
                                        </tr>
<!--                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '2', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '7', 'dispatchStatus' => '1',
                                            'approvalStatus' => '1', 'statusText' => 'approved',
                                            'statusTextCap' => __('label.APPROVED'), 'crmColor' => 'green-seagreen'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '2', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '7', 'dispatchStatus' => '1',
                                            'approvalStatus' => '2', 'statusText' => 'denied',
                                            'statusTextCap' => __('label.DENIED'), 'crmColor' => 'red-mint'
                                            ])
                                        </tr>-->
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '3', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '0', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'cancelled',
                                            'statusTextCap' => __('label.CANCELLED'), 'crmColor' => 'red-flamingo'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '4', 'revokedStatus' => '0',
                                            'lastActivityStatus' => '0', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'void',
                                            'statusTextCap' => __('label.VOID'), 'crmColor' => 'grey-cascade'
                                            ])
                                        </tr>
                                        <tr>
                                            @include('admin.others.crmStatusTableRows', [
                                            'crmStatusSummarySl' => ++$crmStatusSummarySl,
                                            'crmDailyCount' => $crmDailyCount, 'crmWeeklyCount' => $crmWeeklyCount,
                                            'status' => '1', 'revokedStatus' => '1',
                                            'lastActivityStatus' => '0', 'dispatchStatus' => '0',
                                            'approvalStatus' => '0', 'statusText' => 'revoked',
                                            'statusTextCap' => __('label.REVOKED'), 'crmColor' => 'red-thunderbird'
                                            ])
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END :: CRM  Status Summary -->
        @endif
        @if(Auth::user()->allowed_for_crm == '1' || Auth::user()->group_id=='1')
        <!-- START :: CRM Schedule -->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.CRM_ACTIVITY_SCHEDULE')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="table-responsive webkit-scrollbar" style="width: 100%; max-height: 400px; margin: 0 auto;">
                                <div id="showCalendar" class="has-toolbar"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END :: CRM Schedule -->
        @endif
    </div>
    <!--*************************** End :: CRM ******************************-->

    <!--GRAPH PART-->
    <div class="row">

        <!--**************************SALES PERSONS PART START******************************-->
        @if(Auth::user()->allowed_for_sales=='1' || Auth::user()->group_id=='1')
        <!--Team Performance (Current Month)-->
        @if(array_key_exists(Auth::user()->id,$teamSupervisorArr))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TEAM_PERFORMANCE_CURRENT_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="teamPerformanceCurrentMonth" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--End of Team Performance (Current Month)-->

        <!--my last six month performance-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.MY_PERFORMANCE_LAST_SIX_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="lastSixMonthPerformance" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--my end of last six month performance div-->
        <!--Last Six Months (Team Performance)-->
        @if(array_key_exists(Auth::user()->id,$teamSupervisorArr))
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TEAM_PERFORMANCE_LAST_SIX_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="teamPerformanceLastSixMonth" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--End of Last Six Months (Team Performance) div--> 

        <!--Last 15 Days (Inquiry Summary)-->
        @if(!empty($userAccessArr[23][1]) || Auth::user()->group_id=='1')
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.INQUIRY_SUMMARY_LAST_30_DAYS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="lastFifteenDaysInquirySummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--End of Last 15 Days (Inquiry Summary) div--> 

        <!--START DIV MY Sales Status-->
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.MY_SALES_STATUS')&nbsp;({{$showLast12Month.' ' .'-'.' '. $showCurrentDay}})
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="mySalesStatus" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--End of Div My Sales Status--> 

        <!--START DIV Sales Status (TEAM)-->
        @if(array_key_exists(Auth::user()->id,$teamSupervisorArr))
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.SALES_STATUS_TEAM')&nbsp;({{$showLast12Month.' ' .'-'.' '. $showCurrentDay}})
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="teamSalesStatus" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        @endif
        <!--End of Div Sales Status (TEAM)-->
    </div>
    <!--COUNT BUYER PRODUCT && BRAND-->
    @if(Auth::user()->allowed_for_sales=='1' || Auth::user()->group_id=='1')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="row text-center">
                        <div class="col-md-3 margin-top-10 margin-bottom-10">
                            <a href="#buyerDetailsModal" id="buyerModalId" data-toggle="modal">
                                <span class="dashboard-circle-1 tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_BUYER')">
                                    <div data-counter="counterup" data-value="{{$myBuyerCount}}" class="number-style">{{$myBuyerCount}}</div>
                                </span>
                            </a>  
                            <div class="bold margin-top-20 font-size-15">@lang('label.BUYER')</div>
                        </div>
                        <div class="col-md-3 margin-top-10 margin-bottom-10">
                            <a href="#productDetailsModal" id="productModalId" data-toggle="modal">
                                <span class="dashboard-circle-2 tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_PRODUCT')">
                                    <div data-counter="counterup" data-value="{{count($myProductArr)}}" class="number-style">{{count($myProductArr)}}</div>
                                </span>
                            </a>
                            <div class="bold margin-top-20 font-size-15">@lang('label.PRODUCT')</div>
                        </div>
                        <div class="col-md-3 margin-top-10 margin-bottom-10">
                            <a href="#brandDetailsModal" id="brandModalId" data-toggle="modal">
                                <span class="dashboard-circle-3 tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_BRAND')">
                                    <div data-counter="counterup" data-value="{{count($myBrandCount)}}" class="number-style">{{count($myBrandCount)}}</div>
                                </span>  
                            </a>
                            <div class="bold margin-top-20 font-size-15">@lang('label.BRAND')</div>
                        </div>
                        <div class="col-md-3 margin-top-10 margin-bottom-10">
                            <a href="#salesPersonsModal" id="salesPersonsModalId" data-toggle="modal">
                                <span class="dashboard-circle-4 tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_PERSONS')">
                                    <div data-counter="counterup" data-value="{{count($salesPersonCount)}}" class="number-style">{{count($salesPersonCount)}}</div>
                                </span>  
                            </a>
                            <div class="bold margin-top-20 font-size-15">@lang('label.SALES_PERSON')</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END OF COUNT-->
    @endif 
    <!--END OF allowed_for_sales -->
    <!--****************************END OF SALES PERSON PART****************************-->
    <div class="row">
        <!--********************************* START SERVICE PERSON PART **************************-->
        @if(!empty($userAccessArr[27][1]) || !empty($userAccessArr[31][1]))
        <!--******** START NEXT 15 DAYS ETS SUMMARY ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NEXT_15_DAYS_ETS_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="next15DaysEtsSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF NEXT 15 DAYS ETS SUMMARY ************-->

        <!--******** START NEXT 15 DAYS ETA SUMMARY ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NEXT_15_DAYS_ETA_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="next15DaysEtaSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  NEXT 15 DAYS ETA SUMMARY ************-->
        @endif
        <!--********************************* END OF SERVICE PERSON PART **************************-->

        <!--********************************* Start :: Accounts Person Part **************************-->


        <!--************* Start :: Top 10 Supplier with Payment Due *************-->
        @if(!empty($userAccessArr[50][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TOP_10_SUPPLIER_WITH_PAYMENT_DUE')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-default" href="{{ URL::to('supplierLedger') }}"> See All </a>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="top10SupplierWithPaymentDue" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--************* End :: Top 10 Supplier with Payment Due *************-->


        <!--************* Start :: Top 10 Buyer with Payment Due *************-->
        @if(!empty($userAccessArr[48][2]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TOP_10_BUYER_WITH_PAYMENT_DUE')&nbsp;(@lang('label.RECEIVABLE'))
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-default" href="{{ URL::to('paymentStatus') }}"> See All </a>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="top10BuyerWithPaymentDue" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--************* End :: Top 10 Buyer with Payment Due *************-->


        <!--************* Start :: Top 10 Sales Commission Due *************-->
        @if(!empty($userAccessArr[62][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TOP_10_SALES_COMMSSION_DUE')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-default" href="{{ URL::to('salesPersonLedger') }}"> See All </a>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="top10SalesCommissionDue" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--************* End :: Top 10 Sales Commission Due *************-->



        <!--************* Start :: Top 10 Buyer Commission Due *************-->
        @if(!empty($userAccessArr[65][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TOP_10_BUYER_COMMSSION_DUE')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-default" href="{{ URL::to('buyerLedger') }}"> See All </a>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="top10BuyerCommissionDue" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--************* End :: Top 10 Buyer Commission Due *************-->


        <!--********************************* End :: Accounts Person Part **************************-->
    </div>
    <!--END OF GRAPH PART-->

</div>
<!--***************MODAL******************-->
<!--product pricing modal-->
<div class="modal fade" id="productPricingModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="productPricingViewModal">
        </div>
    </div>
</div>

<!--set product pricing modal-->
<div class="modal fade" id="setProductPricingModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showSetProductPricing">
        </div>
    </div>
</div>

<!--Buyer modal-->
<div class="modal fade" id="buyerDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="buyerViewModal">
        </div>
    </div>
</div>
<!--Product modal-->
@if(Auth::user()->group_id=='1')
<div class="modal fade" id="productDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="productViewModal">
        </div>
    </div>
</div>
@endif
<!--Brand modal-->
@if(Auth::user()->group_id=='1')
<div class="modal fade" id="brandDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="brandViewModal">
        </div>
    </div>
</div>
@endif
<!--Sales Persons modal-->
@if(Auth::user()->group_id=='1')
<div class="modal fade" id="salesPersonsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="salesPersonsViewModal">
        </div>
    </div>
</div>
@endif
<!--Pending for LC modal-->
<div class="modal fade" id="pendingLcModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingLcViewModal">
        </div>
    </div>
</div>

<!--Pending for Shipment modal-->
<div class="modal fade" id="pendingShipmentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingShipmentViewModal">
        </div>
    </div>
</div>

<!--Partially Shipped modal-->
<div class="modal fade" id="partiallyShippedModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="partiallyShippedViewModal">
        </div>
    </div>
</div>

<!--Waiting  for Tracking NO modal-->
<div class="modal fade" id="trackingNoModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="trackingNoViewModal">
        </div>
    </div>
</div>

<!--CRM Status Summary modal-->
<div class="modal fade" id="crmOpportunityListModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showCrmOpportunityList">
        </div>
    </div>
</div>

<!--Ets Summary modal-->
<div class="modal fade" id="etsSummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtsSummary">
        </div>
    </div>
</div>
<!--Eta Summary modal-->
<div class="modal fade" id="etaSummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtaSummary">
        </div>
    </div>
</div>

<!--inquiry Summary modal-->
<div class="modal fade" id="inquirySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showInquirySummary">
        </div>
    </div>
</div>

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
//table header fix
$(".table-head-fixer-color-grey-mint").tableHeadFixer();
//product pricing MODAL
$(document).on("click", "#productPricingId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/productPricingView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#productPricingViewModal").html('');
        },
        success: function (res) {
        $("#productPricingViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END PRODUCT PRICING MODAL

//set product pricing modal
$("#setProductPricingId").on("click", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('/dashboard/showProductPricing')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        beforeSend: function () {
        $("#showSetProductPricing").html('');
        App.blockUI({
        boxed: true
        });
        },
        success: function (res) {
        $("#showSetProductPricing").html(res.html);
        App.unblockUI();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        App.unblockUI();
        }
}); //ajax
});
//end :: set product pricing modal

//buyer Details MODAL
$(document).on("click", "#buyerModalId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getAuthorizedUserBuyer')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#buyerViewModal").html('');
        },
        success: function (res) {
        $("#buyerViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of BUYER DETAILS MODAL
//SHOW BUYER MODAL
$(document).on("click", ".name-search", function (e) {
e.preventDefault();
var name = $(".search").val();
$.ajax({
url: "{{ URL::to('dashboard/getAuthorizedUserBuyerByName')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        name: name,
        },
        beforeSend: function() {
        $("#showBuyer").html('');
        },
        success: function (res) {
        $("#showBuyer").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of SHOW BUYER
//SHOW ALL BUYER MODAL
$(document).on("click", ".all-search", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getAllAuthorizedUserBuyer')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#showBuyer").html('');
        },
        success: function (res) {
        $("#showBuyer").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of SHOW ALL BUYER

//product Details MODAL
$(document).on("click", "#productModalId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getAuthorizedUserProduct')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#productViewModal").html('');
        },
        success: function (res) {
        $("#productViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of product DETAILS MODAL

//brand Details MODAL
$(document).on("click", "#brandModalId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getAuthorizedUserBrand')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#brandViewModal").html('');
        },
        success: function (res) {
        $("#brandViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of brand DETAILS MODAL

//Sales Person Details MODAL
$(document).on("click", "#salesPersonsModalId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getSalesPersons')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#salesPersonsViewModal").html('');
        },
        success: function (res) {
        $("#salesPersonsViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of Sales Persons DETAILS MODAL

//PENDING FOR LC  Details MODAL
$(document).on("click", "#pendingLcId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/pendingForLc')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#pendingLcViewModal").html('');
        },
        success: function (res) {
        $("#pendingLcViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PENDING FOR LC DETAILS MODAL

//PENDING FOR Shipment Details MODAL
$(document).on("click", "#pendingShipmentId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/pendingForShipment')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#pendingShipmentViewModal").html('');
        },
        success: function (res) {
        $("#pendingShipmentViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PENDING FOR SHIPMENT DETAILS MODAL

//PARTIALLY SHIPPED Details MODAL
$(document).on("click", "#partiallyShippedId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getPartiallyShipped')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#partiallyShippedViewModal").html('');
        },
        success: function (res) {
        $("#partiallyShippedViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PARTIALLY SHIPPED DETAILS MODAL

//Waiting For Tracking No Details MODAL
$(document).on("click", "#trackingNoId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/waitingTrackingNo')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function() {
        $("#trackingNoViewModal").html('');
        },
        success: function (res) {
        $("#trackingNoViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//CRM Opportunity List MODAL
$(document).on("click", ".crm-opportunity-list", function (e) {
e.preventDefault();
var formData = {
status : $(this).attr('data-status'),
        revoked_status : $(this).attr('data-revoked-status'),
        last_activity_status : $(this).attr('data-last-ctivity-status'),
        dispatch_status : $(this).attr('data-dispatch-status'),
        approval_status : $(this).attr('data-approval-status'),
        duration : $(this).attr('data-duration'),
        status_text_cap : $(this).attr('data-status-text-cap'),
        duration_text : $(this).attr('data-duration-text'),
        };
$.ajax({
url: "{{ URL::to('dashboard/getCrmOpportunityList')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        beforeSend: function() {
        $("#showCrmOpportunityList").html('');
        },
        success: function (res) {
        $("#showCrmOpportunityList").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PARTIALLY SHIPPED DETAILS MODAL


//PRODUCT WISE DATA
$(document).on("click", "#generate", function (e) {
var productId = $('#productId').val();
$.ajax({
url: "{{ URL::to('dashboard/getProductPricing')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        product_id: productId,
        },
        success: function (res) {
        $("#showProductPricing").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END PRODUCT PRICING

//********************** START SALES PERSONS PART ***********************
//my last six month performance Script
var myPerformanceSixMonthOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#1BA39C', '#8E44AD', '#E08283', '#555555', '#C49F47', '#1BA39C', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1BA39C', '#8E44AD', '#E08283', '#555555', '#C49F47', '#1BA39C', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "@lang('label.TARGET')",
                data: [
<?php
if (!empty($myLastSixMonthTagAcivArr)) {
    foreach ($myLastSixMonthTagAcivArr as $target) {
        ?>
                        "{{$target['target']}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.ACHIEVEMENT')",
                data: [
<?php
if (!empty($myLastSixMonthTagAcivArr)) {
    foreach ($myLastSixMonthTagAcivArr as $achiev) {
        ?>
                        "{{$achiev['achievement']}}",
        <?php
    }
}
?>
                ]
        }
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($myLastSixMonthTagAcivArr)) {
    foreach ($myLastSixMonthTagAcivArr as $monthName => $item) {
        ?>
                "{{$monthName}}",
        <?php
    }
}
?>
        ],
                title: {
                text: "@lang('label.MONTHS')"
                }
        },
        yaxis: {
        title: {
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        },
        tooltip: {
        y: [
        {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        },
                title: {
                formatter: function (val) {
                return val + " (@lang('label.UNIT'))"
                }
                }
        },
        {
        formatter: function (val) {
        return parseFloat(val).toFixed(2)
        },
                title: {
                formatter: function (val) {
                return val + " (@lang('label.UNIT'))"
                }
                }
        },
        ]
        },
        };
var myPerformanceSixMonth = new ApexCharts(document.querySelector("#lastSixMonthPerformance"), myPerformanceSixMonthOptions);
myPerformanceSixMonth.render();
//ENDOF LINE CHART
//my End of last six month performance Script

//last six months (Team Performance) script
var teamPerformanceSixMonthOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E35B5A', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E35B5A', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
<?php
foreach ($salesPersonLastSixMonthArr as $salesPersonId => $salesPersonName) {
    ?>
            {
            name: "{{$salesPersonName}}",
                    data: [
    <?php
    foreach ($lastSixMonthArr as $monthName) {
        $parcent = !empty($teamLastSixMonthPercent[$monthName][$salesPersonId]) ? $teamLastSixMonthPercent[$monthName][$salesPersonId] : 0.00;
        ?>

                        "{{$parcent}}",
    <?php }
    ?>
                    ]
            },
    <?php
}
?>

        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
foreach ($lastSixMonthArr as $monthName) {
    ?>
            "{{$monthName}}",
    <?php
}
?>
        ],
                title: {
                text: "@lang('label.MONTHS')"
                }
        },
        yaxis: {
        title: {
        text: "@lang('label.PERFORMANCE_PERCENTAGE')"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + "%"
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
};
var teamPerformanceSixMonth = new ApexCharts(document.querySelector("#teamPerformanceLastSixMonth"), teamPerformanceSixMonthOptions);
teamPerformanceSixMonth.render();
//End of Last Six Months (Team Performance)

//TEAM PERFORMANCE CURRENT MONTH ********************************
var achieveArr = [];
var targetArr = [];
<?php
if (!empty($salesPersonCurrentMonthArr)) {
    foreach ($salesPersonCurrentMonthArr as $userId => $salesPersonName) {
        $achieve = !empty($teamCurrentMonthPercent[$userId]['achieve']) ? $teamCurrentMonthPercent[$userId]['achieve'] : 0.00;
        $target = !empty($teamCurrentMonthPercent[$userId]['target']) ? $teamCurrentMonthPercent[$userId]['target'] : 0.00;
        ?>
        achieveArr.push({{$achieve}});
        targetArr.push({{$target}});
        <?php
    }
}
?>
var teamPerformanceCurrentMonthoptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "@lang('label.PERFORMANCE')",
                data: [
<?php
if (!empty($salesPersonCurrentMonthArr)) {
    foreach ($salesPersonCurrentMonthArr as $userId => $salesPersonName) {
        $currentMonthAchivpercent = !empty($teamCurrentMonthPercent[$userId]['percent']) ? $teamCurrentMonthPercent[$userId]['percent'] : 0.00;
        ?>
                        "{{$currentMonthAchivpercent}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($salesPersonCurrentMonthArr)) {
    foreach ($salesPersonCurrentMonthArr as $userId => $salesPersonName) {
        echo "'$salesPersonName', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.SALES_PERSONS')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "@lang('label.PERFORMANCE_PERCENTAGE')"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        custom: function({series, seriesIndex, dataPointIndex, w}) {
        var salesPersonName = w.config.xaxis.categories[dataPointIndex];
        var performanceText = w.config.series[seriesIndex].name;
        var performance = parseFloat(series[seriesIndex][dataPointIndex]).toFixed(2);
        var target = parseFloat(targetArr[dataPointIndex]).toFixed(2);
        var achieve = parseFloat(achieveArr[dataPointIndex]).toFixed(2);
        return '<div class="row custom-chart-x-axis">' +
                '<div class="col-md-12">' +
                '<span>' + salesPersonName + '</span>' +
                '</div>' +
                '</div>' +
                '<div class="row custom-chart-y-axis">' +
                '<div class="col-md-12">' +
                '<button type="button" class="btn btn-xs padding-5 cursor-default" style="background-color:' + w.config.colors[dataPointIndex] + ';"></button>' +
                '&nbsp;<span>' + performanceText + ':&nbsp;' + performance + '%</span><br/>' +
                '<button type="button" class="btn btn-xs padding-5 cursor-default" style="background-color:' + w.config.colors[dataPointIndex] + ';"></button>' +
                '&nbsp;<span>Target&nbsp;(Unit):&nbsp;' + target + '</span><br/>' +
                '<button type="button" class="btn btn-xs padding-5 cursor-default" style="background-color:' + w.config.colors[dataPointIndex] + ';"></button>' +
                '&nbsp;<span>Achievement&nbsp;(Unit):&nbsp;' + achieve + '</span><br/>' +
                '</div>' +
                '</div>'
        }
        }
};
var teamPerformanceCurrentMonth = new ApexCharts(document.querySelector("#teamPerformanceCurrentMonth"), teamPerformanceCurrentMonthoptions);
teamPerformanceCurrentMonth.render();
//END OF TEAM PERFORMANCE CURRENT MONTH *******************************

//******************* START MY SALES STATUS ***************
var mySalesStatusOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors:["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"]
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [

        {
        name: "@lang('label.UPCOMING')",
                data: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandId => $braName) {
        $upcoming = !empty($upcomingSalesVolume[$brandId]) ? $upcomingSalesVolume[$brandId] : 0;
        ?>
                        "{{$upcoming}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.PIPE_LINE')",
                data: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandId => $braName) {
        $pipeLine = !empty($pipeLineSalesVolume[$brandId]) ? $pipeLineSalesVolume[$brandId] : 0;
        ?>
                        "{{$pipeLine}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.CONFIRMED')",
                data: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandId => $braName) {
        $confirmed = !empty($confirmedSalesVolume[$brandId]) ? $confirmedSalesVolume[$brandId] : 0;
        ?>
                        "{{$confirmed}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.ACCOMPLISHED')",
                data: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandId => $braName) {
        $accomplished = !empty($accomplishedSalesVolume[$brandId]) ? $accomplishedSalesVolume[$brandId] : 0;
        ?>
                        "{{$accomplished}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.CANCELLED')",
                data: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandId => $braName) {
        $cancelled = !empty($cancelledSalesVolume[$brandId]) ? $cancelledSalesVolume[$brandId] : 0;
        ?>
                        "{{$cancelled}}",
        <?php
    }
}
?>
                ]
        }

        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: true,
                minHeight: undefined,
                maxHeight: 100,
                offsetX: 0,
                offsetY: 0,
                format: undefined,
                formatter: undefined,
        },
                categories: [
<?php
if (!empty($myBrandArr)) {
    foreach ($myBrandArr as $brandName) {
        echo "'$brandName',";
    }
}
?>
                ],
                title: {
                text: "@lang('label.BRAND')",
                        offsetX: - 40,
                        offsetY: 50,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " Unit"
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 10,
                offsetX: 0,
                width: undefined,
                height: 100,
        }

};
var mySalesStatus = new ApexCharts(
        document.querySelector("#mySalesStatus"),
        mySalesStatusOptions
        );
mySalesStatus.render();
//******************* End OF My Sales Status *********************

//******************* START Team SALES STATUS ***************
var teamSalesStatusOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors:["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ["#4C87B9", "#8E44AD", "#F36A5A", "#1BA39C", "#D91E18"]
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [

        {
        name: "@lang('label.UPCOMING')",
                data: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandId => $braName) {
        $upcomingTeam = !empty($upcomingSalesVolumeTeam[$brandId]) ? $upcomingSalesVolumeTeam[$brandId] : 0;
        ?>
                        "{{$upcomingTeam}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.PIPE_LINE')",
                data: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandId => $braName) {
        $pipeLineTeam = !empty($pipeLineSalesVolumeTeam[$brandId]) ? $pipeLineSalesVolumeTeam[$brandId] : 0;
        ?>
                        "{{$pipeLineTeam}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.CONFIRMED')",
                data: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandId => $braName) {
        $confirmedTeam = !empty($confirmedSalesVolumeTeam[$brandId]) ? $confirmedSalesVolumeTeam[$brandId] : 0;
        ?>
                        "{{$confirmedTeam}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.ACCOMPLISHED')",
                data: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandId => $braName) {
        $accomplishedTeam = !empty($accomplishedSalesVolumeTeam[$brandId]) ? $accomplishedSalesVolumeTeam[$brandId] : 0;
        ?>
                        "{{$accomplishedTeam}}",
        <?php
    }
}
?>
                ]
        },
        {
        name: "@lang('label.CANCELLED')",
                data: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandId => $braName) {
        $cancelledTeam = !empty($cancelledSalesVolumeTeam[$brandId]) ? $cancelledSalesVolumeTeam[$brandId] : 0;
        ?>
                        "{{$cancelledTeam}}",
        <?php
    }
}
?>
                ]
        }

        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: true,
                minHeight: undefined,
                maxHeight: 100,
                offsetX: 0,
                offsetY: 0,
                format: undefined,
                formatter: undefined,
        },
                categories: [
<?php
if (!empty($teamBrandArr)) {
    foreach ($teamBrandArr as $brandName) {
        echo "'$brandName',";
    }
}
?>
                ],
                title: {
                text: "@lang('label.BRAND')",
                        offsetX: - 40,
                        offsetY: 50,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " Unit"
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 10,
                offsetX: 0,
                width: undefined,
                height: 100,
        }
};
var teamSalesStatus = new ApexCharts(
        document.querySelector("#teamSalesStatus"),
        teamSalesStatusOptions
        );
teamSalesStatus.render();
//******************* End OF My Sales Status *********************

//*******************END OF SALES PERSONS PART *******************


/*****************************************************************
 ******************* START SERVICE PERSON PART *******************
 */

//******************* NEXT 15 DAYS ETS SUMMARY ******************

var next15DaysEtsSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
        $.ajax({
        url: "{{ URL::to('dashboard/getEtsSummary')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                date_index: dateIndex,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#etsSummaryModal").modal("show");
                $("#showEtsSummary").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                var errorsHtml = '';
                var errors = jqXhr.responseJSON.message;
                $.each(errors, function (key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                toastr.error('Error', 'Something went wrong', options);
                }
                //                        App.unblockUI();
                }
        }); //ajax
        }
        },
        },
        series: [{
        name: "@lang('label.NO_OF_SHIPMENT')",
                data: [
<?php
if (!empty($next15DaysEtsSummaryArr)) {
    foreach ($next15DaysEtsSummaryArr as $item) {
        ?>
                        "{{$item}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($next15DaysEtsSummaryArr)) {
    foreach ($next15DaysEtsSummaryArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_SHIPMENT')"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var next15DaysEtsSummary = new ApexCharts(document.querySelector("#next15DaysEtsSummary"), next15DaysEtsSummaryOptions);
next15DaysEtsSummary.render();
//****************** END OF NEXT 15 DAYS ETS SUMMARY ************

//** ** ** ** ** ** ** * NEXT 15 DAYS ETA SUMMARY ** ** ** ** ** ** ** ** **
var next15DaysEtaSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
        $.ajax({
        url: "{{ URL::to('dashboard/getEtaSummary')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                date_index: dateIndex,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#etaSummaryModal").modal("show");
                $("#showEtaSummary").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                var errorsHtml = '';
                var errors = jqXhr.responseJSON.message;
                $.each(errors, function (key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                toastr.error('Error', 'Something went wrong', options);
                }
                //                        App.unblockUI();
                }
        }); //ajax
        }
        },
        },
        series: [{
        name: "@lang('label.NO_OF_SHIPMENT')",
                data: [
<?php
if (!empty($next15DaysEtaSummaryArr)) {
    foreach ($next15DaysEtaSummaryArr as $item) {
        ?>
                        "{{$item}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($next15DaysEtaSummaryArr)) {
    foreach ($next15DaysEtaSummaryArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_SHIPMENT')"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var next15DaysEtaSummary = new ApexCharts(document.querySelector("#next15DaysEtaSummary"), next15DaysEtaSummaryOptions);
next15DaysEtaSummary.render();
//****************** END OF NEXT 15 DAYS ETA SUMMARY ************

//** ** ** ** ** ** ** * LAST 15 DAYS INQUIRY SUMMARY ** ** ** ** ** ** ** ** **
var lastFifteenDaysInquirySummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
        $.ajax({
        url: "{{ URL::to('dashboard/getInquirySummary')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                date_index: dateIndex,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#inquirySummaryModal").modal("show");
                $("#showInquirySummary").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                var errorsHtml = '';
                var errors = jqXhr.responseJSON.message;
                $.each(errors, function (key, value) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                toastr.error('Error', 'Something went wrong', options);
                }
                //                        App.unblockUI();
                }
        }); //ajax
        }
        },
        },
        series: [{
        name: "@lang('label.VOLUME')",
                data: [
<?php
if (!empty($last15DaysInquirySummaryArr)) {
    foreach ($last15DaysInquirySummaryArr as $item) {
        echo "'$item',";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($last15DaysInquirySummaryArr)) {
    foreach ($last15DaysInquirySummaryArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  parseFloat(val).toFixed(2) + " @lang('label.UNIT')"
        }
        }
        }
};
var lastFifteenDaysInquirySummary = new ApexCharts(document.querySelector("#lastFifteenDaysInquirySummary"), lastFifteenDaysInquirySummaryOptions);
lastFifteenDaysInquirySummary.render();
//****************** END OF LAST 15 DAYS INQUIRY SUMMARY ************

//Start :: Top 10 Supplier with Payment Due ********************************
var top10SupplierWithPaymentDueOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "@lang('label.AMOUNTS')",
                data: [
<?php
if (!empty($top10SupplierWithPaymentDueArr)) {
    foreach ($top10SupplierWithPaymentDueArr as $supplierId => $due) {
        echo "'$due', ";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return "$" + parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($top10SupplierWithPaymentDueArr)) {
    foreach ($top10SupplierWithPaymentDueArr as $supplierId => $due) {
        $supplier = !empty($supplierList[$supplierId]) ? $supplierList[$supplierId] : '';
        echo "'$supplier', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.SUPPLIERS')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
                axisBorder: {
                show: true,
                        color: '#78909C',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: 0
                },
                axisTicks: {
                show: true,
                        borderType: 'solid',
                        color: '#78909C',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                },
                tickAmount: undefined,
                tickPlacement: 'between',
                min: undefined,
                max: undefined,
                range: undefined,
                floating: false,
                position: 'bottom',
        },
        yaxis: {
        title: {
        text: "@lang('label.AMOUNTS') ($)"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  "$" + parseFloat(val).toFixed(2)
        }
        }
        }

};
var top10SupplierWithPaymentDue = new ApexCharts(document.querySelector("#top10SupplierWithPaymentDue"), top10SupplierWithPaymentDueOptions);
top10SupplierWithPaymentDue.render();
//End :: Top 10 Supplier with Payment Due *******************************

//Start :: Top 10 Buyer with Payment Due ********************************
var top10BuyerWithPaymentDueOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "@lang('label.AMOUNTS')",
                data: [
<?php
if (!empty($top10BuyerWithPaymentDueArr)) {
    foreach ($top10BuyerWithPaymentDueArr as $buyerId => $due) {
        echo "'$due', ";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return "$" + parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($top10BuyerWithPaymentDueArr)) {
    foreach ($top10BuyerWithPaymentDueArr as $buyerId => $due) {
        $buyer = !empty($buyerList[$buyerId]) ? $buyerList[$buyerId] : '';
        echo "'$buyer', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.BUYERS')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
                axisBorder: {
                show: true,
                        color: '#78909C',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: 0
                },
                axisTicks: {
                show: true,
                        borderType: 'solid',
                        color: '#78909C',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                },
                tickAmount: undefined,
                tickPlacement: 'between',
                min: undefined,
                max: undefined,
                range: undefined,
                floating: false,
                position: 'bottom',
        },
        yaxis: {
        title: {
        text: "@lang('label.AMOUNTS') ($)"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  "$" + parseFloat(val).toFixed(2)
        }
        }
        }

};
var top10BuyerWithPaymentDue = new ApexCharts(document.querySelector("#top10BuyerWithPaymentDue"), top10BuyerWithPaymentDueOptions);
top10BuyerWithPaymentDue.render();
//End :: Top 10 Buyer with Payment Due *******************************


//Start :: Top 10 Sales Commission Due ********************************
var top10SalesCommissionDueOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "@lang('label.AMOUNTS')",
                data: [
<?php
if (!empty($top10SalesCommissionDueArr)) {
    foreach ($top10SalesCommissionDueArr as $employeeId => $due) {
        echo "'$due', ";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return "$" + parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($top10SalesCommissionDueArr)) {
    foreach ($top10SalesCommissionDueArr as $employeeId => $due) {
        $employee = !empty($employeeList[$employeeId]) ? $employeeList[$employeeId] : '';
        echo "'$employee', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.EMPLOYEES')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
                axisBorder: {
                show: true,
                        color: '#78909C',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: 0
                },
                axisTicks: {
                show: true,
                        borderType: 'solid',
                        color: '#78909C',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                },
                tickAmount: undefined,
                tickPlacement: 'between',
                min: undefined,
                max: undefined,
                range: undefined,
                floating: false,
                position: 'bottom',
        },
        yaxis: {
        title: {
        text: "@lang('label.AMOUNTS') ($)"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        title:{
        formatter: function (val) {
        return  "Commission"
        }
        },
                formatter: function (val) {
                return  "$" + parseFloat(val).toFixed(2)
                }
        }
        }

};
var top10SalesCommissionDue = new ApexCharts(document.querySelector("#top10SalesCommissionDue"), top10SalesCommissionDueOptions);
top10SalesCommissionDue.render();
//End :: Top 10 Sales Commission Due *******************************


//Start :: Top 10 Buyer Commission Due ********************************
var top10BuyerCommissionDueOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "@lang('label.AMOUNTS')",
                data: [
<?php
if (!empty($top10BuyerCommissionDueArr)) {
    foreach ($top10BuyerCommissionDueArr as $buyerId => $due) {
        echo "'$due', ";
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return "$" + parseFloat(val).toFixed(2);
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($top10BuyerCommissionDueArr)) {
    foreach ($top10BuyerCommissionDueArr as $buyerId => $due) {
        $buyer = !empty($buyerList[$buyerId]) ? $buyerList[$buyerId] : '';
        echo "'$buyer', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.BUYERS')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
                axisBorder: {
                show: true,
                        color: '#78909C',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: 0
                },
                axisTicks: {
                show: true,
                        borderType: 'solid',
                        color: '#78909C',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                },
                tickAmount: undefined,
                tickPlacement: 'between',
                min: undefined,
                max: undefined,
                range: undefined,
                floating: false,
                position: 'bottom',
        },
        yaxis: {
        title: {
        text: "@lang('label.AMOUNTS') ($)"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        title:{
        formatter: function (val) {
        return  "Commission"
        }
        },
                formatter: function (val) {
                return  "$" + parseFloat(val).toFixed(2)
                }
        }
        }

};
var top10BuyerCommissionDue = new ApexCharts(document.querySelector("#top10BuyerCommissionDue"), top10BuyerCommissionDueOptions);
top10BuyerCommissionDue.render();
//End :: Top 10 Buyer Commission Due *******************************

//CRM Schedule Calendar
$('#showCalendar').fullCalendar({

header: {
left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
        },
        defaultDate: "{{ date('Y-m-d') }}",
        navLinks: true,
        editable: true,
        eventLimit: false,
        eventRender: function(eventObj, $el) {
        $el.popover({
        title: eventObj.popTitle,
                content: eventObj.description,
                trigger: 'hover',
                placement: 'top',
                container: '#showCalendar',
                html: true
        });
        },
        eventClick: function(calEvent, jsEvent, view) {
        var opportunityId = calEvent.opportunity;
        var activityKey = calEvent.activityKey;
        var doneColor = calEvent.scheduledone;
        //alert(calEvent.access)

        if (calEvent.scheduleStatus != 1) {
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        var options = {
        closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
        };
<?php
if (!empty($userAccessArr[80][17])) {
    ?>
            swal({
            title: "@lang('label.ARE_YOU_SURE_YOU_WANT_TO_MARK_THIS_SCHEDULE_AS_DONE')",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, Done',
                    cancelButtonText: 'No, Cancel',
                    closeOnConfirm: true,
                    closeOnCancel: true
            }, function (isConfirm) {
            if (isConfirm) {
            $.ajax({
            url: "{{ URL::to('crmScheduleCalendar/scheduleDone')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                    opportunity_id: opportunityId,
                            activity_key: activityKey
                    },
                    success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    location.reload();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                    errorsHtml += '<li>' + value + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                    toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                    }
            });
            }
            });
    <?php
}
?>
        }

        },
        events: [
                @foreach($activityEventArr as $key => $event)
        {
        title: "{!! $event['title'].'\n'.Helper::trimString($event['purpose'])  !!}",
                start: "{!! $event['start_date'] !!}",
                description:"{!! 'Buyer : '.$opportunityArr[$event['opportunity_id']]. '<br/>Schedule Created By : ' . $event['schedule_creator'] . '<br/>Status : <span class=\"label label-sm bold label-' .$event['color'].' \">' . $event['status'] .'</span><br />Schedule Date/Time : '.Helper::formatDateTime($event['start_date']).'<br />Schedule Purpose : '.$event['purpose'] !!}",
                popTitle: "{!! 'Date: '.$event['title'] !!}",
                color: "{!! !empty($event['schedule_status']) && $event['schedule_status'] == 1 ? '#1BA39C' : '#525E64' !!}",
                opportunity: "{!! $event['opportunity_id'] !!}",
                activityKey: "{!! $key !!}",
                scheduleStatus: "{!! $event['schedule_status'] !!}",
                scheduledone: "{!! $event['schedule_done_color'] !!}",
        },
                @endforeach
        ]
});
});
function trimString(str) {
var returnStr = str;
if (typeof(str) != 'undefined'){
var dot = '';
if (str.length > 20) {
dot = '...';
}

var returnStr = str.substring(0, 20) + dot;
}
return returnStr;
}
</script>
@endsection