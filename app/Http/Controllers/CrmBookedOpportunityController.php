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
use App\Lead;
use App\ContactDesignation;
use App\InquiryDetails;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;

class CrmBookedOpportunityController extends Controller {

    private $controller = 'CrmBookedOpportunity';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::pluck('name', 'id')->toArray();
        $statusList = ['' => __('label.SELECT_STATUS_OPT')] + ['0' => __('label.PENDING'), '1' => __('label.APPROVED'), '2' => __('label.DENIED'), '3' => __('label.DISPATCHED')];

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
                        , 'crm_opportunity.approval_status', 'crm_opportunity.dispatch_status', 'crm_opportunity.deny_remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"), 'crm_opportunity.product_data')
                ->where('crm_opportunity.status', '2');

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

        if (isset($request->status)) {
            if ($request->status == '0') {
                $targetArr = $targetArr->where('crm_opportunity.dispatch_status', '0');
            } else if ($request->status == '1') {
                $targetArr = $targetArr->where('crm_opportunity.approval_status', '1');
            } else if ($request->status == '2') {
                $targetArr = $targetArr->where('crm_opportunity.approval_status', '2');
            } else if ($request->status == '3') {
                $targetArr = $targetArr->where('crm_opportunity.dispatch_status', '1')
                        ->where('crm_opportunity.approval_status', '0');
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

        //Opportunity has Activity Log
        $hasActivityLog = CrmActivityLog::join('crm_opportunity', 'crm_opportunity.id', '=', 'crm_activity_log.opportunity_id')
                        ->where('crm_opportunity.status', '2')->pluck('crm_activity_log.opportunity_id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/crmBookedOpportunity?page=' . $page);
        }
        return view('crmBookedOpportunity.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'statusList', 'hasActivityLog', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'assignedPersonList'
                                , 'brandArr', 'productArr', 'memberList'));
    }

    public function filter(Request $request) {
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&status=' . $request->status . '&product=' . $request->product
                . '&brand=' . $request->brand . '&assigned_to=' . $request->assigned_to
                . '&update_from_date=' . $request->update_from_date . '&update_to_date=' . $request->update_to_date;
        return Redirect::to('crmBookedOpportunity?' . $url);
    }

    public function doDispatch(Request $request) {
        $opportunityInfo = CrmOpportunity::find($request->opportunity_id);

        $buyerList = Buyer::orderBy('name', 'asc')->pluck('id', 'name')->toArray();
        $productList = Product::orderBy('name', 'asc')->pluck('id', 'name')->toArray();
        $brandList = Brand::orderBy('name', 'asc')->pluck('id', 'name')->toArray();
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        //buyer_contact_person

        $productArr = !empty($opportunityInfo->product_data) ? json_decode($opportunityInfo->product_data, true) : [];

        $productTempArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $pKey => $pInfo) {
                if ($pInfo['brand_has_id'] == '0') {
                    if (array_key_exists($pInfo['brand'], $brandList)) {
                        $pInfo['brand'] = $brandList[$pInfo['brand']];
                    } else {
                        $pInfo['brand'] = 0;
                    }
                }
                if ($pInfo['product_has_id'] == '0') {
                    if (array_key_exists($pInfo['product'], $productList)) {
                        $pInfo['product'] = $productList[$pInfo['product']];
                    } else {
                        $pInfo['product'] = 0;
                    }
                }
                if (!empty($pInfo['product']) && !empty($pInfo['brand'])) {
                    if (!empty($pInfo['final'])) {
                        $productTempArr[$pKey] = $pInfo;
                    }
                }
            }
        }
        $productArr = $productTempArr;

        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $contactPersonArr = !empty($opportunityInfo->buyer_contact_person) ? json_decode($opportunityInfo->buyer_contact_person, true) : [];

        $buyer_contact_person = $buyer_contact_person_identifier = '';
        if (!empty($contactPersonArr)) {
            foreach ($contactPersonArr as $key => $item) {
                $designation = !empty($designationArr[$item['designation']]) ? '(' . $designationArr[$item['designation']] . ')' : '';
                $buyer_contact_person = $item['name'] . $designation;
                $buyer_contact_person_identifier = $key;
            }
        }
        if ($opportunityInfo->buyer_has_id == '0') {
            if (array_key_exists($opportunityInfo->buyer, $buyerList)) {
                $opportunityInfo->buyer = $buyerList[$opportunityInfo->buyer];
            } else {
                $opportunityInfo->buyer = 0;
            }
        }

        if (empty($opportunityInfo->buyer)) {
            $errorMsg = __('label.BUYER_INPUT_IS_INVALID');
            return Response::json(array('success' => false, 'message' => $errorMsg), 401);
        }
        if (empty($productArr)) {
            $errorMsg = __('label.INQUIRY_COULD_NOT_BE_GENERATED_WITHOUT_ANY_FINAL_PRODUCT');
            return Response::json(array('success' => false, 'message' => $errorMsg), 401);
        }
