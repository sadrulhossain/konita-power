<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFactory;
use App\Product;
use App\User;
use App\BuyerToProduct;
use App\ProductToBrand;
use App\SalesPersonToProduct;
use App\SalesPersonToBuyer;
use App\InquiryDetails;
use App\Brand;
use App\BuyerToGsmVolume;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class BuyerToProductController extends Controller {

    public function index(Request $request) {
        $userBuyer = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id', 'buyer_id')->toArray();
        $buyerList = Buyer::orderBy('buyer.name', 'asc');
        if (Auth::user()->group_id != 1) {
            $buyerList = $buyerList->whereIn('buyer.id', $userBuyer);
        }
        $buyerList = $buyerList->where('buyer.status', '1')->pluck('buyer.name', 'buyer.id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + $buyerList;

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
        $productRelatedToBuyer = $brandRelatedToBuyer = [];
        $dependentBrandArr = $inactiveBrandArr = $inactiveProductArr = [];
        if (!empty($request->get('product_id')) && !empty($request->get('buyer_id'))) {
            $relatedProductArr = BuyerToProduct::select('brand_id', 'product_id')
                            ->where('buyer_id', $request->get('buyer_id'))->get();

            if (!$relatedProductArr->isEmpty()) {
                foreach ($relatedProductArr as $relatedProduct) {
                    $productRelatedToBuyer[$relatedProduct->product_id] = $relatedProduct->product_id;
                    $brandRelatedToBuyer[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
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
                            ->select('brand_id')->where('inquiry_details.product_id', $request->get('product_id'))
                            ->where('inquiry.buyer_id', $request->get('buyer_id'))->get();

            if (!$inquiryDetailsRecord->isEmpty()) {
                foreach ($inquiryDetailsRecord as $inquiryDetails) {
                    $dependentBrandArr[$request->get('buyer_id')][$request->get('product_id')][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
                }
            }

            //end :: dependency check
        }

        $assignedProductList = $dependentProductArr = [];
        if (!empty($request->get('buyer_id'))) {
            $assignedProductArr = BuyerToProduct::join('product', 'product.id', '=', 'buyer_to_product.product_id')
                            ->select('buyer_to_product.product_id', 'product.name as product_name')
                            ->where('buyer_to_product.buyer_id', $request->get('buyer_id'))->get();

            $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();

            $assignedProductList = [];
            if (!$assignedProductArr->isEmpty()) {
                foreach ($assignedProductArr as $item) {
                    $assignedProductList[$item->product_id] = $item->product_name;
                }
            }

            //dependency check
            //dependent on buyer to gsm volume
//            $buyerToGsmVolumeArr = BuyerToGsmVolume::select('product_id')->where('buyer_id', $request->get('buyer_id'))->get();
//
//            if (!$buyerToGsmVolumeArr->isEmpty()) {
//                foreach ($buyerToGsmVolumeArr as $buyerToGsmVolume) {
//                    $dependentProductArr[$request->get('buyer_id')][$buyerToGsmVolume->product_id] = $buyerToGsmVolume->product_id;
//                }
//            }
            //dependent on inquiry details
            $inquiryDetailsArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                            ->select('inquiry_details.product_id')->where('inquiry.buyer_id', $request->get('buyer_id'))->get();

            if (!$inquiryDetailsArr->isEmpty()) {
                foreach ($inquiryDetailsArr as $inquiryDetails) {
                    $dependentProductArr[$request->get('buyer_id')][$inquiryDetails->product_id] = $inquiryDetails->product_id;
                }
            }

            //end :: dependency check
        }

        return view('buyerToProduct.index')->with(compact('buyerArr', 'productArr', 'relatedBrandArr'
                                , 'request', 'productRelatedToBuyer', 'brandRelatedToBuyer'
                                , 'brandInfo', 'dependentBrandArr', 'assignedProductList', 'dependentProductArr'
                                , 'inactiveBrandArr', 'inactiveProductArr'));
    }

    public function getProductsToRelate(Request $request) {
        $relatedProductArr = BuyerToProduct::select('brand_id', 'product_id')
                        ->where('buyer_id', $request->buyer_id)->get();

        $productRelatedToBuyer = $brandRelatedToBuyer = $inactiveBrandArr = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToBuyer[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToBuyer[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
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
                        ->where('inquiry.buyer_id', $request->buyer_id)->get();

        if (!$inquiryDetailsRecord->isEmpty()) {
            foreach ($inquiryDetailsRecord as $inquiryDetails) {
                $dependentBrandArr[$request->buyer_id][$request->product_id][$inquiryDetails->brand_id] = $inquiryDetails->brand_id;
            }
        }

        //end :: dependency check


        $view = view('buyerToProduct.showProducts', compact('relatedBrandArr'
                        , 'request', 'productRelatedToBuyer', 'brandRelatedToBuyer'
                        , 'brandInfo', 'dependentBrandArr', 'inactiveBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedProducts(Request $request) {
        // Set Name of Selected buyer
        $buyer = Buyer::select('name', 'id')->where('id', $request->buyer_id)->first();

        //Selected buyer realated Product id's
        $relatedProductArr = BuyerToProduct::select('product_id', 'brand_id')
                        ->where('buyer_id', $request->buyer_id)->get();


        // Make array selected sales person of related product id's  
        $productRelatedToBuyer = $brandRelatedToBuyer = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToBuyer[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToBuyer[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }

        // Get Details of Related Product
        $productArr = [];
        if (isset($productRelatedToBuyer)) {
            $productArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                            ->whereIn('product.id', $productRelatedToBuyer)
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



        $view = view('buyerToProduct.showRelatedProducts', compact('productArr', 'brandInfo'
                        , 'brandRelatedToBuyer', 'productRelatedToBuyer', 'request'
                        , 'buyer', 'inactiveBrandArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateBuyerToProduct(Request $request) {
        $rules = [
            'buyer_id' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
            'brand' => 'required',
        ];

        $messages = [];
        $messages['brand.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BRAND_TO_RELATE_THIS_PRODUCT');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $buyerToProduct = [];
        $i = 0;
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //data entry to sales person to product table
                $buyerToProduct[$i]['buyer_id'] = $request->buyer_id;
                $buyerToProduct[$i]['product_id'] = $request->product_id;
                $buyerToProduct[$i]['brand_id'] = $brandId;
                $buyerToProduct[$i]['created_by'] = Auth::user()->id;
                $buyerToProduct[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        //delete from buyer_to_product before insert
        BuyerToProduct::where('buyer_id', $request->buyer_id)
                ->where('product_id', $request->product_id)->delete();

        if (BuyerToProduct::insert($buyerToProduct)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.BUYER_HAS_BEEN_RELATED_TO_PRODUCT_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_BUYER_TO_PRODUCT')), 401);
        }
    }

    public function getAssignedProducts(Request $request) {
        $assignedProductArr = BuyerToProduct::join('product', 'product.id', '=', 'buyer_to_product.product_id')
                        ->select('buyer_to_product.product_id', 'product.name as product_name')
                        ->where('buyer_to_product.buyer_id', $request->buyer_id)->get();

        $assignedProductList = $inactiveProductArr = [];
        if (!$assignedProductArr->isEmpty()) {
            foreach ($assignedProductArr as $item) {
                $assignedProductList[$item->product_id] = $item->product_name;
            }
        }

        $inactiveProductArr = Product::where('status', '2')->pluck('id')->toArray();

        //dependency check
        $dependentProductArr = [];

        //dependent on buyer to gsm volume
//        $buyerToGsmVolumeArr = BuyerToGsmVolume::select('product_id')->where('buyer_id', $request->buyer_id)->get();
//
//        if (!$buyerToGsmVolumeArr->isEmpty()) {
//            foreach ($buyerToGsmVolumeArr as $buyerToGsmVolume) {
//                $dependentProductArr[$request->buyer_id][$buyerToGsmVolume->product_id] = $buyerToGsmVolume->product_id;
//            }
//        }

        //dependent on inquiry details
        $inquiryDetailsArr = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.product_id')->where('inquiry.buyer_id', $request->buyer_id)->get();

        if (!$inquiryDetailsArr->isEmpty()) {
            foreach ($inquiryDetailsArr as $inquiryDetails) {
                $dependentProductArr[$request->buyer_id][$inquiryDetails->product_id] = $inquiryDetails->product_id;
            }
        }
        
//        echo '<pre>';
//        print_r($inquiryDetails);

        //end :: dependency check

        $view = view('buyerToProduct.showAssignedProducts', compact('request', 'assignedProductList'
                        , 'dependentProductArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function removeAssignedProduct(Request $request) {
        //delete from buyer to product
        $removeAssignedProduct = BuyerToProduct::where('buyer_id', $request->buyer_id)
                ->where('product_id', $request->product_id);

        if ($removeAssignedProduct->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_IS_REMOVED_FROM_ASSIGNED_PRODUCT_LIST_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVED_PRODUCT_FROM_ASSIGNED_PRODUCT_LIST')), 401);
        }
    }
    public function removeAllAssignment(Request $request) {
        //delete from buyer to product
        $removeAssignment = BuyerToProduct::where('buyer_id', $request->buyer_id);

        if ($removeAssignment->delete()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.ALL_ASSIGNMENTS_ARE_REMOVED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REMOVE_ALL_ASSIGNMENTS')), 401);
        }
    }

}
