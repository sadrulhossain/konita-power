<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Buyer;
use App\BuyerFactory;
use App\BuyerFollowUpHistory;
use App\BuyerToProduct;
use App\SalesPersonToBuyer;
use App\User;
use App\Lead;
use App\InquiryDetails;
use App\BuyerMachineType;
use App\BuyerToGsmVolume;
use App\ContactDesignation;
use App\FinishedGoods;
use App\BuyerPayment;
use App\CauseOfFailure;
use App\Invoice;
use App\InvoiceCommissionHistory;
use App\Receive;
use App\Delivery;
use App\DeliveryDetails;
use App\CompanyInformation;
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

class BuyerProfileController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        //buyer information
        $target = Buyer::join('buyer_category', 'buyer_category.id', 'buyer.buyer_category_id')
                        ->leftJoin('country', 'country.id', 'buyer.country_id')
                        ->leftJoin('division', 'division.id', 'buyer.division_id')
                        ->select('buyer.id', 'buyer.code', 'buyer.logo', 'buyer.status'
                                , 'buyer.name', 'buyer.head_office_address', 'buyer.created_at'
                                , 'buyer_category.name as category', 'country.name as country'
                                , 'division.name as division', 'buyer.contact_person_data'
                                , 'buyer.fsc_certified', 'buyer.iso_certified', 'buyer.customer_type as type'
                                , 'buyer.related_competitors_product as competitors_product'
                                , 'buyer.related_finished_goods as finished_goods', 'buyer.machine_brand')
                        ->where('buyer.user_id', Auth::user()->id)->first();
        
        $typeArr = [];
        $id = !empty($target->id) ? $target->id : 0;

        $inquiryList = Lead::where('buyer_id', $id)->whereIn('order_status', ['2', '3', '4', '6'])->pluck('id', 'id')->toArray();

        //buyer type
        if (!empty($target->type)) {
            $typeArr = explode(",", $target->type);
        }

        //buyer primary factory
        $primaryFactory = BuyerFactory::select('name', 'address')->where('buyer_id', $id)
                        ->where('primary_factory', '1')->where('status', '1')->first();

        //start :: buyer latest followup 
        $followUpPrevHistory = BuyerFollowUpHistory::select('history')
                        ->where('buyer_id', $id)->first();


        if (!empty($followUpPrevHistory)) {
            $followUpHistoryArr = json_decode($followUpPrevHistory->history, true);
            krsort($followUpHistoryArr);
            $i = 0;

            if (!empty($followUpHistoryArr)) {
                foreach ($followUpHistoryArr as $followUpHistory) {
                    $finalArr[$followUpHistory['updated_at']][$i]['status'] = $followUpHistory['status'];
                    $i++;
                }
            }

            krsort($finalArr);
        }

        $latestFollowupArr = $latestFollowup = [];
        if (!empty($finalArr)) {
            foreach ($finalArr as $followUpHistory) {
                $latestFollowup = reset($followUpHistory);
                $latestFollowupArr['status'] = $latestFollowup['status'];
            }
        }

        //end :: buyer latest followup 
        //business start date - pi date of confirmed order for the first time
        $businessInitationDate = Lead::select(DB::raw('MIN(pi_date) as start'))
                ->where('buyer_id', $id)->whereIn('order_status', ['2', '3', '4'])
                ->first();

        //buyer contact person
        $contactPersonArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
        }

        //fineshed goods
        $finishedGoodsArr = [];
        if (!empty($target->finished_goods)) {
            $finishedGoodsArr = json_decode($target->finished_goods, true);
        }

        //competitors' product
        $competitorsProductArr = [];
        if (!empty($target->competitors_product)) {
            $competitorsProductArr = json_decode($target->competitors_product, true);
        }

        $finishedGoodsList = FinishedGoods::pluck('name', 'id')->toArray();
        $competitorsProductList = Product::where('competitors_product', '1')
                        ->pluck('name', 'id')->toArray();

        $contactDesignationList = ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //start :: actively engaged sales person
        $activelyEngagedSalesPersonArr = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                ->join('designation', 'designation.id', 'users.designation_id');
        $activelyEngagedSalesPersonIdArr = $activelyEngagedSalesPersonArr->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')->pluck('users.id', 'users.id')
                ->toArray();
        $activelyEngagedSalesPersonArr = $activelyEngagedSalesPersonArr->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                        , 'designation.title as designation', 'users.photo', 'users.phone'
                        , 'users.id', 'users.employee_id', 'users.email')
                ->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')
                ->where('users.allowed_for_sales', '1')
                ->where('users.status', '1')
                ->orderBy('designation.order', 'asc')
                ->get();

        $activelyEngagedSalesPersonOrderList = [];

        //end :: actively engaged sales person
        //start :: product info
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->join('country', 'country.id', 'brand.origin')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo', 'measure_unit.name as unit'
                                ,'country.name as country_of_origin')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), DB::raw("SUM(inquiry_details.total_price) as total_amount"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4']);

        $overAllSalesSummaryArr = $salesSummaryInfoArr->first();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
						->where('inquiry.buyer_id', $id)
						->whereIn('inquiry.order_status', ['2', '3', '4'])
						->get();
        
        $totalSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"))
                        ->where('inquiry.buyer_id', $id)
						->whereIn('inquiry.order_status', ['2', '3', '4'])->first();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($totalSalesVolumeInfo->total_volume) && $totalSalesVolumeInfo->total_volume != 0) ? $totalSalesVolumeInfo->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume'] = $info->total_volume;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id]['volume_rate'] = $volumeRate;
            }
        }

        $productInfoArr = $productRowSpanArr = [];
        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                if (!empty($brandWiseVolumeRateArr)) {
                    if (array_key_exists($item->product_id, $brandWiseVolumeRateArr)) {
                        if (array_key_exists($item->brand_id, $brandWiseVolumeRateArr[$item->product_id])) {
                            $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                            $productInfoArr[$item->product_id]['unit'] = $item->unit;
                            $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                            $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;
                            $productInfoArr[$item->product_id]['brand'][$item->brand_id]['origin'] = $item->country_of_origin;

                            $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
                        }
                    }
                }
            }
        }



        //import volume
        $buyerImportVolumeInfo = BuyerToGsmVolume::join('product', 'product.id', 'buyer_to_gsm_volume.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_gsm_volume.set_gsm_volume', 'buyer_to_gsm_volume.product_id'
                                , 'measure_unit.name as unit')
                        ->where('buyer_to_gsm_volume.buyer_id', $id)->get();

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

                $importVolArr[$volume->product_id]['unit'] = $volume->unit ?? '';
                $importVolArr[$volume->product_id]['volume'] = $importVolArr[$volume->product_id]['volume'] ?? 0;
                $importVolArr[$volume->product_id]['volume'] += $gsmVol;
            }
        }
        //end :: product info
        //start :: inquiry count
        $inquiryCountInfoArr = Lead::select('id', 'status', 'order_status', 'order_cancel_cause', 'cancel_cause')
                        ->whereIn('id', $inquiryList)->get();

        $inquiryCountArr = $cancelCauseArr = $mostFrequentCancelCauseArr = [];
        if (!$inquiryCountInfoArr->isEmpty()) {
            foreach ($inquiryCountInfoArr as $item) {
                if ($item->order_status == '2') {
                    $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                    $inquiryCountArr['matured'] += 1;
                    $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                    $inquiryCountArr['confirmed'] += 1;
                } elseif ($item->order_status == '3') {
                    $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                    $inquiryCountArr['matured'] += 1;
                    $inquiryCountArr['processing'] = !empty($inquiryCountArr['processing']) ? $inquiryCountArr['processing'] : 0;
                    $inquiryCountArr['processing'] += 1;
                } elseif ($item->order_status == '4') {
                    $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                    $inquiryCountArr['matured'] += 1;
                    $inquiryCountArr['accomplished'] = !empty($inquiryCountArr['accomplished']) ? $inquiryCountArr['accomplished'] : 0;
                    $inquiryCountArr['accomplished'] += 1;
                } elseif ($item->order_status == '6') {
                    $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                    $inquiryCountArr['failed'] += 1;
                }

                $inquiryCountArr['total'] = !empty($inquiryCountArr['total']) ? $inquiryCountArr['total'] : 0;
                $inquiryCountArr['total'] += 1;

                if (!empty($item->cancel_cause) && $item->cancel_cause != 0) {
                    $cancelCauseArr[$item->cancel_cause] = !empty($cancelCauseArr[$item->cancel_cause]) ? $cancelCauseArr[$item->cancel_cause] : 0;
                    $cancelCauseArr[$item->cancel_cause] += 1;
                }

                if (!empty($item->order_cancel_cause) && $item->order_cancel_cause != 0) {
                    $cancelCauseArr[$item->order_cancel_cause] = !empty($cancelCauseArr[$item->order_cancel_cause]) ? $cancelCauseArr[$item->order_cancel_cause] : 0;
                    $cancelCauseArr[$item->order_cancel_cause] += 1;
                }
            }
        }
        //end :: inquiry count

        if (!empty($cancelCauseArr)) {
            $mostFrequentCancelCauseArr = array_keys($cancelCauseArr, max($cancelCauseArr));
        }

        $cancelCauseList = CauseOfFailure::pluck('title', 'id')->toArray();

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
        $fiveYearsAgo = date("Y-m-d", strtotime("-5 year"));



