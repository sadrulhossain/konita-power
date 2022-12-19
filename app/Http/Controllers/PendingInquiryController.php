<?php

namespace App\Http\Controllers;

use Validator;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmActivityLog;
use App\User;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use App\CrmOpportunityToMember;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;

class PendingInquiryController extends Controller {

    private $controller = 'PendingInquiry';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::where('status', '1')->pluck('name', 'id')->toArray();
        $approvalStatusList = ['' => __('label.SELECT_APPROVAL_STATUS_OPTION')] + ['0' => __('label.PENDING_FOR_APPROVAL'), '1' => __('label.APPROVED'), '2' => __('label.DENIED')];

        $memberList = ['0' => __('label.SELECT_MEMBER_OPT')] + CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->where('users.status', '1')->pluck('name', 'users.id')->toArray();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $buyerByNameArr = CrmOpportunity::where('buyer_has_id', '0')->orderBy('buyer', 'asc')
                        ->pluck('buyer', 'buyer')->toArray();
        $buyerByIdArr = CrmOpportunity::join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->orderBy('buyer.name', 'asc')
                        ->pluck('buyer.name', 'crm_opportunity.buyer')->toArray();

        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerByNameArr + $buyerByIdArr;

        $targetArr = CrmOpportunityToMember::leftJoin('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                        ->join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                                , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                                , 'crm_opportunity.approval_status'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"), 'crm_opportunity.product_data')
                        ->where('crm_opportunity.status', '2')->where('crm_opportunity.dispatch_status', '1');


        //begin filtering
        $buyerSearch = $request->buyer;
        if (!empty($buyerSearch)) {
            $targetArr = $targetArr->where('crm_opportunity.buyer', $buyerSearch);
        }

        if (!empty($request->source_id)) {
            $targetArr = $targetArr->where('crm_opportunity.source_id', $request->source_id);
        }
        if (!empty($request->assigned_to)) {
            $targetArr = $targetArr->where('crm_opportunity_to_member.member_id', $request->assigned_to);
        }

        if (isset($request->approval_status)) {
            $targetArr = $targetArr->where('crm_opportunity.approval_status', $request->approval_status);
        }

        $opportunityInfo = $targetArr->get();
        $productArr = $productOpportunityArr = $opportunityFilterIdArr = $opportunityProductFilterIdArr = [];
        $brandArr = $brandOpportunityArr = $opportunityBrandFilterIdArr = [];

        if (!$opportunityInfo->isEmpty()) {
            foreach ($opportunityInfo as $item) {
                $productData = json_decode($item->product_data, TRUE);
                if (!empty($productData)) {
                    foreach ($productData as $key => $info) {
                        if (!empty($info['product'])) {
                            $productOpportunityArr[$item->id][$info['product']] = $info['product'];
                            if ($info['product_has_id'] == '1') {
                                $productArr[$info['product']] = $productList[$info['product']];
                            } else {
                                $productArr[$info['product']] = $info['product'];
                            }
                        }
                        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productArr;
                        if (!empty($info['brand'])) {
                            $brandOpportunityArr[$item->id][$info['brand']] = $info['brand'];
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

        if (!empty($request->update_from_date)) {
            $fromDate = date('Y-m-d 00:00:00', strtotime($request->update_from_date));
            $targetArr = $targetArr->where('crm_opportunity.updated_at', '>=', $fromDate);
        }

        if (!empty($request->update_to_date)) {
            $toDate = date('Y-m-d 23:00:00', strtotime($request->update_to_date));
            $targetArr = $targetArr->where('crm_opportunity.updated_at', '<=', $toDate);
        }

        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/pendingInquiry?page=' . $page);
        }

        //Opportunity has Activity Log
        $hasActivityLog = CrmActivityLog::join('crm_opportunity', 'crm_opportunity.id', '=', 'crm_activity_log.opportunity_id')
                        ->where('crm_opportunity.status', '2')
                        ->where('crm_opportunity.dispatch_status', '1')->pluck('crm_activity_log.opportunity_id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        return view('pendingInquiry.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'approvalStatusList', 'hasActivityLog', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'productArr'
                                , 'brandArr', 'assignedPersonList', 'memberList'));
    }

    public function filter(Request $request) {
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&approval_status=' . $request->approval_status
                . '&product=' . $request->product . '&brand=' . $request->brand
                . '&assigned_to=' . $request->assigned_to. '&update_from_date=' . $request->update_from_date 
                . '&update_to_date=' . $request->update_to_date;
        return Redirect::to('pendingInquiry?' . $url);
    }

    public function approve(Request $request) {
        $target = CrmOpportunity::find($request->opportunity_id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_APPROVED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.OPPORTUNITY_COULD_NOT_BE_APPROVED')), 401);
        }
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'pendingInquiry.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    public function showRemarksModal(Request $request) {
        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.id', $request->opportunity_id)->first();

        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $productArr = !empty($target->product_data) ? json_decode($target->product_data, true) : [];

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('pendingInquiry.showDenyModal', compact('request', 'target', 'contactArr', 'productArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList'))->render();
        return response()->json(['html' => $view]);
    }

    public function deny(Request $request) {
        $target = CrmOpportunity::find($request->opportunity_id);

        $target->approval_status = '2';
        $target->deny_remarks = $request->remarks;
        $target->denied_at = date('Y-m-d H:i:s');
        $target->denied_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_DENIED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.OPPORTUNITY_COULD_NOT_BE_DENIED')), 401);
        }
    }

    //************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'pendingInquiry.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

    //************************* END :: Activity Log ***********************//
}
