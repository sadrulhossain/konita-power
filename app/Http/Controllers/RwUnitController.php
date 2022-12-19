<?php

namespace App\Http\Controllers;

use Validator;
use App\RwUnit;
use App\RwBreakdown;
use App\RwUnitConversion;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class RwUnitController extends Controller {

    private $controller = 'RwUnit';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = RwUnit::select('rw_unit.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = RwUnit::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->status)) {
            $targetArr = $targetArr->where('rw_unit.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/department?page=' . $page);
        }

        return view('rwUnit.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('rwUnit.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:rw_unit',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('rwUnit/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new RwUnit;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.RW_UNIT_CREATED_SUCCESSFULLY'));
            return redirect('rwUnit');
        } else {
            Session::flash('error', __('label.RW_UNIT_COULD_NOT_BE_CREATED'));
            return redirect('rwUnit/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = RwUnit::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('rwUnit');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('rwUnit.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = RwUnit::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:rw_unit,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('rwUnit/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.RW_UNIT_UPDATED_SUCCESSFULLY'));
            return redirect('rwUnit' . $pageNumber);
        } else {
            Session::flash('error', __('label.RW_UNIT_COULD_NOT_BE_UPDATED'));
            return redirect('rwUnit/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = RwUnit::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'department_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('rwUnit' . $pageNumber);
//                }
//            }
//        }

        $rwBreakdownInfo = RwBreakdown::select('rw_unit_id', 'gsm_details')->get();
        $RwUnitArr = $gsmDetailsArr = $RwUnitIdArr = $RwUnitIdArr2 = [];
        if (!$rwBreakdownInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $item) {
                $RwUnitArr[] = json_decode($item->rw_unit_id, true);
                $gsmDetailsArr[] = json_decode($item->gsm_details, true);
            }
            //RW UNIT ID
            if (!empty($RwUnitArr)) {
                foreach ($RwUnitArr as $values) {
                    if (!empty($values)) {
                        foreach ($values as $rwUnitId) {
                            $RwUnitIdArr[$rwUnitId] = $rwUnitId;
                        }
                    }
                }
            }
            //END OF RW UNIT 
            if (array_key_exists($id, $RwUnitIdArr)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'RW Breakdown']));
                return redirect('rwUnit' . $pageNumber);
            }

            //GSM UNDER RW UNIT ID
            if (!empty($gsmDetailsArr)) {
                foreach ($gsmDetailsArr as $gsmVal) {
                    if (!empty($gsmVal)) {
                        foreach ($gsmVal as $rowVal) {
                            foreach ($rowVal as $values) {
                                foreach ($values as $rwUnitId => $item) {
                                    if ($rwUnitId != 'quantity') {
                                        $RwUnitIdArr2[$rwUnitId] = $rwUnitId;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (array_key_exists($id, $RwUnitIdArr2)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'RW Breakdown']));
                return redirect('rwUnit' . $pageNumber);
            }
        }


        //END OF DEPENDENCY


        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.RW_UNIT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.RW_UNIT_COULD_NOT_BE_DELETED'));
        }
        return redirect('rwUnit' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('rwUnit?' . $url);
    }

    public function getConversion(Request $request) {
        $unitList = RwUnit::where('status', '1')->pluck('name', 'id')->toArray();

        $prevDataArr = RwUnitConversion::where('base_unit_id', $request->unit_id)
                        ->pluck('conv_unit_rate', 'conv_unit_id')->toArray();

        $view = view('rwUnit.showSetUnitConversion', compact('request', 'unitList', 'prevDataArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function setConversion(Request $request) {

        $convRate = $request->conv_rate;
        $convUnit = $request->conv_unit;
        //validation
        $rules = $message = array();

        if (!empty($convRate)) {
            foreach ($convRate as $unitId => $unit) {
                $rules['conv_rate.' . $unitId] = 'required';
                $message['conv_rate.' . $unitId] = __('label.CONVERSION_RATE_IS_REQUIRED_FOR_UNIT', ['unit' => $convUnit[$unitId]]);
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation
        //find data input from checked brands only
        $i = 0;
        $data = $dataSheetData = $productTechDataSheet = [];
        if (!empty($convRate)) {
            foreach ($convRate as $unitId => $unit) {
                $data[$i]['base_unit_id'] = $request->base_unit_id;
                $data[$i]['base_unit_rate'] = 1;
                $data[$i]['conv_unit_id'] = $unitId;
                $data[$i]['conv_unit_rate'] = $unit;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }

        RwUnitConversion::where('base_unit_id', $request->base_unit_id)->delete();
        if (RwUnitConversion::insert($data)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.UNIT_CONVERSION_HAS_BEEN_SET_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.UNIT_CONVERSION_COULD_NOT_BE_SET')), 401);
        }
    }

}