//endof buyer_contact_person
//                echo '<pre>';
//        print_r($productArr);
//        exit;
        DB::beginTransaction();
        try {
            if (!empty($productArr)) {
                foreach ($productArr as $key => $productInfo) {
                    if ($productInfo['brand_has_id'] == '0') {
                        if (array_key_exists($productInfo['brand'], $brandList)) {
                            $productInfo['brand'] = $brandList[$productInfo['brand']];
                        } else {
                            $productInfo['brand'] = 0;
                        }
                    }
                    if ($productInfo['product_has_id'] == '0') {
                        if (array_key_exists($productInfo['product'], $productList)) {
                            $productInfo['product'] = $productList[$productInfo['product']];
                        } else {
                            $productInfo['product'] = 0;
                        }
                    }
                    if (!empty($opportunityInfo->buyer)) {
                        if (!empty($productInfo['product']) && !empty($productInfo['brand'])) {


                            $creationDate = Helper::dateFormatConvert(date('d F Y'));

                            $target = new Lead;
                            $target->opportunity_id = $request->opportunity_id;
                            $target->buyer_id = $opportunityInfo->buyer;
                            $target->buyer_contact_person = $buyer_contact_person;
                            $target->contact_person_identifier = $buyer_contact_person_identifier;
                            $target->salespersons_id = $opportunityInfo->created_by;
                            $target->creation_date = $creationDate;
                            $target->shipment_address_status = 1;
                            $target->head_office_address = $opportunityInfo->address ?? null;
                            $target->factory_id = $request->null;
                            $target->status = '1';
                            $target->add_first_followup = '0';
                            $target->followup_remarks = null;
                            if ($target->save()) {
                                $inquery = new InquiryDetails;
                                $inquery->inquiry_id = $target->id;
                                $inquery->product_id = !empty($productInfo['product']) ? $productInfo['product'] : 0;
                                $inquery->brand_id = !empty($productInfo['brand']) ? $productInfo['brand'] : 0;
                                $inquery->grade_id = !empty($productInfo['grade']) ? $productInfo['grade'] : NULL;
                                $inquery->gsm = !empty($productInfo['gsm']) ? $productInfo['gsm'] : '';
                                $inquery->quantity = !empty($productInfo['quantity']) ? $productInfo['quantity'] : 0.00;
                                $inquery->unit_price = !empty($productInfo['unit_price']) ? $productInfo['unit_price'] : 0.00;
                                $inquery->total_price = !empty($productInfo['total_price']) ? $productInfo['total_price'] : 0.00;
                                $inquery->save();
                            }
                            CrmOpportunity::where('id', $request->opportunity_id)
                                    ->update(['dispatch_status' => '1', 'approval_status' => '0'
                                        , 'dispatched_at' => date('Y-m-d H:i:s'), 'dispatched_by' => Auth::user()->id]);
                        }
                    }
                }
            }
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_DISPATCHED_SUCCESSFULLY')), 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.OPPORTUNITY_COULD_NOT_BE_DISPATCHED')), 401);
        }
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'crmBookedOpportunity.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    //************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'crmBookedOpportunity.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

    //************************* END :: Activity Log ***********************//
}
