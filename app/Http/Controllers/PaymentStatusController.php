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
use App\DeliveryDetails;
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

class PaymentStatusController extends Controller {

    public function create(Request $request) {
        $supplierArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->where('delivery.shipment_status', '2')
                        ->where('delivery.payment_status', '1')
                        ->where('delivery.buyer_payment_status', '0')
                        ->pluck('supplier.name', 'supplier.id')->toArray();
        $supplierList = array('0' => __('label.SELECT_SUPPLIER_OPT')) + $supplierArr;

        return view('paymentStatus.create')->with(compact('supplierList'));
    }

    //get payment status
    public function getPaymentStatus(Request $request) {

        $deliveryDetailsInfoArr = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                ->join('inquiry_details', 'inquiry_details.id', '=', 'delivery_details.inquiry_details_id')
                ->join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.supplier_id', $request->supplier_id)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '1')
                ->where('delivery.buyer_payment_status', '0')
                ->where('delivery.shipment_status', '2')
                ->select('delivery_details.id as delivery_details_id', 'inquiry.id as inquiry_id'
                        , 'delivery.bl_no', 'delivery.id as delivery_id', 'inquiry.order_no'
                        , 'buyer.name as buyer_name', 'delivery_details.inquiry_details_id'
                        , 'delivery_details.shipment_quantity', 'inquiry_details.unit_price'
                        , 'measure_unit.name as unit_name', 'product.name as product_name'
                        , 'brand.name as brand_name', 'grade.name as grade_name'
                        , 'inquiry_details.quantity')
                ->get();

        $inquiryArr = $deliveryArr = $deliveryDetailsArr = [];
        $inquiryRowSpan = $deliveryRowSpan = [];
        if (!$deliveryDetailsInfoArr->isEmpty()) {
            foreach ($deliveryDetailsInfoArr as $item) {
                $inquiryArr[$item->inquiry_id]['order_no'] = $item->order_no;
                $inquiryArr[$item->inquiry_id]['buyer_name'] = $item->buyer_name;

                $deliveryArr[$item->inquiry_id][$item->delivery_id]['bl_no'] = $item->bl_no;

                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['product_name'] = $item->product_name;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['brand_name'] = $item->brand_name;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['grade_name'] = $item->grade_name ?? '';
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['unit_price'] = $item->unit_price;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['total_quantity'] = $item->quantity;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['shipment_quantity'] = $item->shipment_quantity;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['total_price'] = $item->unit_price * $item->shipment_quantity;
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['unit'] = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                $deliveryDetailsArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id]['per_unit'] = !empty($item->unit_name) ? ' /' . $item->unit_name : '';

                //define rowspan
                $deliveryRowSpan[$item->inquiry_id][$item->delivery_id] = !empty($deliveryDetailsArr[$item->inquiry_id][$item->delivery_id]) ? count($deliveryDetailsArr[$item->inquiry_id][$item->delivery_id]) : 1;
                $inquiryRowSpan[$item->inquiry_id] = array_sum($deliveryRowSpan[$item->inquiry_id]);
            }
        }

        $view = view('paymentStatus.showPaymentStatus', compact('request', 'inquiryArr', 'deliveryArr'
                        , 'deliveryDetailsArr', 'inquiryRowSpan', 'deliveryRowSpan'))->render();
        return response()->json(['html' => $view]);
    }

    //set payment stauts
    public function setPaymentStatus(Request $request) {
        //validation
        $rules = $message = [];
        $rules = [
            'supplier_id' => 'required|not_in:0'
        ];

        

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        
        if (empty($request->delivery)) {
            $message['delivery'] = __('label.PLEASE_CHOOSE_AT_LEAST_ONE_SHIPMENT_FOR_BUYER_PAYMENT_STATUS_CHANGE');
        }

        if (!empty($message)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $message), 400);
        }
        //end :: validation
        
        $deliveryIdArr = [];
        if(!empty($request->delivery)){
            foreach($request->delivery as $inquiryId => $delivery){
                foreach($delivery as $deliveryId => $status){
                    $deliveryIdArr[$deliveryId] = $deliveryId;
                }
            }
        }
        
        $paymentStatus = Delivery::whereIn('id', $deliveryIdArr)->update(['buyer_payment_status' => '1']);
        
        if($paymentStatus){
            return Response::json(array('heading' => 'Success', 'message' => __('label.PAYMENT_STATUS_OF_CHOSEN_SHIPMENT_S_UPDATED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_UPDATE_PAYMENT_STATUS_OF_CHOSEN_SHIPMENT_S')), 401);
        }
    }

}
