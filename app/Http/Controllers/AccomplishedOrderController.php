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
use App\SupplierToProduct;
use App\RwBreakdown;
use App\InquiryDetails;
use App\SalesPersonToBuyer;
use App\Buyer;
use App\Grade;
use App\User;
use App\CommissionSetup;
use App\FollowUpHistory;
use App\InvoiceCommissionHistory;
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
use Illuminate\Http\Request;

class AccomplishedOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $lcNoArr = Lead::select('lc_no')->whereNotNull('lc_no')->where('order_status', '4')->get();
        $uniqueNoArr = ['0' => __('label.SELECT_ORDER_NO_OPT')] + Lead::where('order_status', '4')->orderBy('id', 'desc')->pluck('order_no', 'order_no')->toArray();
        $purchaseOrderNoArr = ['0' => __('label.SELECT_PO_NO_OPT')] + Lead::where('order_status', '4')->orderBy('id', 'desc')->pluck('purchase_order_no', 'purchase_order_no')->toArray();

        $productUnit = product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->pluck('measure_unit.name', 'product.id')->toArray();

        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        //sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        //endof arr
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;
        //END OF BUYER LIST
        //product list
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::pluck('name', 'id')->toArray();

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();

        //Sales Persons List
        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('users.allowed_for_sales', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;


        //ENDOF Sales Persons list
        //RW Status Arr
        $rwBreakdownStatusArr = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->where('inquiry.order_status', '4')
                        ->where('rw_breakdown.status', '2')
                        ->pluck('rw_breakdown.status', 'rw_breakdown.inquiry_id')->toArray();

        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.order_status', '4');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $inquiryDetails = $inquiryDetails->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
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


        //Main Data
        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        $targetArr = $targetArr->select('inquiry.id', 'inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'inquiry.lc_transmitted_copy_done'
                        , 'inquiry.purchase_order_no', 'inquiry.order_accomplish_remarks'
                        , 'inquiry.purchase_order_no', 'supplier.name as supplier_name'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date')
                ->where('inquiry.order_status', '4')
                ->orderBy('inquiry.creation_date', 'desc');




        //begin filtering
        if (!empty($request->product_id) || !empty($request->brand_id)) {
            $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
        }
        if (!empty($request->buyer_id)) {
            $targetArr = $targetArr->where('inquiry.buyer_id', $request->buyer_id);
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
            return redirect('/accomplishedOrder?page=' . $page);
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

        //inquiries with commission alredy set
        $commissionAlreadySetList = CommissionSetup::join('inquiry', 'inquiry.id', '=', 'commission_setup.inquiry_id')
                        ->where('inquiry.order_status', '4')->pluck('inquiry.id')->toArray();

        //inquiry has followup history
        $hasFollowupList = [];
        $hasFollowupArr = FollowUpHistory::join('inquiry', 'inquiry.id', '=', 'follow_up_history.inquiry_id')
                        ->where('inquiry.order_status', '4')->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->where('inquiry.order_status', '4')->pluck('inquiry.id')->toArray();

        if (!empty($hasFollowupArr)) {
            foreach ($hasFollowupArr as $fKey => $inquiryId) {
                $hasFollowupList[$inquiryId] = $inquiryId;
            }
        }
        if (!empty($hasActivityArr)) {
            foreach ($hasActivityArr as $aKey => $inquiryId) {
                $hasFollowupList[$inquiryId] = $inquiryId;
            }
        }

        //inquiry has invoice
        $hasInvoiceList = InvoiceCommissionHistory::pluck('inquiry_id', 'inquiry_id')->toArray();

        return view('accomplishedOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'uniqueNoArr', 'purchaseOrderNoArr'
                                , 'productUnit', 'rowspanArr', 'hasInvoiceList'
                                , 'productArr', 'brandArr', 'gradeArr', 'rwBreakdownStatusArr'
                                , 'buyerList', 'lcNoArr', 'commissionAlreadySetList', 'deliveryArr'
                                , 'productList', 'brandList', 'hasFollowupList', 'salesPersonList'));
    }

    public function filter(Request $request) {
        $url = 'order_no=' . urlencode($request->order_no) . '&purchase_order_no=' . urlencode($request->purchase_order_no)
                . '&lc_no=' . urlencode($request->lc_no) . '&buyer_id=' . $request->buyer_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&salespersons_id=' . $request->salespersons_id;
        return Redirect::to('accomplishedOrder?' . $url);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'accomplishedOrder.print.index';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getOrderDetails(Request $request) {
        $loadView = 'accomplishedOrder.showOrderDetails';
        return Common::getOrderDetails($request, $loadView);
    }

    //follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'accomplishedOrder.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

    //:::::::Start rwBreakdown :::::::::::

    public function rwBreakdown(Request $request, $id) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwBreakdown($request, $id, $loadView);
    }

    public function rwBreakdownGetBrand(Request $request) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwBreakdownGetBrand($request, $loadView);
    }

    public function rwBreakdownGetGrade(Request $request) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwBreakdownGetGrade($request, $loadView);
    }

    public function getRwBreakdownView(Request $request) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::getRwBreakdownView($request, $loadView);
    }

    public function rwProceedRequest(Request $request) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwProceedRequest($request, $loadView);
    }

    public function rwProceedRequestEdit(Request $request) {

        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwProceedRequestEdit($request, $loadView);
    }

    public function rwPreviewRequest(Request $request) {
        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::rwPreviewRequest($request, $loadView);
    }

    public function rwBreakDownSave(Request $request) {
        return Common::rwBreakDownSave($request);
    }

    public function leadRwBreakdownView(Request $request) {

        $loadView = 'accomplishedOrder.rwBreakdown';
        return Common::leadRwBreakdownView($request, $loadView);
    }

    public function getLeadRwParametersName(Request $request) {
        return Common::getLeadRwParametersName($request);
    }

    //::::::::::: END OF RW BREAKDOWN
    //new commission setup modal function
    public function getCommissionSetupModal(Request $request) {
        $loadView = 'accomplishedOrder';
        return Common::getCommissionSetupModal($request, $loadView);
    }

    public function commissionSetupSave(Request $request) {
        return Common::commissionSetupSave($request);
    }

    //end of commission setup
    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'accomplishedOrder.showQuantitySummaryModal';
        $isConfirmedOrder = 0;
        $statusType = 'order_status';
        $status = '4';

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //end of summary
    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'accomplishedOrder.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    //update tracking no
    public function updateTrackingNo(Request $request) {
        return Common::updateTrackingNo($request);
    }

    //lead time
    public function getLeadTime(Request $request) {
        $loadView = 'accomplishedOrder.showLeadTime';
        return Common::getLeadTime($request, $loadView);
    }

    //*********************start :: order cancellation********************
    public function orderCancellationModal(Request $request) {
        $loadView = 'accomplishedOrder.showOrderCancellation';
        return Common::orderCancellationModal($request, $loadView);
    }

    public function cancel(Request $request) {
        return Common::cancel($request);
    }

    //*********************end :: order cancellation********************
}
