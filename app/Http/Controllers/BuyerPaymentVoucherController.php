<?php

namespace App\Http\Controllers;

use Validator;
use App\Receive;
use App\BuyerPayment;
use App\CompanyInformation;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Common;
use Response;
use Illuminate\Http\Request;

class BuyerPaymentVoucherController extends Controller {

    private $controller = 'BuyerPaymentVoucher';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $approvalStatusList = [
            '0' => __('label.SELECT_APPROVAL_STATUS_OPT')
            , '1' => __('label.PENDING_FOR_APPROVAL')
            , '2' => __('label.APPROVED')
        ];

        $buyerArr = BuyerPayment::join('buyer', 'buyer.id', '=', 'buyer_payment.buyer_id')
                        ->orderBy('buyer.name', 'asc')
                        ->pluck('buyer.name', 'buyer.id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerArr;

        $targetArr = BuyerPayment::join('buyer', 'buyer.id', '=', 'buyer_payment.buyer_id')
                ->select('buyer_payment.*', DB::raw("CONCAT(buyer.name, ' (', buyer.code, ')') AS name"))
                ->orderBy('created_at', 'desc');

        //begin filtering
        if (!empty($request->buyer_id)) {
            $targetArr = $targetArr->where('buyer_payment.buyer_id', $request->buyer_id);
        }

        $approvalStatus = $request->approval_status;
        if (isset($approvalStatus)) {
            if ($approvalStatus == '1') {
                $targetArr = $targetArr->where('buyer_payment.approval_status', '0');
            } else if ($approvalStatus == '2') {
                $targetArr = $targetArr->where('buyer_payment.approval_status', '1');
            }
        }
        //end filtering

        //$targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        $targetArr = $targetArr->get();

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/buyerPaymentVoucher?page=' . $page);
        }

        return view('buyerPaymentVoucher.index')->with(compact('targetArr', 'qpArr', 'buyerList', 'approvalStatusList'));
    }

    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&approval_status=' . $request->approval_status;
        return Redirect::to('buyerPaymentVoucher?' . $url);
    }

    public function voucherPrint(Request $request) {
        $target = BuyerPayment::join('buyer', 'buyer.id', '=', 'buyer_payment.buyer_id')
                        ->select('buyer_payment.*', DB::raw("CONCAT(buyer.name, ' (', buyer.code, ')') AS name"))
                        ->where('buyer_payment.id', $request->payment_id)->first();

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $userAccessArr = Common::userAccess();
        if (empty($userAccessArr[64][6])) {
            return redirect('/dashboard');
        }
        return view('buyerPaymentVoucher.print.index')->with(compact('request', 'target', 'konitaInfo'
                                , 'phoneNumber'));
    }

    public function approve(Request $request) {
        $target = BuyerPayment::find($request->payment_id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_APPROVED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PAYMENT_COULD_NOT_BE_APPROVED')), 401);
        }
    }

    public function deny(Request $request) {

        if (BuyerPayment::where('id', $request->payment_id)->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_DENIED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PAYMENT_COULD_NOT_BE_DENIED')), 401);
        }
    }

}
