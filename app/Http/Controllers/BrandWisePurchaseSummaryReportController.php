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
use App\ProductToBrand;
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
use DateTime;
use PDF;
use Illuminate\Http\Request;

class BrandWisePurchaseSummaryReportController extends Controller {

    public function index(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';

        $summaryArr = $brandList = $brandLogoList = $purchaseSummaryArr = $totalArr = $brandWiseVolumeRateArr = $productList = [];

		$productListInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
				->select('buyer_to_product.product_id','product.name as product_name','buyer_to_product.brand_id')
				->where('buyer_to_product.buyer_id', $id)
				->get();
		
		
		/***************************** START:: Get Purchased Products ***************************/
		$brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
						->where('inquiry.buyer_id', $id)
						->whereIn('inquiry.order_status', ['2', '3', '4'])
						->get();

        
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['brand'] = $info->brand_id;
            }
        }

        
        if (!$productListInfoArr->isEmpty()) {
            foreach ($productListInfoArr as $item) {
                if (!empty($brandWiseVolumeRateArr)) {
                    if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                            $productList[$item->product_id]= $item->product_name;
                        }
                    }
                }
            }
        }
		/***************************** END:: Get Purchased Products ***************************/
		
		
        $selectedBrandList = [];

        if ($request->generate == 'true') {
            if (!empty($request->pi_from_date)) {
                $fromDate = date("Y-m-01", strtotime($request->pi_from_date));
            }
            if (!empty($request->pi_to_date)) {
                $toDate = date("Y-m-t", strtotime($request->pi_to_date));
            }

            $selectedProductList = explode(",", $request->product);

            if (!empty($selectedProductList)) {
                $selectedBrandList = BuyerToProduct::join('brand', 'brand.id', 'buyer_to_product.brand_id')
                                ->where('buyer_to_product.buyer_id', $id)->whereIn('buyer_to_product.product_id', $selectedProductList)
                                ->pluck('brand.id')->toArray();
            }

            $purchaseSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->where('inquiry.buyer_id', $id)
                    ->whereBetween('inquiry.pi_date', [$fromDate, $toDate]);
					
            if (!empty($request->product)) {
                $purchaseSummaryInfoArr = $purchaseSummaryInfoArr->whereIn('inquiry_details.product_id', $selectedProductList);
            }
            $purchaseSummaryInfoArr = $purchaseSummaryInfoArr->select('inquiry_details.id','inquiry_details.quantity', 'inquiry_details.brand_id','inquiry_details.product_id')
                    ->get();

			
            if (!$purchaseSummaryInfoArr->isEmpty()) {
                foreach ($purchaseSummaryInfoArr as $summary) {
                    $summaryArr[$summary->brand_id]['volume'] = $summaryArr[$summary->brand_id]['volume'] ?? 0;
                    $summaryArr[$summary->brand_id]['volume'] += $summary->quantity ?? 0;
                }
            }
			
			//echo '<pre>';print_r($summaryArr);exit;

            $brandList = Brand::where('status', '1');

            if (!empty($selectedBrandList)) {
                $brandList = $brandList->whereIn('id', $selectedBrandList);
            }
            $brandLogoList = $brandList->pluck('logo', 'id')->toArray();
            $brandList = $brandList->pluck('name', 'id')->toArray();

            if (!empty($brandList)) {
                foreach ($brandList as $brandId => $brandName) {
					if(!empty($summaryArr)){
					    if(array_key_exists($brandId, $summaryArr)){
							$purchaseSummaryArr[$brandId]['volume'] = !empty($purchaseSummaryArr[$brandId]['volume']) ? $purchaseSummaryArr[$brandId]['volume'] : 0;
							$purchaseSummaryArr[$brandId]['volume'] += !empty($summaryArr[$brandId]['volume']) ? $summaryArr[$brandId]['volume'] : 0;

							$purchaseSummaryArr['total']['volume'] = $purchaseSummaryArr['total']['volume'] ?? 0;
							$purchaseSummaryArr['total']['volume'] += !empty($summaryArr[$brandId]['volume']) ? $summaryArr[$brandId]['volume'] : 0;
						}		
					}
					
                }
            }
          //echo '<pre>';
//            print_r($monthArr);
           // print_r($purchaseSummaryArr);
           //exit;

            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            return view('report.brandWisePurchaseSummary.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'purchaseSummaryArr', 'brandList', 'brandLogoList', 'fromDate', 'toDate','summaryArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.brandWisePurchaseSummary.print.index', compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'purchaseSummaryArr', 'brandList', 'brandLogoList', 'fromDate', 'toDate','summaryArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
            return $pdf->download('brand_wise_purchase_summary_report' . $fromDate . '_' . $toDate . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.brandWisePurchaseSummary.index')->with(compact('request', 'purchaseSummaryArr', 'brandList'
                                    , 'fromDate', 'toDate', 'productList', 'brandLogoList','summaryArr'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'pi_from_date' => 'required',
            'pi_to_date' => 'required',
        ];

        $messages = [
            'pi_from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'pi_to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        $url = 'pi_from_date=' . $request->pi_from_date . '&pi_to_date=' . $request->pi_to_date
                . '&product=' . $product;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('brandWisePurchaseSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('brandWisePurchaseSummaryReport?generate=true&' . $url);
    }

}
