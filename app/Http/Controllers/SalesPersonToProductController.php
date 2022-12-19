<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\User;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\InquiryDetails;
use App\Brand;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class SalesPersonToProductController extends Controller {

    public function index(Request $request) {
        $salesPersonArr = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_sales', '1')->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();

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

        $relatedBrandArr = $brandInfo = [];
        $dependentBrandArr = $inactiveBrandArr = [];
        $productRelatedToSalesPerson = $brandRelatedToSalesPerson = [];
        if (!empty($request->get('product_id')) && !empty($request->get('sales_person_id'))) {
            $relatedProductArr = SalesPersonToProduct::select('brand_id', 'product_id')
                            ->where('sales_person_id', $request->get('sales_person_id'))->get();

            if (!$relatedProductArr->isEmpty()) {
                foreach ($relatedProductArr as $relatedProduct) {
                    $productRelatedToSalesPerson[$relatedProduct->product_id] = $relatedProduct->product_id;
                    $brandRelatedToSalesPerson[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
                }
            }

            $productToBrandArr = ProductToBrand::select('product_id', 'brand_id')->get();


            if (!$productToBrandArr->isEmpty()) {
                foreach ($productToBrandArr as $productToBrandArr) {
                    $relatedBrandArr[$productToBrandArr->product_id][$productToBrandArr->brand_id] = $productToBrandArr->brand_id;
                }
            }

            $brandList = Brand::select('id', 'name', 'logo')->get();

            if (!$brandList->isEmpty()) {
                foreach ($brandList as $brand) {
                    $brandInfo[$brand->id]['name'] = $brand->name;
                    $brandInfo[$brand->id]['logo'] = $brand->logo;
                }
            }

            $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

            //dependency check
            //dependent on inquiry details
            $inquiryDetailsRecord = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                            ->select('brand_id')->where('inquiry_details.product_id', $request->get('product_id'))
                            ->where('inquiry.salespersons_id', $request->get('sales_person_id'))->get();

            if (!$inquiryDetailsRecord->isEmpty()) {
                foreach ($inquiryDetailsRecord as $inquiryDetails) {
                    $dependentBrandArr[$request->get('sales_person_id')][$request->get('product_id')][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
                }
            }

            //end :: dependency check
        }

        $assignedProductList = $dependentProductArr = $inactiveProductArr = [];
        if (!empty($request->get('sales_person_id'))) {
            $assignedProductArr = SalesPersonToProduct::join('product', 'product.id', '=', 'sales_person_to_product.product_id')
                            ->select('sales_person_to_product.product_id', 'product.name as product_name')
                            ->where('sales_person_to_product.sales_person_id', $request->get('sales_person_id'))->get();

            $assignedProductList = [];
            if (!$assignedProductArr->isEmpty()) {
                foreach ($assignedProductArr as $item) {
                    $assignedProductList[$item->product_id] = $item->product_name;
                }
            }
            
            $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();


            //dependency check
            //dependent on inquiry details
            $inquiryDetailsArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                            ->select('inquiry_details.product_id')->where('inquiry.salespersons_id', $request->get('sales_person_id'))->get();

            if (!$inquiryDetailsArr->isEmpty()) {
                foreach ($inquiryDetailsArr as $inquiryDetails) {
                    $dependentProductArr[$request->get('sales_person_id')][$inquiryDetails->product_id] = $inquiryDetails->product_id;
                }
            }

            //end :: dependency check
        }

        return view('salesPersonToProduct.index')->with(compact('salesPersonArr', 'productArr', 'relatedBrandArr'
                                , 'request', 'productRelatedToSalesPerson', 'brandRelatedToSalesPerson'
                                , 'brandInfo', 'dependentBrandArr', 'assignedProductList', 'dependentProductArr'
                                , 'inactiveBrandArr', 'inactiveProductArr'));
    }

    public function getProductsToRelate(Request $request) {
        $relatedProductArr = SalesPersonToProduct::select('brand_id', 'product_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();

        $productRelatedToSalesPerson = $brandRelatedToSalesPerson = $inactiveBrandArr = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToSalesPerson[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToSalesPerson[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

        $productToBrandArr = ProductToBrand::select('product_id', 'brand_id')->get();

        $relatedBrandArr = [];
        if (!$productToBrandArr->isEmpty()) {
            foreach ($productToBrandArr as $productToBrandArr) {
                $relatedBrandArr[$productToBrandArr->product_id][$productToBrandArr->brand_id] = $productToBrandArr->brand_id;
            }
        }

        $brandList = Brand::select('id', 'name', 'logo')->get();
        $brandInfo = [];
        if (!$brandList->isEmpty()) {
            foreach ($brandList as $brand) {
                $brandInfo[$brand->id]['name'] = $brand->name;
                $brandInfo[$brand->id]['logo'] = $brand->logo;
            }
        }

        //dependency check
        $dependentBrandArr = [];

        //dependent on inquiry details
        $inquiryDetailsRecord = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->select('brand_id')->where('inquiry_details.product_id', $request->product_id)
                        ->where('inquiry.salespersons_id', $request->sales_person_id)->get();

        if (!$inquiryDetailsRecord->isEmpty()) {
            foreach ($inquiryDetailsRecord as $inquiryDetails) {
                $dependentBrandArr[$request->sales_person_id][$request->product_id][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
            }
        }

        //end :: dependency check

        $view = view('salesPersonToProduct.showProducts', compact('relatedBrandArr'
                        , 'request', 'productRelatedToSalesPerson', 'brandRelatedToSalesPerson'
                        , 'brandInfo', 'dependentBrandArr', 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedProducts(Request $request) {
        // Set Name of Selected Sales Person
        $salesPerson = User::select(DB::raw("CONCAT(first_name, ' ', last_name) AS name"), 'id')
                        ->where('id', $request->sales_person_id)->first();

        //Selected Sales Person realated Product id's
        $relatedProductArr = SalesPersonToProduct::select('product_id', 'brand_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();


        // Make array selected sales person of related product id's  
        $productRelatedToSalesPerson = $brandRelatedToSalesPerson = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToSalesPerson[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToSalesPerson[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }

        // Get Details of Related Product
        $productArr = [];
        if (isset($productRelatedToSalesPerson)) {
            $productArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                            ->whereIn('product.id', $productRelatedToSalesPerson)
                            ->select('product.id', 'product.name', 'product_category.name as product_category_name')
                            ->orderBy('product.product_category_id', 'asc')->get()->toArray();
        }

        $brandList = Brand::select('id', 'name', 'logo')->get();
        $brandInfo = [];
        if (!$brandList->isEmpty()) {
            foreach ($brandList as $brand) {
                $brandInfo[$brand->id]['name'] = $brand->name;
                $brandInfo[$brand->id]['logo'] = $brand->logo;
            }
        }
        
        $inactiveBrandArr = $inactiveProductArr = [];

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

        $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();



        $view = view('salesPersonToProduct.showRelatedProducts', compact('productArr', 'brandInfo'
                        , 'brandRelatedToSalesPerson', 'productRelatedToSalesPerson', 'request'
                        , 'salesPerson', 'inactiveBrandArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateSalesPersonToProduct(Request $request) {
        $rules = [
            'sales_person_id' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
            'brand' => 'required',
        ];

        $messages = [];
        $messages['brand.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BRAND_TO_RELATE_THIS_PRODUCT');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $salesPersonToProduct = [];
        $i = 0;
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //data entry to sales person to product table
                $salesPersonToProduct[$i]['sales_person_id'] = $request->sales_person_id;
                $salesPersonToProduct[$i]['product_id'] = $request->product_id;
                $salesPersonToProduct[$i]['brand_id'] = $brandId;
                $salesPersonToProduct[$i]['created_by'] = Auth::user()->id;
                $salesPersonToProduct[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }


        //delete from sales_person_to_product before insert
        SalesPersonToProduct::where('sales_person_id', $request->sales_person_id)
                ->where('product_id', $request->product_id)->delete();

        if (SalesPersonToProduct::insert($salesPersonToProduct)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SALES_PERSON_HAS_BEEN_RELATED_TO_PRODUCT_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SALES_PERSON_TO_PRODUCT')), 401);
        }
    }

    public function getAssignedProducts(Request $request) {
        $assignedProductArr = SalesPersonToProduct::join('product', 'product.id', '=', 'sales_person_to_product.product_id')
                        ->select('sales_person_to_product.product_id', 'product.name as product_name')
                        ->where('sales_person_to_product.sales_person_id', $request->sales_person_id)->get();

        $assignedProductList = $inactiveProductArr = [];
        if (!$assignedProductArr->isEmpty()) {
            foreach ($assignedProductArr as $item) {
                $assignedProductList[$item->product_id] = $item->product_name;
            }
        }
        
        $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();


        

        //dependency check
        $dependentProductArr = [];

        //dependent on inquiry details
        $inquiryDetailsArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.product_id')->where('inquiry.salespersons_id', $request->sales_person_id)->get();

        if (!$inquiryDetailsArr->isEmpty()) {
            foreach ($inquiryDetailsArr as $inquiryDetails) {
                $dependentProductArr[$request->sales_person_id][$inquiryDetails->product_id] = $inquiryDetails->product_id;
            }
        }

        //end :: dependency check

        $view = view('salesPersonToProduct.showAssignedProducts', compact('request', 'assignedProductList'
                , 'dependentProductArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function removeAssignedProduct(Request $request) {
        //delete from sales_person_to_product
        $removeAssignedProduct = SalesPersonToProduct::where('sales_person_id', $request->sales_person_id)
                ->where('product_id', $request->product_id);

        if ($removeAssignedProduct->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_IS_REMOVED_FROM_ASSIGNED_PRODUCT_LIST_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVED_PRODUCT_FROM_ASSIGNED_PRODUCT_LIST')), 401);
        }
    }
    
    public function removeAllAssignment(Request $request) {
        //delete from buyer to product
        $removeAssignment = SalesPersonToProduct::where('sales_person_id', $request->sales_person_id);

        if ($removeAssignment->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ALL_ASSIGNMENTS_ARE_REMOVED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVE_ALL_ASSIGNMENTS')), 401);
        }
    }

}
