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

class PurchaseSummaryReportController extends Controller {

    public function index(Request $request) {
        $buyer = Buyer::select('id')->where('user_id', Auth::user()->id)->first();
        $id = !empty($buyer->id) ? $buyer->id : 0;

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';

        $summaryArr = $monthArr = $purchaseSummaryArr = $totalArr = [];

        if ($request->generate == 'true') {
            if (!empty($request->pi_from_date)) {
                $fromDate = date("Y-m-01", strtotime($request->pi_from_date));
            }
            if (!empty($request->pi_to_date)) {
                $toDate = date("Y-m-t", strtotime($request->pi_to_date));
            }

            $startDay = new DateTime($fromDate);
            $endDay = new DateTime($toDate);

            $purchaseSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->where('inquiry.buyer_id', $id)
                    ->whereBetween('inquiry.pi_date', [$fromDate, $toDate])
                    ->select('inquiry_details.quantity', 'inquiry.pi_date')
                    ->get();


            if (!$purchaseSummaryInfoArr->isEmpty()) {
                foreach ($purchaseSummaryInfoArr as $summary) {
                    $summaryArr[$summary->pi_date]['volume'] = $summaryArr[$summary->pi_date]['volume'] ?? 0;
                    $summaryArr[$summary->pi_date]['volume'] += $summary->quantity ?? 0;
                }
            }

            for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
                $day = $j->format("Y-m-d");
                $month = $j->format("Y-m");

                $purchaseSummaryArr[$month]['volume'] = !empty($purchaseSummaryArr[$month]['volume']) ? $purchaseSummaryArr[$month]['volume'] : 0;
                $purchaseSummaryArr[$month]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;


                $purchaseSummaryArr['total']['volume'] = $purchaseSummaryArr['total']['volume'] ?? 0;
                $purchaseSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;


                $monthArr[$month] = $j->format("F Y");
            }


            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            return view('report.purchaseSummary.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'purchaseSummaryArr', 'monthArr', 'fromDate', 'toDate'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.purchaseSummary.print.index', compact('request', 'konitaInfo', 'phoneNumber'
                                    , 'purchaseSummaryArr', 'monthArr', 'fromDate', 'toDate'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('purchase_summary_report' . $fromDate . '_' . $toDate . '.pdf');
//            return $pdf->stream();
        } else {
            return view('report.purchaseSummary.index')->with(compact('request', 'purchaseSummaryArr', 'monthArr'
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
            return redirect('purchaseSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('purchaseSummaryReport?generate=true&' . $url);
    }

}
