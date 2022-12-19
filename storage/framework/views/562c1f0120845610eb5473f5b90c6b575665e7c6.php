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
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/dashboard')); ?>" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> <?php echo app('translator')->get('label.DASHBOARD'); ?></span>
                </a>
            </li>

            <!--Start :: Buyer Account Menus-->
            <?php if(Auth::user()->group_id == 0): ?>
            <li <?php $current = ( in_array($controllerName, array('buyerprofile'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/buyerProfile')); ?>" class="nav-link ">
                    <i class="fa fa-user-secret"></i>
                    <span class="title"> <?php echo app('translator')->get('label.MY_PROFILE'); ?></span>
                </a>
            </li>


            <li <?php $current = ( in_array($controllerName, array('productcatalog'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/productCatalog')); ?>" class="nav-link ">
                    <i class="fa fa-cubes"></i>
                    <span class="title"> <?php echo app('translator')->get('label.PRODUCT_CATALOG'); ?></span>
                </a>
            </li>

            <!--messaging from buyer-->
            <li <?php $current = ( in_array($controllerName, array('buyermessaging'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/buyerMessaging')); ?>" class="nav-link ">
                    <i class="fa fa-commenting"></i>
                    <span class="title"> <?php echo app('translator')->get('label.MESSAGE'); ?></span>
                </a>
            </li>

            <li <?php $current = ( in_array($controllerName, array('buyerquotationrequest'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/buyerQuotationRequest')); ?>" class="nav-link ">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title"> <?php echo app('translator')->get('label.QUOTATION_REQUEST'); ?></span>
                </a>
            </li>

            <li <?php $current = ( in_array($controllerName, array('buyerorder'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/buyerOrder')); ?>" class="nav-link ">
                    <i class="fa fa-retweet"></i>
                    <span class="title"> <?php echo app('translator')->get('label.ORDER'); ?></span>
                </a>
            </li>

            <li <?php
            $current = ( in_array($controllerName, array('ordersummaryreport', 'purchasesummaryreport', 'brandwisepurchasesummaryreport'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-line-chart"></i>
                    <span class="title"><?php echo app('translator')->get('label.REPORTS'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('ordersummaryreport'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/orderSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.ORDER_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('purchasesummaryreport'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/purchaseSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PURCHASE_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('brandwisepurchasesummaryreport'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/brandWisePurchaseSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BRAND_WISE_PURCHASE_SUMMARY'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php endif; ?>
            <!--End :: Buyer Account Menus-->

            <!-- CRM related menus -->
            <?php if(in_array(1, $crmMenu)): ?>
            <li <?php
            $current = ( in_array($controllerName, array('crmactivitytype', 'crmsource', 'crmnewopportunity', 'crmopportunitytomember'
                        , 'crmmyopportunity', 'crmallopportunity', 'crmbookedopportunity', 'crmvoidopportunity', 'crmcancelledopportunity'
                        , 'crmreassignmentopportunity', 'crmrevokeopportunity', 'crmschedulecalendar'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-simplybuilt"></i>
                    <span class="title"><?php echo app('translator')->get('label.CRM'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--Source Mgt--> 
                    <?php if(!empty($userAccessArr[68][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('crmsource'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/crmSource')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SOURCE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!--Source Mgt Ends--> 

                    <!--Activity Type Mgt--> 
                    <?php if(!empty($userAccessArr[67][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('crmactivitytype'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/crmActivityType')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.ACTIVITY_TYPE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!--Activity Type Mgt Ends--> 

                    <!--Start :: CRM opportunity menu--> 
                    <?php if(in_array(1, $crmOpportunityMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('crmnewopportunity', 'crmmyopportunity', 'crmallopportunity'
                                , 'crmbookedopportunity', 'crmvoidopportunity', 'crmcancelledopportunity'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.OPPORTUNITY'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <!--New Opportunity-->
                            <?php if(!empty($userAccessArr[69][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmnewopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmNewOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.NEW_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!--My Opportunity-->
                            <?php if(!empty($userAccessArr[71][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmmyopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmMyOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.MY_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!--Booked Opportunity-->
                            <?php if(!empty($userAccessArr[72][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmbookedopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmBookedOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BOOKED_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!--Void Opportunity-->
                            <?php if(!empty($userAccessArr[76][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmvoidopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmVoidOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.VOID_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!--Cancelled Opportunity-->
                            <?php if(!empty($userAccessArr[75][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmcancelledopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmCancelledOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CANCELLED_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <!--All Opportunity-->
                            <?php if(Auth::user()->for_crm_leader == '1' || Auth::user()->group_id =='1' || (Auth::user()->allowed_for_crm == '1' && Auth::user()->allowed_for_sales == '0')): ?>
                            <?php if(!empty($userAccessArr[74][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmallopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmAllOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.ALL_OPPORTUNITY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!--End :: CRM opportunity menu--> 

                    <!--START:: CRM opportunity distribution menu--> 
                    <?php if(Auth::user()->for_crm_leader == '1'): ?>
                    <?php if(in_array(1, $crmOpportunityDistributionMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('crmopportunitytomember', 'crmreassignmentopportunity', 'crmrevokeopportunity'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.OPPORTUNITY_DISTRIBUTION'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[70][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmopportunitytomember'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/crmOpportunityToMember')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.ASSIGN'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[78][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmreassignmentopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmReassignmentOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.REASSIGN'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[79][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmrevokeopportunity'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/crmRevokeOpportunity')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.REVOKE'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    <!--END:: CRM opportunity distribution menu-->

                    <!-- START:: CRM Schedule Calendar --->
                    <?php if(Auth::user()->allowed_for_crm == '1' || Auth::user()->group_id=='1'): ?>
                    <?php if(!empty($userAccessArr[80][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('crmschedulecalendar'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/crmScheduleCalendar')); ?>" class="nav-link ">
                            <span class="title"> <?php echo app('translator')->get('label.SCHEDULE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    <!-- END:: CRM Schedule Calendar --->
                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: CRM menus-->

            <!--sales/service activities related menus-->
            <?php if(in_array(1, $salesServiceMenu)): ?>
            <li <?php
            $current = ( in_array($controllerName, array('lead', 'pendingorder', 'confirmedorder', 'delivery'
                        , 'cancelledorder', 'cancelledinquiry', 'accomplishedorder', 'paymentstatus'
                        , 'pendinginquiry'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-th"></i>
                    <span class="title"><?php echo app('translator')->get('label.SALES_SERVICE'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!-- inquiry menu -->
                    <?php if(in_array(1, $inquiryMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('lead', 'cancelledinquiry', 'pendinginquiry'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.INQUIRY'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[77][1])): ?>
<!--                            <li <?php $current = ( in_array($controllerName, array('pendinginquiry'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/pendingInquiry')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PENDING_INQUIRY'); ?></span>
                                </a>
                            </li>-->
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[23][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('lead'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/lead')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.NEW_LEAD'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[29][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('cancelledinquiry'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/cancelledInquiry')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CANCELLED_INQUIRY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- order menu -->
                    <?php if(in_array(1, $orderMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('pendingorder', 'confirmedorder', 'cancelledorder', 'accomplishedorder'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.ORDER'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[26][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('pendingorder'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/pendingOrder')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PENDING_ORDER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[27][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('confirmedorder'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/confirmedOrder')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CONFIRMED_ORDER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[31][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('accomplishedorder'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/accomplishedOrder')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.ACCOMPLISHED_ORDER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[30][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('cancelledorder'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/cancelledOrder')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CANCELLED_ORDER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[48][2])): ?>
                    <li <?php $current = ( in_array($controllerName, array('paymentstatus'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/paymentStatus')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PAYMENT_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: sales & service menus-->

            <!--Billing Information activities related menus-->
            <?php if(in_array(1, $billingSetupMenu)): ?>
            <li <?php
            $current = (in_array($routeName, array('billing/billingcreate', 'billing/billingledgerview')) || in_array($controllerName, array('principalledger'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-retweet"></i>
                    <span class="title"><?php echo app('translator')->get('label.BILLING_INFORMATION'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(!empty($userAccessArr[41][2])): ?>
                    <li <?php $current = ( in_array($routeName, array('billing/billingcreate'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('billing/billingCreate')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RECEIVABLE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[41][1])): ?>
                    <li <?php $current = (in_array($routeName, array('billing/billingledgerview'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('billing/billingLedgerView')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.INVOICE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[81][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('principalledger'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('principalLedger')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PRINCIPAL_LEDGER'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: billing menus-->

            <!--Payment activities related menus-->
            <?php if(in_array(1, $paymentSetupMenu)): ?>
            <li <?php
            $current = (in_array($controllerName, array('receive', 'supplierledger', 'salespersonpayment'
                        , 'salespersonpaymentvoucher', 'salespersonledger', 'buyerpayment', 'buyerpaymentvoucher'
                        , 'buyerledger'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-usd"></i>
                    <span class="title"><?php echo app('translator')->get('label.PAYMENT_INFORMATION'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--start :: supplier payment-->
                    <?php if(!empty($userAccessArr[46][2])): ?>
                    <li <?php $current = ( in_array($controllerName, array('receive'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('receive')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RECEIVE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[50][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('supplierledger'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('supplierLedger')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SUPPLIER_LEDGER'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!--end :: supplier payment-->

                    <!--start :: sales person payment-->
                    <?php if(in_array(1, $salesPersonPaymentSetupMenu)): ?>
                    <li <?php
                    $current = (in_array($controllerName, array('salespersonpayment', 'salespersonpaymentvoucher'
                                , 'salespersonledger'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.SALES_PERSON_PAYMENT'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[60][2])): ?>
                            <li <?php $current = ( in_array($controllerName, array('salespersonpayment'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('salesPersonPayment')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PAYMENT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[61][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('salespersonpaymentvoucher'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('salesPersonPaymentVoucher')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PAYMENT_VOUCHER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[62][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('salespersonledger'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('salesPersonLedger')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.LEDGER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!--end :: sales person payment-->

                    <!--start :: buyer payment-->
                    <?php if(in_array(1, $buyerPaymentSetupMenu)): ?>
                    <li <?php
                    $current = (in_array($controllerName, array('buyerpayment', 'buyerpaymentvoucher'
                                , 'buyerledger'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.BUYER_PAYMENT'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[63][2])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyerpayment'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('buyerPayment')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PAYMENT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[64][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyerpaymentvoucher'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('buyerPaymentVoucher')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PAYMENT_VOUCHER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[65][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyerledger'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('buyerLedger')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.LEDGER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!--end :: buyer payment-->
                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: payment menus-->

            <!-- start buyer message menu -->
            <?php if(Auth::user()->allowed_for_messaging == '1'): ?>
            <?php if(!empty($userAccessArr[87][1]) && !empty($userAccessArr[87][17])): ?>
            <li <?php $current = ( in_array($controllerName, array('buyermessage'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/buyerMessage')); ?>" class="nav-link ">
                    <i class="fa fa-commenting"></i>
                    <span class="title"> <?php echo app('translator')->get('label.BUYER_MESSAGE'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <!-- start buyer Quotation menu -->
            <?php if(Auth::user()->allowed_to_view_quotation == '1'): ?>
            <?php if(!empty($userAccessArr[88][1]) && !empty($userAccessArr[88][5])): ?>
            <li <?php $current = ( in_array($controllerName, array('quotationrequest'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/quotationRequest')); ?>" class="nav-link ">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title"> <?php echo app('translator')->get('label.BUYER_QUOTATION_REQ'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>


            <!-- User Group wise common feature set up -->
            <?php if(in_array(1, $adminSetupMenu)): ?>
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
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title"><?php echo app('translator')->get('label.ADMINISTRATIVE_SETUP'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!-- access control setup -->
                    <?php if(in_array(1, $accessControlMenu)): ?>
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.ACCESS_CONTROL_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[3][7])): ?>
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/aclUserGroupToAccess/userGroupToAccess')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.USER_GROUP_ACCESS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[3][1])): ?>
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('aclUserGroupToAccess/moduleAccessControl/')); ?>" class="nav-link">
                                    <span class="title"><?php echo app('translator')->get('label.MODULE_WISE_ACCESS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- User setup -->
                    <?php if(in_array(1, $userSetupMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('department', 'designation', 'branch'
                                , 'usergroup', 'user'))) ? 'start active open' : '';
                    ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.USER_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[2][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/userGroup')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.USER_GROUP'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[4][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/department')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.DEPARTMENT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[5][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/designation')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.DESIGNATION'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[6][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('branch'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/branch')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BRANCH'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[1][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/user')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.USER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- product setup -->
                    <?php if(in_array(1, $productSetupMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('productcategory', 'measureunit', 'brand'
                                , 'product', 'salespersontoproduct', 'producttobrand', 'grade', 'producttograde'))) ? 'start active open' : '';
                    ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.PRODUCT_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[7][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('productcategory'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/productCategory')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PRODUCT_CATEGORY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[9][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('measureunit'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/measureUnit')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PRODUCT_MEASUREMENT_UNIT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[11][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('brand'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/brand')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BRAND'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[44][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('grade'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/grade')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.GRADE'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[15][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('product')) && ($routeName != 'product/approvalproduct' )) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/product')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PRODUCT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[32][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('producttobrand'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/productToBrand')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PRODUCT_TO_BRAND'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[45][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('producttograde'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/productToGrade')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.PRODUCT_TO_GRADE'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[16][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('salespersontoproduct'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/salesPersonToProduct')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.SALES_PERSON_TO_PRODUCT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- contact designation -->
                    <?php if(!empty($userAccessArr[39][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('contactdesignation'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/contactDesignation')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CONTACT_DESIGNATION'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Contact Designation Ends -->
                    <!-- supplier setup -->
                    <?php if(in_array(1, $supplierSetupMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('supplierclassification', 'supplier', 'suppliertoproduct', 'beneficiarybank'))) ? 'start active open' : '';
                    ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.SUPPLIER_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[12][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('supplierclassification'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/supplierClassification')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.SUPPLIER_CLASSIFICATION'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[13][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('supplier'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/supplier')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.SUPPLIER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($userAccessArr[47][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('beneficiarybank'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/beneficiaryBank')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BENEFICIARY_BANK'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($userAccessArr[21][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('suppliertoproduct'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/supplierToProduct')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.SUPPLIER_TO_PRODUCT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- buyer setup -->
                    <?php if(in_array(1, $buyerSetupMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('buyercategory', 'finishedgoods', 'buyer'
                                , 'buyerfactory', 'salespersontobuyer', 'buyertoproduct'
                                , 'transferbuyertosalesperson', 'buyerfollowup'))) ? 'start active open' : '';
                    ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.BUYER_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[10][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyercategory'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/buyerCategory')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BUYER_CATEGORY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[24][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('finishedgoods'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/finishedGoods')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.FINISHED_GOODS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[18][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyer'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/buyer')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BUYER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[19][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyerfactory'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/buyerFactory')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BUYER_FACTORY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[17][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('salespersontobuyer'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/salesPersonToBuyer')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.SALES_PERSON_TO_BUYER'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[22][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyertoproduct'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/buyerToProduct')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BUYER_TO_PRODUCT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[66][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('buyerfollowup'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/buyerFollowup')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.BUYER_FOLLOWUP'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[59][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('transferbuyertosalesperson'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('/transferBuyerToSalesPerson')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.TRANSFER_BUYER_TO_SALES_PERSON'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!--Start new mgt-->
                    <?php if(!empty($userAccessArr[8][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('configuration'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/configuration')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CONFIGURATION'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[33][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('precarrier'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/preCarrier')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PRECARRIER'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[35][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('paymentterms'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/paymentTerms')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PAYMENTTERMS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[36][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('shippingterms'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/shippingTerms')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SHIPPINGTERMS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[42][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('shippingline'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/shippingLine')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SHIPPING_LINE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- bank setup -->
                    <?php if(in_array(1, $bankSetupMenu)): ?>
                    <li <?php
                    $current = ( in_array($controllerName, array('bank', 'konitabank'))) ? 'start active open' : '';
                    ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.BANK_SETUP'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[38][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('bank'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/bank')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CLIENT_BANK'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[40][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('konitabank'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/konitaBank')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.KONITA_BANK'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!--end new mgt-->
                    <!--followup status-->
                    <?php if(!empty($userAccessArr[52][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('followupstatus'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/followupStatus')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.FOLLOWUP_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!--end :: followup status-->
                    <?php if(!empty($userAccessArr[20][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('salestarget'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/salesTarget')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SALES_TARGET'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[14][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('causeoffailure'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/causeOfFailure')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CAUSE_OF_FAILURE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[25][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('rwunit'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/rwUnit')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RW_UNIT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[43][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('certificate'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/certificate')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CERTIFICATE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: admin setup menus-->

            <!-- start db backup menu -->
            <?php if(!empty($userAccessArr[85][1])): ?>
            <li <?php $current = ( in_array($controllerName, array('dbbackup'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/dbBackup')); ?>" class="nav-link ">
                    <i class="fa fa-database"></i>
                    <span class="title"> <?php echo app('translator')->get('label.DB_BACKUP'); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <!--report menus-->
            <?php if(in_array(1, $reportMenu)): ?>
            <li <?php
            $current = (in_array($controllerName, array('salesvolumereport', 'salesstatusreport', 'marketengagement'
                        , 'newmarketforecast', 'salessummaryreport', 'brandwisesalessummaryreport', 'supplierwisesalessummaryreport'
                        , 'buyersummaryreport', 'idlyengagedbuyerreport', 'crmstatusreport', 'crmsummaryreport'
                        , 'dbbackupdownloadlogreport'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-book"></i>
                    <span class="title"><?php echo app('translator')->get('label.REPORT'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(!empty($userAccessArr[86][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('dbbackupdownloadlogreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/dbBackupDownloadLogReport')); ?>" class="nav-link ">
                            <span class="title"> <?php echo app('translator')->get('label.DB_BACKUP_DOWNLOAD_LOG'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(1, $crmReportMenu)): ?>
                    <li <?php
                    $current = (in_array($controllerName, array('crmstatusreport', 'crmsummaryreport'))) ? 'start active open' : '';
                    ?>class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.CRM'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[83][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmstatusreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('crmStatusReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CRM_STATUS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[84][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('crmsummaryreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                                <a href="<?php echo e(url('crmSummaryReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CRM_SUMMARY'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[49][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('salesvolumereport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('salesVolumeReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SALES_VOLUME'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[51][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('salesstatusreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('salesStatusReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SALES_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[55][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('salessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('salesSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SALES_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[56][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('brandwisesalessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('brandWiseSalesSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BRAND_WISE_SALES_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[57][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('supplierwisesalessummaryreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('supplierWiseSalesSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SUPPLIER_WISE_SALES_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[58][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('buyersummaryreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('buyerSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BUYER_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[73][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('idlyengagedbuyerreport'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('idlyEngagedBuyerReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BUYER_ENGAGEMENT_IDLE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[53][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('marketengagement'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('marketEngagement')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.MARKET_ENGAGEMENT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[54][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('newmarketforecast'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('newMarketForecast')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.NEW_MARKET_FORECAST'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <!--endof :: report menus-->
        </ul>
    </div>
</div><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/layouts/default/sidebar.blade.php ENDPATH**/ ?>