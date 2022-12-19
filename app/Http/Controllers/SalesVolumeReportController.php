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
use App\SalesPersonToBuyer;
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

class SalesVolumeReportController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.id', Auth::user()->id)->first();

        //sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);
        //endof arr


        $salesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                ->where('users.allowed_for_sales', '1');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $salesPersonArr = $salesPersonArr->whereIn('users.id', $finalUserIdArr);
        }
        $salesPersonArr = $salesPersonArr->pluck('name', 'users.id')->toArray();
        $salesPersonList = ['0' => __('label.SELECT_SALES_PERSON_OPT')] + $salesPersonArr;


        //buyer list
        $buyerIdArr = SalesPersonToBuyer::where('sales_person_id', Auth::user()->id)->pluck('buyer_id')->toArray();

        $buyerList = Buyer::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $buyerList = $buyerList->whereIn('id', $buyerIdArr);
        }

        $buyerList = $buyerList->pluck('name', 'id')->toArray();
        $buyerList = array('0' => __('label.SELECT_BUYER_OPT')) + $buyerList;

        //product list
        $productIdArr = SalesPersonToProduct::where('sales_person_id', Auth::user()->id)->pluck('product_id')->toArray();

        $productList = Product::orderBy('name', 'asc');
        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $productList = $productList->whereIn('id', $productIdArr);
        }
        $productList = $productList->pluck('name', 'id')->toArray();
        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + $productList;



        //brandList
        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::pluck('name', 'id')->toArray();

        //supplier list
        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::join('inquiry', 'inquiry.supplier_id', '=', 'supplier.id')
                        ->pluck('supplier.name', 'supplier.id')->toArray();

        $fromDate = $toDate = '';
        $rowspanArr = [];
        $inquiryIdArr = $targetArr = $prevComsnArr = $inquiryDetailsArr = $comsnIncomeArr = $commissionArr = [];

        $productArr = Product::pluck('name', 'id')->toArray();
        $brandArr = Brand::pluck('name', 'id')->toArray();
        $gradeArr = Grade::pluck('name', 'id')->toArray();

        $totalSalesVolume = $totalSalesAmount = $totalKonitaCommission = $totalAdminCost = $totalCommission = 0;
        $lcTransmitted = $notLcTransmitted = $totalKonitaNetCmsn = 0;
        $konitaCmsn = $salesPersonCmsn = $buyerCmsn = $rebateCmsn = $principalCmsn = 0;
        $countryWiseAccount = [];

        $countryList = Country::pluck('name', 'id')->toArray();

        $deliveryArr = $profitArr = [];

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';


        if ($request->generate == 'true') {

            if (!empty($request->pi_from_date)) {
                $fromDate = Helper::dateFormatConvert($request->pi_from_date);
            }
            if (!empty($request->pi_to_date)) {
                $toDate = Helper::dateFormatConvert($request->pi_to_date);
            }


            //inquiry Details
            $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                    ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                    ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                    ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                    ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'inquiry_details.inquiry_id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->where('inquiry.pi_date', '>=', $fromDate)
                    ->where('inquiry.pi_date', '<=', $toDate);
            if (!empty($request->salespersons_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
                $inquiryDetails = $inquiryDetails->whereIn('inquiry.salespersons_id', $finalUserIdArr);
            }
            if (!empty($request->buyer_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.buyer_id', $request->buyer_id);
            }

            if (!empty($request->product_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry_details.product_id', $request->product_id);
            }
            if (!empty($request->brand_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry_details.brand_id', $request->brand_id);
            }
            if (!empty($request->supplier_id)) {
                $inquiryDetails = $inquiryDetails->where('inquiry.supplier_id', $request->supplier_id);
            }

            $inquiryDetails = $inquiryDetails->select('inquiry_details.id as inquiry_details_id', 'inquiry_details.inquiry_id'
                            , 'inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                            , 'product.name as product_name', 'brand.name as brand_name'
                            , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                            , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                            , 'grade.name as grade_name', 'commission_setup.konita_cmsn'
                            , 'commission_setup.principle_cmsn', 'commission_setup.sales_person_cmsn'
                            , 'commission_setup.buyer_cmsn', 'commission_setup.rebate_cmsn')
                    ->get();



            if (!$inquiryDetails->isEmpty()) {
                foreach ($inquiryDetails as $item) {
                    $inquiryIdArr[$item->inquiry_id] = $item->inquiry_id;
                }
            }

            $targetArr = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
//                    ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'inquiry.id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4'])
                    ->where('inquiry.pi_date', '>=', $fromDate)
                    ->where('inquiry.pi_date', '<=', $toDate);

            if (!empty($request->product_id) || !empty($request->brand_id)) {
                $targetArr = $targetArr->whereIn('inquiry.id', $inquiryIdArr);
            }
            if (!empty($request->salespersons_id)) {
                $targetArr = $targetArr->where('inquiry.salespersons_id', $request->salespersons_id);
            }
            if (!empty($request->buyer_id)) {
                $targetArr = $targetArr->where('inquiry.buyer_id', $request->buyer_id);
            }

            if (!empty($request->supplier_id)) {
                $targetArr = $targetArr->where('inquiry.supplier_id', $request->supplier_id);
            }
            if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
                $targetArr = $targetArr->whereIn('inquiry.salespersons_id', $finalUserIdArr);
            }
            $targetArr = $targetArr->select('inquiry.id', 'inquiry.order_no', 'inquiry.order_status', 'inquiry.purchase_order_no'
                            , 'buyer.name as buyerName', 'inquiry.pi_date', 'inquiry.lc_transmitted_copy_done'
                            , 'buyer.country_id', 'inquiry.supplier_id')
                    ->orderBy('inquiry.pi_date', 'desc');
            //begin filtering
            //end filtering
            $targetArr = $targetArr->get();



            //inquiry Details Arr

            if (!$inquiryDetails->isEmpty()) {
                foreach ($inquiryDetails as $item) {
                    $gradeId = !empty($item->grade_id) ? $item->grade_id : 0;
                    $gsm = !empty($item->gsm) ? $item->gsm : 0;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_name'] = $item->unit_name;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['sales_volume'] = $item->quantity;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['unit_price'] = $item->unit_price;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['sales_amount'] = $item->total_price;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['gsm'] = $item->gsm;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['inquiry_details_id'] = $item->inquiry_details_id;

                    $konitaCmsn = !empty($item->konita_cmsn) ? $item->konita_cmsn : 0;
                    $salesPersonCmsn = !empty($item->sales_person_cmsn) ? $item->sales_person_cmsn : 0;
                    $buyerCmsn = !empty($item->buyer_cmsn) ? $item->buyer_cmsn : 0;
                    $rebateCmsn = !empty($item->rebate_cmsn) ? $item->rebate_cmsn : 0;

                    $totalKonitaCmsn = ($konitaCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                    $expenditureCmsn = ($salesPersonCmsn + $buyerCmsn);
                    $konitaNetCmsn = ($konitaCmsn + $rebateCmsn);

                    $totalNetCmsn = $item->quantity * $konitaNetCmsn;
                    $totalExpenditureCmsn = $item->quantity * $expenditureCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['konita_net_cmsn'] = $totalNetCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['expenditure_cmsn'] = $totalExpenditureCmsn;

                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_konita_cmsn'] = $item->quantity * $totalKonitaCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['admin_cost'] = ($item->quantity * (!empty($item->principle_cmsn) ? $item->principle_cmsn : 0));
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['total_cmsn'] = ($item->quantity * $totalKonitaCmsn) + ($item->quantity * (!empty($item->principle_cmsn) ? $item->principle_cmsn : 0));
                    //individual wise commission*sales volume
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['konita_cmsn'] = $item->quantity * $konitaCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['salesPerson_cmsn'] = $item->quantity * $salesPersonCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['buyer_cmsn'] = $item->quantity * $buyerCmsn;
                    $inquiryDetailsArr[$item->inquiry_id][$item->product_id][$item->brand_id][$gradeId][$gsm]['rebate_cmsn'] = $item->quantity * $rebateCmsn;
                }
            }


            $commissionInfo = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)
                    ->select('konita_cmsn', 'principle_cmsn', 'sales_person_cmsn', 'buyer_cmsn'
                            , 'rebate_cmsn', 'inquiry_details_id', 'inquiry_id')
                    ->get();
            if (!$commissionInfo->isEmpty()) {
                foreach ($commissionInfo as $cmsn) {
                    $konitaCmsn = !empty($cmsn->konita_cmsn) ? $cmsn->konita_cmsn : 0;
                    $principleCmsn = !empty($cmsn->principle_cmsn) ? $cmsn->principle_cmsn : 0;
                    $salesPersonCmsn = !empty($cmsn->sales_person_cmsn) ? $cmsn->sales_person_cmsn : 0;
                    $buyerCmsn = !empty($cmsn->buyer_cmsn) ? $cmsn->buyer_cmsn : 0;
                    $rebateCmsn = !empty($cmsn->rebate_cmsn) ? $cmsn->rebate_cmsn : 0;

                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['konita_cmsn'] = ($konitaCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['company_konita_cmsn'] = $konitaCmsn;
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['principal_cmsn'] = $principleCmsn;
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['sales_person_cmsn'] = $salesPersonCmsn;
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['buyer_cmsn'] = $buyerCmsn;
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['rebate_cmsn'] = $rebateCmsn;

                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['expenditure_csmn'] = ($salesPersonCmsn + $buyerCmsn);
                    $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['konita_net_csmn'] = ($konitaCmsn + $rebateCmsn);
                }
            }

            //inquiry Details
            //START final targetArr
            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $key => $item) {
                    $targetArr[$key] = $item;
                    $targetArr[$key]['inquiryDetails'] = !empty($inquiryDetailsArr[$item->id]) ? $inquiryDetailsArr[$item->id] : '';
                }
            }


            //ENDOF final targetArr
            //START Rowspan Arr


            if (!empty($inquiryDetailsArr)) {
                foreach ($inquiryDetailsArr as $inquiryId => $inquiryData) {
                    foreach ($inquiryData as $productId => $productData) {
                        foreach ($productData as $brandId => $brandData) {
                            foreach ($brandData as $gradeId => $gradeData) {
                                foreach ($gradeData as $gsm => $item) {
                                    //rowspan for grade
                                    $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                    $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                    //rowspan for brand
                                    $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                    $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                    //rowspan for product
                                    $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                    $rowspanArr['product'][$inquiryId][$productId] += 1;
                                    //rowspan for inquiry
                                    $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                    $rowspanArr['inquiry'][$inquiryId] += 1;
                                }
                            }
                        }
                    }
                }
            }
            //ENDOF Rowspan Arr
            //SUMMARY

            if (!$targetArr->isEmpty()) {
                foreach ($targetArr as $target) {
                    foreach ($target->inquiryDetails as $productId => $productData) {
                        foreach ($productData as $brandId => $brandData) {
                            foreach ($brandData as $gradeId => $gsmData) {
                                foreach ($gsmData as $gsm => $item) {
                                    $totalSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                    $totalSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;

                                    //commission calculation
                                    $inquiryId = $target->id;
                                    $inqDetailsId = $item['inquiry_details_id'];
                                    $inquiryDetailsId = !empty($prevComsnArr[$inquiryId]) && array_key_exists($item['inquiry_details_id'], $prevComsnArr[$inquiryId]) ? $item['inquiry_details_id'] : 0;

                                    $konitaCommission = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : 0;
                                    $companyKonitaComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['company_konita_cmsn'] : 0;
                                    $principalComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['principal_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['principal_cmsn'] : 0;
                                    $salesPersonComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn'] : 0;
                                    $buyerComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn'] : 0;
                                    $rebateComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn'] : 0;

                                    $expenditureCsmn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['expenditure_csmn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['expenditure_csmn'] : 0;
                                    $konitaNetComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_net_csmn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_net_csmn'] : 0;

                                    $commissionArr[$inquiryId][$inqDetailsId]['total_konita_cmsn'] = $konitaCommission * $item['sales_volume'];
                                    $commissionArr[$inquiryId][$inqDetailsId]['admin_cost'] = $principalComsn * $item['sales_volume'];
                                    $commissionArr[$inquiryId][$inqDetailsId]['total_cmsn'] = ($konitaCommission * $item['sales_volume']) + ($principalComsn * $item['sales_volume']);

                                    //individual total commission
                                    $comsnIncomeArr['konita_cmsn'] = !empty($comsnIncomeArr['konita_cmsn']) ? $comsnIncomeArr['konita_cmsn'] : 0;
                                    $comsnIncomeArr['konita_cmsn'] += ($companyKonitaComsn * $item['sales_volume']);
                                    $comsnIncomeArr['principal_cmsn'] = !empty($comsnIncomeArr['principal_cmsn']) ? $comsnIncomeArr['principal_cmsn'] : 0;
                                    $comsnIncomeArr['principal_cmsn'] += ($principalComsn * $item['sales_volume']);
                                    $comsnIncomeArr['sales_person_cmsn'] = !empty($comsnIncomeArr['sales_person_cmsn']) ? $comsnIncomeArr['sales_person_cmsn'] : 0;
                                    $comsnIncomeArr['sales_person_cmsn'] += ($salesPersonComsn * $item['sales_volume']);
                                    $comsnIncomeArr['buyer_cmsn'] = !empty($comsnIncomeArr['buyer_cmsn']) ? $comsnIncomeArr['buyer_cmsn'] : 0;
                                    $comsnIncomeArr['buyer_cmsn'] += ($buyerComsn * $item['sales_volume']);
                                    $comsnIncomeArr['rebate_cmsn'] = !empty($comsnIncomeArr['rebate_cmsn']) ? $comsnIncomeArr['rebate_cmsn'] : 0;
                                    $comsnIncomeArr['rebate_cmsn'] += ($rebateComsn * $item['sales_volume']);
                                    //end individual total commission
                                    $comsnIncomeArr['total_konita_cmsn'] = !empty($comsnIncomeArr['total_konita_cmsn']) ? $comsnIncomeArr['total_konita_cmsn'] : 0;
                                    $comsnIncomeArr['total_konita_cmsn'] += $commissionArr[$inquiryId][$inqDetailsId]['total_konita_cmsn'];
                                    $comsnIncomeArr['total_admin_cost'] = !empty($comsnIncomeArr['total_admin_cost']) ? $comsnIncomeArr['total_admin_cost'] : 0;
                                    $comsnIncomeArr['total_admin_cost'] += $commissionArr[$inquiryId][$inqDetailsId]['admin_cost'];
                                    $comsnIncomeArr['total_cmsn'] = !empty($comsnIncomeArr['total_cmsn']) ? $comsnIncomeArr['total_cmsn'] : 0;
                                    $comsnIncomeArr['total_cmsn'] += $commissionArr[$inquiryId][$inqDetailsId]['total_cmsn'];

                                    $comsnIncomeArr['total_expenditure_csmn'] = !empty($comsnIncomeArr['total_csmn']) ? $comsnIncomeArr['total_expenditure_csmn'] : 0;
                                    $comsnIncomeArr['total_expenditure_csmn'] += ($expenditureCsmn * $item['sales_volume']);
                                    $comsnIncomeArr['total_konita_net_csmn'] = !empty($comsnIncomeArr['total_konita_net_csmn']) ? $comsnIncomeArr['total_konita_net_csmn'] : 0;
                                    $comsnIncomeArr['total_konita_net_csmn'] += ($konitaNetComsn * $item['sales_volume']);

                                    $profitArr[$inquiryId]['net_commission'] = !empty($profitArr[$inquiryId]['net_commission']) ? $profitArr[$inquiryId]['net_commission'] : 0;
                                    $profitArr[$inquiryId]['net_commission'] += ($konitaNetComsn * $item['sales_volume']);

                                    $profitArr[$inquiryId]['expenditure'] = !empty($profitArr[$inquiryId]['expenditure']) ? $profitArr[$inquiryId]['expenditure'] : 0;
                                    $profitArr[$inquiryId]['expenditure'] += ($expenditureCsmn * $item['sales_volume']);
                                    //end commission calculation
                                    if ($target->lc_transmitted_copy_done == '1') {
//                                        $lcTransmitted += !empty($item['total_cmsn']) ? $item['total_cmsn'] : 0;
                                        $lcTransmitted += $commissionArr[$inquiryId][$inqDetailsId]['total_cmsn'];
                                    } else {
//                                        $notLcTransmitted += !empty($item['total_cmsn']) ? $item['total_cmsn'] : 0;
                                        $notLcTransmitted += $commissionArr[$inquiryId][$inqDetailsId]['total_cmsn'];
                                    }
                                    //country wise sales volume && sales Amount
                                    if (!empty($target->country_id)) {
                                        $countryWiseAccount[$target->country_id]['total_sales_volyme'] = !empty($countryWiseAccount[$target->country_id]['total_sales_volyme']) ? $countryWiseAccount[$target->country_id]['total_sales_volyme'] : 0;
                                        $countryWiseAccount[$target->country_id]['total_sales_volyme'] += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                        $countryWiseAccount[$target->country_id]['total_sales_amount'] = !empty($countryWiseAccount[$target->country_id]['total_sales_amount']) ? $countryWiseAccount[$target->country_id]['total_sales_amount'] : 0;
                                        $countryWiseAccount[$target->country_id]['total_sales_amount'] += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }

//                        echo '<pre>';
//            print_r($commissionArr);
//            print_r($prevComsnArr);
//            print_r($comsnIncomeArr);
//            exit;

            $deliveryInfoArr = Delivery::select('inquiry_id', 'bl_no', 'id', 'payment_status'
                            , 'buyer_payment_status', 'shipment_status')->get();


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


            //ENDOF SUMMARY
            //KONITA INFO
            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[49][6])) {
                return redirect('/dashboard');
            }
            return view('report.salesVolume.print.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr'
                                    , 'totalSalesVolume', 'totalSalesAmount', 'totalKonitaCommission'
                                    , 'totalAdminCost', 'totalCommission', 'notLcTransmitted', 'lcTransmitted'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'totalKonitaNetCmsn'
                                    , 'konitaCmsn', 'salesPersonCmsn', 'buyerCmsn', 'rebateCmsn', 'countryWiseAccount'
                                    , 'countryList', 'principalCmsn', 'supplierList', 'deliveryArr'
                                    , 'userAccessArr', 'buyerList', 'profitArr', 'commissionArr', 'comsnIncomeArr'));
        } elseif ($request->view == 'pdf') {
            if (empty($userAccessArr[49][9])) {
                return redirect('/dashboard');
            }
            $pdf = PDF::loadView('report.salesVolume.print.index', compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr'
                                    , 'totalSalesVolume', 'totalSalesAmount', 'totalKonitaCommission'
                                    , 'totalAdminCost', 'totalCommission', 'notLcTransmitted', 'lcTransmitted'
                                    , 'konitaInfo', 'phoneNumber', 'salesPersonArr', 'totalKonitaNetCmsn'
                                    , 'konitaCmsn', 'salesPersonCmsn', 'buyerCmsn', 'rebateCmsn', 'countryWiseAccount'
                                    , 'countryList', 'principalCmsn', 'supplierList', 'deliveryArr'
                                    , 'userAccessArr', 'buyerList', 'profitArr', 'commissionArr', 'comsnIncomeArr'))
                    ->setPaper('a3', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('sales_volume_report.pdf');
//            return $pdf->stream();
        } else {
            return view('report.salesVolume.index')->with(compact('request', 'qpArr', 'targetArr', 'rowspanArr'
                                    , 'productArr', 'brandArr', 'gradeArr', 'buyerList', 'profitArr'
                                    , 'totalSalesVolume', 'totalSalesAmount', 'totalKonitaCommission'
                                    , 'totalAdminCost', 'totalCommission', 'notLcTransmitted', 'lcTransmitted'
                                    , 'salesPersonList', 'totalKonitaNetCmsn', 'konitaCmsn', 'salesPersonCmsn'
                                    , 'buyerCmsn', 'rebateCmsn', 'countryWiseAccount', 'countryList'
                                    , 'principalCmsn', 'productList', 'brandList', 'supplierList', 'deliveryArr', 'commissionArr', 'comsnIncomeArr'
            ));
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
        $url = 'pi_from_date=' . $request->pi_from_date . '&pi_to_date=' . $request->pi_to_date
                . '&salespersons_id=' . $request->salespersons_id . '&buyer_id=' . $request->buyer_id
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&supplier_id=' . $request->supplier_id;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('salesVolumeReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('salesVolumeReport?generate=true&' . $url);
    }

    //get shipment detail 
    public function getShipmentDetails(Request $request) {
        $loadView = 'report.salesVolume.showShipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

    public function getShipmentDetailsPrint(Request $request) {
        $loadView = 'report.salesVolume.print.shipmentDetails';
        return Common::getShipmentFullDetail($request, $loadView);
    }

}
