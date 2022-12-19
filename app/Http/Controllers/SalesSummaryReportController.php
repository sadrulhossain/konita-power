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
use DateTime;
use PDF;
use Illuminate\Http\Request;

class SalesSummaryReportController extends Controller {

    public function index(Request $request) {



        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';

        $summaryArr = $monthArr = $salesSummaryArr = $totalArr = [];

        if ($request->generate == 'true') {
            if (!empty($request->pi_from_date)) {
                $fromDate = date("Y-m-01", strtotime($request->pi_from_date));
            }
            if (!empty($request->pi_to_date)) {
                $toDate = date("Y-m-t", strtotime($request->pi_to_date));
            }

            $startDay = new DateTime($fromDate);
            $endDay = new DateTime($toDate);

            $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->whereBetween('inquiry.pi_date', [$fromDate, $toDate])
                    ->select(DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income')
                            , 'inquiry_details.quantity', 'inquiry.pi_date', 'inquiry_details.id as inquiry_details_id')
                    ->get();
            
            $cmsnIncomeSummaryArr = [];
            if (!$salesSummaryInfoArr->isEmpty()) {
                foreach ($salesSummaryInfoArr as $summary) {
                    $cmsnIncomeSummaryArr[$summary->pi_date][$summary->inquiry_details_id]['quantity'] = $summary->quantity ?? 0;
                    $cmsnIncomeSummaryArr[$summary->pi_date][$summary->inquiry_details_id]['net_income'] = $summary->net_income ?? 0;
                }
            }

            if (!empty($cmsnIncomeSummaryArr)) {
                foreach ($cmsnIncomeSummaryArr as $piDate => $inquiry) {
                    foreach ($inquiry as $inquiryDetailsId => $details) {
                        $summaryArr[$piDate]['volume'] = $summaryArr[$piDate]['volume'] ?? 0;
                        $summaryArr[$piDate]['volume'] += $details['quantity'] ?? 0;
                        
                        $summaryArr[$piDate]['net_income'] = $summaryArr[$piDate]['net_income'] ?? 0;
                        $summaryArr[$piDate]['net_income'] += $details['net_income'] ?? 0;
                    }
                }
            }

            for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
                $day = $j->format("Y-m-d");
                $month = $j->format("Y-m");

                $salesSummaryArr[$month]['volume'] = !empty($salesSummaryArr[$month]['volume']) ? $salesSummaryArr[$month]['volume'] : 0;
                $salesSummaryArr[$month]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

                $salesSummaryArr[$month]['net_income'] = !empty($salesSummaryArr[$month]['net_income']) ? $salesSummaryArr[$month]['net_income'] : 0;
                $salesSummaryArr[$month]['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

                $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
                $salesSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

                $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
                $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

                $monthArr[$month] = $j->format("F Y");
            }

            if (!empty($salesSummaryArr)) {
                foreach ($salesSummaryArr as $month => $sales) {
                    $prevMonth = date("Y-m", strtotime("-1 month", strtotime($month)));
                    $thisMonthVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                    $thisMonthIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                    $prevMonthVolume = !empty($salesSummaryArr[$prevMonth]['volume']) ? $salesSummaryArr[$prevMonth]['volume'] : 0;
                    $prevMonthIncome = !empty($salesSummaryArr[$prevMonth]['net_income']) ? $salesSummaryArr[$prevMonth]['net_income'] : 0;

                    $volumeDeviation = (($thisMonthVolume - $prevMonthVolume) * 100) / ($prevMonthVolume > 0 ? $prevMonthVolume : 1);
                    $incomeDeviation = (($thisMonthIncome - $prevMonthIncome) * 100) / ($prevMonthIncome > 0 ? $prevMonthIncome : 1);

                    $salesSummaryArr[$month]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                    $salesSummaryArr[$month]['net_income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
                }
            }

            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[55][6])) {
                return redirect('/dashboard');
            }
            return view('report.salesSummary.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'salesSummaryArr', 'monthArr', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[55][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.salesSummary.print.index', compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'salesSummaryArr', 'monthArr', 'fromDate', 'toDate'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('sales_summary_report' . $fromDate . '_' . $toDate . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.salesSummary.index')->with(compact('request', 'salesSummaryArr', 'monthArr'
                                    , 'fromDate', 'toDate'));
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
        $url = 'pi_from_date=' . $request->pi_from_date . '&pi_to_date=' . $request->pi_to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('salesSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('salesSummaryReport?generate=true&' . $url);
    }

}
