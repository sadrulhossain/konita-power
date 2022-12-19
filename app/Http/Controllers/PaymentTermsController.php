<?php

namespace App\Http\Controllers;

use Validator;
use App\PaymentTerm;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class PaymentTermsController extends Controller {

    private $controller = 'PaymentTerm';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = PaymentTerm::select('payment_terms.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = PaymentTerm::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('payment_terms.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/paymentTerms?page=' . $page);
        }

        return view('paymentTerms.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('paymentTerms.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:payment_terms',
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('paymentTerms/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new PaymentTerm;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PAYMENTTERMS_CREATED_SUCCESSFULLY'));
            return redirect('paymentTerms');
        } else {
            Session::flash('error', __('label.PAYMENTTERMS_COULD_NOT_BE_CREATED'));
            return redirect('paymentTerms/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = PaymentTerm::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('paymentTerms');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('paymentTerms.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = PaymentTerm::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:payment_terms,name,' . $id,
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('paymentTerms/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.PAYMENTTERMS_UPDATED_SUCCESSFULLY'));
            return redirect('paymentTerms' . $pageNumber);
        } else {
            Session::flash('error', __('label.PAYMENTTERMS_COULD_NOT_BE_UPDATED'));
            return redirect('paymentTerms/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = PaymentTerm::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Quotation' => ['1' => 'payment_term_id'],
            'PoGenerate' => ['1' => 'payment_term_id'],
            'PiGenerate' => ['1' => 'payment_term_id', '2' => 'payment_terms_id_2'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('paymentTerms' . $pageNumber);
                }
            }
        }

   

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PAYMENTTERMS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PAYMENTTERMS_COULD_NOT_BE_DELETED'));
        }
        return redirect('paymentTerms' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('paymentTerms?' . $url);
    }

}
