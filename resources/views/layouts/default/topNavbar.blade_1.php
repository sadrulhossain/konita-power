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

                @if(Auth::user()->allowed_for_crm == '1')
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="crmNotification" data-container="body"  data-original-title="@lang('label.CRM_NOTIFICATION')" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="fa fa-simplybuilt" ></i>
                        <span class="badge badge-green-steel">{!! $crmCount['total'] ?? 0 !!}</span>

                    </a>
                    <ul class="dropdown-menu">
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
                                    <a href="javascript:;" class="notification-padding">
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
                        <li>
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
                        </li>
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
                @if(!empty($userAccessArr[27][5]) || !empty($userAccessArr[31][5]))
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
                        @if(!empty($userAccessArr[27][5]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/confirmedOrder')}}" class="notification-padding">
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
                                    <a href="{{URL::to('/confirmedOrder')}}" class="notification-padding">
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
                                    <a class="confirmed-accomplished-href notification-padding" href="#modalConfirmedOrAccomplishedRedirect" data-ref="@lang('label.ESTIMATED_TIME_OF_SHIPMENT')" data-confirmed-count="{!! $shipmentCount['ets_confirmed'] ?? 0 !!}" data-accomplished-count="{!! $shipmentCount['ets_accomplished'] ?? 0 !!}" data-toggle="modal">
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
                                    <a class="confirmed-accomplished-href notification-padding" href="#modalConfirmedOrAccomplishedRedirect" data-ref="@lang('label.ESTIMATED_TIME_OF_ARRIVAL')" data-confirmed-count="{!! $shipmentCount['eta_confirmed'] ?? 0 !!}" data-accomplished-count="{!! $shipmentCount['eta_accomplished'] ?? 0 !!}" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['eta'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.ESTIMATED_TIME_OF_ARRIVAL')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if(!empty($userAccessArr[27][5]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/confirmedOrder')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['partially_shipped'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.PARTIALLY_SHIPPED')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a class="confirmed-accomplished-href notification-padding" href="#modalConfirmedOrAccomplishedRedirect" data-ref="@lang('label.WAITING_FOR_TRACKING_NO')" data-confirmed-count="{!! $shipmentCount['waiting_for_tracking_no_confirmed'] ?? 0 !!}" data-accomplished-count="{!! $shipmentCount['waiting_for_tracking_no_accomplished'] ?? 0 !!}" data-toggle="modal">
                                        <span class="details">
                                            <span class="badge badge-purple req-number">{!! $shipmentCount['waiting_for_tracking_no'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.WAITING_FOR_TRACKING_NO')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
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
                        @if(!empty($userAccessArr[77][1]))
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{URL::to('/pendingInquiry?buyer=&source_id=0&approval_status=0')}}" class="notification-padding">
                                        <span class="details">
                                            <span class="badge badge-blue-madison req-number">{!! $pendingForApprovalCount['pending_inquiry'] ?? 0 !!}</span>&nbsp;
                                            @lang('label.PENDING_INQUIRY')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
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
                        if (!empty($user->photo)) {
                            ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/uploads/user/{{$user->photo}}" />
                        <?php } else { ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/img/unknown.png" />
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

<!--opportunity details-->
<div class="modal fade" id="modalConfirmedOrAccomplishedRedirect" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div id="showConfirmedOrAccomplishedRedirect"></div>
    </div>
</div>
<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();

        $('.confirmed-accomplished-href').on('click', function (e) {
            e.preventDefault();
            var formData = {
                ref: $(this).attr("data-ref"),
                confirmed_count: $(this).attr("data-confirmed-count"),
                accomplished_count: $(this).attr("data-accomplished-count"),
            };
            $.ajax({
                url: "{{ URL::to('/defaultService/getConfirmedOrAccomplishedRedirect')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                beforeSend: function () {
                    $("#showConfirmedOrAccomplishedRedirect").html('');
                },
                success: function (res) {
                    $("#showConfirmedOrAccomplishedRedirect").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
    });
</script>