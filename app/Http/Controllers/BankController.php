<?php

namespace App\Http\Controllers;

use Validator;
use App\Bank;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class BankController extends Controller {

    private $controller = 'Bank';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Bank::select('bank.*');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('bank.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/bank?page=' . $page);
        }

        return view('bank.index')->with(compact('targetArr', 'qpArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('bank.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:bank',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('bank/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Bank;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BANK_CREATED_SUCCESSFULLY'));
            return redirect('bank');
        } else {
            Session::flash('error', __('label.BANK_COULD_NOT_BE_CREATED'));
            return redirect('bank/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Bank::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('bank');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('bank.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Bank::find($id);
//        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:bank,name,' . $id,
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('bank/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BANK_UPDATED_SUCCESSFULLY'));
            return redirect('bank' . $pageNumber);
        } else {
            Session::flash('error', __('label.BANK_COULD_NOT_BE_UPDATED'));
            return redirect('bank/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Bank::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Lead' => ['1' => 'bank']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('bank' . $pageNumber);
                }
            }
        }


        if ($target->delete()) {
            Session::flash('error', __('label.BANK_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BANK_COULD_NOT_BE_DELETED'));
        }
        return redirect('bank' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('bank?' . $url);
    }

}
