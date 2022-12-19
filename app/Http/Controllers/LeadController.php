<?php

namespace App\Http\Controllers;

use Validator;
use App\Lead; //model class
use App\Buyer; //model class
use App\Product; //model class
use App\Designation; //model class
use App\ContactDesignation; //model class
use App\User; //model class
use App\BuyerFactory; //model class
use App\RwUnit; //model class
use App\RwBreakdown; //model class
use App\SalesPersonToProduct; //model class
use App\SalesPersonToBuyer; //model class
use App\SupplierToProduct; //model class
use App\FollowUpHistory;
use App\ProductToBrand;
use App\BuyerToProduct;
use App\Brand;
use App\ProductPricing;
use App\InquiryDetails;
use App\ProductToGrade;
use App\Grade;
use App\CommissionSetup;
use App\CompanyInformation;
use App\PaymentTerm;
use App\ShippingTerm;
use App\PreCarrier;
use App\Quotation;
use App\CauseOfFailure;
use App\FollowupStatus;
use App\CrmSource;
use App\CrmOpportunity;
use App\CrmOpportunityToMember;
use App\CrmActivityLog;
use App\Country;
use PDF;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use DB;
use Illuminate\Http\Request;
use Helper;

//LEAD/INQUIRY Controller
class LeadController extends Controller {

//index
    public function index(Request $request) {
//passing param for custom function
        $qpArr = $request->all();

        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

//sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
//endof arr
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;


        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('users.allowed_for_sales', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;

        //product list
        $productIdArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

        $productList = Product::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $productList = $productList->whereIn('id', $productIdArr);
        }
        $productList = $productList->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productList;

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();


        //RW Status Arr
        $rwBreakdownStatusArr = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->where('inquiry.status', '1')
                        ->where('rw_breakdown.status', '2')
                        ->pluck('rw_breakdown.status', 'rw_breakdown.inquiry_id')->toArray();




        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $inquiryDetails = $inquiryDetails->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        if (!empty($request->product_id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry_details.product_id', $request->product_id);
        }
        if (!empty($request->brand_id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry_details.brand_id', $request->brand_id);
        }

        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id')
                ->get();

        $inquiryIdArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $inquiryIdArr[$item->inquiry_id] = $item->inquiry_id;
            }
        }

        $targetArr = Lead::Join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->leftJoin('buyer_factory', 'buyer_factory.id', '=', 'inquiry.factory_id')
                ->where('inquiry.status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }

        $targetArr = $targetArr->select('inquiry.cancel_remarks', 'inquiry.buyer_contact_person', 'inquiry.id'
                        , 'inquiry.creation_date', 'inquiry.head_office_address'
                        , 'buyer.name as buyerName'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name,' (',designation.short_name,')') as salesPersonName")
                        , 'buyer_factory.name as factoryName', 'inquiry.status'
                        , 'inquiry.shipment_address_status'
                )
                ->orderBy('inquiry.creation_date', 'desc')
                ->orderBy('inquiry.id', 'desc');


        if (!empty($request->product_id) || !empty($request->brand_id)) {
            $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
        }
        if (!empty($request->buyer_id)) {
            $targetArr = $targetArr->where('inquiry.buyer_id', $request->buyer_id);
        }

        if (!empty($request->status)) {
            $targetArr = $targetArr->where('inquiry.status', $request->status);
        }
        if (!empty($request->salespersons_id)) {
            $targetArr = $targetArr->where('inquiry.salespersons_id', $request->salespersons_id);
        }

        $fromDate = '';
        if (!empty($request->from_date)) {
            $fromDate = Helper::dateFormatConvert($request->from_date);
            $targetArr = $targetArr->where('inquiry.creation_date', '>=', $fromDate);
        }
        $toDate = '';
        if (!empty($request->to_date)) {
            $toDate = Helper::dateFormatConvert($request->to_date);
            $targetArr = $targetArr->where('inquiry.creation_date', '<=', $toDate);
        }


        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/lead?page=' . $page);
        }


