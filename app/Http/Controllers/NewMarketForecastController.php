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

class NewMarketForecastController extends Controller {

    public function index(Request $request) {

        $productArr = Product::join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->where('competitors_product', '1')->orderBy('product.name', 'asc');
        $productList = $productArr->pluck('product.name', 'product.id')->toArray();
        $productUnitList = $productArr->pluck('measure_unit.name', 'product.id')->toArray();

        $productIdList = $productArr->pluck('product.id')->toArray();
        $buyerImportVolumeInfo = BuyerToGsmVolume::select('set_gsm_volume', 'product_id')
                        ->whereIn('product_id', $productIdList)->get();

        $importVolArr = [];
        if (!$buyerImportVolumeInfo->isEmpty()) {
            foreach ($buyerImportVolumeInfo as $volume) {
                $volumeArr = json_decode($volume->set_gsm_volume, true);
                $gsmVol = 0;
                if (!empty($volumeArr)) {
                    foreach ($volumeArr as $key => $gsmVal) {
                        $gsmVol += ($gsmVal['volume'] ?? 0);
                    }
                }

                $importVolArr[$volume->product_id] = $importVolArr[$volume->product_id] ?? 0;
                $importVolArr[$volume->product_id] += $gsmVol;
            }
        }



        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));

        $salesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])
                        ->select(DB::raw("SUM(inquiry_details.quantity) as sales_volume")
                                , 'inquiry_details.product_id')->groupBy('inquiry_details.product_id')
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
            if (empty($userAccessArr[54][6])) {
                return redirect('/dashboard');
            }
            return view('report.newMarketForecast.print.index')->with(compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'konitaInfo', 'phoneNumber'
                                    , 'engagementArr', 'opportunityArr'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[54][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.newMarketForecast.print.index', compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'konitaInfo', 'phoneNumber'
                                    , 'engagementArr', 'opportunityArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('new_market_forecast_' . $oneYearAgo . '_' . $today . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.newMarketForecast.index')->with(compact('request', 'productList'
                                    , 'importVolumeArr', 'salesVolumeArr', 'productUnitList'
                                    , 'today', 'oneYearAgo', 'engagementArr', 'opportunityArr'));
        }
    }

}
