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

class CrmReassignmentOpportunityController extends Controller {

    private $controller = 'CrmReassignmentOpportunity';

    public function index(Request $request) {
        $memberArr = ['0' => __('label.SELECT_MEMBER_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, ' - ', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')
                        ->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_crm', '1')
                        ->pluck('name', 'users.id')->toArray();
        return view('crmReassignmentOpportunity.index')->with(compact('request', 'memberArr'));
    }

    public function getOpportunityToRelate(Request $request) {

        $activityStatusList = CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();

        $opportunitiesArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0')
                ->get();

        $relatedOpportunityArr = CrmOpportunityToMember::select('opportunity_id')
                        ->where('member_id', $request->member_id)->get();

        $opportunityRelatedToMemberArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"), 'crm_opportunity.status'
                        , 'crm_opportunity.revoked_status', 'crm_opportunity.last_activity_status', 'crm_opportunity.approval_status'
                        , 'crm_opportunity.dispatch_status')
                ->whereIn('crm_opportunity.id', $relatedOpportunityArr)
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0')
                ->get();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('crmReassignmentOpportunity.showOpportunities', compact('opportunitiesArr', 'opportunityRelatedToMemberArr'
                        , 'buyerList', 'activityStatusList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getMemberToTransfer(Request $request) {

        $rules = [
            'member_id' => 'required|not_in:0',
        ];

        $messages = [];
        if (empty($request->opportunity)) {
            $rules['opportunity'] = 'required';
            $messages['opportunity.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_OPPORTUNITY');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $opportunityRelatedToMemberArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                ->whereIn('crm_opportunity.id', $request->opportunity)
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0')
                ->get();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();



        $memberList = ['0' => __('label.SELECT_MEMBER_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, ' - ', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')
                        ->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_crm', '1')
                        ->where('users.id', '<>', $request->member_id)
                        ->pluck('name', 'users.id')->toArray();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('crmReassignmentOpportunity.showTransferOpportunityrToMember', compact('request', 'opportunityRelatedToMemberArr', 'memberList'
                        , 'buyerList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateMemberToOpportunity(Request $request) {

        $rules = [
            'new_member_id' => 'required|not_in:0',
            'member_id' => 'required|not_in:0',
        ];
        $request->opportunity = json_decode($request->opportunity, true);
        $messages = [];
        if (empty($request->opportunity)) {
            $rules['opportunity'] = 'required';
            $messages['opportunity.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BUYER');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $memberToOpportunity = [];
        $i = 0;
        $memberToOpportunityList = CrmOpportunityToMember::where('member_id', $request->new_member_id)
                        ->pluck('opportunity_id')->toArray();

        if (!empty($request->opportunity)) {
            foreach ($request->opportunity as $opportunityId) {
                if (!in_array($opportunityId, $memberToOpportunityList)) {
                    //data entry to member to opportunity table
                    $memberToOpportunity[$i]['member_id'] = $request->new_member_id;
                    $memberToOpportunity[$i]['opportunity_id'] = $opportunityId;
                    $memberToOpportunity[$i]['created_by'] = Auth::user()->id;
                    $memberToOpportunity[$i]['created_at'] = date('Y-m-d H:i:s');
                    $i++;
                }
            }
        }

        CrmOpportunityToMember::whereIn('opportunity_id', $request->opportunity)->delete();
        if (CrmOpportunityToMember::insert($memberToOpportunity)) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.OPPORTUNITY_REASSIGNED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_REASSIGN')], 401);
        }
    }

}
