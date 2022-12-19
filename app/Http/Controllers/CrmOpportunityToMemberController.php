<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\CrmOpportunity;
use App\CrmOpportunityToMember;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class CrmOpportunityToMemberController extends Controller {

    public function index(Request $request) {
        $memberArr = ['0' => __('label.SELECT_MEMBER_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, ' - ', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')
                        ->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_crm', '1')
                        ->pluck('name', 'users.id')->toArray();
        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $opportunitiesArr = $opportunityRelatedToMember = [];
        if (!empty($request->get('member_id'))) {
            $opportunitiesArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                            ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                            ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                                    , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                                    , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                            ->where('crm_opportunity.status', '0')->orWhere('crm_opportunity.revoked_status', '1')->get();

            $relatedOpportunityArr = CrmOpportunityToMember::select('opportunity_id')
                            ->where('member_id', $request->member_id)->get();


            if (!$relatedOpportunityArr->isEmpty()) {
                foreach ($relatedOpportunityArr as $relatedOpportunity) {
                    $opportunityRelatedToMember[$relatedOpportunity->opportunity_id] = $relatedOpportunity->opportunity_id;
                }
            }
        }

        return view('crmOpporunityToMember.index')->with(compact('request', 'memberArr', 'opportunitiesArr', 'opportunityRelatedToMember'
                                , 'buyerList'));
    }

    public function getOpportunityToRelate(Request $request) {
        $opportunitiesArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                                , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.status', '0')->orWhere('crm_opportunity.revoked_status', '1')->get();


        $relatedOpportunityArr = CrmOpportunityToMember::select('opportunity_id')
                        ->where('member_id', $request->member_id)->get();


        $opportunityRelatedToMember = [];
        if (!$relatedOpportunityArr->isEmpty()) {
            foreach ($relatedOpportunityArr as $relatedOpportunity) {
                $opportunityRelatedToMember[$relatedOpportunity->opportunity_id] = $relatedOpportunity->opportunity_id;
            }
        }

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();


        $view = view('crmOpporunityToMember.showOpportunities', compact('opportunitiesArr', 'opportunityRelatedToMember'
                        , 'buyerList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedOpportunities(Request $request) {

        // Set Name of Selected Sales Person
        $member = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS name"), 'id')
                        ->where('id', $request->member_id)->first();

        //get related factories
        $relatedOpportunityArr = CrmOpportunityToMember::select('crm_opportunity_to_member.*')
                        ->where('member_id', $request->member_id)->get();

        $opportunityRelatedToMember = [];
        if (!$relatedOpportunityArr->isEmpty()) {
            foreach ($relatedOpportunityArr as $relatedOpportunity) {
                $opportunityRelatedToMember[$relatedOpportunity->opportunity_id] = $relatedOpportunity->opportunity_id;
            }
        }


        $opportunityInfoArr = [];
        if (isset($opportunityRelatedToMember)) {
            $opportunityInfoArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                            ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                            ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_opportunity.buyer_contact_person', 'crm_source.name as source'
                                    , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                                    , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                            ->whereIn('crm_opportunity.id', $opportunityRelatedToMember)
                            ->get()->toArray();
        }

        $contactArr = [];
        if (!empty($opportunityInfoArr)) {
            foreach ($opportunityInfoArr as $opportunityInfo) {
                $contact = json_decode($opportunityInfo['buyer_contact_person'], true);
                $contactArr[$opportunityInfo['id']] = $contact;
            }
        }


        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();



        $view = view('crmOpporunityToMember.showRelatedOpportunities', compact('member', 'request', 'opportunityInfoArr', 'contactArr'
                        , 'buyerList'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateOpportunityToMember(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        //validation
        $rules = [
            'member_id' => 'required|not_in:0',
            'opportunity' => 'required',
        ];

        $messages = [];
        $messages['opportunity.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_OPPORTUNITY_TO_RELATE_THIS_MEMBER');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $opportunityToMember = [];
        $i = 0;
        if (!empty($request->opportunity)) {
            foreach ($request->opportunity as $opportunityId) {
                //data entry to sales person to product table
                $opportunityToMember[$i]['member_id'] = $request->member_id;
                $opportunityToMember[$i]['opportunity_id'] = $opportunityId;
                $opportunityToMember[$i]['created_by'] = Auth::user()->id;
                $opportunityToMember[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete from opportunity_to_member before insert
        CrmOpportunityToMember::where('member_id', $request->member_id)->whereIn('opportunity_id', $request->opportunity)->delete();

        DB::beginTransaction();
        try {
            if (CrmOpportunityToMember::insert($opportunityToMember)) {
                CrmOpportunity::whereIn('id', $request->opportunity)->update(['status' => '1', 'revoked_status' => '0']);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.OPPORTUNITY_HAS_RELATED_TO_THIS_MEMBER_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_RELATE_OPPORTUNITY_TO_MEMBER')], 401);
        }
    }

}
