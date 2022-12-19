<?php

namespace App\Http\Controllers;

use Validator;
use App\FollowupStatus;
use App\FollowUpHistory;
use App\Colors;
use App\Icons;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class FollowupStatusController extends Controller {

    private $controller = 'FollowupStatus';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = FollowupStatus::select('followup_status.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = FollowupStatus::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('followup_status.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/followupStatus?page=' . $page);
        }

        return view('followupStatus.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();

        $colorList = ['0' => __('label.SELECT_COLOR_OPT')] + Colors::pluck('name', 'name')->toArray();
        $iconList = ['0' => __('label.SELECT_ICONS_OPT')] + Icons::pluck('name', 'name')->toArray();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('followupStatus.create')->with(compact('qpArr', 'orderList'
                                , 'colorList', 'iconList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:followup_status',
                    'color' => 'required|not_in:0|unique:followup_status',
                    'icon' => 'required|not_in:0|unique:followup_status',
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('followupStatus/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new FollowupStatus;
        $target->name = $request->name;
        $target->color = $request->color;
        $target->icon = $request->icon;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.FOLLOWUP_STATUS_CREATED_SUCCESSFULLY'));
            return redirect('followupStatus');
        } else {
            Session::flash('error', __('label.FOLLOWUP_STATUS_COULD_NOT_BE_CREATED'));
            return redirect('followupStatus/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = FollowupStatus::find($id);
        $colorList = ['0' => __('label.SELECT_COLOR_OPT')] + Colors::pluck('name', 'name')->toArray();
        $iconList = ['0' => __('label.SELECT_ICONS_OPT')] + Icons::pluck('name', 'name')->toArray();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('followupStatus');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('followupStatus.edit')->with(compact('target', 'qpArr', 'orderList'
                                , 'colorList', 'iconList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = FollowupStatus::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:followup_status,name,' . $id,
                    'color' => 'required|not_in:0|unique:followup_status,color,' . $id,
                    'icon' => 'required|not_in:0|unique:followup_status,icon,' . $id,
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('followupStatus/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->color = $request->color;
        $target->icon = $request->icon;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.FOLLOWUP_STATUS_UPDATED_SUCCESSFULLY'));
            return redirect('followupStatus' . $pageNumber);
        } else {
            Session::flash('error', __('label.FOLLOWUP_STATUS_COULD_NOT_BE_UPDATED'));
            return redirect('followupStatus/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = FollowupStatus::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $followupInfo = FollowUpHistory::pluck('history')->toArray();
        $s = 0;
        if (!empty($followupInfo)) {
            foreach ($followupInfo as $history) {
                $historyArr = json_decode($history, true);
                if (!empty($historyArr)) {
                    $statusArr = array_column($historyArr, 'status');
                    if (in_array($id, $statusArr)) {
                        Session::flash('error', __('label.COULD_NOT_DELETE_DATA_USED_IN_FOLLOWUP'));
                        return redirect('followupStatus' . $pageNumber);
                    }
                }
            }
        }
//        echo $s;
//        echo '<pre>';
//        print_r($historyArr);
//        exit;
//        $dependencyArr = [
//            'Quotation' => ['1' => 'followup_status_id'],
//            'PoGenerate' => ['1' => 'followup_status_id'],
//            'PiGenerate' => ['1' => 'followup_status_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('followupStatus' . $pageNumber);
//                }
//            }
//        }


        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.FOLLOWUP_STATUS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.FOLLOWUP_STATUS_COULD_NOT_BE_DELETED'));
        }
        return redirect('followupStatus' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('followupStatus?' . $url);
    }

}
