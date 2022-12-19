<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier;
use App\Invoice;
use App\InvoiceCommissionHistory;
use App\Receive;
use App\SalesPersonPayment;
use App\CompanyInformation;
use App\Lead;
use App\Delivery;
use App\DeliveryDetails;
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

class PrincipalLedgerController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        //supplier list
        $supplierArr = Supplier::join('invoice', 'invoice.supplier_id', '=', 'supplier.id');
        $supplierCodeArr = $supplierArr->pluck('supplier.code', 'supplier.id')->toArray();
        $supplierArr = $supplierArr->pluck('supplier.name', 'supplier.id')->toArray();

        $supplierList = [0 => __('label.SELECT_SUPPLIER_OPT')] + $supplierArr;
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

        $invoiceNoList = Invoice::pluck('invoice_no', 'id')->toArray();

        if ($request->generate == 'true') {
            $supplierId = $request->supplier_id;
            $supplierCode = '-' . $supplierCodeArr[$supplierId];
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';

            //billed info
            $billedInfo = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                    ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                    ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as net_receivable')
                            , 'invoice_commission_history.invoice_id', 'invoice.updated_at')
                    ->groupBy('invoice.updated_at', 'invoice_commission_history.invoice_id')
                    ->where('inquiry.supplier_id', $supplierId);

            if (!empty($fromDate) && !empty($toDate)) {
                $billedInfo = $billedInfo->whereBetween('invoice.updated_at', [$fromDate, $toDate]);
            }

            $billedInfo = $billedInfo->get();

            if (!$billedInfo->isEmpty()) {
                foreach ($billedInfo as $bill) {
                    $ledgerArr[$bill->updated_at][$bill->invoice_id]['billed'] = $bill->net_receivable ?? 0.00;
                }
            }
            //end :: billed info
            //received info
            $receivedInfo = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                            ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                            ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as net_received')
                                    , 'invoice_commission_history.invoice_id', 'invoice.approved_at')
                            ->groupBy('invoice.approved_at', 'invoice_commission_history.invoice_id')
                            ->where('inquiry.supplier_id', $supplierId)->where('invoice.approval_status', '1');

            if (!empty($fromDate) && !empty($toDate)) {
                $receivedInfo = $receivedInfo->whereBetween('invoice.approved_at', [$fromDate, $toDate]);
            }

            $receivedInfo = $receivedInfo->get();

            if (!$receivedInfo->isEmpty()) {
                foreach ($receivedInfo as $receive) {
                    $ledgerArr[$receive->approved_at][$receive->invoice_id]['received'] = $receive->net_received ?? 0.00;
                }
            }
            ksort($ledgerArr);

            //end :: received info
            //previous balance set
            if (!empty($fromDate)) {
                $previousBilledInfo = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as total_billed'))
                        ->where('inquiry.supplier_id', $supplierId)->where('invoice.updated_at', '<', $fromDate)
                        ->first();
                $previousReceivedInfo = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as total_received'))
                        ->where('inquiry.supplier_id', $supplierId)->where('invoice.approval_status', '1')
                        ->where('invoice.approved_at', '<', $fromDate)->first();

                $previousBilled = !empty($previousBilledInfo->total_billed) ? $previousBilledInfo->total_billed : 0;
                $previousReceived = !empty($previousReceivedInfo->total_received) ? $previousReceivedInfo->total_received : 0;
                $previousBalance = $previousBilled - $previousReceived;
            }

            //end :: previous balance set
            //balance sheet
            if (!empty($ledgerArr)) {
                $balance = $previousBalance;
                foreach ($ledgerArr as $dateTime => $invoiceList) {
                    foreach ($invoiceList as $invoiceId => $amount) {
                        $billed = !empty($amount['billed']) ? $amount['billed'] : 0;
                        $received = !empty($amount['received']) ? $amount['received'] : 0;
                        $balance = $balance + $billed - $received;
                        $balanceArr[$dateTime][$invoiceId] = $balance;
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
            if (empty($userAccessArr[81][6])) {
                return redirect('dashboard');
            }
            return view('principalLedger.print.index')->with(compact('supplierList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'supplierCodeArr', 'invoiceNoList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[81][9])) {
                return redirect('dashboard');
            }

            $pdf = PDF::loadView('principalLedger.print.index', compact('supplierList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'supplierCodeArr', 'invoiceNoList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('PrincipalLedger-' . $supplierCode . '-' . date('YmdHis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('principalLedger.index')->with(compact('supplierList', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'totalBilled', 'totalReceived', 'request', 'konitaInfo'
                                    , 'phoneNumber', 'supplierCodeArr', 'invoiceNoList'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'supplier_id' => 'required|not_in:0',
        ];

        if (!empty($request->from_date)) {
            $rules['to_date'] = 'required';
            $messages['to_date.required'] = __('label.THE_TO_DATE_FIELD_IS_REQUIRED');
        }
        if (!empty($request->to_date)) {
            $rules['from_date'] = 'required';
            $messages['from_date.required'] = __('label.THE_FROM_DATE_FIELD_IS_REQUIRED');
        }

        $url = 'supplier_id=' . $request->supplier_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('principalLedger?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('principalLedger?generate=true&' . $url);
    }

}
