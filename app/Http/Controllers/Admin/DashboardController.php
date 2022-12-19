<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\ProductToBrand;
use App\Grade;
use App\ProductToGrade;
use App\Product;
use App\ProductPricing;
use App\ProductPricingHistory;
use App\SalesPersonToProduct;
use App\BuyerToProduct;
use App\CompanyInformation;
use App\SalesTarget;
use App\Brand;
use App\Lead;
use App\InquiryDetails;
use App\ProductTechDataSheet;
use App\SalesPersonToBuyer;
use App\Buyer;
use App\Certificate;
use App\Receive;
use App\SalesPersonPayment;
use App\Delivery;
use App\Supplier;
use App\Invoice;
use App\DeliveryDetails;
use App\BuyerPayment;
use App\CrmOpportunity;
use App\CrmActivityLog;
use App\CrmActivityStatus;
use App\CrmOpportunityToMember;
use App\Country;
use App\InvoiceCommissionHistory;
use DateTime;
use PDF;
use DB;
use Common;
use Debugbar;
use Helper;
use Response;
use Validator;

class DashboardController extends Controller {

    public function __construct() {
//$this->middleware('auth');
    }

    public function index() {
        if (Auth::user()->group_id != 0) {
            return $this->allUserDashboard();
        } else {
            return $this->buyerDashboard();
        }
    }

    public function allUserDashboard() {

        //**********************************************************************************************   
//******************************** Seals Persons Part ******************************************* 
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        $finalUserIdArr = array_unique($finalUserIdArr);

        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        $teamSupervisorArr = User::pluck('supervisor_id', 'supervisor_id')->toArray();

        $hasRelationWithProduct = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)
                        ->pluck('product_id')->toArray();

//        my last six month performance 
        $currentDay = date('Y-m-d');
        $currentMonth = date('Y-m-01');
        $lastSixMonth = date('Y-m-01', strtotime('-5 months'));
        $fifteenDaysAgo = date('Y-m-d', strtotime('-14 days'));
        $thirtyDaysAgo = date('Y-m-d', strtotime('-29 days'));
//MY SALES TARGET
        $mySalesTargetArr = SalesTarget::where('sales_person_id', Auth::user()->id)
                        ->whereBetween('effective_date', [$lastSixMonth, $currentMonth])
                        ->pluck('total_quantity', 'effective_date')->toArray();

//MY SALES Achievement
        $mySalesAchievementArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.salespersons_id', Auth::user()->id)
                        ->whereBetween('inquiry.pi_date', [$lastSixMonth, $currentDay])
                        ->groupBy('inquiry.pi_date')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as qty")
                                , 'inquiry.pi_date')
                        ->pluck('qty', 'inquiry.pi_date')->toArray();

        $beginMonthDay = new DateTime($lastSixMonth);
        $endMonthDay = new DateTime($currentDay);
        $myLastSixMonthTagAcivArr = [];
        for ($j = $beginMonthDay; $j <= $endMonthDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("M Y");
            $myLastSixMonthTagAcivArr[$month]['achievement'] = !empty($myLastSixMonthTagAcivArr[$month]['achievement']) ? $myLastSixMonthTagAcivArr[$month]['achievement'] : 0;
            $myLastSixMonthTagAcivArr[$month]['achievement'] += !empty($mySalesAchievementArr[$day]) ? $mySalesAchievementArr[$day] : 0;
            $myLastSixMonthTagAcivArr[$month]['target'] = !empty($myLastSixMonthTagAcivArr[$month]['target']) ? $myLastSixMonthTagAcivArr[$month]['target'] : 0;
            $myLastSixMonthTagAcivArr[$month]['target'] += !empty($mySalesTargetArr[$day]) ? $mySalesTargetArr[$day] : 0;
        }


