<?php

namespace App\Http\Controllers;

use Validator;
use App\KonitaBankAccount;
use App\SignatoryInfo;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class KonitaBankController extends Controller {

    private $controller = 'KonitaBank';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = KonitaBankAccount::select('konita_bank_account.*')->orderBy('id');

        //begin filtering

        $searchText = $request->search;
        $nameArr = KonitaBankAccount::select('bank_name');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('bank_name', 'LIKE', '%' . $searchText . '%');
            });
        }



        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/konitaBank?page=' . $page);
        }

        return view('konitaBank.index')->with(compact('targetArr', 'qpArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('konitaBank.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'bank_name' => 'required',
                    'account_no' => 'required',
                    'account_name' => 'required',
                    'branch' => 'required',
                    'swift' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('konitaBank/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new KonitaBankAccount;
        $target->bank_name = $request->bank_name;
        $target->account_no = $request->account_no;
        $target->account_name = $request->account_name;
        $target->branch = $request->branch;
        $target->swift = $request->swift;

        if ($target->save()) {
            Session::flash('success', __('label.KONITA_BANK_ACCOUNT_CREATED_SUCCESSFULLY'));
            return redirect('konitaBank');
        } else {
            Session::flash('error', __('label.KONITA_BANK_ACCOUNT_COULD_NOT_BE_CREATED'));
            return redirect('konitaBank/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = KonitaBankAccount::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('konitaBank');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('konitaBank.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = KonitaBankAccount::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'bank_name' => 'required',
                    'account_no' => 'required',
                    'account_name' => 'required',
                    'branch' => 'required',
                    'swift' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('konitaBank/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->bank_name = $request->bank_name;
        $target->account_no = $request->account_no;
        $target->account_name = $request->account_name;
        $target->branch = $request->branch;
        $target->swift = $request->swift;

        if ($target->save()) {
            Session::flash('success', __('label.KONITA_BANK_ACCOUNT_UPDATED_SUCCESSFULLY'));
            return redirect('konitaBank' . $pageNumber);
        } else {
            Session::flash('error', __('label.KONITA_BANK_ACCOUNT_COULD_NOT_BE_UPDATED'));
            return redirect('konitaBank/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = KonitaBankAccount::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Invoice' => ['1' => 'konita_bank_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('konitaBank' . $pageNumber);
                }
            }
        }

        //END OF Dependency

        if ($target->delete()) {
            Session::flash('error', __('label.KONITA_BANK_ACCOUNT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.KONITA_BANK_ACCOUNT_COULD_NOT_BE_DELETED'));
        }
        return redirect('konitaBank' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('konitaBank?' . $url);
    }

}
