<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFactory;
use App\User;
use App\SalesPersonToBuyer;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Lead;
use App\CompanyInformation;
use Response;
use Auth;
use DB;
use PDF;
use Redirect;
use Helper;
use Common;
use Session;
use Illuminate\Http\Request;

class SalesPersonToBuyerController extends Controller {

    public function index(Request $request) {

        $salesPersonArr = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_sales', '1')
                        ->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();


        $buyerArr = $buyerRelatedToSalesPerson = $unassignedBuyerArr = [];
        $dependentBuyerArr = $inactiveBuyerArr = [];


        if (!empty($request->get('sales_person_id'))) {
            // get buyers who are assigned
            $assignedBuyerArr = SalesPersonToBuyer::pluck('buyer_id')->toArray();
            if (!empty($assignedBuyerArr)) {
                $unassignedBuyerArr = Buyer::whereNotIn('id', $assignedBuyerArr)->pluck('id')->toArray();
            }

            //check logged in user's related products
            $userSalesPerson = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)
                            ->pluck('buyer_id', 'buyer_id')->toArray();

            $buyerArr = Buyer::select('buyer.id', 'buyer.name', 'buyer.logo');

            /*
             * if logged in user is not super admin,get all products
             * else, get only assigned product 
             */
            if (Auth::user()->group_id != 1) {
                $buyerArr = $buyerArr->whereIn('buyer.id', $userSalesPerson);
            }
            $buyerArr = $buyerArr->orderBy('buyer.name', 'asc')->get()->toArray();

            $relatedBuyerArr = SalesPersonToBuyer::select('buyer_id')
                            ->where('sales_person_id', $request->get('sales_person_id'))->get();


            $buyerRelatedToSalesPerson = [];
            if (!$relatedBuyerArr->isEmpty()) {
                foreach ($relatedBuyerArr as $relatedBuyer) {
                    $buyerRelatedToSalesPerson[$relatedBuyer->buyer_id] = $relatedBuyer->buyer_id;
                }
            }

            $inactiveBuyerArr = Buyer::where('status', '2')->pluck('id')->toArray();

            //dependency check
            //dependent on inquiry
            $inquiryRecord = Lead::select('buyer_id')->where('salespersons_id', $request->get('sales_person_id'))->get();

            if (!$inquiryRecord->isEmpty()) {
                foreach ($inquiryRecord as $inquiry) {
                    $dependentBuyerArr[$request->get('sales_person_id')][$inquiry->buyer_id] = $inquiry->buyer_id;
                }
            }

            //end :: dependency check
        }

        $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"), 'buyer_id')
                        ->groupBy('buyer_id')->pluck('no_of_sales_person', 'buyer_id')->toArray();