        //inquiry Details arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['quantity'] = $item->quantity;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_price'] = $item->total_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
            }
        }
        //inquiry Details
        //START final targetArr
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $key => $item) {
                $targetArr[$key] = $item;
                $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
            }
        }
        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $productId => $productData) {
                    foreach ($productData as $brandId => $brandData) {
                        foreach ($brandData as $gradeId => $gradeData) {
                            foreach ($gradeData as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }
        //ENDOF Rowspan Arr
//        echo '<pre>';
//        print_r($inquiryDetailsArr[1070]);
//        print_r($rowspanArr['inquiry'][1070]);
//        print_r($rowspanArr['product'][1070]);
//        print_r($rowspanArr['brand'][1070]);
//        print_r($rowspanArr['grade'][1070]);
//        exit;
        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        //inquiries with commission alredy set
        $commissionAlreadySetList = CommissionSetup::join('inquiry', 'inquiry.id', '=', 'commission_setup.inquiry_id')
                        ->where('inquiry.status', '1')->pluck('inquiry.id')->toArray();

        //inquiry has followup history
        $hasFollowupList = [];
        $hasFollowupArr = FollowUpHistory::join('inquiry', 'inquiry.id', '=', 'follow_up_history.inquiry_id')
                        ->where('inquiry.status', '1')->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->where('inquiry.status', '1')->pluck('inquiry.id')->toArray();

        if (!empty($hasFollowupArr)) {
            foreach ($hasFollowupArr as $fKey => $inquiryId) {
                $hasFollowupList[$inquiryId] = $inquiryId;
            }
        }
        if (!empty($hasActivityArr)) {
            foreach ($hasActivityArr as $aKey => $inquiryId) {
                $hasFollowupList[$inquiryId] = $inquiryId;
            }
        }


        return view('lead.index')->with(compact('request', 'qpArr', 'targetArr', 'buyerList'
                                , 'salesPersonList', 'inquiryDetailsArr'
                                , 'productArr', 'brandArr', 'gradeArr', 'rwBreakdownStatusArr'
                                , 'commissionAlreadySetList', 'productList', 'brandList'
                                , 'hasFollowupList', 'rowspanArr'));
    }

    //FILTER
    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&salespersons_id=' . $request->salespersons_id
                . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id;
        return Redirect::to('lead?' . $url);
    }

//create
    public function create(Request $request) {
//passing param for custom function
        $qpArr = $request->all();

        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

//sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
//endof arr
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->where('status', '1')->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;


        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT'));
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT'));

//
//        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
//                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
//                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
//                ->where('users.allowed_for_sales', '1');
//        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
//            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
//        }
//        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();

        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')];



        $factoryList = [];

        $brandList = array('0' => __('label.SELECT_BRAND_OPT'));
        $gradeList = array('0' => __('label.SELECT_GRADE_OPT'));

        $followupStatusList = ['0' => __('label.SELECT_STATUS_OPT')] + FollowupStatus::where('status', '1')->pluck('name', 'id')->toArray();


        return view('lead.create')->with(compact('qpArr', 'buyerList', 'buyerContPersonList'
                                , 'salesPersonList', 'productList', 'factoryList'
                                , 'brandList', 'gradeList', 'followupStatusList'));
    }

//store
    public function store(Request $request) {

//passing param for custom function
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        $rules = $message = array();
        $rules = [
            'buyer_id' => 'required|not_in:0',
            'buyer_contact_person' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'quantity' => 'required',
            'gsm' => 'required',
            'creation_date' => 'required',
            'salespersons_id' => 'required|not_in:0',
            'followup_status' => 'required|not_in:0',
            'followup_remarks' => 'required',
        ];


        if ($request->shipment_address_status == '1') {
            $rules['head_office_address'] = 'required';
        }
        if ($request->shipment_address_status == '2') {
            $rules['factory_id'] = 'required|not_in:0';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }



        //buyer_contact_person
        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $contactPersonInfo = Buyer::find($request->buyer_id);
        $contactPersonArr = json_decode($contactPersonInfo->contact_person_data, true);

        $buyer_contact_person = '';
        if (!empty($contactPersonArr)) {
            foreach ($contactPersonArr as $key => $item) {
                if ($request->buyer_contact_person == $key) {
                    $designation = !empty($designationArr[$item['designation_id']]) ? '(' . $designationArr[$item['designation_id']] . ')' : '';
                    $buyer_contact_person = $item['name'] . $designation;
                }
            }
        }


//endof buyer_contact_person



        $creationDate = Helper::dateFormatConvert($request->creation_date);

        $target = new Lead;
        $target->opportunity_id = 0;
        $target->buyer_id = $request->buyer_id;
        $target->buyer_contact_person = $buyer_contact_person;
        $target->contact_person_identifier = $request->buyer_contact_person;
        $target->salespersons_id = $request->salespersons_id;
        $target->creation_date = $creationDate;
        $target->shipment_address_status = $request->shipment_address_status;
        $target->status = '1';
        if ($request->shipment_address_status == '1') {
            $target->head_office_address = $request->head_office_address;
            $target->factory_id = $request->null;
        }
        if ($request->shipment_address_status == '2') {
            $target->factory_id = $request->factory_id;
            $target->head_office_address = $request->null;
        }

        $historyData = [];
        $target->add_first_followup = '1';
        $target->followup_remarks = $request->followup_remarks;

        $uniqId = uniqid();

        //create new follow up array
        $historyData[$uniqId]['follow_up_date'] = $request->creation_date;
        $historyData[$uniqId]['status'] = $request->followup_status;
        $historyData[$uniqId]['remarks'] = $request->followup_remarks;
        $historyData[$uniqId]['updated_by'] = Auth::user()->id;
        $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');

        if (empty($request->add_btn)) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_LEAD_FOR_SAVE')], 401);
        }


        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;
                foreach ($request->product_id as $key => $productId) {
                    $data[$i]['inquiry_id'] = $target->id;
                    $data[$i]['product_id'] = $request->product_id[$key];
                    $data[$i]['brand_id'] = $request->brand_id[$key];
                    $data[$i]['grade_id'] = !empty($request->grade_id[$key]) ? $request->grade_id[$key] : null;
                    $data[$i]['gsm'] = $request->gsm[$key];
                    $data[$i]['quantity'] = $request->quantity[$key];
                    $data[$i]['unit_price'] = $request->unit_price[$key];
                    $data[$i]['total_price'] = $request->total_price[$key];
                    $i++;
                }

                $followUpHistory = new FollowUpHistory;
                $followUpHistory->inquiry_id = $target->id;
                $followUpHistory->history = json_encode($historyData);
                $followUpHistory->updated_at = date('Y-m-d H:i:s');
                $followUpHistory->updated_by = Auth::user()->id;
                $followUpHistory->save();

                InquiryDetails::insert($data);
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_CREATED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_CREATED')], 401);
        }
    }

