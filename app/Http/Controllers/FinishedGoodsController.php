<?php

namespace App\Http\Controllers;

use Validator;
use App\FinishedGoods;
use App\Buyer;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class FinishedGoodsController extends Controller {

    private $controller = 'FinishedGoods';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = FinishedGoods::select('finished_goods.*')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = FinishedGoods::select('name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('finished_goods.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/finishedGoods?page=' . $page);
        }

        return view('finishedGoods.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('finishedGoods.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:finished_goods',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('finishedGoods/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new FinishedGoods;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.FINISHED_GOODS_CREATED_SUCCESSFULLY'));
            return redirect('finishedGoods');
        } else {
            Session::flash('error', __('label.FINISHED_GOODS_COULD_NOT_BE_CREATED'));
            return redirect('finishedGoods/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = FinishedGoods::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('finishedGoods');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('finishedGoods.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = FinishedGoods::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:department,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('finishedGoods/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.FINISHED_GOODS_UPDATED_SUCCESSFULLY'));
            return redirect('finishedGoods' . $pageNumber);
        } else {
            Session::flash('error', __('label.FINISHED_GOODS_COULD_NOT_BE_UPDATED'));
            return redirect('finishedGoods/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = FinishedGoods::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        // Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'department_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('finishedGoods' . $pageNumber);
//                }
//            }
//        }

        $buyerInfo = Buyer::whereNotNull('related_finished_goods')->select('related_finished_goods')->get();
        $finishedGoodsArr = $finishedGoodsArr2 = [];
        if (!$buyerInfo->isEmpty()) {
            foreach ($buyerInfo as $item) {
                $finishedGoodsArr[] = json_decode($item->related_finished_goods, true);
            }
            if (!empty($finishedGoodsArr)) {
                foreach ($finishedGoodsArr as $values) {
                    foreach ($values as $finishedGoodsId) {
                        $finishedGoodsArr2[$finishedGoodsId] = $finishedGoodsId;
                    }
                }
            }

            if (array_key_exists($id, $finishedGoodsArr2)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Buyer']));
                return redirect('finishedGoods' . $pageNumber);
            }
        }

        //END OF Dependency

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.FINISHED_GOODS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.FINISHED_GOODS_COULD_NOT_BE_DELETED'));
        }
        return redirect('finishedGoods' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('finishedGoods?' . $url);
    }

}
