<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Buyer;
use App\OrderMessaging;
use App\UserWiseBuyerMessage;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class BuyerMessageController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')->where('user_id', '<>', 0)
                        ->pluck('name', 'id')->toArray();

        $orderNoList = ['0' => __('label.SELECT_ORDER_NO_OPT')] + OrderMessaging::join('inquiry', 'inquiry.id', 'order_messaging.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3'])
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();

        $messageTypeList = [
            '0' => __('label.ALL_TYPES'),
            '1' => __('label.COMMON_MESSAGE'),
            '2' => __('label.ORDER_BASED_MESSAGE'),
        ];

        $buyerMessageInfo = UserWiseBuyerMessage::join('order_messaging', function($join) {
                    $join->on('order_messaging.buyer_id', '=', 'user_wise_buyer_message.buyer_id');
                    $join->on('order_messaging.inquiry_id', '=', 'user_wise_buyer_message.inquiry_id');
                })
                ->join('buyer', 'buyer.id', 'order_messaging.buyer_id')
                ->leftJoin('inquiry', 'inquiry.id', 'order_messaging.inquiry_id')
                ->select('order_messaging.inquiry_id', 'order_messaging.buyer_id', 'order_messaging.history'
                        , 'buyer.name as buyer', 'inquiry.order_no as order_no')
                ->where('user_wise_buyer_message.user_id', Auth::user()->id)
                ->orderBy('order_messaging.updated_at', 'desc');

        if (!empty($request->buyer_id)) {
            $buyerMessageInfo = $buyerMessageInfo->where('order_messaging.buyer_id', $request->buyer_id);
        }
        if (!empty($request->inquiry_id)) {
            $buyerMessageInfo = $buyerMessageInfo->where('order_messaging.inquiry_id', $request->inquiry_id);
        }
        if (!empty($request->message_type)) {
            if ($request->message_type == '1') {
                $buyerMessageInfo = $buyerMessageInfo->where('order_messaging.inquiry_id', 0);
            } elseif ($request->message_type == '2') {
                $buyerMessageInfo = $buyerMessageInfo->where('order_messaging.inquiry_id', '<>', 0);
            }
        }
        $buyerMessageInfo = $buyerMessageInfo->get();

        $buyerMessageArr = [];
        if (!$buyerMessageInfo->isEmpty()) {
            $i = 1;
            foreach ($buyerMessageInfo as $info) {
                $historyArr = !empty($info->history) ? json_decode($info->history, true) : [];
                $lastMessage = end($historyArr);
                $buyerMessageArr[$i] = $info->toArray();
                $buyerMessageArr[$i]['message'] = $lastMessage['message'];
                $buyerMessageArr[$i]['user_group_id'] = $lastMessage['user_group_id'];
                $buyerMessageArr[$i]['updated_at'] = $lastMessage['updated_at'];
                $buyerMessageArr[$i]['updated_by'] = $lastMessage['updated_by'];
                $i++;
            }
        }

        $userInfoArr = User::leftJoin('designation', 'designation.id', 'users.designation_id')
                ->select('users.first_name', 'users.last_name', 'users.id', 'users.photo'
                        , 'designation.title as designation')
                ->get();
        $userArr = [];
        if (!$userInfoArr->isEmpty()) {
            foreach ($userInfoArr as $user) {
                $fName = !empty($user->first_name) ? $user->first_name : '';
                $lName = !empty($user->last_name) ? $user->last_name : '';
                $fullName = $fName . ' ' . $lName;
                $userArr[$user->id]['full_name'] = $fullName;
                $userArr[$user->id]['photo'] = $user->photo;
                $userArr[$user->id]['designation'] = $user->designation;
            }
        }

        $hasUnreadMsg = UserWiseBuyerMessage::where('user_id', Auth::user()->id)
                        ->where('status', '0')->select(DB::raw("CONCAT(buyer_id, '_', inquiry_id) as m_key"))
                        ->pluck('m_key', 'm_key')->toArray();




        return view('buyerMessage.index')->with(compact('request', 'buyerList', 'orderNoList', 'messageTypeList'
                                , 'qpArr', 'userArr', 'buyerMessageArr', 'hasUnreadMsg'));
    }

    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&inquiry_id=' . $request->inquiry_id
                . '&message_type=' . $request->message_type;
        return Redirect::to('buyerMessage?' . $url);
    }

    //start messaging
    public function getOrderMessaging(Request $request) {
        $loadView = 'buyerMessage.showOrderMessaging';
        return Common::getOrderMessaging($request, $loadView);
    }

    public function setMessage(Request $request) {
        $loadView = 'buyerMessage.showMessagebody';
        return Common::setMessage($request, $loadView);
    }

    //end messaging
}
