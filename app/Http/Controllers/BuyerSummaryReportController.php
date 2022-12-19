<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\Brand;
use App\Buyer;
use App\BuyerCategory;
use App\BuyerFollowUpHistory;
use App\Grade;
use App\CompanyInformation;
use App\Country;
use App\Division;
use App\BuyerToProduct;
use App\ProductToGrade;
use App\ProductToBrand;
use App\SalesPersonToBuyer;
use App\User;
use App\Lead;
use App\InquiryDetails;
use App\BuyerMachineType;
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

class BuyerSummaryReportController extends Controller {

    public function index(Request $request) {
        $today = date("Y-m-d");

        $buyerCategoryList = ['0' => __('label.SELECT_BUYER_CATEGORY_OPT')] + BuyerCategory::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $buyerSearchList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $productCategoryList = ['0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')] + ProductCategory::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')];
        $businessStatusList = [
            '0' => __('label.ALL_TYPES')
            , '1' => __('label.ENGAGED_IN_BUSINESS')
            , '2' => __('label.BUSINESS_NOT_INITIATED')
        ];
        $salesStatusList = [
            '0' => __('label.ALL_TYPES')
            , '1' => __('label.ASSIGNED_TO_SALES_PERSON_S')
            , '2' => __('label.NOT_ASSIGNED')
        ];
        $machineTypeList = ['0' => __('label.SELECT_MACHINE_TYPE_OPT')];

        $countryList = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::pluck('name', 'id')->toArray();
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')];

        $buyerInfoArr = $buyerListArr = $buyerList = [];
        $buyerList1 = $buyerList2 = $buyerList3 = $buyerList4 = $buyerList5 = $buyerList6 = [];
        $buyerList7 = $buyerList8 = $buyerList9 = $buyerList10 = $buyerList11 = $buyerList12 = $buyerList13 = [];
        $productArr = [];

        $allBuyerList = Buyer::pluck('id', 'id')->toArray();
        if ($request->generate == 'true') {
            if (!empty($request->buyer_category_id)) {
                $buyerList1 = Buyer::where('buyer_category_id', $request->buyer_category_id)
                                ->pluck('id', 'id')->toArray();
                array_push($buyerListArr, $buyerList1);
            }
            if (!empty($request->product_category_id)) {
                $buyerList2 = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                                ->where('product.product_category_id', $request->product_category_id)
                                ->pluck('buyer_to_product.buyer_id', 'buyer_to_product.buyer_id')->toArray();
                array_push($buyerListArr, $buyerList2);

                $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                                ->where('product_category_id', $request->product_category_id)
                                ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            }

            if (!empty($request->product_id)) {
                if (!empty($request->brand_id)) {
                    $buyerList3 = BuyerToProduct::where('product_id', $request->product_id)
                                    ->where('brand_id', $request->brand_id)
                                    ->pluck('buyer_id', 'buyer_id')->toArray();
                    if (!empty($request->machine_type_id)) {
                        $buyerList13 = BuyerMachineType::where('product_id', $request->product_id)
                                ->where('brand_id', $request->brand_id);

                        if ($request->machine_type_id == '1') {
                            $buyerList13 = $buyerList13->where('machine_type_id', 1)->pluck('buyer_id', 'buyer_id')->toArray();
                        } else if ($request->machine_type_id == '2') {
                            $buyerList13 = $buyerList13->where('machine_type_id', 2)->pluck('buyer_id', 'buyer_id')->toArray();
                        } else if ($request->machine_type_id == '3') {
                            $buyerList13 = $buyerList13->where('machine_type_id', 3)->pluck('buyer_id', 'buyer_id')->toArray();
                        } else if ($request->machine_type_id == '4') {
                            $buyerList13 = $buyerList13->whereIn('machine_type_id', [1, 2, 3])->pluck('buyer_id', 'buyer_id')->toArray();
                        }
                        array_push($buyerListArr, $buyerList13);
                    } else {
                        array_push($buyerListArr, $buyerList3);
                    }

                    $machineTypeList = [
                        '0' => __('label.SELECT_MACHINE_TYPE_OPT')
                        , '1' => __('label.MANUAL')
                        , '2' => __('label.AUTOMATIC')
                        , '3' => __('label.BOTH_MANUAL_N_AUTOMATIC')
                        , '4' => __('label.ANY_TYPE')
                    ];
                } else {
                    $buyerList4 = BuyerToProduct::where('product_id', $request->product_id)
                                    ->pluck('buyer_id', 'buyer_id')->toArray();
                    array_push($buyerListArr, $buyerList4);
                }

                $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id')
                                ->where('product_to_brand.product_id', $request->product_id)->where('brand.status', '1')
                                ->orderBy('brand.name', 'asc')->pluck('brand.name', 'brand.id')->toArray();
            }

            if (!empty($request->business_status_id)) {

                $buyerList6 = Buyer::join('inquiry', 'inquiry.buyer_id', 'buyer.id')
                        ->join('inquiry_details', 'inquiry_details.inquiry_id', 'inquiry.id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4']);

                if (!empty($request->product_id)) {
                    if (!empty($request->brand_id)) {
                        $buyerList6 = $buyerList6->where('product_id', $request->product_id)
                                ->where('brand_id', $request->brand_id);
                    } else {
                        $buyerList6 = $buyerList6->where('product_id', $request->product_id);
                    }
                }
                $buyerList6 = $buyerList6->pluck('buyer.id', 'buyer.id')->toArray();
                $buyerList7 = array_diff($allBuyerList, $buyerList6);

                if ($request->business_status_id == '1') {
                    array_push($buyerListArr, $buyerList6);
                } else if ($request->business_status_id == '2') {
                    array_push($buyerListArr, $buyerList7);
                }
            } else {
                array_push($buyerListArr, $allBuyerList);
            }

