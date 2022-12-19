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
use App\SupplierToProduct;
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

class SupplierWiseSalesSummaryReportController extends Controller {

    public function index(Request $request) {

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';

        $summaryArr = $supplierList = $salesSummaryArr = $totalArr = [];

        $productList = Product::pluck('name', 'id')->toArray();
        $selectedSupplierList = [];

        if ($request->generate == 'true') {
            if (!empty($request->pi_from_date)) {
                $fromDate = date("Y-m-01", strtotime($request->pi_from_date));
            }
            if (!empty($request->pi_to_date)) {
                $toDate = date("Y-m-t", strtotime($request->pi_to_date));
            }

            $selectedProductList = explode(",", $request->product);

            if (!empty($selectedProductList)) {
                $selectedSupplierList = SupplierToProduct::whereIn('product_id', $selectedProductList)
                                ->pluck('supplier_id')->toArray();
            }


            $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->whereBetween('inquiry.pi_date', [$fromDate, $toDate]);
            if(!empty($request->product)){
                $salesSummaryInfoArr = $salesSummaryInfoArr->whereIn('inquiry_details.product_id', $selectedProductList);
            }
            $salesSummaryInfoArr = $salesSummaryInfoArr->select(DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income')
                            , 'inquiry_details.quantity', 'inquiry.supplier_id', 'inquiry_details.id as inquiry_details_id')
                    ->get();
            
            $cmsnIncomeSummaryArr = [];
            if (!$salesSummaryInfoArr->isEmpty()) {
                foreach ($salesSummaryInfoArr as $summary) {
                    $cmsnIncomeSummaryArr[$summary->supplier_id][$summary->inquiry_details_id]['quantity'] = $summary->quantity ?? 0;
                    $cmsnIncomeSummaryArr[$summary->supplier_id][$summary->inquiry_details_id]['net_income'] = $summary->net_income ?? 0;
                }
            }

            if (!empty($cmsnIncomeSummaryArr)) {
                foreach ($cmsnIncomeSummaryArr as $supplierId => $inquiry) {
                    foreach ($inquiry as $inquiryDetailsId => $details) {
                        $summaryArr[$supplierId]['volume'] = $summaryArr[$supplierId]['volume'] ?? 0;
                        $summaryArr[$supplierId]['volume'] += $details['quantity'] ?? 0;
                        
                        $summaryArr[$supplierId]['net_income'] = $summaryArr[$supplierId]['net_income'] ?? 0;
                        $summaryArr[$supplierId]['net_income'] += $details['net_income'] ?? 0;
                    }
                }
            }


            $supplierList = Supplier::where('status', '1');

            if (!empty($selectedSupplierList)) {
                $supplierList = $supplierList->whereIn('id', $selectedSupplierList);
            }
            $supplierList = $supplierList->pluck('name', 'id')->toArray();

            if (!empty($supplierList)) {
                foreach ($supplierList as $supplierId => $supplierName) {
                    $salesSummaryArr[$supplierId]['volume'] = !empty($salesSummaryArr[$supplierId]['volume']) ? $salesSummaryArr[$supplierId]['volume'] : 0;
                    $salesSummaryArr[$supplierId]['volume'] += !empty($summaryArr[$supplierId]['volume']) ? $summaryArr[$supplierId]['volume'] : 0;

                    $salesSummaryArr[$supplierId]['net_income'] = !empty($salesSummaryArr[$supplierId]['net_income']) ? $salesSummaryArr[$supplierId]['net_income'] : 0;
                    $salesSummaryArr[$supplierId]['net_income'] += !empty($summaryArr[$supplierId]['net_income']) ? $summaryArr[$supplierId]['net_income'] : 0;

                    $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
                    $salesSummaryArr['total']['volume'] += !empty($summaryArr[$supplierId]['volume']) ? $summaryArr[$supplierId]['volume'] : 0;

                    $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
                    $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$supplierId]['net_income']) ? $summaryArr[$supplierId]['net_income'] : 0;
                }
            }
//            echo '<pre>';
//            print_r($monthArr);
//            print_r($salesSummaryArr);
//            exit;

            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[57][6])) {
                return redirect('/dashboard');
            }
            return view('report.supplierWiseSalesSummary.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'salesSummaryArr', 'supplierList', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[57][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.supplierWiseSalesSummary.print.index', compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'salesSummaryArr', 'supplierList', 'fromDate', 'toDate'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('supplier_wise_sales_summary_report' . $fromDate . '_' . $toDate . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.supplierWiseSalesSummary.index')->with(compact('request', 'salesSummaryArr', 'supplierList'
                                    , 'fromDate', 'toDate', 'productList'));
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
            return redirect('supplierWiseSalesSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('supplierWiseSalesSummaryReport?generate=true&' . $url);
    }

}
