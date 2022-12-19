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
use App\CrmSource;
use App\CrmOpportunity;
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

class CrmSummaryReportController extends Controller {

    public function index(Request $request) {


        $sourceList = ['0' => __('label.SELECT_SOURCE_OPT')] + CrmSource::pluck('name', 'id')->toArray();


        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $statusList = Common::getOpportunityStatusList(1);
        $phoneNumber = '';

        $fromDate = $toDate = '';

        $summaryArr = $monthArr = $crmSummaryArr = $totalArr = [];

        if ($request->generate == 'true') {
            if (!empty($request->pi_from_date)) {
                $fromDate = date("Y-m-01 00:00:00", strtotime($request->pi_from_date));
            }
            if (!empty($request->pi_to_date)) {
                $toDate = date("Y-m-t 23:59:59", strtotime($request->pi_to_date));
            }
            $startDay = new DateTime($fromDate);
            $endDay = new DateTime($toDate);

            $crmSummaryInfoArr = CrmOpportunity::select('id', 'updated_at')
                    ->whereBetween('updated_at', [$fromDate, $toDate]);

            if (!empty($request->status)) {
                if ($request->status == '1') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '0');
                } elseif ($request->status == '2') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '1')->where('last_activity_status', '0');
                } elseif ($request->status == '3') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '3');
                } elseif ($request->status == '4') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '4');
                } elseif ($request->status == '5') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '2')->where('dispatch_status', '1')->where('dispatch_status', '0');
                } elseif ($request->status == '6') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '2')->where('dispatch_status', '1')->where('dispatch_status', '1');
                } elseif ($request->status == '7') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '2')->where('dispatch_status', '1')->where('dispatch_status', '2');
                } elseif ($request->status == '8') {
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('status', '1')->where('revoked_status', '1');
                } else {
                    $lastActivityStatus = $request->status - 8;
                    $crmSummaryInfoArr = $crmSummaryInfoArr->where('last_activity_status', $lastActivityStatus);
                }
            }
            $crmSummaryInfoArr = $crmSummaryInfoArr->get();
            
            


            if (!$crmSummaryInfoArr->isEmpty()) {
                foreach ($crmSummaryInfoArr as $summary) {
                    $updatedAt = date('Y-m-d', strtotime($summary->updated_at));
                    $summaryArr[$updatedAt]['no_of_opp'] = !empty($summaryArr[$updatedAt]['no_of_opp']) ? $summaryArr[$updatedAt]['no_of_opp'] : 0;
                    $summaryArr[$updatedAt]['no_of_opp'] += 1;
                }
            }

            for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
                $day = $j->format("Y-m-d");
                $month = $j->format("Y-m");

                $crmSummaryArr[$month]['no_of_opp'] = !empty($crmSummaryArr[$month]['no_of_opp']) ? $crmSummaryArr[$month]['no_of_opp'] : 0;
                $crmSummaryArr[$month]['no_of_opp'] += !empty($summaryArr[$day]['no_of_opp']) ? $summaryArr[$day]['no_of_opp'] : 0;
                
                $crmSummaryArr['total']['no_of_opp'] = $crmSummaryArr['total']['no_of_opp'] ?? 0;
                $crmSummaryArr['total']['no_of_opp'] += !empty($summaryArr[$day]['no_of_opp']) ? $summaryArr[$day]['no_of_opp'] : 0;

                $monthArr[$month] = $j->format("F Y");
            }
//            echo '<pre>';
//            print_r($crmSummaryArr);
//            exit;

            if (!empty($crmSummaryArr)) {
                foreach ($crmSummaryArr as $month => $crm) {
                    $prevMonth = date("Y-m", strtotime("-1 month", strtotime($month)));
                    $thisMonthOpp = !empty($crm['no_of_opp']) ? $crm['no_of_opp'] : 0;
                    $prevMonthOpp = !empty($crmSummaryArr[$prevMonth]['no_of_opp']) ? $crmSummaryArr[$prevMonth]['no_of_opp'] : 0;

                    $oppDeviation = (($thisMonthOpp - $prevMonthOpp) * 100) / ($prevMonthOpp > 0 ? $prevMonthOpp : 1);

                    $crmSummaryArr[$month]['opp_deviation'] = Helper::numberFormatDigit2($oppDeviation);
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
            return view('report.crmSummary.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'crmSummaryArr', 'monthArr', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[55][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.crmSummary.print.index', compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'crmSummaryArr', 'monthArr', 'fromDate', 'toDate'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('crm_summary_report' . $fromDate . '_' . $toDate . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.crmSummary.index')->with(compact('request', 'crmSummaryArr', 'monthArr'
                                    , 'fromDate', 'toDate', 'statusList', 'sourceList'));
        }
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'pi_from_date' => 'required',
            'pi_to_date' => 'required',
            'status' => 'required|not_in:0'
        ];

        $messages = [
            'pi_from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'pi_to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
            'status.required' => __('label.THE_STATUS_FIELD_IS_REQUIRED'),
        ];
        $url = 'pi_from_date=' . $request->pi_from_date . '&pi_to_date=' . $request->pi_to_date
                . '&status=' . $request->status;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('crmSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('crmSummaryReport?generate=true&' . $url);
    }

}