            if (!empty($request->sales_status_id)) {

                $buyerList9 = SalesPersonToBuyer::pluck('buyer_id', 'buyer_id')->toArray();
                $buyerList10 = array_diff($allBuyerList, $buyerList9);

                if ($request->sales_status_id == '1') {
                    array_push($buyerListArr, $buyerList9);
                } else if ($request->sales_status_id == '2') {
                    array_push($buyerListArr, $buyerList10);
                }
            } else {
                array_push($buyerListArr, $allBuyerList);
            }


            if (!empty($request->country_id)) {
                $buyerList11 = Buyer::where('country_id', $request->country_id)
                                ->pluck('id', 'id')->toArray();
                array_push($buyerListArr, $buyerList11);

                $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $request->country_id)
                                ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            }

            if (!empty($request->division_id)) {
                $buyerList12 = Buyer::where('division_id', $request->division_id)
                                ->pluck('id', 'id')->toArray();
                array_push($buyerListArr, $buyerList12);
            }

            if (!empty($buyerListArr)) {
                //if more than 1 supplier set
                if (count($buyerListArr) > 1) {
                    foreach ($buyerListArr as $key => $value) {
                        //for 1st supplier set
                        if ($key == 0) {
                            //find common suppliers
                            $buyerList = array_intersect($buyerListArr[$key], $buyerListArr[$key + 1]);
                        } else if (count($buyerListArr) >= 2) {
                            //if 2 or more than 2 supplier set
                            $buyerList = array_intersect($buyerList, $buyerListArr[$key]);
                        }
                    }
                } else {
                    //if 1 supplier set
                    $buyerList = $buyerListArr[0];
                }
            }

            $buyerSearchList = ['0' => __('label.SELECT_BUYER_OPT')];
            if (!empty($buyerList)) {
                $buyerSearchList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::whereIn('id', $buyerList)
                                ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            }
            if (!empty($request->buyer_id)) {
                $buyerList = Buyer::where('id', $request->buyer_id)->pluck('id', 'id')->toArray();
            }
            if (!empty($buyerList)) {
                $buyerInfoArr = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                                ->leftJoin('division', 'division.id', '=', 'buyer.division_id')
                                ->whereIn('buyer.id', $buyerList)
                                ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                                        , 'division.name as division_name', 'buyer.id', 'buyer.code', 'buyer.logo'
                                        , 'buyer.status')
                                ->orderBy('buyer.name', 'asc')
                                ->get()->toArray();
            }


