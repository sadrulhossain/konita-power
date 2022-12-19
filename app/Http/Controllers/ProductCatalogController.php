<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Buyer;
use App\BuyerToProduct;
use App\CompanyInformation;
use App\InquiryDetails;
use App\Certificate;
use App\ProductTechDataSheet;
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

class ProductCatalogController extends Controller {

    public function index(Request $request) {
        //buyer info
        $target = Buyer::select('id', 'name', 'show_all_brands')->where('buyer.user_id', Auth::user()->id)->first();
        $id = !empty($target->id) ? $target->id : 0;
        $name = !empty($target->name) ? $target->name : 0;
        $certificateArr = Certificate::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //previous technical data sheet
        $previousTechDataSheetArr = ProductTechDataSheet::select('product_id', 'brand_id', 'data_sheet')->get();
        $previousDataSheetArr = [];
        if (!$previousTechDataSheetArr->isEmpty()) {
            foreach ($previousTechDataSheetArr as $previousTechDataSheet) {
                $previousDataSheetArr[$previousTechDataSheet->product_id][$previousTechDataSheet->brand_id] = json_decode($previousTechDataSheet->data_sheet, true);
            }
        }
        
        
        
        //start :: product info
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->join('country', 'country.id', 'brand.origin')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo', 'measure_unit.name as unit'
                                , 'country.name as country_of_origin', 'brand.certificate')
                        ->where('buyer_to_product.buyer_id', $id)->get();
        //echo '<pre>';print_r($buyerToProductInfoArr->toArray());exit;
        $productInfoArr = $productRowSpanArr = $brandWiseVolumeRateArr = [];
        /*         * **************************************** START:: From Buyer Profile ********************** */
        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();


        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }
        /*         * **************************************** END:: From Buyer Profile ********************** */

        //echo '<pre>';print_r($buyerToProductInfoArr->toArray());exit;

        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                if (!empty($target->show_all_brands)) {
                    $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                    $productInfoArr[$item->product_id]['unit'] = $item->unit;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['origin'] = $item->country_of_origin;
                    $productInfoArr[$item->product_id]['brand'][$item->brand_id]['certificate'] = json_decode($item->certificate, true);
                    
                    $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
                } else {
                    if (!empty($brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                            if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                                $productInfoArr[$item->product_id]['unit'] = $item->unit;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['origin'] = $item->country_of_origin;
                                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['certificate'] = json_decode($item->certificate, true);
                                
                                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
                            }
                        }
                    }
                }
            }
        }


        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();
        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->first();

        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            return view('productCatalog.print.index')->with(compact('target', 'request', 'konitaInfo', 'phoneNumber'
                                    , 'productInfoArr', 'productRowSpanArr', 'brandWiseVolumeRateArr', 'certificateArr','previousDataSheetArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('productCatalog.print.index', compact('target', 'request', 'konitaInfo', 'phoneNumber'
                                    , 'productInfoArr', 'productRowSpanArr', 'brandWiseVolumeRateArr', 'certificateArr','previousDataSheetArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('Product-Catalog-' . date('ymdhis') . '.pdf');
//            return $pdf->stream();
        } else {
            return view('productCatalog.index')->with(compact('target', 'request', 'productInfoArr', 'productRowSpanArr'
                                    , 'brandWiseVolumeRateArr', 'certificateArr','previousDataSheetArr'));
        }
    }

}
