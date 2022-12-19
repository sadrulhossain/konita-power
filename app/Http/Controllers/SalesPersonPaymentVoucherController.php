<?php

namespace App\Http\Controllers;

use Validator;
use App\Receive;
use App\SalesPersonPayment;
use App\CompanyInformation;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Common;
use Response;
use Illuminate\Http\Request;

class SalesPersonPaymentVoucherController extends Controller {

    private $controller = 'SalesPersonPaymentVoucher';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $approvalStatusList = [
            '0' => __('label.SELECT_APPROVAL_STATUS_OPT')
            , '1' => __('label.PENDING_FOR_APPROVAL')
            , '2' => __('label.APPROVED')
        ];

        $salesPersonArr = SalesPersonPayment::join('users', 'users.id', '=', 'sales_person_payment.sales_person_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->pluck('name', 'users.id')->toArray();
        $salesPersonList = array('0' => __('label.SELECT_SALES_PERSON_OPT')) + $salesPersonArr;

        $targetArr = SalesPersonPayment::join('users', 'users.id', '=', 'sales_person_payment.sales_person_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select('sales_person_payment.*', 'designation.title as designation'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id, ')') AS name"))
                ->orderBy('created_at', 'desc');

        //begin filtering
        if (!empty($request->sales_person_id)) {
            $targetArr = $targetArr->where('sales_person_payment.sales_person_id', $request->sales_person_id);
        }
        $approvalStatus = $request->approval_status;
        if (!empty($approvalStatus)) {
            if($approvalStatus == '1'){
                $targetArr = $targetArr->where('sales_person_payment.approval_status', '0');
            } else if ($approvalStatus == '2'){
                $targetArr = $targetArr->where('sales_person_payment.approval_status', '1');
            }
            
        }
        //end filtering

        $targetArr = $targetArr->get();
        //$targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/salesPersonPaymentVoucher?page=' . $page);
        }

        return view('salesPersonPaymentVoucher.index')->with(compact('targetArr', 'qpArr', 'salesPersonList', 'approvalStatusList'));
    }

    public function filter(Request $request) {
        $url = 'sales_person_id=' . $request->sales_person_id . '&approval_status=' . $request->approval_status;
        return Redirect::to('salesPersonPaymentVoucher?' . $url);
    }

    public function voucherPrint(Request $request) {
        $target = SalesPersonPayment::join('users', 'users.id', '=', 'sales_person_payment.sales_person_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select('sales_person_payment.*', 'designation.title as designation'
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id, ')') AS name"))
                        ->where('sales_person_payment.id', $request->payment_id)->first();

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $userAccessArr = Common::userAccess();
        if (empty($userAccessArr[61][6])) {
            return redirect('/dashboard');
        }
        return view('salesPersonPaymentVoucher.print.index')->with(compact('request', 'target', 'konitaInfo'
                                , 'phoneNumber'));
    }
    
    public function approve(Request $request){
        $target = SalesPersonPayment::find($request->payment_id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_APPROVED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PAYMENT_COULD_NOT_BE_APPROVED')), 401);
        }
    }
    
    public function deny(Request $request){

        if (SalesPersonPayment::where('id', $request->payment_id)->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_DENIED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PAYMENT_COULD_NOT_BE_DENIED')), 401);
        }
    }

}
