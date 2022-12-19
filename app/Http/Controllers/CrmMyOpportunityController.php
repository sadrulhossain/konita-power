<?php

namespace App\Http\Controllers;

use Validator;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmActivityStatus;
use App\CrmQuotation;
use App\CrmOpportunityToMember;
use App\CompanyInformation;
use App\User;
use App\PaymentTerm;
use App\ShippingTerm;
use App\PreCarrier;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use App\Country;
use App\CrmQuotationTerms;
use App\ContactDesignation;
use App\BuyerToProduct;
use App\SalesPersonToBuyer;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\ProductToGrade;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Response;
use Common;
use Illuminate\Http\Request;
use PDF;

class CrmMyOpportunityController extends Controller {

    private $controller = 'CrmMyOpportunity';

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

        $statusList = Common::getOpportunityStatusList(1);

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
        //if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
        $buyerByNameArr = $buyerByNameArr->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        //}
        $buyerByNameArr = $buyerByNameArr->pluck('buyer', 'buyer')->toArray();

        $buyerByIdArr = CrmOpportunityToMember::join('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                        ->join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->orderBy('buyer.name', 'asc');
        //if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
        $buyerByIdArr = $buyerByIdArr->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        //}
        $buyerByIdArr = $buyerByIdArr->pluck('buyer.name', 'crm_opportunity.buyer')->toArray();

        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerByNameArr + $buyerByIdArr;

        $targetArr = CrmOpportunityToMember::join('crm_opportunity', 'crm_opportunity.id', 'crm_opportunity_to_member.opportunity_id')
                ->join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.updated_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator")
                        , 'crm_opportunity.status', 'crm_opportunity.revoked_status', 'crm_opportunity.last_activity_status'
                        , 'crm_opportunity.approval_status', 'crm_opportunity.dispatch_status', 'crm_opportunity.product_data')
                ->where('crm_opportunity_to_member.member_id', Auth::user()->id)
                ->where('crm_opportunity.status', '1')
                ->where('crm_opportunity.revoked_status', '0');

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

        if (!empty($request->status)) {
            $lastActivityStatus = $request->status - 1;
            $targetArr = $targetArr->where('crm_opportunity.last_activity_status', $lastActivityStatus);
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


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/crmMyOpportunity?page=' . $page);
        }

        $crmLeader = User::where('id', Auth::user()->id)->where('status', '1')
                        ->where('allowed_for_crm', '1')->where('for_crm_leader', '1')->first();


        return view('crmMyOpportunity.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'employeeList', 'crmLeader', 'activityStatusList'
                                , 'statusList', 'opportunityCountArr', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'assignedPersonList'
                                , 'productArr', 'brandArr', 'memberList'));
    }

    public function filter(Request $request) {
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . '&created_by=' . $request->created_by . '&status=' . $request->status
                . '&product=' . $request->product . '&brand=' . $request->brand
                . '&update_from_date=' . $request->update_from_date . '&update_to_date=' . $request->update_to_date;
        return Redirect::to('crmMyOpportunity?' . $url);
    }

    public function edit(Request $request, $id) {
        $target = CrmOpportunity::find($id);

        $generator = User::where('id', $target->created_by)->select('group_id')->first();

        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $productArr = !empty($target->product_data) ? json_decode($target->product_data, true) : [];

        //passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::where('status', '1')->pluck('name', 'id')->toArray();
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', $target->created_by)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc');
        if ($generator->group_id != '1') {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;
        //endif buyer list
        //Product List
        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $target->buyer)->get();
        $productIdArr = $productIdArr2 = [];
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', $target->created_by)->get();

        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }

        $productArr2 = Product::whereIn('id', $productIdArr);
        if ($generator->group_id != 1) {
            $productArr2 = $productArr2->whereIn('id', $productIdArr2);
        }
        $productArr2 = $productArr2->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr2;
