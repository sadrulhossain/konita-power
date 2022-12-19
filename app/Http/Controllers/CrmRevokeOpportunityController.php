<?php

namespace App\Http\Controllers;

use Validator;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmOpportunityToMember;
use App\User;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use App\CrmActivityStatus;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;

class CrmRevokeOpportunityController extends Controller {

    private $controller = 'CrmRevokeOpportunity';

    public function index(Request $request) {

        $activityStatusList = CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();

        $opportunitiesArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0')
                ->get();

        $opportunityRelatedToMemberArr = CrmOpportunity::join('crm_opportunity_to_member', 'crm_opportunity_to_member.opportunity_id', 'crm_opportunity.id')
                ->join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"), 'crm_opportunity.status'
                        , 'crm_opportunity.revoked_status', 'crm_opportunity.last_activity_status', 'crm_opportunity.approval_status'
                        , 'crm_opportunity.dispatch_status')
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0')
                ->get();


        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        return view('crmRevokeOpportunity.index')->with(compact('request', 'opportunityRelatedToMemberArr'
                                , 'opportunitiesArr', 'buyerList', 'activityStatusList', 'assignedPersonList'));
    }

    public function revoke(Request $request) {
        //echo '<pre>';print_r($request->all());exit;

        $messages = $rules = [];
        if (empty($request->opportunity)) {
            $rules['opportunity'] = 'required';
            $messages['opportunity.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_OPPORTUNITY');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $revokeStatus = CrmOpportunityToMember::whereIn('opportunity_id', $request->opportunity)->delete();

        DB::beginTransaction();
        try {
            if ($revokeStatus) {
                CrmOpportunity::whereIn('id', $request->opportunity)->update(['revoked_status' => '1'
                    , 'revoked_by' => Auth::user()->id
                    , 'revoked_at' => date('Y-m-d H:i:s')]);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.OPPORTUNITY_HAS_REVOKED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_REVOKED_OPPORTUNITY')], 401);
        }
    }

}
