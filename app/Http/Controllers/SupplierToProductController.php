<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\Supplier;
use App\SupplierClassification;
use App\User;
use App\SupplierToProduct;
use App\SalesPersonToProduct;
use App\ProductToBrand;
use App\Brand;
use App\InquiryDetails;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class SupplierToProductController extends Controller {

    public function index(Request $request) {
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

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
        $productRelatedToSupplier = $brandRelatedToSupplier = [];
        $dependentBrandArr = $inactiveBrandArr = $inactiveProductArr = [];
        if (!empty($request->get('product_id')) && !empty($request->get('supplier_id'))) {
            $relatedProductArr = SupplierToProduct::select('brand_id', 'product_id')
                            ->where('supplier_id', $request->get('supplier_id'))->get();

            if (!$relatedProductArr->isEmpty()) {
                foreach ($relatedProductArr as $relatedProduct) {
                    $productRelatedToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
                    $brandRelatedToSupplier[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
                }
            }

            $productToBrandArr = ProductToBrand::select('product_id', 'brand_id')->get();

            $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

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

            //dependency check
            //dependent on inquiry details
            $inquiryDetailsRecord = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                            ->select('brand_id')->where('inquiry.supplier_id', $request->get('supplier_id'))
                            ->where('inquiry_details.product_id', $request->get('product_id'))->get();

            if (!$inquiryDetailsRecord->isEmpty()) {
                foreach ($inquiryDetailsRecord as $inquiryDetails) {
                    $dependentBrandArr[$request->get('supplier_id')][$request->get('product_id')][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
                }
            }

            //end :: dependency check
        }

        $assignedProductList = $dependentProductArr = [];
        if (!empty($request->get('supplier_id'))) {
            $assignedProductArr = SupplierToProduct::join('product', 'product.id', '=', 'supplier_to_product.product_id')
                            ->select('supplier_to_product.product_id', 'product.name as product_name')
                            ->where('supplier_to_product.supplier_id', $request->get('supplier_id'))->get();

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
                            ->select('inquiry_details.product_id')->where('inquiry.supplier_id', $request->get('supplier_id'))->get();

            if (!$inquiryDetailsArr->isEmpty()) {
                foreach ($inquiryDetailsArr as $inquiryDetails) {
                    $dependentProductArr[$request->get('supplier_id')][$inquiryDetails->product_id] = $inquiryDetails->product_id;
                }
            }

            //end :: dependency check
        }


        return view('supplierToProduct.index')->with(compact('supplierArr', 'productArr', 'relatedBrandArr'
                                , 'request', 'productRelatedToSupplier', 'brandRelatedToSupplier'
                                , 'brandInfo', 'dependentBrandArr', 'assignedProductList', 'dependentProductArr'
                                , 'inactiveBrandArr', 'inactiveProductArr'));
    }

    public function getProductsToRelate(Request $request) {
        $relatedProductArr = SupplierToProduct::select('brand_id', 'product_id')
                        ->where('supplier_id', $request->supplier_id)->get();

        $productRelatedToSupplier = $brandRelatedToSupplier = $inactiveBrandArr = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToSupplier[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }

        $productToBrandArr = ProductToBrand::select('product_id', 'brand_id')->get();

        $inactiveBrandArr = Brand::where('status', '2')->pluck('id')->toArray();

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
                        ->select('brand_id')->where('inquiry.supplier_id', $request->supplier_id)
                        ->where('inquiry_details.product_id', $request->product_id)->get();

        if (!$inquiryDetailsRecord->isEmpty()) {
            foreach ($inquiryDetailsRecord as $inquiryDetails) {
                $dependentBrandArr[$request->supplier_id][$request->product_id][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
            }
        }

        //end :: dependency check

        $view = view('supplierToProduct.showProducts', compact('relatedBrandArr', 'request'
                        , 'productRelatedToSupplier', 'brandRelatedToSupplier', 'brandInfo'
                        , 'dependentBrandArr', 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedProducts(Request $request) {
        // Set Name of Selected supplier
        $supplier = Supplier::select('name', 'id')
                        ->where('id', $request->supplier_id)->first();

        //Selected supplier realated Product id's
        $relatedProductArr = SupplierToProduct::select('product_id', 'brand_id')
                        ->where('supplier_id', $request->supplier_id)->get();


        // Make array selected sales person of related product id's  
        $productRelatedToSupplier = $brandRelatedToSupplier = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToSupplier[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToSupplier[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }

        // Get Details of Related Product
        $productArr = [];
        if (isset($productRelatedToSupplier)) {
            $productArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                            ->whereIn('product.id', $productRelatedToSupplier)
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

        $view = view('supplierToProduct.showRelatedProducts', compact('productArr', 'brandInfo'
                        , 'brandRelatedToSupplier', 'productRelatedToSupplier', 'request', 'supplier'
                        , 'inactiveBrandArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateSupplierToProduct(Request $request) {
        //validation
        $rules = [
            'supplier_id' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
            'brand' => 'required',
        ];

        $messages = [];
        $messages['brand.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BRAND_TO_RELATE_THIS_PRODUCT');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $supplierToProduct = [];
        $i = 0;
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //data entry to sales person to product table
                $supplierToProduct[$i]['supplier_id'] = $request->supplier_id;
                $supplierToProduct[$i]['product_id'] = $request->product_id;
                $supplierToProduct[$i]['brand_id'] = $brandId;
                $supplierToProduct[$i]['created_by'] = Auth::user()->id;
                $supplierToProduct[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete from supplier_to_product before insert
        SupplierToProduct::where('supplier_id', $request->supplier_id)
                ->where('product_id', $request->product_id)->delete();

        if (SupplierToProduct::insert($supplierToProduct)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SUPPLIER_HAS_BEEN_RELATED_TO_PRODUCT_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SUPPLIER_TO_PRODUCT')), 401);
        }
    }

    public function getAssignedProducts(Request $request) {
        $assignedProductArr = SupplierToProduct::join('product', 'product.id', '=', 'supplier_to_product.product_id')
                        ->select('supplier_to_product.product_id', 'product.name as product_name')
                        ->where('supplier_to_product.supplier_id', $request->supplier_id)->get();

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
                        ->select('inquiry_details.product_id')->where('inquiry.supplier_id', $request->supplier_id)->get();

        if (!$inquiryDetailsArr->isEmpty()) {
            foreach ($inquiryDetailsArr as $inquiryDetails) {
                $dependentProductArr[$request->supplier_id][$inquiryDetails->product_id] = $inquiryDetails->product_id;
            }
        }

        //end :: dependency check

        $view = view('supplierToProduct.showAssignedProducts', compact('request', 'assignedProductList'
                        , 'dependentProductArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function removeAssignedProduct(Request $request) {
        //delete from supplier to product
        $removeAssignedProduct = SupplierToProduct::where('supplier_id', $request->supplier_id)
                ->where('product_id', $request->product_id);

        if ($removeAssignedProduct->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_IS_REMOVED_FROM_ASSIGNED_PRODUCT_LIST_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVED_PRODUCT_FROM_ASSIGNED_PRODUCT_LIST')), 401);
        }
    }
    
    public function removeAllAssignment(Request $request) {
        //delete from buyer to product
        $removeAssignment = SupplierToProduct::where('supplier_id', $request->supplier_id);

        if ($removeAssignment->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ALL_ASSIGNMENTS_ARE_REMOVED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVE_ALL_ASSIGNMENTS')), 401);
        }
    }
}