//        echo '<pre>';
//        print_r($overAllSalesSummaryArr->total_volume);
//        print_r($brandWiseVolumeRateArr);
//        exit;

        $lastOneYearSalesSummaryArr = $salesSummaryInfoArr->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])->first();

        $buyerPaymentInfoArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("(delivery_details.shipment_quantity * inquiry_details.unit_price) as amount")
                                , 'delivery.buyer_payment_status', 'delivery_details.shipment_quantity', 'delivery_details.delivery_id'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $buyerPaymentArr = $deliveryIdArr = [];
        if (!$buyerPaymentInfoArr->isEmpty()) {
            foreach ($buyerPaymentInfoArr as $payment) {
                $deliveryIdArr[$payment->delivery_id] = $payment->delivery_id;

                $buyerPaymentArr['due'] = !empty($buyerPaymentArr['due']) ? $buyerPaymentArr['due'] : 0;
                $buyerPaymentArr['paid'] = !empty($buyerPaymentArr['paid']) ? $buyerPaymentArr['paid'] : 0;

                $buyerPaymentArr['shipped_quantity'] = !empty($buyerPaymentArr['shipped_quantity']) ? $buyerPaymentArr['shipped_quantity'] : 0;
                $buyerPaymentArr['shipped_quantity'] += !empty($payment->shipment_quantity) ? $payment->shipment_quantity : 0;

                $buyerPaymentArr['payable'] = !empty($buyerPaymentArr['payable']) ? $buyerPaymentArr['payable'] : 0;
                $buyerPaymentArr['payable'] += !empty($payment->amount) ? $payment->amount : 0;

                if ($payment->buyer_payment_status == '0') {
                    $buyerPaymentArr['due'] += (!empty($payment->amount) ? $payment->amount : 0);
                } else {
                    $buyerPaymentArr['paid'] += (!empty($payment->amount) ? $payment->amount : 0);
                }
            }
        }

        //start :: invoiced amount
        $invoiceInfoArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select('invoice.id as invoice_id', 'invoice.bl_no_history')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $blHistoryArr = [];
        if (!$invoiceInfoArr->isEmpty()) {
            foreach ($invoiceInfoArr as $inv) {
                $blHistoryArr[$inv->invoice_id] = json_decode($inv->bl_no_history, true);
            }
        }
        $invoicedAmount = 0;
        if (!empty($blHistoryArr)) {
            foreach ($blHistoryArr as $invoiceId => $blHistory) {
                if (!empty($blHistory)) {
                    foreach ($blHistory as $deliveryId => $bl) {
                        if (array_key_exists($deliveryId, $deliveryIdArr)) {
                            foreach ($bl as $deliveryDetailsId => $details) {
                                $invoicedAmount = !empty($invoicedAmount) ? $invoicedAmount : 0;
                                $invoicedAmount += !empty($details['shipment_total_price']) ? $details['shipment_total_price'] : 0;
                            }
                        }
                    }
                }
            }
        }
        //end :: invoiced amount
        //start :: received amount & commission
        $received = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.buyer_commission) as total_buyer_commission")
                                , DB::raw("SUM(receive.company_commission + rebate_commission) as net_income")
                                , DB::raw("SUM(receive.collection_amount) as total_collection"))
                        ->where('inquiry.buyer_id', $id)->first();

        $paid = BuyerPayment::select(DB::raw("SUM(amount) as amount"))
                        ->where('buyer_id', $id)->first();

        $commissionReceived = !empty($received->total_buyer_commission) ? $received->total_buyer_commission : 0;
        $commissionPaid = !empty($paid->amount) ? $paid->amount : 0;
        $commissionDue = $commissionReceived - $commissionPaid;
        //end :: received amount & commission


        $startDay = new DateTime($fiveYearsAgo);
        $endDay = new DateTime($today);

        //start :: net income
        $salesSummaryInfoArr2 = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->select('inquiry_details.total_price', 'inquiry_details.quantity', 'inquiry.pi_date'
                        , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.buyer_id', $id);

        $overAllSalesSummaryInfoArr = $salesSummaryInfoArr2->get();

        $netIncome = 0;
        if (!$overAllSalesSummaryInfoArr->isEmpty()) {
            foreach ($overAllSalesSummaryInfoArr as $summary) {
                $netIncome = $netIncome ?? 0;
                $netIncome += $summary->net_income ?? 0;
            }
        }
        //end :: net income

        $last5YearsSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw('SUM(inquiry_details.quantity) as total_qty'), 'inquiry_details.product_id', 'inquiry.pi_date')
                        ->groupBy('inquiry_details.product_id', 'inquiry.pi_date')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.buyer_id', $id)
                        ->whereBetween('inquiry.pi_date', [$fiveYearsAgo, $today])->get();

        //start :: sales summary
        if (!$last5YearsSalesSummaryInfoArr->isEmpty()) {
            foreach ($last5YearsSalesSummaryInfoArr as $summary) {
                $summaryArr[$summary->product_id][$summary->pi_date]['volume'] = $summary->total_qty ?? 0;
            }
        }

        $salesSummaryArr = [];
        
        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $year = $j->format("Y");

            if (!empty($productInfoArr)) {
                foreach ($productInfoArr as $productId => $product) {
                    $salesSummaryArr[$productId][$year]['volume'] = !empty($salesSummaryArr[$productId][$year]['volume']) ? $salesSummaryArr[$productId][$year]['volume'] : 0;
                    $salesSummaryArr[$productId][$year]['volume'] += !empty($summaryArr[$productId][$day]['volume']) ? $summaryArr[$productId][$day]['volume'] : 0;


                    $salesSummaryArr[$productId]['total']['volume'] = $salesSummaryArr[$productId]['total']['volume'] ?? 0;
                    $salesSummaryArr[$productId]['total']['volume'] += !empty($summaryArr[$productId][$day]['volume']) ? $summaryArr[$productId][$day]['volume'] : 0;
                }
            }


            $yearArr[$year] = $j->format("Y");
        }

        if (!empty($salesSummaryArr)) {
            foreach ($salesSummaryArr as $year => $sales) {
                $prevYear = date("Y", strtotime("-1 year", strtotime($year)));
                $thisYearVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                $thisYearAmount = !empty($sales['amount']) ? $sales['amount'] : 0;
                $thisYearIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                $prevYearVolume = !empty($salesSummaryArr[$prevYear]['volume']) ? $salesSummaryArr[$prevYear]['volume'] : 0;
                $prevYearAmount = !empty($salesSummaryArr[$prevYear]['amount']) ? $salesSummaryArr[$prevYear]['amount'] : 0;
                $prevYearIncome = !empty($salesSummaryArr[$prevYear]['net_income']) ? $salesSummaryArr[$prevYear]['net_income'] : 0;

                $volumeDeviation = (($thisYearVolume - $prevYearVolume) * 100) / ($prevYearVolume > 0 ? $prevYearVolume : 1);
                $amountDeviation = (($thisYearAmount - $prevYearAmount) * 100) / ($prevYearAmount > 0 ? $prevYearAmount : 1);
                $incomeDeviation = (($thisYearIncome - $prevYearIncome) * 100) / ($prevYearIncome > 0 ? $prevYearIncome : 1);

                $salesSummaryArr[$year]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                $salesSummaryArr[$year]['amount_deviation'] = Helper::numberFormatDigit2($amountDeviation);
                $salesSummaryArr[$year]['income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
            }
        }
        //end :: sales summary

        $inquiryInfoArr = Lead::leftJoin('supplier', 'supplier.id', 'inquiry.supplier_id')
                        ->join('users', 'users.id', 'inquiry.salespersons_id')
                        ->select('inquiry.*', 'supplier.name as supplier'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                        ->whereIn('inquiry.id', $inquiryList)->get();



        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                        ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                                , 'grade.name as grade_name', 'measure_unit.name as unit')
                        ->whereIn('inquiry.id', $inquiryList)->get();

        $inquiryDetailsArr = $inquryRowSpanArr = $productRowSpanArr2 = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            $inquryRowSpanArr[$inquiryId] = !empty($inquryRowSpanArr[$inquiryId]) ? $inquryRowSpanArr[$inquiryId] : 0;
                            $inquryRowSpanArr[$inquiryId] += 1;

                            $productRowSpanArr2[$inquiryId][$productId] = !empty($productRowSpanArr2[$inquiryId][$productId]) ? $productRowSpanArr2[$inquiryId][$productId] : 0;
                            $productRowSpanArr2[$inquiryId][$productId] += 1;

                            $brandRowSpanArr[$inquiryId][$productId][$brandId] = !empty($brandRowSpanArr[$inquiryId][$productId][$brandId]) ? $brandRowSpanArr[$inquiryId][$productId][$brandId] : 0;
                            $brandRowSpanArr[$inquiryId][$productId][$brandId] += 1;
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                                , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                        ->whereIn('inquiry.id', $inquiryList)->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


        $loadView = ($request->view == 'print') ? '.print' : '';
        return view('buyerProfile' . $loadView . '.index')->with(compact('target', 'qpArr', 'request', 'typeArr'
                                , 'primaryFactory', 'latestFollowupArr', 'businessInitationDate'
                                , 'contactPersonArr', 'contactDesignationList', 'activelyEngagedSalesPersonOrderList'
                                , 'activelyEngagedSalesPersonArr', 'productInfoArr', 'productRowSpanArr'
                                , 'importVolArr', 'competitorsProductArr', 'finishedGoodsArr'
                                , 'finishedGoodsList', 'competitorsProductList', 'inquiryCountArr'
                                , 'cancelCauseList', 'mostFrequentCancelCauseArr', 'overAllSalesSummaryArr'
                                , 'lastOneYearSalesSummaryArr', 'commissionDue', 'invoicedAmount'
                                , 'salesSummaryArr', 'yearArr', 'buyerPaymentArr', 'received'
                                , 'commissionReceived', 'commissionPaid', 'netIncome'
                                , 'brandWiseVolumeRateArr', 'konitaInfo', 'phoneNumber'
                                , 'inquiryInfoArr', 'inquiryDetailsArr', 'inquryRowSpanArr'
                                , 'productRowSpanArr2', 'brandRowSpanArr', 'lsdArr', 'deliveryArr'));
    }

    //****************************** start :: buyer profile ********************************//

    public function getInvolvedOrderList(Request $request) {
        $loadView = 'buyerProfile.showInvolvedOrderList';
        $buyerLogin = 1;
        return Common::getInvolvedOrderList($request, $loadView, $buyerLogin);
    }

    public function printInvolvedOrderList(Request $request) {
        $loadView = 'buyerProfile.print.showInvolvedOrderList';
        $modueId = 0;
        $buyerLogin = 1;
        return Common::printInvolvedOrderList($request, $loadView, $modueId, $buyerLogin);
    }

    //****************************** end :: buyer profile *********************************//
	
	public function updateLogo(Request $request) {
		//begin back same page after update
        $qpArr = $request->all();
        //end back same page after update
        $target = Buyer::find($request->buyer_id);
        $buyerUser = User::select('id')->where('id', $target->user_id)->first();
        $user = !empty($buyerUser->id) ? User::find($buyerUser->id) : new User;
        $userId = !empty($buyerUser->id) ? $buyerUser->id : 0;

        
        $rules = $message = array();

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/buyer/' . $target->logo;
            $prevfileNameUser = 'public/uploads/user/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
            if (File::exists($prevfileNameUser)) {
                File::delete($prevfileNameUser);
            }
        }

        //logo upload
        $file = $request->file('logo');
        $logoName = '';
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
        }


        $target->logo = !empty($logoName) ? $logoName : $target->logo;
		
        $user->photo = !empty($logoName) ? $logoName : $target->logo;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (!empty($logoName)) {
                    $userPicUp = $file->move('public/uploads/buyer', $target->logo);

                    if (!empty($request->username)) {
                        if (File::exists('public/uploads/user', $target->logo)) {
                            File::delete('public/uploads/user', $target->logo);
                        }
                        copy('public/uploads/buyer/' . $target->logo, 'public/uploads/user/' . $target->logo);
                    } else {
                        if (File::exists('public/uploads/user', $target->logo)) {
                            File::delete('public/uploads/user', $target->logo);
                        }
                    }
                } else {
                    if (empty($request->username)) {
                        if (File::exists('public/uploads/user', $target->logo)) {
                            File::delete('public/uploads/user', $target->logo);
                        }
                    } else {
                        if (!File::exists('public/uploads/user', $target->logo)) {
                            copy('public/uploads/buyer/' . $target->logo, 'public/uploads/user/' . $target->logo);
                        }
                    }
                }
                $user->save();
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYER_LOGO_UPDATED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYER_LOGO_COULD_NOT_BE_UPDATED')], 401);
        }
    }
	
}