//edit
    public function edit(Request $request, $id) {
//passing param for custom function
        $qpArr = $request->all();


//sales person access system arr
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
//endof arr

        $target = lead::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('lead');
        }

        //modify code 
        //inquiry Details 
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $target->id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name')
                ->get();

        //netTotalPrice And ProductIdArr
        $productIdArr = [];
        if (!empty($inquiryDetails)) {
            foreach ($inquiryDetails as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
                $netTotalPrice = !empty($netTotalPrice) ? $netTotalPrice : 0.00;
                $netTotalPrice += $item->total_price;
            }
        }

        //end of modify code
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;
        //endif buyer list
//Product List
        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $target->buyer_id)->get();


        $productIdArr = $productIdArr2 = [];
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', $target->salespersons_id)->get();

        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }


        $productArr = Product::whereIn('id', $productIdArr)
                        ->whereIn('id', $productIdArr2)
                        ->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr;

//Endof product list



        $salesPersonArr = User::join('sales_person_to_buyer', 'sales_person_to_buyer.sales_person_id', '=', 'users.id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('sales_person_to_buyer.buyer_id', $target->buyer_id)
                ->where('sales_person_to_buyer.business_status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;


        //Factory List
        $factoryList = ['0' => __('label.SELECT_FACTORY_OPT')] + BuyerFactory::where('status', '1')
                        ->where('buyer_id', $target->buyer_id)
                        ->orderBy('primary_factory', 'desc')
                        ->pluck('name', 'id')->toArray();
        //buyer factory address
        $factoryAddressInfo = BuyerFactory::where('id', $target->factory_id)
                        ->select('address')->first();
        $factoryAddress = '';
        if (!empty($factoryAddressInfo)) {
            $factoryAddress = $factoryAddressInfo->address;
        }

//        buyerContPersonList
        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $contactPersonInfo = Buyer::find($target->buyer_id);
        $contactPersonArr = json_decode($contactPersonInfo->contact_person_data, true);

        $buyerContactPersonArr = [];
        if (!empty($contactPersonArr)) {
            foreach ($contactPersonArr as $key => $item) {
                $designation = !empty($designationArr[$item['designation_id']]) ? ' (' . $designationArr[$item['designation_id']] . ')' : '';
                $buyerContactPersonArr[$key] = $item['name'] . $designation;
            }
        }

        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT')) + $buyerContactPersonArr;
//endof buyerContPersonList
        //brand List
        /*     $brandArr1 = SalesPersonToProduct::where('sales_person_id', $target->salespersons_id)->whereIn('product_id', $productIdArr)->pluck('brand_id')->toArray();
          $brandArr2 = BuyerToProduct::where('buyer_id', $target->buyer_id)->whereIn('product_id', $productIdArr)->pluck('brand_id')->toArray();

          $brandArr = ProductToBrand::select('brand_id')->whereIn('product_id', $productIdArr);

          $brandArr = $brandArr->whereIn('brand_id', $brandArr1);
          $brandArr = $brandArr->whereIn('brand_id', $brandArr2);
          $brandArr = $brandArr->get();

          $brandIdArr = [];
          if (!$brandArr->isEmpty()) {
          foreach ($brandArr as $brand) {
          $brandIdArr[$brand->brand_id] = $brand->brand_id;
          }
          }
          $brandArr3 = Brand::orderBy('name', 'asc')->whereIn('id', $brandIdArr)->pluck('name', 'id')->toArray(); */
        $brandList = array('0' => __('label.SELECT_BRAND_OPT'));
        $gradeList = array('0' => __('label.SELECT_GRADE_OPT'));

//ENDOF buyer List

        $followupStatusList = ['0' => __('label.SELECT_STATUS_OPT')] + FollowupStatus::where('status', '1')->pluck('name', 'id')->toArray();

        $firstFollowupStatus = 0;

        $followUpHistory = FollowUpHistory::where('inquiry_id', $id)->first();

        if (!empty($followUpHistory)) {
            $preHistoryArr = json_decode($followUpHistory->history, true);
            $firstFollowup = reset($preHistoryArr);
            $firstFollowupStatus = $firstFollowup['status'];
        }


        return view('lead.edit')->with(compact('qpArr', 'target', 'buyerList', 'buyerContPersonList'
                                , 'productList', 'salesPersonList', 'factoryList', 'brandList'
                                , 'inquiryDetails', 'netTotalPrice'
                                , 'gradeList', 'factoryAddress', 'followupStatusList', 'firstFollowupStatus'));
    }

//update
    public function update(Request $request) {
//echo '<pre>';
//print_r($request->all());
//exit;
        $target = Lead::find($request->id);

//begin back same page after update
        $qpArr = $request->all();
//echo '<pre>';print_r($qpArr);exit;
        $pageNumber = $qpArr['filter'];
//end back same page after update
        $rules = $message = array();

        $rules = [
            'buyer_id' => 'required|not_in:0',
            'buyer_contact_person' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'quantity' => 'required',
            'gsm' => 'required',
            'creation_date' => 'required',
            'salespersons_id' => 'required|not_in:0',
            'followup_status' => 'required|not_in:0',
            'followup_remarks' => 'required',
        ];


        if ($request->shipment_address_status == '1') {
            $rules['head_office_address'] = 'required';
        }
        if ($request->shipment_address_status == '2') {
            $rules['factory_id'] = 'required|not_in:0';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


//buyer_contact_person
        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $contactPersonInfo = Buyer::find($request->buyer_id);
        $contactPersonArr = json_decode($contactPersonInfo->contact_person_data, true);

        $buyer_contact_person = '';
        if (!empty($contactPersonArr)) {
            foreach ($contactPersonArr as $key => $item) {
                if ($request->buyer_contact_person == $key) {
                    $designation = !empty($designationArr[$item['designation_id']]) ? '(' . $designationArr[$item['designation_id']] . ')' : '';
                    $buyer_contact_person = $item['name'] . $designation;
                }
            }
        }

//endof buyer_contact_person
        $creationDate = Helper::dateFormatConvert($request->creation_date);
        $target->buyer_id = $request->buyer_id;
        $target->buyer_contact_person = $buyer_contact_person;
        $target->contact_person_identifier = $request->buyer_contact_person;
        $target->salespersons_id = $request->salespersons_id;

        $target->creation_date = $creationDate;
        $target->shipment_address_status = $request->shipment_address_status;
        $target->status = '1';
        if ($request->shipment_address_status == '1') {
            $target->head_office_address = $request->head_office_address;
            $target->factory_id = $request->null;
        }
        if ($request->shipment_address_status == '2') {
            $target->factory_id = $request->factory_id;
            $target->head_office_address = $request->null;
        }

        $historyData = [];

        $followUpHistory = FollowUpHistory::where('inquiry_id', $request->id)->first();


        $target->followup_remarks = $request->followup_remarks;

        if (!empty($followUpHistory)) {
            $preHistoryArr = json_decode($followUpHistory->history, true);
            $firstFollowup = reset($preHistoryArr);
            if (!empty($preHistoryArr)) {
                foreach ($preHistoryArr as $key => $history) {
                    if ($firstFollowup == $history) {
                        $historyData[$key]['follow_up_date'] = $request->creation_date;
                        $historyData[$key]['status'] = $request->followup_status;
                        $historyData[$key]['remarks'] = $request->followup_remarks;
                        $historyData[$key]['updated_by'] = Auth::user()->id;
                        $historyData[$key]['updated_at'] = date('Y-m-d H:i:s');
                    } else {
                        $historyData[$key]['follow_up_date'] = $history['follow_up_date'];
                        $historyData[$key]['status'] = $history['status'];
                        $historyData[$key]['remarks'] = $history['remarks'];
                        $historyData[$key]['updated_by'] = $history['updated_by'];
                        $historyData[$key]['updated_at'] = $history['updated_at'];
                    }
                }
            }
        } else {
            $followUpHistory = new FollowUpHistory;
            $uniqKey = uniqid();
            $historyData[$uniqKey]['follow_up_date'] = $request->creation_date;
            $historyData[$uniqKey]['status'] = $request->followup_status;
            $historyData[$uniqKey]['remarks'] = $request->followup_remarks;
            $historyData[$uniqKey]['updated_by'] = Auth::user()->id;
            $historyData[$uniqKey]['updated_at'] = date('Y-m-d H:i:s');
        }
        $followUpHistory->inquiry_id = $target->id;
        $followUpHistory->history = json_encode($historyData);
        $followUpHistory->updated_at = date('Y-m-d H:i:s');
        $followUpHistory->updated_by = Auth::user()->id;

//        echo '<pre>';
//        print_r($followUpHistory->toArray());
//        exit;


        if (empty($request->add_btn)) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_LEAD_FOR_SAVE')], 401);
        }


        DB::beginTransaction();
        try {
            if ($target->save()) {
                $data = [];
                $i = 0;
                foreach ($request->product_id as $key => $productId) {
                    $data[$i]['inquiry_id'] = $target->id;
                    $data[$i]['product_id'] = $request->product_id[$key];
                    $data[$i]['brand_id'] = $request->brand_id[$key];
                    $data[$i]['grade_id'] = !empty($request->grade_id[$key]) ? $request->grade_id[$key] : null;
                    $data[$i]['gsm'] = $request->gsm[$key];
                    $data[$i]['quantity'] = $request->quantity[$key];
                    $data[$i]['unit_price'] = $request->unit_price[$key];
                    $data[$i]['total_price'] = $request->total_price[$key];
                    $i++;
                }
            }


            $followUpHistory->save();


            InquiryDetails::where('inquiry_id', $target->id)->delete();
            RwBreakdown::where('inquiry_id', $target->id)->delete();
            CommissionSetup::where('inquiry_id', $target->id)->delete();
            InquiryDetails::insert($data);
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_UPDATED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {

//            echo '<pre>';
//            print_r($e->getMessage());
//            exit;
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_UPDATED')], 401);
        }
    }

    //******************* Start :: CRM integration *************************
    public function getOpportunityDetails(Request $request) {
        $loadView = 'lead.showOpportunityDetails';
        return Common::getOpportunityDetails($request, $loadView);
    }

    public function getChooseOpportunity(Request $request) {
        $targetArr = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                ->where('crm_opportunity.status', '2')->where('crm_opportunity.revoked_status', '0')
                ->where('crm_opportunity.dispatch_status', '1')->where('crm_opportunity.approval_status', '1')
                ->get();

        $productArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $target) {
                $productArr[$target->id] = !empty($target->product_data) ? json_decode($target->product_data, true) : [];
            }
        }

        $productRowSpanArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $opId => $product) {
                foreach ($product as $pKey => $pInfo) {
                    $productRowSpanArr[$opId] = !empty($productRowSpanArr[$opId]) ? $productRowSpanArr[$opId] : 0;
                    $productRowSpanArr[$opId] += 1;
                }
            }
        }

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

        $view = view('lead.showChooseOpportunity', compact('targetArr', 'request', 'productArr', 'productRowSpanArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public function setChooseOpportunity(Request $request) {

        $rules = $messages = [];
        if (empty($request->opportunity)) {
            $rules['opportunity'] = 'required';
            $messages['opportunity.required'] = __('label.PLEASE_SELECT_AT_LEAST_ONE_OPPORTUNITY');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $opportunityId = !empty($request->opportunity) ? array_key_first($request->opportunity) : 0;

        return response()->json(['opportunityId' => $opportunityId]);
    }

    //******************* Start :: CRM integration *************************

    public function destroy(Request $request, $id) {
        $target = Lead::find($id);


//begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
//end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


//        //Dependency
//        $dependencyArr = [
//            'ProductToSupplier' => ['1' => 'supplier_id'],
//            'Recipe' => ['1' => 'supplier_id'],
//            'BatchRecipe' => ['1' => 'supplier_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
//                    return redirect('supplier' . $pageNumber);
//                }
//            }
//        }
        //Remove data from child table
        RwBreakdown::where('inquiry_id', $target->id)->delete();
        InquiryDetails::where('inquiry_id', $target->id)->delete();
        FollowUpHistory::where('inquiry_id', $target->id)->delete();
        CommissionSetup::where('inquiry_id', $target->id)->delete();
        Quotation::where('inquiry_id', $target->id)->delete();

        if ($target->delete()) {
            Session::flash('error', __('label.INQUIRY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.INQUIRY_COULD_NOT_BE_DELETED'));
        }
        return redirect('lead' . $pageNumber);
    }

//leadConfirmation
    public function leadConfirmation(Request $request) {

        $target = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->where('inquiry.id', $request->inquiry_id)
                        ->select('inquiry.id', 'buyer.code as buyer_code', 'buyer.name as buyerName', 'inquiry.buyer_id'
                                , 'inquiry.po_date', 'inquiry.purchase_order_no')->first();


        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $target->id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.id')
                ->get();
        
//        $request->sl_no = Lead::where('buyer_id', $target->buyer_id)->where('status', '1')->where('order_status', '0')->count();

        $buyerName = !empty($target->buyer_code) ? $target->buyer_code : '';
        $slNo = '';
        if ($request->sl_no > 0 && $request->sl_no < 10) {
            $slNo = '00' . $request->sl_no;
        } elseif ($request->sl_no > 9 && $request->sl_no < 100) {
            $slNo = '0' . $request->sl_no;
        } else {
            $slNo = $request->sl_no;
        }
        $poGenerate = $buyerName . '-' . date('my') . '-' . $slNo;
        $view = view('lead.showConfirmationModal', compact('target', 'inquiryDetails', 'poGenerate'))->render();
        return response()->json(['html' => $view]);
    }

    public function leadConfirmationSave(Request $request) {

        $target = Lead::find($request->inquiry_id);


        $rules = $message = [];

        if (!empty($request->purchase_order_no)) {
            $rules['purchase_order_no'] = 'required|unique:inquiry,purchase_order_no,' . $target->id;
            $message['purchase_order_no.required'] = __('label.THE_PO_NO_FIELD_IS_REQUIRED');
        }

        if (!empty($request->unit_price)) {
            $i = 1;
            foreach ($request->unit_price as $key => $val) {
                if (empty($val)) {
                    $rules['unit_price' . 'Row' . $i] = 'required';
                }
                if ($val == '0.00') {
                    $rules['unit_price' . 'Row' . $i] = 'required';
                }

                $i++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $poDate = !empty($request->po_date) ? Helper::dateFormatConvert($request->po_date) : null;
        $target->purchase_order_no = $request->purchase_order_no;
        $target->po_date = $poDate;
        $target->status = '2';
        $target->order_status = '1';
        $target->inquiry_confirm_updated_at = date('Y-m-d H:i:s');
        $target->inquiry_confirm_updated_by = Auth::user()->id;

        $inquiryDetailsList = InquiryDetails::where('inquiry_id', $target->id)
                        ->pluck('unit_price', 'id')->toArray();


        //value check
        if (!empty($inquiryDetailsList)) {
            foreach ($inquiryDetailsList as $detailId => $unitPrice) {
                if (empty($request->unit_price[$detailId]) || empty($request->total_price[$detailId])) {
                    return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_CONFIRMATION')], 401);
                }
            }
        }




        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (!empty($inquiryDetailsList)) {
                    foreach ($inquiryDetailsList as $detailId => $unitPrice) {
                        $newUnitPrice = !empty($request->unit_price[$detailId]) ? $request->unit_price[$detailId] : 0.00;
                        $newTotalPrice = !empty($request->total_price[$detailId]) ? $request->total_price[$detailId] : 0.00;

                        if ($unitPrice != $newUnitPrice) {
                            InquiryDetails::where('id', $detailId)->update([
                                'unit_price' => $newUnitPrice,
                                'total_price' => $newTotalPrice,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_CONFIRMATION_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_CONFIRMATION')], 401);
        }
    }

    public function leadCancellationModal(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $causeList = ['0' => __('label.SELECT_CAUSE_OF_FAILURE_OPT')] + CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $view = view('lead.showCancellationModal', compact('target', 'causeList'))->render();
        return response()->json(['html' => $view]);
    }

    public function leadCancellation(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $rules = $message = array();
        $rules = [
            'cancel_cause' => 'required|not_in:0',
        ];


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }



        $target->cancel_cause = $request->cancel_cause;
        $target->cancel_remarks = $request->remarks;
        $target->status = '3';
        $target->inquiry_cancelled_updated_at = date('Y-m-d H:i:s');
        $target->inquiry_cancelled_updated_by = Auth::user()->id;


        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_CANCELLATION_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_CANCELLATION')], 401);
        }
    }

    public function getLeadProduct(Request $request) {

        $buyerToProductArr = BuyerToProduct::select('product_id')->where('buyer_id', $request->buyer_id)->get();


        $productIdArr = $productIdArr2 = [];
// ',' separate function
        if (!$buyerToProductArr->isEmpty()) {
            foreach ($buyerToProductArr as $buyerToProduct) {
                $productIdArr[$buyerToProduct->product_id] = $buyerToProduct->product_id;
            }
        }

        $salesPersonToProductArr = SalesPersonToProduct::select('product_id')->where('sales_person_id', $request->salespersons_id)->get();
// ',' separate function
        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $productIdArr2[$salesPersonToProduct->product_id] = $salesPersonToProduct->product_id;
            }
        }


        $productArr = Product::whereIn('id', $productIdArr);
        if (!empty($request->salespersons_id)) {
            $productArr = $productArr->whereIn('id', $productIdArr2);
        }
        $productArr = $productArr->where('status', '1')->pluck('name', 'id')->toArray();


        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr;

        $view = view('lead.showProduct', compact('request', 'productList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getBuyerContPerson(Request $request) {
        //salesperson list
        //sales person access system arr
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        //endof arr
        $salesPersonArr = User::join('sales_person_to_buyer', 'sales_person_to_buyer.sales_person_id', '=', 'users.id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('sales_person_to_buyer.buyer_id', $request->buyer_id)
                ->where('sales_person_to_buyer.business_status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->where('users.status', '1')->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;
        //end of sales person list

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

        $headOffice = '';
        if (!empty($target->head_office_address)) {
            $headOffice = $target->head_office_address;
        }

        $factoryList = ['0' => __('label.SELECT_FACTORY_OPT')] + BuyerFactory::where('status', '1')
                        ->where('buyer_id', $request->buyer_id)
                        ->where('status', '1')
                        ->orderBy('primary_factory', 'desc')
                        ->pluck('name', 'id')->toArray();

        $factory = view('lead.showFactory', compact('request', 'factoryList'))->render();
        $salesPerson = view('lead.showSalesPerson', compact('request', 'salesPersonList'))->render();

        $view = view('lead.showContactPerson', compact('request', 'buyerContPersonList'))->render();
        return response()->json(['html' => $view, 'headOffice' => $headOffice, 'factory' => $factory, 'salesPerson' => $salesPerson]);
    }

//new function
//:::::::Start rwBreakdown :::::::::::
    public function rwBreakdown(Request $request, $id) {
        $loadView = 'lead.rwBreakdown';
        return Common::rwBreakdown($request, $id, $loadView);
    }

    public function rwBreakdownGetBrand(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::rwBreakdownGetBrand($request, $loadView);
    }

    public function rwBreakdownGetGrade(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::rwBreakdownGetGrade($request, $loadView);
    }

    public function getRwBreakdownView(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::getRwBreakdownView($request, $loadView);
    }

    public function rwProceedRequest(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::rwProceedRequest($request, $loadView);
    }

    public function rwProceedRequestEdit(Request $request) {

        $loadView = 'lead.rwBreakdown';
        return Common::rwProceedRequestEdit($request, $loadView);
    }

    public function rwPreviewRequest(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::rwPreviewRequest($request, $loadView);
    }

    public function rwBreakDownSave(Request $request) {

        return Common::rwBreakDownSave($request);
    }

    public function leadRwBreakdownView(Request $request) {
        $loadView = 'lead.rwBreakdown';
        return Common::leadRwBreakdownView($request, $loadView);
    }

    public function getLeadRwParametersName(Request $request) {
        return Common::getLeadRwParametersName($request);
    }

//::::::::::: END OF RW BREAKDOWN
//new function add
//getLeadProductUnit
    public function getLeadProductUnit(Request $request) {
        $measureUnit = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('measure_unit.name as measure_unit_name')->where('product.id', $request->product_id)->first();
        $measureUnitName = !empty($measureUnit->measure_unit_name) ? $measureUnit->measure_unit_name : '';
        $perMeasureUnitName = !empty($measureUnit->measure_unit_name) ? __('label.PER') . ' ' . $measureUnit->measure_unit_name : '';
        return response()->json(['measureUnitName' => $measureUnitName, 'perMeasureUnitName' => $perMeasureUnitName]);
    }

//follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'lead.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

//follow up save
    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

//getLeadBrand
    public function getLeadBrand(Request $request) {
        $brandArr1 = SalesPersonToProduct::where('sales_person_id', $request->salespersons_id)->where('product_id', $request->product_id)->pluck('brand_id')->toArray();
        $brandArr2 = BuyerToProduct::where('buyer_id', $request->buyer_id)->where('product_id', $request->product_id)->pluck('brand_id')->toArray();

//brand List
        $brandArr = ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id')
                        ->select('product_to_brand.brand_id')->where('product_to_brand.product_id', $request->product_id);

        $brandArr = $brandArr->whereIn('product_to_brand.brand_id', $brandArr1);
        $brandArr = $brandArr->whereIn('product_to_brand.brand_id', $brandArr2);
        $brandArr = $brandArr->where('brand.status', '1');
        $brandArr = $brandArr->get();

        $brandIdArr = [];
        if (!$brandArr->isEmpty()) {
            foreach ($brandArr as $brand) {
                $brandIdArr[$brand->brand_id] = $brand->brand_id;
            }
        }

        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::orderBy('name', 'asc')
                        ->whereIn('id', $brandIdArr)->pluck('name', 'id')->toArray();



        $view = view('lead.showBrand', compact('request', 'brandList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getProductPricing(Request $request) {

        $productPricingInfo = ProductPricing::select('target_selling_price', 'minimum_selling_price')
                ->where('product_pricing.product_id', $request->product_id)
                ->where('product_pricing.brand_id', $request->brand_id);
        if (!empty($request->grade_id)) {
            $productPricingInfo = $productPricingInfo->where('product_pricing.grade_id', $request->grade_id);
        } else {
            $productPricingInfo = $productPricingInfo->whereNull('product_pricing.grade_id');
        }
        $productPricingInfo = $productPricingInfo->first();

        $targetSellingPrice = !empty($productPricingInfo->target_selling_price) ? $productPricingInfo->target_selling_price : 0;
        $minimumSellingPrice = !empty($productPricingInfo->minimum_selling_price) ? $productPricingInfo->minimum_selling_price : 0;
        $status = '';
        if (!empty($request->unit_price)) {
            $status = $request->unit_price - $minimumSellingPrice;
        }
        $view = view('lead.productPricing', compact('targetSellingPrice', 'minimumSellingPrice', 'status'))->render();
        return response()->json(['html' => $view]);
    }

//new commission setup modal function
    public function getCommissionSetupModal(Request $request) {
        $loadView = 'lead';
        return Common::getCommissionSetupModal($request, $loadView);
    }

    public function commissionSetupSave(Request $request) {

        return Common::commissionSetupSave($request);
    }

//end of commission
    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'lead.showQuantitySummaryModal';
        $isConfirmedOrder = 0;
        $statusType = 'status';
        $status = '1';

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //end of summary
    //***************** new method add modify inquiry ***********************
    public function getProductBrandData(Request $request) {

        $productInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.name as product_name'
                                , 'measure_unit.name as unit_name')
                        ->where('product.id', $request->product_id)->first();

        $brandInfo = Brand::select('brand.name as brand_name')
                        ->where('brand.id', $request->brand_id)->first();

        $gradeInfo = Grade::select('name as grade_name')
                        ->where('id', $request->grade_id)->first();

        $gradeName = '';
        if (!empty($gradeInfo)) {
            $gradeName = $gradeInfo->grade_name;
        }


        return response()->json(['productName' => $productInfo->product_name
                    , 'productUnit' => $productInfo->unit_name, 'brandName' => $brandInfo->brand_name
                    , 'gradeName' => $gradeName]);
    }

//    Get Grade
    public function getLeadGrade(Request $request) {


        $gradeArr = ProductToGrade::join('grade', 'grade.id', '=', 'product_to_grade.grade_id')
                        ->where('product_to_grade.product_id', $request->product_id)
                        ->where('product_to_grade.brand_id', $request->brand_id)
                        ->where('grade.status', '1')
                        ->pluck('grade.name', 'grade.id')->toArray();
        $gradeList = array('0' => __('label.SELECT_GRADE_OPT')) + $gradeArr;

        $gradeVal = 0;
        if (!empty($gradeArr)) {
            $gradeVal = 1;
        }

        $view = view('lead.showGrade', compact('request', 'gradeList', 'gradeVal'))->render();
        return response()->json(['html' => $view]);
    }

    //***********************end of modify inquiry method ******************
    //GET FACTORY ADDRESS
    public function getFactoryAddress(Request $request) {
        $factoryAddressInfo = BuyerFactory::where('id', $request->factory_id)
                        ->select('address')->first();
        $address = '';
        if (!empty($factoryAddressInfo)) {
            $address = $factoryAddressInfo->address;
        }
        return response()->json(['address' => $address]);
    }

    //new method
    //***************quotation************** */
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

        $target = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select('inquiry.id', 'inquiry.buyer_id', 'buyer.name as buyer_name'
                                , 'buyer.head_office_address as office_address', 'buyer.contact_person_data'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as sales_person_name")
                                , 'designation.title as designation', 'users.email as email', 'users.phone as contact_no')
                        ->where('inquiry.id', $id)->first();

        $attentionArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonDataList = json_decode($target->contact_person_data, true);
            foreach ($contactPersonDataList as $key => $details) {
                $attentionArr[$key] = $details['name'];
            }
        }

        $attentionList = [__('label.N_A') => __('label.SELECT_ATTENTION_OPT')] + $attentionArr;

        //auto generated quotation no.
        $quotationNo = strtoupper(substr(uniqid(), -6)) . date('YmdHis');

        $inquiryDetailsArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                        ->leftJoin('country', 'country.id', '=', 'brand.origin')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->select('inquiry_details.quantity', 'inquiry_details.unit_price', 'inquiry_details.total_price'
                                , 'product.name as product_name', 'grade.name as grade_name', 'measure_unit.name as unit_name'
                                , 'brand.name as brand_name', 'brand.logo as logo', 'country.name as country_of_origin'
                                , 'inquiry_details.id', 'inquiry_details.gsm')
                        ->where('inquiry_details.inquiry_id', $id)->get();

        $subtotal = 0.00;
        if (!$inquiryDetailsArr->isEmpty()) {
            foreach ($inquiryDetailsArr as $item) {
                $subtotal += $item->total_price;
            }
        }

        $preCarrierList = ['0' => __('label.SELECT_PRE_CARRIER_OPT')] + PreCarrier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $shippingTermList = ['0' => __('label.SELECT_SHIPPING_TERMS_OPT')] + ShippingTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $paymentTermList = ['0' => __('label.SELECT_PAYMENT_TERMS_OPT')] + PaymentTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //previous quotation info
        $quotationInfo = Quotation::select('*')->where('inquiry_id', $id)->first();

//        $gsmArr = [];
//        if (!empty($quotationInfo->gsm_values)) {
//            $gsmArr = json_decode($quotationInfo->gsm_values, true);
//        }

        $quotationNo = $quotationInfo->quotation_no ?? $quotationNo;


        if ($request->view == 'print') {
            return view('lead.quotation.print.index')->with(compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'inquiryDetailsArr'
                                    , 'subtotal', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'quotationInfo', 'qpArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('lead.quotation.print.index', compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'inquiryDetailsArr'
                                    , 'subtotal', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'quotationInfo', 'qpArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('Qtn-' . $quotationNo . '.pdf');
//            return $pdf->stream();
        } else {

            return view('lead.quotation.index')->with(compact('request', 'target', 'konitaInfo'
                                    , 'phoneNumber', 'quotationNo', 'attentionList', 'inquiryDetailsArr'
                                    , 'subtotal', 'preCarrierList', 'shippingTermList', 'paymentTermList'
                                    , 'quotationInfo', 'qpArr'));
        }
    }

    //save quotation page
    public function quotationSave(Request $request) {

        //validation
        $rules = $message = [];

        $rules = [
            'quotation_date' => 'required',
            'quotation_no' => 'required',
            'quotation_valid_till' => 'required',
            'payment_term_id' => 'required|not_in:0',
            'shipping_term_id' => 'required|not_in:0',
            'port_of_loading' => 'required',
            'port_of_discharge' => 'required',
            'total_lead_time' => 'required',
            'pre_carrier_id' => 'required|not_in:0',
            'estimated_shipment_date' => 'required',
        ];

//        foreach ($request->gsm as $itemId => $gsm) {
//            $item = $request->item[$itemId] ?? __('label.N_A');
//            $rules['gsm.' . $itemId] = 'required';
//            $message['gsm.' . $itemId . '.required'] = __('label.GSM_IS_REQUIRED_FOR_ITEM', ['item' => $item]);
//        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end :: validation

        if (!empty($request->quotation_id)) {
            $target = Quotation::find($request->quotation_id);
        } else {
            $target = New Quotation;
        }

        //data save
        $quotationDate = !empty($request->quotation_date) ? Helper::dateFormatConvert($request->quotation_date) : '';
        $quotationValidTill = !empty($request->quotation_valid_till) ? Helper::dateFormatConvert($request->quotation_valid_till) : '';
        $estimatedShipmentDate = !empty($request->estimated_shipment_date) ? Helper::dateFormatConvert($request->estimated_shipment_date) : '';

        $target->inquiry_id = $request->inquiry_id;
        $target->attention_id = $request->attention_id;
        $target->quotation_date = $quotationDate;
        $target->quotation_no = $request->quotation_no;
        $target->quotation_valid_till = $quotationValidTill;
        $target->gsm_values = json_encode($request->gsm);
        $target->payment_term_id = $request->payment_term_id;
        $target->shipping_term_id = $request->shipping_term_id;
        $target->port_of_loading = $request->port_of_loading ?? '';
        $target->port_of_discharge = $request->port_of_discharge ?? '';
        $target->total_lead_time = $request->total_lead_time ?? '';
        $target->pre_carrier_id = $request->pre_carrier_id;
        $target->estimated_shipment_date = $estimatedShipmentDate;
        $target->note = $request->note ?? '';
        $target->remove_total = !empty($request->remove_total) ? '1' : '0';
        $target->status = $request->status;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

//        echo '<pre>';
//        print_r($target->toArray());
//        exit;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.QUOTATION_SET_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SET_QUOTATION')), 401);
        }
    }

    //***************end :: quotation************** */
    //************************ satrt :: inquiry reassignment ************************* */

    public function getInquiryReassigned(Request $request) {
        $loadView = 'lead.showInquiryReassignment';
        return Common::getInquiryReassigned($request, $loadView);
    }

    public function setInquiryReassigned(Request $request) {
        return Common::setInquiryReassigned($request);
    }

    //************************ end :: inquiry reassignment ************************* */
}
