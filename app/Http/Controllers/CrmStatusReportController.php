<?php

namespace App\Http\Controllers;

use Validator;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmActivityStatus;
use App\CrmOpportunityToMember;
use App\CrmActivityLog;
use App\User;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use App\Country;
use App\CompanyInformation;
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

class CrmStatusReportController extends Controller {

    public function index(Request $request) {
        //passing param for custom function

        $qpArr = $request->all();

        $activityStatusList = CrmActivityStatus::pluck('name', 'id')->toArray();

        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::pluck('name', 'id')->toArray();
        $employeeList = ['0' => __('label.SELECT_EMPLOYEE_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->pluck('name', 'users.id')->toArray();
        $memberList = ['0' => __('label.SELECT_MEMBER_OPT')] + CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->pluck('name', 'users.id')->toArray();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        $statusList = Common::getOpportunityStatusList(1);

        $buyerByNameArr = CrmOpportunity::where('buyer_has_id', '0')->orderBy('buyer', 'asc')
                        ->pluck('buyer', 'buyer')->toArray();
        $buyerByIdArr = CrmOpportunity::join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->orderBy('buyer.name', 'asc')
                        ->pluck('buyer.name', 'crm_opportunity.buyer')->toArray();

        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerByNameArr + $buyerByIdArr;
        $countryList = Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        $opportunityCountArr = $targetArr = $contactArr = $productArr = $productRowspanArr = [];
        $hasActivityLog = [];

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';
        $productArrFilter = $productOpportunityArr = $opportunityFilterIdArr = $opportunityProductFilterIdArr = [];
        $brandArr = $brandOpportunityArr = $opportunityBrandFilterIdArr = [];

        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();


        $opportunityInfo = CrmOpportunityToMember::leftJoin('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                        ->join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')->select('crm_opportunity.product_data')->get();

        if (!$opportunityInfo->isEmpty()) {
            foreach ($opportunityInfo as $item) {
                $productData = json_decode($item->product_data, TRUE);
                if (!empty($productData)) {
                    foreach ($productData as $key => $info) {
                        if (!empty($info['product'])) {

                            if ($info['product_has_id'] == '1') {
                                $productArrFilter[$info['product']] = $productList[$info['product']];
                            } else {
                                $productArrFilter[$info['product']] = $info['product'];
                            }
                        }
                        $productArrFilter = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productArrFilter;
                        if (!empty($info['brand'])) {

                            if ($info['brand_has_id'] == '1') {
                                $brandArr[$info['brand']] = $brandList[$info['brand']];
                            } else {
                                $brandArr[$info['brand']] = $info['brand'];
                            }
                        }
                        $brandArr = ['0' => __('label.SELECT_BRAND_OPT')] + $brandArr;
                    }
                }
            }
        }

        if ($request->generate == 'true') {
            if (!empty($request->update_from_date)) {
                $fromDate = date('Y-m-d 00:00:00', strtotime($request->update_from_date));
            }
            if (!empty($request->update_to_date)) {
                $toDate = date('Y-m-d 23:59:59', strtotime($request->update_to_date));
            }

            $targetArr = CrmOpportunityToMember::leftJoin('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                    ->join('users', 'users.id', 'crm_opportunity.created_by')
                    ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                    ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                            , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                            , 'crm_opportunity.buyer_contact_person', 'crm_opportunity.product_data'
                            , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator")
                            , 'crm_opportunity.status', 'crm_opportunity.revoked_status', 'crm_opportunity.last_activity_status'
                            , 'crm_opportunity.approval_status', 'crm_opportunity.dispatch_status', 'crm_opportunity_to_member.member_id')
                    ->whereBetween('crm_opportunity.updated_at', [$fromDate, $toDate]);


            $opportunityCountInfo = $targetArr->get();
            $opportunityCountArr = Common::getOpportunityCount($opportunityCountInfo);
            //begin filtering
            $buyerSearch = $request->buyer;
            if (!empty($buyerSearch)) {
                $targetArr = $targetArr->where('crm_opportunity.buyer', $buyerSearch);
            }

            if (!empty($request->source_id)) {
                $targetArr = $targetArr->where('crm_opportunity.source_id', $request->source_id);
            }

            if (!empty($request->created_by)) {
                $targetArr = $targetArr->where('crm_opportunity.created_by', $request->created_by);
            }
            if (!empty($request->assigned_to)) {
                $targetArr = $targetArr->where('crm_opportunity_to_member.member_id', $request->assigned_to);
            }
            if (!empty($request->status)) {
                if ($request->status == '1') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '0');
                } elseif ($request->status == '2') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '1')->where('crm_opportunity.last_activity_status', '0');
                } elseif ($request->status == '3') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '3');
                } elseif ($request->status == '4') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '4');
                } elseif ($request->status == '5') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '2')->where('crm_opportunity.dispatch_status', '1')->where('crm_opportunity.approval_status', '0');
                } elseif ($request->status == '6') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '2')->where('crm_opportunity.dispatch_status', '1')->where('crm_opportunity.approval_status', '1');
                } elseif ($request->status == '7') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '2')->where('crm_opportunity.dispatch_status', '1')->where('crm_opportunity.approval_status', '2');
                } elseif ($request->status == '8') {
                    $targetArr = $targetArr->where('crm_opportunity.status', '1')->where('crm_opportunity.revoked_status', '1');
                } else {
                    $lastActivityStatus = $request->status - 8;
                    $targetArr = $targetArr->where('crm_opportunity.last_activity_status', $lastActivityStatus);
                }
            }

            $opportunityInfo = $targetArr->get();


            if (!$opportunityInfo->isEmpty()) {
                foreach ($opportunityInfo as $item) {
                    $productData = json_decode($item->product_data, TRUE);
                    if (!empty($productData)) {
                        foreach ($productData as $key => $info) {
                            if (!empty($info['product'])) {
                                $productOpportunityArr[$item->id][$info['product']] = $info['product'];
                                if ($info['product_has_id'] == '1') {
                                    //$productArrFilter[$info['product']] = $productList[$info['product']];
                                } else {
                                    //$productArrFilter[$info['product']] = $info['product'];
                                }
                            }
                            //$productArrFilter = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productArrFilter;
                            if (!empty($info['brand'])) {
                                $brandOpportunityArr[$item->id][$info['brand']] = $info['brand'];
                                if ($info['brand_has_id'] == '1') {
                                    //$brandArr[$info['brand']] = $brandList[$info['brand']];
                                } else {
                                    //$brandArr[$info['brand']] = $info['brand'];
                                }
                            }
                            //$brandArr = ['0' => __('label.SELECT_BRAND_OPT')] + $brandArr;
                        }
                    }
                }
            }

            $itemFilter = 0;
            if (!empty($request->product)) {
                if (!empty($productOpportunityArr)) {
                    foreach ($productOpportunityArr as $id => $product) {
                        if (array_key_exists($request->product, $product)) {
                            $opportunityProductFilterIdArr[$id] = $id;
                        }
                    }
                }
                $itemFilter = 1;
            }
            if (!empty($request->brand)) {
                if (!empty($brandOpportunityArr)) {
                    foreach ($brandOpportunityArr as $id => $brand) {
                        if (array_key_exists($request->brand, $brand)) {
                            $opportunityBrandFilterIdArr[$id] = $id;
                        }
                    }
                }
                $itemFilter = 1;
            }
