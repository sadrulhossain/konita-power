<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Buyer;
use App\OrderMessaging;
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

class BuyerMessagingController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        
        $orderNoList = ['0' => __('label.SELECT_ORDER_NO_OPT')] + OrderMessaging::join('inquiry', 'inquiry.id', 'order_messaging.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3'])
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();

        $messageTypeList = [
            '0' => __('label.ALL_TYPES'),
            '1' => __('label.COMMON_MESSAGE'),
            '2' => __('label.ORDER_BASED_MESSAGE'),
        ];

        $buyerMessagingInfo = OrderMessaging::join('buyer', 'buyer.id', 'order_messaging.buyer_id')
                ->leftJoin('inquiry', 'inquiry.id', 'order_messaging.inquiry_id')
                ->select('order_messaging.inquiry_id', 'order_messaging.buyer_id', 'order_messaging.history'
                        , 'order_messaging.buyer_read', 'buyer.name as buyer', 'inquiry.order_no as order_no')
//                ->whereIn('inquiry.order_status', ['2', '3'])
                ->orderBy('order_messaging.updated_at', 'desc');

        if (!empty($request->inquiry_id)) {
            $buyerMessagingInfo = $buyerMessagingInfo->where('order_messaging.inquiry_id', $request->inquiry_id);
        }
        if (!empty($request->message_type)) {
            if ($request->message_type == '1') {
                $buyerMessagingInfo = $buyerMessagingInfo->where('order_messaging.inquiry_id', 0);
            } elseif ($request->message_type == '2') {
                $buyerMessagingInfo = $buyerMessagingInfo->where('order_messaging.inquiry_id', '<>', 0);
            }
        }
        $buyerMessagingInfo = $buyerMessagingInfo->get();

        $buyerMessagingArr = [];
        if (!$buyerMessagingInfo->isEmpty()) {
            $i = 1;
            foreach ($buyerMessagingInfo as $info) {
                $historyArr = !empty($info->history) ? json_decode($info->history, true) : [];
                $lastMessage = end($historyArr);
                $buyerMessagingArr[$i] = $info->toArray();
                $buyerMessagingArr[$i]['message'] = $lastMessage['message'];
                $buyerMessagingArr[$i]['user_group_id'] = $lastMessage['user_group_id'];
                $buyerMessagingArr[$i]['updated_at'] = $lastMessage['updated_at'];
                $buyerMessagingArr[$i]['updated_by'] = $lastMessage['updated_by'];
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
        

        return view('buyerMessaging.index')->with(compact('request', 'orderNoList', 'messageTypeList'
                                , 'qpArr', 'userArr', 'buyerMessagingArr'));
    }

    public function filter(Request $request) {
        $url = 'inquiry_id=' . $request->inquiry_id . '&message_type=' . $request->message_type;
        return Redirect::to('buyerMessaging?' . $url);
    }

    //start messaging
    public function getOrderMessaging(Request $request) {
        $loadView = 'buyerMessaging.showOrderMessaging';
        return Common::getOrderMessaging($request, $loadView);
    }

    public function setMessage(Request $request) {
        $loadView = 'buyerMessaging.showMessagebody';
        return Common::setMessage($request, $loadView);
    }

    //end messaging
}
