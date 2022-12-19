<?php

namespace App\Http\Controllers;

use Validator;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmOpportunityToMember;
use App\CrmActivityLog;
use App\User;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;

class CrmVoidOpportunityController extends Controller {

    private $controller = 'CrmVoidOpportunity';

    public function index(Request $request) {
//passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::pluck('name', 'id')->toArray();

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

        $buyerByNameArr = CrmOpportunityToMember::join('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                        ->where('buyer_has_id', '0')->orderBy('buyer', 'asc');
        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $buyerByNameArr = $buyerByNameArr->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        }
        $buyerByNameArr = $buyerByNameArr->pluck('buyer', 'buyer')->toArray();

        $buyerByIdArr = CrmOpportunityToMember::join('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                        ->join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->orderBy('buyer.name', 'asc');
        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $buyerByIdArr = $buyerByIdArr->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        }
        $buyerByIdArr = $buyerByIdArr->pluck('buyer.name', 'crm_opportunity.buyer')->toArray();

        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerByNameArr + $buyerByIdArr;

        $targetArr = CrmOpportunityToMember::join('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                ->join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , 'crm_opportunity.void_remarks', 'crm_opportunity.product_data'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                ->where('crm_opportunity.status', '4');

        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $targetArr = $targetArr->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        }
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

        $targetArr = $targetArr->orderBy('crm_opportunity.updated_at', 'desc');
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        //Opportunity has Activity Log
        $hasActivityLog = CrmActivityLog::join('crm_opportunity', 'crm_opportunity.id', '=', 'crm_activity_log.opportunity_id')
                        ->where('crm_opportunity.status', '4')->pluck('crm_activity_log.opportunity_id')->toArray();


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/crmVoidOpportunity?page=' . $page);
        }
        return view('crmVoidOpportunity.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'hasActivityLog', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'assignedPersonList'
                                , 'brandArr', 'productArr', 'memberList'));
    }

    public function filter(Request $request) {
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&product=' . $request->product . '&brand=' . $request->brand
                . '&assigned_to=' . $request->assigned_to . '&update_from_date=' . $request->update_from_date
                . '&update_to_date=' . $request->update_to_date;
        return Redirect::to('crmVoidOpportunity?' . $url);
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'crmVoidOpportunity.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

//************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'crmVoidOpportunity.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

//************************* END :: Activity Log ***********************//
}
