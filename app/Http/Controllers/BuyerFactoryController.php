<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer; //model class
use App\BuyerFactory; //model class
use App\Designation; //model class
use App\ContactDesignation; //model class
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Illuminate\Http\Request;

class BuyerFactoryController extends Controller {

    public function index(Request $request) {
//passing param for custom function
        $qpArr = $request->all();
        $buyerArr = array('0' => __('label.SELECT_BUYER_OPT')) + Buyer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $targetArr = BuyerFactory::leftJoin('buyer', 'buyer.id', '=', 'buyer_factory.buyer_id')
                        ->select('buyer_factory.*', 'buyer.name as buyer')->orderBy('buyer_factory.id', 'desc');

//begin filtering
        $searchText = $request->search;
        $nameArr = BuyerFactory::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('buyer_factory.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->buyer_id)) {
            $targetArr = $targetArr->where('buyer_factory.buyer_id', '=', $request->buyer_id);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('buyer_factory.status', '=', $request->status);
        }


//end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/buyerFactory?page=' . $page);
        }


        return view('buyerFactory.index')->with(compact('qpArr', 'targetArr', 'buyerArr', 'nameArr', 'status'));
    }

    public function create(Request $request) {
//passing param for custom function
        $qpArr = $request->all();

        $buyerArr = array('0' => __('label.SELECT_BUYER_OPT')) + Buyer::where('status', '1')->orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        return view('buyerFactory.create')->with(compact('qpArr', 'buyerArr', 'designationList'));
    }

    public function store(Request $request) {
//passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:buyer_factory',
            'buyer_id' => 'required|not_in:0',
            'address' => 'required',
        ];

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';


                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $contactPhone = $request->contact_phone;
                if (!empty($contactPhone[$key])) {
                    $row2 = 0;
                    foreach ($contactPhone[$key] as $key2 => $name) {
                        $rules['contact_phone.' . $key . '.' . $key2] = 'required';
                        $message['contact_phone.' . $key . '.' . $key2 . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_THIS_BLOCK_NO_IN_THIS_ROW_NO', ['block' => ($row2 + 1), 'row' => ($row + 1)]);
                        $row2++;
                    }
                }
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $contactPersonDataArr = [];
//Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $uniqueKey => $name) {
                $contactPersonDataArr[$uniqueKey]['name'] = $name;
                $contactPersonDataArr[$uniqueKey]['designation_id'] = !empty($request->designation_id[$uniqueKey]) ? $request->designation_id[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['email'] = !empty($request->contact_email[$uniqueKey]) ? $request->contact_email[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['phone'] = !empty($request->contact_phone[$uniqueKey]) ? $request->contact_phone[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['note'] = !empty($request->special_note[$uniqueKey]) ? $request->special_note[$uniqueKey] : '';
            }
        }

        $target = new BuyerFactory;
        $target->buyer_id = $request->buyer_id;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->primary_factory = !empty($request->primary_factory) ? $request->primary_factory : '0';
        $target->gmap_embed_code = $request->gmap_embed_code;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;
//Make One Factory Primary
        if (!empty($request->primary_factory)) {
            BuyerFactory::where('buyer_id', $request->buyer_id)->where('primary_factory', '1')->update(['primary_factory' => '0']);
        }
        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYER_FACTORY_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYER_NOT_BE_CREATED')], 401);
        }
    }

    public function edit(Request $request, $id) {
//passing param for custom function
        $qpArr = $request->all();

        $target = BuyerFactory::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('buyerFactory');
        }

        $buyerArr = array('0' => __('label.SELECT_BUYER_OPT')) + Buyer::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $prevContactPersonArr = json_decode($target->contact_person_data, true);

        return view('buyerFactory.edit')->with(compact('qpArr', 'target', 'buyerArr', 'designationList', 'prevContactPersonArr'));
    }

    public function update(Request $request) {
        $target = BuyerFactory::find($request->id);

//begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
//end back same page after update
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:buyer_factory,name,' . $request->id,
            'buyer_id' => 'required|not_in:0',
            'address' => 'required',
        ];

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $contactPhone = $request->contact_phone;
                if (!empty($contactPhone[$key])) {
                    $row2 = 0;
                    foreach ($contactPhone[$key] as $key2 => $name) {
                        $rules['contact_phone.' . $key . '.' . $key2] = 'required';
                        $message['contact_phone.' . $key . '.' . $key2 . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_THIS_BLOCK_NO_IN_THIS_ROW_NO', ['block' => ($row2 + 1), 'row' => ($row + 1)]);
                        $row2++;
                    }
                }
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $contactPersonDataArr = [];
//Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $identifier => $name) {
                $contactPersonDataArr[$identifier]['name'] = $name;
                $contactPersonDataArr[$identifier]['designation_id'] = !empty($request->designation_id[$identifier]) ? $request->designation_id[$identifier] : '';
                $contactPersonDataArr[$identifier]['email'] = !empty($request->contact_email[$identifier]) ? $request->contact_email[$identifier] : '';
                $contactPersonDataArr[$identifier]['phone'] = !empty($request->contact_phone[$identifier]) ? $request->contact_phone[$identifier] : '';
                $contactPersonDataArr[$identifier]['note'] = !empty($request->special_note[$identifier]) ? $request->special_note[$identifier] : '';
            }
        }

        $target->buyer_id = $request->buyer_id;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->primary_factory = !empty($request->primary_factory) ? $request->primary_factory : '0';
        $target->gmap_embed_code = $request->gmap_embed_code;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;
//Make One Factory Primary
        if (!empty($request->primary_factory)) {
            BuyerFactory::where('buyer_id', $request->buyer_id)->where('primary_factory', '1')->update(['primary_factory' => '0']);
        }

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BUYER_FACTORY_UPDATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BUYER_FACTORY_NOT_BE_UPDATED')], 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = BuyerFactory::find($id);

//begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
//end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        //Dependency
        $dependencyArr = [
            'Lead' => ['1' => 'factory_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('buyerFactory' . $pageNumber);
                }
            }
        }

        //END OF Dependency

        if ($target->delete()) {
            Session::flash('error', __('label.BUYER_FACTORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BUYER_FACTORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('buyerFactory' . $pageNumber);
    }

    public function newContactPersonToCreate() {
        return Common::buyerContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::buyerContactPerson();
    }

    public function getBuyerPrimaryFactoryCreate(Request $request) {
        return Common::checkPrimaryFactory($request);
    }

    public function getBuyerPrimaryFactoryEdit(Request $request) {
        return Common::checkPrimaryFactory($request);
    }

    public function getDetailsOfContactPerson(Request $request) {
        $target = BuyerFactory::find($request->factory_id);
        $factoryName = $target->name;
        $contactPersonArr = json_decode($target->contact_person_data, true);
        $view = view('buyerFactory.showContactPersonDetails', compact('contactPersonArr', 'request', 'factoryName'))->render();
        return response()->json(['html' => $view]);
    }

    public function showLocationView(Request $request) {
        $target = BuyerFactory::find($request->factory_id);
        $view = view('buyerFactory.showMapView', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&buyer_id=' . $request->buyer_id . '&status=' . $request->status;
        return Redirect::to('buyerFactory?' . $url);
    }

    public function getBuyerName(Request $request) {
        $target = Buyer::select('name')->where('id', $request->buyer_id)->first();
        $buyerName = '';
        if (!empty($target)) {
            $buyerName = $target->name;
        }

        return response()->json(['html' => $buyerName]);
    }

    public function addPhoneNumber(Request $request) {

        $view = view('buyerFactory.addPhoneNumber', compact('request'))->render();
        return response()->json(['html' => $view]);
    }

}
