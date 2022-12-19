<?php
$controllerName = Request::segment(1);
$controllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());

//Admin setup menus
$adminSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : '', !empty($userAccessArr[3][1]) ? 1 : ''
    , !empty($userAccessArr[3][7]) ? 1 : '', !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : ''
    , !empty($userAccessArr[6][1]) ? 1 : '', !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : ''
    , !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : '', !empty($userAccessArr[12][1]) ? 1 : ''
    , !empty($userAccessArr[13][1]) ? 1 : '', !empty($userAccessArr[14][1]) ? 1 : '', !empty($userAccessArr[15][1]) ? 1 : ''
    , !empty($userAccessArr[16][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[21][1]) ? 1 : '', !empty($userAccessArr[24][1]) ? 1 : ''
    , !empty($userAccessArr[25][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : '', !empty($userAccessArr[32][1]) ? 1 : ''
    , !empty($userAccessArr[33][1]) ? 1 : '', !empty($userAccessArr[35][1]) ? 1 : '', !empty($userAccessArr[36][1]) ? 1 : ''
    , !empty($userAccessArr[38][1]) ? 1 : '', !empty($userAccessArr[39][1]) ? 1 : '', !empty($userAccessArr[8][1]) ? 1 : ''
    , !empty($userAccessArr[42][1]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : '', !empty($userAccessArr[43][1]) ? 1 : ''
    , !empty($userAccessArr[44][1]) ? 1 : '', !empty($userAccessArr[45][1]) ? 1 : '', !empty($userAccessArr[52][1]) ? 1 : ''
    , !empty($userAccessArr[59][1]) ? 1 : '', !empty($userAccessArr[66][1]) ? 1 : ''
];
//access control menu
$accessControlMenu = [!empty($userAccessArr[3][1]) ? 1 : '', !empty($userAccessArr[3][7]) ? 1 : ''];
//user setup menu
$userSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : ''
    , !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : ''
    , !empty($userAccessArr[6][1]) ? 1 : ''
];
//product setup menu
$productSetupMenu = [
    !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : ''
    , !empty($userAccessArr[15][1]) ? 1 : '', !empty($userAccessArr[16][1]) ? 1 : '', !empty($userAccessArr[32][1]) ? 1 : ''
    , !empty($userAccessArr[44][1]) ? 1 : '', !empty($userAccessArr[45][1]) ? 1 : ''
];
//supplier setup menu
$supplierSetupMenu = [!empty($userAccessArr[12][1]) ? 1 : '', !empty($userAccessArr[13][1]) ? 1 : '', !empty($userAccessArr[21][1]) ? 1 : ''
    , !empty($userAccessArr[47][1]) ? 1 : ''];
//product setup menu
$buyerSetupMenu = [
    !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[24][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[19][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : ''
    , !empty($userAccessArr[59][1]) ? 1 : '', !empty($userAccessArr[66][1]) ? 1 : ''
];

//bank setup Menu
$bankSetupMenu = [!empty($userAccessArr[38][1]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : ''];


//CRM menu
$crmMenu = [
    !empty($userAccessArr[67][1]) ? 1 : '', !empty($userAccessArr[68][1]) ? 1 : ''
    , !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[70][1]) ? 1 : ''
    , !empty($userAccessArr[71][1]) ? 1 : '', !empty($userAccessArr[72][1]) ? 1 : ''
    , !empty($userAccessArr[74][1]) ? 1 : '', !empty($userAccessArr[75][1]) ? 1 : ''
    , !empty($userAccessArr[76][1]) ? 1 : '', !empty($userAccessArr[78][1]) ? 1 : ''
    , !empty($userAccessArr[79][1]) ? 1 : '', !empty($userAccessArr[80][1]) ? 1 : ''
];

//CRM opportunity menu
$crmOpportunityMenu = [
    !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[71][1]) ? 1 : ''
    , !empty($userAccessArr[72][1]) ? 1 : '', !empty($userAccessArr[74][1]) ? 1 : ''
    , !empty($userAccessArr[75][1]) ? 1 : '', !empty($userAccessArr[76][1]) ? 1 : ''
];

//CRM opportunity distribution menu
$crmOpportunityDistributionMenu = [
    !empty($userAccessArr[70][1]) ? 1 : '', !empty($userAccessArr[78][1]) ? 1 : ''
    , !empty($userAccessArr[79][1]) ? 1 : ''
];

//sales service menus
$salesServiceMenu = [
    !empty($userAccessArr[23][1]) ? 1 : '', !empty($userAccessArr[26][1]) ? 1 : '', !empty($userAccessArr[27][1]) ? 1 : ''
    , !empty($userAccessArr[28][1]) ? 1 : '', !empty($userAccessArr[30][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : ''
    , !empty($userAccessArr[31][1]) ? 1 : '', !empty($userAccessArr[48][2]) ? 1 : '', !empty($userAccessArr[77][1]) ? 1 : ''
];
//inquiry menu
$inquiryMenu = [
    !empty($userAccessArr[23][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : '', !empty($userAccessArr[77][1]) ? 1 : ''
];
//order menu
$orderMenu = [
    !empty($userAccessArr[26][1]) ? 1 : '', !empty($userAccessArr[27][1]) ? 1 : '', !empty($userAccessArr[30][1]) ? 1 : ''
    , !empty($userAccessArr[31][1]) ? 1 : ''
];

//billing setup menus
$billingSetupMenu = [
    !empty($userAccessArr[41][2]) ? 1 : '', !empty($userAccessArr[41][1]) ? 1 : '', !empty($userAccessArr[81][1]) ? 1 : ''
];

//payment setup menus
$paymentSetupMenu = [
    !empty($userAccessArr[46][2]) ? 1 : '', !empty($userAccessArr[50][1]) ? 1 : ''
    , !empty($userAccessArr[60][2]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : ''
    , !empty($userAccessArr[62][1]) ? 1 : '', !empty($userAccessArr[63][2]) ? 1 : ''
    , !empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''
];

//sales person payment setup menus
$salesPersonPaymentSetupMenu = [
    !empty($userAccessArr[60][2]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : ''
    , !empty($userAccessArr[62][1]) ? 1 : ''
];

//buyer payment setup menus
$buyerPaymentSetupMenu = [
    !empty($userAccessArr[63][2]) ? 1 : '', !empty($userAccessArr[64][1]) ? 1 : ''
    , !empty($userAccessArr[65][1]) ? 1 : ''
];



//report menus
$reportMenu = [
    !empty($userAccessArr[49][1]) ? 1 : '', !empty($userAccessArr[51][1]) ? 1 : ''
    , !empty($userAccessArr[53][1]) ? 1 : '', !empty($userAccessArr[54][1]) ? 1 : ''
    , !empty($userAccessArr[55][1]) ? 1 : '', !empty($userAccessArr[56][1]) ? 1 : ''
    , !empty($userAccessArr[57][1]) ? 1 : '', !empty($userAccessArr[58][1]) ? 1 : ''
    , !empty($userAccessArr[73][1]) ? 1 : '', !empty($userAccessArr[83][1]) ? 1 : ''
    , !empty($userAccessArr[84][1]) ? 1 : '', !empty($userAccessArr[86][1]) ? 1 : ''
];

//CRM report menu
$crmReportMenu = [
    !empty($userAccessArr[83][1]) ? 1 : '', !empty($userAccessArr[84][1]) ? 1 : ''
];
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dashboard')}}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>

            <!--Start :: Buyer Account Menus-->
            @if(Auth::user()->group_id == 0)
            <li <?php $current = ( in_array($controllerName, array('buyerprofile'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/buyerProfile')}}" class="nav-link ">
                    <i class="fa fa-user-secret"></i>
                    <span class="title"> @lang('label.MY_PROFILE')</span>
                </a>
            </li>


            <li <?php $current = ( in_array($controllerName, array('productcatalog'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/productCatalog')}}" class="nav-link ">
                    <i class="fa fa-cubes"></i>
                    <span class="title"> @lang('label.PRODUCT_CATALOG')</span>
                </a>
            </li>

            <!--messaging from buyer-->
            <li <?php $current = ( in_array($controllerName, array('buyermessaging'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/buyerMessaging')}}" class="nav-link ">
                    <i class="fa fa-commenting"></i>
                    <span class="title"> @lang('label.MESSAGE')</span>
                </a>
            </li>

            <li <?php $current = ( in_array($controllerName, array('buyerquotationrequest'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/buyerQuotationRequest')}}" class="nav-link ">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title"> @lang('label.QUOTATION_REQUEST')</span>
                </a>
            </li>

            <li <?php $current = ( in_array($controllerName, array('buyerorder'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/buyerOrder')}}" class="nav-link ">
                    <i class="fa fa-retweet"></i>
                    <span class="title"> @lang('label.ORDER')</span>
                </a>
            </li>

            <li <?php
            $current = ( in_array($controllerName, array('ordersummaryreport', 'purchasesummaryreport', 'brandwisepurchasesummaryreport'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-line-chart"></i>
                    <span class="title">@lang('label.REPORTS')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('ordersummaryreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/orderSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.ORDER_SUMMARY')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('purchasesummaryreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/purchaseSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.PURCHASE_SUMMARY')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('brandwisepurchasesummaryreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/brandWisePurchaseSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.BRAND_WISE_PURCHASE_SUMMARY')</span>
                        </a>
                    </li>
                </ul>
            </li>

            @endif
            <!--End :: Buyer Account Menus-->

            <!-- CRM related menus -->
            @if(in_array(1, $crmMenu))
            <li <?php
            $current = ( in_array($controllerName, array('crmactivitytype', 'crmsource', 'crmnewopportunity', 'crmopportunitytomember'
                        , 'crmmyopportunity', 'crmallopportunity', 'crmbookedopportunity', 'crmvoidopportunity', 'crmcancelledopportunity'
                        , 'crmreassignmentopportunity', 'crmrevokeopportunity', 'crmschedulecalendar'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-simplybuilt"></i>
                    <span class="title">@lang('label.CRM')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--Source Mgt--> 
                    @if(!empty($userAccessArr[68][1]))
                    <li <?php $current = ( in_array($controllerName, array('crmsource'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/crmSource')}}" class="nav-link ">
                            <span class="title">@lang('label.SOURCE')</span>
                        </a>
                    </li>
                    @endif
                    <!--Source Mgt Ends--> 

                    <!--Activity Type Mgt--> 
                    @if(!empty($userAccessArr[67][1]))
                    <li <?php $current = ( in_array($controllerName, array('crmactivitytype'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/crmActivityType')}}" class="nav-link ">
                            <span class="title">@lang('label.ACTIVITY_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    <!--Activity Type Mgt Ends--> 

                    <!--Start :: CRM opportunity menu--> 
                    @if(in_array(1, $crmOpportunityMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('crmnewopportunity', 'crmmyopportunity', 'crmallopportunity'
                                , 'crmbookedopportunity', 'crmvoidopportunity', 'crmcancelledopportunity'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.OPPORTUNITY')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <!--New Opportunity-->
                            @if(!empty($userAccessArr[69][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmnewopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmNewOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.NEW_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif
                            <!--My Opportunity-->
                            @if(!empty($userAccessArr[71][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmmyopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmMyOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.MY_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif
                            <!--Booked Opportunity-->
                            @if(!empty($userAccessArr[72][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmbookedopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmBookedOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.BOOKED_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif
                            <!--Void Opportunity-->
                            @if(!empty($userAccessArr[76][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmvoidopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmVoidOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.VOID_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif
                            <!--Cancelled Opportunity-->
                            @if(!empty($userAccessArr[75][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmcancelledopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmCancelledOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.CANCELLED_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif

                            <!--All Opportunity-->
                            @if(Auth::user()->for_crm_leader == '1' || Auth::user()->group_id =='1' || (Auth::user()->allowed_for_crm == '1' && Auth::user()->allowed_for_sales == '0'))
                            @if(!empty($userAccessArr[74][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmallopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmAllOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.ALL_OPPORTUNITY')</span>
                                </a>
                            </li>
                            @endif
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--End :: CRM opportunity menu--> 

                    <!--START:: CRM opportunity distribution menu--> 
                    @if(Auth::user()->for_crm_leader == '1')
                    @if(in_array(1, $crmOpportunityDistributionMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('crmopportunitytomember', 'crmreassignmentopportunity', 'crmrevokeopportunity'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.OPPORTUNITY_DISTRIBUTION')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[70][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmopportunitytomember'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/crmOpportunityToMember')}}" class="nav-link ">
                                    <span class="title">@lang('label.ASSIGN')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[78][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmreassignmentopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmReassignmentOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.REASSIGN')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[79][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmrevokeopportunity'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/crmRevokeOpportunity')}}" class="nav-link ">
                                    <span class="title">@lang('label.REVOKE')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @endif
                    <!--END:: CRM opportunity distribution menu-->

                    <!-- START:: CRM Schedule Calendar --->
                    @if(Auth::user()->allowed_for_crm == '1' || Auth::user()->group_id=='1')
                    @if(!empty($userAccessArr[80][1]))
                    <li <?php $current = ( in_array($controllerName, array('crmschedulecalendar'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/crmScheduleCalendar')}}" class="nav-link ">
                            <span class="title"> @lang('label.SCHEDULE')</span>
                        </a>
                    </li>
                    @endif
                    @endif
                    <!-- END:: CRM Schedule Calendar --->
                </ul>
            </li>
            @endif
            <!--endof :: CRM menus-->

            <!--sales/service activities related menus-->
            @if(in_array(1, $salesServiceMenu))
            <li <?php
            $current = ( in_array($controllerName, array('lead', 'pendingorder', 'confirmedorder', 'delivery'
                        , 'cancelledorder', 'cancelledinquiry', 'accomplishedorder', 'paymentstatus'
                        , 'pendinginquiry'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-th"></i>
                    <span class="title">@lang('label.SALES_SERVICE')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!-- inquiry menu -->
                    @if(in_array(1, $inquiryMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('lead', 'cancelledinquiry', 'pendinginquiry'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.INQUIRY')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[77][1]))
<!--                            <li <?php $current = ( in_array($controllerName, array('pendinginquiry'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/pendingInquiry')}}" class="nav-link ">
                                    <span class="title">@lang('label.PENDING_INQUIRY')</span>
                                </a>
                            </li>-->
                            @endif
                            @if(!empty($userAccessArr[23][1]))
                            <li <?php $current = ( in_array($controllerName, array('lead'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/lead')}}" class="nav-link ">
                                    <span class="title">@lang('label.NEW_LEAD')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[29][1]))
                            <li <?php $current = ( in_array($controllerName, array('cancelledinquiry'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/cancelledInquiry')}}" class="nav-link ">
                                    <span class="title">@lang('label.CANCELLED_INQUIRY')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- order menu -->
                    @if(in_array(1, $orderMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('pendingorder', 'confirmedorder', 'cancelledorder', 'accomplishedorder'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.ORDER')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[26][1]))
                            <li <?php $current = ( in_array($controllerName, array('pendingorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/pendingOrder')}}" class="nav-link ">
                                    <span class="title">@lang('label.PENDING_ORDER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[27][1]))
                            <li <?php $current = ( in_array($controllerName, array('confirmedorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/confirmedOrder')}}" class="nav-link ">
                                    <span class="title">@lang('label.CONFIRMED_ORDER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[31][1]))
                            <li <?php $current = ( in_array($controllerName, array('accomplishedorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/accomplishedOrder')}}" class="nav-link ">
                                    <span class="title">@lang('label.ACCOMPLISHED_ORDER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[30][1]))
                            <li <?php $current = ( in_array($controllerName, array('cancelledorder'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/cancelledOrder')}}" class="nav-link ">
                                    <span class="title">@lang('label.CANCELLED_ORDER')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[48][2]))
                    <li <?php $current = ( in_array($controllerName, array('paymentstatus'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/paymentStatus')}}" class="nav-link ">
                            <span class="title">@lang('label.PAYMENT_STATUS')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!--endof :: sales & service menus-->

            <!--Billing Information activities related menus-->
            @if(in_array(1, $billingSetupMenu))
            <li <?php
            $current = (in_array($routeName, array('billing/billingcreate', 'billing/billingledgerview')) || in_array($controllerName, array('principalledger'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-retweet"></i>
                    <span class="title">@lang('label.BILLING_INFORMATION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[41][2]))
                    <li <?php $current = ( in_array($routeName, array('billing/billingcreate'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('billing/billingCreate')}}" class="nav-link ">
                            <span class="title">@lang('label.RECEIVABLE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[41][1]))
                    <li <?php $current = (in_array($routeName, array('billing/billingledgerview'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('billing/billingLedgerView')}}" class="nav-link ">
                            <span class="title">@lang('label.INVOICE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[81][1]))
                    <li <?php $current = ( in_array($controllerName, array('principalledger'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('principalLedger')}}" class="nav-link ">
                            <span class="title">@lang('label.PRINCIPAL_LEDGER')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!--endof :: billing menus-->

            <!--Payment activities related menus-->
            @if(in_array(1, $paymentSetupMenu))
            <li <?php
            $current = (in_array($controllerName, array('receive', 'supplierledger', 'salespersonpayment'
                        , 'salespersonpaymentvoucher', 'salespersonledger', 'buyerpayment', 'buyerpaymentvoucher'
                        , 'buyerledger'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-usd"></i>
                    <span class="title">@lang('label.PAYMENT_INFORMATION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--start :: supplier payment-->
                    @if(!empty($userAccessArr[46][2]))
                    <li <?php $current = ( in_array($controllerName, array('receive'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('receive')}}" class="nav-link ">
                            <span class="title">@lang('label.RECEIVE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[50][1]))
                    <li <?php $current = ( in_array($controllerName, array('supplierledger'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('supplierLedger')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_LEDGER')</span>
                        </a>
                    </li>
                    @endif
                    <!--end :: supplier payment-->

                    <!--start :: sales person payment-->
                    @if(in_array(1, $salesPersonPaymentSetupMenu))
                    <li <?php
                    $current = (in_array($controllerName, array('salespersonpayment', 'salespersonpaymentvoucher'
                                , 'salespersonledger'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.SALES_PERSON_PAYMENT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[60][2]))
                            <li <?php $current = ( in_array($controllerName, array('salespersonpayment'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('salesPersonPayment')}}" class="nav-link ">
                                    <span class="title">@lang('label.PAYMENT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[61][1]))
                            <li <?php $current = ( in_array($controllerName, array('salespersonpaymentvoucher'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('salesPersonPaymentVoucher')}}" class="nav-link ">
                                    <span class="title">@lang('label.PAYMENT_VOUCHER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[62][1]))
                            <li <?php $current = ( in_array($controllerName, array('salespersonledger'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('salesPersonLedger')}}" class="nav-link ">
                                    <span class="title">@lang('label.LEDGER')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--end :: sales person payment-->

                    <!--start :: buyer payment-->
                    @if(in_array(1, $buyerPaymentSetupMenu))
                    <li <?php
                    $current = (in_array($controllerName, array('buyerpayment', 'buyerpaymentvoucher'
                                , 'buyerledger'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.BUYER_PAYMENT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[63][2]))
                            <li <?php $current = ( in_array($controllerName, array('buyerpayment'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('buyerPayment')}}" class="nav-link ">
                                    <span class="title">@lang('label.PAYMENT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[64][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyerpaymentvoucher'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('buyerPaymentVoucher')}}" class="nav-link ">
                                    <span class="title">@lang('label.PAYMENT_VOUCHER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[65][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyerledger'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('buyerLedger')}}" class="nav-link ">
                                    <span class="title">@lang('label.LEDGER')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--end :: buyer payment-->
                </ul>
            </li>
            @endif
            <!--endof :: payment menus-->

            <!-- start buyer message menu -->
            @if(Auth::user()->allowed_for_messaging == '1')
            @if(!empty($userAccessArr[87][1]) && !empty($userAccessArr[87][17]))
            <li <?php $current = ( in_array($controllerName, array('buyermessage'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/buyerMessage')}}" class="nav-link ">
                    <i class="fa fa-commenting"></i>
                    <span class="title"> @lang('label.BUYER_MESSAGE')</span>
                </a>
            </li>
            @endif
            @endif

            <!-- start buyer Quotation menu -->
            @if(Auth::user()->allowed_to_view_quotation == '1')
            @if(!empty($userAccessArr[88][1]) && !empty($userAccessArr[88][5]))
            <li <?php $current = ( in_array($controllerName, array('quotationrequest'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/quotationRequest')}}" class="nav-link ">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title"> @lang('label.BUYER_QUOTATION_REQ')</span>
                </a>
            </li>
            @endif
            @endif


            <!-- User Group wise common feature set up -->
            @if(in_array(1, $adminSetupMenu))
            <li <?php
            $current = ( in_array($controllerName, array('user', 'usergroup', 'department', 'designation',
                        'configuration', 'certificate', 'buyer', 'factory', 'brand', 'supplierclassification', 'supplier',
                        'productcategory', 'measureunit', 'product', 'branch', 'causeoffailure',
                        'color', 'aclusergrouptoaccess', 'buyercategory', 'salespersontoproduct'
                        , 'salespersontobuyer', 'buyerfactory', 'salestarget', 'suppliertoproduct'
                        , 'finishedgoods', 'rwunit', 'buyertoproduct', 'producttobrand'
                        , 'precarrier', 'paymentterms', 'shippingterms', 'bank', 'contactdesignation'
                        , 'configuration', 'shippingline', 'konitabank', 'grade', 'beneficiarybank'
                        , 'certificate', 'producttograde', 'followupstatus', 'transferbuyertosalesperson'
                        , 'buyerfollowup'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">@lang('label.ADMINISTRATIVE_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!-- access control setup -->
                    @if(in_array(1, $accessControlMenu))
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.ACCESS_CONTROL_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[3][7]))
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/aclUserGroupToAccess/userGroupToAccess')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[3][1]))
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('aclUserGroupToAccess/moduleAccessControl/')}}" class="nav-link">
                                    <span class="title">@lang('label.MODULE_WISE_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- User setup -->
                    @if(in_array(1, $userSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('department', 'designation', 'branch'
                                , 'usergroup', 'user'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.USER_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[2][1]))
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/userGroup')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[4][1]))
                            <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/department')}}" class="nav-link ">
                                    <span class="title">@lang('label.DEPARTMENT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[5][1]))
                            <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/designation')}}" class="nav-link ">
                                    <span class="title">@lang('label.DESIGNATION')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[6][1]))
                            <li <?php $current = ( in_array($controllerName, array('branch'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/branch')}}" class="nav-link ">
                                    <span class="title">@lang('label.BRANCH')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[1][1]))
                            <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/user')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- product setup -->
                    @if(in_array(1, $productSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('productcategory', 'measureunit', 'brand'
                                , 'product', 'salespersontoproduct', 'producttobrand', 'grade', 'producttograde'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.PRODUCT_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[7][1]))
                            <li <?php $current = ( in_array($controllerName, array('productcategory'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/productCategory')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_CATEGORY')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[9][1]))
                            <li <?php $current = ( in_array($controllerName, array('measureunit'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/measureUnit')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_MEASUREMENT_UNIT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[11][1]))
                            <li <?php $current = ( in_array($controllerName, array('brand'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/brand')}}" class="nav-link ">
                                    <span class="title">@lang('label.BRAND')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[44][1]))
                            <li <?php $current = ( in_array($controllerName, array('grade'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/grade')}}" class="nav-link ">
                                    <span class="title">@lang('label.GRADE')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[15][1]))
                            <li <?php $current = ( in_array($controllerName, array('product')) && ($routeName != 'product/approvalproduct' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/product')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[32][1]))
                            <li <?php $current = ( in_array($controllerName, array('producttobrand'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/productToBrand')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_TO_BRAND')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[45][1]))
                            <li <?php $current = ( in_array($controllerName, array('producttograde'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/productToGrade')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_TO_GRADE')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[16][1]))
                            <li <?php $current = ( in_array($controllerName, array('salespersontoproduct'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/salesPersonToProduct')}}" class="nav-link ">
                                    <span class="title">@lang('label.SALES_PERSON_TO_PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- contact designation -->
                    @if(!empty($userAccessArr[39][1]))
                    <li <?php $current = ( in_array($controllerName, array('contactdesignation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/contactDesignation')}}" class="nav-link ">
                            <span class="title">@lang('label.CONTACT_DESIGNATION')</span>
                        </a>
                    </li>
                    @endif
                    <!-- Contact Designation Ends -->
                    <!-- supplier setup -->
                    @if(in_array(1, $supplierSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('supplierclassification', 'supplier', 'suppliertoproduct', 'beneficiarybank'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.SUPPLIER_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[12][1]))
                            <li <?php $current = ( in_array($controllerName, array('supplierclassification'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/supplierClassification')}}" class="nav-link ">
                                    <span class="title">@lang('label.SUPPLIER_CLASSIFICATION')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[13][1]))
                            <li <?php $current = ( in_array($controllerName, array('supplier'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/supplier')}}" class="nav-link ">
                                    <span class="title">@lang('label.SUPPLIER')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[47][1]))
                            <li <?php $current = ( in_array($controllerName, array('beneficiarybank'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/beneficiaryBank')}}" class="nav-link ">
                                    <span class="title">@lang('label.BENEFICIARY_BANK')</span>
                                </a>
                            </li>
                            @endif

                            @if(!empty($userAccessArr[21][1]))
                            <li <?php $current = ( in_array($controllerName, array('suppliertoproduct'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/supplierToProduct')}}" class="nav-link ">
                                    <span class="title">@lang('label.SUPPLIER_TO_PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- buyer setup -->
                    @if(in_array(1, $buyerSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('buyercategory', 'finishedgoods', 'buyer'
                                , 'buyerfactory', 'salespersontobuyer', 'buyertoproduct'
                                , 'transferbuyertosalesperson', 'buyerfollowup'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.BUYER_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[10][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyercategory'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/buyerCategory')}}" class="nav-link ">
                                    <span class="title">@lang('label.BUYER_CATEGORY')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[24][1]))
                            <li <?php $current = ( in_array($controllerName, array('finishedgoods'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/finishedGoods')}}" class="nav-link ">
                                    <span class="title">@lang('label.FINISHED_GOODS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[18][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/buyer')}}" class="nav-link ">
                                    <span class="title">@lang('label.BUYER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[19][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyerfactory'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/buyerFactory')}}" class="nav-link ">
                                    <span class="title">@lang('label.BUYER_FACTORY')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[17][1]))
                            <li <?php $current = ( in_array($controllerName, array('salespersontobuyer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/salesPersonToBuyer')}}" class="nav-link ">
                                    <span class="title">@lang('label.SALES_PERSON_TO_BUYER')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[22][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyertoproduct'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/buyerToProduct')}}" class="nav-link ">
                                    <span class="title">@lang('label.BUYER_TO_PRODUCT')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[66][1]))
                            <li <?php $current = ( in_array($controllerName, array('buyerfollowup'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/buyerFollowup')}}" class="nav-link ">
                                    <span class="title">@lang('label.BUYER_FOLLOWUP')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[59][1]))
                            <li <?php $current = ( in_array($controllerName, array('transferbuyertosalesperson'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('/transferBuyerToSalesPerson')}}" class="nav-link ">
                                    <span class="title">@lang('label.TRANSFER_BUYER_TO_SALES_PERSON')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--Start new mgt-->
                    @if(!empty($userAccessArr[8][1]))
                    <li <?php $current = ( in_array($controllerName, array('configuration'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/configuration')}}" class="nav-link ">
                            <span class="title">@lang('label.CONFIGURATION')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[33][1]))
                    <li <?php $current = ( in_array($controllerName, array('precarrier'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/preCarrier')}}" class="nav-link ">
                            <span class="title">@lang('label.PRECARRIER')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[35][1]))
                    <li <?php $current = ( in_array($controllerName, array('paymentterms'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/paymentTerms')}}" class="nav-link ">
                            <span class="title">@lang('label.PAYMENTTERMS')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[36][1]))
                    <li <?php $current = ( in_array($controllerName, array('shippingterms'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/shippingTerms')}}" class="nav-link ">
                            <span class="title">@lang('label.SHIPPINGTERMS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[42][1]))
                    <li <?php $current = ( in_array($controllerName, array('shippingline'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/shippingLine')}}" class="nav-link ">
                            <span class="title">@lang('label.SHIPPING_LINE')</span>
                        </a>
                    </li>
                    @endif
                    <!-- bank setup -->
                    @if(in_array(1, $bankSetupMenu))
                    <li <?php
                    $current = ( in_array($controllerName, array('bank', 'konitabank'))) ? 'start active open' : '';
                    ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.BANK_SETUP')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[38][1]))
                            <li <?php $current = ( in_array($controllerName, array('bank'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/bank')}}" class="nav-link ">
                                    <span class="title">@lang('label.CLIENT_BANK')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[40][1]))
                            <li <?php $current = ( in_array($controllerName, array('konitabank'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/konitaBank')}}" class="nav-link ">
                                    <span class="title">@lang('label.KONITA_BANK')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--end new mgt-->
                    <!--followup status-->
                    @if(!empty($userAccessArr[52][1]))
                    <li <?php $current = ( in_array($controllerName, array('followupstatus'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/followupStatus')}}" class="nav-link ">
                            <span class="title">@lang('label.FOLLOWUP_STATUS')</span>
                        </a>
                    </li>
                    @endif
                    <!--end :: followup status-->
                    @if(!empty($userAccessArr[20][1]))
                    <li <?php $current = ( in_array($controllerName, array('salestarget'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/salesTarget')}}" class="nav-link ">
                            <span class="title">@lang('label.SALES_TARGET')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[14][1]))
                    <li <?php $current = ( in_array($controllerName, array('causeoffailure'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/causeOfFailure')}}" class="nav-link ">
                            <span class="title">@lang('label.CAUSE_OF_FAILURE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[25][1]))
                    <li <?php $current = ( in_array($controllerName, array('rwunit'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/rwUnit')}}" class="nav-link ">
                            <span class="title">@lang('label.RW_UNIT')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[43][1]))
                    <li <?php $current = ( in_array($controllerName, array('certificate'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/certificate')}}" class="nav-link ">
                            <span class="title">@lang('label.CERTIFICATE')</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            <!--endof :: admin setup menus-->

            <!-- start db backup menu -->
            @if(!empty($userAccessArr[85][1]))
            <li <?php $current = ( in_array($controllerName, array('dbbackup'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dbBackup')}}" class="nav-link ">
                    <i class="fa fa-database"></i>
                    <span class="title"> @lang('label.DB_BACKUP')</span>
                </a>
            </li>
            @endif

            <!--report menus-->
            @if(in_array(1, $reportMenu))
            <li <?php
            $current = (in_array($controllerName, array('salesvolumereport', 'salesstatusreport', 'marketengagement'
                        , 'newmarketforecast', 'salessummaryreport', 'brandwisesalessummaryreport', 'supplierwisesalessummaryreport'
                        , 'buyersummaryreport', 'idlyengagedbuyerreport', 'crmstatusreport', 'crmsummaryreport'
                        , 'dbbackupdownloadlogreport'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-book"></i>
                    <span class="title">@lang('label.REPORT')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[86][1]))
                    <li <?php $current = ( in_array($controllerName, array('dbbackupdownloadlogreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/dbBackupDownloadLogReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.DB_BACKUP_DOWNLOAD_LOG')</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(1, $crmReportMenu))
                    <li <?php
                    $current = (in_array($controllerName, array('crmstatusreport', 'crmsummaryreport'))) ? 'start active open' : '';
                    ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.CRM')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[83][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmstatusreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('crmStatusReport')}}" class="nav-link ">
                                    <span class="title">@lang('label.CRM_STATUS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[84][1]))
                            <li <?php $current = ( in_array($controllerName, array('crmsummaryreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                                <a href="{{url('crmSummaryReport')}}" class="nav-link ">
                                    <span class="title">@lang('label.CRM_SUMMARY')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[49][1]))
                    <li <?php $current = ( in_array($controllerName, array('salesvolumereport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('salesVolumeReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SALES_VOLUME')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[51][1]))
                    <li <?php $current = ( in_array($controllerName, array('salesstatusreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('salesStatusReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SALES_STATUS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[55][1]))
                    <li <?php $current = ( in_array($controllerName, array('salessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('salesSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SALES_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[56][1]))
                    <li <?php $current = ( in_array($controllerName, array('brandwisesalessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('brandWiseSalesSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.BRAND_WISE_SALES_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[57][1]))
                    <li <?php $current = ( in_array($controllerName, array('supplierwisesalessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('supplierWiseSalesSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_WISE_SALES_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[58][1]))
                    <li <?php $current = ( in_array($controllerName, array('buyersummaryreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('buyerSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.BUYER_SUMMARY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[73][1]))
                    <li <?php $current = ( in_array($controllerName, array('idlyengagedbuyerreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('idlyEngagedBuyerReport')}}" class="nav-link ">
                            <span class="title">@lang('label.BUYER_ENGAGEMENT_IDLE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[53][1]))
                    <li <?php $current = ( in_array($controllerName, array('marketengagement'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('marketEngagement')}}" class="nav-link ">
                            <span class="title">@lang('label.MARKET_ENGAGEMENT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[54][1]))
                    <li <?php $current = ( in_array($controllerName, array('newmarketforecast'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('newMarketForecast')}}" class="nav-link ">
                            <span class="title">@lang('label.NEW_MARKET_FORECAST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!--endof :: report menus-->
        </ul>
    </div>
</div>