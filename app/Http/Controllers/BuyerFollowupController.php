<?php

namespace App\Http\Controllers;

use Validator;
use App\Lead;
use App\InquiryDetails;
use App\Buyer;
use App\BuyerFollowUpHistory;
use App\User;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use DB;
use PDF;
use Illuminate\Http\Request;
use Helper;

//LEAD/INQUIRY Controller
class BuyerFollowupController extends Controller {

    public function index(Request $request) {
        $buyerArr = Buyer::where('status', '1')->pluck('name', 'id')->toArray();
        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerArr;

        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT')
            , '1' => __('label.NORMAL')
            , '2' => __('label.HAPPY')
            , '3' => __('label.UNHAPPY')
        ];
        $userArr = $finalArr = $followUpHistoryArr = [];
        if ($request->generate == 'true') {

            //get followup history
            $followUpPrevHistory = BuyerFollowUpHistory::where('buyer_id', $request->buyer_id)->first();


            if (!empty($followUpPrevHistory)) {
                $followUpHistoryArr = json_decode($followUpPrevHistory->history, true);
                krsort($followUpHistoryArr);
                $i = 0;
                if (!empty($followUpHistoryArr)) {
                    foreach ($followUpHistoryArr as $followUpHistory) {
                        $followUpDate = Helper::dateFormatConvert($followUpHistory['follow_up_date']);
                        $finalArr[$followUpDate][$i]['follow_up_date'] = $followUpHistory['follow_up_date'];
                        $finalArr[$followUpDate][$i]['status'] = $followUpHistory['status'];
                        $finalArr[$followUpDate][$i]['order_no'] = $followUpHistory['order_no'];
                        $finalArr[$followUpDate][$i]['remarks'] = $followUpHistory['remarks'];
                        $finalArr[$followUpDate][$i]['updated_by'] = $followUpHistory['updated_by'] ?? 0;
                        $finalArr[$followUpDate][$i]['updated_at'] = $followUpHistory['updated_at'] ?? '';
                        $i++;
                    }
                }
            }
            krsort($finalArr);



            $userInfoArr = User::select(DB::raw("CONCAT(first_name,' ', last_name) as full_name")
                            , 'employee_id', 'id', 'photo')->get();

            if (!$userInfoArr->isEmpty()) {
                foreach ($userInfoArr as $user) {
                    $userArr[$user->id]['full_name'] = $user->full_name;
                    $userArr[$user->id]['employee_id'] = $user->employee_id;
                    $userArr[$user->id]['photo'] = $user->photo;
                }
            }
        }

        return view('buyerFollowup.index')->with(compact('request', 'buyerList'
                                , 'statusList', 'finalArr', 'userArr'));
    }

    public function filter(Request $request) {
        $rules = [
            'buyer_id' => 'required|not_in:0',
        ];
        $url = 'buyer_id=' . $request->buyer_id;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('buyerFollowup?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }
        return Redirect::to('buyerFollowup?generate=true&' . $url);
    }

    public function getAddFollowup(Request $request) {
        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT')
            , '1' => __('label.NORMAL')
            , '2' => __('label.HAPPY')
            , '3' => __('label.UNHAPPY')
        ];
        $buyerInfo = Buyer::where('id', $request->buyer_id)->select('name')->first();

        $orderNoList = Lead::where('buyer_id', $request->buyer_id)
                        ->whereIn('order_status', ['2', '3', '4'])
                        ->pluck('order_no', 'order_no')->toArray();
        $orderNoList = ['0' => __('label.SELECT_ORDER_NO_OPT')] + $orderNoList;
        
        $view = view('buyerFollowup.showSetFollowup', compact('request', 'statusList', 'orderNoList'
                        , 'buyerInfo'))->render();

        return response()->json(['html' => $view]);
    }
    
     public function setAddFollowup(Request $request) {
        //validation
        $rules = $message = [];
        $rules = [
            'follow_up_date' => 'required',
            'status' => 'required|not_in:0',
            'remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end of validation
        //create follow up history
        $historyData = [];
        $uniqId = uniqid();
        
        $orderNo = !empty($request->order_no) && $request->order_no != '0' ? $request->order_no : ''; 

        //create new follow up array
        $historyData[$uniqId]['follow_up_date'] = $request->follow_up_date;
        $historyData[$uniqId]['status'] = $request->status;
        $historyData[$uniqId]['order_no'] = $request->order_no;
        $historyData[$uniqId]['remarks'] = $request->remarks;
        $historyData[$uniqId]['updated_by'] = Auth::user()->id;
        $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');


        //merge with previous history and pack in json
        $followUpHistory = BuyerFollowUpHistory::where('buyer_id', $request->buyer_id)->first();

        if (!empty($followUpHistory)) {
            $preHistoryArr = json_decode($followUpHistory->history, true);
            $historyArr = array_merge($preHistoryArr, $historyData);
        } else {
            $followUpHistory = new BuyerFollowUpHistory;
            $historyArr = $historyData;
        }


        $followUpHistory->buyer_id = $request->buyer_id;
        $followUpHistory->history = json_encode($historyArr);
        $followUpHistory->updated_at = date('Y-m-d H:i:s');
        $followUpHistory->updated_by = Auth::user()->id;


        if ($followUpHistory->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.FOLLOW_UP_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FOLLOW_UP_COULD_NOT_BE_CREATED')], 401);
        }
    }

}