//        END OF my last six month performance 
        //************ START last six month (Team performanch)*************
        //LAST SIX MONTH
        $lastSixMonthArr = [];
        for ($i = 1; $i < 6; $i++) {
            $lastSixMonthArr[] = date("M Y", strtotime(date('Y-m-01') . " -$i months"));
        }
        array_unshift($lastSixMonthArr, date('M Y'));
        krsort($lastSixMonthArr);


        $userIdLastSixMonthArr = User::where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $userIdLastSixMonthArr2 = User::where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $finalUserIdLastSixMonthArr = array_merge($userIdLastSixMonthArr, $userIdLastSixMonthArr2);
        $finalUserIdLastSixMonthArr = array_unique($finalUserIdLastSixMonthArr);
        if (Auth::user()->group_id == '1') {
            $finalUserIdLastSixMonthArr = User::where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        }


        //Team Wise Sales Target
        $teamSalesTargetArr = SalesTarget::join('users', 'users.id', '=', 'sales_target.sales_person_id')
                        ->where('users.allowed_for_sales', '1')
                        ->whereIn('sales_target.sales_person_id', $finalUserIdLastSixMonthArr)
                        ->whereBetween('sales_target.effective_date', [$lastSixMonth, $currentMonth])
                        ->select('sales_target.total_quantity', 'sales_target.effective_date', 'sales_target.sales_person_id')->get();

        $teamSalesTargetFianlArr = $salesTarget = [];
        if (!$teamSalesTargetArr->isEmpty()) {
            foreach ($teamSalesTargetArr as $item) {
                $effectiveDate = date("M Y", strtotime($item->effective_date));
                $salesTarget[$effectiveDate][$item->sales_person_id] = $item->total_quantity;
            }
        }


        foreach ($lastSixMonthArr as $targetMonth) {
            foreach ($finalUserIdLastSixMonthArr as $userId) {
                $teamSalesTargetFianlArr[$targetMonth][$userId] = !empty($salesTarget[$targetMonth][$userId]) ? $salesTarget[$targetMonth][$userId] : 0;
            }
        }



        //End of Team Wise Sales Target
        //Monthly Net Income
        $fromDate = date('Y-m-01');
        $toDate = date('Y-m-t');
        $monthlyNetIncomeArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.pi_date', '>=', $fromDate)
                ->where('inquiry.pi_date', '<=', $toDate)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();
        $monthlyNetIncome = !empty($monthlyNetIncomeArr) ? array_sum($monthlyNetIncomeArr) : 0;
        
        //End of Monthly Net Income
        // Expected Income
        $InvoiceCommissionHistoryArr = InvoiceCommissionHistory::pluck('inquiry_id', 'inquiry_id')->toArray();

        $expectedIncomeArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->whereNotIn('inquiry_details.inquiry_id', $InvoiceCommissionHistoryArr)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();
        $expectedIncome = !empty($expectedIncomeArr) ? array_sum($expectedIncomeArr) : 0;
        
        //End Expected Income
        //Team Wise Sales Achievement
        $teamSalesAchievement = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->whereIn('inquiry.salespersons_id', $finalUserIdLastSixMonthArr)
                ->whereBetween('inquiry.pi_date', [$lastSixMonth, $currentDay])
                ->groupBy('inquiry.pi_date', 'inquiry.salespersons_id')
                ->select(DB::raw("SUM(inquiry_details.quantity) as qty")
                        , 'inquiry.pi_date', 'inquiry.salespersons_id')
                ->get();

        $teamSalesAchievementFinalArr = $teamSalesAchievementArr = [];
        if (!$teamSalesAchievement->isEmpty()) {
            foreach ($teamSalesAchievement as $item) {
                $piDate = date("M Y", strtotime($item->pi_date));
                $teamSalesAchievementArr[$piDate][$item->salespersons_id] = !empty($teamSalesAchievementArr[$piDate][$item->salespersons_id]) ? $teamSalesAchievementArr[$piDate][$item->salespersons_id] : 0;
                $teamSalesAchievementArr[$piDate][$item->salespersons_id] += $item->qty;
            }
        }


        foreach ($lastSixMonthArr as $achivMonth) {
            foreach ($finalUserIdLastSixMonthArr as $userId) {
                $teamSalesAchievementFinalArr[$achivMonth][$userId] = !empty($teamSalesAchievementArr[$achivMonth][$userId]) ? $teamSalesAchievementArr[$achivMonth][$userId] : 0;
            }
        }




        //End of  Team Wise Sales Achievement
        $teamLastSixMonthPercent = [];
        foreach ($teamSalesAchievementFinalArr as $achieveMonth => $achieveItem) {
            foreach ($achieveItem as $salesPersonId => $achieve) {
                if (!empty($teamSalesTargetFianlArr[$achieveMonth][$salesPersonId]) && $teamSalesTargetFianlArr[$achieveMonth][$salesPersonId] != '0.00') {
                    $lastSixMonthPercent = ($achieve * 100 / $teamSalesTargetFianlArr[$achieveMonth][$salesPersonId]);
                    $teamLastSixMonthPercent[$achieveMonth][$salesPersonId] = Helper::numberFormat2Digit($lastSixMonthPercent);
                } else {
                    if (!empty($achieve)) {
                        $teamLastSixMonthPercent[$achieveMonth][$salesPersonId] = Helper::numberFormat2Digit(100);
                    } else {
                        $teamLastSixMonthPercent[$achieveMonth][$salesPersonId] = Helper::numberFormat2Digit(0);
                    }
                }
            }
        }



        $salesPersonLastSixMonthArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->whereIn('users.id', $finalUserIdLastSixMonthArr)
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->pluck('name', 'users.id')->toArray();



        //End of last six month (Team performanch) 
        //**********************TEAM PERFORMANCE CURRENT MONTH **********************************************


        $userIdCurrentMonthArr = User::where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $userIdCurrentMonthArr2 = User::where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $finalUserIdCurrentMonthArr = array_merge($userIdCurrentMonthArr, $userIdCurrentMonthArr2);
        $finalUserIdCurrentMonthArr = array_unique($finalUserIdCurrentMonthArr);
        if (Auth::user()->group_id == '1') {
            $finalUserIdCurrentMonthArr = User::where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        }

        $salesPersonCurrentMonthArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->whereIn('users.id', $finalUserIdCurrentMonthArr)
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->pluck('name', 'users.id')->toArray();

        //TARGET
        $teamSalesTargetCurrentMonthArr = SalesTarget::join('users', 'users.id', '=', 'sales_target.sales_person_id')
                        ->where('users.allowed_for_sales', '1')
                        ->whereIn('sales_target.sales_person_id', $finalUserIdCurrentMonthArr)
                        ->where('sales_target.effective_date', $currentMonth)
                        ->pluck('sales_target.total_quantity', 'sales_target.sales_person_id')->toArray();


        $teamSalesTargetCurrentMonthFianlArr = [];
        foreach ($finalUserIdCurrentMonthArr as $userId) {
            $teamSalesTargetCurrentMonthFianlArr[$userId] = !empty($teamSalesTargetCurrentMonthArr[$userId]) ? $teamSalesTargetCurrentMonthArr[$userId] : 0;
        }



        //ACHIEVEMENT
        $teamSalesAchievementCurrentMonth = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->whereIn('inquiry.salespersons_id', $finalUserIdCurrentMonthArr)
                        ->whereBetween('inquiry.pi_date', [$currentMonth, $currentDay])
                        ->groupBy('inquiry.salespersons_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as qty")
                                , 'inquiry.salespersons_id')
                        ->pluck('qty', 'inquiry.salespersons_id')->toArray();


        $teamSalesAchieveCurrentMonthFianlArr = [];
        foreach ($finalUserIdCurrentMonthArr as $userId) {
            $teamSalesAchieveCurrentMonthFianlArr[$userId] = !empty($teamSalesAchievementCurrentMonth[$userId]) ? $teamSalesAchievementCurrentMonth[$userId] : 0;
        }


        //  % of Achievement Current Month

        $teamCurrentMonthPercent = [];
        foreach ($teamSalesAchieveCurrentMonthFianlArr as $salesPersonId => $achieve) {
            $teamCurrentMonthPercent[$salesPersonId]['achieve'] = !empty($achieve) ? Helper::numberFormatDigit2($achieve) : Helper::numberFormatDigit2(0);

            $target = $teamSalesTargetCurrentMonthFianlArr[$salesPersonId];
            $teamCurrentMonthPercent[$salesPersonId]['target'] = (!empty($target) && $target != '0.00') ? Helper::numberFormatDigit2($target) : Helper::numberFormatDigit2(0);

            if (!empty($target) && $target != '0.00') {
                $currentMonthPercent = ($achieve * 100 / $target);
                $teamCurrentMonthPercent[$salesPersonId]['percent'] = Helper::numberFormat2Digit($currentMonthPercent);
            } else {
                if (!empty($achieve)) {
                    $teamCurrentMonthPercent[$salesPersonId]['percent'] = Helper::numberFormat2Digit(100);
                } else {
                    $teamCurrentMonthPercent[$salesPersonId]['percent'] = Helper::numberFormat2Digit(0);
                }
            }
        }


        //END OF TEAM PERFORMANCE CURRENT MONTH ********************************************
        //*** Buyer && product && brand && Sales Persons Count of authorized user ***
        if (Auth::user()->group_id == 1) {
            $myBuyerCount = Buyer::where('status', '1')->count();
        } else {
            $myBuyerCount = SalesPersonToBuyer::join('buyer', 'buyer.id', '=', 'sales_person_to_buyer.buyer_id')
                            ->where('buyer.status', '1')
                            ->where('sales_person_to_buyer.sales_person_id', Auth::user()->id)->count();
        }


        if (Auth::user()->group_id == 1) {
            $myProductArr = Product::where('status', '1')->get();
        } else {
            $myProductArr = SalesPersonToProduct::join('product', 'product.id', '=', 'sales_person_to_product.product_id')
                            ->where('sales_person_to_product.sales_person_id', Auth::user()->id)
                            ->where('product.status', '1')
                            ->pluck('sales_person_to_product.product_id', 'sales_person_to_product.product_id')->toArray();
        }


        if (Auth::user()->group_id == 1) {
            $myBrandCount = Brand::where('status', '1')->get();
        } else {
            $myBrandCount = SalesPersonToProduct::join('brand', 'brand.id', '=', 'sales_person_to_product.brand_id')
                            ->where('sales_person_to_product.sales_person_id', Auth::user()->id)
                            ->where('brand.status', '1')
                            ->pluck('sales_person_to_product.brand_id', 'sales_person_to_product.brand_id')->toArray();
        }




        if (Auth::user()->group_id == 1) {
            $salesPersonCount = User::where('status', '1')->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        } else {
            $salesPersonCount1 = User::where('status', '1')->where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
            $salesPersonCount2 = User::where('status', '1')->where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
            $salesPersonCount = array_merge($userIdArr, $userIdArr2);
            $salesPersonCount = array_unique($finalUserIdArr);
        }






        //***End of Buyer && product && brand Count of authorized user ***
        //**************** MY SALES STATUS GRAPH ************************

        $last12Month = date('Y-m-01', strtotime('-11 months'));

        $showLast12Month = date('01 F Y', strtotime('-11 months'));
        $showCurrentDay = date('d F Y');

        $myBrandArr = SalesPersonToProduct::join('brand', 'brand.id', '=', 'sales_person_to_product.brand_id')
                        ->where('sales_person_to_product.sales_person_id', Auth::user()->id)
                        ->pluck('brand.name', 'brand.id')->toArray();
        ksort($myBrandArr);

        $mySalesStatus = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->where('inquiry.salespersons_id', Auth::user()->id)
                ->whereBetween('inquiry.creation_date', [$last12Month, $currentDay])
                ->select('inquiry_details.id as inquiry_details_id', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.brand_id', 'inquiry_details.quantity'
                        , 'inquiry.status', 'inquiry.order_status')
                ->get();


        $upcomingSalesVolume = $pipeLineSalesVolume = $confirmedSalesVolume = $accomplishedSalesVolume = $cancelledSalesVolume = [];
        if (!empty($mySalesStatus)) {
            foreach ($mySalesStatus as $item) {

                if ($item->status == '1') {
                    //upcoming
                    $upcomingSalesVolume[$item->brand_id] = !empty($upcomingSalesVolume[$item->brand_id]) ? $upcomingSalesVolume[$item->brand_id] : 0;
                    $upcomingSalesVolume[$item->brand_id] += $item->quantity;
                }

                if ($item->order_status == '1') {
                    //pipe Line
                    $pipeLineSalesVolume[$item->brand_id] = !empty($pipeLineSalesVolume[$item->brand_id]) ? $pipeLineSalesVolume[$item->brand_id] : 0;
                    $pipeLineSalesVolume[$item->brand_id] += $item->quantity;
                } elseif ($item->order_status == '2' || $item->order_status == '3') {
                    //confirmed oreder
                    $confirmedSalesVolume[$item->brand_id] = !empty($confirmedSalesVolume[$item->brand_id]) ? $confirmedSalesVolume[$item->brand_id] : 0;
                    $confirmedSalesVolume[$item->brand_id] += $item->quantity;
                } elseif ($item->order_status == '4') {
                    //accomplished order
                    $accomplishedSalesVolume[$item->brand_id] = !empty($accomplishedSalesVolume[$item->brand_id]) ? $accomplishedSalesVolume[$item->brand_id] : 0;
                    $accomplishedSalesVolume[$item->brand_id] += $item->quantity;
                }

                if ($item->status == '3' || $item->order_status == '6') {
                    //Cancelled order/inquiry
                    $cancelledSalesVolume[$item->brand_id] = !empty($cancelledSalesVolume[$item->brand_id]) ? $cancelledSalesVolume[$item->brand_id] : 0;
                    $cancelledSalesVolume[$item->brand_id] += $item->quantity;
                }
            }
        }

        //*********** END OF MY SALES STATUS GRAPH ************************ 
        //***********START SALES STATUS TEAM WISE **********************
        $userIdSalesStatusArr = User::where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $userIdSalesStatusArr2 = User::where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $finalUserIdSalesStatusArr = array_merge($userIdSalesStatusArr, $userIdSalesStatusArr2);
        $finalUserIdSalesStatusArr = array_unique($finalUserIdSalesStatusArr);
        if (Auth::user()->group_id == '1') {
            $finalUserIdSalesStatusArr = User::where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        }



        $teamBrandArr = SalesPersonToProduct::join('brand', 'brand.id', '=', 'sales_person_to_product.brand_id')
                        ->whereIn('sales_person_to_product.sales_person_id', $finalUserIdSalesStatusArr)
                        ->pluck('brand.name', 'brand.id')->toArray();
        ksort($teamBrandArr);

        $teamSalesStatus = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.salespersons_id', $finalUserIdSalesStatusArr)
                ->whereBetween('inquiry.creation_date', [$last12Month, $currentDay])
                ->select('inquiry_details.id as inquiry_details_id', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.brand_id', 'inquiry_details.quantity'
                        , 'inquiry.status', 'inquiry.order_status')
                ->get();


        $upcomingSalesVolumeTeam = $pipeLineSalesVolumeTeam = $confirmedSalesVolumeTeam = $accomplishedSalesVolumeTeam = $cancelledSalesVolumeTeam = [];
        if (!empty($teamSalesStatus)) {
            foreach ($teamSalesStatus as $item) {

                if ($item->status == '1') {
                    //upcoming
                    $upcomingSalesVolumeTeam[$item->brand_id] = !empty($upcomingSalesVolumeTeam[$item->brand_id]) ? $upcomingSalesVolumeTeam[$item->brand_id] : 0;
                    $upcomingSalesVolumeTeam[$item->brand_id] += $item->quantity;
                }

                if ($item->order_status == '1') {
                    //pipe Line
                    $pipeLineSalesVolumeTeam[$item->brand_id] = !empty($pipeLineSalesVolumeTeam[$item->brand_id]) ? $pipeLineSalesVolumeTeam[$item->brand_id] : 0;
                    $pipeLineSalesVolumeTeam[$item->brand_id] += $item->quantity;
                } elseif ($item->order_status == '2' || $item->order_status == '3') {
                    //confirmed oreder
                    $confirmedSalesVolumeTeam[$item->brand_id] = !empty($confirmedSalesVolumeTeam[$item->brand_id]) ? $confirmedSalesVolumeTeam[$item->brand_id] : 0;
                    $confirmedSalesVolumeTeam[$item->brand_id] += $item->quantity;
                } elseif ($item->order_status == '4') {
                    //accomplished order
                    $accomplishedSalesVolumeTeam[$item->brand_id] = !empty($accomplishedSalesVolumeTeam[$item->brand_id]) ? $accomplishedSalesVolumeTeam[$item->brand_id] : 0;
                    $accomplishedSalesVolumeTeam[$item->brand_id] += $item->quantity;
                }

                if ($item->status == '3' || $item->order_status == '6') {
                    //Cancelled order/inquiry
                    $cancelledSalesVolumeTeam[$item->brand_id] = !empty($cancelledSalesVolumeTeam[$item->brand_id]) ? $cancelledSalesVolumeTeam[$item->brand_id] : 0;
                    $cancelledSalesVolumeTeam[$item->brand_id] += $item->quantity;
                }
            }
        }

        //*************** END OF TEAM SALES STATUS **********************
        //***CURRENT MONTH SALES PERSONS COMMISSION ***
        $currentMonthDateTime = date("Y-m-01 00:00:00");
        $toDayDateTime = date("Y-m-d H:i:s");

        $myCurrentMonthRecievedCmsnData = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->groupBY('inquiry.salespersons_id')
                        ->where('inquiry.salespersons_id', Auth::user()->id)
                        ->select(DB::raw("SUM(receive.sales_person_commission) as total_commission"))->first();

        $myCurrentMonthPaidCmsnData = SalesPersonPayment::where('sales_person_id', Auth::user()->id)
                        ->select(DB::raw("SUM(amount) as total_paid"))->first();

        $currentMonthRecieved = !empty($myCurrentMonthRecievedCmsnData->total_commission) ? $myCurrentMonthRecievedCmsnData->total_commission : 0;
        $currentMonthPaid = !empty($myCurrentMonthPaidCmsnData->total_paid) ? $myCurrentMonthPaidCmsnData->total_paid : 0;
        $myCurrentMonthCmsnData = $currentMonthRecieved - $currentMonthPaid;

        //***END OF CURRENT MONTH SALES PERSONS COMMISSION ***
        //**********************************************************************************
        //******************************END OF SALES PERSON PART ***************************
        //********************** START SERVICE PERSONS PART **************************** 
        //******************************************************************************
        //pending for lc
        $pendingForLc = Lead::whereIn('order_status', ['2', '3', '4'])
                        ->where('lc_transmitted_copy_done', '0')->count();


        //pending for shipment
//        $deliveryDataArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
//                        ->where('inquiry.order_status', '2')
//                        ->pluck('inquiry.id')->toArray();

        $pendingForShipment = Lead::where('order_status', '2')
