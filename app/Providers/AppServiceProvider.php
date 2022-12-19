<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AclUserGroupToAccess;
use App\CrmOpportunity;
use App\CrmActivityLog;
use App\CrmActivityStatus;
use App\CrmOpportunityToMember;
use App\Lead;
use App\Delivery;
use App\Invoice;
use App\BuyerPayment;
use App\Buyer;
use App\SalesPersonPayment;
use App\UserWiseQuotationReq;
use App\QuotationRequest;
use App\OrderMessaging;
use App\UserWiseBuyerMessage;
use DB;
use Route;
use Common;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        view()->composer('*', function ($view) {

            //get request notification number on topnavber in all views
            if (Auth::check()) {
                $toDayDate = date('Y-m-d');
                $todayStart = date('Y-m-d 00:00:00');
                $todayEnd = date('Y-m-d 23:59:59');

                //ACL ACCESS LIST
                $userAccessArr = Common::userAccess();

                $currentControllerFunction = Route::currentRouteAction();
                $controllerName = $currentCont = '';
                if (!empty($currentControllerFunction[1])) {
                    $currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
                    $controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
                }

                $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
                $id = !empty($buyer->id) ? $buyer->id : 0;
                $userAndBuyerId = $id;

                $pendingForApprovalCount = [];
                $pendingForApprovalCount['total'] = !empty($pendingForApprovalCount['total']) ? $pendingForApprovalCount['total'] : 0;
                if (!empty($userAccessArr[77][1])) {
                    $pendingInquiryCount = CrmOpportunity::where('status', '2')->where('dispatch_status', '1')
                                    ->where('approval_status', '0')->count();
                    $pendingForApprovalCount['pending_inquiry'] = $pendingInquiryCount;
                    $pendingForApprovalCount['total'] += $pendingInquiryCount;
                }
                if (!empty($userAccessArr[41][1])) {
                    $invoiceCount = Invoice::where('approval_status', '0')->count();
                    $pendingForApprovalCount['invoice'] = $invoiceCount;
                    $pendingForApprovalCount['total'] += $invoiceCount;
                }
                if (!empty($userAccessArr[61][1])) {
                    $salePersonPaymentCount = SalesPersonPayment::where('approval_status', '0')->count();
                    $pendingForApprovalCount['sales_person_payment'] = $salePersonPaymentCount;
                    $pendingForApprovalCount['total'] += $salePersonPaymentCount;
                }
                if (!empty($userAccessArr[64][1])) {
                    $buyerPaymentCount = BuyerPayment::where('approval_status', '0')->count();
                    $pendingForApprovalCount['buyer_payment'] = $buyerPaymentCount;
                    $pendingForApprovalCount['total'] += $buyerPaymentCount;
                }

                $shipmentCount = [];
                $shipmentCount['total'] = !empty($shipmentCount['total']) ? $shipmentCount['total'] : 0;
                $shipmentCount['ets'] = !empty($shipmentCount['ets']) ? $shipmentCount['ets'] : 0;
                $shipmentCount['eta'] = !empty($shipmentCount['eta']) ? $shipmentCount['eta'] : 0;

                if (!empty($userAccessArr[27][5]) || Auth::user()->group_id == 0) {
                    $pendingForLCCount = Lead::whereIn('order_status', ['2', '3', '4']);
                    if (Auth::user()->group_id == 0) {
                        $pendingForLCCount = $pendingForLCCount->where('buyer_id', $id);
                    }
                    $pendingForLCCount = $pendingForLCCount->where('lc_transmitted_copy_done', '0')->count();
                    $shipmentCount['pending_for_lc'] = $pendingForLCCount;
                    $shipmentCount['total'] += $pendingForLCCount;

                    $pendingForShipmentCount = Lead::where('order_status', '2');
                    if (Auth::user()->group_id == 0) {
                        $pendingForShipmentCount = $pendingForShipmentCount->where('buyer_id', $id);
                    }
                    $pendingForShipmentCount = $pendingForShipmentCount->count();
                    $shipmentCount['pending_for_shipment'] = $pendingForShipmentCount;
                    $shipmentCount['total'] += $pendingForShipmentCount;

                    $etsEtaSummaryInfo = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                                    ->select('inquiry.order_status', 'delivery.id', 'delivery.inquiry_id', 'delivery.ets_info', 'delivery.eta_info')
                                    ->whereIn('inquiry.order_status', ['2', '3', '4']);
                    if (Auth::user()->group_id == 0) {
                        $etsEtaSummaryInfo = $etsEtaSummaryInfo->where('inquiry.buyer_id', $id);
                    }
                    $etsEtaSummaryInfo = $etsEtaSummaryInfo->get();


                    if (!$etsEtaSummaryInfo->isEmpty()) {
                        foreach ($etsEtaSummaryInfo as $item) {
                            //ETS
                            if (!empty($item->ets_info)) {
                                $etsInfoArr = json_decode($item->ets_info, true);
                                if (!empty($etsInfoArr)) {
                                    $etsLastDataList = end($etsInfoArr);
                                    $lastEtsDate = $etsLastDataList['ets_date'];
                                    $lastEtsNotificationDate = $etsLastDataList['ets_notification_date'];
                                    if (!empty($lastEtsDate) && !empty($lastEtsNotificationDate)) {
                                        if (strtotime($lastEtsNotificationDate) <= strtotime($toDayDate) && strtotime($toDayDate) <= strtotime($lastEtsDate)) {
                                            $shipmentCount['ets'] += 1;
                                            $shipmentCount['total'] += 1;
                                        }
                                    }
                                }
                            }

                            //ETA
                            if (!empty($item->eta_info)) {
                                $etaInfoArr = json_decode($item->eta_info, true);
                                if (!empty($etaInfoArr)) {
                                    $etaLastDataList = end($etaInfoArr);
                                    $lastEtaDate = $etaLastDataList['eta_date'];
                                    $lastEtaNotificationDate = $etaLastDataList['eta_notification_date'];
                                    if (!empty($lastEtaDate) && !empty($lastEtaNotificationDate)) {
                                        if (strtotime($lastEtaNotificationDate) <= strtotime($toDayDate) && strtotime($toDayDate) <= strtotime($lastEtaDate)) {
                                            $shipmentCount['eta'] += 1;
                                            $shipmentCount['total'] += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $partiallyShippedCount = Lead::where('order_status', '3');
                    if (Auth::user()->group_id == 0) {
                        $partiallyShippedCount = $partiallyShippedCount->where('buyer_id', $id);
                    }
                    $partiallyShippedCount = $partiallyShippedCount->count();
                    $shipmentCount['partially_shipped'] = $partiallyShippedCount;
                    $shipmentCount['total'] += $partiallyShippedCount;
                }

                if (!empty($userAccessArr[27][5]) || !empty($userAccessArr[31][5])) {
                    $waitingTrackingNoCount = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                                    ->where('delivery.shipment_status', '2')->whereNull('delivery.express_tracking_no')
                                    ->whereIn('inquiry.order_status', ['2', '3', '4'])->count();
                    $shipmentCount['waiting_for_tracking_no'] = $waitingTrackingNoCount;
                    $shipmentCount['total'] += $waitingTrackingNoCount;
                }


                $activityStatusList = CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();

                $crmOpportunityArr = CrmOpportunity::select('id', 'status', 'revoked_status', 'last_activity_status'
                                , 'approval_status', 'dispatch_status')
                        ->whereBetween('updated_at', [$todayStart, $todayEnd]);

                $crmOpportunityCountInfo = $crmOpportunityArr->get();
                $crmCount = Common::getOpportunityCount($crmOpportunityCountInfo);

//                echo '<pre>';
//                print_r($crmOpportunityCountArr);
//                exit;

                $totalQuotReqForUser = UserWiseQuotationReq::where('user_id', Auth::user()->id)
                        ->where('status', '0')->count();
                
                $totalBuyerQuotReq = QuotationRequest::where('buyer_id', $userAndBuyerId)
                        ->where('status', '0')->count();
                
                $totalBuyerUnreadCommonMsg = OrderMessaging::where('buyer_id', $userAndBuyerId)
                        ->where('inquiry_id', 0)
                        ->where('buyer_read', '0')->count();
                
                $totalBuyerUnreadOrderMsg = OrderMessaging::where('buyer_id', $userAndBuyerId)
                        ->where('inquiry_id', '<>', 0)
                        ->where('buyer_read', '0')->count();
                
                $totalUserUnreadMsg['order'] = UserWiseBuyerMessage::where('user_id', Auth::user()->id)
                        ->where('inquiry_id', '<>', 0)
                        ->where('status', '0')->count();
                
                $totalUserUnreadMsg['common'] = UserWiseBuyerMessage::where('user_id', Auth::user()->id)
                        ->where('inquiry_id', 0)
                        ->where('status', '0')->count();
                
                $totalUserUnreadMsg['total'] = ($totalUserUnreadMsg['order'] ?? 0) + ($totalUserUnreadMsg['common'] ?? 0);
                
                
                
                
                
                

                $view->with([
                    'userAccessArr' => $userAccessArr,
                    'controllerName' => $controllerName,
                    'pendingForApprovalCount' => $pendingForApprovalCount,
                    'shipmentCount' => $shipmentCount,
                    'crmCount' => $crmCount,
                    'activityStatusList' => $activityStatusList,
                    'userAndBuyerId' => $userAndBuyerId,
                    'totalQuotReqForUser' => $totalQuotReqForUser,
                    'totalBuyerQuotReq' => $totalBuyerQuotReq,
                    'totalBuyerUnreadCommonMsg' => $totalBuyerUnreadCommonMsg,
                    'totalBuyerUnreadOrderMsg' => $totalBuyerUnreadOrderMsg,
                    'totalUserUnreadMsg' => $totalUserUnreadMsg,
                ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

}
