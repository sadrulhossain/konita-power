<?php

namespace App\Http\Controllers;

use Validator;
use App\Lead; //model class
use App\Buyer; //model class
use App\Product; //model class
use App\Designation; //model class
use App\User; //model class
use App\BuyerFactory; //model class
use App\RwUnit; //model class
use App\RwBreakdown; //model class
use App\Order; //model class
use App\BuyerToProduct; //model class
use App\SalesPersonToProduct; //model class
use App\SalesPersonToBuyer; //model class
use App\SupplierToProduct; //model class
use App\InquiryDetails;
use App\ProductToGrade;
use App\FollowUpHistory;
use App\CommissionSetup;
use App\Quotation;
use App\Brand;
use App\Grade;
use App\CauseOfFailure;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use DB;
use Illuminate\Http\Request;
use Helper;

//LEAD/INQUIRY Controller
class CancelledInquiryController extends Controller {

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

        //BRAND LIST
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();


        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.status', '3');
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


        $targetArr = Lead::Join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                ->leftJoin('buyer_factory', 'buyer_factory.id', '=', 'inquiry.factory_id')
                ->where('inquiry.status', '3');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }
        $targetArr = $targetArr->select('inquiry.cancel_remarks', 'inquiry.buyer_contact_person', 'inquiry.id'
                        , 'inquiry.creation_date', 'inquiry.head_office_address'
                        , 'buyer.name as buyerName'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                        , 'buyer_factory.name as factoryName', 'inquiry.status'
                        , 'inquiry.shipment_address_status', 'inquiry.cancel_cause')
                ->orderBy('inquiry.creation_date', 'desc');

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

        //begin filtering
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/supplier?page=' . $page);
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
                        ->where('inquiry.status', '3')->pluck('inquiry.id')->toArray();

        $hasActivityArr = Lead::join('crm_activity_log', 'crm_activity_log.opportunity_id', '=', 'inquiry.opportunity_id')
                        ->where('inquiry.status', '3')->pluck('inquiry.id')->toArray();

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


        $causeList = CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        return view('cancelledInquiry.index')->with(compact('request', 'qpArr', 'targetArr', 'buyerList'
                                , 'salesPersonList', 'inquiryDetailsArr', 'rowspanArr','productArr', 'brandArr'
                                , 'gradeArr', 'productList', 'brandList', 'hasFollowupList'
                                , 'causeList'));
    }

    public function filter(Request $request) {
        $url = 'buyer_id=' . $request->buyer_id . '&salespersons_id=' . $request->salespersons_id
                . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id;
        return Redirect::to('cancelledInquiry?' . $url);
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
//        $dependencyArr = [
//            'ProductToSupplier' => ['1' => 'supplier_id'],
//            'Recipe' => ['1' => 'supplier_id'],
//            'BatchRecipe' => ['1' => 'supplier_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
//                    return redirect('supplier' . $pageNumber);
//                }
//            }
//        }
        //Remove data from child table
        RwBreakdown::where('inquiry_id', $target->id)->delete();
        InquiryDetails::where('inquiry_id', $target->id)->delete();
        FollowUpHistory::where('inquiry_id', $target->id)->delete();
        CommissionSetup::where('inquiry_id', $target->id)->delete();
        Quotation::where('inquiry_id', $target->id)->delete();

        if ($target->delete()) {
            Session::flash('error', __('label.CANCELLED_INQUIRY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CANCELLED_INQUIRY_COULD_NOT_BE_DELETED'));
        }
        return redirect('cancelledInquiry' . $pageNumber);
    }

    //follow up
    public function getFollowUpModal(Request $request) {
        $loadView = 'cancelledInquiry.showFollowUpModal';
        return Common::getFollowUpModal($request, $loadView);
    }

    public function setFollowUpSave(Request $request) {
        return Common::setFollowUpSave($request);
    }

    //reactivate cancelled inquiry
    public function reactivate(Request $request) {
        $target = Lead::find($request->inquiry_id);

        $target->status = '1';

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INQUIRY_IS_REACTIVATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INQUIRY_COULD_NOT_BE_REACTIVATED')], 401);
        }
    }

    //product wise total quantiry summary
    public function quantitySummaryView(Request $request) {
        $loadView = 'cancelledInquiry.showQuantitySummaryModal';
        $isConfirmedOrder = 0;
        $statusType = 'status';
        $status = '3';

        return Common::quantitySummaryView($request, $loadView, $isConfirmedOrder, $statusType, $status);
    }

    //end of summary
}