//                ->whereNotIn('id', $deliveryDataArr)
                ->count();
        //end of pending for shipment
        //PARTIALLY SHIPPED
        $partiallyShippedCount = Lead::where('order_status', '3')->count();
        //END OF PARTIALLY SHIPPED
        //WAITING FOR TRACKING NO
        $waitingTrackingNoCount = Delivery::where('shipment_status', '2')
                        ->whereNull('express_tracking_no')->count();

        //END OF WAITING FOR TRACKING NO
        //************* START NEXT 15 DAYS ETS/ETA SUMMARY *******************

        $toDayDate = date('Y-m-d');
        $next15DayDate = date('Y-m-d', strtotime('+14 days'));
        $etsSummaryInfo = Delivery::select('id', 'inquiry_id', 'ets_info', 'eta_info')->get();

        $etsLastDataArr = $etsInfoArr = $etaLastDataArr = $etaInfoArr = [];
        if (!$etsSummaryInfo->isEmpty()) {
            foreach ($etsSummaryInfo as $item) {
                //ETS
                $etsInfoArr = json_decode($item->ets_info, true);
                $etsLastDataArr[$item->id] = end($etsInfoArr);

                //ETA
                $etaInfoArr = json_decode($item->eta_info, true);
                $etaLastDataArr[$item->id] = end($etaInfoArr);
            }
        }




        //ETS
        $etsDateArr = $etsDateCountArr = [];
        if (!empty($etsLastDataArr)) {
            foreach ($etsLastDataArr as $deliveryId => $item) {
                //ETS
                $etsDateArr[$item['ets_date']][$deliveryId] = $deliveryId;
                $etsDateCountArr[$item['ets_date']] = !empty($etsDateArr[$item['ets_date']]) ? count($etsDateArr[$item['ets_date']]) : 0;
            }
        }

        $etaDateArr = $etaDateCountArr = [];
        if (!empty($etaLastDataArr)) {
            foreach ($etaLastDataArr as $deliveryId => $item) {
                //ETA
                $etaDateArr[$item['eta_date']][$deliveryId] = $deliveryId;
                $etaDateCountArr[$item['eta_date']] = !empty($etaDateArr[$item['eta_date']]) ? count($etaDateArr[$item['eta_date']]) : 0;
            }
        }




        $beginDate = new DateTime($toDayDate);
        $endDate = new DateTime($next15DayDate);
        $next15DaysEtsSummaryArr = $next15DaysEtaSummaryArr = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("d F Y");
            //ETS
            $next15DaysEtsSummaryArr[$day] = !empty($etsDateCountArr[$day]) ? $etsDateCountArr[$day] : 0;
            //ETS
            $next15DaysEtaSummaryArr[$day] = !empty($etaDateCountArr[$day]) ? $etaDateCountArr[$day] : 0;
        }


        //************ END OF NEXT 15 DAYS ETS/ETA SUMMARY *******************
        //******************************************************************************
        //********************* END OF SERVICE PERSONS PART ****************************
        //***************** Last 15 Days Inquiry Summary **************************
        $beginDate = new DateTime($thirtyDaysAgo);
        $endDate = new DateTime($currentDay);

        $lastFifteenDaysInquiryArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->where('inquiry.status', '1')
                ->whereBetween('inquiry.creation_date', [$thirtyDaysAgo, $currentDay]);

        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $lastFifteenDaysInquiryArr = $lastFifteenDaysInquiryArr->whereIn('salespersons_id', $finalUserIdArr);
        }
        $lastFifteenDaysInquiryArr = $lastFifteenDaysInquiryArr->select(DB::raw("SUM(inquiry_details.quantity) as volume")
                                , 'inquiry.creation_date')
                        ->groupBY('inquiry.creation_date')->pluck('volume', 'inquiry.creation_date')->toArray();

        $last15DaysInquirySummaryArr = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            //inquiry summary
            $last15DaysInquirySummaryArr[$day] = !empty($lastFifteenDaysInquiryArr[$day]) ? $lastFifteenDaysInquiryArr[$day] : 0;
        }

//        echo '<pre>';
//        print_r($last15DaysInquirySummaryArr);
//        exit;
        //***************** End :: Last 15 Days Inquiry Summary **************************
        //***************** Start :: Accounts Person Part ***************************************
        //********************************************************************************
        //***************** Start :: Top 10 Suppier with Payment Due *********************

        $supplierList = Supplier::pluck('name', 'id')->toArray();

        $supplierReceivableAmountList = Invoice::select(DB::raw("SUM(net_receivable) as receivable")
                                , 'supplier_id')->groupBy('supplier_id')
                        ->pluck('receivable', 'supplier_id')->toArray();
        $supplierReceivedAmountList = Receive::select(DB::raw("SUM(collection_amount) as collection")
                                , 'supplier_id')->groupBy('supplier_id')
                        ->pluck('collection', 'supplier_id')->toArray();

        $supplierDueArr = [];
        if (!empty($supplierList)) {
            foreach ($supplierList as $supplierId => $supplier) {
                $receivable = !empty($supplierReceivableAmountList[$supplierId]) ? $supplierReceivableAmountList[$supplierId] : 0;
                $received = !empty($supplierReceivedAmountList[$supplierId]) ? $supplierReceivedAmountList[$supplierId] : 0;
                $supplierDueArr[$supplierId] = $receivable - $received;
            }
            arsort($supplierDueArr);
        }

        $top10SupplierWithPaymentDueArr = [];
        $supplierCount = 0;
        if (!empty($supplierDueArr)) {
            foreach ($supplierDueArr as $supplierId => $due) {
                if ($supplierCount < 10) {
                    $top10SupplierWithPaymentDueArr[$supplierId] = $due;
                }
                $supplierCount++;
            }
        }

        //***************** End :: Top 10 Suppier with Payment Due ***********************
        //***************** Start :: Top 10 Buyer with Payment Due *********************

        $buyerList = Buyer::pluck('name', 'id')->toArray();

        $buyerDueAmountList = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->where('delivery.buyer_payment_status', '0')
                        ->select(DB::raw("SUM(delivery_details.shipment_quantity * inquiry_details.unit_price) as due")
                                , 'inquiry.buyer_id')->groupBy('inquiry.buyer_id')
                        ->pluck('due', 'inquiry.buyer_id')->toArray();

        $buyerDueArr = [];
        if (!empty($buyerList)) {
            foreach ($buyerList as $buyerId => $buyer) {
                $buyerDueArr[$buyerId] = !empty($buyerDueAmountList[$buyerId]) ? $buyerDueAmountList[$buyerId] : 0;
            }
            arsort($buyerDueArr);
        }

        $top10BuyerWithPaymentDueArr = [];
        $buyerCount = 0;
        if (!empty($buyerDueArr)) {
            foreach ($buyerDueArr as $buyerId => $due) {
                if ($buyerCount < 10) {
                    $top10BuyerWithPaymentDueArr[$buyerId] = $due;
                }
                $buyerCount++;
            }
        }

//        echo '<pre>';
//        print_r($buyerDueAmountList);
//        print_r($buyerDueArr);
//        print_r($top10BuyerWithPaymentDueArr);
//        exit;
        //***************** End :: Top 10 Buyer with Payment Due ***********************
        //***************** Start :: Top 10 Sales Commission Due *********************

        $employeeList = user::select(DB::raw("CONCAT(first_name, ' ', last_name) as name"), 'id')
                        ->where('allowed_for_sales', '1')->pluck('name', 'id')->toArray();


        $employeeReceivedAmountList = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.sales_person_commission) as commission_received")
                                , 'inquiry.salespersons_id')->groupBy('inquiry.salespersons_id')
                        ->pluck('commission_received', 'inquiry.salespersons_id')->toArray();

        $employeePaidAmountList = SalesPersonPayment::select(DB::raw("SUM(amount) as commission_paid")
                                , 'sales_person_id')->groupBy('sales_person_id')
                        ->pluck('commission_paid', 'sales_person_id')->toArray();

        $employeeDueArr = [];
        if (!empty($employeeList)) {
            foreach ($employeeList as $employeeId => $employee) {
                $received = !empty($employeeReceivedAmountList[$employeeId]) ? $employeeReceivedAmountList[$employeeId] : 0;
                $paid = !empty($employeePaidAmountList[$employeeId]) ? $employeePaidAmountList[$employeeId] : 0;
                $employeeDueArr[$employeeId] = $received - $paid;
            }
            arsort($employeeDueArr);
        }

        $top10SalesCommissionDueArr = [];
        $employeeCount = 0;
        if (!empty($employeeDueArr)) {
            foreach ($employeeDueArr as $employeeId => $due) {
                if ($employeeCount < 10) {
                    $top10SalesCommissionDueArr[$employeeId] = $due;
                }
                $employeeCount++;
            }
        }


