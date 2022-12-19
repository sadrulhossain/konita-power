<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\MeasureUnit;
use App\Brand;
use App\ProductToBrand;
use App\User;
use App\Grade;
use App\ProductToGrade;
use App\ProductPricingHistory;
use App\ProductPricing;
use App\ProductTechDataSheet;
use App\Country;
use App\SalesTarget;
use App\Buyer;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller {

    private $fileSize = '102400';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $nameArr = Product::select('name')->orderBy('product_code', 'asc')->get();
        $productCodeArr = Product::select('product_code')->get();
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $measureUnitArr = array('0' => __('label.SELECT_MEASUREMENT_UNIT_OPT')) + MeasureUnit::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();


        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->select('product.*', 'product_category.name as product_category'
                , 'measure_unit.name as measure_unit');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {

                $query->where('product.name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->product_category)) {
            $targetArr = $targetArr->where('product.product_category_id', $request->product_category);
        }

        if (!empty($request->product_code)) {
            $targetArr = $targetArr->where('product.product_code', $request->product_code);
        }

        if (!empty($request->measure_unit)) {
            $targetArr = $targetArr->where('product.measure_unit_id', $request->measure_unit);
        }
        //end filtering

        $productIdArr = $targetArr->pluck('product.id', 'product.id')->toArray();

        $targetArr = $targetArr->orderBy('product.id', 'desc')->paginate(Session::get('paginatorCount'));

        $hsCodeArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $target) {
                $hsCodeArr[$target->id] = json_decode($target->hs_code, true);
            }
        }

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/product?page=' . $page);
        }

        $relatedBrandArr = $pricedBrandArr = [];
        if (!empty($productIdArr)) {
            $productToBrandArr = ProductToBrand::select('product_id', 'brand_id')
                    ->whereIn('product_id', $productIdArr)
                    ->get();

            if (!$productToBrandArr->isEmpty()) {
                foreach ($productToBrandArr as $productToBrand) {
                    $relatedBrandArr[$productToBrand->product_id][$productToBrand->brand_id] = $productToBrand->brand_id;
                }
            }

            $productPricingArr = ProductPricing::select('product_id', 'brand_id')
                    ->whereIn('product_id', $productIdArr)
                    ->get();

            if (!$productPricingArr->isEmpty()) {
                foreach ($productPricingArr as $productPricing) {
                    $pricedBrandArr[$productPricing->product_id][$productPricing->brand_id] = $productPricing->brand_id;
                }
            }
        }

        return view('product.index')->with(compact('qpArr', 'targetArr', 'measureUnitArr'
                                , 'productCategoryArr', 'nameArr', 'productCodeArr', 'hsCodeArr'
                                , 'relatedBrandArr', 'pricedBrandArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::where('status', 1)->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $measureUnitArr = array('0' => __('label.SELECT_MEASUREMENT_UNIT_OPT')) + MeasureUnit::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        return view('product.create')->with(compact('qpArr', 'productCategoryArr'
                                , 'measureUnitArr'));
    }

    //store
    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $hsCodeArr = $request->hs_code;

        $message = [];
        $rules = [
            'name' => 'required|unique:product',
            'product_code' => 'required|unique:product',
            'product_category_id' => 'required|not_in:0',
            'measure_unit_id' => 'required|not_in:0',
        ];

        //end validation
