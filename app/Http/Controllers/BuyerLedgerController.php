<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Invoice;
use App\Receive;
use App\BuyerPayment;
use App\CompanyInformation;
use App\Lead;
use App\Delivery;
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

class BuyerLedgerController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        //sales person list
        $buyerArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->orderBy('buyer.name', 'asc')
                        ->pluck('buyer.name', 'buyer.id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerArr;
        

        //end :: supplier list
        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info

        $ledgerArr = [];
        $balanceArr = [];
        $previousBalance = 0;
        $totalBilled = 0;
        $totalReceived = 0;
        $totalBalance = 0;

        $supplierCode = '';


        $blNoList = Delivery::where('payment_status', '2')
                        ->pluck('bl_no', 'id')->toArray();

        $orderNoList = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->pluck('inquiry.order_no', 'receive.delivery_id')->toArray();

        if ($request->generate == 'true') {
            $buyerId = $request->buyer_id;
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';

            //billed info
            $billedInfo = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                    ->join('delivery', 'delivery.id', '=', 'receive.delivery_id')
                    ->select(DB::raw("SUM(receive.buyer_commission) as total_commission"), 'receive.created_at'
                            , 'receive.delivery_id')
                    ->groupBy('receive.created_at', 'receive.delivery_id')
                    ->where('inquiry.buyer_id', $buyerId);

            if (!empty($fromDate) && !empty($toDate)) {
                $billedInfo = $billedInfo->whereBetween('receive.created_at', [$fromDate, $toDate]);
            }

            $billedInfo = $billedInfo->get();

            if (!$billedInfo->isEmpty()) {
                foreach ($billedInfo as $bill) {
                    $ledgerArr[$bill->created_at][$bill->delivery_id]['billed'] = $bill->total_commission;
                }
            }
            //end :: billed info
            //received info
            $receivedInfo = BuyerPayment::select('amount', 'created_at', 'remarks', 'buyer_contact_person', 'buyer_contact_number')
                    ->where('buyer_id', $buyerId)
                    ->where('approval_status', '1');

            if (!empty($fromDate) && !empty($toDate)) {
                $receivedInfo = $receivedInfo->whereBetween('created_at', [$fromDate, $toDate]);
            }

            $receivedInfo = $receivedInfo->get();

            if (!$receivedInfo->isEmpty()) {
                foreach ($receivedInfo as $receive) {
                    $ledgerArr[$receive->created_at]['payment']['received'] = $receive->amount;
                    $ledgerArr[$receive->created_at]['payment']['buyer_contact_person'] = $receive->buyer_contact_person;
                    $ledgerArr[$receive->created_at]['payment']['buyer_contact_number'] = $receive->buyer_contact_number;
                    $ledgerArr[$receive->created_at]['payment']['remarks'] = $receive->remarks;
                }
            }
            ksort($ledgerArr);
            //end :: received info
            //previous balance set
            if (!empty($fromDate)) {

                $previousBilledInfo = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.buyer_commission) as total_commission"))
                        ->where('inquiry.buyer_id', $buyerId)->where('receive.created_at', '<', $fromDate)
                        ->first();

                $receivedInfo = BuyerPayment::select(DB::raw('SUM(amount) as total_amount'), 'created_at')
                        ->groupBy('created_at')
                        ->where('buyer_id', $buyerId);
                $previousReceivedInfo = BuyerPayment::select(DB::raw('SUM(amount) as total_amount'))
                        ->where('buyer_id', $buyerId)->where('created_at', '<', $fromDate)
                        ->first();

                $previousBilled = !empty($previousBilledInfo->total_commission) ? $previousBilledInfo->total_commission : 0;
                $previousReceived = !empty($previousReceivedInfo->total_amount) ? $previousReceivedInfo->total_amount : 0;
                $previousBalance = $previousBilled - $previousReceived;
            }

            //end :: previous balance set
            //balance sheet
            if (!empty($ledgerArr)) {
                $balance = $previousBalance;
                foreach ($ledgerArr as $dateTime => $delivery) {
                    foreach ($delivery as $deliveryId => $amount) {
                        $billed = !empty($amount['billed']) ? $amount['billed'] : 0;
                        $received = !empty($amount['received']) ? $amount['received'] : 0;
                        $balance = $balance + $billed - $received;
                        $balanceArr[$dateTime][$deliveryId] = $balance;
                        $totalBilled += $billed;
                        $totalReceived += $received;
                        $totalBalance = $previousBalance + $totalBilled - $totalReceived;
                    }
                }
            }

            //end :: balance sheet
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[65][6])) {
                return redirect('dashboard');
            }
            return view('buyerLedger.print.index')->with(compact('buyerList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'blNoList', 'orderNoList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[65][9])) {
                return redirect('dashboard');
            }

            $pdf = PDF::loadView('buyerLedger.print.index', compact('buyerList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'blNoList', 'orderNoList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('PaymentLedger-' . $buyerId . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('buyerLedger.index')->with(compact('buyerList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'blNoList', 'orderNoList'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'buyer_id' => 'required|not_in:0',
        ];

        if (!empty($request->from_date)) {
            $rules['to_date'] = 'required';
            $messages['to_date.required'] = __('label.THE_TO_DATE_FIELD_IS_REQUIRED');
        }
        if (!empty($request->to_date)) {
            $rules['from_date'] = 'required';
            $messages['from_date.required'] = __('label.THE_FROM_DATE_FIELD_IS_REQUIRED');
        }

        $url = 'buyer_id=' . $request->buyer_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('buyerLedger?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('buyerLedger?generate=true&' . $url);
    }

    //get shipment detail 
    public function shipment(Request $request) {
        $loadView = 'buyerLedger.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
