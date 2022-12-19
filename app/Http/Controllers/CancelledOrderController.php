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
use App\FollowUpHistory;
use App\InquiryDetails;
use App\SalesPersonToBuyer;
use App\InvoiceCommissionHistory;
use App\RwBreakdown;
use App\CommissionSetup;
use App\Quotation;
use App\Delivery;
use App\DeliveryDetails;
use App\Buyer;
use App\Grade;
use App\User;
use App\PiGenerate;
use App\PoGenerate;
use App\CauseOfFailure;
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

class CancelledOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $lcNoArr = Lead::select('lc_no')->whereNotNull('lc_no')->where('order_status', '6')->get();
        $uniqueNoArr = ['0' => __('label.SELECT_ORDER_NO_OPT')] + Lead::where('order_status', '6')->orderBy('id', 'desc')->pluck('order_no', 'order_no')->toArray();
        $purchaseOrderNoArr = ['0' => __('label.SELECT_PO_NO_OPT')] + Lead::where('order_status', '6')->orderBy('id', 'desc')->pluck('purchase_order_no', 'purchase_order_no')->toArray();

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
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.order_status', '6');
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


        //main data
        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        $targetArr = $targetArr->select('inquiry.id', 'inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.purchase_order_no'
                        , 'inquiry.order_cancel_remarks', 'inquiry.order_cancel_cause'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date')
                ->where('inquiry.order_status', '6')
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
            return redirect('/cancelledOrder?page=' . $page);
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

        //inquiry has followup history
        $hasFollowupList = [];
        $hasFollowupArr = FollowUpHistory::join('inquiry', 'inquiry.id', '=', 'follow_up_history.inquiry_id')
                        ->where('inquiry.order_status', '6')->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->where('inquiry.order_status', '6')->pluck('inquiry.id')->toArray();

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
        $hasDeliveryList = Delivery::where('shipment_status', '2')->pluck('inquiry_id', 'inquiry_id')->toArray();

        $causeList = CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        return view('cancelledOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'uniqueNoArr', 'purchaseOrderNoArr'
                                , 'productUnit', 'rowspanArr', 'hasDeliveryList', 'causeList'
                                , 'productArr', 'brandArr', 'gradeArr', 'buyerList', 'lcNoArr'
                                , 'productList', 'brandList', 'hasFollowupList', 'salesPersonList'));
    }

    public function filter(Request $request) {
        $url = 'order_no=' . urlencode($request->order_no) . '&purchase_order_no=' . urlencode($request->purchase_order_no)
                . '&lc_no=' . urlencode($request->lc_no) . '&buyer_id=' . $request->buyer_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&salespersons_id=' . $request->salespersons_id;
        return Redirect::to('cancelledOrder?' . $url);
    }

    //DELETE
    public function destroy(Request $request, $id) {
        $target = Lead::find($id);

//begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
//end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


//        //Dependency
        $dependencyArr = [
            'Delivery' => ['1' => 'inquiry_id'],
            'InvoiceCommissionHistory' => ['1' => 'inquiry_id'],
            'Receive' => ['1' => 'inquiry_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('cancelledOrder' . $pageNumber);
                }
            }
        }

        $deliveryIdArr = Delivery::where('inquiry_id', $target->id)
                        ->pluck('id')->toArray();

        //Remove data from child table
        RwBreakdown::where('inquiry_id', $target->id)->delete();
        InquiryDetails::where('inquiry_id', $target->id)->delete();
        FollowUpHistory::where('inquiry_id', $target->id)->delete();
        CommissionSetup::where('inquiry_id', $target->id)->delete();
        Quotation::where('inquiry_id', $target->id)->delete();
        PoGenerate::where('inquiry_id', $target->id)->delete();
        PiGenerate::where('inquiry_id', $target->id)->delete();

        DeliveryDetails::whereIn('delivery_id', $deliveryIdArr)->delete();
        Delivery::where('inquiry_id', $target->id)->delete();

        if ($target->delete()) {
            Session::flash('error', __('label.CANCELLED_ORDER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CANCELLED_ORDER_COULD_NOT_BE_DELETED'));
        }
        return redirect('cancelledOrder' . $pageNumber);
    }

    public function getOrderDetails(Request $request) {
        $loadView = 'cancelledOrder.showOrderDetails';
        return Common::getOrderDetails($request, $loadView);
    }

    //follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'cancelledOrder.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'cancelledOrder.showQuantitySummaryModal';
        $isConfirmedOrder = 0;
        $statusType = 'order_status';
        $status = '6';

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //end of summary
    //reactivate cancelled inquiry
    public function reactivate(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $target->status = '1';
        $target->order_status = '0';
        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_IS_REACTIVATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_REACTIVATED')], 401);
        }
    }

}
