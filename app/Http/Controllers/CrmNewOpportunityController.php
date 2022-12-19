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
use App\Country;
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

class CrmNewOpportunityController extends Controller {

    private $controller = 'CrmNewOpportunity';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::where('status', '1')->pluck('name', 'id')->toArray();
        $employeeList = ['0' => __('label.SELECT_EMPLOYEE_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->where('users.status', '1')->pluck('name', 'users.id')->toArray();

        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1') {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;
        //endif buyer list

        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        $buyerByNameArr = CrmOpportunity::where('buyer_has_id', '0')->orderBy('buyer', 'asc');


        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $buyerByNameArr = $buyerByNameArr->where('crm_opportunity.created_by', Auth::user()->id);
        }
        $buyerByNameArr = $buyerByNameArr->pluck('buyer', 'buyer')->toArray();

        $buyerByIdArr = CrmOpportunity::join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->orderBy('buyer.name', 'asc');

        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $buyerByIdArr = $buyerByIdArr->where('crm_opportunity.created_by', Auth::user()->id);
        }
        $buyerByIdArr = $buyerByIdArr->pluck('buyer.name', 'crm_opportunity.buyer')->toArray();

        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerByNameArr + $buyerByIdArr;

        $targetArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.id', 'crm_opportunity.buyer', 'crm_opportunity.buyer_has_id', 'crm_source.name as source'
                        , 'crm_opportunity.created_at', 'crm_opportunity.created_by', 'crm_opportunity.remarks'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"), 'crm_opportunity.product_data')
                ->where('crm_opportunity.status', '0');

        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $targetArr = $targetArr->where('crm_opportunity.created_by', Auth::user()->id);
        }
        //begin filtering
        $buyerSearch = $request->buyer;
        if (!empty($buyerSearch)) {
            $targetArr = $targetArr->where('crm_opportunity.buyer', $buyerSearch);
        }

        if (!empty($request->source_id)) {
            $targetArr = $targetArr->where('crm_opportunity.source_id', $request->source_id);
        }
        if (Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1') {
            if (!empty($request->created_by)) {
                $targetArr = $targetArr->where('crm_opportunity.created_by', $request->created_by);
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
        //end filtering

        $targetArr = $targetArr->orderBy('crm_opportunity.updated_at', 'desc');
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/crmNewOpportunity?page=' . $page);
        }

        $crmLeader = User::where('id', Auth::user()->id)->where('status', '1')
                        ->where('allowed_for_crm', '1')->where('for_crm_leader', '1')->first();

        return view('crmNewOpportunity.index')->with(compact('targetArr', 'qpArr', 'buyerArr'
                                , 'sourceList', 'employeeList', 'crmLeader', 'buyerList'
                                , 'productList', 'brandList', 'gradeList'
                                , 'productArr', 'brandArr'));
    }

    public function filter(Request $request) {
        $createdBy = '';
        if (Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1') {
            $createdBy = '&created_by=' . $request->created_by;
        }
        $url = 'buyer=' . urlencode($request->buyer) . '&source_id=' . $request->source_id
                . $createdBy . '&product=' . $request->product . '&brand=' . $request->brand;
        return Redirect::to('crmNewOpportunity?' . $url);
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::where('status', '1')->pluck('name', 'id')->toArray();
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1') {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;
        //endif buyer list
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')];
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')];
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')];
        $countryList = ['0' => __('label.SELECT_ORIGIN_OPT')] + Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT'));

        return view('crmNewOpportunity.create')->with(compact('qpArr', 'sourceList', 'buyerList'
                                , 'productList', 'brandList', 'gradeList', 'countryList', 'buyerContPersonList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
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
                $rules['product.' . $pKey . '.gsm'] = 'required';
                $message['product.' . $pKey . '.product_id' . '.not_in'] = __('label.PRODUCT_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.brand_id' . '.not_in'] = __('label.BRAND_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.gsm' . '.required'] = __('label.GSM_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
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

        $target = new CrmOpportunity;
        $target->buyer = $buyer;
        $target->buyer_has_id = $request->buyer_has_id;
        $target->address = $request->address;
        $target->source_id = $request->source_id;
        $target->remarks = $request->remarks;
        $target->buyer_contact_person = json_encode($contactArr);
        $target->product_data = json_encode($productArr);
        $target->status = '0';

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.NEW_OPPORTUNITY_CREATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NEW_OPPORTUNITY_COULD_NOT_BE_CREATED')), 401);
        }
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
        if ($generator->group_id != 1) {
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

        return view('crmNewOpportunity.edit')->with(compact('target', 'qpArr', 'sourceList'
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
                $rules['product.' . $pKey . '.gsm'] = 'required';
                $message['product.' . $pKey . '.product_id' . '.not_in'] = __('label.PRODUCT_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.brand_id' . '.not_in'] = __('label.BRAND_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['product.' . $pKey . '.gsm' . '.required'] = __('label.GSM_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
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
        $target->status = '0';

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.NEW_OPPORTUNITY_UPDATED_SUCCESSFULLY'), 'page' => $page), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NEW_OPPORTUNITY_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = CrmOpportunity::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

//        //Dependency
//        $dependencyArr = [
//            'ProductCategory' => ['1' => 'parent_id'],
//            'Product' => ['1' => 'product_category_id'],
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('crmNewOpportunity' . $pageNumber);
//                }
//            }
//        }

        if ($target->delete()) {
            Session::flash('error', __('label.OPPORTUNITY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.OPPORTUNITY_COULD_NOT_BE_DELETED'));
        }
        return redirect('crmNewOpportunity' . $pageNumber);
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
        if (!empty($request->status_id)) {
            $productArr = $productArr->where('status', '1');
        }
        $productArr = $productArr->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr;

//Endof product list

        $buyerHeadOfficeAddress = !empty($target->head_office_address) ? $target->head_office_address : null;

        $view = view('crmNewOpportunity.showContactPerson', compact('request', 'buyerContPersonList'))->render();
        $productView = view('crmNewOpportunity.getProduct', compact('request', 'productList'))->render();

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
        $brandArr = $brandArr->whereIn('product_to_brand.brand_id', $brandArr2);
        if (!empty($request->status_id)) {
            $brandArr = $brandArr->where('brand.status', '1');
        }
        $brandArr = $brandArr->get();

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
        $html = view('crmNewOpportunity.getBrand', compact('request', 'brandList'))->render();

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

        $view = view('crmNewOpportunity.getGrade', compact('request', 'gradeList'))->render();
        return response()->json(['html' => $view, 'originName' => $originName, 'originId' => $originId]);
    }

    public function newContactRow(Request $request) {
        $view = view('crmNewOpportunity.newContactRow')->render();
        return response()->json(['html' => $view]);
    }

    public function newProductRow(Request $request) {
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
        if (!empty($request->status_id)) {
            $productArr = $productArr->where('status', '1');
        }
        $productArr = $productArr->pluck('name', 'id')->toArray();
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
        $view = view('crmNewOpportunity.newProductRow', compact('productList', 'brandList', 'gradeList', 'countryList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getOpportunityDetails(Request $request) {
        $loadView = 'crmNewOpportunity.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    //******************* Start :: Opportunity Assignment **********************
    public function getOpportunityToMemberToRelate(Request $request) {
        $loadView = 'crmNewOpportunity.showAssignOpportunity';
        return Common::getOpportunityToMemberToRelate($request, $loadView);
    }

    public function relateOpportunityToMember(Request $request) {
        return Common::relateOpportunityToMember($request);
    }

    //******************* End :: Opportunity Assignment ************************
}