//        echo '<pre>';
//        print_r($opportunityProductFilterIdArr);
//        exit;


            if (empty($opportunityProductFilterIdArr) && !empty($opportunityBrandFilterIdArr)) {
                $opportunityFilterIdArr = $opportunityBrandFilterIdArr;
            }
            if (!empty($opportunityProductFilterIdArr) && empty($opportunityBrandFilterIdArr)) {
                $opportunityFilterIdArr = $opportunityProductFilterIdArr;
            }
            if (!empty($opportunityProductFilterIdArr) && !empty($opportunityBrandFilterIdArr)) {
                $opportunityFilterIdArr = array_intersect($opportunityProductFilterIdArr, $opportunityBrandFilterIdArr);
            }


            if ($itemFilter == 1) {
                $targetArr = $targetArr->whereIn('crm_opportunity.id', $opportunityFilterIdArr);
            }
            //end filtering
            $targetArr = $targetArr->orderBy('crm_opportunity.updated_at', 'desc')->get();

            $contactInfoArr = [];
            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $target) {
                    $contactInfoArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
                    $hasPrimary = array_keys(array_column($contactInfoArr, 'primary'), 1);
                    if (!empty($contactInfoArr)) {
                        if (!empty($hasPrimary)) {
                            foreach ($contactInfoArr as $cKey => $cInfo) {
                                if ($cInfo['primary'] == 1) {
                                    $contactArr[$target->id] = $cInfo;
                                }
                            }
                        } else {
                            $contactArr[$target->id] = reset($contactInfoArr);
                        }
                    }

                    $productArr[$target->id] = !empty($target->product_data) ? json_decode($target->product_data, true) : [];
                }
            }

