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
use App\CommissionSetup;
use App\Buyer;
use App\InquiryDetails;
use App\Grade;
use App\DeliveryDetails;
use App\CompanyInformation;
use App\Country;
use App\SalesPersonToProduct;
use App\BuyerToGsmVolume;
use App\BuyerToProduct;
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

class MarketEngagementController extends Controller {

    public function index(Request $request) {

        $productsList = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->where('buyer_to_product.buyer_id', $request->buyer_id)
                        ->pluck('product.name', 'buyer_to_product.product_id')->toArray();

        $productsList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productsList;
        $buyerCountryList = Buyer::pluck('country_id')->toArray();
        $buyersList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $countryList = Country::whereIn('id', $buyerCountryList)->pluck('name', 'id')->toArray();

        $productArr = Product::join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->orderBy('product.name', 'asc');

        $productsList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productArr->pluck('product.name', 'product.id')->toArray();

        $buyerList = [];

        if ($request->generate == 'true') {
            $selectedCountryList = explode(",", $request->country);
            if (!empty($selectedCountryList)) {
                if (empty($request->buyer_id)) {
                    $buyerList = Buyer::whereIn('country_id', $selectedCountryList)->pluck('id')->toArray();
                } else {
                    $buyerList = Buyer::where('id', $request->buyer_id)->pluck('id')->toArray();
                }

                $buyersList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::whereIn('country_id', $selectedCountryList)
                                ->pluck('name', 'id')->toArray();

                $productsList = [];
                if (!empty($buyersList)) {
                    $productsList = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id');
                    if(!empty($request->buyer_id)){
                        $productsList = $productsList->where('buyer_to_product.buyer_id', $request->buyer_id);
                    } else{
                        $productsList = $productsList->whereIn('buyer_to_product.buyer_id', $buyersList);
                    }
                                    
                    $productsList = $productsList->pluck('product.name', 'buyer_to_product.product_id')->toArray();

                    $productsList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productsList;
                }
            }
        }
        if ($request->generate == 'true' && !empty($request->product_id)) {
            $productList = $productArr->where('product.id', $request->product_id)
                            ->pluck('product.name', 'product.id')->toArray();
        } else {
            $productList = $productArr->pluck('product.name', 'product.id')->toArray();
        }


        $productUnitList = $productArr->pluck('measure_unit.name', 'product.id')->toArray();

        $productIdList = $productArr->pluck('product.id')->toArray();
        $buyerImportVolumeInfo = BuyerToGsmVolume::select('buyer_id', 'set_gsm_volume', 'product_id');
        if (!empty($buyerList)) {
            $buyerImportVolumeInfo = $buyerImportVolumeInfo->whereIn('buyer_id', $buyerList);
        }
        $buyerImportVolumeInfo = $buyerImportVolumeInfo->whereIn('product_id', $productIdList)->get();

        $importBuyerList = $importVolArr = [];
        if (!$buyerImportVolumeInfo->isEmpty()) {
            foreach ($buyerImportVolumeInfo as $volume) {
                $volumeArr = json_decode($volume->set_gsm_volume, true);
                $gsmVol = 0;
                if (!empty($volumeArr)) {
                    foreach ($volumeArr as $key => $gsmVal) {
                        $gsmVol += (!empty($gsmVal['volume']) ? $gsmVal['volume'] : 0);
                    }
                }

                $importVolArr[$volume->product_id] = $importVolArr[$volume->product_id] ?? 0;
                $importVolArr[$volume->product_id] += $gsmVol;
                $importBuyerList[$volume->buyer_id] = $volume->buyer_id;
            }
        }

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));

        $salesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->whereBetween('inquiry.pi_date', [$oneYearAgo, $today]);
        if (!empty($buyerList)) {
            $salesVolumeInfo = $salesVolumeInfo->whereIn('inquiry.buyer_id', $buyerList);
        }

        $salesBuyerList = $salesVolumeInfo->pluck('inquiry.buyer_id', 'inquiry.buyer_id')->toArray();

        $salesVolumeInfo = $salesVolumeInfo->select(DB::raw("SUM(inquiry_details.quantity) as sales_volume")
                                , 'inquiry_details.product_id')
                        ->groupBy('inquiry_details.product_id')
                        ->pluck('sales_volume', 'inquiry_details.product_id')->toArray();


        $importVolumeArr = $salesVolumeArr = $engagementArr = $opportunityArr = [];
        if (!empty($productIdList)) {
            foreach ($productIdList as $productId) {
                $importVolumeArr[$productId] = $importVolArr[$productId] ?? 0;
                $salesVolumeArr[$productId] = $salesVolumeInfo[$productId] ?? 0;

                if (empty($importVolArr[$productId])) {
                    $opportunityArr[$productId] = 0;
                    $engagementArr[$productId] = 0;

                    if (!empty($salesVolumeInfo[$productId])) {
                        $engagementArr[$productId] = 100;
                    }
                } else {
                    $opportunityArr[$productId] = 100;
                    $engagementArr[$productId] = 0;

                    if (!empty($salesVolumeInfo[$productId])) {
                        $engagementArr[$productId] = ($salesVolumeInfo[$productId] * 100) / $importVolArr[$productId];
                        $opportunityArr[$productId] = 100 - $engagementArr[$productId];

                        if ($engagementArr[$productId] >= 100) {
                            $opportunityArr[$productId] = 0;
                        }
                    }
                }
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

//        echo '<pre>';
//        print_r($salesVolumeInfo);
//        exit;

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[53][6])) {
                return redirect('/dashboard');
            }
            return view('report.marketEngagement.print.index')->with(compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'konitaInfo', 'phoneNumber'
                                    , 'engagementArr', 'opportunityArr', 'importBuyerList'
                                    , 'salesBuyerList', 'buyersList', 'productsList'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[53][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.marketEngagement.print.index', compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'konitaInfo', 'phoneNumber'
                                    , 'engagementArr', 'opportunityArr', 'importBuyerList'
                                    , 'salesBuyerList', 'buyersList', 'productsList'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('market_engagement_' . $oneYearAgo . '_' . $today . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.marketEngagement.index')->with(compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'engagementArr', 'opportunityArr'
                                    , 'countryList', 'importBuyerList', 'salesBuyerList'
                                    , 'buyersList', 'productsList'));
        }
    }

    public function filter(Request $request) {
        $country = !empty($request->country) ? implode(",", $request->country) : '';
        $url = 'country=' . $country . '&buyer_id=' . $request->buyer_id . '&product_id=' . $request->product_id;

        return Redirect::to('marketEngagement?generate=true&' . $url);
    }

    public function getBuyerProduct(Request $request) {
        $buyersList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::whereIn('country_id', $request->country)->pluck('name', 'id')->toArray();

        $productsList = [];
        if (!empty($buyersList)) {
            $productsList = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                            ->whereIn('buyer_to_product.buyer_id', $buyersList)
                            ->pluck('product.name', 'buyer_to_product.product_id')->toArray();

            $productsList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productsList;
        }

        $buyer = view('report.marketEngagement.showBuyers', compact('request', 'buyersList'))->render();
        $product = view('report.marketEngagement.showProducts', compact('request', 'productsList'))->render();
        return response()->json(['buyer' => $buyer, 'product' => $product]);
    }

    public function getProduct(Request $request) {
        $productsList = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->where('buyer_to_product.buyer_id', $request->buyer_id)
                        ->pluck('product.name', 'buyer_to_product.product_id')->toArray();

        $productsList = ['0' => __('label.SELECT_PRODUCT_OPT')] + $productsList;


        $product = view('report.marketEngagement.showProducts', compact('request', 'productsList'))->render();
        return response()->json(['product' => $product]);
    }

}
