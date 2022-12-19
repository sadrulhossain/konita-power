<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\MeasureUnit;
use App\Lead;
use App\Brand;
use App\Buyer;
use App\Supplier;
use App\BuyerFactory;
use App\RwUnit;
use App\SupplierToProduct;
use App\RwBreakdown;
use App\Bank;
use App\User;
use App\ProductPricingHistory;
use App\ProductTechDataSheet;
use App\SalesPersonToBuyer;
use App\ProductPricing;
use App\InquiryDetails;
use App\ProductToGrade;
use App\Grade;
use App\CommissionSetup;
use App\FollowUpHistory;
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

class PendingOrderController extends Controller {

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
        //buyer list
        $buyerArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc')->where('status', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerArr);
        }
        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;

        //salesPerson List
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
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::pluck('name', 'id')->toArray();

        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();


        //RW Status Arr
        $rwBreakdownStatusArr = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->where('inquiry.order_status', '1')
                        ->where('rw_breakdown.status', '2')
                        ->pluck('rw_breakdown.status', 'rw_breakdown.inquiry_id')->toArray();



        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.order_status', '1');
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


        $targetArr = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                ->leftJoin('buyer_factory', 'buyer_factory.id', '=', 'inquiry.factory_id');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        $targetArr = $targetArr->select('inquiry.order_status', 'inquiry.buyer_contact_person', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'inquiry.creation_date', 'inquiry.head_office_address'
                        , 'buyer.name as buyerName', DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                        , 'buyer_factory.name as factoryName', 'inquiry.status'
                        , 'inquiry.shipment_address_status')
                ->where('inquiry.order_status', '1')
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

        $fromDate = '';
        if (!empty($request->from_date)) {
            $fromDate = Helper::dateFormatConvert($request->from_date);
            $targetArr = $targetArr->where('inquiry.creation_date', '>=', $fromDate);
        }
        $toDate = '';
        if (!empty($request->to_date)) {
            $toDate = Helper::dateFormatConvert($request->to_date);
            $targetArr = $targetArr->where('inquiry.creation_date', '<=', $toDate);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //Helper::pr($targetArr->toArray(), 1);
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/pendingOrder?page=' . $page);
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
//        echo '<pre>';
//        print_r($inquiryDetailsArr);
//        print_r($rowspanArr['inquiry']);
//        print_r($rowspanArr['product']);
//        print_r($rowspanArr['brand']);
//        print_r($rowspanArr['grade']);
//        exit;
        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        //inquiries with commission alredy set
        $commissionAlreadySetList = CommissionSetup::join('inquiry', 'inquiry.id', '=', 'commission_setup.inquiry_id')
                        ->where('inquiry.order_status', '1')->pluck('inquiry.id')->toArray();

        //inquiry has followup history
        $hasFollowupList = [];
        $hasFollowupArr = FollowUpHistory::join('inquiry', 'inquiry.id', '=', 'follow_up_history.inquiry_id')
                        ->where('inquiry.order_status', '1')->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->where('inquiry.order_status', '1')->pluck('inquiry.id')->toArray();

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

        return view('pendingOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'buyerList'
                                , 'productArr', 'brandArr', 'gradeArr','rowspanArr'
                                , 'rwBreakdownStatusArr', 'salesPersonList', 'commissionAlreadySetList'
                                , 'productList', 'brandList', 'hasFollowupList'));
    }

    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&salespersons_id=' . $request->salespersons_id
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id;
        return Redirect::to('pendingOrder?' . $url);
    }

    public function getConfirmOrderModal(Request $request) {
        $inquiry = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->select('inquiry.id', 'inquiry.creation_date', 'inquiry.order_status'
                                , 'inquiry.confirmation_date', 'inquiry.po_date', 'inquiry.purchase_order_no'
                                , 'buyer.name as buyerName', 'buyer.id as buyer_id'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.order_no', 'inquiry.pi_date', 'inquiry.supplier_id')
                        ->where('inquiry.id', $request->inquiry_id)->first();

        //inquiry details
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.id')
                ->get();

        $prevOrderNo = Lead::select('order_no')->where('buyer_id', $inquiry->buyer_id)
//                        ->where('inquiry.product_id', $inquiry->product_id)
                        ->where('order_status', '!=', '1')->first();

        $bankList = array('0' => __('label.SELECT_BANK_OPT')) + Bank::where('status', '1')->pluck('name', 'id')->toArray();

        /*         * ********* preparing suplier list ************* */
        //get arrays from inquiry details
        $productIdArr = $brandIdArr = $itemArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                $gradeId = $item->grade_id ?? 0;
                $productIdArr[$item->product_id] = $item->product_id;
                $brandIdArr[$item->brand_id] = $item->brand_id;
                $itemArr[$item->product_id][$item->brand_id][$gradeId] = $gradeId;
            }
        }

        //getting all suppliers related to the inquiry products and brands 
        $supplierToProductArr = SupplierToProduct::select('supplier_id', 'product_id', 'brand_id')
                        ->whereIn('product_id', $productIdArr)->whereIn('brand_id', $brandIdArr)->get();

        //preparing array of set of supplier
        $supplierToProductList = [];
        if (!$supplierToProductArr->isEmpty()) {
            foreach ($supplierToProductArr as $supplierToProduct) {
                $supplierToProductList[$supplierToProduct->product_id][$supplierToProduct->brand_id][$supplierToProduct->supplier_id] = $supplierToProduct->supplier_id;
            }
        }

        //preparing array of supplier of the inquiry item sets
        $supplierToProductListArr = $supplierArr = [];
        if (!empty($itemArr)) {
            foreach ($itemArr as $productId => $brandList) {
                foreach ($brandList as $brandId => $gradeList) {
                    foreach ($gradeList as $gradeId) {
                        if (!empty($supplierToProductList[$productId][$brandId])) {
                            $supplierToProductListArr[$productId][$brandId][$gradeId] = $supplierToProductList[$productId][$brandId];
                            $supplierArr[] = $supplierToProductListArr[$productId][$brandId][$gradeId];
                        }
                    }
                }
            }
        }



        $commonSupplierArr = [];
        if (!empty($supplierArr)) {
            //if more than 1 supplier set
            if (count($supplierArr) > 1) {
                foreach ($supplierArr as $key => $value) {
                    //for 1st supplier set
                    if ($key == 0) {
                        //find common suppliers
                        $commonSupplierArr = array_intersect($supplierArr[$key], $supplierArr[$key + 1]);
                    } else if (count($supplierArr) >= 2) {
                        //if 2 or more than 2 supplier set
                        $commonSupplierArr = array_intersect($commonSupplierArr, $supplierArr[$key]);
                    }
                }
            } else {
                //if 1 supplier set
                $commonSupplierArr = $supplierArr[0];
            }
        }

        $supplierList = array('0' => __('label.SELECT_SUPPLIER_OPT')) + Supplier::whereIn('id', $commonSupplierArr)
                        ->where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        /*         * ********* end of preparing suplier list ************* */

        $view = view('pendingOrder.showConfirmOrder', compact('request', 'inquiry', 'bankList', 'prevOrderNo'
                        , 'supplierList', 'inquiryDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public function newLsdRow(Request $request) {

        $view = view('pendingOrder.addNewRow')->render();
        return response()->json(['html' => $view]);
    }

    public function confirmOrder(Request $request) {
        $target = Lead::find($request->inquiry_id);
        //validation
        $rules = [
            'purchase_order_no' => 'required|unique:inquiry,purchase_order_no,' . $target->id,
            'order_no' => 'required|unique:inquiry,order_no,' . $target->id,
            'supplier_id' => 'required|not_in:0',
            'pi_date' => 'required',
        ];

//        if (empty($target->purchase_order_no)) {
//            $rules['purchase_order_no'] = 'required|unique:inquiry';
//        }
        if (empty($target->po_date)) {
            $rules['po_date'] = 'required';
        }

        $message = [];

        $message = [
            'purchase_order_no.required' => __('label.THE_PO_NO_FIELD_IS_REQUIRED'),
        ];

        if (!empty($request->lc_transmitted_copy_done)) {
            $rules['lc_issue_date'] = 'required';
            $rules['bank'] = 'required|not_in:0';
            $rules['branch'] = 'required';

            if (!empty($request->lsd)) {
                $row = 0;
                foreach ($request->lsd as $key => $lsd) {
                    $rules['lsd.' . $key] = 'required';
                    $message['lsd.' . $key . '.required'] = __('label.LSD_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                    $row++;
                }
            }
            if (!empty($request->lc_expiry_date)) {
                $row = 0;
                foreach ($request->lc_expiry_date as $key => $lcExpiryDate) {
                    $rules['lc_expiry_date.' . $key] = 'required';
                    $message['lc_expiry_date.' . $key . '.required'] = __('label.LC_EXPIRY_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                    $row++;
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        $target->purchase_order_no = $request->purchase_order_no;
        $target->order_no = $request->order_no;
        $target->supplier_id = $request->supplier_id;
        $target->lc_no = $request->lc_no;
        $target->lc_date = !empty($request->lc_date) ? Helper::dateFormatConvert($request->lc_date) : null;
        if (empty($target->po_date)) {
            $target->po_date = !empty($request->po_date) ? Helper::dateFormatConvert($request->po_date) : null;
        }
        $target->pi_date = !empty($request->pi_date) ? Helper::dateFormatConvert($request->pi_date) : null;
        $target->note = $request->note;

        if (!empty($request->lc_transmitted_copy_done)) {
            $target->lc_transmitted_copy_done = $request->lc_transmitted_copy_done;
        } else {
            $target->lc_transmitted_copy_done = '0';
        }
        $lsdInfo = [];

        if (!empty($request->lc_transmitted_copy_done)) {

            if (!empty($request->lsd)) {
                foreach ($request->lsd as $klsd => $lsd) {
                    $lsdInfo[$klsd]['lsd'] = $lsd;
                }
            }
            if (!empty($request->lc_expiry_date)) {
                foreach ($request->lc_expiry_date as $kled => $lcExpiryDate) {
                    $lsdInfo[$kled]['lc_expiry_date'] = $lcExpiryDate;
                }
            }

            $target->lc_issue_date = Helper::dateFormatConvert($request->lc_issue_date);
            $target->bank = $request->bank;
            $target->branch = $request->branch;
            $target->lsd_info = !empty($lsdInfo) ? json_encode($lsdInfo) : '';
        }

        $target->order_status = '2';
        $target->order_confirmed_at = date('Y-m-d H:i:s');
        $target->order_confirmed_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_CONFIRMED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_COULD_NOT_BE_CONFIRMED')), 401);
        }
    }

    //follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'pendingOrder.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

    //:::::::Start rwBreakdown :::::::::::

    public function rwBreakdown(Request $request, $id) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwBreakdown($request, $id, $loadView);
    }

    public function rwBreakdownGetBrand(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwBreakdownGetBrand($request, $loadView);
    }

    public function rwBreakdownGetGrade(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwBreakdownGetGrade($request, $loadView);
    }

    public function getRwBreakdownView(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::getRwBreakdownView($request, $loadView);
    }

    public function rwProceedRequest(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwProceedRequest($request, $loadView);
    }

    public function rwProceedRequestEdit(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwProceedRequestEdit($request, $loadView);
    }

    public function rwPreviewRequest(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::rwPreviewRequest($request, $loadView);
    }

    public function rwBreakDownSave(Request $request) {
        return Common::rwBreakDownSave($request);
    }

    public function leadRwBreakdownView(Request $request) {
        $loadView = 'pendingOrder.rwBreakdown';
        return Common::leadRwBreakdownView($request, $loadView);
    }

    public function getLeadRwParametersName(Request $request) {
        return Common::getLeadRwParametersName($request);
    }

    //::::::::::: END OF RW BREAKDOWN
    //new commission setup modal function
    public function getCommissionSetupModal(Request $request) {
        $loadView = 'pendingOrder';
        return Common::getCommissionSetupModal($request, $loadView);
    }

    public function commissionSetupSave(Request $request) {
        return Common::commissionSetupSave($request);
    }

    //end of commission setup
    //pending order cancellation
    public function pendingOrderCancelModal(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $causeList = ['0' => __('label.SELECT_CAUSE_OF_FAILURE_OPT')] + CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $view = view('pendingOrder.showCancellationModal', compact('target', 'causeList'))->render();
        return response()->json(['html' => $view]);
    }

    public function pendingOrderCancelSave(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $rules = $message = array();
        $rules = [
            'cancel_cause' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->cancel_cause = $request->cancel_cause;
        $target->cancel_remarks = $request->remarks;
        $target->status = '3';
        $target->order_status = '0';
        $target->inquiry_cancelled_updated_at = date('Y-m-d H:i:s');
        $target->inquiry_cancelled_updated_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.PENDING_ODRED_CANCELLATION_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.PENDING_ODRED_COULD_NOT_BE_CANCELLATION')], 401);
        }
    }

    //end of pending order cancellation
    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'pendingOrder.showQuantitySummaryModal';
        $isConfirmedOrder = 0;
        $statusType = 'order_status';
        $status = '1';

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //end of summary

    /*     * ********************** satrt :: inquiry reassignment ************************* */

    public function getInquiryReassigned(Request $request) {
        $loadView = 'pendingOrder.showInquiryReassignment';
        return Common::getInquiryReassigned($request, $loadView);
    }

    public function setInquiryReassigned(Request $request) {
        return Common::setInquiryReassigned($request);
    }

    /*     * ********************** end :: inquiry reassignment ************************* */
}