//            echo '<pre>';
//            print_r($productArr);
//            exit;


            if (!empty($productArr)) {
                foreach ($productArr as $opId => $product) {
                    foreach ($product as $pKey => $pInfo) {
                        $productRowspanArr[$opId] = !empty($productRowspanArr[$opId]) ? $productRowspanArr[$opId] : 0;
                        $productRowspanArr[$opId] += 1;
                    }
                }
            }
            //Opportunity has Activity Log
            $hasActivityLog = CrmActivityLog::join('crm_opportunity', 'crm_opportunity.id', '=', 'crm_activity_log.opportunity_id')
                            ->pluck('crm_activity_log.opportunity_id')->toArray();

            //KONITA INFO
            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[83][6])) {
                return redirect('/dashboard');
            }
            return view('report.crmStatus.print.index')->with(compact('request', 'targetArr', 'qpArr', 'buyerArr'
                                    , 'sourceList', 'employeeList', 'hasActivityLog', 'activityStatusList', 'statusList', 'opportunityCountArr'
                                    , 'buyerList', 'productList', 'brandList', 'gradeList', 'fromDate', 'toDate', 'contactArr'
                                    , 'productArr', 'productRowspanArr', 'konitaInfo', 'phoneNumber', 'memberList', 'assignedPersonList', 'countryList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[83][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.crmStatus.print.index', compact('request', 'targetArr', 'qpArr', 'buyerArr'
                                    , 'sourceList', 'employeeList', 'hasActivityLog', 'activityStatusList', 'statusList', 'opportunityCountArr'
                                    , 'buyerList', 'productList', 'brandList', 'gradeList', 'fromDate', 'toDate', 'contactArr'
                                    , 'productArr', 'productRowspanArr', 'konitaInfo', 'phoneNumber', 'memberList', 'assignedPersonList', 'countryList'))
                    ->setPaper('a3', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif'])
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
//            return $pdf->download('sales_status_report.pdf');
            return $pdf->stream();
        } else {
            return view('report.crmStatus.index')->with(compact('request', 'targetArr', 'qpArr', 'buyerArr'
                                    , 'sourceList', 'employeeList', 'hasActivityLog', 'activityStatusList', 'statusList', 'opportunityCountArr'
                                    , 'buyerList', 'productList', 'brandList', 'gradeList', 'fromDate', 'toDate', 'contactArr'
                                    , 'productArr', 'productRowspanArr', 'konitaInfo', 'phoneNumber', 'memberList', 'assignedPersonList'
                                    , 'countryList', 'productArrFilter', 'brandArr'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'update_from_date' => 'required',
            'update_to_date' => 'required',
        ];

        $messages = [
            'update_from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'update_to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'update_from_date=' . $request->update_from_date . '&update_to_date=' . $request->update_to_date
                . '&buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&created_by=' . $request->created_by . '&status=' . $request->status
                . '&assigned_to=' . $request->assigned_to . '&product=' . $request->product
                . '&brand=' . $request->brand;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('crmStatusReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('crmStatusReport?generate=true&' . $url);
    }

    //************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'report.crmStatus.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

    //************************* End :: Activity Log ***********************//
}
