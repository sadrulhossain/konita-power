<?php

namespace App\Http\Controllers;

use Validator;
use App\ContactDesignation;
use App\Buyer;
use App\BuyerFactory;
use App\Supplier;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ContactDesignationController extends Controller {

    private $controller = 'ContactDesignation';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ContactDesignation::select('contact_designation.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = ContactDesignation::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('contact_designation.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/contactDesignation?page=' . $page);
        }

        return view('contactDesignation.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('contactDesignation.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:contact_designation',
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('contactDesignation/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ContactDesignation;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.CONTACT_DESIGNATION_CREATED_SUCCESSFULLY'));
            return redirect('contactDesignation');
        } else {
            Session::flash('error', __('label.CONTACT_DESIGNATION_COULD_NOT_BE_CREATED'));
            return redirect('contactDesignation/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ContactDesignation::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('contactDesignation');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('contactDesignation.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = ContactDesignation::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:contact_designation,name,' . $id,
                    'order' => 'required|not_in:0',
                    'status' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('contactDesignation/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.CONTACT_DESIGNATION_UPDATED_SUCCESSFULLY'));
            return redirect('contactDesignation' . $pageNumber);
        } else {
            Session::flash('error', __('label.CONTACT_DESIGNATION_COULD_NOT_BE_UPDATED'));
            return redirect('contactDesignation/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ContactDesignation::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //  Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'contactDesignation_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
//                    return redirect('contactDesignation' . $pageNumber);
//                }
//            }
//        }
        //BUYER
        $buyerCheckArr = Buyer::select('contact_person_data')->get();
        $designationArr = $designationArr2 = [];
        if (!$buyerCheckArr->isEmpty()) {
            foreach ($buyerCheckArr as $item) {
                $designationArr[] = json_decode($item->contact_person_data, true);
            }
            if (!empty($designationArr)) {
                foreach ($designationArr as $values) {
                    foreach ($values as $item2) {
                        $designationArr2[$item2['designation_id']] = $item2['designation_id'];
                    }
                }
            }
            
            if (array_key_exists($id, $designationArr2)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Buyer']));
                return redirect('contactDesignation' . $pageNumber);
            }
        }


        //Buyer Factory
        $buyerFactoryArr = BuyerFactory::select('contact_person_data')->get();
        $designationArr3 = $designationArr4 = [];
        if (!$buyerFactoryArr->isEmpty()) {
            foreach ($buyerFactoryArr as $item) {
                $designationArr3[] = json_decode($item->contact_person_data, true);
            }
            if (!empty($designationArr3)) {
                foreach ($designationArr3 as $values) {
                    foreach ($values as $item2) {
                        $designationArr4[$item2['designation_id']] = $item2['designation_id'];
                    }
                }
            }
            if (array_key_exists($id, $designationArr4)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Buyer Factory']));
                return redirect('contactDesignation' . $pageNumber);
            }
        }


        //SUPPLIER

        $supplierArr = Supplier::select('contact_person_data')->get();
        $designationArr5 = $designationArr6 = [];
        if (!$supplierArr->isEmpty()) {
            foreach ($supplierArr as $item) {
                $designationArr5[] = json_decode($item->contact_person_data, true);
            }
            if (!empty($designationArr5)) {
                foreach ($designationArr5 as $values) {
                    foreach ($values as $item2) {
                        $designationArr6[$item2['designation_id']] = $item2['designation_id'];
                    }
                }
            }
            if (array_key_exists($id, $designationArr6)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Supplier']));
                return redirect('contactDesignation' . $pageNumber);
            }
        }

        // END OF Dependency

 
        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.CONTACT_DESIGNATION_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CONTACT_DESIGNATION_COULD_NOT_BE_DELETED'));
        }
        return redirect('contactDesignation' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('contactDesignation?' . $url);
    }

}
