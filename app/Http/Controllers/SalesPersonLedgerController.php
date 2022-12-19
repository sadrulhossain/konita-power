<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Invoice;
use App\Receive;
use App\SalesPersonPayment;
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

class SalesPersonLedgerController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        //sales person list
        $salesPersonArr = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name")
                                , DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id, ')') AS full_name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc');
        $salesPersonNameArr = $salesPersonArr->pluck('full_name', 'users.id')->toArray();
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = array('0' => __('label.SELECT_SALES_PERSON_OPT')) + $salesPersonArr;


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
            $salesPersonId = $request->sales_person_id;
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';

            //billed info
            $billedInfo = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                    ->join('delivery', 'delivery.id', '=', 'receive.delivery_id')
                    ->select(DB::raw("SUM(receive.sales_person_commission) as total_commission"), 'receive.created_at'
                            , 'receive.delivery_id')
                    ->groupBy('receive.created_at', 'receive.delivery_id')
                    ->where('inquiry.salespersons_id', $salesPersonId);


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
            $receivedInfo = SalesPersonPayment::select(DB::raw('SUM(amount) as total_amount'), 'created_at')
                    ->groupBy('created_at')
                    ->where('sales_person_id', $salesPersonId)
                    ->where('approval_status', '1');

            if (!empty($fromDate) && !empty($toDate)) {
                $receivedInfo = $receivedInfo->whereBetween('created_at', [$fromDate, $toDate]);
            }

            $receivedInfo = $receivedInfo->get();

            if (!$receivedInfo->isEmpty()) {
                foreach ($receivedInfo as $receive) {
                    $ledgerArr[$receive->created_at]['payment']['received'] = $receive->total_amount;
                }
            }
            ksort($ledgerArr);
            //end :: received info
            //previous balance set
            if (!empty($fromDate)) {

                $previousBilledInfo = Receive::join('inquiry', 'inquiry.id', '=', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.sales_person_commission) as total_commission"))
                        ->where('inquiry.salespersons_id', $salesPersonId)->where('receive.created_at', '<', $fromDate)
                        ->first();

                $receivedInfo = SalesPersonPayment::select(DB::raw('SUM(amount) as total_amount'), 'created_at')
                        ->groupBy('created_at')
                        ->where('sales_person_id', $salesPersonId);
                $previousReceivedInfo = SalesPersonPayment::select(DB::raw('SUM(amount) as total_amount'))
                        ->where('sales_person_id', $salesPersonId)->where('created_at', '<', $fromDate)
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
            if (empty($userAccessArr[62][6])) {
                return redirect('dashboard');
            }
            return view('salesPersonLedger.print.index')->with(compact('salesPersonList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'salesPersonNameArr', 'blNoList', 'orderNoList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[62][9])) {
                return redirect('dashboard');
            }

            $pdf = PDF::loadView('salesPersonLedger.print.index', compact('salesPersonList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'salesPersonNameArr', 'blNoList', 'orderNoList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('PaymentLedger-' . $salesPersonId . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('salesPersonLedger.index')->with(compact('salesPersonList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'salesPersonNameArr', 'blNoList', 'orderNoList'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'sales_person_id' => 'required|not_in:0',
        ];

        if (!empty($request->from_date)) {
            $rules['to_date'] = 'required';
            $messages['to_date.required'] = __('label.THE_TO_DATE_FIELD_IS_REQUIRED');
        }
        if (!empty($request->to_date)) {
            $rules['from_date'] = 'required';
            $messages['from_date.required'] = __('label.THE_FROM_DATE_FIELD_IS_REQUIRED');
        }

        $url = 'sales_person_id=' . $request->sales_person_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('salesPersonLedger?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('salesPersonLedger?generate=true&' . $url);
    }

    //get shipment detail 
    public function shipment(Request $request) {
        $loadView = 'salesPersonLedger.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
