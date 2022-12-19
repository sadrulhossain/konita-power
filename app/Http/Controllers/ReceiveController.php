<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Supplier;
use App\User;
use App\SupplierToProduct;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Lead;
use App\InquiryDetails;
use App\Delivery;
use App\Invoice;
use App\InvoiceCommissionHistory;
use App\Receive;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Helper;
use Illuminate\Http\Request;

class ReceiveController extends Controller {

    public function create(Request $request) {
        $supplierArr = Invoice::join('supplier', 'supplier.id', '=', 'invoice.supplier_id')
                        ->where('payment_status', '0')->where('approval_status', '1')
                        ->pluck('supplier.name', 'supplier.id')->toArray();
        $supplierList = array('0' => __('label.SELECT_SUPPLIER_OPT')) + $supplierArr;

        return view('receive.create')->with(compact('supplierList'));
    }

    //receive data against invoice
    public function getReceiveData(Request $request) {
        $receivedAmountHistoryArr = Receive::select('invoice_id', 'inquiry_id', 'delivery_id', 'collection_amount')
                        ->where('supplier_id', $request->supplier_id)->get();

        $invoiceCollection = $blCollection = [];
        if (!$receivedAmountHistoryArr->isEmpty()) {
            foreach ($receivedAmountHistoryArr as $amount) {
                $invoiceCollection[$amount->invoice_id]['received'] = $invoiceCollection[$amount->invoice_id]['received'] ?? 0;
                $invoiceCollection[$amount->invoice_id]['received'] += $amount->collection_amount;


                $blCollection[$amount->invoice_id][$amount->inquiry_id][$amount->delivery_id]['received'] = $blCollection[$amount->invoice_id][$amount->inquiry_id][$amount->delivery_id]['received'] ?? 0;
                $blCollection[$amount->invoice_id][$amount->inquiry_id][$amount->delivery_id]['received'] += $amount->collection_amount;

                $totalCommission = $deliveryDetailsArr[$amount->invoice_id][$amount->inquiry_id][$amount->delivery_id]['total_konita_commission'] ?? 0;
            }
        }

        //get invoice wise commission history of inquiry
        $invoiceCommissionHistoryArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', '=', 'invoice_commission_history.invoice_id')
                ->join('inquiry', 'inquiry.id', '=', 'invoice_commission_history.inquiry_id')
                ->join('delivery', 'delivery.id', '=', 'invoice_commission_history.delivery_id')
                ->select('invoice.invoice_no', 'invoice.net_receivable', 'inquiry.order_no', 'delivery.bl_no'
                        , 'invoice_commission_history.*', 'invoice.total_amount')
                ->where('invoice.supplier_id', $request->supplier_id)
                ->where('invoice.payment_status', '0')->where('invoice.approval_status', '1')
                ->get();

        $invoiceDetailsArr = $inquiryDetailsArr = $deliveryDetailsArr = [];
        $invoiceRowSpan = $inquiryRowSpan = $inquiryRowSpan2 = [];
        if (!$invoiceCommissionHistoryArr->isEmpty()) {
            foreach ($invoiceCommissionHistoryArr as $history) {
                $invoiceDetailsArr[$history->invoice_id]['invoice_no'] = $history->invoice_no;
                $invoiceDetailsArr[$history->invoice_id]['total_billed'] = $history->total_amount;
                $invoiceDetailsArr[$history->invoice_id]['billed'] = $history->net_receivable;
                $inquiryDetailsArr[$history->invoice_id][$history->inquiry_id]['order_no'] = $history->order_no;

                $invoiceCollection[$history->invoice_id]['due'] = $history->net_receivable - ($invoiceCollection[$history->invoice_id]['received'] ?? 0);


                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['bl_no'] = $history->bl_no;

                //bl wise total sales person commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_sales_person_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_sales_person_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_sales_person_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_sales_person_commission'] += $history->total_sales_person_cmsn;

                //bl wise total company commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_company_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_company_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_company_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_company_commission'] += $history->total_company_konita_cmsn;

                //bl wise total buyer commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_buyer_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_buyer_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_buyer_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_buyer_commission'] += $history->total_buyer_cmsn;

                //bl wise total rebate commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_rebate_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_rebate_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_rebate_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_rebate_commission'] += $history->total_rebate_cmsn;

                //bl wise total principle commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_principle_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_principle_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_principle_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_principle_commission'] += $history->total_principle_cmsn;

                //bl wise total konita commission
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_konita_commission'] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_konita_commission']) ? $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_konita_commission'] : 0;
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_konita_commission'] += $history->total_konita_cmsn;

                //bl wise payment
                $totalKonitaCommission = $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_konita_commission'];

                $blCollection[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['due'] = $totalKonitaCommission - ($blCollection[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['received'] ?? 0);
                $blCollection[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['disabled'] = $blCollection[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['due'] == 0 ? 'disabled' : '';

                $totalPrincipleCommission = $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['total_principle_commission'];
                $deliveryDetailsArr[$history->invoice_id][$history->inquiry_id][$history->delivery_id]['billed'] = $totalKonitaCommission - $totalPrincipleCommission;

                $inquiryRowSpan[$history->invoice_id][$history->inquiry_id] = !empty($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id]) ? count($deliveryDetailsArr[$history->invoice_id][$history->inquiry_id]) : 1;
                $invoiceRowSpan[$history->invoice_id] = array_sum($inquiryRowSpan[$history->invoice_id]);
            }
        }



        $view = view('receive.showReceiveData', compact('request', 'invoiceDetailsArr', 'inquiryDetailsArr'
                        , 'deliveryDetailsArr', 'invoiceRowSpan', 'inquiryRowSpan', 'invoiceCollection', 'blCollection'))->render();
        return response()->json(['html' => $view]);
    }

    //preview payment receive data
    public function previewReceiveData(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        //validation
        $rules = $message = [];
        $rules = [
            'supplier_id' => 'required|not_in:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $inv = [];
        if (count(array_filter($request->invoice_collection_amount)) == 0) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => [__('label.PLEASE_INSERT_COLLECTION_AMOUNT_TO_ATLEAST_ONE_INVOICE')]), 400);
        } else {
            foreach ($request->invoice_collection_amount as $invoiceId => $invoice) {
                if (!empty($invoice) && empty($request->full_pay[$invoiceId])) {
                    $invoiceNo = $request->invoice_no[$invoiceId] ?? '';

                    if (!empty($request->collection_amount[$invoiceId])) {
                        foreach ($request->collection_amount[$invoiceId] as $inquiryId => $inquiry) {
                            foreach ($inquiry as $deliveryId => $collectionAmount) {
                                $orderNo = $request->order_no[$inquiryId] ?? '';
                                $blNo = $request->bl_no[$deliveryId] ?? '';

                                $inv[$invoiceId][] = $collectionAmount;
                                $due = $request->due[$invoiceId][$inquiryId][$deliveryId] ?? 0.00;

                                if ($collectionAmount > $due) {
                                    $message[$invoiceId . '.' . $inquiryId . '.' . $deliveryId] = __('label.SHIPMENT_COLLECTION_AMOUNT_OF_THIS_BL_OF_THIS_INQUIRY_THIS_INVOICE_MUST_NOT_BE_GREATED_THAN_DUE_AMOUNT', ['invoice_no' => $invoiceNo, 'order_no' => $orderNo, 'bl_no' => $blNo]);
                                }
                            }
                        }
                    }

                    //if bl total collection doesn't match invoice collection
                    if ($invoice != strval(array_sum($inv[$invoiceId]))) {

                        $message[$invoiceId] = __('label.TOTAL_COLLECTION_AMOUNT_OF_ALL_SHIPMENT_IN_THIS_INVOICE_MUST_BE_EQUAL_TO_INVOICE_COLLECTION_AMOUNT', ['invoice_no' => $invoiceNo]);
                    }

                    if (count(array_filter($inv[$invoiceId])) == 0) {
                        $message[$invoiceId] = __('label.PLEASE_INSERT_COLLECTION_AMOUNT_TO_ATLEAST_ONE_SHIPMENT_OF_THIS_INVOICE_NO', ['invoice_no' => $invoiceNo]);
                    }
                    //if invoice collection is greater than
                    if ($invoice > $request->invoice_due[$invoiceId]) {
                        $message[$invoiceId] = __('label.INVOICE_COLLECTION_AMOUNT_OF_THIS_INVOICE_MUST_NOT_BE_GREATED_THAN_INVOICE_DUE_AMOUNT', ['invoice_no' => $invoiceNo]);
                    }
                }
            }

            if (!empty($message)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $message), 400);
            }
        }
        
        $i = 0;
        $receive = $receiveList = $receiveList2 = $receiveList3 = $invoiceRowSpan = $inquiryRowSpan = [];
        if (count(array_filter($request->invoice_collection_amount)) != 0) {
            foreach ($request->invoice_collection_amount as $invoiceId => $invoiceCollection) {
                if (!empty($invoiceCollection) && $invoiceCollection != 0) {
                    if (!empty($request->collection_amount[$invoiceId])) {
                        foreach ($request->collection_amount[$invoiceId] as $inqiuryId => $inquiry) {
                            if (!empty($inquiry)) {
                                foreach ($inquiry as $deliveryId => $collectionAmount) {
                                    if (!empty($collectionAmount) && $collectionAmount != 0) {
                                        $tKC = $request->total_konita_commission[$invoiceId][$inqiuryId][$deliveryId];
                                        $tSC = $request->total_sales_person_commission[$invoiceId][$inqiuryId][$deliveryId];
                                        $tCC = $request->total_company_commission[$invoiceId][$inqiuryId][$deliveryId];
                                        $tBC = $request->total_buyer_commission[$invoiceId][$inqiuryId][$deliveryId];
                                        $tRC = $request->total_rebate_commission[$invoiceId][$inqiuryId][$deliveryId];
                                        $tPC = $request->total_principle_commission[$invoiceId][$inqiuryId][$deliveryId];

                                        if ($tKC != 0) {
                                            $salesPersonCommission = ($tSC * $collectionAmount) / $tKC;
                                            $commpanyCommission = ($tCC * $collectionAmount) / $tKC;
                                            $buyerCommission = ($tBC * $collectionAmount) / $tKC;
                                            $rebateCommission = ($tRC * $collectionAmount) / $tKC;
                                            $principleCommission = ($tPC * $collectionAmount) / $tKC;

                                            $receiveList[$invoiceId]['invoice_no'] = $request->invoice_no[$invoiceId];
                                            $receiveList2[$invoiceId][$inqiuryId]['order_no'] = $request->order_no[$inqiuryId];

                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['bl_no'] = $request->bl_no[$deliveryId];
                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['collection_amount'] = $collectionAmount;

                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['sales_person_commission'] = $salesPersonCommission;
                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['company_commission'] = $commpanyCommission;
                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['buyer_commission'] = $buyerCommission;
                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['rebate_commission'] = $rebateCommission;
                                            $receiveList3[$invoiceId][$inqiuryId][$deliveryId]['princple_commission'] = $principleCommission;


                                            $inquiryRowSpan[$invoiceId][$inqiuryId] = !empty($receiveList3[$invoiceId][$inqiuryId]) ? count($receiveList3[$invoiceId][$inqiuryId]) : 1;
                                            $invoiceRowSpan[$invoiceId] = array_sum($inquiryRowSpan[$invoiceId]);

                                            $receive[$i]['supplier_id'] = $request->supplier_id;
                                            $receive[$i]['invoice_id'] = $invoiceId;
                                            $receive[$i]['inquiry_id'] = $inqiuryId;
                                            $receive[$i]['delivery_id'] = $deliveryId;
                                            $receive[$i]['company_commission'] = $commpanyCommission;
                                            $receive[$i]['sales_person_commission'] = $salesPersonCommission;
                                            $receive[$i]['buyer_commission'] = $buyerCommission;
                                            $receive[$i]['rebate_commission'] = $rebateCommission;
                                            $receive[$i]['principle_commission'] = $principleCommission;
                                            $receive[$i]['collection_amount'] = $collectionAmount;
                                            $receive[$i]['created_at'] = date('Y-m-d H:i:s');
                                            $receive[$i]['created_by'] = Auth::user()->id;
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $supplier = Supplier::select('name')->where('id', $request->supplier_id)->first();

        $receive = json_encode($receive);

        $view = view('receive.showReceivePreview', compact('request', 'supplier', 'receive', 'receiveList'
                        , 'receiveList2', 'receiveList3', 'invoiceRowSpan', 'inquiryRowSpan'))->render();
        return response()->json(['html' => $view]);
    }

    public function setReceiveData(Request $request) {
        $receive = json_decode($request->receive, true);



        if (Receive::insert($receive)) {
            $receivedAmountHistoryArr = Receive::join('invoice', 'invoice.id', '=', 'receive.invoice_id')
                            ->select('receive.invoice_id', 'receive.inquiry_id', 'receive.delivery_id'
                                    , 'receive.collection_amount', 'invoice.net_receivable')
                            ->where('receive.supplier_id', $request->supplier_id)->get();

            $invoiceCollection = $blCollection = $yes = [];
            if (!$receivedAmountHistoryArr->isEmpty()) {
                foreach ($receivedAmountHistoryArr as $amount) {
                    $invoiceCollection[$amount->invoice_id]['received'] = $invoiceCollection[$amount->invoice_id]['received'] ?? 0;
                    $invoiceCollection[$amount->invoice_id]['received'] += $amount->collection_amount;
                    $invoiceCollection[$amount->invoice_id]['billed'] = $amount->net_receivable;
                    $received = Helper::numberFormat2Digit($invoiceCollection[$amount->invoice_id]['received']);
                    $billed = Helper::numberFormat2Digit($amount->net_receivable);
                    $invoiceCollection[$amount->invoice_id]['due'] = $amount->net_receivable - $invoiceCollection[$amount->invoice_id]['received'];
                    $due = $invoiceCollection[$amount->invoice_id]['due'];

                    if ($due < 0.01) {
//                        $yes[$amount->invoice_id] = 'yes';
                        Invoice::where('id', $amount->invoice_id)->update(['payment_status' => '1']);
                    }
                }
            }

            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_RECEIVED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RECEIVE_PAYMENT')), 401);
        }
    }

}