//        echo '<pre>';
//        print_r($employeeReceivedAmountList);
//        print_r($employeePaidAmountList);
//        print_r($employeeDueArr);
//        print_r($top10SalesCommissionDueArr);
//        exit;
        //***************** End :: Top 10 Sales Commission Due ***********************
        //***************** Start :: Top 10 Buyer Commission Due *********************

        $buyerReceivedAmountList = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.buyer_commission) as commission_received")
                                , 'inquiry.buyer_id')->groupBy('inquiry.buyer_id')
                        ->pluck('commission_received', 'inquiry.buyer_id')->toArray();

        $buyerPaidAmountList = BuyerPayment::select(DB::raw("SUM(amount) as commission_paid")
                                , 'buyer_id')->groupBy('buyer_id')
                        ->pluck('commission_paid', 'buyer_id')->toArray();

        $buyerDueArr = [];
        if (!empty($buyerList)) {
            foreach ($buyerList as $buyerId => $buyer) {
                $received = !empty($buyerReceivedAmountList[$buyerId]) ? $buyerReceivedAmountList[$buyerId] : 0;
                $paid = !empty($buyerPaidAmountList[$buyerId]) ? $buyerPaidAmountList[$buyerId] : 0;
                $buyerDueArr[$buyerId] = $received - $paid;
            }
            arsort($buyerDueArr);
        }

        $top10BuyerCommissionDueArr = [];
        $buyerCount = 0;
        if (!empty($buyerDueArr)) {
            foreach ($buyerDueArr as $buyerId => $due) {
                if ($buyerCount < 10) {
                    $top10BuyerCommissionDueArr[$buyerId] = $due;
                }
                $buyerCount++;
            }
        }
        //***************** End :: Top 10 Buyer Commission Due ***********************
        //********************************************************************************
        //***************** End :: Accounts Person Part *****************************************
        //******************************** Start :: CRM **************************//
        //******************************** Start :: CRM Status Summary **************************//
        $todayDate = date('Y-m-d');
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $todayWeek = date('w') + 1;
        $weekStatDate = date('Y-m-d 00:00:00', strtotime('-' . $todayWeek . ' days'));
        $activityStatusList = CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();

        $crmOpportunityArr = CrmOpportunity::select('id', 'status', 'revoked_status', 'last_activity_status'
                        , 'approval_status', 'dispatch_status');

        $crmOpportunityWeeklyCountInfo = $crmOpportunityArr->whereBetween('updated_at', [$weekStatDate, $todayEnd])
                ->get();
        $crmOpportunityDailyCountInfo = $crmOpportunityArr->whereBetween('updated_at', [$todayStart, $todayEnd])
                ->get();
        $crmWeeklyCount = Common::getOpportunityCount($crmOpportunityWeeklyCountInfo);
        $crmDailyCount = Common::getOpportunityCount($crmOpportunityDailyCountInfo);

        //******************************** End :: CRM Status Summary **************************//
        //******************************** Start :: CRM Schedule **************************//
        $logArrPre = CrmActivityLog::leftJoin('crm_opportunity_to_member', 'crm_opportunity_to_member.opportunity_id', 'crm_activity_log.opportunity_id')
                ->join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                ->select('crm_activity_log.opportunity_id', 'crm_activity_log.log'
                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as member_name"));
        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $logArrPre = $logArrPre->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        }
        $logArrPre = $logArrPre->get();

        $opportunityWithBuyerNameArr = CrmOpportunity::where('buyer_has_id', '0')->pluck('buyer', 'id')->toArray();
        $opportunityWithBuyerIdArr = CrmOpportunity::join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->pluck('buyer.name', 'crm_opportunity.id')->toArray();
        $opportunityArr = $opportunityWithBuyerNameArr + $opportunityWithBuyerIdArr;
        $statusColorCodeArr = CrmActivityStatus::pluck('color_code', 'id')->toArray();
        $statusColorArr = CrmActivityStatus::pluck('color', 'id')->toArray();
        $statusArr = CrmActivityStatus::pluck('name', 'id')->toArray();

        $order = array("\r\n", "\n", "\r");
        $replace = '<br />';

        $activityEventArr = [];
        if (!$logArrPre->isEmpty()) {
            foreach ($logArrPre as $log) {
                $activityLogArr = json_decode($log->log, true);

                if (!empty($activityLogArr)) {
                    foreach ($activityLogArr as $key => $item) {
                        if ($item['has_schedule'] == '1') {
                            $activityEventArr[$key]['opportunity_id'] = $log->opportunity_id;
                            $activityEventArr[$key]['schedule_creator'] = $log->member_name;
                            $activityEventArr[$key]['title'] = date("D, d M Y", strtotime($item['schedule_date_time']));
                            $activityEventArr[$key]['start_date'] = $item['schedule_date_time'];
                            $activityEventArr[$key]['purpose'] = str_replace($order, $replace, $item['schedule_purpose']);
                            $activityEventArr[$key]['color'] = (!empty($item['status']) && isset($statusColorArr[$item['status']])) ? $statusColorArr[$item['status']] : '';
                            $activityEventArr[$key]['color_code'] = (!empty($item['status']) && isset($statusColorCodeArr[$item['status']])) ? $statusColorCodeArr[$item['status']] : '';
                            $activityEventArr[$key]['status'] = (!empty($item['status']) && isset($statusArr[$item['status']])) ? $statusArr[$item['status']] : '';
                            ;
                            $activityEventArr[$key]['schedule_status'] = $item['schedule_status'] ?? '';
                            $activityEventArr[$key]['schedule_done_color'] = $statusColorCodeArr[12] ?? '';
                        }
                    }//foreach
                }
            }//foreach
        }//if
        //******************************** End :: CRM Schedule **************************//
        //******************************** End :: CRM ****************************//

        return view('admin.dashboard')->with(compact('myLastSixMonthTagAcivArr', 'teamLastSixMonthPercent', 'lastSixMonthArr'
                                , 'teamCurrentMonthPercent', 'myBuyerCount', 'myProductArr'
                                , 'myBrandArr', 'upcomingSalesVolume', 'pipeLineSalesVolume', 'confirmedSalesVolume'
                                , 'accomplishedSalesVolume', 'cancelledSalesVolume', 'salesPersonCount'
                                , 'myBrandCount', 'teamBrandArr', 'upcomingSalesVolumeTeam', 'pipeLineSalesVolumeTeam'
                                , 'confirmedSalesVolumeTeam', 'accomplishedSalesVolumeTeam', 'cancelledSalesVolumeTeam'
                                , 'showLast12Month', 'showCurrentDay', 'salesPersonCurrentMonthArr', 'salesPersonLastSixMonthArr'
                                , 'teamSupervisorArr', 'myCurrentMonthCmsnData', 'pendingForLc', 'pendingForShipment'
                                , 'partiallyShippedCount', 'waitingTrackingNoCount', 'next15DaysEtsSummaryArr'
                                , 'next15DaysEtaSummaryArr', 'last15DaysInquirySummaryArr', 'top10SupplierWithPaymentDueArr'
                                , 'supplierList', 'top10BuyerWithPaymentDueArr', 'buyerList'
                                , 'top10SalesCommissionDueArr', 'employeeList', 'hasRelationWithProduct'
                                , 'activityStatusList', 'crmWeeklyCount', 'crmDailyCount'
                                , 'activityEventArr', 'opportunityArr', 'top10BuyerCommissionDueArr', 'monthlyNetIncome'
                                , 'expectedIncome'));
    }

    public function buyerDashboard() {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
        //pending for lc
        $pendingForLc = Lead::whereIn('order_status', ['2', '3', '4'])
                        ->where('buyer_id', $id)
                        ->where('lc_transmitted_copy_done', '0')->count();


        //pending for shipment
        $pendingForShipment = Lead::where('order_status', '2')
                ->where('buyer_id', $id)
//                ->whereNotIn('id', $deliveryDataArr)
                ->count();
        //end of pending for shipment
        //PARTIALLY SHIPPED
        $partiallyShippedCount = Lead::where('order_status', '3')
                        ->where('buyer_id', $id)->count();
        //END OF PARTIALLY SHIPPED
        //************* START NEXT 15 DAYS ETS/ETA SUMMARY *******************

        $toDayDate = date('Y-m-d');
        $next15DayDate = date('Y-m-d', strtotime('+14 days'));
        $etsSummaryInfo = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->where('inquiry.buyer_id', $id)
                        ->select('delivery.id', 'delivery.inquiry_id', 'delivery.ets_info', 'delivery.eta_info')->get();

        $etsLastDataArr = $etsInfoArr = $etaLastDataArr = $etaInfoArr = [];
        if (!$etsSummaryInfo->isEmpty()) {
            foreach ($etsSummaryInfo as $item) {
                //ETS
                $etsInfoArr = json_decode($item->ets_info, true);
                $etsLastDataArr[$item->id] = end($etsInfoArr);

                //ETA
                $etaInfoArr = json_decode($item->eta_info, true);
                $etaLastDataArr[$item->id] = end($etaInfoArr);
            }
        }

        //ETS
        $etsDateArr = $etsDateCountArr = [];
        if (!empty($etsLastDataArr)) {
            foreach ($etsLastDataArr as $deliveryId => $item) {
                //ETS
                $etsDateArr[$item['ets_date']][$deliveryId] = $deliveryId;
                $etsDateCountArr[$item['ets_date']] = !empty($etsDateArr[$item['ets_date']]) ? count($etsDateArr[$item['ets_date']]) : 0;
            }
        }

        $etaDateArr = $etaDateCountArr = [];
        if (!empty($etaLastDataArr)) {
            foreach ($etaLastDataArr as $deliveryId => $item) {
                //ETA
                $etaDateArr[$item['eta_date']][$deliveryId] = $deliveryId;
                $etaDateCountArr[$item['eta_date']] = !empty($etaDateArr[$item['eta_date']]) ? count($etaDateArr[$item['eta_date']]) : 0;
            }
        }

        $beginDate = new DateTime($toDayDate);
        $endDate = new DateTime($next15DayDate);
        $next15DaysEtsSummaryArr = $next15DaysEtaSummaryArr = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("d F Y");
            //ETS
            $next15DaysEtsSummaryArr[$day] = !empty($etsDateCountArr[$day]) ? $etsDateCountArr[$day] : 0;
            //ETS
            $next15DaysEtaSummaryArr[$day] = !empty($etaDateCountArr[$day]) ? $etaDateCountArr[$day] : 0;
        }


        //************ END OF NEXT 15 DAYS ETS/ETA SUMMARY *******************
        //************ START OF ORDER & SHIPMENT SUMMARY LAST 6 MONTHS *******************
        $toDayDateTime = date('Y-m-d 23:59:59');
        $sixMnthsAgoDateTime = date('Y-m-d 00:00:00', strtotime('-5 months'));

        // last 6 months confirmed order
        $last6MonthsConfOrderInfo = Lead::select(DB::raw("COUNT(id) as total"), 'order_confirmed_at')
                ->groupBy('order_confirmed_at')->where('order_status', '2')->where('buyer_id', $id)
                ->whereBetween('order_confirmed_at', [$sixMnthsAgoDateTime, $toDayDateTime])
                ->get();
        $last6MonthsConfOrderArr = [];
        if (!$last6MonthsConfOrderInfo->isEmpty()) {
            foreach ($last6MonthsConfOrderInfo as $info) {
                $dayTime = date('Y-m-d', strtotime($info->order_confirmed_at));
                $last6MonthsConfOrderArr[$dayTime] = !empty($last6MonthsConfOrderArr[$dayTime]) ? $last6MonthsConfOrderArr[$dayTime] : 0;
                $last6MonthsConfOrderArr[$dayTime] += $info->total;
            }
        }

        // last 6 months in prodress order
        $last6MonthsProgOrderInfo = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.created_at')
                ->where('inquiry.order_status', '3')->where('inquiry.buyer_id', $id)
                ->whereBetween('delivery.created_at', [$sixMnthsAgoDateTime, $toDayDateTime])
                ->get();

        $last6MonthsProgOrderArr = $lastOrderShipmentArr = $last6MonthsShipmentArr = [];
        if (!$last6MonthsProgOrderInfo->isEmpty()) {
            foreach ($last6MonthsProgOrderInfo as $info) {
                $lastOrderShipmentArr[$info->inquiry_id][$info->created_at] = $info->created_at;
            }
        }
        if (!empty($lastOrderShipmentArr)) {
            foreach ($lastOrderShipmentArr as $inquiryId => $time) {
                $dayTime = date('Y-m-d', strtotime(max($time)));
                $last6MonthsProgOrderArr[$dayTime] = !empty($last6MonthsProgOrderArr[$dayTime]) ? $last6MonthsProgOrderArr[$dayTime] : 0;
                $last6MonthsProgOrderArr[$dayTime] += 1;
            }
        }

        //last 6 months shipment
        $last6MonthsShipmentInfo = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select(DB::raw("COUNT(delivery.id) as total"), 'delivery.created_at')
                ->groupBy('delivery.created_at')->where('inquiry.buyer_id', $id)
                ->whereBetween('delivery.created_at', [$sixMnthsAgoDateTime, $toDayDateTime])
                ->get();

        if (!$last6MonthsShipmentInfo->isEmpty()) {
            foreach ($last6MonthsShipmentInfo as $info) {
                $dayTime = date('Y-m-d', strtotime($info->created_at));
                $last6MonthsShipmentArr[$dayTime] = !empty($last6MonthsShipmentArr[$dayTime]) ? $last6MonthsShipmentArr[$dayTime] : 0;
                $last6MonthsShipmentArr[$dayTime] += $info->total;
            }
        }

        // last 6 months Accomplished order
        $last6MonthsAcmpOrderInfo = Lead::select(DB::raw("COUNT(id) as total"), 'order_accomplished_at')
                ->groupBy('order_accomplished_at')->where('order_status', '4')->where('buyer_id', $id)
                ->whereBetween('order_accomplished_at', [$sixMnthsAgoDateTime, $toDayDateTime])
                ->get();

        $last6MonthsAcmpOrderArr = [];
        if (!$last6MonthsAcmpOrderInfo->isEmpty()) {
            foreach ($last6MonthsAcmpOrderInfo as $info) {
                $dayTime = date('Y-m-d', strtotime($info->order_accomplished_at));
                $last6MonthsAcmpOrderArr[$dayTime] = !empty($last6MonthsAcmpOrderArr[$dayTime]) ? $last6MonthsAcmpOrderArr[$dayTime] : 0;
                $last6MonthsAcmpOrderArr[$dayTime] += $info->total;
            }
        }

        // last 6 months cancelled order
        $last6MonthsCancOrderInfo = Lead::select(DB::raw("COUNT(id) as total"), 'order_cancelled_at')
                ->groupBy('order_cancelled_at')->where('order_status', '6')->where('buyer_id', $id)
                ->whereBetween('order_cancelled_at', [$sixMnthsAgoDateTime, $toDayDateTime])
                ->get();

        $last6MonthsCancOrderArr = [];
        if (!$last6MonthsCancOrderInfo->isEmpty()) {
            foreach ($last6MonthsCancOrderInfo as $info) {
                $dayTime = date('Y-m-d', strtotime($info->order_cancelled_at));
                $last6MonthsCancOrderArr[$dayTime] = !empty($last6MonthsCancOrderArr[$dayTime]) ? $last6MonthsCancOrderArr[$dayTime] : 0;
                $last6MonthsCancOrderArr[$dayTime] += $info->total;
            }
        }

        $beginDateTime = new DateTime($sixMnthsAgoDateTime);
        $endDateTime = new DateTime($toDayDateTime);
        $last6MonthsOrderArr = $last6MonthsShipmentSummaryArr = [];
        for ($j = $beginDateTime; $j <= $endDateTime; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("M Y");

            //confirmed order count
            $last6MonthsOrderArr[$month]['confirmed'] = !empty($last6MonthsOrderArr[$month]['confirmed']) ? $last6MonthsOrderArr[$month]['confirmed'] : 0;
            $last6MonthsOrderArr[$month]['confirmed'] += (!empty($last6MonthsConfOrderArr[$day]) ? $last6MonthsConfOrderArr[$day] : 0);

            //in progress order count
            $last6MonthsOrderArr[$month]['in_progress'] = !empty($last6MonthsOrderArr[$month]['in_progress']) ? $last6MonthsOrderArr[$month]['in_progress'] : 0;
            $last6MonthsOrderArr[$month]['in_progress'] += (!empty($last6MonthsProgOrderArr[$day]) ? $last6MonthsProgOrderArr[$day] : 0);

            //shipment count
            $last6MonthsShipmentSummaryArr[$month] = !empty($last6MonthsShipmentSummaryArr[$month]) ? $last6MonthsShipmentSummaryArr[$month] : 0;
            $last6MonthsShipmentSummaryArr[$month] += (!empty($last6MonthsShipmentArr[$day]) ? $last6MonthsShipmentArr[$day] : 0);

            //accomplished order count
            $last6MonthsOrderArr[$month]['accomplished'] = !empty($last6MonthsOrderArr[$month]['accomplished']) ? $last6MonthsOrderArr[$month]['accomplished'] : 0;
            $last6MonthsOrderArr[$month]['accomplished'] += (!empty($last6MonthsAcmpOrderArr[$day]) ? $last6MonthsAcmpOrderArr[$day] : 0);

            //cancelled order count
            $last6MonthsOrderArr[$month]['cancelled'] = !empty($last6MonthsOrderArr[$month]['cancelled']) ? $last6MonthsOrderArr[$month]['cancelled'] : 0;
            $last6MonthsOrderArr[$month]['cancelled'] += (!empty($last6MonthsCancOrderArr[$day]) ? $last6MonthsCancOrderArr[$day] : 0);
        }

        //************ END OF ORDER & SHIPMENT SUMMARY LAST 6 MONTHS *******************
        //************ START OF IMPORT SUMMARY LAST 6 MONTHS *******************
        $toDayDate = date('Y-m-d');
        $sixMnthsAgoDate = date('Y-m-d', strtotime('-5 months'));

        // last 6 months import
        $last6MonthsImportInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->select(DB::raw("SUM(inquiry_details.quantity) as total"), 'inquiry.pi_date')
                ->groupBy('inquiry.pi_date')->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.buyer_id', $id)
                ->whereBetween('inquiry.pi_date', [$sixMnthsAgoDate, $toDayDate])
                ->get();
        $last6MonthsImportArr = [];
        if (!$last6MonthsImportInfo->isEmpty()) {
            foreach ($last6MonthsImportInfo as $info) {
                $last6MonthsImportArr[$info->pi_date] = !empty($last6MonthsImportArr[$info->pi_date]) ? $last6MonthsImportArr[$info->pi_date] : 0;
                $last6MonthsImportArr[$info->pi_date] += $info->total;
            }
        }
        // last 6 months import

        $last6MonthsBrandImportArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->select(DB::raw("SUM(inquiry_details.quantity) as total"), 'inquiry_details.brand_id')
                ->groupBy('inquiry_details.brand_id')->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.buyer_id', $id)
                ->whereBetween('inquiry.pi_date', [$sixMnthsAgoDate, $toDayDate]);
        $last6MonthsBrandImportArr = $last6MonthsBrandImportArr->pluck('total', 'inquiry_details.brand_id')->toArray();

        //echo '<pre>';print_r($last6MonthsBrandImportArr);exit;

        $last6MonthsBrandImportList = BuyerToProduct::join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->where('brand.status', '1')->orderBy('brand.name', 'asc')
                        ->pluck('brand.name', 'brand.id')->toArray();

        $beginDate = new DateTime($sixMnthsAgoDate);
        $endDate = new DateTime($toDayDate);
        $last6MonthsImportSummaryArr = [];
        for ($j = $beginDate; $j <= $endDate; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $month = $j->format("M Y");

            $last6MonthsImportSummaryArr[$month] = !empty($last6MonthsImportSummaryArr[$month]) ? $last6MonthsImportSummaryArr[$month] : 0;
            $last6MonthsImportSummaryArr[$month] += (!empty($last6MonthsImportArr[$day]) ? $last6MonthsImportArr[$day] : 0);
        }

        //************ END OF IMPORT SUMMARY LAST 6 MONTHS *******************



        return view('admin.buyerDashboard')->with(compact('pendingForLc', 'id', 'pendingForShipment', 'partiallyShippedCount'
                                , 'next15DaysEtsSummaryArr', 'next15DaysEtaSummaryArr', 'last6MonthsOrderArr', 'last6MonthsShipmentSummaryArr'
                                , 'last6MonthsImportSummaryArr', 'last6MonthsBrandImportList', 'last6MonthsBrandImportArr'));
    }

