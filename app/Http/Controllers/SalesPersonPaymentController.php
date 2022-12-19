<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Supplier;
use App\User;
use App\SupplierToProduct;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Lead;
use App\InquiryDetails;
use App\Delivery;
use App\DeliveryDetails;
use App\Invoice;
use App\InvoiceCommissionHistory;
use App\SalesPersonPayment;
use App\Receive;
use App\CompanyInformation;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Helper;
use Common;
use Illuminate\Http\Request;

class SalesPersonPaymentController extends Controller {

    public function create(Request $request) {
        $salesPersonArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->pluck('name', 'users.id')->toArray();
        $salesPersonList = array('0' => __('label.SELECT_SALES_PERSON_OPT')) + $salesPersonArr;

        return view('salesPersonPayment.create')->with(compact('salesPersonList'));
    }

    //get payment status
    public function getPayment(Request $request) {

        $commissionInfoArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                ->select(DB::raw("SUM(receive.sales_person_commission) as total_commission"))
                ->where('inquiry.salespersons_id', $request->sales_person_id)
                ->first();

        $paymentInfoArr = SalesPersonPayment::select(DB::raw("SUM(amount) as total_paid"))
                ->where('sales_person_id', $request->sales_person_id)
                ->where('approval_status', '1')
                ->first();
        $hasPendingPayment = SalesPersonPayment::where('approval_status', '0')->where('sales_person_id', $request->sales_person_id)->pluck('id')->toArray();
        
        $commission = !empty($commissionInfoArr->total_commission) ? $commissionInfoArr->total_commission : 0.00;
        $paid = !empty($paymentInfoArr->total_paid) ? $paymentInfoArr->total_paid : 0.00;
        $commissionDue = $commission - $paid;


        $view = view('salesPersonPayment.showPayment', compact('request', 'commissionDue', 'hasPendingPayment'))->render();
        return response()->json(['html' => $view]);
    }

    //preview payment
    public function previewPayment(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        //validation
        $rules = $message = [];
        $rules = [
            'sales_person_id' => 'required|not_in:0',
            'payment' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $commissionDue = !empty($request->commission_due) ? $request->commission_due : 0;
        $payment = !empty($request->payment) ? $request->payment : 0;

        $netDue = $commissionDue - $payment;

        $salesPersonInfo = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id, ')') AS name")
                                , 'designation.title as designation')
                        ->where('users.id', $request->sales_person_id)->first();

        $view = view('salesPersonPayment.showPreview', compact('request', 'salesPersonInfo', 'konitaInfo'
                        , 'phoneNumber', 'netDue'))->render();
        return response()->json(['html' => $view]);
    }

    //set payment
    public function setPayment(Request $request) {
        //validation
        $rules = $message = [];
        $rules = [
            'sales_person_id' => 'required|not_in:0',
            'payment' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }



        $salesPersonPayment = new SalesPersonPayment;
        $salesPersonPayment->sales_person_id = $request->sales_person_id;
        $salesPersonPayment->amount = $request->payment;
        $salesPersonPayment->commission_due = $request->commission_due;
        $salesPersonPayment->net_due = $request->net_due;
        $salesPersonPayment->approval_status = '0';
        $salesPersonPayment->created_at = date('Y-m-d H:i:s');
        $salesPersonPayment->created_by = Auth::user()->id;
        if ($salesPersonPayment->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_COMPLETED_SUCCESSFULLY')
                        , 'salesPersonId' => $request->sales_person_id, 'commissionDue' => $request->commission_due
                        , 'payment' => $request->payment), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_COMPLETE_PAYMENT')), 401);
        }
    }

    //set payment with print
    public function setPaymentWithPrint(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        
        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        
        $commissionDue = !empty($request->commission_due) ? $request->commission_due : 0;
        $payment = !empty($request->payment) ? $request->payment : 0;

        $netDue = $commissionDue - $payment;

        $salesPersonInfo = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id, ')') AS name")
                                , 'designation.title as designation')
                        ->where('users.id', $request->sales_person_id)->first();
        
        $userAccessArr = Common::userAccess();
        if (empty($userAccessArr[60][6])) {
            return redirect('/dashboard');
        }
        return view('salesPersonPayment.print')->with(compact('request', 'salesPersonInfo', 'konitaInfo'
                                , 'phoneNumber', 'netDue'));
    }

}
