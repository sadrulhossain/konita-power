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
use App\ProductPricingHistory;
use App\ProductTechDataSheet;
use App\SupplierToProduct;
use App\CommissionSetup;
use App\ShippingLine;
use App\Invoice;
use App\Buyer;
use App\PreCarrier;
use App\ShippingTerm;
use App\PaymentTerm;
use App\RwBreakdown;
use App\RwUnit;
use App\InquiryDetails;
use App\SalesPersonToBuyer;
use App\Grade;
use App\DeliveryDetails;
use App\PoGenerate;
use App\CompanyInformation;
use App\SignatoryInfo;
use App\BeneficiaryBank;
use App\PiGenerate;
use App\ProductPricing;
use App\BuyerFactory;
use App\FollowUpHistory;
use App\BuyerFollowUpHistory;
use App\InvoiceCommissionHistory;
use App\CauseOfFailure;
use App\BuyerToProduct;
use App\OrderMessaging;
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

class BuyerOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $buyerId = !empty($buyer->id) ? $buyer->id : 0;

        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT'),
            '2' => __('label.CONFIRMED'),
            '3' => __('label.IN_PROGRESS'),
            '4' => __('label.ACCOMPLISHED'),
            '6' => __('label.CANCELLED'),
        ];

        $inquiryList = Lead::whereIn('inquiry.order_status', ['2', '3', '4', '5', '6'])
                        ->where('inquiry.buyer_id', $buyerId)
                        ->pluck('inquiry.id', 'inquiry.id')->toArray();

        $lcNoArr = Lead::select('lc_no')->whereNotNull('lc_no')->whereIn('id', $inquiryList)->get();
        $uniqueNoArr = ['0' => __('label.SELECT_ORDER_NO_OPT')] + Lead::whereIn('id', $inquiryList)
                        ->orderBy('id', 'desc')->pluck('order_no', 'order_no')->toArray();
        $purchaseOrderNoArr = ['0' => __('label.SELECT_PO_NO_OPT')] + Lead::whereIn('id', $inquiryList)
                        ->orderBy('id', 'desc')->pluck('purchase_order_no', 'purchase_order_no')->toArray();
        $productUnit = product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->pluck('measure_unit.name', 'product.id')->toArray();

        //endof arr

        $salesVolumeInfo = InquiryDetails::whereIn('inquiry_id', $inquiryList);

        $purchasedProductIdArr = $salesVolumeInfo->pluck('product_id', 'product_id')->toArray();
        $purchasedBrandIdArr = $salesVolumeInfo->pluck('brand_id', 'brand_id')->toArray();


        //product list
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->where('buyer_to_product.buyer_id', $buyerId)->whereIn('buyer_to_product.product_id', $purchasedProductIdArr)
                        ->pluck('product.name', 'product.id')->toArray();

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + BuyerToProduct::join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->where('buyer_to_product.buyer_id', $buyerId)->whereIn('buyer_to_product.brand_id', $purchasedBrandIdArr)
                        ->pluck('brand.name', 'brand.id')->toArray();

        //Sales Persons List
        $salesPersonArr = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('users.allowed_for_sales', '1')
                ->where('users.status', '1')
                ->where('sales_person_to_buyer.buyer_id', $buyerId);

        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;


        //ENDOF Sales Persons list
        //RW Status Arr
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry.id', $inquiryList);
        if (!empty($request->product_id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry_details.product_id', $request->product_id);
        }
        if (!empty($request->brand_id)) {
            $inquiryDetails = $inquiryDetails->where('inquiry_details.brand_id', $request->brand_id);
        }
        $inquiryDetails = $inquiryDetails->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id')
                ->get();

        $inquiryIdArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $inquiryIdArr[$item->inquiry_id] = $item->inquiry_id;
            }
        }


        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->whereIn('inquiry.id', $inquiryList);

        $targetArr = $targetArr->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date', 'supplier.pi_required')
                ->orderBy('inquiry.creation_date', 'desc');


        //begin filtering
        if (!empty($request->product_id) || !empty($request->brand_id)) {
            $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
        }

        if (!empty($request->salespersons_id)) {
            $targetArr = $targetArr->where('inquiry.salespersons_id', $request->salespersons_id);
        }
        if (!empty($request->order_no)) {
            $targetArr = $targetArr->where('inquiry.order_no', $request->order_no);
        }

        $searchText = $request->lc_no;
        if (!empty($searchText)) {
            $targetArr = $targetArr->where(function ($query) use ($searchText) {
                $query->where('inquiry.lc_no', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->purchase_order_no)) {
            $targetArr = $targetArr->where('inquiry.purchase_order_no', $request->purchase_order_no);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('inquiry.order_status', $request->status);
        }

        $fromDate = '';
        if (!empty($request->from_date)) {
            $fromDate = Helper::dateFormatConvert($request->from_date);
            $targetArr = $targetArr->where('inquiry.pi_date', '>=', $fromDate);
        }
        $toDate = '';
        if (!empty($request->to_date)) {
            $toDate = Helper::dateFormatConvert($request->to_date);
            $targetArr = $targetArr->where('inquiry.pi_date', '<=', $toDate);
        }

        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/buyerOrder?page=' . $page);
        }



        //inquiry Details Arr
        $inquiryDetailsArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $gsm = !empty($item->gsm) ? $item->gsm : 0;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['quantity'] = $item->quantity;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_price'] = $item->total_price;
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
        //ENDOF final targetArr
        //START Rowspan Arr
        $rowspanArr = [];
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

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        $deliveryInfoArr = Delivery::select('inquiry_id', 'bl_no', 'id', 'payment_status'
                        , 'buyer_payment_status', 'shipment_status')->get();

        $deliveryArr = [];
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

        $hasMessageList = OrderMessaging::where('inquiry_id', '<>', 0)->whereNotNull('history')
                        ->pluck('inquiry_id', 'inquiry_id')->toArray();


        return view('buyerOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'uniqueNoArr', 'purchaseOrderNoArr'
                                , 'productUnit', 'rowspanArr', 'productArr', 'brandArr', 'gradeArr', 'statusList', 'hasMessageList'
                                , 'lcNoArr', 'deliveryArr', 'productList', 'brandList', 'salesPersonList', 'buyerId'));
    }

    public function filter(Request $request) {
        $url = 'order_no=' . urlencode($request->order_no) . '&purchase_order_no=' . urlencode($request->purchase_order_no)
                . '&lc_no=' . urlencode($request->lc_no) . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&salespersons_id=' . $request->salespersons_id . '&status=' . $request->status;
        return Redirect::to('buyerOrder?' . $url);
    }

    public function getOrderDetails(Request $request) {
        $loadView = 'buyerOrder.showOrderDetails';
        return Common::getOrderDetails($request, $loadView);
    }

    public function getLsdInfo(Request $request) {
        $lsdInfo = Lead::select('lsd_info')->where('inquiry.id', $request->inquiry_id)->first();
        $lsdInfoArr = [];
        if (!empty($lsdInfo)) {
            $lsdInfoArr = json_decode($lsdInfo->lsd_info, true);
        }

        $view = view('buyerOrder.getLsdInfo', compact('lsdInfoArr'))->render();
        return response()->json(['html' => $view]);
    }

    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'buyerOrder.showQuantitySummaryModal';
        $isConfirmedOrder = 1;
        $statusType = 'order_status';
        $status = ['2', '3', '4'];

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'buyerOrder.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'buyerOrder.print.index';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    //end of summary
    
    //start messaging
    public function getOrderMessaging(Request $request) {
        $loadView = 'buyerOrder.showOrderMessaging';
        return Common::getOrderMessaging($request, $loadView);
    }
    public function setMessage(Request $request) {
        $loadView = 'buyerOrder.showMessagebody';
        return Common::setMessage($request, $loadView);
    }

    //end messaging
}
