<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\MeasureUnit;
use App\Order;
use App\Lead;
use App\Brand;
use App\User;
use App\ProductPricingHistory;
use App\ProductTechDataSheet;
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

class OrderController extends Controller {

    private $fileSize = '10240';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $uniqueNoArr = Order::select('order_unique_no')->get();
        $statusArr = ['0' => __('label.SELECT_STATUS_OPT'), '1' => __('label.PENDING'), '2' => __('label.CANCELLED'), '3' => __('label.PROCESSING'), '4' => __('label.DELIVERED'), '5' => __('label.PAYMENT_DONE')];
        $lcDraftStatArr = ['0' => __('label.SELECT_LC_DRAFT_STATUS_OPT'), '1' => __('label.NO'), '2' => __('label.YES')];
        $lcTransCopyStatArr = ['0' => __('label.SELECT_LC_TRANSMITTED_COPY_STATUS_OPT'), '1' => __('label.NO'), '2' => __('label.YES')];

        $targetArr = Order::join('inquiry', 'inquiry.id', '=', 'order.lead_id')
                ->leftJoin('product', 'product.id', '=', 'inquiry.product_id')
                ->leftJoin('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->select('order.*', DB::raw("CONCAT(buyer.name, ' - ', date_format(inquiry.creation_date, '%d %M %Y'), ' - ', product.name) AS lead_name"));

        //begin filtering
        $orderUniqueNo = $request->order_unique_no;
        if (!empty($orderUniqueNo)) {
            $targetArr->where(function ($query) use ($orderUniqueNo) {

                $query->where('order.order_unique_no', 'LIKE', '%' . $orderUniqueNo . '%');
            });
        }
        if (!empty($request->lc_date)) {
            $targetArr = $targetArr->where('order.lc_date', $request->lc_date);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('order.status', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->orderBy('order.id', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/order?page=' . $page);
        }
        return view('order.index')->with(compact('qpArr', 'targetArr', 'uniqueNoArr', 'statusArr', 'lcDraftStatArr', 'lcTransCopyStatArr'));
    }

    public function filter(Request $request) {
        $url = 'order_unique_no=' . $request->order_unique_no . '&status=' . $request->status
                . '&lc_date=' . $request->lc_date;
        return Redirect::to('order?' . $url);
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $leadArr = array('0' => __('label.SELECT_LEAD_OPT')) + Lead::leftJoin('product', 'product.id', '=', 'inquiry.product_id')
                        ->leftJoin('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->select(DB::raw("CONCAT(buyer.name, ' - ', date_format(inquiry.creation_date, '%d %M %Y'), ' - ', product.name) AS lead_name"), 'inquiry.id')
                        ->orderBy('lead_name', 'asc')->where('inquiry.status', '2')
                        ->pluck('lead_name', 'inquiry.id')->toArray();

        return view('order.create')->with(compact('qpArr', 'leadArr'));
    }

    public function loadLcValueToCreate(Request $request) {
        return Common::loadLcValue($request);
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        //validation
        $rules = [
            'order_unique_no' => 'required|unique:order',
            'lead_id' => 'required|not_in:0|unique:order',
            'lc_value' => 'required',
            'lc_no' => 'required|unique:order',
            'lc_date' => 'required',
            'express_tracking_no' => 'required|unique:order',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('order/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Order;
        $target->order_unique_no = $request->order_unique_no;
        $target->lead_id = $request->lead_id;
        $target->lc_value = $request->lc_value;
        $target->lc_no = $request->lc_no;
        $target->lc_date = $request->lc_date;
        $target->express_tracking_no = $request->express_tracking_no;
        $target->note = $request->note;

        if (!empty($request->lc_draft_done)) {
            $target->lc_draft_done = $request->lc_draft_done;
        } else {
            $target->lc_draft_done = '0';
        }

        if (!empty($request->lc_transmitted_copy_done)) {
            $target->lc_transmitted_copy_done = $request->lc_transmitted_copy_done;
        } else {
            $target->lc_transmitted_copy_done = '0';
        }

        if ($target->save()) {
            Session::flash('success', __('label.ORDER_CREATED_SUCCESSFULLY'));
            return redirect('order');
        } else {
            Session::flash('error', __('label.ORDER_COULD_NOT_BE_CREATED'));
            return redirect('order/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Order::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('order');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $leadArr = array('0' => __('label.SELECT_LEAD_OPT')) + Lead::leftJoin('product', 'product.id', '=', 'inquiry.product_id')
                        ->leftJoin('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->select(DB::raw("CONCAT(buyer.name, ' - ', date_format(inquiry.creation_date, '%d %M %Y'), ' - ', product.name) AS lead_name"), 'inquiry.id')
                        ->orderBy('lead_name', 'asc')->where('inquiry.status', '2')
                        ->pluck('lead_name', 'inquiry.id')->toArray();
        return view('order.edit')->with(compact('qpArr', 'target', 'leadArr'));
    }

    public function loadLcValueToEdit(Request $request) {
        return Common::loadLcValue($request);
    }

    public function update(Request $request, $id) {

        $target = Order::find($id);
//        echo '<pre>';
//                var_dump($request->all());exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //validation
        $rules = [
            'order_unique_no' => 'required|unique:order,order_unique_no,' . $id,
            'lead_id' => 'required|not_in:0|unique:order,lead_id,' . $id,
            'lc_value' => 'required',
            'lc_no' => 'required|unique:order,lc_no,' . $id,
            'lc_date' => 'required',
            'express_tracking_no' => 'required|unique:order,express_tracking_no,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('order/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target->order_unique_no = $request->order_unique_no;
        $target->lead_id = $request->lead_id;
        $target->lc_value = $request->lc_value;
        $target->lc_no = $request->lc_no;
        $target->lc_date = $request->lc_date;
        $target->express_tracking_no = $request->express_tracking_no;
        $target->note = $request->note;

        if (!empty($request->lc_draft_done)) {
            $target->lc_draft_done = $request->lc_draft_done;
        } else {
            $target->lc_draft_done = '0';
        }

        if (!empty($request->lc_transmitted_copy_done)) {
            $target->lc_transmitted_copy_done = $request->lc_transmitted_copy_done;
        } else {
            $target->lc_transmitted_copy_done = '0';
        }



        if ($target->save()) {
            Session::flash('success', __('label.ORDER_UPDATED_SUCCESSFULLY'));
            return redirect('order' . $pageNumber);
        } else {
            Session::flash('error', __('label.ORDER_COULD_NOT_BE_UPDATED'));
            return redirect('order/' . $id . '/edit' . $pageNumber);
        }
    }

    public function cancel(Request $request) {
        $target = Order::find($request->order_id);

        $target->status = '2';
        
        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_CANCELLED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_COULD_NOT_BE_CANCELLED')), 401);
        }
    }

    public function getOrderDetails(Request $request) {
        $orderInfo = Order::join('inquiry', 'inquiry.id', '=', 'order.lead_id')
                        ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('product', 'product.id', '=', 'inquiry.product_id')
                        ->join('brand', 'brand.id', '=', 'product.brand_id')
                        ->select('order.*', 'buyer.name as buyer_name'
                                , 'product.name as product_name'
                                , 'brand.name as brand_name')
                        ->where('order.id', $request->order_id)->first();

        $view = view('order.showOrderDetails', compact('request', 'orderInfo'))->render();
        return response()->json(['html' => $view]);
    }

}
