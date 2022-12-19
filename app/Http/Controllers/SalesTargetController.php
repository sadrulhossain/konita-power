<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\SalesPersonToProduct;
use App\SalesTarget;
use App\Lead;
use App\InquiryDetails;
use App\MeasureUnit;
use App\Product;
use Response;
use Auth;
use DB;
use Redirect;
use Helper;
use Session;
use Common;
use Illuminate\Http\Request;

class SalesTargetController extends Controller {

    public function index() {
        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name")
                                , 'users.photo', 'users.id', 'designation.title as designation')
                        ->orderBy('designation.order', 'asc')->orderBy('full_name', 'asc')
                        ->where('users.allowed_for_sales', '1')->where('users.status', '1')
                        ->where('users.supervisor_id', Auth::user()->id)->get();

        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));
        
        $salesTarget = SalesTarget::where('effective_date', $effectiveDate)
                        ->pluck('total_quantity', 'sales_person_id')->toArray();
        
        $salesAchievement = $this->getSalesAchievement($effectiveDate, $deadline);

        return view('salesTarget.index')->with(compact('salesPersonArr', 'salesTarget', 'salesAchievement'));
    }

    public function reloadView() {
        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name")
                                , 'users.photo', 'users.id', 'designation.title as designation')
                        ->orderBy('designation.order', 'asc')->orderBy('full_name', 'asc')
                        ->where('users.allowed_for_sales', '1')->where('users.status', '1')
                        ->where('users.supervisor_id', Auth::user()->id)->get();

        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));

        $salesTarget = SalesTarget::where('effective_date', $effectiveDate)
                        ->pluck('total_quantity', 'sales_person_id')->toArray();
        
        $salesAchievement = $this->getSalesAchievement($effectiveDate, $deadline);

        $gridView = view('salesTarget.reloadedGridView', compact('salesPersonArr', 'salesTarget', 'salesAchievement'))->render();
        $tabularView = view('salesTarget.reloadedTabularView', compact('salesPersonArr', 'salesTarget'))->render();
        return response()->json(['gridView' => $gridView, 'tabularView' => $tabularView]);
    }

    public function showSalesTarget(Request $request) {
        $loadView = 'showSalesTarget';
        $salesPersonList = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"), 'id')
                        ->orderBy('id', 'asc')->get()->pluck('full_name', 'id')->toArray();

        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));

        $salesPersonToProduct = SalesPersonToProduct::select('product_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = $salesPersonToProductArr = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $salesPersonToProductArr[$productId] = $productId;
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        } else if (!$salesPersonToProduct->isEmpty()) {
            foreach ($salesPersonToProduct as $product) {
                $salesPersonToProductArr[$product->product_id] = $product->product_id;
            }
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();


        $view = view('salesTarget.' . $loadView, compact('request', 'salesPersonList'
                        , 'effectiveDate', 'deadline', 'productList', 'salesTarget'
                        , 'targetArr', 'remarks', 'quantity'))->render();
        return response()->json(['html' => $view]);
    }

    public function showSalesTargetDetail(Request $request) {
        $loadView = 'showSalesTargetDetail';
        $salesPersonList = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"), 'id')
                        ->orderBy('id', 'asc')->get()->pluck('full_name', 'id')->toArray();

        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));

        //get sales target by effective date
        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = $salesPersonToProductArr = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $salesPersonToProductArr[$productId] = $productId;
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();


        $view = view('salesTarget.' . $loadView, compact('request', 'salesPersonList'
                        , 'effectiveDate', 'deadline', 'productList', 'salesTarget'
                        , 'targetArr', 'remarks', 'quantity'))->render();
        return response()->json(['html' => $view]);
    }

    public function getSalesTarget(Request $request) {
        $loadView = 'getSalesTarget';
        return Common::getSalesTarget($request, $loadView);
    }

    public function getSalesTargetDetail(Request $request) {
        $loadView = 'getSalesTargetDetail';
        return Common::getSalesTargetDetail($request, $loadView);
    }

    public function setSalesTarget(Request $request) {
        $lockStatus = '0';
        $successMessage = __('label.SALES_TARGET_IS_SET_SUCCESSFULLY');
        $failureMessage = __('label.FAILED_TO_SET_SALES_TARGET');
        return Common::setOrLockSalesTarget($request, $lockStatus, $successMessage, $failureMessage);
    }

    public function lockSalesTarget(Request $request) {
        $lockStatus = '1';
        $successMessage = __('label.SALES_TARGET_IS_SET_AND_LOCKED_SUCCESSFULLY');
        $failureMessage = __('label.FAILED_TO_SET_AND_LOCK_SALES_TARGET');
        return Common::setOrLockSalesTarget($request, $lockStatus, $successMessage, $failureMessage);
    }

    public function getHeirarchyTree() {
        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime(date("Y-m-d")));
        $deadline = date("Y-m-t", strtotime(date("Y-m-d")));


        //heirarchy tree of sales target info
        $userHeirarchyTreeArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select('users.id', 'users.supervisor_id', 'users.photo'
                                , DB::raw("CONCAT(first_name, ' ', last_name) AS full_name")
                                , 'designation.title as designation')
                        ->orderBy('designation.order', 'asc')
                        ->orderBy('users.id', 'asc')
                        ->where('users.allowed_for_sales', '1')
                        ->where('users.status', '1')->get();


        $hierarchyArr = $heirarchyArr = $userArr = [];
        if (!$userHeirarchyTreeArr->isEmpty()) {
            foreach ($userHeirarchyTreeArr as $user) {
                $userArr[$user->id]['name'] = $user->full_name;
                $userArr[$user->id]['designation'] = $user->designation;
                $userArr[$user->id]['photo'] = $user->photo;
                if ($user->supervisor_id != $user->id) {
                    $heirarchyArr[$user->supervisor_id][$user->id] = $user->id;
                }
            }
        }

        if (!empty($heirarchyArr[Auth::user()->id])) {
            foreach ($heirarchyArr[Auth::user()->id] as $userId) {
                $hierarchyArr[Auth::user()->id][$userId] = $userId;
                $hierarchyArr = $this->getRecursive($heirarchyArr, $hierarchyArr, $userId);
            }
        }


        $salesTarget = SalesTarget::where('effective_date', $effectiveDate)
                        ->pluck('total_quantity', 'sales_person_id')->toArray();


        $salesAchievement = $this->getSalesAchievement($effectiveDate, $deadline);

        //end :: heirarchy tree of sales target info

        return view('salesTarget.getHeirarchyTree')->with(compact('heirarchyArr', 'hierarchyArr'
                                , 'salesTarget', 'userArr', 'userHeirarchyTreeArr', 'salesAchievement'));
    }

    public function getRecursive($heirarchyArr, $hierarchyArr, $supervisorId) {
        if (array_key_exists($supervisorId, $heirarchyArr)) {
            foreach ($heirarchyArr[$supervisorId] as $userId) {
                $hierarchyArr[$supervisorId][$userId] = $userId;
                $hierarchyArr = $this->getRecursive($heirarchyArr, $hierarchyArr, $userId);
            }
        }

        return $hierarchyArr;
    }
    
    public function getSalesAchievement($effectiveDate, $deadline) {
        $salesAchievement = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->whereBetween('inquiry.pi_date', [$effectiveDate, $deadline])
                        ->select(DB::raw("SUM(inquiry_details.quantity) as achievement")
                                , 'inquiry.salespersons_id')->groupBy('inquiry.salespersons_id')
                        ->pluck('achievement', 'inquiry.salespersons_id')->toArray();
        return $salesAchievement;
    }

}