//Endof product list
        //brand List
        $pIdArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $pKey => $pInfo) {
                $pIdArr[$pInfo['product']] = $pInfo['product'];
            }
        }

        $brandArr = ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id');

        $buyer = $target->buyer;
        $createdBy = $target->created_by;
        if ($generator->group_id != 1) {
            $brandArr = $brandArr->join('sales_person_to_product', function($join) use($createdBy) {
                $join->on('sales_person_to_product.product_id', '=', 'product_to_brand.product_id')
                        ->on('sales_person_to_product.brand_id', '=', 'product_to_brand.brand_id')
                        ->where('sales_person_to_product.sales_person_id', $createdBy);
            });
        }
        $brandArr = $brandArr->join('buyer_to_product', function($join) use($buyer) {
                    $join->on('buyer_to_product.product_id', '=', 'product_to_brand.product_id')
                    ->on('buyer_to_product.brand_id', '=', 'product_to_brand.brand_id')
                    ->where('buyer_to_product.buyer_id', $buyer);
                })
                ->select('product_to_brand.brand_id', 'brand.name', 'product_to_brand.product_id')
                ->whereIn('product_to_brand.product_id', $pIdArr)
                ->get();

        $brandIdArr = $brandArr3 = [];
        if (!$brandArr->isEmpty()) {
            foreach ($brandArr as $brand) {
                $brandArr3[$brand->product_id][$brand->brand_id] = $brand->name;
                $brandIdArr[$brand->brand_id] = $brand->brand_id;
            }
        }
        $brandList = $brandArr3;

        //endof brand list
        // grade lsit

        $gradeArr = ProductToGrade::join('grade', 'grade.id', '=', 'product_to_grade.grade_id')
                ->whereIn('product_to_grade.product_id', $pIdArr)
                ->whereIn('product_to_grade.brand_id', $brandIdArr)
                ->where('grade.status', '1')
                ->select('grade.name', 'grade.id', 'product_to_grade.product_id', 'product_to_grade.brand_id')
                ->get();
        $gradeArr2 = [];
        if (!$gradeArr->isEmpty()) {
            foreach ($gradeArr as $grade) {
                $gradeArr2[$grade->product_id][$grade->brand_id][$grade->id] = $grade->name;
            }
        }
        $gradeList = $gradeArr2;
        //endof grade list

        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        //buyer contact person list

        $designationArr = ContactDesignation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $targetBuyer = Buyer::find($target->buyer);
        $buyerContactPersonArr = [];
        if (!empty($targetBuyer)) {
            $contactPersonArr = json_decode($targetBuyer->contact_person_data, true);
            if (!empty($contactPersonArr)) {
                foreach ($contactPersonArr as $key => $item) {
                    $designation = !empty($designationArr[$item['designation_id']]) ? ' (' . $designationArr[$item['designation_id']] . ')' : '';
                    $buyerContactPersonArr[$key] = $item['name'] . ' ' . ' ' . $designation;
                }
            }
        }

        $selectedContPersonId = null;
        if (!empty($target)) {

            $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
            if (!empty($contactArr)) {
                foreach ($contactArr as $selectedKey => $cInfo) {
                    $selectedContPersonId = $selectedKey ?? null;
                }
            }
        }
        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT')) + $buyerContactPersonArr;
        //endof buyer contact person list

        return view('crmMyOpportunity.edit')->with(compact('target', 'qpArr', 'sourceList'
                                , 'contactArr', 'productArr', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'countryList', 'buyerContPersonList'
                                , 'selectedContPersonId', 'generator'));
    }

    public function update(Request $request) {
        $target = CrmOpportunity::find($request->id);

        //begin back same page after update
        $page = !empty($request->page) ? $request->page : '';
//        $qpArr = $request->all();
//        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = $message = [];
        $rules = [
            'address' => 'required',
            'buyer_contact_person' => 'required|not_in:0',
            'remarks' => 'required',
        ];

        if ($request->buyer_has_id == '0') {
            $rules['buyer_name'] = 'required';
        } elseif ($request->buyer_has_id == '1') {
            $rules['buyer_id'] = 'required|not_in:0';
        }

        if (!empty($request->product)) {
            $row = 1;
            foreach ($request->product as $pKey => $pInfo) {
                $rules['product.' . $pKey . '.product_id'] = 'required|not_in:0';
                $rules['product.' . $pKey . '.brand_id'] = 'required|not_in:0';
                $message['product.' . $pKey . '.product_id' . '.not_in'] = __('label.PRODUCT_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.brand_id' . '.not_in'] = __('label.BRAND_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if ($request->buyer_has_id == '0') {
            $buyer = $request->buyer_name;
        } elseif ($request->buyer_has_id == '1') {
            $buyer = $request->buyer_id;
        }

        $contactArr = $productArr = [];
        $designationArr = ContactDesignation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $buyerInfo = Buyer::find($buyer);
        if (!empty($buyerInfo)) {
            $contactPersonArr = json_decode($buyerInfo->contact_person_data, true);
            if (!empty($contactPersonArr)) {
                foreach ($contactPersonArr as $key => $item) {
                    if ($key == $request->buyer_contact_person) {
                        $designation = !empty($designationArr[$item['designation_id']]) ? $designationArr[$item['designation_id']] : '';
                        $contactArr[$key]['name'] = $item['name'] ?? '';
                        $contactArr[$key]['designation'] = $designation;
                        $contactArr[$key]['email'] = $item['email'] ?? '';
                        if (is_array($item['phone']) && !empty($item['phone'])) {
                            foreach ($item['phone'] as $pKey => $phoneNumber) {
                                $contactArr[$key]['phone'] = $phoneNumber ?? '';
                            }
                        } else {
                            $contactArr[$key]['phone'] = $item['phone'] ?? '';
                        }
                    }
                }
            }
        }


//        if (!empty($request->contact)) {
//            foreach ($request->contact as $cKey => $cInfo) {
//                if (count(array_filter($cInfo)) != 0) {
//                    $contactArr[$cKey]['name'] = $cInfo['name'] ?? '';
//                    $contactArr[$cKey]['designation'] = $cInfo['designation'] ?? '';
//                    $contactArr[$cKey]['email'] = $cInfo['email'] ?? '';
//                    $contactArr[$cKey]['phone'] = $cInfo['phone'] ?? '';
//                    $contactArr[$cKey]['primary'] = !empty($cInfo['primary']) ? $cInfo['primary'] : '0';
//                }
//            }
//        }

        if (!empty($request->product)) {
            foreach ($request->product as $pKey => $pInfo) {
                if (count(array_filter($pInfo)) != 0) {
                    $product = $pInfo['product_has_id'] == '1' ? ($pInfo['product_id'] ?? '') : ($pInfo['product_name'] ?? '');
                    $brand = $pInfo['brand_has_id'] == '1' ? ($pInfo['brand_id'] ?? '') : ($pInfo['brand_name'] ?? '');
                    $grade = $pInfo['grade_has_id'] == '1' ? ($pInfo['grade_id'] ?? '') : ($pInfo['grade_name'] ?? '');

                    $productArr[$pKey]['product'] = $product;
                    $productArr[$pKey]['product_has_id'] = $pInfo['product_has_id'] ?? '0';
                    $productArr[$pKey]['brand'] = $brand;
                    $productArr[$pKey]['brand_has_id'] = $pInfo['brand_has_id'] ?? '0';
                    $productArr[$pKey]['grade'] = $grade;
                    $productArr[$pKey]['grade_has_id'] = $pInfo['grade_has_id'] ?? '0';
                    $productArr[$pKey]['origin'] = $pInfo['origin'] ?? '';
                    $productArr[$pKey]['gsm'] = $pInfo['gsm'] ?? '';
                    $productArr[$pKey]['quantity'] = $pInfo['quantity'] ?? '0.00';
                    $productArr[$pKey]['unit'] = $pInfo['unit'] ?? '';
                    $productArr[$pKey]['unit_price'] = $pInfo['unit_price'] ?? '0.00';
                    $productArr[$pKey]['total_price'] = $pInfo['total_price'] ?? '0.00';
                }
            }
        }

        $target->buyer = $buyer;
        $target->buyer_has_id = $request->buyer_has_id;
        $target->address = $request->address;
        $target->source_id = $request->source_id;
        $target->remarks = $request->remarks;
        $target->buyer_contact_person = json_encode($contactArr);
        $target->product_data = json_encode($productArr);

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_UPDATED_SUCCESSFULLY'), 'page' => $page), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.OPPORTUNITY_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function newContactRow(Request $request) {
        $view = view('crmMyOpportunity.newContactRow')->render();
        return response()->json(['html' => $view]);
    }

    public function getBuyerContPerson(Request $request) {

        $designationArr = ContactDesignation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $target = Buyer::find($request->buyer_id);
        $buyerContactPersonArr = [];
        if (!empty($target)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
            if (!empty($contactPersonArr)) {
                foreach ($contactPersonArr as $key => $item) {
                    $designation = !empty($designationArr[$item['designation_id']]) ? ' (' . $designationArr[$item['designation_id']] . ')' : '';
                    $buyerContactPersonArr[$key] = $item['name'] . ' ' . ' ' . $designation;
                }
            }
        }
        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT')) + $buyerContactPersonArr;
        //Product List
        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $request->buyer_id)->get();
        $productIdArr = $productIdArr2 = [];
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', $request->sales_person_id)->get();

        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }

        $productArr = Product::whereIn('id', $productIdArr);
        if ($request->sales_person_group_id != 1) {
            $productArr = $productArr->whereIn('id', $productIdArr2);
        }
        $productArr = $productArr->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr;

//Endof product list

        $buyerHeadOfficeAddress = !empty($target->head_office_address) ? $target->head_office_address : null;

        $view = view('crmMyOpportunity.showContactPerson', compact('request', 'buyerContPersonList'))->render();
        $productView = view('crmMyOpportunity.getProduct', compact('request', 'productList'))->render();

        return response()->json(['html' => $view, 'buyerHeadOfficeAddress' => $buyerHeadOfficeAddress
                    , 'productView' => $productView]);
    }

    public function getProductUnit(Request $request) {
        $productInfo = Product::join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->select('measure_unit.name')
                ->where('product.id', $request->product_id)
                ->first();

        //brand List
        $brandArr1 = SalesPersonToProduct::where('sales_person_id', $request->sales_person_id)->where('product_id', $request->product_id)->pluck('brand_id')->toArray();
        $brandArr2 = BuyerToProduct::where('buyer_id', $request->buyer_id)->where('product_id', $request->product_id)->pluck('brand_id')->toArray();

        $brandArr = ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id')
                        ->select('product_to_brand.brand_id')->where('product_to_brand.product_id', $request->product_id);
        if ($request->sales_person_group_id != 1) {
            $brandArr = $brandArr->whereIn('product_to_brand.brand_id', $brandArr1);
        }
        $brandArr = $brandArr->whereIn('product_to_brand.brand_id', $brandArr2)->get();
        $brandIdArr = [];
        if (!$brandArr->isEmpty()) {
            foreach ($brandArr as $brand) {
                $brandIdArr[$brand->brand_id] = $brand->brand_id;
            }
        }

        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::orderBy('name', 'asc')
                        ->whereIn('id', $brandIdArr)->pluck('name', 'id')->toArray();
        //endof brand list

        $unit = !empty($productInfo->name) ? $productInfo->name : null;
        $html = view('crmMyOpportunity.getBrand', compact('request', 'brandList'))->render();

        return response()->json(['unit' => $unit, 'brand' => $html]);
    }

    public function getGradeOrigin(Request $request) {

        $gradeArr = ProductToGrade::join('grade', 'grade.id', '=', 'product_to_grade.grade_id')
                        ->where('product_to_grade.product_id', $request->product_id)
                        ->where('product_to_grade.brand_id', $request->brand_id)
                        ->where('grade.status', '1')
                        ->pluck('grade.name', 'grade.id')->toArray();
//echo '<pre>';
//        print_r($request->all());
//        exit;
        $gradeList = array('0' => __('label.SELECT_GRADE_OPT')) + $gradeArr;

        $originInfo = Brand::join('country', 'country.id', 'brand.origin')
                ->select('country.name', 'country.id')->where('brand.id', $request->brand_id)
                ->first();

        $originName = !empty($originInfo->name) ? $originInfo->name : '';
        $originId = !empty($originInfo->id) ? $originInfo->id : null;

        $view = view('crmMyOpportunity.getGrade', compact('request', 'gradeList'))->render();
        return response()->json(['html' => $view, 'originName' => $originName, 'originId' => $originId]);
    }

    public function newProductRow(Request $request) {

        $v4 = 'np' . uniqid();
        //Product List
        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $request->buyer_id)->get();
        $productIdArr = $productIdArr2 = [];
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', Auth::user()->id)->get();

        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }

        $productArr = Product::whereIn('id', $productIdArr);
        if (Auth::user()->group_id != 1) {
            $productArr = $productArr->whereIn('id', $productIdArr2);
        }
        $productArr = $productArr->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr;

