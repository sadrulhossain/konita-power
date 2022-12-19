<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\MeasureUnit;
use App\Order;
use App\Delivery;
use App\Lead;
use App\Brand;
use App\User;
use App\ProductPricingHistory;
use App\ProductTechDataSheet;
use App\CauseOfDeliveryFailure;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class DeliveryController extends Controller {

    private $fileSize = '10240';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderArr = ['0' => __('label.SELECT_ORDER_OPT')] + Order::orderBy('id', 'asc')->pluck('order_unique_no', 'id')->toArray();
        $statusArr = ['0' => __('label.SELECT_STATUS_OPT'), '1' => __('label.PROCESSING'), '2' => __('label.DELIVERED'), '3' => __('label.PAYMENT_DONE'), '4' => __('label.FAILED'), '5' => __('label.LOCKED')];
        $lcDocArr = ['0' => __('label.SELECT_LC_DOC_STATUS_OPT'), '1' => __('label.YES'), '2' => __('label.NO')];
        $shipmentDocArr = ['0' => __('label.SELECT_SHIPMENT_DOC_STATUS_OPT'), '1' => __('label.YES'), '2' => __('label.NO')];

        $targetArr = Delivery::join('order', 'order.id', '=', 'delivery.order_id')
                ->select('delivery.*', 'order.order_unique_no as order_no', 'order.lc_value as total_quantity');

        //begin filtering
        if (!empty($request->order_id)) {
            $targetArr = $targetArr->where('delivery.order_id', $request->order_id);
        }
        if (!empty($request->latest_shipment_date)) {
            $targetArr = $targetArr->where('delivery.latest_shipment_date', $request->latest_shipment_date);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('delivery.status', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->orderBy('order.id', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/delivery?page=' . $page);
        }
        return view('delivery.index')->with(compact('qpArr', 'targetArr', 'orderArr', 'statusArr', 'lcDocArr', 'shipmentDocArr'));
    }

    public function filter(Request $request) {
        $url = 'order_id=' . $request->order_id . '&status=' . $request->status . '&latest_shipment_date=' . $request->latest_shipment_date;
        return Redirect::to('delivery?' . $url);
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderArr = array('0' => __('label.SELECT_ORDER_OPT')) + Order::orderBy('id', 'asc')
                        ->whereIn('status', ['1', '3'])
                        ->pluck('order_unique_no', 'id')->toArray();

        return view('delivery.create')->with(compact('qpArr', 'orderArr'));
    }

    public function loadTotalQuantity(Request $request) {
        $order = order::select('lc_value')->where('id', $request->order_id)->first();

        //find remaining quantity of order
        $QuantitySum = Delivery::select(DB::raw("SUM(quantity) AS quantity_sum"))->where('order_id', $request->order_id)->where('status', '!=', '4')->first();
        $remainingQuantity = (!empty($order) ? $order->lc_value : 0) - (!empty($QuantitySum) ? $QuantitySum->quantity_sum : 0);

        $view = view('delivery.loadTotalQuantity', compact('order'))->render();
        $remainingQty = view('delivery.loadRemainingQuantity', compact('remainingQuantity'))->render();
        return response()->json(['html' => $view, 'remainingQty' => $remainingQty]);
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        //validation
        $rules = [
            'order_id' => 'required|not_in:0',
            'quantity' => 'required',
            'latest_shipment_date' => 'required',
            'notification_date' => 'required',
            'ets' => 'required',
            'ets_notification_date' => 'required',
            'eta' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('delivery/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Delivery;
        $target->order_id = $request->order_id;
        $target->quantity = $request->quantity;
        $target->latest_shipment_date = $request->latest_shipment_date;
        $target->notification_date = $request->notification_date;
        $target->ets = $request->ets;
        $target->ets_notification_date = $request->ets_notification_date;
        $target->eta = $request->eta;

        if (!empty($request->lc_doc)) {
            $target->lc_doc = $request->lc_doc;
        } else {
            $target->lc_doc = '0';
        }

        if (!empty($request->shipment_doc)) {
            $target->shipment_doc = $request->shipment_doc;
        } else {
            $target->shipment_doc = '0';
        }

        $order = Order::find($request->order_id);
        $order->status = '3';

        if ($target->save() && $order->save()) {
            Session::flash('success', __('label.DELIVERY_CREATED_SUCCESSFULLY'));
            return redirect('delivery');
        } else {
            Session::flash('error', __('label.DELIVERY_COULD_NOT_BE_CREATED'));
            return redirect('delivery/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Delivery::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('delivery');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $orderArr = array('0' => __('label.SELECT_ORDER_OPT')) + Order::orderBy('id', 'asc')
                        ->whereIn('status', ['1', '3'])
                        ->pluck('order_unique_no', 'id')->toArray();
        //find total quantity of order
        $totalQuantity = Order::select('lc_value')->where('id', $target->order_id)->first();

        //find remaining quantity of order
        $QuantitySum = Delivery::select(DB::raw("SUM(quantity) AS quantity_sum"))
                        ->where('order_id', $target->order_id)->where('status', '!=', '4')->first();
        $remainingQuantity = (!empty($totalQuantity) ? $totalQuantity->lc_value : 0) - (!empty($QuantitySum) ? $QuantitySum->quantity_sum : 0);

        return view('delivery.edit')->with(compact('qpArr', 'target', 'orderArr', 'totalQuantity', 'remainingQuantity'));
    }

    public function update(Request $request, $id) {
        $target = Delivery::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //validation
        //validation
        $rules = [
            'order_id' => 'required|not_in:0',
            'quantity' => 'required',
            'latest_shipment_date' => 'required',
            'notification_date' => 'required',
            'ets' => 'required',
            'ets_notification_date' => 'required',
            'eta' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('delivery/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target->order_id = $request->order_id;
        $target->quantity = $request->quantity;
        $target->latest_shipment_date = $request->latest_shipment_date;
        $target->notification_date = $request->notification_date;
        $target->ets = $request->ets;
        $target->ets_notification_date = $request->ets_notification_date;
        $target->eta = $request->eta;

        if (!empty($request->lc_doc)) {
            $target->lc_doc = $request->lc_doc;
        } else {
            $target->lc_doc = '0';
        }

        if (!empty($request->shipment_doc)) {
            $target->shipment_doc = $request->shipment_doc;
        } else {
            $target->shipment_doc = '0';
        }

        if ($target->save()) {
            Session::flash('success', __('label.DELIVERY_UPDATED_SUCCESSFULLY'));
            return redirect('delivery' . $pageNumber);
        } else {
            Session::flash('error', __('label.DELIVERY_COULD_NOT_BE_UPDATED'));
            return redirect('delivery/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Delivery::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update



        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        if ($target->delete()) {
            $order = Order::find($target->order_id);
            $hasDelivery = Delivery::where('order_id', $target->order_id)->where('status', '!=', '4')->get();
            if ($hasDelivery->isEmpty()) {
                $order->status = '1';
            }
            $order->save();
            Session::flash('error', __('label.DELIVERY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.DELIVERY_COULD_NOT_BE_DELETED'));
        }
        return redirect('delivery' . $pageNumber);
    }

    public function lock(Request $request) {
        $target = Delivery::find($request->delivery_id);
        $target->status = '5';

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.DELIVERY_LOCKED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.DELIVERY_COULD_NOT_BE_LOCKED')), 401);
        }
    }

    public function getFailedDelivery(Request $request) {
        $deliveryInfo = Delivery::select('id', 'status')->where('id', $request->delivery_id)->first();
        $failureCauseArr = ['0' => __('label.SELECT_CAUSE_OPT')] + CauseOfDeliveryFailure::orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        $view = view('delivery.showMarkFailedDelivery', compact('request', 'deliveryInfo', 'failureCauseArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function markFailedDelivery(Request $request) {
        $rules = [
            'failure_cause_id' => 'required|not_in:0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $delivery = Delivery::find($request->delivery_id);
        $delivery->status = '4';
        $delivery->failure_cause_id = $request->failure_cause_id;

        if ($delivery->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.DELIVERY_MARKED_AS_FAILURE')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_MARK_DELIVERY_AS_FAILURE')), 401);
        }
    }

    public function getDeliveryDetails(Request $request) {
        $deliveryInfo = Delivery::join('order', 'order.id', '=', 'delivery.order_id')
                        ->join('lead', 'lead.id', '=', 'order.lead_id')
                        ->join('buyer', 'buyer.id', '=', 'lead.buyer_id')
                        ->join('product', 'product.id', '=', 'lead.product_id')
                        ->join('brand', 'brand.id', '=', 'product.brand_id')
                        ->select('delivery.*', 'order.order_unique_no as order_no', 'order.lc_value as total_quantity'
                                , 'buyer.name as buyer_name', 'product.name as product_name'
                                , 'brand.name as brand_name')
                        ->where('delivery.id', $request->delivery_id)->first();

        $view = view('delivery.showDeliveryDetails', compact('request', 'deliveryInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function deliver(Request $request) {
        $target = Delivery::find($request->delivery_id);
        $target->status = '2';

        //find total quantity of order
        $totalQuantity = Order::select('lc_value')->where('id', $target->order_id)->first();

        //Helper::pr($fanalDelivery, 1);

        if ($target->save()) {
            //find remaining quantity of order
            $QuantitySum = Delivery::select(DB::raw("SUM(quantity) AS quantity_sum"))
                            ->where('order_id', $target->order_id)->where('status', '2')->first();
            $remainingQuantity = (!empty($totalQuantity) ? $totalQuantity->lc_value : 0) - (!empty($QuantitySum) ? $QuantitySum->quantity_sum : 0);


            $finalDelivery = Delivery::where('order_id', $target->order_id)->where('status', '2')
                            ->pluck('final_delivery', 'id')->toArray();
            $order = Order::find($target->order_id);

            if ($remainingQuantity <= 0 || (!empty($finalDelivery) && in_array('1', $finalDelivery))) {
                $order->status = '4';
            }
            $order->save();

            return Response::json(array('heading' => 'Success', 'message' => __('label.DELIVERY_COMPLETED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_COMPLETE_DELIVERY')), 401);
        }
    }

}
