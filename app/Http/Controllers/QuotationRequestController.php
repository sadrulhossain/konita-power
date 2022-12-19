<?php

namespace App\Http\Controllers;

use Validator;
use App\QuotationRequest;
use App\UserWiseQuotationReq;
use App\Buyer;
use App\User;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use DateTime;
use PDF;
use Illuminate\Http\Request;

class QuotationRequestController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT'),
            '1' => __('label.PENDING'),
            '2' => __('label.READ'),
        ];


        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer:: where('status', '1')->pluck('name', 'id')->toArray();
        $targetArr = QuotationRequest::join('buyer', 'buyer.id', '=', 'quotation_request.buyer_id')
                ->join('user_wise_quotation_req', 'user_wise_quotation_req.quotation_id', '=', 'quotation_request.id')
                ->select('quotation_request.*', 'buyer.name as buyer_name', 'buyer.id as buyer_id', 'user_wise_quotation_req.status as read_status')
                ->where('user_wise_quotation_req.user_id', Auth::user()->id)
                ->orderBy('quotation_request.id', 'desc');

        if (!empty($request->buyer_id)) {
            $targetArr = $targetArr->where('quotation_request.buyer_id', $request->buyer_id);
        }

        if (!empty($request->status)) {
            if ($request->status == '1') {
                $targetArr = $targetArr->where('user_wise_quotation_req.status', '0');
            } elseif ($request->status == '2') {
                $targetArr = $targetArr->where('user_wise_quotation_req.status', '1');
            }
        }


        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        return view('quotationRequest.index')->with(compact('request', 'qpArr', 'targetArr', 'buyerList', 'statusList'));
    }

    public function buyerQuotationReqDetails(Request $request, $id = null) {

        $quotationId = !empty($request->quotation_id) ? $request->quotation_id : 0;
        $buyerId = !empty($request->buyer_id) ? $request->buyer_id : 0;
        $loadType = $request->view;
        if ($request->view == 'print') {
            $quotationId = $id;
            $buyerIdData = QuotationRequest::select('buyer_id')->where('id', $quotationId)->first();
            $buyerId = $buyerIdData->buyer_id;
        }
        return Common::getDetails($request, $quotationId, $buyerId);
    }

    public function markAsRead(Request $request) {
        $target = QuotationRequest::find($request->quotation_id);
        $target->status = '1';

        DB::beginTransaction();
        try {
            if ($target->save()) {
                UserWiseQuotationReq::where('user_id', Auth::user()->id)
                        ->where('buyer_id', $request->buyer_id)->where('quotation_id', $request->quotation_id)
                        ->update([
                            'status' => '1',
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->id
                ]);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.QUOTATION_REQUEST_HAS_BEEN_READ_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_SET_QUOTATION')], 401);
        }
    }

    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&status=' . $request->status;
        return Redirect::to('quotationRequest?' . $url);
    }

}
