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
use App\OrderMessaging;
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

class ConfirmedOrderController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $lcNoArr = Lead::select('lc_no')->whereNotNull('lc_no')->whereIn('order_status', ['2', '3'])->get();
        $uniqueNoArr = ['0' => __('label.SELECT_ORDER_NO_OPT')] + Lead::whereIn('order_status', ['2', '3'])->orderBy('id', 'desc')->pluck('order_no', 'order_no')->toArray();
        $purchaseOrderNoArr = ['0' => __('label.SELECT_PO_NO_OPT')] + Lead::whereIn('order_status', ['2', '3'])->orderBy('id', 'desc')->pluck('purchase_order_no', 'purchase_order_no')->toArray();
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
                        ->whereIn('inquiry.order_status', ['2', '3'])
                        ->where('rw_breakdown.status', '2')
                        ->pluck('rw_breakdown.status', 'rw_breakdown.inquiry_id')->toArray();


        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry.order_status', ['2', '3']);
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


        $targetArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->whereIn('inquiry.order_status', ['2', '3']);
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        $targetArr = $targetArr->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                        , 'inquiry.order_status', 'inquiry.note', 'supplier.name as supplier_name'
                        , 'inquiry.lc_transmitted_copy_done', 'inquiry.id', 'inquiry.purchase_order_no'
                        , 'buyer.name as buyerName', 'inquiry.lc_issue_date', 'inquiry.pi_date'
                        , 'inquiry.creation_date', 'supplier.pi_required', 'inquiry.buyer_id')
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
            return redirect('/confirmedOrder?page=' . $page);
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
                        ->whereIn('inquiry.order_status', ['2', '3'])->pluck('inquiry.id')->toArray();

        //inquiry has followup history
        $hasFollowupList = [];
        $hasFollowupArr = FollowUpHistory::join('inquiry', 'inquiry.id', '=', 'follow_up_history.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3'])->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->whereIn('inquiry.order_status', ['2', '3'])->pluck('inquiry.id')->toArray();

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
        $hasMessageList = OrderMessaging::where('inquiry_id', '<>', 0)->whereNotNull('history')
                        ->pluck('inquiry_id', 'inquiry_id')->toArray();

        return view('confirmedOrder.index')->with(compact('request', 'qpArr', 'targetArr', 'uniqueNoArr', 'purchaseOrderNoArr'
                                , 'productUnit', 'rowspanArr', 'hasInvoiceList'
                                , 'productArr', 'brandArr', 'gradeArr', 'rwBreakdownStatusArr'
                                , 'buyerList', 'lcNoArr', 'commissionAlreadySetList', 'deliveryArr'
                                , 'productList', 'brandList', 'hasFollowupList', 'salesPersonList', 'hasMessageList'));
    }

    public function filter(Request $request) {
        $url = 'order_no=' . urlencode($request->order_no) . '&purchase_order_no=' . urlencode($request->purchase_order_no)
                . '&lc_no=' . urlencode($request->lc_no) . '&buyer_id=' . $request->buyer_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&salespersons_id=' . $request->salespersons_id;
        return Redirect::to('confirmedOrder?' . $url);
    }

    public function edit(Request $request) {
        $target = Lead::find($request->inquiry_id);

        $inquiry = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->select('inquiry.id', 'inquiry.creation_date', 'inquiry.order_status'
                                , 'inquiry.purchase_order_no', 'buyer.name as buyerName', 'buyer.id as buyer_id'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.po_date')
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


        $bankList = array('0' => __('label.SELECT_BANK_OPT')) + Bank::where('status', '1')->pluck('name', 'id')->toArray();
        $previousLsdInfo = Lead::select('lsd_info')->where('inquiry.id', $request->inquiry_id)->first();

        if (!empty($previousLsdInfo)) {
            $previousLsdInfoArr = json_decode($previousLsdInfo->lsd_info, true);
        }
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

        //ENDOF SUPPLIER LIST

        $view = view('confirmedOrder.showEditLcInfo', compact('target', 'request', 'supplierList'
                        , 'bankList', 'inquiry', 'previousLsdInfoArr', 'inquiryDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public function update(Request $request) {
        $target = Lead::find($request->inquiry_id);
        //validation
        $rules = [
            'supplier_id' => 'required|not_in:0',
            'purchase_order_no' => 'required|unique:inquiry,purchase_order_no,' . $target->id,
            'order_no' => 'required|unique:inquiry,order_no,' . $target->id,
            'pi_date' => 'required',
        ];
        $message = [];
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

        $target->supplier_id = $request->supplier_id;
        $target->purchase_order_no = $request->purchase_order_no;
        $target->order_no = $request->order_no;
        $target->pi_date = !empty($request->pi_date) ? Helper::dateFormatConvert($request->pi_date) : null;
        $target->lc_no = $request->lc_no;
        $target->lc_date = !empty($request->lc_date) ? Helper::dateFormatConvert($request->lc_date) : null;
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


        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_LC_INFO_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_LC_INFO_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function getOrderDetails(Request $request) {
        $loadView = 'confirmedOrder.showOrderDetails';
        return Common::getOrderDetails($request, $loadView);
    }

    public function markOrderAccomplishedModal(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT')
            , '1' => __('label.NORMAL')
            , '2' => __('label.HAPPY')
            , '3' => __('label.UNHAPPY')
        ];
        $view = view('confirmedOrder.showMarkOrderAccomplished', compact('target', 'statusList'))->render();
        return response()->json(['html' => $view]);
    }

    public function accomplish(Request $request) {
        $target = Lead::find($request->inquiry_id);

        //validation
        $rules = [
            'order_accomplish_remarks' => 'required',
            'status' => 'required|not_in:0',
            'remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        $target->order_status = '4';
        $target->order_accomplish_remarks = $request->order_accomplish_remarks;
        $target->order_accomplished_at = date('Y-m-d H:i:s');
        $target->order_accomplished_by = Auth::user()->id;

        $historyData = [];
        $uniqId = uniqid();

        $orderNo = !empty($request->order_no) ? $request->order_no : '';

        //create new follow up array
        $historyData[$uniqId]['follow_up_date'] = date('d F Y');
        $historyData[$uniqId]['status'] = $request->status;
        $historyData[$uniqId]['order_no'] = $request->order_no;
        $historyData[$uniqId]['remarks'] = $request->remarks;
        $historyData[$uniqId]['updated_by'] = Auth::user()->id;
        $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');


        //merge with previous history and pack in json
        $followUpHistory = BuyerFollowUpHistory::where('buyer_id', $target->buyer_id)->first();

        if (!empty($followUpHistory)) {
            $preHistoryArr = json_decode($followUpHistory->history, true);
            $historyArr = array_merge($preHistoryArr, $historyData);
        } else {
            $followUpHistory = new BuyerFollowUpHistory;
            $historyArr = $historyData;
        }


        $followUpHistory->buyer_id = $target->buyer_id;
        $followUpHistory->history = json_encode($historyArr);
        $followUpHistory->updated_at = date('Y-m-d H:i:s');
        $followUpHistory->updated_by = Auth::user()->id;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                $followUpHistory->save();
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.ORDER_MARKED_AS_ACCOMPLISHED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.ORDER_COULD_NOT_BE_MARKED_AS_ACCOMPLISHED')], 401);
        }
    }

    //follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'confirmedOrder.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

    public function getShipmentInfoView($id) {
        //inquiry info
        $target = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                                , 'inquiry.order_status', 'inquiry.note'
                                , 'inquiry.lc_transmitted_copy_done', 'buyer.name as buyer_name', 'inquiry.id'
                                , 'inquiry.purchase_order_no', 'supplier.name as supplier_name'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.creation_date', 'inquiry.confirmation_date'
                                , 'inquiry.po_date', 'inquiry.pi_date')
                        ->where('inquiry.id', $id)->first();

        //inquiry Details 
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                                , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                                , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                                , 'inquiry_details.id')
                        ->where('inquiry_details.inquiry_id', $id)->get();

        $deliveryDetailsArr = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                        ->select('delivery_details.shipment_quantity', 'delivery_details.inquiry_details_id'
                                , 'delivery_details.delivery_id')
                        ->where('delivery.inquiry_id', $id)->get();

        $shipmentQuantityArr = $quantitySumArr = $dueQuantityArr = [];
        if (!$deliveryDetailsArr->isEmpty()) {
            foreach ($deliveryDetailsArr as $deliveryDetails) {
                $shipmentQuantityArr[$deliveryDetails->inquiry_details_id][$deliveryDetails->delivery_id] = $deliveryDetails->shipment_quantity;
                $quantitySumArr[$deliveryDetails->inquiry_details_id] = array_sum($shipmentQuantityArr[$deliveryDetails->inquiry_details_id]);
            }
        }

        $surplusQuantityArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                //find remaining quantity of order
                $quantitySum = !empty($quantitySumArr[$item->id]) ? Helper::numberFormatDigit2($quantitySumArr[$item->id]) : 0.00;
                $dueQuantityArr[$item->id] = $item->quantity - $quantitySum;
                $surplusQuantityArr[$item->id] = $quantitySum - $item->quantity;
            }
        }

        //shipped delivery list
        $shippedDeliveryList = Delivery::select('*')->where('inquiry_id', $id)->where('shipment_status', '2')->get();

        //draft delivery list
        $draftDeliveryList = Delivery::select('*')->where('inquiry_id', $id)->where('shipment_status', '1')->first();

        return view('confirmedOrder.orderShipment.getShipmentInfoView')->with(compact('target', 'quantitySumArr', 'dueQuantityArr'
                                , 'shippedDeliveryList', 'draftDeliveryList', 'inquiryDetails', 'surplusQuantityArr'));
    }

    public function getNewShipmentAdd(Request $request) {
        $target = Lead::find($request->inquiry_id);
        $view = view('confirmedOrder.orderShipment.showAddnewShipment', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function newEtsRow(Request $request) {
        $view = view('confirmedOrder.orderShipment.newEtsRow')->render();
        return response()->json(['html' => $view]);
    }

    public function newEtaRow(Request $request) {
        $view = view('confirmedOrder.orderShipment.newEtaRow')->render();
        return response()->json(['html' => $view]);
    }

    //save shipment info with ets eta only
    public function saveEtsEtaInfo(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        //validation
        $rules = $message = array();

        if (!empty($request->ets_date)) {
            $row = 0;
            foreach ($request->ets_date as $key => $etsDate) {
                $rules['ets_date.' . $key] = 'required';
                $message['ets_date.' . $key . '.required'] = __('label.ETS_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if (!empty($request->ets_notification_date)) {
            $row = 0;
            foreach ($request->ets_date as $key => $etsNotificationDate) {
                $rules['ets_notification_date.' . $key] = 'required';
                $message['ets_notification_date.' . $key . '.required'] = __('label.ETS_NOTIFICATION_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation

        $etsInfo = [];
        //ets info put in array
        if (!empty($request->ets_date)) {
            foreach ($request->ets_date as $ks => $etsDate) {
                $etsInfo[$ks]['ets_date'] = !empty($etsDate) ? $etsDate : '';
            }
        }
        if (!empty($request->ets_notification_date)) {
            foreach ($request->ets_notification_date as $ksn => $etsNotificationDate) {
                $etsInfo[$ksn]['ets_notification_date'] = !empty($etsNotificationDate) ? $etsNotificationDate : '';
            }
        }

//        echo '<pre';
//        print_r($request->eta_date);
//        exit;
        $etaInfo = [];
        //eta info put in array
        if (!empty($request->eta_date)) {
            foreach ($request->eta_date as $ka => $etaDate) {
                $etaInfo[$ka]['eta_date'] = !empty($etaDate) ? $etaDate : '';
            }
        }
        if (!empty($request->eta_notification_date)) {
            foreach ($request->eta_notification_date as $kan => $etaNotificationDate) {
                $etaInfo[$kan]['eta_notification_date'] = !empty($etaNotificationDate) ? $etaNotificationDate : '';
            }
        }
        //echo '<pre>';print_r($etaInfo);exit;
        //save ets eta
        $delivery = new Delivery;
        $delivery->inquiry_id = $request->inquiry_id;
        $delivery->ets_info = !empty($etsInfo) ? json_encode($etsInfo) : '';
        $delivery->eta_info = !empty($etaInfo) ? json_encode($etaInfo) : '';

        if ($delivery->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SHIPMENT_INFO_SET_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.SHIPMENT_INFO_COULD_NOT_BE_SET')), 401);
        }
    }

    //*********************start :: order cancellation********************
    public function orderCancellationModal(Request $request) {
        $loadView = 'confirmedOrder.showOrderCancellation';
        return Common::orderCancellationModal($request, $loadView);
    }

    public function cancel(Request $request) {
        return Common::cancel($request);
    }

    //*********************end :: order cancellation********************
    //edit ets eta modal load
    public function editEtsEtaInfo(Request $request) {
        //previous ets eta info
        $previousEtsEtaInfo = Delivery::select('id', 'ets_info', 'eta_info')->where('id', $request->shipment_id)->first();
        $previousEtsInfoArr = $previousEtaInfoArr = [];
        $prevRevEtsInfoArr = $prevRevEtaInfoArr = [];
        if (!empty($previousEtsEtaInfo->ets_info)) {
            $previousEtsInfoArr = json_decode($previousEtsEtaInfo->ets_info, true);
            $prevRevEtsInfoArr = $previousEtsInfoArr;
            krsort($prevRevEtsInfoArr);
        }
        if (!empty($previousEtsEtaInfo->eta_info)) {
            $previousEtaInfoArr = json_decode($previousEtsEtaInfo->eta_info, true);
            $prevRevEtaInfoArr = $previousEtaInfoArr;
            krsort($prevRevEtaInfoArr);
        }

        $view = view('confirmedOrder.orderShipment.showEditEtsEtaInfo', compact('previousEtsEtaInfo'
                        , 'previousEtsInfoArr', 'previousEtaInfoArr'
                        , 'prevRevEtsInfoArr', 'prevRevEtaInfoArr', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    //update ets eta info
    public function updateEtsEtaInfo(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        //validation
        $rules = $message = array();

        if (!empty($request->ets_date)) {
            $row = 0;
            foreach ($request->ets_date as $keys => $etsDate) {
                $rules['ets_date.' . $keys] = 'required';
                $message['ets_date.' . $keys . '.required'] = __('label.ETS_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if (!empty($request->ets_notification_date)) {
            $row = 0;
            foreach ($request->ets_date as $keys => $etsNotificationDate) {
                $rules['ets_notification_date.' . $keys] = 'required';
                $message['ets_notification_date.' . $keys . '.required'] = __('label.ETS_NOTIFICATION_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if (!empty($request->eta_date)) {
            $row = 0;
            foreach ($request->eta_date as $keya => $etaDate) {
                $rules['eta_date.' . $keya] = 'required';
                $message['eta_date.' . $keya . '.required'] = __('label.ETA_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if (!empty($request->eta_notification_date)) {
            $row = 0;
            foreach ($request->eta_date as $keya => $etaNotificationDate) {
                $rules['eta_notification_date.' . $keya] = 'required';
                $message['eta_notification_date.' . $keya . '.required'] = __('label.ETA_NOTIFICATION_DATE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation

        $etsInfo = [];
        //ets info put in array
        if (!empty($request->ets_date)) {
            foreach ($request->ets_date as $ks => $etsDate) {
                $etsInfo[$ks]['ets_date'] = !empty($etsDate) ? $etsDate : '';
            }
        }
        if (!empty($request->ets_notification_date)) {
            foreach ($request->ets_notification_date as $ksn => $etsNotificationDate) {
                $etsInfo[$ksn]['ets_notification_date'] = !empty($etsNotificationDate) ? $etsNotificationDate : '';
            }
        }

        $etaInfo = [];
        //eta info put in array
        if (!empty($request->eta_date)) {
            foreach ($request->eta_date as $ka => $etaDate) {
                $etaInfo[$ka]['eta_date'] = !empty($etaDate) ? $etaDate : '';
            }
        }
        if (!empty($request->eta_notification_date)) {
            foreach ($request->eta_notification_date as $kan => $etaNotificationDate) {
                $etaInfo[$kan]['eta_notification_date'] = !empty($etaNotificationDate) ? $etaNotificationDate : '';
            }
        }
        //echo '<pre>';print_r($etaInfo);exit;
        //save ets eta
        $delivery = Delivery::find($request->shipment_id);
        $delivery->ets_info = !empty($etsInfo) ? json_encode($etsInfo) : '';
        $delivery->eta_info = !empty($etaInfo) ? json_encode($etaInfo) : '';

        if ($delivery->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ETS_ETA_INFO_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ETS_ETA_INFO_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    //get carrier info modal load
    public function getCarrierInfo(Request $request) {
        //previous ets eta info
        $previousCarrierInfo = Delivery::select('id', 'shipping_line', 'container_no')->where('id', $request->shipment_id)->first();

        //shipping line list 
        $shippingLineList = ['0' => __('label.SELECT_SHIPPING_LINE_OPT')] + ShippingLine::orderBy('order', 'asc')
                        ->where('status', '1')->pluck('name', 'id')->toArray();

        $previousContainerNoArr = [];
        if (!empty($previousCarrierInfo->container_no)) {
            $previousContainerNoArr = json_decode($previousCarrierInfo->container_no, true);
        }

        $view = view('confirmedOrder.orderShipment.showSetCarrierInfo', compact('previousCarrierInfo'
                        , 'previousContainerNoArr', 'shippingLineList', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    public function newContainerNoRow(Request $request) {
        $view = view('confirmedOrder.orderShipment.newContainerNoRow')->render();
        return response()->json(['html' => $view]);
    }

    //update ets eta info
    public function setCarrierInfo(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        //validation
        $message = array();
        $rules = [
            'shipping_line' => 'required|not_in:0',
        ];

        if (!empty($request->container_no)) {
            $row = 0;
            foreach ($request->container_no as $key => $containerNo) {
                $rules['container_no.' . $key] = 'required';
                $message['container_no.' . $key . '.required'] = __('label.CONTAINER_NO_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation

        $containerInfo = [];
        //container info put in array
        if (!empty($request->container_no)) {
            foreach ($request->container_no as $k => $containerNo) {
                $containerInfo[$k] = $containerNo;
            }
        }

        //save ets eta
        $delivery = Delivery::find($request->shipment_id);
        $delivery->shipping_line = $request->shipping_line;
        $delivery->container_no = !empty($containerInfo) ? json_encode($containerInfo) : '';

        if ($delivery->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.CARRIER_INFO_SET_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.CARRIER_INFO_COULD_NOT_BE_SET')), 401);
        }
    }

    //BL METHOD
    public function getBlInfo(Request $request) {
        $statusList = [
            '0' => __('label.SELECT_STATUS_OPT')
            , '1' => __('label.NORMAL')
            , '2' => __('label.HAPPY')
            , '3' => __('label.UNHAPPY')
        ];

        //find the requested shipment data
        $target = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                        ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.bl_date'
                                , 'delivery.express_tracking_no', 'delivery.last_shipment'
                                , 'inquiry.order_no', 'inquiry.buyer_id')
                        ->where('delivery.id', $request->shipment_id)->first();

        //inquiry Details 
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                                , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                                , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                                , 'inquiry_details.id')
                        ->where('inquiry_details.inquiry_id', $target->inquiry_id)->get();

        //shipment quantity details
        $deliveryDetailsArr = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                        ->select('delivery_details.shipment_quantity', 'delivery_details.inquiry_details_id'
                                , 'delivery_details.delivery_id')
                        ->where('delivery.inquiry_id', $target->inquiry_id)->get();

        $shipmentQuantityArr = $quantitySumArr = $dueQuantityArr = [];
        if (!$deliveryDetailsArr->isEmpty()) {
            foreach ($deliveryDetailsArr as $deliveryDetails) {
                $shipmentQuantityArr[$deliveryDetails->inquiry_details_id][$deliveryDetails->delivery_id] = $deliveryDetails->shipment_quantity;
                $quantitySumArr[$deliveryDetails->inquiry_details_id] = array_sum($shipmentQuantityArr[$deliveryDetails->inquiry_details_id]);
            }
        }

        $surplusQuantityArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                //find remaining quantity of order
                $quantitySum = !empty($quantitySumArr[$item->id]) ? Helper::numberFormatDigit2($quantitySumArr[$item->id]) : 0.00;
                $dueQuantityArr[$item->id] = $item->quantity - $quantitySum;
                $surplusQuantityArr[$item->id] = $quantitySum - $item->quantity;
            }
        }

        $view = view('confirmedOrder.orderShipment.showSetBlInfo', compact('target', 'request', 'quantitySumArr', 'dueQuantityArr'
                        , 'inquiryDetails', 'shipmentQuantityArr', 'surplusQuantityArr', 'statusList'))->render();
        return response()->json(['html' => $view]);
    }

    public function setBlInfo(Request $request) {
        //validation
        $id = $request->shipment_id;
        $inquiryId = $request->inquiry_id;
        $noDue = $request->no_due;
        $noOfItem = $request->no_of_item;
        $rules = [
            'bl_no' => 'required|unique:delivery,bl_no,' . $id . ',id,inquiry_id,' . $inquiryId,
            'bl_date' => 'required',
        ];

        if (!empty($request->last_shipment) || $noDue == $noOfItem) {
            $rules['status'] = 'required|not_in:0';
            $rules['remarks'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation
        if (count(array_filter($request->shipment_quantity)) == 0) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.PLEASE_INSERT_SHIPMENT_QUANTITY_TO_ATLEAST_ONE_ITEM')), 401);
        }



        $target = Delivery::find($request->shipment_id);
        $target->bl_no = $request->bl_no;
        $target->express_tracking_no = $request->express_tracking_no;
        $target->bl_date = Helper::dateFormatConvert($request->bl_date);
        $target->last_shipment = !empty($request->last_shipment) ? $request->last_shipment : '0';
        $target->payment_status = '1';
        $target->shipment_status = '2';



        $deliveryDetails = $remainingQtyArr = [];
        $i = 0;
        if (count(array_filter($request->shipment_quantity)) != 0) {
            foreach ($request->shipment_quantity as $itemId => $shipmentQuantity) {

                if (!empty($shipmentQuantity)) {
                    $deliveryDetails[$i]['delivery_id'] = $request->shipment_id;
                    $deliveryDetails[$i]['inquiry_details_id'] = $itemId;
                    $deliveryDetails[$i]['shipment_quantity'] = $shipmentQuantity;
                    $deliveryDetails[$i]['created_at'] = date('Y-m-d H:i:s');
                    $deliveryDetails[$i]['created_by'] = Auth::user()->id;
                    $i++;
                }
            }
        }

        //inquiry Details 
        $inquiryDetails = InquiryDetails::where('inquiry_details.inquiry_id', $target->inquiry_id)
                        ->pluck('inquiry_details.quantity', 'inquiry_details.id')->toArray();



        if ($target->save() && DeliveryDetails::insert($deliveryDetails)) {
//            //shipment quantity details
//            $deliveryDetailsArr = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
//                            ->select('delivery_details.shipment_quantity', 'delivery_details.inquiry_details_id'
//                                    , 'delivery_details.delivery_id')
//                            ->where('delivery.inquiry_id', $target->inquiry_id)->get();
//
//            $shipmentQuantityArr = $quantitySumArr = $dueQuantityArr = [];
//            if (!$deliveryDetailsArr->isEmpty()) {
//                foreach ($deliveryDetailsArr as $deliveryDetails) {
//                    $shipmentQuantityArr[$deliveryDetails->inquiry_details_id][$deliveryDetails->delivery_id] = $deliveryDetails->shipment_quantity;
//                    $quantitySumArr[$deliveryDetails->inquiry_details_id] = array_sum($shipmentQuantityArr[$deliveryDetails->inquiry_details_id]);
//                }
//            }
//
//            $isDueArr = [];
//            $isDue = 0;
//            if (!empty($inquiryDetails)) {
//                foreach ($inquiryDetails as $itemId => $quantity) {
//                    //find remaining quantity of order
//                    $dueQuantity = $quantity - ($quantitySumArr[$itemId] ?? 0.00);
//                    $isDueArr[$itemId] = $dueQuantity <= 0 ? 0 : 1;
//                    $isDue = array_sum($isDueArr);
//                }
//            }

            if ($target->last_shipment == '1' || $noDue == $noOfItem) {
                $inquiryOrderStatus = '4';
            } elseif ($target->last_shipment == '0') {
                $inquiryOrderStatus = '3';
            }

            Lead::where('id', $target->inquiry_id)->update(['order_status' => $inquiryOrderStatus]);



            if (!empty($request->last_shipment) || $noDue == $noOfItem) {
                $historyData = [];
                $uniqId = uniqid();

                $orderNo = !empty($request->order_no) ? $request->order_no : '';

                //create new follow up array
                $historyData[$uniqId]['follow_up_date'] = date('d F Y');
                $historyData[$uniqId]['status'] = $request->status;
                $historyData[$uniqId]['order_no'] = $request->order_no;
                $historyData[$uniqId]['remarks'] = $request->remarks;
                $historyData[$uniqId]['updated_by'] = Auth::user()->id;
                $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');


                //merge with previous history and pack in json
                $followUpHistory = BuyerFollowUpHistory::where('buyer_id', $request->buyer_id)->first();

                if (!empty($followUpHistory)) {
                    $preHistoryArr = json_decode($followUpHistory->history, true);
                    $historyArr = array_merge($preHistoryArr, $historyData);
                } else {
                    $followUpHistory = new BuyerFollowUpHistory;
                    $historyArr = $historyData;
                }


                $followUpHistory->buyer_id = $request->buyer_id;
                $followUpHistory->history = json_encode($historyArr);
                $followUpHistory->updated_at = date('Y-m-d H:i:s');
                $followUpHistory->updated_by = Auth::user()->id;
                $followUpHistory->save();
            }
            return Response::json(array('heading' => 'Success', 'message' => __('label.ORDER_SHIPMENT_INFO_SET_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.ORDER_SHIPMENT_INFO_COULD_NOT_BE_SET')), 401);
        }
    }

    //END OF BL METHOD
    //get shipment full detail  modal load
    public function getShipmentFullDetail(Request $request) {
        $loadView = 'confirmedOrder.orderShipment.showShipmentFullDetail';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function newLsdRow(Request $request) {

        $view = view('pendingOrder.addNewRow')->render();
        return response()->json(['html' => $view]);
    }

    public function getLsdInfo(Request $request) {
        $lsdInfo = Lead::select('lsd_info')->where('inquiry.id', $request->inquiry_id)->first();
        $lsdInfoArr = [];
        if (!empty($lsdInfo)) {
            $lsdInfoArr = json_decode($lsdInfo->lsd_info, true);
        }

        $view = view('confirmedOrder.getLsdInfo', compact('lsdInfoArr'))->render();
        return response()->json(['html' => $view]);
    }

    //:::::::Start rwBreakdown :::::::::::

    public function rwBreakdown(Request $request, $id) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwBreakdown($request, $id, $loadView);
    }

    public function rwBreakdownGetBrand(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwBreakdownGetBrand($request, $loadView);
    }

    public function rwBreakdownGetGrade(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwBreakdownGetGrade($request, $loadView);
    }

    public function getRwBreakdownView(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::getRwBreakdownView($request, $loadView);
    }

    public function rwProceedRequest(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwProceedRequest($request, $loadView);
    }

    public function rwProceedRequestEdit(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwProceedRequestEdit($request, $loadView);
    }

    public function rwPreviewRequest(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::rwPreviewRequest($request, $loadView);
    }

    public function rwBreakDownSave(Request $request) {
        return Common::rwBreakDownSave($request);
    }

    public function leadRwBreakdownView(Request $request) {
        $loadView = 'confirmedOrder.rwBreakdown';
        return Common::leadRwBreakdownView($request, $loadView);
    }

    public function getLeadRwParametersName(Request $request) {
        return Common::getLeadRwParametersName($request);
    }

    //::::::::::: END OF RW BREAKDOWN
    //new commission setup modal function
    public function getCommissionSetupModal(Request $request) {
        $loadView = 'confirmedOrder';
        return Common::getCommissionSetupModal($request, $loadView);
    }

    public function commissionSetupSave(Request $request) {
        return Common::commissionSetupSave($request);
    }

    //ENDOF PO GENERATE
    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'confirmedOrder.showQuantitySummaryModal';
        $isConfirmedOrder = 1;
        $statusType = 'order_status';
        $status = ['2', '3'];

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'confirmedOrder.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'confirmedOrder.print.index';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    //end of summary
    //PO GENERATE
    public function poGenerate(Request $request, $id) {
        $target = Lead::find($id);
        $inquiryId = $target->id;

        $poInfo = PoGenerate::join('pre_carrier', 'pre_carrier.id', '=', 'po_generate.pre_carrier_id')
                ->join('shipping_terms', 'shipping_terms.id', '=', 'po_generate.shipping_term_id')
                ->join('payment_terms', 'payment_terms.id', '=', 'po_generate.payment_term_id')
                ->where('inquiry_id', $id)
                ->select('po_generate.id', 'po_generate.inquiry_id', 'po_generate.po_date', 'po_generate.status'
                        , 'po_generate.final_destination', 'po_generate.delivery_date', 'po_generate.note'
                        , 'pre_carrier.name as pre_carrier_name', 'shipping_terms.name as shipping_terms_name'
                        , 'payment_terms.name as payment_terms_name'
                        , 'po_generate.pre_carrier_id', 'po_generate.shipping_term_id', 'po_generate.payment_term_id'
                        , 'po_generate.summary', 'po_generate.summary_status', 'po_generate.shipment_address_status'
                        , 'po_generate.head_office_address', 'po_generate.factory_id'
                        , 'po_generate.hs_code')
                ->first();

        $poSummaryArr = [];
        if (!empty($poInfo)) {
            $poSummaryArr = json_decode($poInfo->summary, true);
        }

        $hsCodeArr = [];
        if (!empty($poInfo)) {
            $hsCodeArr = json_decode($poInfo->hs_code, true);
        }


        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


        $signatoryInfo = SignatoryInfo::first();
        //END OF KONITA INFO

        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                        ->where('supplier.id', $target->supplier_id)
                        ->select('supplier.name as supplier_name', 'supplier.address'
                                , 'country.name as country_name')->first();

        $buyerInfo = Buyer::where('id', $target->buyer_id)->first();


        $buyerOfficeAddress = '';
        $shipmentAddressStatus = '';
        $factoryId = '';
        if (!empty($poInfo)) {
            if ($poInfo->shipment_address_status == '1') {
                $shipmentAddressStatus = '1';
                $buyerOfficeAddress = $poInfo->head_office_address;
            } elseif ($poInfo->shipment_address_status == '2') {
                $shipmentAddressStatus = '2';
                $factoryInfo = BuyerFactory::where('id', $poInfo->factory_id)
                                ->select('address')->first();
                $factoryId = $poInfo->factory_id;
                $buyerOfficeAddress = $factoryInfo->address;
            }
        } else {
            if ($target->shipment_address_status == '1') {
                $shipmentAddressStatus = '1';
                $buyerOfficeAddress = $target->head_office_address;
            } elseif ($target->shipment_address_status == '2') {
                $shipmentAddressStatus = '2';
                $factoryInfo = BuyerFactory::where('id', $target->factory_id)
                                ->select('address')->first();
                $factoryId = $target->factory_id;
                $buyerOfficeAddress = $factoryInfo->address;
            }
        }

        //echo '<pre>';print_r($buyerOfficeAddress);exit;

        $factoryList = ['0' => __('label.SELECT_FACTORY_OPT')] + BuyerFactory::where('status', '1')->where('buyer_id', $target->buyer_id)
                        ->pluck('name', 'id')->toArray();

        $preCarrierList = ['0' => __('label.SELECT_PRE_CARRIER_OPT')] + PreCarrier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $shippingTermList = ['0' => __('label.SELECT_SHIPPING_TERMS_OPT')] + ShippingTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $PaymentTermList = ['0' => __('label.SELECT_PAYMENT_TERMS_OPT')] + PaymentTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $bankList = ['0' => __('label.SELECT_BANK_OPT')] + Bank::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //RW BREAKDOWN
        $measureUnitArr = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('inquiry_details.inquiry_id', $target->id)
                        ->pluck('measure_unit.name', 'product.id')->toArray();

        $rwBreakdownInfo = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->join('product', 'product.id', '=', 'rw_breakdown.product_id')
                        ->join('brand', 'brand.id', '=', 'rw_breakdown.brand_id')
                        ->leftJoin('country', 'country.id', '=', 'brand.origin')
                        ->leftJoin('grade', 'grade.id', '=', 'rw_breakdown.grade_id')
                        ->where('rw_breakdown.inquiry_id', $target->id)
                        ->select('rw_breakdown.*', 'product.name as productName', 'brand.name as brandName'
                                , 'grade.name as gradeName', 'product.hs_code', 'country.name as country_name')->get();



        $inquiryDetailsInfo = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->where('inquiry_details.inquiry_id', $target->id)
                        ->select('inquiry_details.*')->get();

        $finalPriceArr = [];
        if (!$rwBreakdownInfo->isEmpty() && !$inquiryDetailsInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $rwBreakdown) {
                $rwGsmInfo = json_decode($rwBreakdown->gsm, true);
                foreach ($inquiryDetailsInfo as $inquiryDetails) {
                    $gradeId1 = !empty($rwBreakdown->grade_id) ? $rwBreakdown->grade_id : 0;
                    $gradeId2 = !empty($inquiryDetails->grade_id) ? $inquiryDetails->grade_id : 0;
                    if ($rwBreakdown->product_id == $inquiryDetails->product_id) {
                        if ($rwBreakdown->brand_id == $inquiryDetails->brand_id) {
                            if ($gradeId1 == $gradeId2) {
                                if (!empty($inquiryDetails->gsm)) {
                                    $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['unit_price'][$inquiryDetails->gsm] = $inquiryDetails->unit_price;
                                    $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['total_price'][$inquiryDetails->gsm] = $inquiryDetails->total_price;
                                } else {
                                    if (!empty($rwGsmInfo)) {
                                        foreach ($rwGsmInfo as $gsmKey => $gsmInfo) {
                                            $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['unit_price'][$gsmInfo] = $inquiryDetails->unit_price;
                                            $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['total_price'][$gsmInfo] = $inquiryDetails->total_price;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
//echo '<pre>';
//print_r($rwGsmInfo);
//exit;

        $targetArr = [];
        $hsCodeInfo = [];
        $hsCodeList = [];
        //fianl arr
        if (!$rwBreakdownInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $values) {

                $targetArr[$values->id]['unit_name'] = !empty($measureUnitArr[$values->product_id]) ? $measureUnitArr[$values->product_id] : '';
                $targetArr[$values->id]['product_id'] = $values->product_id;
                $targetArr[$values->id]['brand_id'] = $values->brand_id;
                $targetArr[$values->id]['grade_id'] = $values->grade_id;
                $targetArr[$values->id]['product_name'] = $values->productName;
                $targetArr[$values->id]['brand_name'] = !empty($values->brandName) ? $values->brandName : '';
                $targetArr[$values->id]['grade_name'] = !empty($values->gradeName) ? $values->gradeName : '';
                $gradeId = !empty($values->grade_id) ? $values->grade_id : 0;
                if (!empty($values->hs_code)) {
                    $hsCodeInfo[$values->id] = json_decode($values->hs_code, true);
                    foreach ($hsCodeInfo as $rwBreakdownId => $hsCode) {
                        foreach ($hsCode as $item) {
                            $hsCodeList[$rwBreakdownId][$item] = $item;
                        }
                    }
                }



                $targetArr[$values->id]['hs_code'] = $hsCodeList[$values->id];
                $targetArr[$values->id]['country_name'] = $values->country_name;
                $targetArr[$values->id]['format'] = $values->format;


                $targetArr[$values->id]['gsm_info'] = json_decode($values->gsm, true);
                if ($values->format == '2') {
                    if (!empty($targetArr[$values->id]['gsm_info'])) {
                        foreach ($targetArr[$values->id]['gsm_info'] as $key => $gsm) {
                            $unitPrice = !empty($finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['unit_price'][$gsm]) ? $finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['unit_price'][$gsm] : 0;
                            $totalPrice = !empty($finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['total_price'][$gsm]) ? $finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['total_price'][$gsm] : 0;

                            $targetArr[$values->id]['unit_price'] = $unitPrice;
                            $targetArr[$values->id]['total_price'] = $totalPrice;
                        }
                    }
                }
                $targetArr[$values->id]['bf_info'] = json_decode($values->bf, true);
                $targetArr[$values->id]['gsm_details'] = json_decode($values->gsm_details, true);
                $targetArr[$values->id]['rw_unit'] = json_decode($values->rw_unit_id, true);
                $targetArr[$values->id]['core_and_dia'] = $values->core_and_dia;
            }
        }



        $rw_unit = $rwInfo = $rwParameter = $rwUnitIdArr = $gsmDataCountArr = [];
        $totalQuantity = $gsmDataCountSum = $gsmDataArr = [];
        if (!empty($targetArr)) {
            foreach ($targetArr as $id => $item) {
                if (!empty($item['gsm_details'])) {
                    //    Core & Dia Rw Unit Arr
                    if (!empty($item['rw_unit'])) {
                        $rw_unit[$id] = RwUnit::whereIn('id', $item['rw_unit'])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                        $rwInfo[$id] = Helper::arrayToString($rw_unit[$id]);
                    }
                    foreach ($item['gsm_details'] as $gsmId => $item) {
                        foreach ($item as $key => $values) {
                            foreach ($values as $rwUnitId => $val) {

                                if ($rwUnitId != 'quantity') {
                                    // RW PARAMETER Arr
                                    $rwUnitIdArr[$id][$rwUnitId] = $rwUnitId;
                                    $rwParameter[$id] = RwUnit::whereIn('id', $rwUnitIdArr[$id])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                                }

                                if ($rwUnitId == 'quantity') {
                                    //total quantity arr
                                    $totalQuantity[$id] = !empty($totalQuantity[$id]) ? $totalQuantity[$id] : 0;
                                    $totalQuantity[$id] += $val;
                                }

                                $gsmDataArr[$id][$gsmId][$key][$rwUnitId] = $val;
                                //gsm wise rowspan arr
                                $gsmDataCountArr[$id][$gsmId] = !empty($gsmDataArr[$id][$gsmId]) ? count($gsmDataArr[$id][$gsmId]) : 0;
                                //product wise rowsapn arr
                                $gsmDataCountSum[$id] = !empty($gsmDataCountArr[$id]) ? array_sum($gsmDataCountArr[$id]) : 0;
                            }
                        }
                    }
                } //end of if
            }
        }


        //COMMISSION Summary
        $realizationPrice = ProductPricing::select('product_id', 'brand_id', 'grade_id', 'realization_price')->get();
        $realizationPriceArr = [];
        if (!$realizationPrice->isEmpty()) {
            foreach ($realizationPrice as $item) {
                $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                $realizationPriceArr[$item->product_id][$item->brand_id][$gradeId] = $item->realization_price;
            }
        }

        $summaryInfo = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'inquiry.id')
                        ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->where('inquiry_details.inquiry_id', $target->id)
                        ->select('inquiry_details.*', 'commission_setup.konita_cmsn', 'commission_setup.principle_cmsn'
                                , 'commission_setup.sales_person_cmsn', 'commission_setup.buyer_cmsn'
                                , 'commission_setup.rebate_cmsn', 'product.name as productName', 'brand.name as brandName'
                                , 'grade.name as gradeName','commission_setup.inquiry_details_id')->get();

        $summaryArr = $prevComsn = [];
        if (!$summaryInfo->isEmpty()) {
            foreach ($summaryInfo as $item) {
                $gradeId3 = !empty($item->grade_id) ? $item->grade_id : 0;
                $summaryArr[$item->id]['unit_name'] = !empty($measureUnitArr[$item->product_id]) ? $measureUnitArr[$item->product_id] : '';
                $summaryArr[$item->id]['product_name'] = $item->productName;
                $summaryArr[$item->id]['brand_name'] = $item->brandName;
                $summaryArr[$item->id]['grade_name'] = $item->gradeName;
                $summaryArr[$item->id]['gsm'] = $item->gsm;
                $summaryArr[$item->id]['unit_price'] = $item->unit_price;
                $konitaCommission = ($item->konita_cmsn + $item->principle_cmsn + $item->sales_person_cmsn);
                $rebateBuyerCommission = ($item->buyer_cmsn + $item->rebate_cmsn);
                $prevComsn[$item->inquiry_details_id]['konita_commission'] = $konitaCommission;
                $prevComsn[$item->inquiry_details_id]['rebate_buyer_commission'] = $rebateBuyerCommission;
                $summaryArr[$item->id]['realization_price'] = !empty($realizationPriceArr[$item->product_id][$item->brand_id][$gradeId3]) ? $realizationPriceArr[$item->product_id][$item->brand_id][$gradeId3] : 0;
            }
        }


        //END OF COMMMISSION SUMMARY

        if ($request->view == 'print') {
            return view('confirmedOrder.poGenerate.print.index')->with(compact('request', 'target', 'supplierInfo', 'buyerInfo'
                                    , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                    , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                    , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                    , 'phoneNumber', 'poInfo', 'inquiryId', 'signatoryInfo', 'summaryArr'
                                    , 'poSummaryArr', 'buyerOfficeAddress', 'factoryList', 'shipmentAddressStatus'
                                    , 'factoryId', 'hsCodeArr', 'finalPriceArr','prevComsn'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('confirmedOrder.poGenerate.print.index', compact('request', 'target', 'supplierInfo', 'buyerInfo'
                                    , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                    , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                    , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                    , 'phoneNumber', 'poInfo', 'inquiryId', 'signatoryInfo', 'summaryArr'
                                    , 'poSummaryArr', 'buyerOfficeAddress', 'factoryList', 'shipmentAddressStatus'
                                    , 'factoryId', 'hsCodeArr', 'finalPriceArr','prevComsn'))
                    ->setPaper('a3', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($target->purchase_order_no . '.pdf');
//            return $pdf->stream();
        } else {
            return view('confirmedOrder.poGenerate.poGenerate')->with(compact('target', 'supplierInfo', 'buyerInfo'
                                    , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                    , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                    , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                    , 'phoneNumber', 'poInfo', 'inquiryId', 'signatoryInfo', 'summaryArr'
                                    , 'poSummaryArr', 'buyerOfficeAddress', 'factoryList', 'shipmentAddressStatus'
                                    , 'factoryId', 'hsCodeArr', 'finalPriceArr','prevComsn'));
        }
    }

    public function poGenerateSave(Request $request) {
        $summaryArrJsonEncode = json_encode($request->summaryArr);
        $errors = [];



        $rules = [
            'po_date' => 'required',
            'pre_carrier_id' => 'required|not_in:0',
            'shipping_term_id' => 'required|not_in:0',
            'final_destination' => 'required',
            'payment_term_id' => 'required|not_in:0',
            'delivery_date' => 'required',
        ];

        if ($request->shipment_address_status == '1') {
            $rules['head_office_address'] = 'required';
        }
        if ($request->shipment_address_status == '2') {
            $rules['factory_id'] = 'required|not_in:0';
        }

        $message = [];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }




        if ($request->summary_status == '1') {
            if (!empty($request->summaryArr)) {
                $row = 1;
                foreach ($request->summaryArr as $key => $item) {
                    if (empty($item['realization_price'])) {
                        $errors[] = __('label.THE_REALIZATION_PRICE_FIELD_IS_REQUIRED', ['row' => $row]);
                    }
//                    if (empty($item['konita_commission'])) {
//                        $errors[] = __('label.THE_KONITA_CMSN_FIELD_IS_REQUIRED', ['row' => $row]);
//                    }
//                    if (empty($item['rebate_buyer_commission'])) {
//                        $errors[] = __('label.THE_REBATE_BUYER_CMSN_FIELD_IS_REQUIRED', ['row' => $row]);
//                    }
                    if (empty($item['unit_price'])) {
                        $errors[] = __('label.THE_UNIT_PRICE_FIELD_IS_REQUIRED', ['row' => $row]);
                    }
                    $row++;
                }
            }
        }

        if (!empty($request->hs_code)) {
            $row = 1;
            foreach ($request->hs_code as $val) {
                if (empty($val)) {
                    $errors[] = __('label.THE_HS_CODE_FIELD_IS_REQUIRED', ['row' => $row]);
                }
                $row++;
            }
        } else {
            $errors[] = __('label.PLEASE_SELECT_HS_CODE_FOR_PRODUCTS');
        }

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }



        $hscodeJsonEncode = json_encode($request->hs_code);
        $poDate = Helper::dateFormatConvert($request->po_date);
        $deliveryDate = Helper::dateFormatConvert($request->delivery_date);

        if (!empty($request->po_generate_id)) {
            $target = PoGenerate::find($request->po_generate_id);
        } else {
            $target = New PoGenerate;
        }

        $target->inquiry_id = $request->inquiry_id;
        $target->po_date = $poDate;
        $target->pre_carrier_id = $request->pre_carrier_id;
        $target->shipping_term_id = $request->shipping_term_id;
        $target->final_destination = $request->final_destination;
        $target->payment_term_id = $request->payment_term_id;
        $target->delivery_date = $deliveryDate;
        $target->note = $request->note;
        $target->hs_code = !empty($request->hs_code) ? $hscodeJsonEncode : '';
        if ($request->summary_status == '1') {
            $target->summary = !empty($summaryArrJsonEncode) ? $summaryArrJsonEncode : '';
            $target->summary_status = '1';
        } else {
            $target->summary = null;
            $target->summary_status = '0';
        }
        $target->shipment_address_status = $request->shipment_address_status;
        if ($request->shipment_address_status == '1') {
            $target->head_office_address = $request->head_office_address;
            $target->factory_id = null;
        }
        if ($request->shipment_address_status == '2') {
            $target->factory_id = $request->factory_id;
            $target->head_office_address = null;
        }

        $target->status = $request->status;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PO_GENERATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PO_COULD_NOT_BE_GENERATED')), 401);
        }
    }

    //ENDOF PO GENERATE
    //PI GENERATE
    public function piGenerate(Request $request, $id) {
        $target = Lead::find($id);
        $inquiryId = $target->id;


        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                        ->where('supplier.id', $target->supplier_id)
                        ->select('supplier.name as supplier_name', 'supplier.address'
                                , 'country.name as country_name', 'supplier.pi_required'
                                , 'supplier.header_image', 'supplier.signature_image'
                                , 'supplier.default_format')->first();




        $beneficiaryBankList = ['0' => __('label.SELECT_BENEFICIARY_BANK_OPT')] + BeneficiaryBank::where('supplier_id', $target->supplier_id)
                        ->select(DB::raw("CONCAT(name,' (',account_no,')') AS bankName"), 'id')
                        ->pluck('bankName', 'id')->toArray();





        $piInfo = PiGenerate::join('pre_carrier', 'pre_carrier.id', '=', 'pi_generate.pre_carrier_id')
                ->join('shipping_terms', 'shipping_terms.id', '=', 'pi_generate.shipping_term_id')
                ->join('payment_terms', 'payment_terms.id', '=', 'pi_generate.payment_term_id')
                ->join('beneficiary_bank', 'beneficiary_bank.id', '=', 'pi_generate.beneficiary_bank_id')
                ->where('inquiry_id', $id)
                ->select('pi_generate.id', 'pi_generate.inquiry_id', 'pi_generate.po_date', 'pi_generate.status'
                        , 'pi_generate.final_destination', 'pi_generate.delivery_date', 'pi_generate.remarks'
                        , 'pi_generate.buyer_po_no', 'pi_generate.shipping_marks', 'pi_generate.summary'
                        , 'pi_generate.payment_terms_id_2', 'pi_generate.latest_date_shipment'
                        , 'pre_carrier.name as pre_carrier_name', 'shipping_terms.name as shipping_terms_name'
                        , 'payment_terms.name as payment_terms_name'
                        , 'pi_generate.pre_carrier_id', 'pi_generate.shipping_term_id', 'pi_generate.payment_term_id'
                        , 'beneficiary_bank.name as beneficiaryBank_name', 'beneficiary_bank.account_no'
                        , 'beneficiary_bank.customer_id', 'beneficiary_bank.branch', 'pi_generate.beneficiary_bank_id'
                        , 'pi_generate.shipment_address_status', 'pi_generate.head_office_address'
                        , 'pi_generate.factory_id', 'pi_generate.hs_code')
                ->first();

        $summaryArr = [];
        if (!empty($piInfo)) {
            $summaryArr = json_decode($piInfo->summary, true);
        }

        $hsCodeArr = [];
        if (!empty($piInfo)) {
            $hsCodeArr = json_decode($piInfo->hs_code, true);
        }
//        $aer = [];
//        foreach ($hsCodeArr as $key=> $tr){
//            $aer = $tr;
//        }
//        echo '<pre>';
//        print_r($hsCodeArr);
//        
//        exit;
        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $signatoryInfo = SignatoryInfo::first();
        //END OF KONITA INFO
        $buyerInfo = Buyer::where('id', $target->buyer_id)->first();

        $buyerOfficeAddress = '';
        $shipmentAddressStatus = '';
        $factoryId = '';
        if (!empty($piInfo)) {
            if ($piInfo->shipment_address_status == '1') {
                $shipmentAddressStatus = '1';
                $buyerOfficeAddress = $piInfo->head_office_address;
            } elseif ($piInfo->shipment_address_status == '2') {
                $shipmentAddressStatus = '2';
                $factoryInfo = BuyerFactory::where('id', $piInfo->factory_id)
                                ->select('address')->first();
                $factoryId = $piInfo->factory_id;
                $buyerOfficeAddress = $factoryInfo->address;
            }
        } else {
            if ($target->shipment_address_status == '1') {
                $shipmentAddressStatus = '1';
                $buyerOfficeAddress = $target->head_office_address;
            } elseif ($target->shipment_address_status == '2') {
                $shipmentAddressStatus = '2';
                $factoryInfo = BuyerFactory::where('id', $target->factory_id)
                                ->select('address')->first();
                $factoryId = $target->factory_id;
                $buyerOfficeAddress = $factoryInfo->address;
            }
        }

        $factoryList = ['0' => __('label.SELECT_FACTORY_OPT')] + BuyerFactory::where('buyer_id', $target->buyer_id)
                        ->pluck('name', 'id')->toArray();

        $preCarrierList = ['0' => __('label.SELECT_PRE_CARRIER_OPT')] + PreCarrier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $shippingTermList = ['0' => __('label.SELECT_SHIPPING_TERMS_OPT')] + ShippingTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $PaymentTermList = ['0' => __('label.SELECT_PAYMENT_TERMS_OPT')] + PaymentTerm::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $bankList = ['0' => __('label.SELECT_BANK_OPT')] + Bank::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //RW BREAKDOWN
        $measureUnitArr = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('inquiry_details.inquiry_id', $target->id)
                        ->pluck('measure_unit.name', 'product.id')->toArray();

        $rwBreakdownInfo = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->join('product', 'product.id', '=', 'rw_breakdown.product_id')
                        ->join('brand', 'brand.id', '=', 'rw_breakdown.brand_id')
                        ->leftJoin('country', 'country.id', '=', 'brand.origin')
                        ->leftJoin('grade', 'grade.id', '=', 'rw_breakdown.grade_id')
                        ->where('rw_breakdown.inquiry_id', $target->id)
                        ->select('rw_breakdown.*', 'product.name as productName', 'brand.name as brandName'
                                , 'grade.name as gradeName', 'product.hs_code', 'country.name as country_name')->get();




        $inquiryDetailsInfo = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->where('inquiry_details.inquiry_id', $target->id)
                        ->select('inquiry_details.*', 'product.name as productName', 'brand.name as brandName'
                                , 'grade.name as gradeName', 'measure_unit.name as unit_name')->get();


        //DEFAULT PART 
        $finalPriceArr = [];
        if (!$rwBreakdownInfo->isEmpty() && !$inquiryDetailsInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $rwBreakdown) {
                $rwGsmInfo = json_decode($rwBreakdown->gsm, true);
                foreach ($inquiryDetailsInfo as $inquiryDetails) {
                    $gradeId1 = !empty($rwBreakdown->grade_id) ? $rwBreakdown->grade_id : 0;
                    $gradeId2 = !empty($inquiryDetails->grade_id) ? $inquiryDetails->grade_id : 0;
                    if ($rwBreakdown->product_id == $inquiryDetails->product_id) {
                        if ($rwBreakdown->brand_id == $inquiryDetails->brand_id) {
                            if ($gradeId1 == $gradeId2) {
                                if (!empty($inquiryDetails->gsm)) {
                                    $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['unit_price'][$inquiryDetails->gsm] = $inquiryDetails->unit_price;
                                    $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['total_price'][$inquiryDetails->gsm] = $inquiryDetails->total_price;
                                } else {
                                    if (!empty($rwGsmInfo)) {
                                        foreach ($rwGsmInfo as $gsmKey => $gsmInfo) {
                                            $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['unit_price'][$gsmInfo] = $inquiryDetails->unit_price;
                                            $finalPriceArr[$rwBreakdown->id][$rwBreakdown->product_id][$rwBreakdown->brand_id][$gradeId1]['total_price'][$gsmInfo] = $inquiryDetails->total_price;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $targetArr = [];
        //fianl arr
        $hsCodeInfo = [];
        $hsCodeList = [];
        if (!$rwBreakdownInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $values) {

                $targetArr[$values->id]['unit_name'] = !empty($measureUnitArr[$values->product_id]) ? $measureUnitArr[$values->product_id] : '';
                $targetArr[$values->id]['product_name'] = $values->productName;
                $targetArr[$values->id]['brand_name'] = !empty($values->brandName) ? $values->brandName : '';
                $targetArr[$values->id]['grade_name'] = !empty($values->gradeName) ? $values->gradeName : '';
                $targetArr[$values->id]['product_id'] = $values->product_id;
                $targetArr[$values->id]['brand_id'] = $values->brand_id;
                $targetArr[$values->id]['grade_id'] = $values->grade_id;

                $gradeId = !empty($values->grade_id) ? $values->grade_id : 0;

                if (!empty($values->hs_code)) {
                    $hsCodeInfo[$values->id] = json_decode($values->hs_code, true);
                    foreach ($hsCodeInfo as $rwBreakdownId => $hsCode) {
                        foreach ($hsCode as $item) {
                            $hsCodeList[$rwBreakdownId][$item] = $item;
                        }
                    }
                }

                $targetArr[$values->id]['hs_code'] = $hsCodeList[$values->id];
                $targetArr[$values->id]['country_name'] = $values->country_name;
                $targetArr[$values->id]['format'] = $values->format;
                $targetArr[$values->id]['gsm_info'] = json_decode($values->gsm, true);
                if ($values->format == '2') {
                    if (!empty($targetArr[$values->id]['gsm_info'])) {
                        foreach ($targetArr[$values->id]['gsm_info'] as $key => $gsm) {
                            $unitPrice = !empty($finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['unit_price'][$gsm]) ? $finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['unit_price'][$gsm] : 0;
                            $totalPrice = !empty($finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['total_price'][$gsm]) ? $finalPriceArr[$values->id][$values->product_id][$values->brand_id][$gradeId]['total_price'][$gsm] : 0;

                            $targetArr[$values->id]['unit_price'] = $unitPrice;
                            $targetArr[$values->id]['total_price'] = $totalPrice;
                        }
                    }
                }
                $targetArr[$values->id]['bf_info'] = json_decode($values->bf, true);
                $targetArr[$values->id]['gsm_details'] = json_decode($values->gsm_details, true);
                $targetArr[$values->id]['rw_unit'] = json_decode($values->rw_unit_id, true);
                $targetArr[$values->id]['core_and_dia'] = $values->core_and_dia;
            }
        }

        $rw_unit = $rwInfo = $rwParameter = $rwUnitIdArr = $gsmDataCountArr = [];
        $totalQuantity = $gsmDataCountSum = $gsmDataArr = [];
        if (!empty($targetArr)) {
            foreach ($targetArr as $id => $item) {
                if (!empty($item['gsm_details'])) {
                    //    Core & Dia Rw Unit Arr
                    if (!empty($item['rw_unit'])) {
                        $rw_unit[$id] = RwUnit::whereIn('id', $item['rw_unit'])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                        $rwInfo[$id] = Helper::arrayToString($rw_unit[$id]);
                    }
                    foreach ($item['gsm_details'] as $gsmId => $item) {
                        foreach ($item as $key => $values) {
                            foreach ($values as $rwUnitId => $val) {

                                if ($rwUnitId != 'quantity') {
                                    // RW PARAMETER Arr
                                    $rwUnitIdArr[$id][$rwUnitId] = $rwUnitId;
                                    $rwParameter[$id] = RwUnit::whereIn('id', $rwUnitIdArr[$id])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                                }

                                if ($rwUnitId == 'quantity') {
                                    //total quantity arr
                                    $totalQuantity[$id] = !empty($totalQuantity[$id]) ? $totalQuantity[$id] : 0;
                                    $totalQuantity[$id] += $val;
                                }

                                $gsmDataArr[$id][$gsmId][$key][$rwUnitId] = $val;
                                //gsm wise rowspan arr
                                $gsmDataCountArr[$id][$gsmId] = !empty($gsmDataArr[$id][$gsmId]) ? count($gsmDataArr[$id][$gsmId]) : 0;
                                //product wise rowsapn arr
                                $gsmDataCountSum[$id] = !empty($gsmDataCountArr[$id]) ? array_sum($gsmDataCountArr[$id]) : 0;
                            }
                        }
                    }
                } //end of if
            }
        }


        //END OF DEFAULT PART

        if ($supplierInfo->default_format == '1') {
            if ($request->view == 'print') {
                return view('confirmedOrder.piGenerate.print.default')->with(compact('request', 'target', 'supplierInfo', 'buyerInfo'
                                        , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                        , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                        , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                        , 'phoneNumber', 'inquiryId', 'signatoryInfo', 'inquiryDetailsInfo'
                                        , 'beneficiaryBankList', 'summaryArr', 'piInfo', 'factoryList'
                                        , 'shipmentAddressStatus', 'factoryId', 'buyerOfficeAddress'
                                        , 'hsCodeArr', 'finalPriceArr'));
            } elseif ($request->view == 'pdf') {
                $pdf = PDF::loadView('confirmedOrder.piGenerate.print.default', compact('request', 'target', 'supplierInfo', 'buyerInfo'
                                        , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                        , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                        , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                        , 'phoneNumber', 'inquiryId', 'signatoryInfo', 'inquiryDetailsInfo'
                                        , 'beneficiaryBankList', 'summaryArr', 'piInfo', 'factoryList'
                                        , 'shipmentAddressStatus', 'factoryId', 'buyerOfficeAddress'
                                        , 'hsCodeArr', 'finalPriceArr'))
                        ->setPaper('a3', 'landscape')
                        ->setOptions(['defaultFont' => 'sans-serif']);
                return $pdf->download($target->order_no . '.pdf');
//                return $pdf->stream();
            } else {
                return view('confirmedOrder.piGenerate.default')->with(compact('request', 'target', 'supplierInfo', 'buyerInfo'
                                        , 'preCarrierList', 'shippingTermList', 'PaymentTermList', 'bankList'
                                        , 'targetArr', 'rw_unit', 'rwInfo', 'rwParameter', 'rwUnitIdArr', 'gsmDataCountArr'
                                        , 'totalQuantity', 'gsmDataCountSum', 'gsmDataArr', 'konitaInfo'
                                        , 'phoneNumber', 'inquiryId', 'signatoryInfo', 'inquiryDetailsInfo'
                                        , 'beneficiaryBankList', 'summaryArr', 'piInfo', 'factoryList'
                                        , 'shipmentAddressStatus', 'factoryId', 'buyerOfficeAddress'
                                        , 'hsCodeArr', 'finalPriceArr'));
            }
        } else {
            return view('confirmedOrder.piGenerate.noFormat');
        }
    }

    public function piGenerateSave(Request $request) {
        //START VALIDATION
        $message = [];
        $rules = [
            'po_date' => 'required',
            'buyer_po_no' => 'required',
            'pre_carrier_id' => 'required|not_in:0',
            'shipping_term_id' => 'required|not_in:0',
            'final_destination' => 'required',
            'payment_term_id' => 'required|not_in:0',
            'delivery_date' => 'required',
            'shipping_marks' => 'required',
            'beneficiary_bank_id' => 'required|not_in:0',
            'remarks' => 'required',
            'latest_date_shipment' => 'required',
        ];

        if ($request->shipment_address_status == '1') {
            $rules['head_office_address'] = 'required';
        }
        if ($request->shipment_address_status == '2') {
            $rules['factory_id'] = 'required|not_in:0';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $errors = [];
        if (!empty($request->summaryArr)) {
            foreach ($request->summaryArr as $key => $item) {
                if (empty($item['price_fob'])) {
                    $errors[] = __('label.THE_PRICE_FOB_FIELD_IS_REQUIRED');
                }
            }
        }

        if (!empty($request->hs_code)) {
            $row = 1;
            foreach ($request->hs_code as $id => $val) {
                if (empty($val)) {
                    $errors[] = __('label.THE_HS_CODE_FIELD_IS_REQUIRED', ['row' => $row]);
                }
                $row++;
            }
        } else {
            $errors[] = __('label.PLEASE_SELECT_HS_CODE_FOR_PRODUCTS');
        }

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }

        //END OF VALIDATION
        //DATA SAVE PART

        $poDate = Helper::dateFormatConvert($request->po_date);
        $deliveryDate = Helper::dateFormatConvert($request->delivery_date);
        $latestDateShipment = Helper::dateFormatConvert($request->latest_date_shipment);
        $summaryArr = json_encode($request->summaryArr);
        $hscodeJsonEncode = json_encode($request->hs_code);


        if (!empty($request->pi_generate_id)) {
            $target = PiGenerate::find($request->pi_generate_id);
        } else {
            $target = New PiGenerate;
        }

        $target->inquiry_id = $request->inquiry_id;
        $target->po_date = !empty($request->po_date) ? $poDate : null;
        $target->buyer_po_no = $request->buyer_po_no;
        $target->pre_carrier_id = $request->pre_carrier_id;
        $target->shipping_term_id = $request->shipping_term_id;
        $target->final_destination = $request->final_destination;
        $target->payment_term_id = $request->payment_term_id;
        $target->delivery_date = !empty($request->delivery_date) ? $deliveryDate : null;

        $target->shipping_marks = $request->shipping_marks;
        $target->summary = !empty($summaryArr) ? $summaryArr : null;
//        $target->payment_terms_id_2 = $request->payment_terms_id_2;
        $target->beneficiary_bank_id = $request->beneficiary_bank_id;
        $target->remarks = $request->remarks;
        $target->hs_code = !empty($request->hs_code) ? $hscodeJsonEncode : '';

        $target->latest_date_shipment = !empty($request->latest_date_shipment) ? $latestDateShipment : null;
        $target->shipment_address_status = $request->shipment_address_status;
        if ($request->shipment_address_status == '1') {
            $target->head_office_address = $request->head_office_address;
            $target->factory_id = null;
        }
        if ($request->shipment_address_status == '2') {
            $target->factory_id = $request->factory_id;
            $target->head_office_address = null;
        }


        $target->status = $request->status;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PI_GENERATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PI_COULD_NOT_BE_GENERATED')), 401);
        }
        //END OF SAVE
    }

    //ENDOF PI GENERATE
    //GET FACTORY ADDRESS
    public function getFactoryAddress(Request $request) {
        $factoryAddressInfo = BuyerFactory::where('id', $request->factory_id)
                        ->select('address')->first();
        $address = '';
        if (!empty($factoryAddressInfo)) {
            $address = $factoryAddressInfo->address;
        }
        return response()->json(['address' => $address]);
    }

    //update tracking no
    public function updateTrackingNo(Request $request) {
        return Common::updateTrackingNo($request);
    }

    //lead time
    public function getLeadTime(Request $request) {
        $loadView = 'confirmedOrder.showLeadTime';
        return Common::getLeadTime($request, $loadView);
    }

    //start messaging
    public function getOrderMessaging(Request $request) {
        $loadView = 'confirmedOrder.showOrderMessaging';
        return Common::getOrderMessaging($request, $loadView);
    }

    public function setMessage(Request $request) {
        $loadView = 'confirmedOrder.showMessagebody';
        return Common::setMessage($request, $loadView);
    }

    //end messaging
}
