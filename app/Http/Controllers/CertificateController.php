<?php

namespace App\Http\Controllers;

use Validator;
use App\Certificate;
use App\Brand;
use Auth;
use Session;
use Redirect;
use Helper;
use File;
use Illuminate\Http\Request;

class CertificateController extends Controller {

    private $controller = 'Certificate';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Certificate::select('certificate.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = Certificate::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('certificate.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/certificate?page=' . $page);
        }

        return view('certificate.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('certificate.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
//        echo '<pre>';        print_r($request->all());exit;
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        $rules = [
            'name' => 'required|unique:certificate',
            'order' => 'required|not_in:0',
            'logo' => 'required',
            'status' => 'required|not_in:0'
        ];

//        if (!empty($request->logo)) {
//            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
//        }
        if (!empty($request->logo)) {
            $rules ['logo'] = 'required|max:1024|mimes:jpeg,png,jpg';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('certificate/create' . $pageNumber)
                            ->withInput($request->except('logo'))
                            ->withErrors($validator);
        }
        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/img/certificate', $fileName);
        }
        $target = new Certificate;
        $target->name = $request->name;
        $target->logo = !empty($fileName) ? $fileName : '';
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.CERTIFICATE_CREATED_SUCCESSFULLY'));
            return redirect('certificate');
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_CREATED'));
            return redirect('certificate/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Certificate::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('certificate');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('certificate.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Certificate::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required|unique:certificate,id,' . $id,
            'order' => 'required|not_in:0',
            'status' => 'required|not_in:0'
        ];

        if (!empty($request->logo)) {
            $rules = [
                'logo' => 'required|max:1024|mimes:jpeg,png,jpg'
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('certificate/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/img/certificate/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/img/certificate', $fileName);
        }
        $target->name = $request->name;
        $target->logo = !empty($fileName) ? $fileName : $target->logo;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.CERTIFICATE_UPDATED_SUCCESSFULLY'));
            return redirect('certificate' . $pageNumber);
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_UPDATED'));
            return redirect('certificate/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Certificate::find($id);
//
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'certificate_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('certificate' . $pageNumber);
//                }
//            }
//        }

        $brandInfo = Brand::select('certificate')->get();
        $certificateArr = [];
        if (!$brandInfo->isEmpty()) {
            
            foreach ($brandInfo as $item) {
                $certificateArr[] = json_decode($item->certificate, true);
            }

            if (!empty($certificateArr)) {
                foreach ($certificateArr as $values) {
                    if (!empty($values)) {
                        foreach ($values as $certificateId => $item) {
                            $certificateIdArr[$certificateId] = $certificateId;
                        }
                    }
                }
            }

            if (array_key_exists($id, $certificateIdArr)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Brand']));
                return redirect('certificate' . $pageNumber);
            }
        }
        //END OF Dependency
       
        
        // Delete logo from public folder
        $prevfileName = 'public/img/certificate/' . $target->logo;
        if (File::exists($prevfileName)) {
            File::delete($prevfileName);
        }//Endif

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.CERTIFICATE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_DELETED'));
        }
        return redirect('certificate' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('certificate?' . $url);
    }

}
