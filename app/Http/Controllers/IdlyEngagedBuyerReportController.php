<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\Brand;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFollowUpHistory;
use App\Grade;
use App\CompanyInformation;
use App\Country;
use App\Division;
use App\BuyerToProduct;
use App\ProductToGrade;
use App\ProductToBrand;
use App\SalesPersonToBuyer;
use App\User;
use App\Lead;
use App\InquiryDetails;
use App\BuyerMachineType;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class IdlyEngagedBuyerReportController extends Controller {

    public function index(Request $request) {

        $buyerInfoArr = $buyerLastPIList = $buyerList = $idleTimeArr = [];

        if ($request->generate == 'true') {
            $today = date("Y-m-d");
            $idleFor = !empty($request->idle_for) ? $request->idle_for : 0;
            $fewMonthsAgo = date("Y-m-d", strtotime("-" . $idleFor . " month"));

            $buyerLastPIList = Lead::select(DB::raw("MAX(pi_date) as last_pi"), 'buyer_id')->groupBy('buyer_id')->whereIn('order_status', ['2', '3', '4'])->pluck('last_pi', 'buyer_id')->toArray();

            if (!empty($buyerLastPIList)) {
                foreach ($buyerLastPIList as $buyerId => $lastPI) {
                    if ($lastPI < $fewMonthsAgo) {
                        $buyerList[$buyerId] = $buyerId;
                        $idleTime = Helper::dateDiff($lastPI, $today);
                        $idleTimeArr[$buyerId] = $idleTime;
                    }
                }
            }

            if (!empty($buyerList)) {
                $buyerInfoArr = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                                ->leftJoin('division', 'division.id', '=', 'buyer.division_id')
                                ->whereIn('buyer.id', $buyerList)
                                ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                                        , 'division.name as division_name', 'buyer.id', 'buyer.code', 'buyer.logo'
                                        , 'buyer.status')
                                ->orderBy('buyer.name', 'asc')
                                ->get()->toArray();
            }
        }

        $contactArr = $buyerIdArr = [];
        if (!empty($buyerInfoArr)) {
            foreach ($buyerInfoArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
            }
        }

        //get followup history
        if (!empty($buyerList)) {
            $followUpPrevHistory = BuyerFollowUpHistory::whereIn('buyer_id', $buyerList)
                            ->pluck('history', 'buyer_id')->toArray();
        }


        $finalArr = $followUpHistoryArr = [];
        if (!empty($followUpPrevHistory)) {
            foreach ($followUpPrevHistory as $buyerId => $history) {
                $followUpHistoryArr[$buyerId] = json_decode($history, true);
                krsort($followUpHistoryArr[$buyerId]);
                $i = 0;

                if (!empty($followUpHistoryArr[$buyerId])) {
                    foreach ($followUpHistoryArr[$buyerId] as $followUpHistory) {
                        $finalArr[$buyerId][$followUpHistory['updated_at']][$i]['status'] = $followUpHistory['status'];
                        $i++;
                    }
                }
            }
            krsort($finalArr[$buyerId]);
        }

        $latestFollowupArr = [];
        if (!empty($finalArr)) {
            foreach ($finalArr as $buyerId => $followUpHistory) {
                $latestFollowup = reset($followUpHistory);
                $latestFollowupArr[$buyerId]['status'] = $latestFollowup[0]['status'];
            }
        }

//        echo '<pre>';
//        print_r($latestFollowupArr);
//        exit;


        $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"), 'buyer_id')
                        ->groupBy('buyer_id')->pluck('no_of_sales_person', 'buyer_id')->toArray();


        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[73][6])) {
                return redirect('/dashboard');
            }
            return view('report.idlyEngagedBuyer.print.index')->with(compact('request', 'buyerInfoArr'
                                    , 'contactArr', 'konitaInfo', 'phoneNumber', 'salesPersonToBuyerCountList'
                                    , 'latestFollowupArr', 'idleTimeArr'));
        } else {
            return view('report.idlyEngagedBuyer.index')->with(compact('request', 'buyerInfoArr'
                                    , 'contactArr', 'salesPersonToBuyerCountList', 'latestFollowupArr'
                                    , 'idleTimeArr'));
        }
    }

    public function filter(Request $request) {
        $rules = [
            'idle_for' => 'required',
        ];
        $url = 'idle_for=' . $request->idle_for;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('idlyEngagedBuyerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('idlyEngagedBuyerReport?generate=true&' . $url);
    }

    public function getRelatedSalesPersonList(Request $request) {
        $loadView = 'report.idlyEngagedBuyer.showRelatedSalesPersonList';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

    //****************************** start :: buyer profile ********************************//
    public function profile(Request $request, $id) {
        $loadView = 'report.idlyEngagedBuyer.profile.show';
        return Common::buyerProfile($request, $id, $loadView);
    }

    public function printProfile(Request $request, $id) {
        $loadView = 'report.idlyEngagedBuyer.profile.print.show';
        $modueId = 73;
        return Common::buyerPrintProfile($request, $id, $loadView, $modueId);
    }

    public function getInvolvedOrderList(Request $request) {
        $loadView = 'report.idlyEngagedBuyer.profile.showInvolvedOrderList';
        return Common::getInvolvedOrderList($request, $loadView);
    }

    public  function printInvolvedOrderList(Request $request) {
        $loadView = 'report.idlyEngagedBuyer.profile.print.showInvolvedOrderList';
        $modueId = 73;
        return Common::printInvolvedOrderList($request, $loadView, $modueId);
    }

    //****************************** end :: buyer profile *********************************//
}