//            echo '<pre>';
//            print_r($buyerList);
//            exit;
        } else {
            $buyerInfoArr = Buyer::leftJoin('country', 'country.id', '=', 'buyer.country_id')
                            ->leftJoin('division', 'division.id', '=', 'buyer.division_id')
                            ->select('buyer.name', 'buyer.contact_person_data', 'country.name as country_name'
                                    , 'division.name as division_name', 'buyer.id', 'buyer.code', 'buyer.logo'
                                    , 'buyer.status')
                            ->orderBy('buyer.name', 'asc')
                            ->get()->toArray();
        }

//        echo '<pre>';
//        print_r(count($allBuyerList));
//        print_r(count($buyerList));
//        echo '<pre>';
//        print_r(count($buyerInfoArr));
//        exit;

        $contactArr = $buyerIdArr = [];
        if (!empty($buyerInfoArr)) {
            foreach ($buyerInfoArr as $buyer) {
                $contact = json_decode($buyer['contact_person_data'], true);
                $contactArr[$buyer['id']] = array_shift($contact);
                $buyerIdArr[$buyer['id']] = $buyer['id'];
            }
        }

        //get followup history
        if (!empty($buyerIdArr)) {
            $followUpPrevHistory = BuyerFollowUpHistory::whereIn('buyer_id', $buyerIdArr)
                            ->pluck('history', 'buyer_id')->toArray();
        }


        $finalArr = $followUpHistoryArr = [];
        if (!empty($followUpPrevHistory)) {
            foreach ($followUpPrevHistory as $buyerId => $history) {
                $followUpHistoryArr[$buyerId] = json_decode($history, true);
                krsort($followUpHistoryArr[$buyerId]);
                $i = 0;

                if (!empty($followUpHistoryArr[$buyerId])) {
                    foreach ($followUpHistoryArr[$buyerId] as $followUpHistory) {
                        $finalArr[$buyerId][$followUpHistory['updated_at']][$i]['status'] = $followUpHistory['status'];
                        $i++;
                    }
                }
            }
            krsort($finalArr[$buyerId]);
        }

        $latestFollowupArr = [];
        if (!empty($finalArr)) {
            foreach ($finalArr as $buyerId => $followUpHistory) {
                $latestFollowup = reset($followUpHistory);
                $latestFollowupArr[$buyerId]['status'] = $latestFollowup[0]['status'];
            }
        }

//        echo '<pre>';
//        print_r($latestFollowupArr);
//        exit;


        $salesPersonToBuyerCountList = SalesPersonToBuyer::select(DB::raw("COUNT(id) as no_of_sales_person"), 'buyer_id')
                        ->groupBy('buyer_id')->pluck('no_of_sales_person', 'buyer_id')->toArray();

        $inBusinessEnabled = 0;
        $inBusinessArr = $engageTimeArr = [];

        if ($request->generate == 'true') {
            if (!empty($request->product_id)) {
                if (!empty($request->brand_id)) {
                    if ($request->business_status_id == '2') {
                        $inBusinessEnabled = 1;
                        $inBusinessInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                                ->where('inquiry_details.brand_id', '<>', $request->brand_id)
                                ->select('inquiry.buyer_id', 'inquiry_details.brand_id')
                                ->get();

                        if (!$inBusinessInfoArr->isEmpty()) {
                            foreach ($inBusinessInfoArr as $inB) {
                                $inBusinessArr[$inB->buyer_id][$inB->brand_id] = $inB->brand_id;
                            }
                        }
                    }
                }
            }

            if (in_array($request->business_status_id, ['0', '1'])) {
                $buyerFirstPIList = Lead::select(DB::raw("MIN(pi_date) as first_pi"), 'buyer_id')->groupBy('buyer_id')->whereIn('order_status', ['2', '3', '4'])->pluck('first_pi', 'buyer_id')->toArray();
                if (!empty($buyerFirstPIList)) {
                    foreach ($buyerFirstPIList as $buyerId => $firstPI) {
                        $engageTime = Helper::dateDiff($firstPI, $today);
                        $engageTimeArr[$buyerId] = $engageTime;
                    }
                }
            }
        }