//START GET PRODUCT PRICING
    public function productPricingView(Request $request) {
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();
//product list
        $productIdArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

        $productList = Product::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1' || empty($allowedAllInquiry)) {
            $productList = $productList->whereIn('id', $productIdArr);
        }
        $productList = $productList->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.ALL_PRODUCT_OPT')) + $productList;


        $view = view('admin.productPricing.showProductPricing', compact('request', 'productList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getProductPricing(Request $request) {
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();
//check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();
        $productArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

//check existing pricing
        $productPricingArr = ProductPricing::join('product', 'product.id', '=', 'product_pricing.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'product_pricing.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'product_pricing.grade_id');

        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            if (!empty($request->product_id)) {
                $productPricingArr = $productPricingArr->where('product_pricing.product_id', $request->product_id);
            } else {
                $productPricingArr = $productPricingArr->whereIn('product_pricing.product_id', $productArr);
            }
        } else {
            if (!empty($request->product_id)) {
                $productPricingArr = $productPricingArr->where('product_pricing.product_id', $request->product_id);
            }
        }


        $productPricingArr = $productPricingArr->select('product_pricing.id as product_pricing_id', 'product_pricing.product_id'
                        , 'product_pricing.brand_id', 'product_pricing.grade_id', 'product_pricing.realization_price'
                        , 'product_pricing.target_selling_price', 'product_pricing.minimum_selling_price'
                        , 'product_pricing.effective_date', 'product_pricing.remarks', 'product_pricing.special_note', 'product.name as product_name'
                        , 'brand.name as brand_name', 'grade.name as grade_name', 'measure_unit.name as unit_name')
                ->get();

        $targetArr = $rowspanArr = $productIdArr = $brandIdArr = [];
        if (!$productPricingArr->isEmpty()) {
            foreach ($productPricingArr as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
                $brandIdArr[$item->brand_id] = $item->brand_id;
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['product_id'] = $item->product_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['brand_id'] = $item->brand_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['grade_id'] = $item->grade_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['product_name'] = $item->product_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['brand_name'] = $item->brand_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['grade_name'] = $item->grade_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['unit_name'] = $item->unit_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['realization_price'] = $item->realization_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['target_selling_price'] = $item->target_selling_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['minimum_selling_price'] = $item->minimum_selling_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['effective_date'] = $item->effective_date;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['remarks'] = !empty($item->remarks) ? $item->remarks : '';
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['special_note'] = !empty($item->special_note) ? $item->special_note : '';

                $rowspanArr[$item->product_id][$item->brand_id][$gradeId] = $gradeId;
            }
        }

        $productArr = Product::whereIn('id', $productIdArr)->pluck('name', 'id')->toArray();
        $brandArr = Brand::whereIn('id', $brandIdArr)->pluck('name', 'id')->toArray();


        $view = view('admin.productPricing.getProductPricing', compact('request', 'authorised', 'rowspanArr'
                        , 'targetArr', 'productArr', 'brandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function productPricingPrintpdf(Request $request) {
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();
//check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();
        $productArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

//check existing pricing
        $productPricingArr = ProductPricing::join('product', 'product.id', '=', 'product_pricing.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'product_pricing.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'product_pricing.grade_id');

        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            if (!empty($request->product_id)) {
                $productPricingArr = $productPricingArr->where('product_pricing.product_id', $request->product_id);
            } else {
                $productPricingArr = $productPricingArr->whereIn('product_pricing.product_id', $productArr);
            }
        } else {
            if (!empty($request->product_id)) {
                $productPricingArr = $productPricingArr->where('product_pricing.product_id', $request->product_id);
            }
        }

        $productPricingArr = $productPricingArr->select('product_pricing.id as product_pricing_id', 'product_pricing.product_id'
                        , 'product_pricing.brand_id', 'product_pricing.grade_id', 'product_pricing.realization_price'
                        , 'product_pricing.target_selling_price', 'product_pricing.minimum_selling_price'
                        , 'product_pricing.effective_date', 'product_pricing.remarks', 'product_pricing.special_note', 'product.name as product_name'
                        , 'brand.name as brand_name', 'grade.name as grade_name', 'measure_unit.name as unit_name')
                ->get();

        $targetArr = $rowspanArr = $productIdArr = $brandIdArr = [];
        if (!$productPricingArr->isEmpty()) {
            foreach ($productPricingArr as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
                $brandIdArr[$item->brand_id] = $item->brand_id;
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['product_id'] = $item->product_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['brand_id'] = $item->brand_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['grade_id'] = $item->grade_id;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['product_name'] = $item->product_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['brand_name'] = $item->brand_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['grade_name'] = $item->grade_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['unit_name'] = $item->unit_name;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['realization_price'] = $item->realization_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['target_selling_price'] = $item->target_selling_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['minimum_selling_price'] = $item->minimum_selling_price;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['effective_date'] = $item->effective_date;
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['remarks'] = !empty($item->remarks) ? $item->remarks : '';
                $targetArr[$item->product_id][$item->brand_id][$gradeId]['special_note'] = !empty($item->special_note) ? $item->special_note : '';

                $rowspanArr[$item->product_id][$item->brand_id][$gradeId] = $gradeId;
            }
        }

        $productArr = Product::whereIn('id', $productIdArr)->pluck('name', 'id')->toArray();
        $brandArr = Brand::whereIn('id', $brandIdArr)->pluck('name', 'id')->toArray();


//KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


        if ($request->view == 'print') {
            return view('admin.productPricing.print.index')->with(compact('request', 'authorised', 'rowspanArr'
                                    , 'targetArr', 'productArr', 'brandArr'
                                    , 'konitaInfo', 'phoneNumber'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('admin.productPricing.print.index', compact('request', 'authorised', 'rowspanArr'
                                    , 'targetArr', 'productArr', 'brandArr'
                                    , 'konitaInfo', 'phoneNumber'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('product_pricing.pdf');
//            return $pdf->stream();
        }
    }

//END OF PRODUCT PRICING
    //set product pricing
    public function showProductPricing(Request $request) {
        //check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();

        $productList = Product::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productList;


        $view = view('admin.setProductPricing.showProductPricing', compact('request', 'productList', 'authorised'))->render();
        return response()->json(['html' => $view]);
    }

    public function getProductPricingSetup(Request $request) {
        $loadView = 'admin.setProductPricing.showSetProductPricing';
        $loadFooterView = 'admin.setProductPricing.showFooter';
        return Common::getProductPricingSetup($request, $loadView, $loadFooterView);
    }

    public function setProductPricing(Request $request) {
        return Common::setProductPricing($request);
    }

    //end :: set product pricing
    //getAuthorizedUserBuyer
    public function getAuthorizedUserBuyer(Request $request) {
        if (Auth::user()->group_id == '1') {
            $nameArr = Buyer::where('buyer.status', '1')->select('name')->orderBy('name', 'asc')->get();
            $buyerInfo = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('buyer.status', '1')
                    ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
        } else {
            $buyerInfo = SalesPersonToBuyer::join('buyer', 'buyer.id', '=', 'sales_person_to_buyer.buyer_id')
                    ->leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('sales_person_to_buyer.sales_person_id', Auth::user()->id)
                    ->where('buyer.status', '1')
                    ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
            $nameArr = SalesPersonToBuyer::join('buyer', 'buyer.id', '=', 'sales_person_to_buyer.buyer_id')
                            ->where('buyer.status', '1')->select('name')->orderBy('name', 'asc')->get();
        }


        $contactArr = $buyerIdArr = [];
        $contact = [];
        if (!$buyerInfo->isEmpty()) {
            foreach ($buyerInfo as $buyer) {
                $buyerIdArr[$buyer->id] = $buyer->id;
                $contact = json_decode($buyer->contact_person_data, true);
                $contactArr[$buyer->id] = array_shift($contact);
            }
        }


        $relatedSalesPersonList = [];
        if (!empty($buyerIdArr)) {
            $relatedSalesPersonList = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                            ->join('designation', 'designation.id', 'users.designation_id')
                            ->whereIn('sales_person_to_buyer.buyer_id', $buyerIdArr)
                            ->select('sales_person_to_buyer.buyer_id', 'sales_person_to_buyer.sales_person_id', 'sales_person_to_buyer.business_status'
                                    , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') as sales_person_name"))
                            ->orderBy('users.designation_id', 'asc')
                            ->orderBy('sales_person_name', 'asc')
                            ->get()->toArray();
        }



        $relatedSalesPersonArr = $activeSalesPersonArr = [];
        if (!empty($relatedSalesPersonList)) {
            foreach ($relatedSalesPersonList as $list) {
                $relatedSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_name'];
                if ($list['business_status'] == '1') {
                    $activeSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_id'];
                }
            }
        }


        $view = view('admin.others.authorizedUserBuyer', compact('request', 'buyerInfo', 'contactArr', 'nameArr'
                        , 'relatedSalesPersonArr', 'activeSalesPersonArr'))->render();
        return response()->json(['html' => $view]);
    }

    //getAuthorizedUserBuyer
    public function getAllAuthorizedUserBuyer(Request $request) {

        if (Auth::user()->group_id == '1') {
            $buyerInfo = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('buyer.status', '1')
                    ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
        } else {
            $buyerInfo = SalesPersonToBuyer::join('buyer', 'buyer.id', '=', 'sales_person_to_buyer.buyer_id')
                    ->leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('sales_person_to_buyer.sales_person_id', Auth::user()->id)
                    ->where('buyer.status', '1')
                    ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
        }


        $contactArr = $buyerIdArr = [];
        $contact = [];
        if (!$buyerInfo->isEmpty()) {
            foreach ($buyerInfo as $buyer) {
                $buyerIdArr[$buyer->id] = $buyer->id;
                $contact = json_decode($buyer->contact_person_data, true);
                $contactArr[$buyer->id] = array_shift($contact);
            }
        }

        $relatedSalesPersonList = [];
        if (!empty($buyerIdArr)) {
            $relatedSalesPersonList = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                            ->join('designation', 'designation.id', 'users.designation_id')
                            ->whereIn('sales_person_to_buyer.buyer_id', $buyerIdArr)
                            ->select('sales_person_to_buyer.buyer_id', 'sales_person_to_buyer.sales_person_id', 'sales_person_to_buyer.business_status'
                                    , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') as sales_person_name"))
                            ->orderBy('users.designation_id', 'asc')
                            ->orderBy('sales_person_name', 'asc')
                            ->get()->toArray();
        }

        $relatedSalesPersonArr = $activeSalesPersonArr = [];
        if (!empty($relatedSalesPersonList)) {
            foreach ($relatedSalesPersonList as $list) {
                $relatedSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_name'];
                if ($list['business_status'] == '1') {
                    $activeSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_id'];
                }
            }
        }

        $view = view('admin.others.showBuyer', compact('request', 'buyerInfo', 'contactArr'
                        , 'relatedSalesPersonArr', 'activeSalesPersonArr'))->render();
        return response()->json(['html' => $view]);
    }

    //getAuthorizedUserBuyer
    public function getAuthorizedUserBuyerByName(Request $request) {
        $searchText = $request->name;
        if (Auth::user()->group_id == '1') {
            $buyerInfo = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('buyer.status', '1');

            if (!empty($searchText)) {
                $buyerInfo->where(function ($query) use ($searchText) {
                    $query->where('buyer.name', 'LIKE', '%' . $searchText . '%');
                });
            }
            $buyerInfo = $buyerInfo->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
        } else {
            $buyerInfo = SalesPersonToBuyer::join('buyer', 'buyer.id', '=', 'sales_person_to_buyer.buyer_id')
                    ->leftJoin('country', 'country.id', '=', 'buyer.country_id')
                    ->where('sales_person_to_buyer.sales_person_id', Auth::user()->id)
                    ->where('buyer.status', '1');

            if (!empty($searchText)) {
                $buyerInfo->where(function ($query) use ($searchText) {
                    $query->where('buyer.name', 'LIKE', '%' . $searchText . '%');
                });
            }
            $buyerInfo = $buyerInfo->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                            , 'buyer.id')
                    ->orderBy('buyer.name', 'asc')
                    ->get();
        }


        $contactArr = $buyerIdArr = [];
        $contact = [];
        if (!$buyerInfo->isEmpty()) {
            foreach ($buyerInfo as $buyer) {
                $buyerIdArr[$buyer->id] = $buyer->id;
                $contact = json_decode($buyer->contact_person_data, true);
                $contactArr[$buyer->id] = array_shift($contact);
            }
        }

        $relatedSalesPersonList = [];
        if (!empty($buyerIdArr)) {
            $relatedSalesPersonList = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                            ->join('designation', 'designation.id', 'users.designation_id')
                            ->whereIn('sales_person_to_buyer.buyer_id', $buyerIdArr)
                            ->select('sales_person_to_buyer.buyer_id', 'sales_person_to_buyer.sales_person_id', 'sales_person_to_buyer.business_status'
                                    , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') as sales_person_name"))
                            ->orderBy('users.designation_id', 'asc')
                            ->orderBy('sales_person_name', 'asc')
                            ->get()->toArray();
        }

        $relatedSalesPersonArr = $activeSalesPersonArr = [];
        if (!empty($relatedSalesPersonList)) {
            foreach ($relatedSalesPersonList as $list) {
                $relatedSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_name'];
                if ($list['business_status'] == '1') {
                    $activeSalesPersonArr[$list['buyer_id']][$list['sales_person_id']] = $list['sales_person_id'];
                }
            }
        }

        $view = view('admin.others.showBuyer', compact('request', 'buyerInfo', 'contactArr'
                        , 'relatedSalesPersonArr', 'activeSalesPersonArr'))->render();
        return response()->json(['html' => $view]);
    }

    //getAuthorizedUserProduct
    public function getAuthorizedUserProduct(Request $request) {
        if (Auth::user()->group_id == '1') {
            $productInfoArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                            ->select('product.id', 'product.name as product_name', 'product.product_code'
                                    , 'product.hs_code', 'product_category.name as category')->get();
        } else {
            $productInfoArr = SalesPersonToProduct::join('product', 'product.id', '=', 'sales_person_to_product.product_id')
                            ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                            ->where('sales_person_to_product.sales_person_id', Auth::user()->id)
                            ->select('product.id', 'product.name as product_name', 'product.product_code'
                                    , 'product.hs_code', 'product_category.name as category')->get();
        }

        $hsCodeArr = $productDataArr = [];
        if (!$productInfoArr->isEmpty()) {
            foreach ($productInfoArr as $target) {
                $productDataArr[$target->id]['id'] = $target->id;
                $productDataArr[$target->id]['product_name'] = $target->product_name;
                $productDataArr[$target->id]['product_code'] = $target->product_code;
                $productDataArr[$target->id]['category'] = $target->category;
                $hsCodeArr[$target->id] = json_decode($target->hs_code, true);
            }
        }


        $view = view('admin.others.authorizedUserProduct', compact('request', 'productDataArr', 'hsCodeArr'))->render();
        return response()->json(['html' => $view]);
    }

    //getAuthorizedUserBrand
    public function getAuthorizedUserBrand(Request $request) {
        if (Auth::user()->group_id == '1') {
            $brandInfoArr = Brand::leftJoin('country', 'country.id', '=', 'brand.origin')
                            ->select('brand.id', 'brand.name', 'brand.description', 'country.name as origin'
                                    , 'brand.logo', 'brand.certificate')
                            ->orderBy('brand.name', 'asc')->get();
        } else {
            $brandInfoArr = SalesPersonToProduct::join('brand', 'brand.id', '=', 'sales_person_to_product.brand_id')
                            ->leftJoin('country', 'country.id', '=', 'brand.origin')
                            ->where('sales_person_to_product.sales_person_id', Auth::user()->id)
                            ->select('brand.id', 'brand.name', 'brand.description', 'country.name as origin'
                                    , 'brand.logo', 'brand.certificate')
                            ->orderBy('brand.name', 'asc')->get();
        }


        $certificateArr = Certificate::orderBy('name', 'asc')->pluck('logo', 'id')->toArray();
        $brandCertificateArr = [];
        if (!$brandInfoArr->isEmpty()) {
            foreach ($brandInfoArr as $item) {
                $brandDataArr[$item->id]['id'] = $item->id;
                $brandDataArr[$item->id]['name'] = $item->name;
                $brandDataArr[$item->id]['description'] = $item->description;
                $brandDataArr[$item->id]['origin'] = $item->origin;
                $brandDataArr[$item->id]['logo'] = $item->logo;
                $brandCertificateArr[$item->id] = json_decode($item->certificate, true);
            }
        }


        $view = view('admin.others.authorizedUserBrand', compact('request', 'brandDataArr', 'brandCertificateArr'
                        , 'certificateArr'))->render();
        return response()->json(['html' => $view]);
    }

    //getSalesPersons
    public function getSalesPersons(Request $request) {
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->where('users.allowed_for_sales', '1')->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        $finalUserIdArr = array_unique($finalUserIdArr);

        if (Auth::user()->group_id == '1') {
            $salesPersonInfo = User::join('designation', 'designation.id', '=', 'users.designation_id')
                    ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"), 'users.id'
                            , 'users.employee_id', 'users.photo', 'designation.title as designation_name')
                    ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                    ->where('users.allowed_for_sales', '1')
                    ->where('users.status', '1')
                    ->get();
        } else {

            $salesPersonInfo = User::join('designation', 'designation.id', '=', 'users.designation_id')
                    ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"), 'users.id'
                            , 'users.employee_id', 'users.photo', 'designation.title  as designation_name')
                    ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                    ->where('users.allowed_for_sales', '1')
                    ->where('users.status', '1')
                    ->whereIn('users.id', $finalUserIdArr)
                    ->get();
        }


        $view = view('admin.others.salesPersonsView', compact('request', 'salesPersonInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function pendingForLc(Request $request) {

        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.lc_transmitted_copy_done', '0');
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id')
                ->get();




        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.lc_transmitted_copy_done', '0');
        if (!empty($id)) {
            $targetArr = $targetArr->where('inquiry.buyer_id', $id);
        }
        $targetArr = $targetArr->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date', 'supplier.pi_required')
                ->orderBy('inquiry.creation_date', 'desc')
                ->get();

        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['quantity'] = $item->quantity;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_price'] = $item->total_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
            }
        }

        //inquiry Details
        //START final targetArr
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $key => $item) {
                $targetArr[$key] = $item;
                $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
            }
        }

        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        foreach ($brandData as $gradeId => $gradeData) {
                            foreach ($gradeData as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }
        //ENDOF Rowspan Arr

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        $view = view('admin.others.pendingForLcView', compact('request', 'targetArr', 'rowspanArr'
                        , 'productArr', 'brandArr', 'gradeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function pendingForShipment(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
//        $deliveryDataArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
//                        ->where('inquiry.order_status', '2')
//                        ->pluck('inquiry.id')->toArray();
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.order_status', '2');
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id')
                ->get();




        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->where('inquiry.order_status', '2');
        if (!empty($id)) {
            $targetArr = $targetArr->where('inquiry.buyer_id', $id);
        }
        $targetArr = $targetArr->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date', 'supplier.pi_required', 'inquiry.lsd_info')
                ->orderBy('inquiry.creation_date', 'desc')
                ->get();

        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['quantity'] = $item->quantity;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_price'] = $item->total_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
            }
        }

        //inquiry Details
        //START final targetArr
        $lsd = '';
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $key => $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsd = $lsdInfo['lsd'];
                }

                $targetArr[$key] = $item;
                $targetArr[$key]['lsd'] = $lsd;
                $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
            }
        }

        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        foreach ($brandData as $gradeId => $gradeData) {
                            foreach ($gradeData as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }
        //ENDOF Rowspan Arr

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        $view = view('admin.others.pendingForShipmentView', compact('request', 'targetArr', 'rowspanArr'
                        , 'productArr', 'brandArr', 'gradeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getPartiallyShipped(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.order_status', '3');
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id')
                ->get();




        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->where('inquiry.order_status', '3');
        if (!empty($id)) {
            $targetArr = $targetArr->where('inquiry.buyer_id', $id);
        }
        $targetArr = $targetArr->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date', 'supplier.pi_required')
                ->orderBy('inquiry.creation_date', 'desc')
                ->get();

        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['quantity'] = $item->quantity;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_price'] = $item->total_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
            }
        }

        //inquiry Details
        //START final targetArr
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $key => $item) {
                $targetArr[$key] = $item;
                $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
            }
        }

        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        foreach ($brandData as $gradeId => $gradeData) {
                            foreach ($gradeData as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }
        //ENDOF Rowspan Arr

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                , 'delivery.buyer_payment_status', 'delivery.shipment_status');
        if (!empty($id)) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.buyer_id', $id);
        }
        $deliveryInfoArr = $deliveryInfoArr->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        $view = view('admin.others.partiallyShippedView', compact('request', 'targetArr', 'rowspanArr'
                        , 'productArr', 'brandArr', 'gradeArr', 'deliveryArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function waitingTrackingNo(Request $request) {

        $deliveryArr = Lead::join('delivery', 'delivery.inquiry_id', '=', 'inquiry.id')
                ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->where('delivery.shipment_status', '2')
                ->whereNull('delivery.express_tracking_no')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->select('inquiry.id', 'inquiry.order_no'
                        , 'supplier.name as supplier_name', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'delivery.bl_no'
                        , 'delivery.express_tracking_no', 'delivery.id as delivery_id')
                ->orderBy('inquiry.creation_date', 'desc')
                ->get();

        $targetArr = $blNoArr = [];
        if (!$deliveryArr->isEmpty()) {
            foreach ($deliveryArr as $item) {
                $targetArr[$item->id]['order_no'] = $item->order_no;
                $targetArr[$item->id]['purchase_order_no'] = $item->purchase_order_no;
                $targetArr[$item->id]['buyer_name'] = $item->buyerName;
                $targetArr[$item->id]['supplier_name'] = $item->supplier_name;
                $blNoArr[$item->id][$item->delivery_id] = $item->bl_no;
            }
        }

        $view = view('admin.others.waitingTrackingNoView', compact('request', 'targetArr', 'blNoArr'))->render();
        return response()->json(['html' => $view]);
    }

    //update tracking no
    public function updateTrackingNo(Request $request) {
        return Common::updateTrackingNo($request, 1);
    }

    public function getEtsEtaInfo(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
        $toDayDate = date('Y-m-d');
        $deliveryArr = Lead::join('delivery', 'delivery.inquiry_id', '=', 'inquiry.id')
                ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->leftJoin('bank', 'bank.id', '=', 'inquiry.bank')
                ->whereIn('inquiry.order_status', ['2', '3', '4']);
        if (!empty($id)) {
            $deliveryArr = $deliveryArr->where('inquiry.buyer_id', $id);
        }
        $deliveryArr = $deliveryArr->select('inquiry.id', 'inquiry.order_no', 'supplier.name as supplier_name'
                        , 'inquiry.purchase_order_no', 'buyer.name as buyerName', 'delivery.bl_no'
                        , 'delivery.express_tracking_no', 'delivery.id as delivery_id'
                        , 'delivery.ets_info', 'delivery.eta_info'
                        , 'inquiry.lc_no', 'inquiry.lc_issue_date', 'bank.name as lc_opening_bank')
                ->orderBy('inquiry.creation_date', 'desc')
                ->get();



        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();

        $targetArr = $blNoArr = [];
        if (!$deliveryArr->isEmpty()) {
            foreach ($deliveryArr as $item) {
                //ETS
                if ($request->ref == '1') {
                    if (!empty($item->ets_info)) {
                        $etsInfoArr = json_decode($item->ets_info, true);
                        if (!empty($etsInfoArr)) {
                            $etsLastDataList = end($etsInfoArr);
                            $lastEtsDate = $etsLastDataList['ets_date'];
                            $lastEtsNotificationDate = $etsLastDataList['ets_notification_date'];
                            if (!empty($lastEtsDate) && !empty($lastEtsNotificationDate)) {
                                if (strtotime($lastEtsNotificationDate) <= strtotime($toDayDate) && strtotime($toDayDate) <= strtotime($lastEtsDate)) {
                                    $targetArr[$item->id]['delivery_id'] = $item->delivery_id;
                                    $targetArr[$item->id]['order_no'] = $item->order_no;
                                    $targetArr[$item->id]['purchase_order_no'] = $item->purchase_order_no;
                                    $targetArr[$item->id]['buyer_name'] = $item->buyerName;
                                    $targetArr[$item->id]['supplier_name'] = $item->supplier_name;
                                    $targetArr[$item->id]['lc_no'] = $item->lc_no;
                                    $targetArr[$item->id]['lc_date'] = $item->lc_issue_date;
                                    $targetArr[$item->id]['bank'] = $item->lc_opening_bank;
                                    $blNoArr[$item->id][$item->delivery_id]['bl_no'] = $item->bl_no;
                                    $blNoArr[$item->id][$item->delivery_id]['ets'] = $lastEtsDate;
                                    $blNoArr[$item->id][$item->delivery_id]['ets_notification'] = $lastEtsNotificationDate;
                                }
                            }
                        }
                    }
                }

                //ETA
                if ($request->ref == '2') {
                    if (!empty($item->eta_info)) {
                        $etaInfoArr = json_decode($item->eta_info, true);
                        if (!empty($etaInfoArr)) {
                            $etaLastDataList = end($etaInfoArr);
                            $lastEtaDate = $etaLastDataList['eta_date'];
                            $lastEtaNotificationDate = $etaLastDataList['eta_notification_date'];
                            if (!empty($lastEtaDate) && !empty($lastEtaNotificationDate)) {
                                if (strtotime($lastEtaNotificationDate) <= strtotime($toDayDate) && strtotime($toDayDate) <= strtotime($lastEtaDate)) {
                                    $targetArr[$item->id]['delivery_id'] = $item->delivery_id;
                                    $targetArr[$item->id]['order_no'] = $item->order_no;
                                    $targetArr[$item->id]['purchase_order_no'] = $item->purchase_order_no;
                                    $targetArr[$item->id]['buyer_name'] = $item->buyerName;
                                    $targetArr[$item->id]['supplier_name'] = $item->supplier_name;
                                    $targetArr[$item->id]['lc_no'] = $item->lc_no;
                                    $targetArr[$item->id]['lc_date'] = $item->lc_issue_date;
                                    $targetArr[$item->id]['bank'] = $item->lc_opening_bank;

                                    $blNoArr[$item->id][$item->delivery_id]['bl_no'] = $item->bl_no;
                                    $blNoArr[$item->id][$item->delivery_id]['eta'] = $lastEtaDate;
                                    $blNoArr[$item->id][$item->delivery_id]['eta_notification'] = $lastEtaNotificationDate;
                                }
                            }
                        }
                    }
                }
            }
        }


        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('delivery', 'delivery.inquiry_id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
        ;
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.inquiry_id', 'delivery.id as delivery_id')
                ->get();




        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id]['product'] = $item->product_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id]['brand'] = $item->brand_name;
            }
        }


        //inquiry Details
        //START final targetArr
        if (!empty($targetArr)) {
            foreach ($targetArr as $inquiryId => $item) {
                $targetArr[$inquiryId] = $item;
                $targetArr[$inquiryId]['inquiryDetails'] = !empty($inquiryDetailsArr[$inquiryId]) ? $inquiryDetailsArr[$inquiryId] : '';
            }
        }

        //echo '<pre>';
        //print_r($etsSummaryArr);exit;
        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        //rowspan for brand
                        $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                        $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                        //rowspan for product
                        $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                        $rowspanArr['product'][$inquiryId][$productId] += 1;
                        //rowspan for delivery
                        $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                        $rowspanArr['inquiry'][$inquiryId] += 1;
                    }
                }
            }
        }
        //ENDOF Rowspan Arr



        $view = view('admin.others.showEtsEtaInfo', compact('request', 'targetArr', 'blNoArr', 'productArr', 'brandArr', 'rowspanArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getEtaSummary(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;

        $dateIndex = $request->date_index;
        if ($dateIndex < 0) {
            exit;
        }
        $date = date('d F Y', strtotime('+' . ($dateIndex) . ' days'));

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();

        $etaSummaryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', 'inquiry.buyer_id')
                ->join('supplier', 'supplier.id', 'inquiry.supplier_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->leftJoin('bank', 'bank.id', '=', 'inquiry.bank');
        if (!empty($id)) {
            $etaSummaryInfoArr = $etaSummaryInfoArr->where('inquiry.buyer_id', $id);
        }
        $etaSummaryInfoArr = $etaSummaryInfoArr->select('delivery.id', 'delivery.inquiry_id', 'delivery.eta_info', 'delivery.bl_no'
                        , 'delivery.express_tracking_no', 'inquiry.order_no'
                        , 'buyer.name as buyer_name', 'supplier.name as supplier_name'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS sales_person_name")
                        , 'inquiry.lc_no', 'inquiry.lc_issue_date', 'bank.name as lc_opening_bank')
                ->get();

        $etaSummaryArr = [];
        if (!$etaSummaryInfoArr->isEmpty()) {
            foreach ($etaSummaryInfoArr as $eta) {
                $etaInfoArr = json_decode($eta->eta_info, true);
                $etaArr = end($etaInfoArr);
                if ($etaArr['eta_date'] == $date) {
                    $etaSummaryArr[$eta->id]['id'] = $eta->id;
                    $etaSummaryArr[$eta->id]['order_no'] = $eta->order_no;
                    $etaSummaryArr[$eta->id]['buyer_name'] = $eta->buyer_name;
                    $etaSummaryArr[$eta->id]['supplier_name'] = $eta->supplier_name;
                    $etaSummaryArr[$eta->id]['sales_person_name'] = $eta->sales_person_name;
                    $etaSummaryArr[$eta->id]['bl_no'] = $eta->bl_no;
                    $etaSummaryArr[$eta->id]['express_tracking_no'] = $eta->express_tracking_no;
                    $etaSummaryArr[$eta->id]['lc_no'] = $eta->lc_no;
                    $etaSummaryArr[$eta->id]['lc_date'] = $eta->lc_issue_date;
                    $etaSummaryArr[$eta->id]['bank'] = $eta->lc_opening_bank;
                }
            }
        }


        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('delivery', 'delivery.inquiry_id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
        ;
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.inquiry_id', 'delivery.id as delivery_id')
                ->get();




        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $inquiryDetailsArr[$item->delivery_id][$item->product_id][$item->brand_id]['product'] = $item->product_name;
                $inquiryDetailsArr[$item->delivery_id][$item->product_id][$item->brand_id]['brand'] = $item->brand_name;
            }
        }


        //inquiry Details
        //START final targetArr
        if (!empty($etaSummaryArr)) {
            foreach ($etaSummaryArr as $deliveryId => $item) {
                $etaSummaryArr[$deliveryId] = $item;
                $etaSummaryArr[$deliveryId]['inquiryDetails'] = !empty($inquiryDetailsArr[$deliveryId]) ? $inquiryDetailsArr[$deliveryId] : '';
            }
        }

        //echo '<pre>';
        //print_r($etsSummaryArr);exit;
        //ENDOF final targetArr
        //START Rowspan Arr

        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $deliveryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        //rowspan for brand
                        $rowspanArr['brand'][$deliveryId][$productId][$brandId] = !empty($rowspanArr['brand'][$deliveryId][$productId][$brandId]) ? $rowspanArr['brand'][$deliveryId][$productId][$brandId] : 0;
                        $rowspanArr['brand'][$deliveryId][$productId][$brandId] += 1;
                        //rowspan for product
                        $rowspanArr['product'][$deliveryId][$productId] = !empty($rowspanArr['product'][$deliveryId][$productId]) ? $rowspanArr['product'][$deliveryId][$productId] : 0;
                        $rowspanArr['product'][$deliveryId][$productId] += 1;
                        //rowspan for delivery
                        $rowspanArr['delivery'][$deliveryId] = !empty($rowspanArr['delivery'][$deliveryId]) ? $rowspanArr['delivery'][$deliveryId] : 0;
                        $rowspanArr['delivery'][$deliveryId] += 1;
                    }
                }
            }
        }
        //ENDOF Rowspan Arr
        // echo '<pre>';print_r($etsSummaryArr);exit;

        $view = view('admin.others.showEtaSummary', compact('request', 'etaSummaryArr', 'date', 'productArr', 'brandArr', 'rowspanArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getEtsSummary(Request $request) {

        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;
        $dateIndex = $request->date_index;
        if ($dateIndex < 0) {
            exit;
        }
        $date = date('d F Y', strtotime('+' . ($dateIndex) . ' days'));

        $etsSummaryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', 'inquiry.buyer_id')
                ->join('supplier', 'supplier.id', 'inquiry.supplier_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->leftJoin('bank', 'bank.id', '=', 'inquiry.bank');
        if (!empty($id)) {
            $etsSummaryInfoArr = $etsSummaryInfoArr->where('inquiry.buyer_id', $id);
        }
        $etsSummaryInfoArr = $etsSummaryInfoArr->select('delivery.id', 'delivery.inquiry_id', 'delivery.ets_info', 'delivery.bl_no'
                        , 'delivery.express_tracking_no', 'inquiry.order_no'
                        , 'buyer.name as buyer_name', 'supplier.name as supplier_name'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS sales_person_name")
                        , 'inquiry.lc_no', 'inquiry.lc_issue_date', 'bank.name as lc_opening_bank')
                ->get();

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();




        $etsSummaryPreArr = $etsSummaryArr = [];
        if (!$etsSummaryInfoArr->isEmpty()) {
            foreach ($etsSummaryInfoArr as $ets) {
                $etsInfoArr = json_decode($ets->ets_info, true);
                $etsArr = end($etsInfoArr);
                if ($etsArr['ets_date'] == $date) {
                    $etsSummaryArr[$ets->id]['id'] = $ets->id;
                    $etsSummaryArr[$ets->id]['order_no'] = $ets->order_no;
                    $etsSummaryArr[$ets->id]['buyer_name'] = $ets->buyer_name;
                    $etsSummaryArr[$ets->id]['supplier_name'] = $ets->supplier_name;
                    $etsSummaryArr[$ets->id]['sales_person_name'] = $ets->sales_person_name;
                    $etsSummaryArr[$ets->id]['bl_no'] = $ets->bl_no;
                    $etsSummaryArr[$ets->id]['express_tracking_no'] = $ets->express_tracking_no;
                    $etsSummaryArr[$ets->id]['lc_no'] = $ets->lc_no;
                    $etsSummaryArr[$ets->id]['lc_date'] = $ets->lc_issue_date;
                    $etsSummaryArr[$ets->id]['bank'] = $ets->lc_opening_bank;
                }
            }
        }


        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('delivery', 'delivery.inquiry_id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
        ;
        if (!empty($id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.inquiry_id', 'delivery.id as delivery_id')
                ->get();




        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $inquiryDetailsArr[$item->delivery_id][$item->product_id][$item->brand_id]['product'] = $item->product_name;
                $inquiryDetailsArr[$item->delivery_id][$item->product_id][$item->brand_id]['brand'] = $item->brand_name;
            }
        }


        //inquiry Details
        //START final targetArr
        if (!empty($etsSummaryArr)) {
            foreach ($etsSummaryArr as $deliveryId => $item) {
                $etsSummaryArr[$deliveryId] = $item;
                $etsSummaryArr[$deliveryId]['inquiryDetails'] = !empty($inquiryDetailsArr[$deliveryId]) ? $inquiryDetailsArr[$deliveryId] : '';
            }
        }

        //echo '<pre>';
        //print_r($etsSummaryArr);exit;
        //ENDOF final targetArr
        //START Rowspan Arr

        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $deliveryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        //rowspan for brand
                        $rowspanArr['brand'][$deliveryId][$productId][$brandId] = !empty($rowspanArr['brand'][$deliveryId][$productId][$brandId]) ? $rowspanArr['brand'][$deliveryId][$productId][$brandId] : 0;
                        $rowspanArr['brand'][$deliveryId][$productId][$brandId] += 1;
                        //rowspan for product
                        $rowspanArr['product'][$deliveryId][$productId] = !empty($rowspanArr['product'][$deliveryId][$productId]) ? $rowspanArr['product'][$deliveryId][$productId] : 0;
                        $rowspanArr['product'][$deliveryId][$productId] += 1;
                        //rowspan for delivery
                        $rowspanArr['delivery'][$deliveryId] = !empty($rowspanArr['delivery'][$deliveryId]) ? $rowspanArr['delivery'][$deliveryId] : 0;
                        $rowspanArr['delivery'][$deliveryId] += 1;
                    }
                }
            }
        }
        //ENDOF Rowspan Arr
        // echo '<pre>';print_r($etsSummaryArr);exit;

        $view = view('admin.others.showEtsSummary', compact('request', 'etsSummaryArr', 'date', 'productArr', 'brandArr', 'rowspanArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getProductCatalog(Request $request) {
        //buyer info
        $target = Buyer::select('id', 'name', 'show_all_brands')->where('buyer.user_id', Auth::user()->id)->first();
        $id = !empty($target->id) ? $target->id : 0;
        $name = !empty($target->name) ? $target->name : 0;
        $certificateArr = Certificate::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //previous technical data sheet
        $previousTechDataSheetArr = ProductTechDataSheet::select('product_id', 'brand_id', 'data_sheet')->get();
        $previousDataSheetArr = [];
        if (!$previousTechDataSheetArr->isEmpty()) {
            foreach ($previousTechDataSheetArr as $previousTechDataSheet) {
                $previousDataSheetArr[$previousTechDataSheet->product_id][$previousTechDataSheet->brand_id] = json_decode($previousTechDataSheet->data_sheet, true);
            }
        }


        //start :: product info
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->join('country', 'country.id', 'brand.origin')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo', 'measure_unit.name as unit'
                                , 'country.name as country_of_origin', 'brand.certificate')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $productInfoArr = $productRowSpanArr = [];

        /*         * **************************************** START:: From Buyer Profile ********************** */
        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();


        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }
        /*         * **************************************** END:: From Buyer Profile ********************** */

        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                if (!empty($target->show_all_brands)) {
                    $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                    $productInfoArr[$item->product_id]['unit'] = $item->unit;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['origin'] = $item->country_of_origin;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['certificate'] = json_decode($item->certificate, true);

                    $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
                } else {
                    if (!empty($brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                            if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                                $productInfoArr[$item->product_id]['unit'] = $item->unit;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['origin'] = $item->country_of_origin;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['certificate'] = json_decode($item->certificate, true);

                                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
                            }
                        }
                    }
                }
            }
        }

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();
        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }

        $view = view('admin.others.showProductCatalog', compact('target', 'request', 'productInfoArr', 'productRowSpanArr'
                        , 'brandWiseVolumeRateArr', 'certificateArr', 'previousDataSheetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getInquirySummary(Request $request) {
        $dateIndex = $request->date_index;
        if ($dateIndex < 0) {
            exit;
        }
        $date = date('Y-m-d', strtotime('-' . (29 - $dateIndex) . ' days'));

        $salesPersonList = [Auth::user()->id => Auth::user()->id] + User::where('supervisor_id', Auth::user()->id)
                        ->where('allowed_for_sales', '1')
                        ->pluck('id', 'id')->toArray();


        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        $inquiryDetailsInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.creation_date', $date)
                ->where('inquiry.status', '1');

        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $inquiryDetailsInfo = $inquiryDetailsInfo->whereIn('salespersons_id', $salesPersonList);
        }
        $inquiryDetailsInfo = $inquiryDetailsInfo->select('inquiry_details.product_id'
                        , 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'buyer.name as buyer_name', 'inquiry.buyer_contact_person'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS sales_person_name"))
                ->get();

        $inquiryArr = $inquirySummaryArr = $rowspanArr = [];
        $inquiryRowSpanArr = $productRowSpanArr = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfo->isEmpty()) {
            foreach ($inquiryDetailsInfo as $item) {
                $gradeId = $item->grade_id ?? 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryArr[$item->inquiry_id]['buyer'] = $item->buyer_name ?? '';
                $inquiryArr[$item->inquiry_id]['buyer_contact_person'] = $item->buyer_contact_person ?? '';
                $inquiryArr[$item->inquiry_id]['sales_person'] = $item->sales_person_name ?? '';

                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['product_name'] = $item->product_name ?? '';

                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name ?? '';
                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['grade_name'] = $item->grade_name ?? '';

                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['quantity'] = $item->quantity ?? 0.00;



                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['unit_price'] = $item->unit_price ?? 0.00;

                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['total_price'] = $item->total_price ?? 0.00;
                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['gsm'] = $item->gsm ?? '';


                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['unit'] = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                $inquiryArr[$item->inquiry_id]['product'][$item->product_id]['brand'][$item->brand_id]['grade'][$gradeId]['gsm'][$gsm]['per_unit'] = !empty($item->unit_name) ? ' /' . $item->unit_name : '';
            }

            if (!empty($inquiryArr)) {
                foreach ($inquiryArr as $inquiryId => $inquiry) {
                    if (!empty($inquiry['product'])) {
                        foreach ($inquiry['product'] as $productId => $product) {
                            if (!empty($product['brand'])) {
                                foreach ($product['brand'] as $brandId => $brand) {
                                    if (!empty($brand['grade'])) {
                                        foreach ($brand['grade'] as $gradeId => $gradeData) {
                                            foreach ($gradeData['gsm'] as $gsm => $grade) {
                                                $inquirySummaryArr['total_quantity'] = $inquirySummaryArr['total_quantity'] ?? 0.00;
                                                $inquirySummaryArr['total_quantity'] += ($grade['quantity'] ?? 0.00);

                                                $inquirySummaryArr['total_amount'] = $inquirySummaryArr['total_amount'] ?? 0.00;
                                                $inquirySummaryArr['total_amount'] += ($grade['total_price'] ?? 0.00);

                                                //rowspan for grade
                                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                                //rowspan for brand
                                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                                //rowspan for product
                                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                                //rowspan for inquiry
                                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                                $rowspanArr['inquiry'][$inquiryId] += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $view = view('admin.others.showInquirySummary', compact('request', 'inquiryArr', 'rowspanArr'
                        , 'productRowSpanArr', 'brandRowSpanArr', 'date', 'inquirySummaryArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getCrmOpportunityList(Request $request) {
        $todayDate = date('Y-m-d');
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $todayWeek = date('w') + 1;
        $weekStatDate = date('Y-m-d 00:00:00', strtotime('-' . $todayWeek . ' days'));

        if ($request->duration == 0) {
            $startDate = $weekStatDate;
            $endDate = $todayEnd;
        } elseif ($request->duration == 1) {
            $startDate = $todayStart;
            $endDate = $todayEnd;
        }
        $revoked = $request->revoked_status == '1' ? 1 : 0;
        $cancelled = $request->status == '3' ? 1 : 0;
        $void = $request->status == '4' ? 1 : 0;

        $targetArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.status', $request->status)->where('crm_opportunity.revoked_status', $request->revoked_status);
        if ($revoked == 0 && $cancelled == 0 && $void == 0) {
            $targetArr = $targetArr->where('crm_opportunity.last_activity_status', $request->last_activity_status);
        }
        $targetArr = $targetArr->where('crm_opportunity.dispatch_status', $request->dispatch_status)
                ->where('crm_opportunity.approval_status', $request->approval_status)
                ->whereBetween('crm_opportunity.updated_at', [$startDate, $endDate])
                ->get();

        $productArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $target) {
                $productArr[$target->id] = !empty($target->product_data) ? json_decode($target->product_data, true) : [];
            }
        }

        $productRowSpanArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $opId => $product) {
                foreach ($product as $pKey => $pInfo) {
                    $productRowSpanArr[$opId] = !empty($productRowSpanArr[$opId]) ? $productRowSpanArr[$opId] : 0;
                    $productRowSpanArr[$opId] += 1;
                }
            }
        }



        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $view = view('admin.others.showCrmOpportunityList', compact('targetArr', 'request', 'productArr', 'productRowSpanArr'
                        , 'buyerList', 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

}
