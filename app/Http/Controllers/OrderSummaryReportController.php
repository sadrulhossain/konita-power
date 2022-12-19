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
use App\SalesPersonToBuyer;
use App\BuyerToProduct;
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

class OrderSummaryReportController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;

        //sales person access system arr
        $userIdArr = SalesPersonToBuyer::where('buyer_id', $id)->pluck('sales_person_id')->toArray();
        $userIdArr2 = User::where('group_id', 1)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        //endof arr


        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.allowed_for_sales', '1')
                        ->whereIn('users.id', $finalUserIdArr)
                        ->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;




        //product list
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->where('buyer_to_product.buyer_id', $id)->orderBy('product.name', 'asc')
                        ->pluck('product.name', 'product.id')->toArray();

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + BuyerToProduct::join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->where('buyer_to_product.buyer_id', $id)->orderBy('brand.name', 'asc')
                        ->pluck('brand.name', 'brand.id')->toArray();

        //supplier list
        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::join('inquiry', 'inquiry.supplier_id', '=', 'supplier.id')
                        ->where('inquiry.buyer_id', $id)
                        ->pluck('supplier.name', 'supplier.id')->toArray();

        $targetArr = [];
        $rowspanArr = [];

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();


        $purchaseSummary = $deliveryArr = [];

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        if ($request->generate == 'true') {
            $fromDate = $toDate = '';
            if (!empty($request->pi_from_date)) {
                $fromDate = Helper::dateFormatConvert($request->pi_from_date);
            }
            if (!empty($request->pi_to_date)) {
                $toDate = Helper::dateFormatConvert($request->pi_to_date);
            }


            //inquiry Details
            $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                    ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                    ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                    ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                    ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                    ->where('inquiry.buyer_id', $id)
                    ->whereIn('inquiry.order_status', ['2', '3', '4', '6'])
                    ->where('inquiry.pi_date', '>=', $fromDate)
                    ->where('inquiry.pi_date', '<=', $toDate);
            if (!empty($request->salespersons_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (!empty($request->supplier_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.supplier_id', $request->supplier_id);
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


            $targetArr = Lead::join('users', 'users.id', '=', 'inquiry.salespersons_id')
                    ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                    ->where('inquiry.buyer_id', $id)
                    ->whereIn('inquiry.order_status', ['2', '3', '4', '6'])
                    ->where('inquiry.pi_date', '>=', $fromDate)
                    ->where('inquiry.pi_date', '<=', $toDate);

            if (!empty($request->product_id) || !empty($request->brand_id)) {
                $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
            }
            if (!empty($request->salespersons_id)) {
                $targetArr = $targetArr->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (!empty($request->supplier_id)) {
                $targetArr = $targetArr->where('inquiry.supplier_id', $request->supplier_id);
            }
            $targetArr = $targetArr->select('inquiry.id', 'inquiry.order_no', 'inquiry.order_status', 'inquiry.purchase_order_no'
                            , 'inquiry.pi_date', 'inquiry.lc_transmitted_copy_done', 'inquiry.lc_no', 'inquiry.lc_date'
                            , 'inquiry.creation_date as inquiry_date', 'inquiry.supplier_id', 'supplier.name as supplier'
                            , DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS sales_person"))
                    ->orderBy('inquiry.pi_date', 'desc');
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
                                    if ($target->order_status == '2') {
                                        $purchaseSummary['volume']['confirmed'] = !empty($purchaseSummary['volume']['confirmed']) ? $purchaseSummary['volume']['confirmed'] : 0;
                                        $purchaseSummary['volume']['confirmed'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;

                                        $purchaseSummary['amount']['confirmed'] = !empty($purchaseSummary['amount']['confirmed']) ? $purchaseSummary['amount']['confirmed'] : 0;
                                        $purchaseSummary['amount']['confirmed'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    } elseif ($target->order_status == '3') {
                                        $purchaseSummary['volume']['in_progress'] = !empty($purchaseSummary['volume']['in_progress']) ? $purchaseSummary['volume']['in_progress'] : 0;
                                        $purchaseSummary['volume']['in_progress'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;

                                        $purchaseSummary['amount']['in_progress'] = !empty($purchaseSummary['amount']['in_progress']) ? $purchaseSummary['amount']['in_progress'] : 0;
                                        $purchaseSummary['amount']['in_progress'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    } elseif ($target->order_status == '4') {
                                        $purchaseSummary['volume']['accomplished'] = !empty($purchaseSummary['volume']['accomplished']) ? $purchaseSummary['volume']['accomplished'] : 0;
                                        $purchaseSummary['volume']['accomplished'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;

                                        $purchaseSummary['amount']['accomplished'] = !empty($purchaseSummary['amount']['accomplished']) ? $purchaseSummary['amount']['accomplished'] : 0;
                                        $purchaseSummary['amount']['accomplished'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    } elseif ($target->order_status == '6') {
                                        $purchaseSummary['volume']['cancelled'] = !empty($purchaseSummary['volume']['cancelled']) ? $purchaseSummary['volume']['cancelled'] : 0;
                                        $purchaseSummary['volume']['cancelled'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;

                                        $purchaseSummary['amount']['cancelled'] = !empty($purchaseSummary['amount']['cancelled']) ? $purchaseSummary['amount']['cancelled'] : 0;
                                        $purchaseSummary['amount']['cancelled'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    }

                                    $purchaseSummary['volume']['total'] = !empty($purchaseSummary['volume']['total']) ? $purchaseSummary['volume']['total'] : 0;
                                    $purchaseSummary['volume']['total'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;

                                    $purchaseSummary['amount']['total'] = !empty($purchaseSummary['amount']['total']) ? $purchaseSummary['amount']['total'] : 0;
                                    $purchaseSummary['amount']['total'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                }
                            }
                        }
                    }
                }
            }
            //  ENDOF SUMMARY

            $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                            ->where('inquiry.buyer_id', $id)
                            ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                                    , 'delivery.buyer_payment_status', 'delivery.shipment_status')->get();


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
            return view('report.orderSummary.print.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr', 'purchaseSummary'
                                    , 'productList', 'brandList', 'salesPersonList', 'supplierList'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'deliveryArr'
                                    , 'userAccessArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.orderSummary.print.index', compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr', 'purchaseSummary'
                                    , 'productList', 'brandList', 'salesPersonList', 'supplierList'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'deliveryArr'
                                    , 'userAccessArr'))
                    ->setPaper('a3', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
//                    $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
//            return $pdf->download('order_summary_report.pdf');
            return $pdf->stream();
        } else {
            return view('report.orderSummary.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr', 'purchaseSummary'
                                    , 'productList', 'brandList', 'salesPersonList', 'supplierList', 'deliveryArr'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'pi_from_date' => 'required',
            'pi_to_date' => 'required',
        ];

        $messages = [
            'pi_from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'pi_to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'pi_from_date=' . $request->pi_from_date . '&pi_to_date=' . $request->pi_to_date
                . '&salespersons_id=' . $request->salespersons_id
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&supplier_id=' . $request->supplier_id;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('orderSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('orderSummaryReport?generate=true&' . $url);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'report.orderSummary.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'report.orderSummary.print.shipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
