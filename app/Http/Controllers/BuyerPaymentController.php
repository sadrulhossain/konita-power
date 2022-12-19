<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Supplier;
use App\Buyer;
use App\ContactDesignation;
use App\SupplierToProduct;
use App\BuyerToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Lead;
use App\InquiryDetails;
use App\Delivery;
use App\DeliveryDetails;
use App\Invoice;
use App\InvoiceCommissionHistory;
use App\BuyerPayment;
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

class BuyerPaymentController extends Controller {

    public function create(Request $request) {
        $buyerArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->orderBy('buyer.name', 'asc')
                        ->pluck('buyer.name', 'buyer.id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerArr;

        return view('buyerPayment.create')->with(compact('buyerList'));
    }

    //get payment status
    public function getPayment(Request $request) {

        $commissionInfoArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                ->select(DB::raw("SUM(receive.buyer_commission) as total_commission"))
                ->where('inquiry.buyer_id', $request->buyer_id)
                ->first();

        $paymentInfoArr = BuyerPayment::select(DB::raw("SUM(amount) as total_paid"))
                ->where('buyer_id', $request->buyer_id)
                ->where('approval_status', '1')
                ->first();
        $hasPendingPayment = BuyerPayment::where('approval_status', '0')->where('buyer_id', $request->buyer_id)->pluck('id')->toArray();

        $commission = !empty($commissionInfoArr->total_commission) ? $commissionInfoArr->total_commission : 0.00;
        $paid = !empty($paymentInfoArr->total_paid) ? $paymentInfoArr->total_paid : 0.00;
        $commissionDue = $commission - $paid;

        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $contactPersonInfo = Buyer::find($request->buyer_id);
        $contactPersonArr = json_decode($contactPersonInfo->contact_person_data, true);

        $buyerContactPersonArr = [];
        if (!empty($contactPersonArr)) {
            foreach ($contactPersonArr as $key => $item) {
                $designation = !empty($designationArr[$item['designation_id']]) ? ' (' . $designationArr[$item['designation_id']] . ')' : '';
                $name = $item['name'] . $designation;
                $buyerContactPersonArr[$key] = $name;
            }
        }

        $buyerContPersonList = array('0' => __('label.SELECT_BUYER_CONTACT_PERSON_OPT')) + $buyerContactPersonArr;


        $view = view('buyerPayment.showPayment', compact('request', 'commissionDue', 'buyerContPersonList', 'hasPendingPayment'))->render();
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
            'buyer_id' => 'required|not_in:0',
            'payment' => 'required',
            'buyer_contact_person' => 'required|not_in:0',
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

        $buyerInfo = Buyer::select(DB::raw("CONCAT(name, ' (', code, ')') AS name"), 'contact_person_data')
                        ->where('id', $request->buyer_id)->first();

        $buyerContactInfo = [];
        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $key = $request->buyer_contact_person;
        if (!empty($buyerInfo)) {
            $buyerContactPersonArr = json_decode($buyerInfo->contact_person_data, true);
            if (!empty($buyerContactPersonArr)) {
                if (array_key_exists($key, $buyerContactPersonArr)) {
                    $name = $buyerContactPersonArr[$key]['name'] ?? '';
                    $designationId = $buyerContactPersonArr[$key]['designation_id'] ?? 0;
                    $designation = !empty($designationArr[$designationId]) ? ' (' . $designationArr[$designationId] . ')' : '';
                    $name = !empty($buyerContactPersonArr[$key]['name']) ? $buyerContactPersonArr[$key]['name'] . $designation : __('label.N_A');
                    $phoneNoList = $buyerContactPersonArr[$key]['phone'];
                    if (is_array($phoneNoList)) {
                        foreach ($phoneNoList as $k => $pn) {
                            $phoneArr[] = $pn;
                        }
                        $phone = !empty($phoneArr) ? implode(",", $phoneArr) : __('label.N_A');
                    } else {
                        $phone = $phoneNoList ?? __('label.N_A');
                    }

                    $buyerContactInfo['name'] = $name;
                    $buyerContactInfo['phone'] = $phone;
                }
            }
        }

        $view = view('buyerPayment.showPreview', compact('request', 'buyerInfo', 'konitaInfo'
                        , 'phoneNumber', 'netDue', 'buyerContactInfo'))->render();
        return response()->json(['html' => $view]);
    }

    //set payment
    public function setPayment(Request $request) {
        //validation
        $rules = $message = [];
        $rules = [
            'buyer_id' => 'required|not_in:0',
            'payment' => 'required',
            'buyer_contact_person' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $buyerInfo = Buyer::select(DB::raw("CONCAT(name, ' (', code, ')') AS name"), 'contact_person_data')
                        ->where('id', $request->buyer_id)->first();

        $buyerContactInfo = [];
        $designationArr = ContactDesignation::pluck('name', 'id')->toArray();
        $key = $request->buyer_contact_person;
        if (!empty($buyerInfo)) {
            $buyerContactPersonArr = json_decode($buyerInfo->contact_person_data, true);
            if (!empty($buyerContactPersonArr)) {
                if (array_key_exists($key, $buyerContactPersonArr)) {
                    $name = $buyerContactPersonArr[$key]['name'] ?? '';
                    $designationId = $buyerContactPersonArr[$key]['designation_id'] ?? 0;
                    $designation = !empty($designationArr[$designationId]) ? ' (' . $designationArr[$designationId] . ')' : '';
                    $name = !empty($buyerContactPersonArr[$key]['name']) ? $buyerContactPersonArr[$key]['name'] . $designation : '';
                    $phoneNoList = $buyerContactPersonArr[$key]['phone'];
                    if (is_array($phoneNoList)) {
                        foreach ($phoneNoList as $k => $pn) {
                            $phoneArr[] = $pn;
                        }
                        $phone = !empty($phoneArr) ? implode(",", $phoneArr) : __('label.N_A');
                    } else {
                        $phone = $phoneNoList ?? '';
                    }

                    $buyerContactInfo['name'] = $name;
                    $buyerContactInfo['phone'] = $phone;
                }
            }
        }

        $contactName = $buyerContactInfo['name'] ?? '';
        $contactNumber = $buyerContactInfo['phone'] ?? '';

        $buyerPayment = new BuyerPayment;
        $buyerPayment->buyer_id = $request->buyer_id;
        $buyerPayment->amount = $request->payment;
        $buyerPayment->commission_due = $request->commission_due;
        $buyerPayment->net_due = $request->net_due;
        $buyerPayment->buyer_contact_person = $contactName;
        $buyerPayment->buyer_contact_number = $contactNumber;
        $buyerPayment->remarks = $request->remarks;
        $buyerPayment->approval_status = '0';
        $buyerPayment->created_at = date('Y-m-d H:i:s');
        $buyerPayment->created_by = Auth::user()->id;
        if ($buyerPayment->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_COMPLETED_SUCCESSFULLY')
                        , 'buyerId' => $request->buyer_id, 'commissionDue' => $request->commission_due
                        , 'payment' => $request->payment, 'remarks' => urlencode($request->remarks)
                        , 'contactName' => urlencode($contactName), 'contactNumber' => urlencode($contactNumber)), 201);
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

        $buyerInfo = Buyer::select(DB::raw("CONCAT(buyer.name, ' (', buyer.code, ')') AS name"))
                        ->where('buyer.id', $request->buyer_id)->first();

        $userAccessArr = Common::userAccess();
        if (empty($userAccessArr[63][6])) {
            return redirect('/dashboard');
        }
        return view('buyerPayment.print')->with(compact('request', 'buyerInfo', 'konitaInfo'
                                , 'phoneNumber', 'netDue'));
    }

}
