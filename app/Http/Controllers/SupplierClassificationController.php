<?php

namespace App\Http\Controllers;

use Validator;
use App\SupplierClassification;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class SupplierClassificationController extends Controller {

    private $controller = 'SupplierClassification';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SupplierClassification::select('supplier_classification.*')->orderBy('order', 'asc');

        //begin filtering
        $nameArr = SupplierClassification::select('name')->orderBy('order', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('supplier_classification.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/supplierClassification?page=' . $page);
        }

        return view('supplierClassification.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('supplierClassification.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:supplier_classification',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('supplierClassification/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new SupplierClassification;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.SUPPLIER_CLASSIFICATION_CREATED_SUCCESSFULLY'));
            return redirect('supplierClassification');
        } else {
            Session::flash('error', __('label.SUPPLIER_CLASSIFICATION_COULD_NOT_BE_CREATED'));
            return redirect('supplierClassification/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = SupplierClassification::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('supplierClassification');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('supplierClassification.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = SupplierClassification::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:supplier_classification,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('supplierClassification/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.SUPPLIER_CLASSIFICATION_UPDATED_SUCCESSFULLY'));
            return redirect('supplierClassification' . $pageNumber);
        } else {
            Session::flash('error', __('label.SUPPLIER_CLASSIFICATION_COULD_NOT_BE_UPDATED'));
            return redirect('supplierClassification/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = SupplierClassification::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        //Dependency
        $dependencyArr = [
            'Supplier' => ['1' => 'supplier_classification_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('supplierClassification' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.SUPPLIER_CLASSIFICATION_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SUPPLIER_CLASSIFICATION_COULD_NOT_BE_DELETED'));
        }
        return redirect('supplierClassification' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('supplierClassification?' . $url);
    }

}