//        
        if (!empty($hsCodeArr)) {
            $row = 0;
            foreach ($hsCodeArr as $key => $hsCode) {
                $rules['hs_code.' . $key] = 'required';
                $message['hs_code.' . $key . '.required'] = __('label.HS_CODE_IS_REQUIRED_FOR_THIS_ROW', ['row' => ($row + 1)]);
                $row++;
            }
        }


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $hsCodeInfo = [];
        if (!empty($hsCodeArr)) {
            foreach ($hsCodeArr as $k => $hsCode) {
                $hsCodeInfo[$k] = $hsCode;
            }
        }

        $target = new Product;
        $target->name = $request->name;
        $target->product_code = $request->product_code;
        $target->hs_code = !empty($hsCodeInfo) ? json_encode($hsCodeInfo) : '';
        $target->product_category_id = $request->product_category_id;
        $target->measure_unit_id = $request->measure_unit_id;
        $target->competitors_product = !empty($request->competitors_product) ? $request->competitors_product : '0';
        $target->status = $request->status;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_CREATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_COULD_NOT_BE_CREATED')), 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Product::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('product');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $previousHsCodeArr = json_decode($target->hs_code, true);
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::where('status', 1)->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $measureUnitArr = array('0' => __('label.SELECT_MEASUREMENT_UNIT_OPT')) + MeasureUnit::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        return view('product.edit')->with(compact('qpArr', 'target', 'productCategoryArr'
                                , 'measureUnitArr', 'previousHsCodeArr'));
    }

    //update
    public function update(Request $request) {
        $id = $request->id;
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $hsCodeArr = $request->hs_code;


        $message = [];
        $rules = [
            'name' => 'required|unique:product,name,' . $id,
            'product_code' => 'required|unique:product,product_code,' . $id,
            'product_category_id' => 'required|not_in:0',
            'measure_unit_id' => 'required|not_in:0',
        ];

        //Validation Rules for FSC Certification

        if (!empty($hsCodeArr)) {
            $row = 0;
            foreach ($hsCodeArr as $key => $hsCode) {
                $rules['hs_code.' . $key] = 'required';
                $message['hs_code.' . $key . '.required'] = __('label.HS_CODE_IS_REQUIRED_FOR_THIS_ROW', ['row' => ($row + 1)]);
                $row++;
            }
        }


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $hsCodeInfo = [];
        if (!empty($hsCodeArr)) {
            foreach ($hsCodeArr as $k => $hsCode) {
                $hsCodeInfo[$k] = $hsCode;
            }
        }

        $target->name = $request->name;
        $target->product_code = $request->product_code;
        $target->hs_code = !empty($hsCodeInfo) ? json_encode($hsCodeInfo) : '';
        $target->product_category_id = $request->product_category_id;
        $target->measure_unit_id = $request->measure_unit_id;
        $target->competitors_product = !empty($request->competitors_product) ? $request->competitors_product : '0';
        $target->status = $request->status;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency check
        $dependencyArr = [
            'ProductToBrand' => ['1' => 'product_id'],
            'ProductToGrade' => ['1' => 'product_id'],
            'SalesPersonToProduct' => ['1' => 'product_id'],
            'ProductPricing' => ['1' => 'product_id'],
            'ProductPricingHistory' => ['1' => 'product_id'],
            'ProductTechDataSheet' => ['1' => 'product_id'],
            'BuyerToProduct' => ['1' => 'product_id'],
            'SupplierToProduct' => ['1' => 'product_id'],
            'BuyerToGsmVolume' => ['1' => 'product_id'],
            'InquiryDetails' => ['1' => 'product_id'],
            'RwBreakdown' => ['1' => 'product_id'],
        ];


        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('product' . $pageNumber);
                }
            }
        }

        //dependency on buyers' compitetor's product
        $buyersCompetitorsProductArr = Buyer::select('related_competitors_product')
                        ->whereNotNull('related_competitors_product')->get();

        if (!$buyersCompetitorsProductArr->isEmpty()) {
            foreach ($buyersCompetitorsProductArr as $competitorsProduct) {
                $competitorsProductArr = json_decode($competitorsProduct->related_competitors_product, true);
                if (!empty($competitorsProductArr)) {
                    if (in_array($id, $competitorsProductArr)) {
                        Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Buyer']));
                        return redirect('product' . $pageNumber);
                    }
                }
            }
        }

        //dependency on sales  target
        $salesTargetRecordArr = SalesTarget::select('target')->get();
        if (!$salesTargetRecordArr->isEmpty()) {
            foreach ($salesTargetRecordArr as $salesTargetRecord) {
                $salesTargetArr = json_decode($salesTargetRecord->target, true);
                if (!empty($salesTargetArr)) {
                    if (array_key_exists($id, $salesTargetArr)) {
                        Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'SalesTarget']));
                        return redirect('product' . $pageNumber);
                    }
                }
            }
        }

        //end :: dependency check

        if ($target->delete()) {
            Session::flash('error', __('label.PRODUCT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_DELETED'));
        }
        return redirect('product' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&product_category=' . $request->product_category
                . '&product_code=' . $request->product_code . '&measure_unit=' . $request->measure_unit;
        return Redirect::to('product?' . $url);
    }

    public function loadProductNameCreate(Request $request) {
        return Common::loadProductName($request);
    }

    public function loadProductNameEdit(Request $request) {
        return Common::loadProductName($request);
    }

    public function getProductPricing(Request $request) {
        $loadView = 'product.showSetProductPricing';
        $loadFooterView = 'admin.setProductPricing.showFooter';
        return Common::getProductPricingSetup($request, $loadView, $loadFooterView);
    }

    public function setProductPricing(Request $request) {
        return Common::setProductPricing($request);
    }

    public function getProductQuality(Request $request) {
        //product name list
        $productInfo = Product::where('product.id', $request->product_id)
                        ->select('product.id', 'product.name')
                        ->orderBy('product.name', 'asc')->first();

        //product brand list
        $brandArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand.id', 'brand.name', 'brand.logo')
                        ->where('product_to_brand.product_id', $request->product_id)->get();


        //previous technical data sheet
        $previousTechDataSheetArr = ProductTechDataSheet::select('product_id', 'brand_id', 'data_sheet')->where('product_id', $request->product_id)->get();
        $previousDataSheetArr = [];
        if (!$previousTechDataSheetArr->isEmpty()) {
            foreach ($previousTechDataSheetArr as $previousTechDataSheet) {
                $previousDataSheetArr[$previousTechDataSheet->brand_id] = json_decode($previousTechDataSheet->data_sheet, true);
            }
        }

        $view = view('product.showSetProductQuality', compact('request', 'productInfo', 'brandArr'
                        , 'previousDataSheetArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function newDataSheetRow(Request $request) {
        $brandId = $request->brand_id;
        $view = view('product.newDataSheetRow', compact('brandId'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductQuality(Request $request) {
        $dataSheetArr = $request->file('data_sheet_file');
        $titleArr = $request->title;
        $brandName = $request->brand_name;
        $prevDataSheetArr = $request->prev_data_sheet;

        //validation
        $rules = $message = array();

        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                if (!empty($titleArr[$brandId])) {
                    $row = 0;
                    foreach ($titleArr[$brandId] as $key => $title) {
                        $rules['title.' . $brandId . '.' . $key] = 'required';
                        $message['title.' . $brandId . '.' . $key . '.required'] = __('label.TITLE_IS_REQUIRED_FOR_ROW_NO_OF_BRAND', ['row' => ($row + 1), 'brand' => $brandName[$brandId]]);

                        if ($request->hasFile('data_sheet_file.' . $brandId . '.' . $key)) {
                            $rules['data_sheet_file.' . $brandId . '.' . $key] = 'max:' . $this->fileSize . '|mimes:pdf';
                            $message['data_sheet_file.' . $brandId . '.' . $key . '.mimes'] = __('label.INVALID_FILE_FORMAT_FOR_ROW_NO_OF_BRAND', ['row' => ($row + 1), 'brand' => $brandName[$brandId]]);
                        }
                        $row++;
                    }
                }
            }
        } else {
            $rules['brand'] = 'required';
            $message['brand.required'] = __('label.PLEASE_ADD_TECHNICAL_DATASHEET_TO_ATLEAST_ONE_BRAND');
        }



        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation
        //get previous datasheet history
        $prevDataSheetHistoryArr = ProductTechDataSheet::select('brand_id', 'data_sheet')->where('product_id', $request->product_id)->get();

        //create array of brand wise data sheet history 
        $prevTechDataSheet = [];
        if (!$prevDataSheetHistoryArr->isEmpty()) {
            foreach ($prevDataSheetHistoryArr as $prevDataSheetHistory) {
                $dataSheetHistory = json_decode($prevDataSheetHistory->data_sheet, true);
                $prevTechDataSheet[$prevDataSheetHistory->brand_id] = $dataSheetHistory;
            }
        }

        //remove all files of the unchecked brand 
        if (!empty($prevTechDataSheet)) {
            foreach ($prevTechDataSheet as $brandId => $dataSheetHistory) {
                if (!empty($request->brand) && !array_key_exists($brandId, $request->brand)) {
                    foreach ($dataSheetHistory as $key => $history) {
                        if (!empty($history['file'])) {
                            $prevfileName = 'public/uploads/techDataSheet/' . $history['file'];
                            if (File::exists($prevfileName)) {
                                File::delete($prevfileName);
                            }
                        }
                    }
                }
            }
        }

        //find data input from checked brands only
        $i = 0;
        $data = $dataSheetData = $productTechDataSheet = [];
        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                //if title is given
                if (!empty($titleArr[$brandId])) {
                    foreach ($titleArr[$brandId] as $key => $title) {
                        //if new file uploaded
                        if ($request->hasFile('data_sheet_file.' . $brandId . '.' . $key)) {
                            $fileName = $request->file('data_sheet_file.' . $brandId . '.' . $key);
                            if (!empty($fileName)) {
                                //reomve previous file from directory
                                //when new file uploaded
                                if (!empty($prevDataSheetArr[$brandId][$key])) {
                                    $prevfileName = 'public/uploads/techDataSheet/' . $prevDataSheetArr[$brandId][$key];
                                    if (File::exists($prevfileName)) {
                                        File::delete($prevfileName);
                                    }
                                }

                                //new file upload
                                $fileNames = Auth::user()->id . uniqid() . "." . $fileName->getClientOriginalExtension();
                                $fileOriginalNames = $fileName->getClientOriginalName();
                                $uploadSuccess = $fileName->move('public/uploads/techDataSheet', $fileNames);
                                $dataSheetData[$brandId][$key]['file'] = $fileNames;
                            }
                        }

                        //arranging brand wise datasheet info fpr json encode
                        $data[$brandId][$key]['title'] = $title;
                        $data[$brandId][$key]['file'] = !empty($dataSheetData[$brandId][$key]['file']) ? $dataSheetData[$brandId][$key]['file'] : (!empty($prevDataSheetArr[$brandId][$key]) ? $prevDataSheetArr[$brandId][$key] : '');
                    }
                }

                //remove file from directory when data sheet block removed
                if (!empty($prevTechDataSheet[$brandId])) {
                    foreach ($prevTechDataSheet[$brandId] as $dataSheetKey => $dataSheet) {
                        if (!isset($prevDataSheetArr[$brandId])) {
                            if (!empty($dataSheet['file'])) {
                                $prevfileName = 'public/uploads/techDataSheet/' . $dataSheet['file'];
                                if (File::exists($prevfileName)) {
                                    File::delete($prevfileName);
                                }
                            }
                        } else if (!array_key_exists($dataSheetKey, $prevDataSheetArr[$brandId])) {

                            if (!empty($dataSheet['file'])) {
                                $prevfileName = 'public/uploads/techDataSheet/' . $dataSheet['file'];
                                if (File::exists($prevfileName)) {
                                    File::delete($prevfileName);
                                }
                            }
                        }
                    }
                }

                //insert data of product quality
                $productTechDataSheet[$i]['product_id'] = $request->product_id;
                $productTechDataSheet[$i]['brand_id'] = $brandId;
                $productTechDataSheet[$i]['data_sheet'] = json_encode($data[$brandId]);
                $productTechDataSheet[$i]['created_at'] = date('Y-m-d H:i:s');
                $productTechDataSheet[$i]['created_by'] = Auth::user()->id;
                $i++;
            }
        }

        ProductTechDataSheet::where('product_id', $request->product_id)->delete();
        if (ProductTechDataSheet::insert($productTechDataSheet)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.TECHNICAL_DATA_SHEET_S_ADDED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_ADD_TECHNICAL_DATA_SHEET_S')), 401);
        }
    }

    public function trackProductPricingHistory(Request $request) {
        //find brand list assigned to this product
        $brandArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand.id', 'brand.logo', 'brand.name')
                        ->where('brand.status', '1')
                        ->where('product_to_brand.product_id', $request->product_id)->get()->toArray();

        $product = Product::select('name')->where('id', $request->product_id)->first();

        $view = view('product.showTrackProductHistory', compact('request', 'brandArr', 'product'))->render();
        return response()->json(['html' => $view]);
    }

    public function getBrandWisePricingHistory(Request $request) {
        //check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();


        $unitName = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('measure_unit.name')->where('product.id', $request->product_id)->first();
        $unit = !empty($unitName->name) ? ' ' . __('label.PER') . ' ' . $unitName->name : '';
        $gradeArr = Grade::orderBy('order', 'asc')->where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //get pricing history of this product and brand
        $pricingHistoryArr = ProductPricingHistory::select('grade_id', 'history')->where('product_id', $request->product_id)->where('brand_id', $request->brand_id)->get();
        $pricingHistory = [];
        if (!$pricingHistoryArr->isEmpty()) {
            foreach ($pricingHistoryArr as $pricingHistoryData) {
                $gradeId = $pricingHistoryData->grade_id ?? 0;

                $productPricingHistory[$gradeId] = json_decode($pricingHistoryData->history, true);
                //krsort($productPricingHistory);
                $i = 0;

                if (!empty($productPricingHistory[$gradeId])) {
                    foreach ($productPricingHistory[$gradeId] as $history) {
                        $pricingHistory[$gradeId][$history['effective_date']]['realization_price'] = !empty($history['realization_price']) ? $history['realization_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['target_selling_price'] = !empty($history['target_selling_price']) ? $history['target_selling_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['minimum_selling_price'] = !empty($history['minimum_selling_price']) ? $history['minimum_selling_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['effective_date'] = !empty($history['effective_date']) ? $history['effective_date'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['remarks'] = !empty($history['remarks']) ? $history['remarks'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['special_note'] = !empty($history['special_note']) ? $history['special_note'] : __('label.N_A');
                    }
                }
                krsort($pricingHistory[$gradeId]);
            }
        }

        $brandNameArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();

        $view = view('product.getBrandWisePricingHistory', compact('request', 'pricingHistory'
                        , 'unit', 'brandNameArr', 'authorised', 'gradeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function brandDetails(Request $request) {
        $brandLogoArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('logo', 'id')->toArray();
        $brandNameArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();

        $brandInfoArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand_id')->where('brand.status', '1')
                        ->where('product_id', $request->product_id)->get();
        $brandIds = [];
        if (!$brandInfoArr->isEmpty()) {
            foreach ($brandInfoArr as $brand) {
                $brandIds[$brand->brand_id] = $brand->brand_id;
            }
        }
        $view = view('product.showBrandDetails', compact('request', 'brandInfoArr', 'brandIds', 'brandLogoArr', 'brandNameArr', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    //add new hs code row
    public function newHsCodeRow(Request $request) {
        $view = view('product.newHsCodeRow')->render();
        return response()->json(['html' => $view]);
    }

}
