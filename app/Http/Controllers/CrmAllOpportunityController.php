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
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;

class CrmAllOpportunityController extends Controller {

    private $controller = 'CrmAllOpportunity';

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

        $targetArr = CrmOpportunity::leftJoin('crm_opportunity_to_member', 'crm_opportunity_to_member.opportunity_id', 'crm_opportunity.id')
                ->join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator")
                , 'crm_opportunity.status', 'crm_opportunity.revoked_status', 'crm_opportunity.last_activity_status'
                , 'crm_opportunity.approval_status', 'crm_opportunity.dispatch_status', 'crm_opportunity.product_data');



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
                        ->pluck('crm_activity_log.opportunity_id')->toArray();


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/crmAllOpportunity?page=' . $page);
        }

        $crmLeader = User::where('id', Auth::user()->id)->where('status', '1')
                        ->where('allowed_for_crm', '1')->where('for_crm_leader', '1')->first();

        return view('crmAllOpportunity.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'employeeList', 'crmLeader', 'activityStatusList'
                                , 'statusList', 'opportunityCountArr', 'hasActivityLog', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'assignedPersonList'
                                , 'brandArr', 'productArr', 'memberList'));
    }

    public function filter(Request $request) {
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&created_by=' . $request->created_by . '&status=' . $request->status . '&product=' . $request->product
                . '&brand=' . $request->brand . '&assigned_to=' . $request->assigned_to
                . '&update_from_date=' . $request->update_from_date . '&update_to_date=' . $request->update_to_date;
        return Redirect::to('crmAllOpportunity?' . $url);
    }

    public function newContactRow(Request $request) {
        $view = view('crmAllOpportunity.newContactRow')->render();
        return response()->json(['html' => $view]);
    }

    public function newProductRow(Request $request) {
        $view = view('crmAllOpportunity.newProductRow')->render();
        return response()->json(['html' => $view]);
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'crmAllOpportunity.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    //******************* Start :: Opportunity Assignment **********************
    public function getOpportunityToMemberToRelate(Request $request) {
        $loadView = 'crmAllOpportunity.showAssignOpportunity';
        return Common::getOpportunityToMemberToRelate($request, $loadView);
    }

    public function relateOpportunityToMember(Request $request) {
        return Common::relateOpportunityToMember($request);
    }

    //******************* End :: Opportunity Assignment ************************
    //******************* Start :: Opportunity Reassignment **********************
    public function getOpportunityReassigned(Request $request) {
        $member = CrmOpportunityToMember::select('member_id')->where('opportunity_id', $request->opportunity_id)->first();

        $memberId = !empty($member->member_id) ? $member->member_id : 0;
        //get employee list
        $memberArr = ['0' => __('label.SELECT_MEMBER_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, ' - ', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')->where('users.status', '1')
                        ->where('users.id', '<>', $memberId)->where('users.allowed_for_crm', '1')
                        ->pluck('name', 'users.id')->toArray();

        //for opportunity details
        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
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
        $countryList = Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $view = view('crmAllOpportunity.showReassignOpportunity', compact('request', 'target', 'contactArr', 'productArr', 'memberArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public function setOpportunityReassigned(Request $request) {
//        echo '<pre>';print_r($request->all());exit;
        //validation
        $rules = [
            'member_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $opportunityToMember = [
            'member_id' => $request->member_id,
            'opportunity_id' => $request->opportunity_id,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        //delete from opportunity_to_member before insert
        CrmOpportunityToMember::where('opportunity_id', $request->opportunity_id)->delete();
        DB::beginTransaction();
        try {
            if (CrmOpportunityToMember::insert($opportunityToMember)) {
                CrmOpportunity::where('id', $request->opportunity_id)->update(['status' => '1']);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.OPPORTUNITY_HAS_RELATED_TO_THIS_MEMBER_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_RELATE_OPPORTUNITY_TO_MEMBER')], 401);
        }
    }

    //******************* End :: Opportunity Reassignment ************************
    //revoke assignment
    public function revoke(Request $request) {
        $revokation = [
            'revoked_status' => '1',
            'revoked_by' => Auth::user()->id,
            'revoked_at' => date('Y-m-d H:i:s'),
        ];
        DB::beginTransaction();
        try {
            if (CrmOpportunityToMember::where('opportunity_id', $request->opportunity_id)->delete()) {
                CrmOpportunity::where('id', $request->opportunity_id)->update($revokation);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.ASSIGNMENT_HAS_BEEN_REVOKED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_REVOKE_ASSIGNMENT')], 401);
        }
    }

    //************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'crmAllOpportunity.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

}