        return view('salesPersonToBuyer.index')->with(compact('salesPersonArr', 'buyerArr'
                                , 'buyerRelatedToSalesPerson', 'request', 'dependentBuyerArr'
                                , 'unassignedBuyerArr', 'inactiveBuyerArr', 'salesPersonToBuyerCountList'));
    }

    public function getBuyersToRelate(Request $request) {
        //check logged in user's related products
        $userSalesPerson = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)
                        ->pluck('buyer_id', 'buyer_id')->toArray();

        $buyerArr = Buyer::select('buyer.id', 'buyer.name', 'buyer.logo');

        /*
         * if logged in user is not super admin,get all products
         * else, get only assigned product 
         */
        if (Auth::user()->group_id != 1) {
            $buyerArr = $buyerArr->whereIn('buyer.id', $userSalesPerson);
        }
        $buyerArr = $buyerArr->orderBy('buyer.name', 'asc')->get();

        $relatedBuyerArr = SalesPersonToBuyer::select('buyer_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();


        $buyerRelatedToSalesPerson = [];
        if (!$relatedBuyerArr->isEmpty()) {
            foreach ($relatedBuyerArr as $relatedBuyer) {
                $buyerRelatedToSalesPerson[$relatedBuyer->buyer_id] = $relatedBuyer->buyer_id;
            }
        }

        $inactiveBuyerArr = Buyer::where('status', '2')->pluck('id')->toArray();

        //dependency check
        $dependentBuyerArr = [];

        //dependent on inquiry
        $inquiryRecord = Lead::select('buyer_id')->where('salespersons_id', $request->sales_person_id)->get();

        if (!$inquiryRecord->isEmpty()) {
            foreach ($inquiryRecord as $inquiry) {
                $dependentBuyerArr[$request->sales_person_id][$inquiry->buyer_id] = $inquiry->buyer_id;
            }
        }

        $unassignedBuyerArr = [];
        // get buyers who are assigned
        $assignedBuyerArr = SalesPersonToBuyer::pluck('buyer_id')->toArray();
        if (!empty($assignedBuyerArr)) {
            $unassignedBuyerArr = Buyer::whereNotIn('id', $assignedBuyerArr)->pluck('id')->toArray();
        }


        //end :: dependency check

        $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"), 'buyer_id')
                        ->groupBy('buyer_id')->pluck('no_of_sales_person', 'buyer_id')->toArray();

        $view = view('salesPersonToBuyer.showBuyers', compact('buyerArr', 'relatedBuyerArr'
                        , 'buyerRelatedToSalesPerson', 'request', 'dependentBuyerArr'
                        , 'unassignedBuyerArr', 'inactiveBuyerArr', 'salesPersonToBuyerCountList'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedBuyers(Request $request) {

        // Set Name of Selected Sales Person
        $salesPerson = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS name"), 'id')
                        ->where('id', $request->sales_person_id)->first();

        //get related factories
        $relatedBuyerArr = SalesPersonToBuyer::select('sales_person_to_buyer.*')
                        ->where('sales_person_id', $request->sales_person_id)->get();

        $buyerRelatedToSalesPerson = [];
        if (!$relatedBuyerArr->isEmpty()) {
            foreach ($relatedBuyerArr as $relatedBuyer) {
                $buyerRelatedToSalesPerson[$relatedBuyer->buyer_id] = $relatedBuyer->buyer_id;
            }
        }

        //unique buyer related to sales person
        $buyerArr = [];
        if (isset($buyerRelatedToSalesPerson)) {
            $buyerArr = Buyer::join('buyer_category', 'buyer_category.id', '=', 'buyer.buyer_category_id')
                            ->select('buyer.id', 'buyer.name', 'buyer.logo', 'buyer.code'
                                    , 'buyer.head_office_address', 'buyer_category.name as buyer_category_name'
                                    , 'buyer.contact_person_data')
                            ->whereIn('buyer.id', $buyerRelatedToSalesPerson)
                            ->orderBy('buyer_category.name', 'asc')
                            ->orderBy('buyer.name', 'asc')
                            ->get()->toArray();
        }

        $contactArr = [];
        if (!empty($buyerArr)) {
            foreach ($buyerArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
            }
        }

        $inactiveBuyerArr = Buyer::where('status', '2')->pluck('id')->toArray();


        $view = view('salesPersonToBuyer.showRelatedBuyers', compact('salesPerson'
                        , 'request', 'buyerArr', 'contactArr', 'inactiveBuyerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedBuyersPrint(Request $request, $id) {

        // Set Name of Selected Sales Person
        $salesPerson = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS name"), 'id')
                        ->where('id', $id)->first();

        //get related factories
        $relatedBuyerArr = SalesPersonToBuyer::select('sales_person_to_buyer.*')
                        ->where('sales_person_id', $id)->get();

        $buyerRelatedToSalesPerson = [];
        if (!$relatedBuyerArr->isEmpty()) {
            foreach ($relatedBuyerArr as $relatedBuyer) {
                $buyerRelatedToSalesPerson[$relatedBuyer->buyer_id] = $relatedBuyer->buyer_id;
            }
        }

        //unique buyer related to sales person
        $buyerArr = [];
        if (isset($buyerRelatedToSalesPerson)) {
            $buyerArr = Buyer::join('buyer_category', 'buyer_category.id', '=', 'buyer.buyer_category_id')
                            ->select('buyer.id', 'buyer.name', 'buyer.logo', 'buyer.code'
                                    , 'buyer.head_office_address', 'buyer_category.name as buyer_category_name'
                                    , 'buyer.contact_person_data')
                            ->whereIn('buyer.id', $buyerRelatedToSalesPerson)
                            ->orderBy('buyer_category.name', 'asc')
                            ->orderBy('buyer.name', 'asc')
                            ->get()->toArray();
        }

        $contactArr = [];
        if (!empty($buyerArr)) {
            foreach ($buyerArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        if ($request->view == 'print') {
            return view('salesPersonToBuyer.print.showRelatedBuyers')->with(compact('salesPerson'
                                    , 'request', 'buyerArr', 'contactArr', 'konitaInfo', 'phoneNumber'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('salesPersonToBuyer.print.showRelatedBuyers', compact('salesPerson'
                                    , 'request', 'buyerArr', 'contactArr', 'konitaInfo', 'phoneNumber'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
//            return $pdf->download('PO.pdf');
            return $pdf->stream();
        }
    }

    public function getUnassignedBuyers(Request $request) {
        // get buyers who are assigned
        $relatedBuyerArr = SalesPersonToBuyer::pluck('buyer_id')->toArray();

        $salesPersonArr = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_sales', '1')
                        ->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();

        //get unassigned buyers' list
        $buyerArr = [];
        if (isset($relatedBuyerArr)) {
            $buyerArr = Buyer::join('buyer_category', 'buyer_category.id', '=', 'buyer.buyer_category_id')
                            ->select('buyer.id', 'buyer.name', 'buyer.logo', 'buyer.code'
                                    , 'buyer.head_office_address', 'buyer_category.name as buyer_category_name'
                                    , 'buyer.contact_person_data')
                            ->whereNotIn('buyer.id', $relatedBuyerArr)
                            ->orderBy('buyer_category.name', 'asc')
                            ->orderBy('buyer.name', 'asc')
                            ->get()->toArray();
        }

        $contactArr = [];
        if (!empty($buyerArr)) {
            foreach ($buyerArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
            }
        }

        $inactiveBuyerArr = Buyer::where('status', '2')->pluck('id')->toArray();


        $view = view('salesPersonToBuyer.showUnassignedBuyers', compact('request'
                        , 'buyerArr', 'contactArr', 'inactiveBuyerArr', 'salesPersonArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getUnassignedBuyersPrint(Request $request) {
        // get buyers who are assigned
        $relatedBuyerArr = SalesPersonToBuyer::pluck('buyer_id')->toArray();

        //get unassigned buyers' list
        $buyerArr = [];
        if (isset($relatedBuyerArr)) {
            $buyerArr = Buyer::join('buyer_category', 'buyer_category.id', '=', 'buyer.buyer_category_id')
                            ->select('buyer.id', 'buyer.name', 'buyer.logo', 'buyer.code'
                                    , 'buyer.head_office_address', 'buyer_category.name as buyer_category_name'
                                    , 'buyer.contact_person_data')
                            ->whereNotIn('buyer.id', $relatedBuyerArr)
                            ->orderBy('buyer_category.name', 'asc')
                            ->orderBy('buyer.name', 'asc')
                            ->get()->toArray();
        }

        $contactArr = [];
        if (!empty($buyerArr)) {
            foreach ($buyerArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        if ($request->view == 'print') {
            return view('salesPersonToBuyer.print.showUnassignedBuyers')->with(compact('request', 'buyerArr'
                                    , 'contactArr', 'konitaInfo', 'phoneNumber'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('salesPersonToBuyer.print.showUnassignedBuyers', compact('request'
                                    , 'buyerArr', 'contactArr', 'konitaInfo', 'phoneNumber'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
//            return $pdf->download('unassigned_buyer_list.pdf');
            return $pdf->stream();
        }
    }

    public function relateSalesPersonToBuyer(Request $request) {
        

        $rules = [
            'sales_person_id' => 'required|not_in:0',
        ];
        $messages = [];
        if (empty($request->buyer)) {
            $rules['buyer'] = 'required';
            $messages['buyer.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BUYER');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $salesPersonToBuyer = [];
        $i = 0;
        if (!empty($request->buyer)) {
            foreach ($request->buyer as $buyerId) {
                //data entry to sales person to product table
                $salesPersonToBuyer[$i]['sales_person_id'] = $request->sales_person_id;
                $salesPersonToBuyer[$i]['buyer_id'] = $buyerId;
                $salesPersonToBuyer[$i]['business_status'] = '1';
                $salesPersonToBuyer[$i]['created_by'] = Auth::user()->id;
                $salesPersonToBuyer[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        if (empty($request->unassigned_list)) {
            SalesPersonToBuyer::where('sales_person_id', $request->sales_person_id)->delete();
        }

        if (SalesPersonToBuyer::insert($salesPersonToBuyer)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SALES_PERSON_HAS_BEEN_RELATED_TO_BUYER_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SALES_PERSON_TO_BUYER')), 401);
        }
    }

    public function getAssignSalesPerson(Request $request) {
        $buyerInfo = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $salesPersonInfoArr = User::join('designation', 'designation.id', 'users.designation_id')
                ->join('department', 'department.id', 'users.department_id')
                ->join('branch', 'branch.id', 'users.branch_id')
                ->select('users.photo', 'users.employee_id', 'designation.title as designation'
                        , 'department.name as department', 'branch.name as branch', 'users.phone'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name")
                        , 'users.id')
                ->where('users.status', '1')
                ->where('users.allowed_for_sales', '1')
                ->orderBy('department.order', 'asc')
                ->orderBy('designation.order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

        $view = view('report.buyerSummary.showAssignSalesPerson', compact('request', 'buyerInfo'
                        , 'salesPersonInfoArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function setAssignSalesPerson(Request $request) {

        $rules = [
            'buyer_id' => 'required|not_in:0',
        ];
        $messages = [];
        if (empty($request->sales_person)) {
            $rules['sales_person'] = 'required';
            $messages['sales_person.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_SALES_PERSON');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if (count($request->sales_person) > 2) {
            return Response::json(array('success' => false, 'message' => __('label.YOU_CANNOT_CHOOSE_MORE_THAN_2_SALES_PERSONS')), 401);
        }

        $salesPersonToBuyer = [];
        $i = 0;
        if (!empty($request->sales_person)) {
            if (count($request->sales_person) <= 2) {
                foreach ($request->sales_person as $salesPersonId) {
                    //data entry to sales person to product table
                    $salesPersonToBuyer[$i]['sales_person_id'] = $salesPersonId;
                    $salesPersonToBuyer[$i]['buyer_id'] = $request->buyer_id;
                    $salesPersonToBuyer[$i]['business_status'] = '1';
                    $salesPersonToBuyer[$i]['created_by'] = Auth::user()->id;
                    $salesPersonToBuyer[$i]['created_at'] = date('Y-m-d H:i:s');
                    $i++;
                }
            }
        }


        if (SalesPersonToBuyer::insert($salesPersonToBuyer)) {
            $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"))
                            ->where('buyer_id', $request->buyer_id)->first();
            $count = !empty($salesPersonToBuyerCountList->no_of_sales_person) ? $salesPersonToBuyerCountList->no_of_sales_person : 0;
            return Response::json(array('heading' => 'Success', 'message' => __('label.SALES_PERSON_HAS_BEEN_RELATED_TO_BUYER_SUCCESSFULLY')
                        , 'count' => $count, 'buyer' => $request->buyer_id), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SALES_PERSON_TO_BUYER')), 401);
        }
    }

    public function getRelatedSalesPersonList(Request $request) {
        $loadView = 'salesPersonToBuyer.showRelatedSalesPersonList';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

    public function getRelatedSalesPersonListPrint(Request $request) {
        $loadView = 'salesPersonToBuyer.print.showRelatedSalesPerson';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

}
