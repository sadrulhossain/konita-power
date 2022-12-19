<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFactory;
use App\User;
use App\SalesPersonToBuyer;
use App\SalesPersonToProduct;
use App\BuyerToProduct;
use App\ProductToBrand;
use App\Brand;
use App\Product;
use App\Lead;
use App\CompanyInformation;
use Response;
use Auth;
use DB;
use PDF;
use Common;
use Redirect;
use Helper;
use Session;
use Illuminate\Http\Request;

class TransferBuyerToSalesPersonController extends Controller {

    public function index(Request $request) {

        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_sales', '1')
                        ->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();

        $loggedinUserArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->where('users.status', '1')
                        ->where('users.allowed_for_sales', '1')
                        ->where('users.id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();


        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $loggedinUserArr + $salesPersonArr;

        return view('transferBuyerToSalesPerson.index')->with(compact('salesPersonList'));
    }

    public function getBuyersToRelate(Request $request) {
        //check sales person's related products
        $userSalesPerson = SalesPersonToBuyer::where('sales_person_id', $request->sales_person_id)
                        ->where('business_status', '1')
                        ->pluck('buyer_id', 'buyer_id')->toArray();

        $buyerArr = Buyer::select('buyer.id', 'buyer.name', 'buyer.logo')
                ->whereIn('buyer.id', $userSalesPerson)
                ->where('buyer.status', '1')->orderBy('buyer.name', 'asc')
                ->get();


        $relatedProductArr = SalesPersonToProduct::select('brand_id', 'product_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();

        $productRelatedToSalesPerson = $brandRelatedToSalesPerson = $inactiveBrandArr = [];
        if (!$relatedProductArr->isEmpty()) {
            foreach ($relatedProductArr as $relatedProduct) {
                $productRelatedToSalesPerson[$relatedProduct->product_id] = $relatedProduct->product_id;
                $brandRelatedToSalesPerson[$relatedProduct->product_id][$relatedProduct->brand_id] = $relatedProduct->brand_id;
            }
        }


        //end :: dependency check

        $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"), 'buyer_id')
                        ->groupBy('buyer_id')->pluck('no_of_sales_person', 'buyer_id')->toArray();

        $view = view('transferBuyerToSalesPerson.showBuyers', compact('buyerArr', 'request'
                        , 'productRelatedToSalesPerson', 'brandRelatedToSalesPerson', 'salesPersonToBuyerCountList'))->render();
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



        $view = view('transferBuyerToSalesPerson.showRelatedProducts', compact('productArr', 'brandInfo'
                        , 'brandRelatedToSalesPerson', 'productRelatedToSalesPerson', 'request'
                        , 'salesPerson', 'inactiveBrandArr', 'inactiveProductArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getSalesPersonToTransfer(Request $request) {

        $rules = [
            'sales_person_id' => 'required|not_in:0',
        ];

        $messages = [];
        if (empty($request->buyer)) {
            $rules['buyer'] = 'required';
            $messages['buyer.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BUYER');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $buyerToProductInfo = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id')
                        ->whereIn('buyer_to_product.buyer_id', $request->buyer)
                        ->where('product.status', '1')->where('brand.status', '1')->get();

        $productIdArr = $brandIdArr = $itemArr = [];
        if (!$buyerToProductInfo->isEmpty()) {
            foreach ($buyerToProductInfo as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
                $brandIdArr[$item->brand_id] = $item->brand_id;
                $itemArr[$item->product_id][$item->brand_id] = $item->brand_id;
            }
        }

        //getting all sales persons related to the inquiry products and brands 
        $salesPersonToProductArr = SalesPersonToProduct::select('sales_person_id', 'product_id', 'brand_id')
                        ->whereIn('product_id', $productIdArr)->whereIn('brand_id', $brandIdArr)->get();

        //preparing array of set of supplier
        $salesPersonToProductList = [];
        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $salesPersonToProductList[$salesPersonToProduct->product_id][$salesPersonToProduct->brand_id][$salesPersonToProduct->sales_person_id] = $salesPersonToProduct->sales_person_id;
            }
        }

        //preparing array of sales person of the inquiry item sets
        $salesPersonToProductListArr = $salesPersonArr = [];
        if (!empty($itemArr)) {
            foreach ($itemArr as $productId => $brandList) {
                foreach ($brandList as $brandId) {
                    if (!empty($salesPersonToProductList[$productId][$brandId])) {
                        $salesPersonToProductListArr[$productId][$brandId] = $salesPersonToProductList[$productId][$brandId];
                        $salesPersonArr[] = $salesPersonToProductListArr[$productId][$brandId];
                    }
                }
            }
        }



        $commonSalesPersonArr = [];
        if (!empty($salesPersonArr)) {
            //if more than 1 supplier set
            if (count($salesPersonArr) > 1) {
                foreach ($salesPersonArr as $key => $value) {
                    //for 1st supplier set
                    if ($key == 0) {
                        //find common suppliers
                        $commonSalesPersonArr = array_intersect($salesPersonArr[$key], $salesPersonArr[$key + 1]);
                    } else if (count($salesPersonArr) >= 2) {
                        //if 2 or more than 2 supplier set
                        $commonSalesPersonArr = array_intersect($commonSalesPersonArr, $salesPersonArr[$key]);
                    }
                }
            } else {
                //if 1 supplier set
                $commonSalesPersonArr = $salesPersonArr[0];
            }
        }
//        echo '<pre>';
//        print_r($commonSalesPersonArr);
//        exit;


        $relatedSalesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->whereIn('users.id', $commonSalesPersonArr)
                        ->where('users.id', '<>', $request->sales_person_id)
                        ->where('users.status', '1')->where('users.allowed_for_sales', '1')
                        ->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();


        if (in_array(Auth::user()->id, $commonSalesPersonArr)) {
            $authUser = User::join('designation', 'designation.id', '=', 'users.designation_id')
                            ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                            ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                            ->where('users.id', Auth::user()->id)
                            ->where('users.id', '<>', $request->sales_person_id)
                            ->where('users.status', '1')->where('users.allowed_for_sales', '1')
                            ->pluck('name', 'users.id')->toArray();
            if (!empty($authUser)) {
                $relatedSalesPersonArr = $relatedSalesPersonArr + $authUser;
            }
        }
        $salesPersonList = array('0' => __('label.SELECT_SALES_PERSON_OPT')) + $relatedSalesPersonArr;

        /*         * ********* end of preparing suplier list ************* */


        // Make array selected sales person of related product id's  
        $productRelatedToBuyer = $brandRelatedToBuyer = [];
        if (!$buyerToProductInfo->isEmpty()) {
            foreach ($buyerToProductInfo as $relatedProduct) {
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

        $view = view('transferBuyerToSalesPerson.showTransferBuyerToSalesPerson', compact('request', 'salesPersonList', 'productArr', 'brandInfo', 'brandRelatedToBuyer', 'productRelatedToBuyer'))->render();
        return response()->json(['html' => $view]);
    }

    public function relateSalesPersonToBuyer(Request $request) {

        $rules = [
            'new_sales_person_id' => 'required|not_in:0',
            'sales_person_id' => 'required|not_in:0',
        ];
        $request->buyer = json_decode($request->buyer, true);
        $messages = [];
        if (empty($request->buyer)) {
            $rules['buyer'] = 'required';
            $messages['buyer.required'] = __('label.PLEASE_CHOOSE_ATLEAST_ONE_BUYER');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $salesPersonToBuyer = [];
        $i = 0;
        $salesPersonToBuyerList = SalesPersonToBuyer::where('sales_person_id', $request->new_sales_person_id)
                        ->pluck('buyer_id')->toArray();

        if (!empty($request->buyer)) {
            foreach ($request->buyer as $buyerId) {
                if (!in_array($buyerId, $salesPersonToBuyerList)) {
                    //data entry to sales person to product table
                    $salesPersonToBuyer[$i]['sales_person_id'] = $request->new_sales_person_id;
                    $salesPersonToBuyer[$i]['buyer_id'] = $buyerId;
                    $salesPersonToBuyer[$i]['business_status'] = '1';
                    $salesPersonToBuyer[$i]['created_by'] = Auth::user()->id;
                    $salesPersonToBuyer[$i]['created_at'] = date('Y-m-d H:i:s');
                    $i++;
                }
            }
        }




        DB::beginTransaction();
        try {
            if (SalesPersonToBuyer::insert($salesPersonToBuyer)) {
                Lead::whereNotIn('order_status', ['2', '3', '4', '5'])
                        ->whereIn('buyer_id', $request->buyer)
                        ->where('salespersons_id', $request->sales_person_id)
                        ->update(['salespersons_id' => $request->new_sales_person_id]);

                SalesPersonToBuyer::where('sales_person_id', $request->sales_person_id)
                        ->whereIn('buyer_id', $request->buyer)
                        ->update(['business_status' => '2']);
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYER_TRANSFERRED_TO_SALES_PERSON_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_TRANSFER_BUYER_TO_SALES_PERSON')], 401);
        }
    }

    public function getRelatedSalesPersonList(Request $request) {
        $loadView = 'transferBuyerToSalesPerson.showRelatedSalesPersonList';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

    public function getRelatedSalesPersonListPrint(Request $request) {
        $loadView = 'transferBuyerToSalesPerson.print.showRelatedSalesPerson';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

}
