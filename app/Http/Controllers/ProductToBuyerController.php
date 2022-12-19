<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFactory;
use App\Product;
use App\User;
use App\ProductToBuyer;
use Response;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Http\Request;

class ProductToBuyerController extends Controller {

    public function index() {
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')->where('competitors_product', '0')->pluck('name', 'id')->toArray();

        return view('productToBuyer.index')->with(compact('productArr'));
    }

    public function getBuyersToRelate(Request $request) {
        //get all 
        $buyerArr = Buyer::select('buyer.id', 'buyer.name')
                        ->orderBy('buyer.name', 'asc')->get();

        $relatedBuyerArr = ProductToBuyer::join('product', 'product.id', '=', 'product_to_buyer.product_id')
                        ->select('product_to_buyer.*', 'product.name as product_name')->get();


        $relateProductList = $buyerRelateToProduct = [];
        if (!$relatedBuyerArr->isEmpty()) {
            foreach ($relatedBuyerArr as $relatedBuyer) {
                $relatedBuyerList = explode(",", $relatedBuyer->buyer);
                if (!empty($relatedBuyerList)) {
                    foreach ($relatedBuyerList as $relatedBuyerId) {
                        $relateProductList[$relatedBuyerId][$relatedBuyer->product_id] = $relatedBuyer->product_name;
                        $buyerRelateToProduct[$relatedBuyer->product_id][$relatedBuyerId] = $relatedBuyerId;
                    }
                }
            }
        }

        $view = view('productToBuyer.showBuyers', compact('buyerArr', 'relateProductList'
                        , 'relatedBuyerArr', 'buyerRelateToProduct', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    public function getRelatedBuyers(Request $request) {
        // Set Name of Selected Sales Person
        $product = Product::select('name', 'id')
                        ->where('id', $request->product_id)->first();

        //get related factories
        $relatedBuyerArr = ProductToBuyer::select('product_to_buyer.*')
                        ->where('product_id', $request->product_id)->get();

        $buyerRelateToProduct = [];
        if (!$relatedBuyerArr->isEmpty()) {
            foreach ($relatedBuyerArr as $relatedBuyer) {
                $relatedBuyerList = explode(",", $relatedBuyer->buyer);
                if (!empty($relatedBuyerList)) {
                    foreach ($relatedBuyerList as $relatedBuyerId) {
                        $buyerRelateToProduct[$relatedBuyer->product_id][$relatedBuyerId] = $relatedBuyerId;
                    }
                }
            }
        }

        //unique buyer related to sales person
        $buyerArr = [];
        if (isset($buyerRelateToProduct[$request->product_id])) {
            $buyerArr = Buyer::join('buyer_category', 'buyer_category.id', '=', 'buyer.buyer_category_id')
                            ->select('buyer.*', 'buyer_category.name as buyer_category_name')
                            ->whereIn('buyer.id', $buyerRelateToProduct[$request->product_id])
                            ->orderBy('buyer.name', 'asc')
                            ->orderBy('buyer_category.name', 'asc')
                            ->get()->toArray();
        }

        $view = view('productToBuyer.showRelatedBuyers', compact('product'
                        , 'relatedBuyerArr', 'buyerRelateToProduct', 'request'
                        , 'buyerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateProductToBuyer(Request $request) {
        $rules = [
            'product_id' => 'required|not_in:0',
            'buyer' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //delete before inserted 
        ProductToBuyer::where('product_id', $request->product_id)->delete();

        $buyerList = implode(",", $request->buyer);
        $target = new ProductToBuyer;
        $target->product_id = $request->product_id;
        $target->buyer = $buyerList;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.SALES_PERSON_HAS_BEEN_RELATED_TO_BUYER_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_SALES_PERSON_TO_BUYER')), 401);
        }
    }

}
