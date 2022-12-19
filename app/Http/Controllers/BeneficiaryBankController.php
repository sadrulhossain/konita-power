<?php

namespace App\Http\Controllers;

use Validator;
use App\BeneficiaryBank;
use App\Supplier;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class BeneficiaryBankController extends Controller {

    private $controller = 'BeneficiaryBank';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = BeneficiaryBank::join('supplier', 'supplier.id', '=', 'beneficiary_bank.supplier_id')
                ->select('beneficiary_bank.*', 'supplier.name as supplier_name')
                ->orderBy('beneficiary_bank.id', 'desc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = BeneficiaryBank::select('name')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('beneficiary_bank.name', 'LIKE', '%' . $searchText . '%');
            });
        }



        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/beneficiaryBank?page=' . $page);
        }

        return view('beneficiaryBank.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();

        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        return view('beneficiaryBank.create')->with(compact('qpArr', 'supplierList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'supplier_id' => 'required|not_in:0',
                    'name' => 'required',
                    'account_no' => 'required',
                    'customer_id' => 'required',
                    'branch' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('beneficiaryBank/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new BeneficiaryBank;
        $target->supplier_id = $request->supplier_id;
        $target->name = $request->name;
        $target->account_no = $request->account_no;
        $target->customer_id = $request->customer_id;
        $target->branch = $request->branch;
        $target->swift_code = $request->swift_code;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BENEFICIARY_BANK_CREATED_SUCCESSFULLY'));
            return redirect('beneficiaryBank');
        } else {
            Session::flash('error', __('label.BENEFICIARY_BANK_COULD_NOT_BE_CREATED'));
            return redirect('beneficiaryBank/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {


        $target = BeneficiaryBank::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('beneficiaryBank');
        }

        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //passing param for custom function
        $qpArr = $request->all();
        return view('beneficiaryBank.edit')->with(compact('target', 'qpArr', 'supplierList'));
    }

    public function update(Request $request, $id) {
        $target = BeneficiaryBank::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'supplier_id' => 'required|not_in:0',
                    'name' => 'required',
                    'account_no' => 'required',
                    'customer_id' => 'required',
                    'branch' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('beneficiaryBank/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->supplier_id = $request->supplier_id;
        $target->name = $request->name;
        $target->account_no = $request->account_no;
        $target->customer_id = $request->customer_id;
        $target->branch = $request->branch;
        $target->swift_code = $request->swift_code;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BENEFICIARY_BANK_UPDATED_SUCCESSFULLY'));
            return redirect('beneficiaryBank' . $pageNumber);
        } else {
            Session::flash('error', __('label.BENEFICIARY_BANK_COULD_NOT_BE_UPDATED'));
            return redirect('beneficiaryBank/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = BeneficiaryBank::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'PiGenerate' => ['1' => 'beneficiary_bank_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('beneficiaryBank' . $pageNumber);
                }
            }
        }
        //END OF Dependency

  
        if ($target->delete()) {
            Session::flash('error', __('label.BENEFICIARY_BANK_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BENEFICIARY_BANK_COULD_NOT_BE_DELETED'));
        }
        return redirect('beneficiaryBank' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('beneficiaryBank?' . $url);
    }

}
