<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{URL::to('/dashboard')}}">
                <img src="{{URL::to('/')}}/public/img/logo.png" alt="logo" /> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!--<li class="show-hide-side-menu">
    <a title="" data-container="body" class="btn-show-hide-link">
        <i class="btn red-sunglo" >
            <span id="fullMenu" data-fullMenu="1">{!! __('label.FULL_SCREEN') !!}</span> 
        </i>
    </a>
</li>-->
                @if(Auth::user()->group_id == 0)
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" data-container="body"  data-original-title="@lang('label.VIEW_COMMON_MESSAGES')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a class="btn btn-lg purple-sharp btn-circle btn-order-messaging order-messaging tooltips" title="@lang('label.VIEW_COMMON_MESSAGES')" href="#modalOrderMessaging" data-id="0" data-buyer-id="{!! $userAndBuyerId !!}" data-toggle="modal">
                        @if(!empty($totalBuyerUnreadCommonMsg) && $totalBuyerUnreadCommonMsg != 0)
                        <span class="badge badge-common-messaging badge-green-steel">{!! $totalBuyerUnreadCommonMsg ?? 0 !!}</span>
                        @endif
                        <i class="fa fa-commenting" ></i>
                    </a>

                </li>
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" data-container="body"  data-original-title="@lang('label.PENDING') @lang('label.VIEW_ORDER_MESSAGES')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="{{URL::to('/buyerMessaging?inquiry_id=0&message_type=2')}}" class="btn btn-lg blue-steel btn-circle btn-order-messaging tooltips" title="@lang('label.VIEW_ORDER_MESSAGES')">
                        @if(!empty($totalBuyerUnreadOrderMsg) && $totalBuyerUnreadOrderMsg != 0)
                        <span class="badge badge-order-messaging badge-green-steel">{!! $totalBuyerUnreadOrderMsg ?? 0 !!}</span>
                        @endif
                        <i class="fa fa-commenting" ></i>
                    </a>
                </li>

                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" data-container="body"  data-original-title="@lang('label.PENDING') @lang('label.QUOTATION_REQUEST')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="{{URL::to('/buyerQuotationRequest?status=1')}}" class="dropdown-toggle">
                        <i class="fa fa-file-text-o" ></i>
                        <span class="badge badge-green-steel">{!! $totalBuyerQuotReq ?? 0 !!}</span>

                    </a>
                </li>
                @else
                @if(Auth::user()->allowed_for_messaging == '1')
                @if(!empty($userAccessArr[87][1]) && !empty($userAccessArr[87][17]))
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="shipmentNotification" data-container="body"  data-original-title="@lang('label.VIEW_MESSAGES')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-commenting" ></i>
                        @if(!empty($totalUserUnreadMsg['total']) && $totalUserUnreadMsg['total'] != 0)
                        <span class="badge badge-purple  badge-user-total-message">{!! $totalUserUnreadMsg['total'] !!}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <?php
                            $totalMsgCount = __('label.NO');
                            if (!empty($totalUserUnreadMsg['total']) && $totalUserUnreadMsg['total'] != 0) {
                                $totalMsgCount = '<span class="bold">' . $totalUserUnreadMsg['total'] . '</span>';
                            }
                            $sMsgCount = $totalUserUnreadMsg['total'] > 1 ? 's' : '';
                            ?>
                            <h3 class="h3-user-total-message">
                                @lang('label.YOU_HAVE_UNREAD_MESSAGES', ['n' => $totalMsgCount, 's' => $sMsgCount])
                            </h3>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding" href="{{URL::to('/buyerMessage?buyer_id=0&inquiry_id=0&message_type=1')}}">
                                        <span class="details">
                                            <span class="badge badge-purple badge-user-common-message req-number">{!! $totalUserUnreadMsg['common'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.COMMON_MESSAGE')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/buyerMessage?buyer_id=0&inquiry_id=0&message_type=1')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-purple  badge-user-order-message req-number">{!! $totalUserUnreadMsg['order'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.ORDER_BASED_MESSAGE')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                @endif
                @endif
                @endif

                @if(Auth::user()->allowed_to_view_quotation == '1')
                @if(!empty($userAccessArr[88][1]) && !empty($userAccessArr[88][5]))
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" data-container="body"  data-original-title="@lang('label.BUYER_QUOTATION_REQ')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="{{URL::to('/quotationRequest?buyer_id=0&status=1')}}" class="dropdown-toggle">
                        <i class="fa fa-file-text-o" ></i>
                        <span class="badge badge-green-steel">{!! $totalQuotReqForUser ?? 0 !!}</span>

                    </a>
                </li>
                @endif
                @endif




                @if((Auth::user()->allowed_for_crm == '1' && Auth::user()->allowed_for_sales != '1') || Auth::user()->for_crm_leader == '1' || Auth::user()->group_id == '1')
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="crmNotification" data-container="body"  data-original-title="@lang('label.CRM_NOTIFICATION')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-simplybuilt" ></i>
                        <span class="badge badge-green-steel">{!! $crmCount['total'] ?? 0 !!}</span>

                    </a>
                    <ul class="dropdown-menu max-height-250 overflow-auto">
                        <li class="external">
                            <?php
                            $totalCrmCount = __('label.NO');
                            if (!empty($crmCount['total']) && $crmCount['total'] != 0) {
                                $totalCrmCount = '<span class="bold">' . $crmCount['total'] . '</span>';
                            }
                            $sCrmCount = (!empty($crmCount['total']) && $crmCount['total'] > 1) ? 's' : '';
                            ?>
                            <h3>
                                @lang('label.YOU_HAVE_CRM_NOTIFICATION', ['n' => $totalCrmCount, 's' => $sCrmCount])
                            </h3>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="#crmOpportunityListModal"  data-toggle="modal" class="crm-opportunity-list notification-padding"
                                       data-status ="0" data-revoked-status ="0"
                                       data-last-ctivity-status ="0" data-dispatch-status ="0"
                                       data-approval-status ="0" data-duration ="1" data-status-text-cap="{!! __('label.NEW') !!}" data-duration-text="{!! __('label.TODAY') !!}"
                                       >
                                        <span class="details">
                                            <span class="badge badge-blue-madison req-number">{!! $crmCount['new'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.NEW_OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-steel req-number">{!! $crmCount['in_progress'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.IN_PROGRESS')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-red-soft req-number">{!! $crmCount['dead'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['1'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-chambray req-number">{!! $crmCount['unreachable'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['2'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-hoki req-number">{!! $crmCount['answering_machine'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['3'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-soft req-number">{!! $crmCount['sdc'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['4'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-green-steel req-number">{!! $crmCount['reached'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['5'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-yellow-mint req-number">{!! $crmCount['not_interested'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['6'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-red-pink req-number">{!! $crmCount['not_booked'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['8'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-purple-sharp req-number">{!! $crmCount['halt'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['9'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-green-sharp req-number">{!! $crmCount['prospective'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['10'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-grey-mint req-number">{!! $crmCount['none'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['11'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-yellow-casablanca req-number">{!! $crmCount['irrelevant'] ?? 0 !!}</span>&nbsp;
                                            {!! $activityStatusList['12'] !!}&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $crmCount['dispatched'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.DISPATCHED')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-green-soft req-number">{!! $crmCount['booked'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.BOOKED')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--                        <li>
                                                    <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                                        <li>
                                                            <a href="javascript:;" class="notification-padding">
                                                                <span class="details">
                                                                    <span class="badge badge-green-seagreen req-number">{!! $crmCount['approved'] ?? 0 !!}</span>&nbsp;
                                                                    @lang('label.APPROVED')&nbsp;@lang('label.OPPORTUNITY')
                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                                        <li>
                                                            <a href="javascript:;" class="notification-padding">
                                                                <span class="details">
                                                                    <span class="badge badge-red-mint req-number">{!! $crmCount['denied'] ?? 0 !!}</span>&nbsp;
                                                                    @lang('label.DENIED')&nbsp;@lang('label.OPPORTUNITY')
                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>-->
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-red-flamingo req-number">{!! $crmCount['cancelled'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.CANCELLED')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-grey-cascade req-number">{!! $crmCount['void'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.VOID')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-red-thunderbird req-number">{!! $crmCount['revoked'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.REVOKED')&nbsp;@lang('label.OPPORTUNITY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif
                @if(!empty($userAccessArr[27][5]) || !empty($userAccessArr[31][5]) || Auth::user()->group_id == 0)
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="shipmentNotification" data-container="body"  data-original-title="@lang('label.SHIPMENT_NOTIFICATION')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-ship" ></i>
                        <span class="badge badge-purple">{!! $shipmentCount['total'] !!}</span>

                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <?php
                            $totalShipmentCount = __('label.NO');
                            if (!empty($shipmentCount['total']) && $shipmentCount['total'] != 0) {
                                $totalShipmentCount = '<span class="bold">' . $shipmentCount['total'] . '</span>';
                            }
                            $sShipmentCount = $shipmentCount['total'] > 1 ? 's' : '';
                            ?>
                            <h3>
                                @lang('label.YOU_HAVE_SHIPMENT_NOTIFICATION', ['n' => $totalShipmentCount, 's' => $sShipmentCount])
                            </h3>
                        </li>
                        @if(!empty($userAccessArr[27][5]) || Auth::user()->group_id == 0)
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding pending-lc" href="#pendingLcModal" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['pending_for_lc'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.PENDING_FOR_LC')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="#pendingShipmentModal" data-toggle="modal" class="pending-shipment notification-padding">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['pending_for_shipment'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.PENDING_FOR_SHIPMENT')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="ets-eta-info notification-padding" href="#modalEtsEtaInfo" data-ref="1" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['ets'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.ESTIMATED_TIME_OF_SHIPMENT')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="ets-eta-info notification-padding" href="#modalEtsEtaInfo" data-ref="2" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['eta'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.ESTIMATED_TIME_OF_ARRIVAL')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if(!empty($userAccessArr[27][5]) || Auth::user()->group_id == 0)
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding partially-shipped" href="#partiallyShippedModal" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['partially_shipped'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.PARTIALLY_SHIPPED')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->group_id != 0)
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="notification-padding waiting-for-tracking" href="#trackingNoModal" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['waiting_for_tracking_no'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.WAITING_FOR_TRACKING_NO')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(!empty($userAccessArr[77][1]) || !empty($userAccessArr[41][1]) || !empty($userAccessArr[61][1]) || !empty($userAccessArr[64][1]))
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="pendingForApprovalNotification" data-container="body"  data-original-title="@lang('label.PENDING_FOR_APPROVAL_NOTIFICATION')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-check-circle" ></i>
                        <span class="badge badge-blue-madison">{!! $pendingForApprovalCount['total'] !!}</span>

                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <?php
                            $totalPendingForApprovalCount = __('label.NO');
                            if (!empty($pendingForApprovalCount['total']) && $pendingForApprovalCount['total'] != 0) {
                                $totalPendingForApprovalCount = '<span class="bold">' . $pendingForApprovalCount['total'] . '</span>';
                            }
                            $sPendingForApprovalCount = $pendingForApprovalCount['total'] > 1 ? 's' : '';
                            ?>
                            <h3>
                                @lang('label.YOU_HAVE_REQUEST_PENDING_FOR_APPROVAL', ['n' => $totalPendingForApprovalCount, 's' => $sPendingForApprovalCount])
                            </h3>
                        </li>
                        @if(!empty($userAccessArr[41][1]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/billing/billingLedgerView?invoice_no=&order_no=0&supplier_id=0&approval_status=1')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-madison req-number">{!! $pendingForApprovalCount['invoice'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.INVOICE') @lang('label.PENDING_FOR_APPROVAL')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(!empty($userAccessArr[61][1]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/salesPersonPaymentVoucher?sales_person_id=0&approval_status=1')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-madison req-number">{!! $pendingForApprovalCount['sales_person_payment'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.SALES_PERSON_PAYMENT') @lang('label.PENDING_FOR_APPROVAL')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(!empty($userAccessArr[64][1]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/buyerPaymentVoucher?buyer_id=0&approval_status=1')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-madison req-number">{!! $pendingForApprovalCount['buyer_payment'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.BUYER_PAYMENT') @lang('label.PENDING_FOR_APPROVAL')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="dropdown dropdown-user">

                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                        $user = Auth::user(); //get current user all information
                        if (!empty($user->photo) && File::exists('public/uploads/user/' . $user->photo)) {
                            ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}"/>
                            <?php
                        } else {
                            $noImg = Auth::user()->group_id != 0 ? "unknown" : "no_image";
                            ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/img/{{$noImg}}.png" />
                        <?php } ?>
                        <span class="username username-hide-on-mobile">@lang('label.WELCOME') {{$user->username}}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{url('changePassword')}}">
                                <i class="icon-key"></i>@lang('label.CHANGE_PASSWORD')</a>
                        </li>

                        <!--                        <li>
                                                    <a href="#" class="tooltips" title="My Profile">
                                                        <i class="icon-user"></i>@lang('label.MY_PROFILE')</a>
                                                </li>-->
                        <!--li>
                            <a href="app_calendar.html">
                                <i class="icon-calendar"></i> My Calendar </a>
                        </li-->
                        <!--li>
                            <a href="app_inbox.html">
                                <i class="icon-envelope-open"></i> My Inbox
                                <span class="badge badge-danger"> 3 </span>
                            </a>
                        </li-->
                        <!--li>
                            <a href="app_todo.html">
                                <i class="icon-rocket"></i> My Tasks
                                <span class="badge badge-success"> 7 </span>
                            </a>
                        </li-->
                        <li class="divider"> </li>

                        <li>
                            <a class="tooltips"  title="Logout" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> @lang('label.LOGOUT')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a class="tooltips" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" title="Logout">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>

<!-- Modal start -->


<!--CRM Status Summary modal-->
<div class="modal fade" id="crmOpportunityListModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showCrmOpportunityList">
        </div>
    </div>
</div>

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

<!--Waiting for Tracking NO modal-->
<div class="modal fade" id="trackingNoModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="trackingNoViewModal">
        </div>
    </div>
</div>
<!--ETS ETA Info modal-->
<div class="modal fade" id="modalEtsEtaInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtsEtaInfo">
        </div>
    </div>
</div>


<!--order messaging-->
@if(Auth::user()->group_id == 0)
<div class="modal fade" id="modalOrderMessaging" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderMessaging"></div>
    </div>
</div>
@endif


<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

<?php if (Auth::user()->group_id == 0) { ?>
            //order messaging modal
            $(".order-messaging").on("click", function (e) {
                e.preventDefault();
                var inquiryId = $(this).attr("data-id");
                var buyerId = $(this).attr("data-buyer-id");
                $.ajax({
                    url: "{{ URL::to('/buyerOrder/getOrderMessaging')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        inquiry_id: inquiryId,
                        buyer_id: buyerId,
                    },
                    beforeSend: function () {
                        $("#showOrderMessaging").html('');
                    },
                    success: function (res) {
                        $("#showOrderMessaging").html(res.html);
                        if (inquiryId == 0) {
                            $('span.badge-common-messaging').remove();
                        }
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    }
                }); //ajax
            });

            //Send Message
            $(document).on("click", ".send-message", function (e) {
                //            e.preventDefault();
                var formData = new FormData($('#setMessageFrom')[0]);

                $.ajax({
                    url: "{{ URL::to('buyerOrder/setMessage')}}",
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        $('.send-message').prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        $('.send-message').prop('disabled', false);
                        $('#message').val('');
                        $('.message-body').html(res.messageBody);
                        App.unblockUI();

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
                        $('.send-message').prop('disabled', false);
                        App.unblockUI();
                    }
                });
            });
<?php } ?>

        //CRM Opportunity List MODAL
        $(document).on("click", ".crm-opportunity-list", function (e) {
            e.preventDefault();
            var formData = {
                status: $(this).attr('data-status'),
                revoked_status: $(this).attr('data-revoked-status'),
                last_activity_status: $(this).attr('data-last-ctivity-status'),
                dispatch_status: $(this).attr('data-dispatch-status'),
                approval_status: $(this).attr('data-approval-status'),
                duration: $(this).attr('data-duration'),
                status_text_cap: $(this).attr('data-status-text-cap'),
                duration_text: $(this).attr('data-duration-text'),
            };
            $.ajax({
                url: "{{ URL::to('dashboard/getCrmOpportunityList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                beforeSend: function () {
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

        //PENDING FOR LC  Details MODAL
        $(document).on("click", ".pending-lc", function (e) {
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
                beforeSend: function () {
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
        $(document).on("click", ".pending-shipment", function (e) {
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
                beforeSend: function () {
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
        $(document).on("click", ".partially-shipped", function (e) {
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
                beforeSend: function () {
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
        $(document).on("click", ".waiting-for-tracking", function (e) {
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
                beforeSend: function () {
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

        $('.ets-eta-info').on('click', function (e) {
            e.preventDefault();
            var formData = {
                ref: $(this).attr("data-ref"),
            };
            $.ajax({
                url: "{{ URL::to('/dashboard/getEtsEtaInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                beforeSend: function () {
                    $("#showEtsEtaInfo").html('');
                },
                success: function (res) {
                    $("#showEtsEtaInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
    });
</script>