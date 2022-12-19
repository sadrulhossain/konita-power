<?php

namespace App\Http\Controllers;

use Validator;
use App\Brand;
use App\Grade;
use App\Product;
use App\ProductToBrand;
use App\ProductToGrade;
use App\SalesPersonToProduct;
use App\ProductPricingHistory;
use App\InquiryDetails;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class ProductToGRADEController extends Controller {

    public function index(Request $request) {
        //check logged in user's related products
        $userSalesPerson = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id', 'product_id')->toArray();

        $productList = Product::where('competitors_product', '0');

        /*
         * if logged in user is not super admin,get all products
         * else, get only assigned product 
         */
        if (Auth::user()->group_id != 1) {
            $productList = $productList->whereIn('id', $userSalesPerson);
        }
        $productList = $productList->where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productList;

        $brandArr = ['0' => __('label.SELECT_BRAND_OPT')];

        $gradeArr = $gradeRelateToProduct = $inactiveGradeArr = [];
        $dependentGradeArr = [];
        if (!empty($request->get('product_id')) && !empty($request->get('brand_id'))) {
            $brandList = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                            ->where('product_to_brand.product_id', $request->get('product_id'))
                            ->where('product_to_brand.has_grade', '1')
                            ->orderBy('brand.name', 'asc')
                            ->pluck('brand.name', 'product_to_brand.brand_id')->toArray();
            $brandArr = ['0' => __('label.SELECT_BRAND_OPT')] + $brandList;

            $gradeArr = Grade::select('id', 'name')->where('status', '1')->orderBy('order', 'asc')->get()->toArray();

            $inactiveGradeArr = Grade::where('status', '2')->pluck('id')->toArray();

            //find products already related to any supplier
            $relatedGradeArr = ProductToGrade::select('grade_id')->where('product_id', $request->get('product_id'))
                            ->where('brand_id', $request->get('brand_id'))->get();

            //mark all barnds related to the selected product

            if (!$relatedGradeArr->isEmpty()) {
                foreach ($relatedGradeArr as $relatedGrade) {
                    $gradeRelateToProduct[$relatedGrade->grade_id] = $relatedGrade->grade_id;
                }
            }

            //dependency check
            //dependent on product pricing history
            $productPricingHistoryRecord = ProductPricingHistory::select('grade_id')
                            ->where('product_id', $request->get('product_id'))
                            ->where('brand_id', $request->get('brand_id'))->get();
            if (!$productPricingHistoryRecord->isEmpty()) {
                foreach ($productPricingHistoryRecord as $productPricingHistory) {
                    $dependentGradeArr[$request->get('product_id')][$request->get('brand_id')][$productPricingHistory->grade_id] = $productPricingHistory->grade_id;
                }
            }

            //dependent on inquiry details
            $inquiryDetailsRecord = InquiryDetails::select('grade_id')
                            ->where('product_id', $request->get('product_id'))
                            ->where('brand_id', $request->get('brand_id'))->get();
            if (!$inquiryDetailsRecord->isEmpty()) {
                foreach ($inquiryDetailsRecord as $inquiryDetails) {
                    $dependentGradeArr[$request->get('product_id')][$request->get('brand_id')][$inquiryDetails->grade_id] = $inquiryDetails->grade_id;
                }
            }
        }
        return view('productToGrade.index')->with(compact('productArr', 'brandArr', 'gradeArr'
                                , 'gradeRelateToProduct', 'request', 'dependentGradeArr'
                                , 'inactiveGradeArr'));
    }

    public function getBrands(Request $request) {
        $brandIdArr = ProductPricingHistory::where('product_id', $request->product_id)
                        ->whereNull('grade_id')->pluck('brand_id')->toArray();

        $brandList = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->where('product_to_brand.product_id', $request->product_id)
                        ->where('product_to_brand.has_grade', '1')
                        ->whereNotIn('product_to_brand.brand_id', $brandIdArr)
                        ->where('brand.status', '1')
                        ->orderBy('brand.name', 'asc')
                        ->pluck('brand.name', 'product_to_brand.brand_id')->toArray();
        $brandArr = ['0' => __('label.SELECT_BRAND_OPT')] + $brandList;

        $view = view('productToGrade.showBrands', compact('brandArr', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    public function getGradesToRelate(Request $request) {

        //get all product list
        $gradeRelateToProduct = $inactiveGradeArr = [];

        $gradeArr = Grade::select('id', 'name')->where('status', '1')->orderBy('order', 'asc')->get();
        
        $inactiveGradeArr = Grade::where('status', '2')->pluck('id')->toArray();
        
        //find products already related to any supplier
        $relatedGradeArr = ProductToGrade::select('grade_id')->where('product_id', $request->product_id)
                        ->where('brand_id', $request->brand_id)->get();

        //mark all barnds related to the selected product

        if (!$relatedGradeArr->isEmpty()) {
            foreach ($relatedGradeArr as $relatedGrade) {
                $gradeRelateToProduct[$relatedGrade->grade_id] = $relatedGrade->grade_id;
            }
        }

        //dependency check
        $dependentGradeArr = [];
        //dependent on product pricing history
        $productPricingHistoryRecord = ProductPricingHistory::select('grade_id')
                        ->where('product_id', $request->product_id)
                        ->where('brand_id', $request->brand_id)->get();
        if (!$productPricingHistoryRecord->isEmpty()) {
            foreach ($productPricingHistoryRecord as $productPricingHistory) {
                $dependentGradeArr[$request->product_id][$request->brand_id][$productPricingHistory->grade_id] = $productPricingHistory->grade_id;
            }
        }

        //dependent on inquiry details
        $inquiryDetailsRecord = InquiryDetails::select('grade_id')
                        ->where('product_id', $request->product_id)
                        ->where('brand_id', $request->brand_id)->get();
        if (!$inquiryDetailsRecord->isEmpty()) {
            foreach ($inquiryDetailsRecord as $inquiryDetails) {
                $dependentGradeArr[$request->product_id][$request->brand_id][$inquiryDetails->grade_id] = $inquiryDetails->grade_id;
            }
        }

        //end :: dependency check
//        echo '<pre>';
//        print_r($dependentGradeArr);
//        exit;

        $view = view('productToGrade.showGrades', compact('gradeArr', 'gradeRelateToProduct'
                        , 'request', 'dependentGradeArr', 'inactiveGradeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedGrades(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::select('name')->where('id', $request->product_id)->first();
        $brand = Brand::select('name')->where('id', $request->brand_id)->first();

        $relatedGradeArr = ProductToGrade::select('grade_id')->where('product_id', $request->product_id)
                        ->where('brand_id', $request->brand_id)->get();

        // Make array selected Product of related Grade's  
        $gradeRelateToProduct = [];
        if (!$relatedGradeArr->isEmpty()) {
            foreach ($relatedGradeArr as $relatedGrade) {
                $gradeRelateToProduct[$relatedGrade->grade_id] = $relatedGrade->grade_id;
            }
        }

        // Get Details of Related Grade
        $gradeArr = [];
        if (isset($gradeRelateToProduct)) {
            $gradeArr = Grade::whereIn('id', $gradeRelateToProduct)
                            ->select('name')->where('status', '1')
                            ->orderBy('order', 'asc')->get()->toArray();
        }

        $inactiveGradeArr = Grade::where('status', '2')->pluck('id')->toArray();
        
        $view = view('productToGrade.showRelatedGrades', compact('brand', 'request'
                        , 'product', 'gradeArr', 'inactiveGradeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateProductToGrade(Request $request) {
//        echo '<pre>';
//        print_r($request->all());exit;
        $rules = [
            'product_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'grade' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($request->grade)) {
            foreach ($request->grade as $gradeId) {
                //data entry to product pricing table
                $target[$i]['product_id'] = $request->product_id;
                $target[$i]['brand_id'] = $request->brand_id;
                $target[$i]['grade_id'] = $gradeId;
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        ProductToGrade::where('product_id', $request->product_id)->where('brand_id', $request->brand_id)->delete();

        if (ProductToGrade::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_GRADE_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_GRADE')), 401);
        }
    }

}
