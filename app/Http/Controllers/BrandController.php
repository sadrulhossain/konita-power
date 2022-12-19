<?php

namespace App\Http\Controllers;

use Validator;
use App\Brand;
use App\ProductToBrand;
use App\SalesPersonToProduct;
use App\BuyerToProduct;
use App\SupplierToProduct;
use App\Product;
use App\Country;
use App\Certificate;
use Session;
use Redirect;
use File;
use Auth;
use Response;
use DB;
use Illuminate\Http\Request;

class BrandController extends Controller {

    private $controller = 'Brand';
    private $fileSize = '1024';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $certificateArr = Certificate::orderBy('name', 'asc')->pluck('logo', 'id')->toArray();

        $targetArr = Brand::leftJoin('country', 'country.id', '=', 'brand.origin')
                        ->select('brand.*', 'country.name as origin')->orderBy('brand.name', 'asc');
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('brand.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('brand.status', '=', $request->status);
        }
        if (!empty($request->origin)) {
            $targetArr = $targetArr->where('brand.origin', $request->origin);
        }


        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        $nameArr = Brand::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/brand?page=' . $page);
        }

        $prevCertificateArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $prevCertificateArr[$item->id] = json_decode($item->certificate, true);
            }
        }

        return view('brand.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status', 'originArr', 'prevCertificateArr', 'certificateArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = Product::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $certificateArr = Certificate::where('status', '1')->select('name', 'logo', 'id')->orderBy('name', 'asc')->get()->toArray();
        return view('brand.create')->with(compact('qpArr', 'originArr', 'certificateArr', 'productArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $certificateName = Certificate::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $qpArr = $request->all();
        $certificateList = Certificate::where('status', '1')->pluck('name', 'id')->toArray();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        $message = [];
        $rules = [
            'name' => 'required|unique:brand',
            'origin' => 'required|not_in:0',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }


        $certificateArr = $request->file('certificate_file');


        if (!empty($request->certificate_checked)) {
            foreach ($request->certificate_checked as $key => $values) {
                if (empty($request->certificate_file)) {
                    $cerName = !empty($certificateList[$key]) ? $certificateList[$key] : '';
                    $rules['certificate_file' . ' ' . $cerName] = 'required';
                }

                if (empty($request->certificate_file[$key])) {
                    $cerName = !empty($certificateList[$key]) ? $certificateList[$key] : '';
                    $rules['certificate_file' . ' ' . $cerName] = 'required';
                }


                if (!empty($request->certificate_file)) {
                    foreach ($request->certificate_file as $cerId => $file) {
                        if ($key == $cerId) {
                            if (empty($file)) {
                                $cerName = !empty($certificateList[$cerId]) ? $certificateList[$cerId] : '';
                                $rules['certificate_file' . ' ' . $cerName] = 'required';
                            }
                        }
                    }
                }
            }
        }

        $fileArr = [];
        if (!empty($request->certificate_checked)) {
            foreach ($request->certificate_checked as $key => $values) {
                if (!empty($certificateArr)) {
                    foreach ($certificateArr as $cerId => $file) {
                        if ($key == $cerId) {
                            $fileArr[$cerId] = $file;
                        }
                    }
                }
            }
        }

        if ($request->hasFile('certificate_file')) {
            foreach ($fileArr as $cerId => $items) {
                $rules['certificate_file.' . $cerId] = 'max:' . $this->fileSize . '|mimes:pdf';
                $message['certificate_file.' . $cerId . '.mimes'] = __('label.INVALID_FILE_FORMAT');
                $cerName = !empty($certificateList[$cerId]) ? $certificateList[$cerId] : '';
                $message['certificate_file.' . $cerId . '.max'] = __('label.THE_CERTIFICATE_FILE') . ' ' . $cerName . ' ' . __('label.MAY_NOT_BE_GREATER_THAN_1024_KILOBYTES');
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $dataSheetData = [];
        //if (!empty($request->certificate_id)) {
        if ($request->hasFile('certificate_file')) {
            if (!empty($fileArr)) {
                foreach ($fileArr as $cerId => $fileName) {
                    $fileNames = Auth::user()->id . uniqid() . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/brandCertificate', $fileNames);
                    $dataSheetData[$cerId] = $fileNames;
                }
            }
        }

        $jsonEncodeCertificateArr = '';
        $jsonEncodeCertificateArr = json_encode($dataSheetData);

        //file upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/brand', $logoName);
        }

        $target = new Brand;
        $target->name = $request->name;
        $target->description = $request->description;
        $target->logo = !empty($logoName) ? $logoName : '';
        $target->origin = $request->origin;
        $target->status = $request->status;
        $target->certificate = $jsonEncodeCertificateArr;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                //save data in product to  brand table
                $productToBrandArr = $salesPersonToProductArr = $buyerToProductArr = $supplierToProductArr = [];
                $p2b = $sp2p = $b2p = $s2p = 0;

                if (!empty($request->product)) {
                    foreach ($request->product as $key => $productId) {
                        $productToBrandArr[$p2b]['product_id'] = $productId;
                        $productToBrandArr[$p2b]['brand_id'] = $target->id;
                        $productToBrandArr[$p2b]['has_grade'] = '0';
                        $productToBrandArr[$p2b]['created_by'] = Auth::user()->id;
                        $productToBrandArr[$p2b]['created_at'] = date('Y-m-d H:i:s');
                        $p2b++;
                    }
                    ProductToBrand::insert($productToBrandArr);

//                    $suppliersArr = SupplierToProduct::whereIn('product_id', $request->product)
//                                    ->pluck('supplier_id', 'supplier_id')->toArray();
                    $salesPersonArr = SalesPersonToProduct::whereIn('product_id', $request->product)
                                    ->pluck('sales_person_id', 'sales_person_id')->toArray();
                    $buyersArr = BuyerToProduct::whereIn('product_id', $request->product)
                                    ->pluck('buyer_id', 'buyer_id')->toArray();

                    //save data in sales person to product table
                    if (!empty($salesPersonArr)) {
                        foreach ($salesPersonArr as $salePersonId => $salesPerson) {
                            if (!empty($request->product)) {
                                foreach ($request->product as $key => $productId) {
                                    $salesPersonToProductArr[$sp2p]['sales_person_id'] = $salePersonId;
                                    $salesPersonToProductArr[$sp2p]['product_id'] = $productId;
                                    $salesPersonToProductArr[$sp2p]['brand_id'] = $target->id;
                                    $salesPersonToProductArr[$sp2p]['created_by'] = Auth::user()->id;
                                    $salesPersonToProductArr[$sp2p]['created_at'] = date('Y-m-d H:i:s');
                                    $sp2p++;
                                }
                            }
                        }
                    }

                    SalesPersonToProduct::insert($salesPersonToProductArr);
                    
                    //save data in byer to product table
                    if (!empty($buyersArr)) {
                        foreach ($buyersArr as $buyerId => $buyer) {
                            if (!empty($request->product)) {
                                foreach ($request->product as $key => $productId) {
                                    $buyerToProductArr[$b2p]['buyer_id'] = $buyerId;
                                    $buyerToProductArr[$b2p]['product_id'] = $productId;
                                    $buyerToProductArr[$b2p]['brand_id'] = $target->id;
                                    $buyerToProductArr[$b2p]['created_by'] = Auth::user()->id;
                                    $buyerToProductArr[$b2p]['created_at'] = date('Y-m-d H:i:s');
                                    $b2p++;
                                }
                            }
                        }
                    }
                    BuyerToProduct::insert($buyerToProductArr);
                    
                    //save data in supplier to product table
//                    if (!empty($suppliersArr)) {
//                        foreach ($suppliersArr as $supplierId => $suppliers) {
//                            if (!empty($request->product)) {
//                                foreach ($request->product as $key => $productId) {
//                                    $supplierToProductArr[$s2p]['supplier_id'] = $supplierId;
//                                    $supplierToProductArr[$s2p]['product_id'] = $productId;
//                                    $supplierToProductArr[$s2p]['brand_id'] = $target->id;
//                                    $supplierToProductArr[$s2p]['created_by'] = Auth::user()->id;
//                                    $supplierToProductArr[$s2p]['created_at'] = date('Y-m-d H:i:s');
//                                    $s2p++;
//                                }
//                            }
//                        }
//                    }
//                    SupplierToProduct::insert($supplierToProductArr);
                }
            }
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.BRAND_CREATED_SUCCESSFULLY')), 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.BRAND_COULD_NOT_BE_CREATED')), 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Brand::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('brand');
        }

        if (!empty($target)) {
            $prevCertificateArr = json_decode($target->certificate, true);
        }
        $certificateArr = Certificate::where('status', '1')->select('name', 'logo', 'id')->orderBy('name', 'asc')->get()->toArray();

        $checkedArr = [];
        if (!empty($prevCertificateArr)) {
            foreach ($prevCertificateArr as $cerId => $item) {
                $checkedArr[$cerId] = $cerId;
            }
        }
        //passing param for custom function
        $qpArr = $request->all();
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('brand.edit')->with(compact('target', 'qpArr', 'originArr', 'prevCertificateArr', 'certificateArr'
                                , 'checkedArr'));
    }

    public function update(Request $request) {

        $target = Brand::find($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $message = [];
        $rules = [
            'name' => 'required',
            'origin' => 'required|not_in:0',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $certificateList = Certificate::where('status', '1')->pluck('name', 'id')->toArray();

        $certificateArr = $request->file('certificate_file');
        if (!empty($request->certificate_checked)) {
            foreach ($request->certificate_checked as $key => $values) {
                if (empty($request->prev_certificate_file[$key])) {
                    if (empty($request->certificate_file)) {
                        $cerName = !empty($certificateList[$key]) ? $certificateList[$key] : '';
                        $rules['certificate_file' . ' ' . $cerName] = 'required';
                    }
                }

                if (empty($request->prev_certificate_file[$key])) {
                    if (empty($request->certificate_file[$key])) {
                        $cerName = !empty($certificateList[$key]) ? $certificateList[$key] : '';
                        $rules['certificate_file' . ' ' . $cerName] = 'required';
                    }
                }


                if (!empty($request->certificate_file)) {
                    foreach ($request->certificate_file as $cerId => $file) {
                        if ($key == $cerId) {
                            if (empty($file)) {
                                $cerName = !empty($certificateList[$cerId]) ? $certificateList[$cerId] : '';
                                $rules['certificate_file' . ' ' . $cerName] = 'required';
                            }
                        }
                    }
                }
            }
        }



        $fileArr = [];
        if (!empty($request->certificate_checked)) {
            foreach ($request->certificate_checked as $key => $values) {
                if (!empty($certificateArr)) {
                    foreach ($certificateArr as $cerId => $file) {
                        if ($key == $cerId) {
                            $fileArr[$cerId] = $file;
                        }
                    }
                }
            }
        }

        if ($request->hasFile('certificate_file')) {
            foreach ($fileArr as $cerId => $items) {
                $rules['certificate_file.' . $cerId] = 'max:' . $this->fileSize . '|mimes:pdf';
                $message['certificate_file.' . $cerId . '.mimes'] = __('label.INVALID_FILE_FORMAT');
                $cerName = !empty($certificateList[$cerId]) ? $certificateList[$cerId] : '';
                $message['certificate_file.' . $cerId . '.max'] = __('label.THE_CERTIFICATE_FILE') . ' ' . $cerName . ' ' . __('label.MAY_NOT_BE_GREATER_THAN_1024_KILOBYTES');
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $dataSheetData = $dataArr = [];
        if ($request->hasFile('certificate_file')) {
            if (!empty($fileArr)) {
                foreach ($fileArr as $cerId => $fileName) {

                    //START::previous file delete from folder
                    $prevfileName = 'public/uploads/brandCertificate/' . $request->prev_certificate_file[$cerId];

                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }

                    foreach ($request->prev_certificate_file as $cerId3 => $fileName3) {
                        if ($cerId3 != $cerId) {
                            if (!array_key_exists($cerId3, $request->certificate_checked)) {
                                $prevfileName = 'public/uploads/brandCertificate/' . $request->prev_certificate_file[$cerId3];
                                if (File::exists($prevfileName)) {
                                    File::delete($prevfileName);
                                }
                            }
                        }
                    }

                    //END::previous file delete from folder

                    $fileNames = Auth::user()->id . uniqid() . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/brandCertificate', $fileNames);
                    $dataArr[$cerId] = $fileNames;
                }
            }
        }

        if (empty($request->certificate_checked)) {
            if (!empty($request->prev_certificate_file)) {
                foreach ($request->prev_certificate_file as $cerId => $file) {
                    //START::previous file delete from folder
                    $prevfileName = 'public/uploads/brandCertificate/' . $file;
                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                    //END::previous file delete from folder
                }
            }
        }


        if (!empty($request->certificate_checked)) {
            if (empty($fileArr)) {
                foreach ($request->prev_certificate_file as $cerId => $file) {
                    foreach ($request->certificate_checked as $cerId2 => $file2) {
                        if (!array_key_exists($cerId, $request->certificate_checked)) {
                            //START::previous file delete from folder
                            $prevfileName = 'public/uploads/brandCertificate/' . $file;
                            if (File::exists($prevfileName)) {
                                File::delete($prevfileName);
                            }
                            //END::previous file delete from folder
                        }
                    }
                }
            }
        }

        if (!empty($request->certificate_checked)) {
            foreach ($request->certificate_checked as $cerId => $values) {
                $dataSheetData[$cerId] = !empty($dataArr[$cerId]) ? $dataArr[$cerId] : (!empty($request->prev_certificate_file[$cerId]) ? $request->prev_certificate_file[$cerId] : '');
            }
        }

        //ENDOF PREV FILE EXISTS

        $jsonEncodeCertificateArr = '';
        $jsonEncodeCertificateArr = json_encode($dataSheetData);

        if (!empty($request->logo)) {
            $prevLogoName = 'public/uploads/brand/' . $target->logo;

            if (File::exists($prevLogoName)) {
                File::delete($prevLogoName);
            }
        }


        //If Previous File Attached and Moved to public folder,Please Delete it from public folder
        if (!empty($request->cerfiticate_file)) {
            $prevfileName = 'public/uploads/brandCertificate/' . $target->certificate_file;
            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }//Endif
        }

        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/brand', $logoName);
        }
        $target->name = $request->name;
        $target->description = $request->description;
        $target->logo = !empty($logoName) ? $logoName : $target->logo;
        $target->origin = $request->origin;
        $target->status = $request->status;
        $target->certificate = $jsonEncodeCertificateArr;


        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.BRAND_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.BRAND_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Brand::find($id);


        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency check
        $dependencyArr = [
            'ProductToBrand' => ['1' => 'brand_id'],
            'ProductToGrade' => ['1' => 'brand_id'],
            'ProductPricing' => ['1' => 'brand_id'],
            'ProductPricingHistory' => ['1' => 'brand_id'],
            'ProductTechDataSheet' => ['1' => 'brand_id'],
            'SalesPersonToProduct' => ['1' => 'brand_id'],
            'BuyerToProduct' => ['1' => 'brand_id'],
            'SupplierToProduct' => ['1' => 'brand_id'],
            'InquiryDetails' => ['1' => 'brand_id'],
            'RwBreakdown' => ['1' => 'brand_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('brand' . $pageNumber);
                }
            }
        }
        //end :: dependency check
        //If Previous Logo Attached and Moved to public folder,Please Delete it from public folder
        $fileName = 'public/uploads/brand/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }


        $certificateArr = [];
        if (!empty($target->certificate)) {
            $certificateArr = json_decode($target->certificate, true);
        }
        if (!empty($certificateArr)) {
            foreach ($certificateArr as $file) {
                $fileName = 'public/uploads/brandCertificate/' . $file;
                if (File::exists($fileName)) {
                    File::delete($fileName);
                }
            }
        }



        if ($target->delete()) {
            Session::flash('error', __('label.BRAND_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BRAND_COULD_NOT_BE_DELETED'));
        }
        return redirect('brand' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status . '&origin=' . $request->origin;
        return Redirect::to('brand?' . $url);
    }

}
