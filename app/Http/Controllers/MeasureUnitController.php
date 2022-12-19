<?php

namespace App\Http\Controllers;

use Validator;
use App\MeasureUnit;
use App\Invoice;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class MeasureUnitController extends Controller {

    private $controller = 'MeasureUnit';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = MeasureUnit::select('measure_unit.*')->orderBy('order', 'asc');
        $nameArr = MeasureUnit::select('name')->orderBy('order', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('measure_unit.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/measureUnit?page=' . $page);
        }

        return view('measureUnit.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('measureUnit.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:measure_unit',
                    'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('measureUnit/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new MeasureUnit;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PRODUCT_MEASUREMENT_UNIT_CREATED_SUCCESSFULLY'));
            return redirect('measureUnit');
        } else {
            Session::flash('error', __('label.PRODUCT_MEASUREMENT_UNIT_COULD_NOT_BE_CREATED'));
            return redirect('measureUnit/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = MeasureUnit::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('measureUnit');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('measureUnit.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = MeasureUnit::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:measure_unit,name,' . $id,
                    'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('measureUnit/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.PRODUCT_MEASUREMENT_UNIT_UPDATED_SUCCESSFULLY'));
            return redirect('measureUnit' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_MEASUREMENT_UNIT_COULD_NOT_BE_UPDATED'));
            return redirect('measureUnit/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = MeasureUnit::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Product' => ['1' => 'measure_unit_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('measureUnit' . $pageNumber);
                }
            }
        }

        $invoiceInfo = Invoice::select('order_no_history')->get();
        $invoiceArr = $measureUnitIdArr = [];
        if (!$invoiceInfo->isEmpty()) {
            foreach ($invoiceInfo as $item) {
                $invoiceArr[] = json_decode($item->order_no_history, true);
            }

            foreach ($invoiceArr as $values) {
                foreach ($values as $val) {
                    if (!empty($val['unit_wise_gty'])) {
                        foreach ($val['unit_wise_gty'] as $unitId => $item) {
                            $measureUnitIdArr[$unitId] = $unitId;
                        }
                    }
                }
            }

            if (array_key_exists($id, $measureUnitIdArr)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Invoice']));
                return redirect('measureUnit' . $pageNumber);
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PRODUCT_MEASUREMENT_UNIT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_MEASUREMENT_UNIT_COULD_NOT_BE_DELETED'));
        }
        return redirect('measureUnit' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('measureUnit?' . $url);
    }

}
