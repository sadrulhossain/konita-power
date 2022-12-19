<?php

namespace App\Http\Controllers;

use Validator;
use App\QuotationRequest;
use App\UserWiseQuotationReq;
use App\Buyer;
use App\BuyerToProduct;
use App\CompanyInformation;
use App\User;
use App\InquiryDetails;
use App\Product;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use DateTime;
use PDF;
use Illuminate\Http\Request;

class BuyerQuotationRequestController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT'),
            '1' => __('label.PENDING'),
            '2' => __('label.READ'),
        ];

        $buyerInfo = Buyer::where('buyer.user_id', Auth::user()->id)->first();
        $targetArr = QuotationRequest::join('buyer', 'buyer.id', '=', 'quotation_request.buyer_id')
                ->select('quotation_request.*', 'buyer.name as buyer_name', 'buyer.id as buyer_id')
                ->where('buyer_id', $buyerInfo->id)
//                ->orderBy('quotation_request.status', 'asc')
                ->orderBy('quotation_request.created_at', 'desc');

        if (!empty($request->status)) {
            if ($request->status == '1') {
                $targetArr = $targetArr->where('quotation_request.status', '0');
            } elseif ($request->status == '2') {
                $targetArr = $targetArr->where('quotation_request.status', '1');
            }
        }

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //$target  
        return view('buyerQuotationRequest.index')->with(compact('request', 'qpArr', 'targetArr', 'buyerInfo', 'statusList'));
    }

    public function filter(Request $request) {
        $url = 'status=' . $request->status;
        return Redirect::to('buyerQuotationRequest?' . $url);
    }

    //************************* Start :: quotation **************************//
    //load quotation page
    public function quotation(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info
        //buyer information
        $target = Buyer::join('buyer_category', 'buyer_category.id', 'buyer.buyer_category_id')
                        ->leftJoin('country', 'country.id', 'buyer.country_id')
                        ->leftJoin('division', 'division.id', 'buyer.division_id')
                        ->select('buyer.id', 'buyer.code', 'buyer.logo', 'buyer.status'
                                , 'buyer.name as buyer_name', 'buyer.head_office_address as address'
                                , 'buyer.created_at', 'buyer.show_all_brands')
                        ->where('buyer.user_id', Auth::user()->id)->first();

        $id = !empty($target->id) ? $target->id : 0;
        $buyer = $target->buyer_name;


        /* echo '<pre>';
          print_r($target->buyer);
          exit; */


        //Product List
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->join('country', 'country.id', 'brand.origin')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo', 'measure_unit.name as unit'
                                , 'country.name as country_of_origin', 'brand.certificate')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();


        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }
        /*         * **************************************** END:: From Buyer Profile ********************** */




        $productArr2 = [];

        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                if (!empty($target->show_all_brands)) {
                    $productArr2[$item->product_id] = $item->product_name;
                } else {
                    if (!empty($brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                            if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                                $productArr2[$item->product_id] = $item->product_name;
                            }
                        }
                    }
                }
            }
        }

        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr2;
        //Endof product list


        return view('buyerQuotationRequest.quotation')->with(compact('request', 'qpArr', 'target', 'konitaInfo', 'buyer'
                                , 'phoneNumber', 'productList'
                                , 'id'));
    }

    public function newProductRow(Request $request) {

        $v4 = 'np' . uniqid();
        $id = !empty($request->buyer_id) ? $request->buyer_id : 0;
        //buyer info
        $target = Buyer::select('id', 'name', 'show_all_brands')->where('buyer.user_id', Auth::user()->id)->first();
        //Product List
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->join('country', 'country.id', 'brand.origin')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo', 'measure_unit.name as unit'
                                , 'country.name as country_of_origin', 'brand.certificate')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();


        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }
        /*         * **************************************** END:: From Buyer Profile ********************** */




        $productArr2 = [];

        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                if (!empty($target->show_all_brands)) {
                    $productArr2[$item->product_id] = $item->product_name;
                } else {
                    if (!empty($brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                            if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                                $productArr2[$item->product_id] = $item->product_name;
                            }
                        }
                    }
                }
            }
        }

        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productArr2;
        //Endof product list
        $view = view('buyerQuotationRequest.newProductRow', compact('productList', 'v4'))->render();
        return response()->json(['html' => $view, 'v4' => $v4]);
    }

    public function getProductUnit(Request $request) {
        $productInfo = Product::join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->select('measure_unit.name')
                ->where('product.id', $request->product_id)
                ->first();


        $unit = !empty($productInfo->name) ? $productInfo->name : null;

        return response()->json(['unit' => $unit]);
    }

    /*     * *********************************** START:: Save Quotation Request ***************************** */

    public function quotationDataSave(Request $request) {

        $rules = $message = [];
        $rules = [
            'description' => 'required',
        ];
        /*         * **************************** Start:: Validation **************************** */
        if (!empty($request->select_product)) {
            if (!empty($request->product)) {
                $row = 1;
                foreach ($request->product as $pKey => $pInfo) {
                    $rules['product.' . $pKey . '.product_id'] = 'required|not_in:0';
                    $message['product.' . $pKey . '.product_id' . '.not_in'] = __('label.PRODUCT_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                    $row++;
                }
            }
        }



        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        /*         * **************************** END:: Validation **************************** */
        $productArr = [];

        if (!empty($request->select_product)) {
            if (!empty($request->product)) {
                foreach ($request->product as $pKey => $pInfo) {
                    if (count(array_filter($pInfo)) != 0) {
                        $productArr[$pKey]['product_id'] = $pInfo['product_id'];
                        $productArr[$pKey]['unit'] = $pInfo['unit'] ?? '';
                        $productArr[$pKey]['gsm'] = $pInfo['gsm'] ?? '0.00';
                        $productArr[$pKey]['quantity'] = $pInfo['quantity'] ?? '0.00';
                    }
                }
            }
        }

        $target = new QuotationRequest;
        $target->buyer_id = $request->buyer_id;
        $target->description = $request->description ?? '';
        $target->product_data = json_encode($productArr);
        $target->status = '0';

        $userArr = User::select('id')->where('allowed_to_view_quotation', '1')->get();


        $userWiseQuotationArr = [];
        $i = 1;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (!$userArr->isEmpty()) {
                    foreach ($userArr as $user) {
                        $userWiseQuotationArr[$i]['user_id'] = $user->id;
                        $userWiseQuotationArr[$i]['quotation_id'] = $target->id;
                        $userWiseQuotationArr[$i]['buyer_id'] = $request->buyer_id;
                        $userWiseQuotationArr[$i]['status'] = '0';
                        $userWiseQuotationArr[$i]['read_by'] = 0;
                        $userWiseQuotationArr[$i]['updated_at'] = date('Y-m-d H:i:s');
                        $userWiseQuotationArr[$i]['updated_by'] = Auth::user()->id;
                        $i++;
                    }
                    //echo '<pre>';print_r($userWiseQuotationArr);
                }
                //exit;
                UserWiseQuotationReq::insert($userWiseQuotationArr);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.QUOTATION_REQUEST_HAS_BEEN_SET_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_SET_QUOTATION')], 401);
        }
    }

    /*     * *********************************** END:: Save Quotation Request ***************************** */

    public function quotationReqDetails(Request $request, $id = null) {
        $quotationId = !empty($request->quotation_id) ? $request->quotation_id : 0;
        $buyerId = !empty($request->buyer_id) ? $request->buyer_id : 0;
        if ($request->view == 'print') {
            $quotationId = $id;
            $buyerIdData = QuotationRequest::select('buyer_id')->where('id', $quotationId)->first();
            $buyerId = $buyerIdData->buyer_id;
        }
        return Common::getDetails($request, $quotationId, $buyerId);
    }

}
