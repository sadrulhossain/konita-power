<?php

namespace App\Http\Controllers;

use Validator;
use App\Brand;
use App\Product;
use App\ProductToBrand;
use App\SalesPersonToProduct;
use App\BuyerToProduct;
use App\SupplierToProduct;
use App\ProductToGrade;
use App\ProductPricingHistory;
use Response;
use Auth;
use Helper;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class ProductToBrandController extends Controller {

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

        $brandArr = $brandRelateToProduct = $brandRelateToProductHasGrade = [];
        $dependentBrandArr = $dependentHasGradeArr = $inactiveBrandArr = [];
        if (!empty($request->get('product_id'))) {
            //get all product list

            $brandArr = Brand::select('brand.id', 'brand.name', 'brand.logo')
                            ->orderBy('name', 'asc')->get()->toArray();
            $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

            //find products already related to any supplier
            $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id', 'product_to_brand.has_grade')
                    ->where('product_to_brand.product_id', $request->get('product_id'))
                    ->get();

            //mark all barnds related to the selected product

            if (!$relatedBrandArr->isEmpty()) {
                foreach ($relatedBrandArr as $relatedBrand) {
                    $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
                    $brandRelateToProductHasGrade[$relatedBrand->brand_id] = $relatedBrand->has_grade;
                }
            }

            //dependency check
            //dependent on sales person to product
            $salesPersonToProductRecord = SalesPersonToProduct::select('brand_id')->where('product_id', $request->product_id)->get();
            if (!$salesPersonToProductRecord->isEmpty()) {
                foreach ($salesPersonToProductRecord as $salesPersonToProduct) {
                    $dependentBrandArr[$request->product_id][$salesPersonToProduct->brand_id] = $salesPersonToProduct->brand_id;
                }
            }

            //dependent on buyer to product
            $buyerToProductRecord = BuyerToProduct::select('brand_id')->where('product_id', $request->product_id)->get();
            if (!$buyerToProductRecord->isEmpty()) {
                foreach ($buyerToProductRecord as $buyerToProduct) {
                    $dependentBrandArr[$request->product_id][$buyerToProduct->brand_id] = $buyerToProduct->brand_id;
                }
            }

            //dependent on supplier to product
            $supplierToProductRecord = SupplierToProduct::select('brand_id')->where('product_id', $request->get('product_id'))->get();
            if (!$supplierToProductRecord->isEmpty()) {
                foreach ($supplierToProductRecord as $supplierToProduct) {
                    $dependentBrandArr[$request->get('product_id')][$supplierToProduct->brand_id] = $supplierToProduct->brand_id;
                }
            }

            //dependent on product to grade
            $productToGradeRecord = ProductToGrade::select('brand_id')->where('product_id', $request->get('product_id'))->get();
            if (!$productToGradeRecord->isEmpty()) {
                foreach ($productToGradeRecord as $productToGrade) {
                    $dependentBrandArr[$request->get('product_id')][$productToGrade->brand_id] = $productToGrade->brand_id;
                    $dependentHasGradeArr[$request->get('product_id')][$productToGrade->brand_id] = $productToGrade->brand_id;
                }
            }

            //dependent on product pricing history
            $productPricingHistoryRecord = ProductPricingHistory::select('brand_id')->where('product_id', $request->get('product_id'))->get();
            if (!$productPricingHistoryRecord->isEmpty()) {
                foreach ($productPricingHistoryRecord as $productPricingHistory) {
                    $dependentBrandArr[$request->get('product_id')][$productPricingHistory->brand_id] = $productPricingHistory->brand_id;
                    $dependentHasGradeArr[$request->get('product_id')][$productPricingHistory->brand_id] = $productPricingHistory->brand_id;
                }
            }

            //end :: dependency check
        }
        return view('productToBrand.index')->with(compact('productArr', 'brandArr', 'brandRelateToProduct', 'request'
                                , 'brandRelateToProductHasGrade', 'dependentBrandArr', 'dependentHasGradeArr'
                                , 'inactiveBrandArr'));
    }

    public function getBrandsToRelate(Request $request) {

        //get all product list
        $brandRelateToProduct = $brandRelateToProductHasGrade = $inactiveBrandArr = [];

        $brandArr = Brand::select('brand.id', 'brand.name', 'brand.logo')
                        ->orderBy('name', 'asc')->get();

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

        //find products already related to any supplier
        $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id', 'product_to_brand.has_grade')
                ->where('product_to_brand.product_id', $request->product_id)
                ->get();

        //mark all barnds related to the selected product

        if (!$relatedBrandArr->isEmpty()) {
            foreach ($relatedBrandArr as $relatedBrand) {
                $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
                $brandRelateToProductHasGrade[$relatedBrand->brand_id] = $relatedBrand->has_grade;
            }
        }

        //dependency check
        $dependentBrandArr = $dependentHasGradeArr = [];

        //dependent on sales person to product
        $salesPersonToProductRecord = SalesPersonToProduct::select('brand_id')->where('product_id', $request->product_id)->get();
        if (!$salesPersonToProductRecord->isEmpty()) {
            foreach ($salesPersonToProductRecord as $salesPersonToProduct) {
                $dependentBrandArr[$request->product_id][$salesPersonToProduct->brand_id] = $salesPersonToProduct->brand_id;
            }
        }

        //dependent on buyer to product
        $buyerToProductRecord = BuyerToProduct::select('brand_id')->where('product_id', $request->product_id)->get();
        if (!$buyerToProductRecord->isEmpty()) {
            foreach ($buyerToProductRecord as $buyerToProduct) {
                $dependentBrandArr[$request->product_id][$buyerToProduct->brand_id] = $buyerToProduct->brand_id;
            }
        }

        //dependent on supplier to product
        $supplierToProductRecord = SupplierToProduct::select('brand_id')->where('product_id', $request->product_id)->get();
        if (!$supplierToProductRecord->isEmpty()) {
            foreach ($supplierToProductRecord as $supplierToProduct) {
                $dependentBrandArr[$request->product_id][$supplierToProduct->brand_id] = $supplierToProduct->brand_id;
            }
        }

        //dependent on product to grade
        $productToGradeRecord = ProductToGrade::select('brand_id')->where('product_id', $request->product_id)->get();
        if (!$productToGradeRecord->isEmpty()) {
            foreach ($productToGradeRecord as $productToGrade) {
                $dependentBrandArr[$request->product_id][$productToGrade->brand_id] = $productToGrade->brand_id;
                $dependentHasGradeArr[$request->product_id][$productToGrade->brand_id] = $productToGrade->brand_id;
            }
        }

        //dependent on product pricing history
        $productPricingHistoryRecord = ProductPricingHistory::select('brand_id')->where('product_id', $request->product_id)->get();
        if (!$productPricingHistoryRecord->isEmpty()) {
            foreach ($productPricingHistoryRecord as $productPricingHistory) {
                $dependentBrandArr[$request->product_id][$productPricingHistory->brand_id] = $productPricingHistory->brand_id;
                $dependentHasGradeArr[$request->product_id][$productPricingHistory->brand_id] = $productPricingHistory->brand_id;
            }
        }

        //end :: dependency check
//        echo '<pre>';
//        print_r($dependentBrandArr);
//        exit;

        $view = view('productToBrand.showBrands', compact('brandArr', 'brandRelateToProduct', 'request'
                        , 'brandRelateToProductHasGrade', 'dependentBrandArr', 'dependentHasGradeArr'
                        , 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedBrands(Request $request) {
        // Set Name of Selected Supplier
        $product = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name', 'product.product_code as code', 'product_category.name as category_name')
                        ->where('product.id', $request->product_id)->first();

        $relatedBrandArr = ProductToBrand::select('product_to_brand.brand_id', 'product_to_brand.has_grade')
                ->where('product_to_brand.product_id', $request->product_id)
                ->get();

        // Make array selected Product of related Brand's  
        $brandRelateToProduct = $brandRelateToProductHasGrade = [];
        if (!$relatedBrandArr->isEmpty()) {
            foreach ($relatedBrandArr as $relatedBrand) {
                $brandRelateToProduct[$relatedBrand->brand_id] = $relatedBrand->brand_id;
                $brandRelateToProductHasGrade[$relatedBrand->brand_id] = $relatedBrand->has_grade;
            }
        }

        // Get Details of Related Brand
        $brandArr = [];
        if (isset($brandRelateToProduct)) {
            $brandArr = Brand::whereIn('brand.id', $brandRelateToProduct)
                            ->select('brand.name', 'brand.logo', 'brand.id')
                            ->where('status', '1')
                            ->orderBy('brand.name', 'asc')->get()->toArray();
        }

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();


        

        $view = view('productToBrand.showRelatedBrands', compact('brandArr'
                        , 'relatedBrandArr', 'brandRelateToProduct', 'request', 'product'
                        , 'brandRelateToProductHasGrade', 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateProductToBrand(Request $request) {
        $rules = [
            'product_id' => 'required|not_in:0',
            'brand' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $i = 0;
        $target = [];
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //data entry to product pricing table
                $target[$i]['product_id'] = $request->product_id;
                $target[$i]['brand_id'] = $brandId;
                $target[$i]['has_grade'] = !empty($request->has_grade[$brandId]) ? $request->has_grade[$brandId] : '0';
                $target[$i]['created_by'] = Auth::user()->id;
                $target[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete before inserted 
        ProductToBrand::where('product_id', $request->product_id)->delete();

        if (ProductToBrand::insert($target)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_BRAND_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_BRAND')), 401);
        }
    }

}
