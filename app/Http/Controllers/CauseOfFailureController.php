<?php

namespace App\Http\Controllers;

use Validator;
use App\CauseOfFailure;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class CauseOfFailureController extends Controller {

    private $controller = 'CauseOfFailure';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = CauseOfFailure::select('cause_of_failure.*')->orderBy('order', 'asc');
        $nameArr = CauseOfFailure::select('title')->orderBy('order', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('cause_of_failure.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/causeOfFailure?page=' . $page);
        }

        return view('causeOfFailure.index')->with(compact('targetArr', 'qpArr','nameArr','status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('causeOfFailure.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:cause_of_failure',
                    'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('causeOfFailure/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new CauseOfFailure;
        $target->title = $request->title;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.CAUSE_OF_FAILURE_CREATED_SUCCESSFULLY'));
            return redirect('causeOfFailure');
        } else {
            Session::flash('error', __('label.CAUSE_OF_FAILURE_COULD_NOT_BE_CREATED'));
            return redirect('causeOfFailure/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = CauseOfFailure::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('causeOfFailure');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('causeOfFailure.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = CauseOfFailure::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:cause_of_failure,title,' . $id,
                    'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('causeOfFailure/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->title = $request->title;
        $target->order = $request->order;
        $target->status = $request->status;
        
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.CAUSE_OF_FAILURE_UPDATED_SUCCESSFULLY'));
            return redirect('causeOfFailure' . $pageNumber);
        } else {
            Session::flash('error', __('label.CAUSE_OF_FAILURE_COULD_NOT_BE_UPDATED'));
            return redirect('causeOfFailure/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = CauseOfFailure::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency check
        $dependencyArr = [
            'Lead' => ['1' => 'cancel_cause', '2' => 'order_cancel_cause'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('causeOfFailure' . $pageNumber);
                }
            }
        }
        //end :: dependency check

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.CAUSE_OF_FAILURE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CAUSE_OF_FAILURE_COULD_NOT_BE_DELETED'));
        }
        return redirect('causeOfFailure' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('causeOfFailure?' . $url);
    }

}