//        echo '<pre>';
//        print_r($inBusinessInfoArr->toArray());
//        print_r($inBusinessArr);
//        exit;
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
            if (empty($userAccessArr[58][6])) {
                return redirect('/dashboard');
            }
            return view('report.buyerSummary.print.index')->with(compact('request', 'buyerCategoryList'
                                    , 'productCategoryList', 'productList', 'brandList'
                                    , 'buyerInfoArr', 'contactArr', 'konitaInfo', 'phoneNumber'
                                    , 'businessStatusList', 'salesPersonToBuyerCountList'
                                    , 'inBusinessEnabled', 'inBusinessArr', 'salesStatusList'
                                    , 'divisionList', 'countryList', 'latestFollowupArr'
                                    , 'machineTypeList', 'buyerSearchList', 'engageTimeArr'));
        } else {
            return view('report.buyerSummary.index')->with(compact('request', 'buyerCategoryList'
                                    , 'productCategoryList', 'productList', 'brandList'
                                    , 'buyerInfoArr', 'contactArr', 'businessStatusList'
                                    , 'salesPersonToBuyerCountList', 'inBusinessEnabled'
                                    , 'inBusinessArr', 'salesStatusList'
                                    , 'divisionList', 'countryList', 'latestFollowupArr'
                                    , 'machineTypeList', 'buyerSearchList', 'engageTimeArr'));
        }
    }

    public function filter(Request $request) {
        $url = 'buyer_category_id=' . $request->buyer_category_id . '&product_category_id=' . $request->product_category_id
                . '&product_id=' . $request->product_id . '&brand_id=' . $request->brand_id
                . '&machine_type_id=' . $request->machine_type_id
                . '&business_status_id=' . $request->business_status_id
                . '&sales_status_id=' . $request->sales_status_id
                . '&country_id=' . $request->country_id . '&division_id=' . $request->division_id
                . '&buyer_id=' . $request->buyer_id;

        return Redirect::to('buyerSummaryReport?generate=true&' . $url);
    }

    public function getDivision(Request $request) {
        //country wise division
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $request->country_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('report.buyerSummary.showDivision', compact('divisionList'))->render();

        $buyerSearchList = self::getBuyerList($request);
        $buyerSearch = view('report.buyerSummary.showBuyer', compact('buyerSearchList'))->render();
        return response()->json(['html' => $view, 'buyerSearch' => $buyerSearch]);
    }

    public function getRelatedSalesPersonList(Request $request) {
        $loadView = 'report.buyerSummary.showRelatedSalesPersonList';
        return Common::getRelatedSalesPersonList($request, $loadView);
    }

    public function getInBusinessBrandList(Request $request) {
        $buyerInfo = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $inBusinessInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry_details.brand_id', '<>', $request->brand_id)
                ->where('inquiry.buyer_id', $request->buyer_id)
                ->pluck('inquiry_details.brand_id', 'inquiry_details.brand_id')
                ->toArray();

        $inBusinessBrandArr = [];
        if (!empty($inBusinessInfoArr)) {
            $inBusinessBrandArr = Brand::join('country', 'country.id', 'brand.origin')
                            ->whereIn('brand.id', $inBusinessInfoArr)
                            ->select('brand.logo', 'brand.name', 'country.name as country', 'brand.description')
                            ->orderBy('brand.name', 'asc')->get()->toArray();
        }


        $view = view('report.buyerSummary.showInBusinessBrandList', compact('request', 'buyerInfo'
                        , 'inBusinessBrandArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getProductList(Request $request) {
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                        ->where('product_category_id', $request->product_category_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('report.buyerSummary.showProductList', compact('productList'))->render();

        $buyerSearchList = self::getBuyerList($request);
        $buyerSearch = view('report.buyerSummary.showBuyer', compact('buyerSearchList'))->render();
        return response()->json(['html' => $view, 'buyerSearch' => $buyerSearch]);
    }

    public function getBrandList(Request $request) {
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + ProductToBrand::join('brand', 'brand.id', 'product_to_brand.brand_id')
                        ->where('product_to_brand.product_id', $request->product_id)->where('brand.status', '1')
                        ->orderBy('brand.name', 'asc')->pluck('brand.name', 'brand.id')->toArray();

        $view = view('report.buyerSummary.showBrandList', compact('brandList'))->render();

        $buyerSearchList = self::getBuyerList($request);
        $buyerSearch = view('report.buyerSummary.showBuyer', compact('buyerSearchList'))->render();
        return response()->json(['html' => $view, 'buyerSearch' => $buyerSearch]);
    }

    public function getMachineTypeList(Request $request) {
        $machineTypeList = [
            '0' => __('label.SELECT_MACHINE_TYPE_OPT')
            , '1' => __('label.MANUAL')
            , '2' => __('label.AUTOMATIC')
            , '3' => __('label.BOTH_MANUAL_N_AUTOMATIC')
            , '4' => __('label.ANY_TYPE')
        ];

        $view = view('report.buyerSummary.showMachineTypeList', compact('machineTypeList'))->render();

        $buyerSearchList = self::getBuyerList($request);
        $buyerSearch = view('report.buyerSummary.showBuyer', compact('buyerSearchList'))->render();
        return response()->json(['html' => $view, 'buyerSearch' => $buyerSearch]);
    }

    public function getBuyerSearchList(Request $request) {
        $buyerSearchList = self::getBuyerList($request);

//            echo '<pre>';
//            print_r(count($buyerSearchList));
//            echo '<pre>';
//            print_r($buyerSearchList);
//            exit;

        $view = view('report.buyerSummary.showBuyer', compact('buyerSearchList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getBuyerList(Request $request) {
        $buyerListArr = $buyerList = [];
        $buyerList1 = $buyerList2 = $buyerList3 = $buyerList4 = $buyerList5 = $buyerList6 = [];
        $buyerList7 = $buyerList8 = $buyerList9 = $buyerList10 = $buyerList11 = $buyerList12 = $buyerList13 = [];
        $productArr = [];

        $buyerSearchList = ['0' => __('label.SELECT_BUYER_OPT')];

        $allBuyerList = Buyer::pluck('id', 'id')->toArray();
        if (!empty($request->buyer_category_id)) {
            $buyerList1 = Buyer::where('buyer_category_id', $request->buyer_category_id)
                            ->pluck('id', 'id')->toArray();
            array_push($buyerListArr, $buyerList1);
        }
        if (!empty($request->product_category_id)) {
            $buyerList2 = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                            ->where('product.product_category_id', $request->product_category_id)
                            ->pluck('buyer_to_product.buyer_id', 'buyer_to_product.buyer_id')->toArray();
            array_push($buyerListArr, $buyerList2);
        }

        if (!empty($request->product_id)) {
            if (!empty($request->brand_id)) {
                $buyerList3 = BuyerToProduct::where('product_id', $request->product_id)
                                ->where('brand_id', $request->brand_id)
                                ->pluck('buyer_id', 'buyer_id')->toArray();
                if (!empty($request->machine_type_id)) {
                    $buyerList13 = BuyerMachineType::where('product_id', $request->product_id)
                            ->where('brand_id', $request->brand_id);

                    if ($request->machine_type_id == '1') {
                        $buyerList13 = $buyerList13->where('machine_type_id', 1)->pluck('buyer_id', 'buyer_id')->toArray();
                    } else if ($request->machine_type_id == '2') {
                        $buyerList13 = $buyerList13->where('machine_type_id', 2)->pluck('buyer_id', 'buyer_id')->toArray();
                    } else if ($request->machine_type_id == '3') {
                        $buyerList13 = $buyerList13->where('machine_type_id', 3)->pluck('buyer_id', 'buyer_id')->toArray();
                    } else if ($request->machine_type_id == '4') {
                        $buyerList13 = $buyerList13->whereIn('machine_type_id', [1, 2, 3])->pluck('buyer_id', 'buyer_id')->toArray();
                    }
                    array_push($buyerListArr, $buyerList13);
                } else {
                    array_push($buyerListArr, $buyerList3);
                }
            } else {
                $buyerList4 = BuyerToProduct::where('product_id', $request->product_id)
                                ->pluck('buyer_id', 'buyer_id')->toArray();
                array_push($buyerListArr, $buyerList4);
            }
        }

        if (!empty($request->business_status_id)) {

            $buyerList6 = Buyer::join('inquiry', 'inquiry.buyer_id', 'buyer.id')
                    ->join('inquiry_details', 'inquiry_details.inquiry_id', 'inquiry.id')
                    ->whereIn('inquiry.order_status', ['2', '3', '4']);

            if (!empty($request->product_id)) {
                if (!empty($request->brand_id)) {
                    $buyerList6 = $buyerList6->where('product_id', $request->product_id)
                            ->where('brand_id', $request->brand_id);
                } else {
                    $buyerList6 = $buyerList6->where('product_id', $request->product_id);
                }
            }
            $buyerList6 = $buyerList6->pluck('buyer.id', 'buyer.id')->toArray();
            $buyerList7 = array_diff($allBuyerList, $buyerList6);

            if ($request->business_status_id == '1') {
                array_push($buyerListArr, $buyerList6);
            } else if ($request->business_status_id == '2') {
                array_push($buyerListArr, $buyerList7);
            }
        } else {
            array_push($buyerListArr, $allBuyerList);
        }

        if (!empty($request->sales_status_id)) {

            $buyerList9 = SalesPersonToBuyer::pluck('buyer_id', 'buyer_id')->toArray();
            $buyerList10 = array_diff($allBuyerList, $buyerList9);

            if ($request->sales_status_id == '1') {
                array_push($buyerListArr, $buyerList9);
            } else if ($request->sales_status_id == '2') {
                array_push($buyerListArr, $buyerList10);
            }
        } else {
            array_push($buyerListArr, $allBuyerList);
        }


        if (!empty($request->country_id)) {
            $buyerList11 = Buyer::where('country_id', $request->country_id)
                            ->pluck('id', 'id')->toArray();
            array_push($buyerListArr, $buyerList11);
        }

        if (!empty($request->division_id)) {
            $buyerList12 = Buyer::where('division_id', $request->division_id)
                            ->pluck('id', 'id')->toArray();
            array_push($buyerListArr, $buyerList12);
        }

        if (!empty($buyerListArr)) {
            //if more than 1 supplier set
            if (count($buyerListArr) > 1) {
                foreach ($buyerListArr as $key => $value) {
                    //for 1st supplier set
                    if ($key == 0) {
                        //find common suppliers
                        $buyerList = array_intersect($buyerListArr[$key], $buyerListArr[$key + 1]);
                    } else if (count($buyerListArr) >= 2) {
                        //if 2 or more than 2 supplier set
                        $buyerList = array_intersect($buyerList, $buyerListArr[$key]);
                    }
                }
            } else {
                //if 1 supplier set
                $buyerList = $buyerListArr[0];
            }
        }

        if (!empty($buyerList)) {
            $buyerSearchList = $buyerSearchList + Buyer::whereIn('id', $buyerList)
                            ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        }
        return $buyerSearchList;
    }

    //****************************** start :: buyer profile ********************************//
    public function profile(Request $request, $id) {
        $loadView = 'report.buyerSummary.profile.show';
        return Common::buyerProfile($request, $id, $loadView);
    }

    public function printProfile(Request $request, $id) {
        $loadView = 'report.buyerSummary.profile.print.show';
        $modueId = 58;
        return Common::buyerPrintProfile($request, $id, $loadView, $modueId);
    }

    public static function getInvolvedOrderList(Request $request) {
        $loadView = 'report.buyerSummary.profile.showInvolvedOrderList';
        return Common::getInvolvedOrderList($request, $loadView);
    }

    public static function printInvolvedOrderList(Request $request) {
        $loadView = 'report.buyerSummary.profile.print.showInvolvedOrderList';
        $modueId = 58;
        return Common::printInvolvedOrderList($request, $loadView, $modueId);
    }

    //****************************** end :: buyer profile *********************************//
}