//Endof product list
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')];
//        Brand::where('status', '1')
//                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')];
//        Grade::where('status', '1')
//                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $view = view('crmMyOpportunity.newProductRow', compact('productList', 'brandList', 'gradeList', 'countryList', 'v4'))->render();
        return response()->json(['html' => $view, 'v4' => $v4]);
    }

    public function newTermsRow(Request $request) {

        $v4 = $request->v_4;
        $id = $request->quotationId;

        //previous quotation info
        $quotationInfo = CrmQuotation::select('*')->where('opportunity_id', '$id')->first();
//        echo '<pre>';
//        print_r($quotationInfo);exit;

        $quotationNo = __('label.CRM') . strtoupper(substr(uniqid(), -3)) . date('YmdHis');
        $quotationNo = $quotationInfo->quotation_no ?? $quotationNo;



        $preCarrierList = ['0' => __('label.SELECT_PRE_CARRIER_OPT')] + PreCarrier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $shippingTermList = ['0' => __('label.SELECT_SHIPPING_TERMS_OPT')] + ShippingTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $paymentTermList = ['0' => __('label.SELECT_PAYMENT_TERMS_OPT')] + PaymentTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

//        echo '<pre>';
//        print_r($v4);exit;

        $view = view('crmMyOpportunity.newTermsRow', compact('preCarrierList', 'shippingTermList', 'paymentTermList', 'v4', 'quotationInfo'))->render();
        return response()->json(['html' => $view, 'v4' => $v4]);
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'crmMyOpportunity.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    //************************* Start :: cancel opportunity ******************************* *//
    public function opportunityCancellationModal(Request $request) {
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
        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $view = view('crmMyOpportunity.showOpportunityCancellation', compact('request', 'target', 'contactArr', 'productArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public function cancel(Request $request) {
        $target = CrmOpportunity::find($request->opportunity_id);

        //validation
        $rules = [
            'cancel_remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        $target->status = '3';
        $target->cancel_remarks = $request->cancel_remarks;
        $target->cancelled_at = date('Y-m-d H:i:s');
        $target->cancelled_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_CANCELLED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_COULD_NOT_BE_CANCELLED')), 401);
        }
    }

    //************************* End :: cancel opportunity ******************************** *//
    //************************* Start :: cancel opportunity ******************************* *//

    public function opportunityVoidModal(Request $request) {
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
        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $view = view('crmMyOpportunity.showOpportunityVoid', compact('request', 'target', 'contactArr', 'productArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public function void(Request $request) {
        $target = CrmOpportunity::find($request->opportunity_id);

        //validation
        $rules = [
            'void_remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        $target->status = '4';
        $target->void_remarks = $request->void_remarks;
        $target->void_at = date('Y-m-d H:i:s');
        $target->void_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.OPPORTUNITY_VOID_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_COULD_NOT_BE_VOID')), 401);
        }
    }

    //************************* End :: void opportunity *********************************//
    //************************* Start :: quotation **************************//
    //load quotation page
    public function quotation(Request $request, $id) {
        $qpArr = $request->all();

        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info

        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.id', $id)->first();
        

        $generator = User::where('id', $target->created_by)->select('group_id')->first();

        $attentionArr = [];
        if (!empty($target->buyer_contact_person)) {
            $contactPersonDataList = json_decode($target->buyer_contact_person, true);
            if (!empty($contactPersonDataList)) {
                foreach ($contactPersonDataList as $cKey => $cInfo) {
                    $attentionArr[$cKey] = $cInfo['name'];
                }
            }
        }

        $attentionList = [__('label.N_A') => __('label.SELECT_ATTENTION_OPT')] + $attentionArr;

        //auto generated quotation no.
        $quotationNo = __('label.CRM') . strtoupper(substr(uniqid(), -3)) . date('YmdHis');

//        echo '<pre>';
//        print_r($target->buyer);
//        exit;

        $subtotal = 0.00;
        $productDataList = [];
        if (!empty($target->product_data)) {
            $productDataList = json_decode($target->product_data, true);
            if (!empty($productDataList)) {
                foreach ($productDataList as $pKey => $pInfo) {
                    $subtotal += $pInfo['total_price'];
                }
            }
        }

        //Product List
        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $target->buyer)->get();
        $productIdArr = $productIdArr2 = [];
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', $target->created_by)->get();

        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }

        $productArr2 = Product::where('status', '1')->whereIn('id', $productIdArr);
        if ($generator->group_id != 1) {
            $productArr2 = $productArr2->whereIn('id', $productIdArr2);
        }
        $productArr2 = $productArr2->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr2;
//Endof product list
        //brand List
        $pIdArr = [];
        if (!empty($productDataList)) {
            foreach ($productDataList as $pKey => $pInfo) {
                $pIdArr[$pInfo['product']] = $pInfo['product'];
            }
        }

        $brandArr = ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id');

        $buyer = $target->buyer;
        $createdBy = $target->created_by;
        if ($generator->group_id != 1) {
            $brandArr = $brandArr->join('sales_person_to_product', function($join) use($createdBy) {
                $join->on('sales_person_to_product.product_id', '=', 'product_to_brand.product_id')
                        ->on('sales_person_to_product.brand_id', '=', 'product_to_brand.brand_id')
                        ->where('sales_person_to_product.sales_person_id', $createdBy);
            });
        }
        $brandArr = $brandArr->join('buyer_to_product', function($join) use($buyer) {
                    $join->on('buyer_to_product.product_id', '=', 'product_to_brand.product_id')
                    ->on('buyer_to_product.brand_id', '=', 'product_to_brand.brand_id')
                    ->where('buyer_to_product.buyer_id', $buyer);
                })
                ->select('product_to_brand.brand_id', 'brand.name', 'product_to_brand.product_id')
                ->whereIn('product_to_brand.product_id', $pIdArr)
                ->get();

        $brandIdArr = $brandArr3 = [];
        if (!$brandArr->isEmpty()) {
            foreach ($brandArr as $brand) {
                $brandArr3[$brand->product_id][$brand->brand_id] = $brand->name;
                $brandIdArr[$brand->brand_id] = $brand->brand_id;
            }
        }
        $brandList = $brandArr3;

        //endof brand list
        // grade lsit

        $gradeArr = ProductToGrade::join('grade', 'grade.id', '=', 'product_to_grade.grade_id')
                ->whereIn('product_to_grade.product_id', $pIdArr)
                ->whereIn('product_to_grade.brand_id', $brandIdArr)
                ->where('grade.status', '1')
                ->select('grade.name', 'grade.id', 'product_to_grade.product_id', 'product_to_grade.brand_id')
                ->get();
        $gradeArr2 = [];
        if (!$gradeArr->isEmpty()) {
            foreach ($gradeArr as $grade) {
                $gradeArr2[$grade->product_id][$grade->brand_id][$grade->id] = $grade->name;
            }
        }
        $gradeList = $gradeArr2;
        //endof grade list

        $responsibleAgent = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->join('designation', 'designation.id', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"), 'designation.title as designation'
                                , 'users.email', 'users.phone as contact_no')
                        ->where('crm_opportunity_to_member.opportunity_id', $id)->first();

        //previous quotation info
        $quotationInfo = CrmQuotation::select('*')->where('opportunity_id', $id)->first();
        $quotationTermArrList = CrmQuotationTerms::select('*')->where('opportunity_id', $id)->get();

        $quotationTermArr = [];
        if (!$quotationTermArrList->isEmpty()) {
            foreach ($quotationTermArrList as $qkey => $qtInfo) {
                $quotationTermArr[$qtInfo->product_key]['payment_term_id'] = $qtInfo->payment_term_id ?? '';
                $quotationTermArr[$qtInfo->product_key]['shipping_term_id'] = $qtInfo->shipping_term_id ?? '';
                $quotationTermArr[$qtInfo->product_key]['port_of_loading'] = $qtInfo->port_of_loading ?? '';
                $quotationTermArr[$qtInfo->product_key]['port_of_discharge'] = $qtInfo->port_of_discharge ?? '';
                $quotationTermArr[$qtInfo->product_key]['total_lead_time'] = $qtInfo->total_lead_time ?? '';
                $quotationTermArr[$qtInfo->product_key]['pre_carrier_id'] = $qtInfo->pre_carrier_id ?? '';
                $quotationTermArr[$qtInfo->product_key]['estimated_shipment_date'] = $qtInfo->estimated_shipment_date ?? '';
            }
        }

        $quotationNo = $quotationInfo->quotation_no ?? $quotationNo;



        $preCarrierList = ['0' => __('label.SELECT_PRE_CARRIER_OPT')] + PreCarrier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $shippingTermList = ['0' => __('label.SELECT_SHIPPING_TERMS_OPT')] + ShippingTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $paymentTermList = ['0' => __('label.SELECT_PAYMENT_TERMS_OPT')] + PaymentTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
//        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
//                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
//        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::where('status', '1')
//                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
//        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::where('status', '1')
//                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
//echo '<pre>';
//        print_r($countryList);
//        exit;

        if ($request->view == 'print') {
            return view('crmMyOpportunity.quotation.print.index')->with(compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'subtotal', 'quotationInfo'
                                    , 'responsibleAgent', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'productDataList', 'qpArr', 'buyerList', 'productList', 'brandList', 'gradeList'
                                    , 'countryList', 'quotationTermArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('crmMyOpportunity.quotation.print.index', compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'subtotal', 'quotationInfo'
                                    , 'responsibleAgent', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'productDataList', 'qpArr', 'buyerList', 'productList', 'brandList', 'gradeList'
                                    , 'countryList', 'quotationTermArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('Qtn-' . $quotationNo . '.pdf');
//            return $pdf->stream();
        } else {
//            echo "<pre>";
//            print_r($quotationInfo);
//            exit;

            return view('crmMyOpportunity.quotation.index')->with(compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'subtotal', 'quotationInfo'
                                    , 'responsibleAgent', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'productDataList', 'qpArr', 'buyerList', 'productList', 'brandList', 'gradeList'
                                    , 'countryList', 'id', 'quotationTermArr', 'generator'));
        }
    }

    //save quotation page
    public function quotationSave(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        // Validation
        $oppDataInfo = CrmOpportunity::select('product_data', 'buyer_has_id')->where('id', $request->opportunity_id)->first();
        if (!empty($oppDataInfo) && $oppDataInfo->buyer_has_id == '0') {
            $errorMsg = __('label.BUYER_INPUT_IS_INVALID');
            return Response::json(array('success' => false, 'message' => $errorMsg), 401);
        }
        $rules = $message = [];
        $rules = [
            'quotation_date' => 'required',
            'quotation_no' => 'required',
            'quotation_valid_till' => 'required',
        ];

        if (!empty($request->product)) {
            $row = 1;
            foreach ($request->product as $pKey => $pInfo) {
                $rules['product.' . $pKey . '.product_id'] = 'required|not_in:0';
                $rules['product.' . $pKey . '.brand_id'] = 'required|not_in:0';
                $message['product.' . $pKey . '.product_id' . '.not_in'] = __('label.PRODUCT_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.brand_id' . '.not_in'] = __('label.BRAND_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $row++;
            }
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if (!empty($request->product)) {
            $termsRow = 1;
            foreach ($request->product as $pKey => $pInfo) {
                //if (count(array_filter($pInfo)) != 0) {
                $rules['payment_term_id.' . $pKey] = 'required|not_in:0';
                $rules['shipping_term_id.' . $pKey] = 'required|not_in:0';
                $rules['port_of_loading.' . $pKey] = 'required|not_in:0';
                $rules['port_of_discharge.' . $pKey] = 'required|not_in:0';
                $rules['total_lead_time.' . $pKey] = 'required|not_in:0';
                $rules['pre_carrier_id.' . $pKey] = 'required|not_in:0';
                $rules['estimated_shipment_date.' . $pKey] = 'required|not_in:0';

                $message['payment_term_id.' . $pKey . '.not_in'] = __('label.PAYMENT_TERM_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['shipping_term_id.' . $pKey . '.not_in'] = __('label.SHIPPING_TERM_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['port_of_loading.' . $pKey . '.required'] = __('label.PORT_OF_LOADING_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['port_of_discharge.' . $pKey . '.required'] = __('label.PORT_OF_DISCHARGE_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['total_lead_time.' . $pKey . '.required'] = __('label.TOTAL_LEAD_TIME_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['pre_carrier_id.' . $pKey . '.not_in'] = __('label.CAREER_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;
                $message['estimated_shipment_date.' . $pKey . '.required'] = __('label.ESTIMATED_SHIPMENT_DATE_IS_REQUIRED_FOR_ROW_NO_') . $termsRow;

                //}
                $termsRow++;
            }
        }


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end :: validation

        if (!empty($request->quotation_id)) {
            $target = CrmQuotation::find($request->quotation_id);
        } else {
            $target = New CrmQuotation;
        }

        $productArr = [];
        $quotationTermsArr = [];
        if (!empty($request->product)) {
            foreach ($request->product as $pKey => $pInfo) {
                if (count(array_filter($pInfo)) != 0) {
                    $product = $pInfo['product_has_id'] == '1' ? ($pInfo['product_id'] ?? '') : ($pInfo['product_name'] ?? '');
                    $brand = $pInfo['brand_has_id'] == '1' ? ($pInfo['brand_id'] ?? '') : ($pInfo['brand_name'] ?? '');
                    $grade = $pInfo['grade_has_id'] == '1' ? ($pInfo['grade_id'] ?? '') : ($pInfo['grade_name'] ?? '');

                    $productArr[$pKey]['product'] = $product;
                    $productArr[$pKey]['product_has_id'] = $pInfo['product_has_id'] ?? '0';
                    $productArr[$pKey]['brand'] = $brand;
                    $productArr[$pKey]['brand_has_id'] = $pInfo['brand_has_id'] ?? '0';
                    $productArr[$pKey]['grade'] = $grade;
                    $productArr[$pKey]['grade_has_id'] = $pInfo['grade_has_id'] ?? '0';
                    $productArr[$pKey]['origin'] = $pInfo['origin'] ?? '';
                    $productArr[$pKey]['gsm'] = $pInfo['gsm'] ?? '';
                    $productArr[$pKey]['quantity'] = $pInfo['quantity'] ?? '0.00';
                    $productArr[$pKey]['unit'] = $pInfo['unit'] ?? '';
                    $productArr[$pKey]['unit_price'] = $pInfo['unit_price'] ?? '0.00';
                    $productArr[$pKey]['total_price'] = $pInfo['total_price'] ?? '0.00';

                    $quotationTermsArr[$pKey]['opportunity_id'] = $request->opportunity_id;
                    $quotationTermsArr[$pKey]['product_key'] = $pKey;
                    $quotationTermsArr[$pKey]['payment_term_id'] = $request->payment_term_id[$pKey];
                    $quotationTermsArr[$pKey]['shipping_term_id'] = $request->shipping_term_id[$pKey];
                    $quotationTermsArr[$pKey]['port_of_loading'] = $request->port_of_loading[$pKey];
                    $quotationTermsArr[$pKey]['port_of_discharge'] = $request->port_of_discharge[$pKey];
                    $quotationTermsArr[$pKey]['total_lead_time'] = $request->total_lead_time[$pKey];
                    $quotationTermsArr[$pKey]['pre_carrier_id'] = $request->pre_carrier_id[$pKey];
                    $quotationTermsArr[$pKey]['estimated_shipment_date'] = $request->estimated_shipment_date[$pKey];
                }
            }
        }


        // Delete previous record for this opportunity_id
        CrmQuotationTerms::where('opportunity_id', $request->opportunity_id)->delete();
        $i = 0;
        $data = [];
        if (!empty($quotationTermsArr)) {
            foreach ($quotationTermsArr as $qkey => $qtInfo) {

                if (count(array_filter($pInfo)) > 0) {
                    $estimatedShipmentDate = !empty($qtInfo['estimated_shipment_date']) ? Helper::dateFormatConvert($qtInfo['estimated_shipment_date']) : '';
                    $data[$i]['opportunity_id'] = $qtInfo['opportunity_id'];
                    $data[$i]['product_key'] = $qtInfo['product_key'];
                    $data[$i]['payment_term_id'] = $qtInfo['payment_term_id'];
                    $data[$i]['shipping_term_id'] = $qtInfo['shipping_term_id'];
                    $data[$i]['port_of_loading'] = $qtInfo['port_of_loading'];
                    $data[$i]['port_of_discharge'] = $qtInfo['port_of_discharge'];
                    $data[$i]['total_lead_time'] = $qtInfo['total_lead_time'];
                    $data[$i]['pre_carrier_id'] = $qtInfo['pre_carrier_id'];
                    $data[$i]['estimated_shipment_date'] = $estimatedShipmentDate;
                    $i++;
                }
            }
        }
        CrmQuotationTerms::insert($data);
        //data save
        $quotationDate = !empty($request->quotation_date) ? Helper::dateFormatConvert($request->quotation_date) : '';
        $quotationValidTill = !empty($request->quotation_valid_till) ? Helper::dateFormatConvert($request->quotation_valid_till) : '';

        $target->opportunity_id = $request->opportunity_id;
        $target->attention_id = $request->attention_id;
        $target->quotation_date = $quotationDate;
        $target->quotation_no = $request->quotation_no;
        $target->quotation_valid_till = $quotationValidTill;
        $target->note = $request->note ?? '';
        $target->remove_total = !empty($request->remove_total) ? '1' : '0';
        $target->status = $request->status;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

//        echo '<pre>';
//        print_r($target->toArray());
//        exit;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                CrmOpportunity::where('id', $request->opportunity_id)->update([
                    'product_data' => json_encode($productArr),
                ]);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.QUOTATION_SET_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_SET_QUOTATION')], 401);
        }
    }

    //************************* End :: quotation ***************************//
    //************************* Start :: Activity Log ***********************//
    public function getOpportunityActivityLogModal(Request $request) {
        $view = 'crmMyOpportunity.showActivityLogModal';
        return Common::getOpportunityActivityLogModal($request, $view);
    }

    public function getActivityContactPersonData(Request $request) {
        $view = 'crmMyOpportunity.contactPersonData';
        return Common::getActivityContactPersonData($request, $view);
    }

    public function saveActivityContactPersonData(Request $request) {
        $loadView = 'crmMyOpportunity.contactPersonView';
        return Common::saveActivityContactPersonData($request, $loadView);
    }

    public function saveActivityModal(Request $request) {
        return Common::saveActivityModal($request);
    }

    //************************* End :: Activity Log *************************//
}
