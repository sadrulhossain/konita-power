<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\MeasureUnit;
use App\Lead;
use App\Inquiry;
use App\Brand;
use App\Supplier;
use App\Delivery;
use App\Bank;
use App\User;
use App\CommissionSetup;
use App\Buyer;
use App\InquiryDetails;
use App\Grade;
use App\DeliveryDetails;
use App\CompanyInformation;
use App\Country;
use App\SalesPersonToProduct;
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

class SalesStatusReportController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        //sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        //endof arr


        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('users.allowed_for_sales', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;




        //product list
        $productIdArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

        $productList = Product::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $productList = $productList->whereIn('id', $productIdArr);
        }
        $productList = $productList->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productList;

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();

        //BUYER LIST

        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::join('inquiry', 'inquiry.buyer_id', '=', 'buyer.id')
                        ->pluck('buyer.name', 'buyer.id')->toArray();

        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::join('inquiry', 'inquiry.supplier_id', '=', 'supplier.id')
                        ->pluck('supplier.name', 'supplier.id')->toArray();

        $targetArr = [];
        $rowspanArr = [];

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();


        $upcomingSalesVolume = $upcomingSalesAmount = $pipeLineSalesVolume = $pipeLineSalesAmount = 0;
        $confirmedSalesVolume = $confirmedSalesAmount = $accomplishedSalesVolume = $accomplishedSalesAmount = 0;
        $deliveryArr = [];

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        if ($request->generate == 'true') {
            $fromDate = $toDate = '';
            if (!empty($request->creation_from_date)) {
                $fromDate = Helper::dateFormatConvert($request->creation_from_date);
            }
            if (!empty($request->creation_to_date)) {
                $toDate = Helper::dateFormatConvert($request->creation_to_date);
            }


            //inquiry Details
            $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                    ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                    ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                    ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                    ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                    ->whereIn('inquiry.status', ['1', '2'])
                    ->whereIn('inquiry.order_status', ['0', '1', '2', '3', '4'])
                    ->where('inquiry.creation_date', '>=', $fromDate)
                    ->where('inquiry.creation_date', '<=', $toDate);
            if (!empty($request->salespersons_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (!empty($request->buyer_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $request->buyer_id);
            }
            if (!empty($request->supplier_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.supplier_id', $request->supplier_id);
            }
            if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
                $inquiryDetails = $inquiryDetails->whereIn('inquiry.salespersons_id', $finalUserIdArr);
            }

            if (!empty($request->product_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry_details.product_id', $request->product_id);
            }
            if (!empty($request->brand_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry_details.brand_id', $request->brand_id);
            }
            $inquiryDetails = $inquiryDetails->select('inquiry_details.id as inquiry_details_id', 'inquiry_details.inquiry_id'
                            , 'inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                            , 'product.name as product_name', 'brand.name as brand_name'
                            , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                            , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                            , 'grade.name as grade_name')
                    ->get();




            $inquiryIdArr = [];
            if (!$inquiryDetails->isEmpty()) {
                foreach ($inquiryDetails as $item) {
                    $inquiryIdArr[$item->inquiry_id] = $item->inquiry_id;
                }
            }


            $targetArr = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                    ->whereIn('inquiry.status', ['1', '2'])
                    ->whereIn('inquiry.order_status', ['0', '1', '2', '3', '4'])
                    ->where('inquiry.creation_date', '>=', $fromDate)
                    ->where('inquiry.creation_date', '<=', $toDate);

            if (!empty($request->product_id) || !empty($request->brand_id)) {
                $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
            }
            if (!empty($request->salespersons_id)) {
                $targetArr = $targetArr->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (!empty($request->buyer_id)) {
                $targetArr = $targetArr->where('inquiry.buyer_id', $request->buyer_id);
            }
            if (!empty($request->supplier_id)) {
                $targetArr = $targetArr->where('inquiry.supplier_id', $request->supplier_id);
            }
            if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
                $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
            }
            $targetArr = $targetArr->select('inquiry.id', 'inquiry.order_no', 'inquiry.order_status', 'inquiry.purchase_order_no'
                            , 'buyer.name as buyerName', 'inquiry.pi_date', 'inquiry.status'
                            , 'inquiry.creation_date as inquiry_date', 'inquiry.supplier_id')
                    ->orderBy('inquiry.creation_date', 'desc');
            //begin filtering
            //end filtering
            $targetArr = $targetArr->get();


            //inquiry Details Arr
            $inquiryDetailsArr = [];
            if (!$inquiryDetails->isEmpty()) {
                foreach ($inquiryDetails as $item) {
                    $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                    $gsm = !empty($item->gsm) ? $item->gsm : 0;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['sales_volume'] = $item->quantity;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['sales_amount'] = $item->total_price;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
                }
            }

            //inquiry Details
            //START final targetArr
            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $key => $item) {
                    $targetArr[$key] = $item;
                    $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
                }
            }
//            echo '<pre>';
//            print_r($targetArr->toArray());
//            exit;
            //ENDOF final targetArr
            //START Rowspan Arr


            if (!empty($inquiryDetailsArr)) {
                foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                    foreach ($inquiryData as $productId => $productData) {
                        foreach ($productData as $brandId => $brandData) {
                            foreach ($brandData as $gradeId => $gradeData) {
                                foreach ($gradeData as $gsm => $item) {
                                    //rowspan for grade
                                    $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                    $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                    //rowspan for brand
                                    $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                    $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                    //rowspan for product
                                    $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                    $rowspanArr['product'][$inquiryId][$productId] += 1;
                                    //rowspan for inquiry
                                    $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                    $rowspanArr['inquiry'][$inquiryId] += 1;
                                }
                            }
                        }
                    }
                }
            }
            //ENDOF Rowspan Arr
            // SUMMARY

            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $target) {
                    foreach ($target->inquiryDetails as $productId => $productData) {
                        foreach ($productData as $brandId => $brandData) {
                            foreach ($brandData as $gradeId => $gradeData) {
                                foreach ($gradeData as $gsm => $item) {
                                    if ($target->status == '1') {
                                        $upcomingSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                        $upcomingSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    }
                                    if ($target->order_status == '1') {
                                        $pipeLineSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                        $pipeLineSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    } elseif ($target->order_status == '2' || $target->order_status == '3') {
                                        $confirmedSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                        $confirmedSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    } elseif ($target->order_status == '4') {
                                        $accomplishedSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                        $accomplishedSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //  ENDOF SUMMARY

            $deliveryInfoArr = Delivery::select('inquiry_id', 'bl_no', 'id', 'payment_status'
                            , 'buyer_payment_status', 'shipment_status')->get();


            if (!$deliveryInfoArr->isEmpty()) {
                foreach ($deliveryInfoArr as $item) {
                    $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                    $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                    $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                    $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                    $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                    $btnColor = 'purple';
                    $paymentStatus = 'Unpaid';
                    if ($item->buyer_payment_status == '1') {
                        $btnColor = 'green-seagreen';
                        $paymentStatus = 'Paid';
                    }

                    $status = 'Draft';
                    $icon = 'file-text';
                    $btnRounded = '';
                    if ($item->shipment_status == '2') {
                        $status = 'Shipped';
                        $icon = 'ship';
                        $btnRounded = 'btn-rounded';
                    }

                    $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                    $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                    $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                    $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                    $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
                }
            }

            //KONITA INFO
            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[51][6])) {
                return redirect('/dashboard');
            }
            return view('report.salesStatus.print.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr'
                                    , 'productList', 'brandList', 'salesPersonList', 'upcomingSalesVolume'
                                    , 'upcomingSalesAmount', 'pipeLineSalesVolume', 'pipeLineSalesAmount'
                                    , 'confirmedSalesVolume', 'confirmedSalesAmount', 'accomplishedSalesVolume'
                                    , 'accomplishedSalesAmount', 'buyerList', 'supplierList'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'deliveryArr'
                                    , 'userAccessArr'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[51][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.salesStatus.print.index', compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr'
                                    , 'productList', 'brandList', 'salesPersonList', 'upcomingSalesVolume'
                                    , 'upcomingSalesAmount', 'pipeLineSalesVolume', 'pipeLineSalesAmount'
                                    , 'confirmedSalesVolume', 'confirmedSalesAmount', 'accomplishedSalesVolume'
                                    , 'accomplishedSalesAmount', 'buyerList', 'supplierList'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'deliveryArr'
                                    , 'userAccessArr'))
                    ->setPaper('a3', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
//            return $pdf->download('sales_status_report.pdf');
            return $pdf->stream();
        } else {
            return view('report.salesStatus.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr'
                                    , 'productList', 'brandList', 'salesPersonList', 'upcomingSalesVolume'
                                    , 'upcomingSalesAmount', 'pipeLineSalesVolume', 'pipeLineSalesAmount'
                                    , 'confirmedSalesVolume', 'confirmedSalesAmount', 'accomplishedSalesVolume'
                                    , 'accomplishedSalesAmount', 'buyerList', 'supplierList', 'deliveryArr'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'creation_from_date' => 'required',
            'creation_to_date' => 'required',
        ];

        $messages = [
            'creation_from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'creation_to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'creation_from_date=' . $request->creation_from_date . '&creation_to_date=' . $request->creation_to_date
                . '&salespersons_id=' . $request->salespersons_id
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&buyer_id=' . $request->buyer_id . '&supplier_id=' . $request->supplier_id;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('salesStatusReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('salesStatusReport?generate=true&' . $url);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'report.salesStatus.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'report.salesStatus.print.shipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
