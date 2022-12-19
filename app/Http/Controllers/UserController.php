<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\UserGroup;
use App\Department;
use App\Designation;
use App\Branch;
use App\Buyer;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct() {
        Validator::extend('complexPassword', function($attribute, $value, $parameters) {
            $password = $parameters[1];

            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
                return true;
            }
            return false;
        });
    }

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;

        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('short_name', 'id')->toArray();

        $targetArr = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->join('branch', 'branch.id', '=', 'users.branch_id')
                ->select('user_group.name as group_name', 'users.group_id'
                        , 'users.id', 'users.first_name', 'users.last_name'
                        , 'users.username', 'users.photo', 'users.status', 'designation.title as designation_name', 'branch.name as branch_name'
                        , 'department.name as department_name', 'users.employee_id', 'users.allowed_for_sales'
                        , 'users.authorised_for_realization_price', 'users.allowed_for_crm', 'users.for_crm_leader'
                        , 'users.allowed_to_view_quotation', 'users.allowed_for_messaging'
                )
                ->orderBy('users.group_id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = User::select('username')->orderBy('group_id', 'asc')->get();
        $userDepartmentOption = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $status = array('0' => __('label.SELECT_STATUS_OPT')) + ['1' => __('label.ACTIVE'), '2' => __('lang.INACTIVE')];

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('users.username', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->user_group)) {
            $targetArr = $targetArr->where('users.group_id', '=', $request->user_group);
        }
        if (!empty($request->department)) {
            $targetArr = $targetArr->where('users.department_id', '=', $request->department);
        }
        if (!empty($request->designation)) {
            $targetArr = $targetArr->where('users.designation_id', '=', $request->designation);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('users.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/user?page=' . $page);
        }
        return view('user.index')->with(compact('qpArr', 'targetArr', 'groupList', 'departmentList', 'nameArr', 'userDepartmentOption', 'designationList', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $userGroupArr = UserGroup::orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $branchList = array('0' => __('label.SELECT_BRANCH_OPT')) + Branch::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();


        $supervisorArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->where('users.status', '1')
                        ->orderBy('users.group_id', 'asc')
                        ->select(DB::raw("CONCAT(users.employee_id,'-',users.first_name,' ',users.last_name,' (',designation.short_name,')') AS name"), 'users.id')
                        ->pluck('users.name', 'users.id')->toArray();
        $supervisorList = array('0' => __('label.SELECT_SUPERVISOR_OPT')) + $supervisorArr;


        return view('user.create')->with(compact('qpArr', 'groupList', 'departmentList', 'designationList'
                                , 'branchList', 'supervisorList'));
    }

    public function store(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'branch_id' => 'required|not_in:0',
            'employee_id' => 'required|unique:users',
            'username' => 'required|unique:users|alpha_num',
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required|same:password'
        ];


        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect('user/create' . $pageNumber)
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        //image crop image and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            $imgName = Auth::user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }


        $target = new User;
        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->branch_id = $request->branch_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->supervisor_id = $request->supervisor_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        $target->password = Hash::make($request->password);
        $target->photo = !empty($imgName) ? $imgName : '';
        $target->status = $request->status;
        $target->allowed_for_sales = !empty($request->allowed_for_sales) ? $request->allowed_for_sales : '0';
        $target->authorised_for_realization_price = !empty($request->authorised_for_realization_price) ? $request->authorised_for_realization_price : '0';
        $target->allowed_to_view_quotation = !empty($request->allowed_to_view_quotation) ? $request->allowed_to_view_quotation : '0';
        $target->allowed_for_messaging = !empty($request->allowed_for_messaging) ? $request->allowed_for_messaging : '0';
        $target->allowed_for_crm = !empty($request->allowed_for_crm) ? $request->allowed_for_crm : '0';
        $target->for_crm_leader = '0';
        if (!empty($request->allowed_for_crm)) {
            $target->for_crm_leader = !empty($request->for_crm_leader) ? $request->for_crm_leader : '0';
        }

        //Make One User as CRM Leader
        if (!empty($request->for_crm_leader)) {
            $prevLeader = User::where('for_crm_leader', '1')->first();
        }

        if ($target->save()) {
            if (!empty($request->for_crm_leader)) {
                //IF Alreday Any User Assigned as CRM Leader
                if (!empty($prevLeader)) {
                    User::where('id', $prevLeader->id)->update(['for_crm_leader' => '0']);
                }
            }
            Session::flash('success', __('label.USER_CREATED_SUCCESSFULLY'));
            return redirect('user');
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_CREATED'));
            return redirect('user/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = User::find($id);
        $buyer = Buyer::select('id')->where('user_id', $id)->first();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('user');
        }
        if (!empty($buyer->id)) {
            Session::flash('error', trans('label.THIS_IS_USER_CANNOT_BE_EDITED'));
            return redirect('user');
        }

        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $branchList = array('0' => __('label.SELECT_BRANCH_OPT')) + Branch::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $supervisorArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->where('users.status', '1')
                        ->orderBy('users.group_id', 'asc')
                        ->select(DB::raw("CONCAT(users.employee_id,'-',users.first_name,' ',users.last_name,' (',designation.short_name,')') AS name"), 'users.id')
                        ->pluck('users.name', 'users.id')->toArray();
        $supervisorList = array('0' => __('label.SELECT_SUPERVISOR_OPT')) + $supervisorArr;

        return view('user.edit')->with(compact('target', 'qpArr', 'groupList', 'departmentList'
                                , 'designationList', 'branchList', 'supervisorList'));
    }

    public function update(Request $request, $id) {
        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'branch_id' => 'required|not_in:0',
            'employee_id' => 'required|unique:users,employee_id,' . $id,
            'username' => 'required|alpha_num|unique:users,username,' . $id,
            'conf_password' => 'same:password',
        ];


        if (!empty($request->password)) {
            $rules['password'] = 'complex_password:,' . $request->password;
            $rules['conf_password'] = 'same:password';
        }

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,gif,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('user/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }
        //image resize and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            if (!empty($target->photo)) {

                $prevfileName = 'public/uploads/user/' . $target->photo;

                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
            }

            $imgName = Auth::user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }


        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->branch_id = $request->branch_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->supervisor_id = $request->supervisor_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        if (!empty($request->password)) {
            $target->password = Hash::make($request->password);
        }
        $target->photo = !empty($imgName) ? $imgName : $target->photo;
        $target->status = $request->status;
        $target->allowed_for_sales = !empty($request->allowed_for_sales) ? $request->allowed_for_sales : '0';
        $target->authorised_for_realization_price = !empty($request->authorised_for_realization_price) ? $request->authorised_for_realization_price : '0';
        $target->allowed_for_crm = !empty($request->allowed_for_crm) ? $request->allowed_for_crm : '0';
        $target->allowed_to_view_quotation = !empty($request->allowed_to_view_quotation) ? $request->allowed_to_view_quotation : '0';
        $target->allowed_for_messaging = !empty($request->allowed_for_messaging) ? $request->allowed_for_messaging : '0';
        $target->for_crm_leader = '0';
        if (!empty($request->allowed_for_crm)) {
            $target->for_crm_leader = !empty($request->for_crm_leader) ? $request->for_crm_leader : '0';
        }

        //Make One User as CRM Leader
        if (!empty($request->for_crm_leader)) {
            $prevLeader = User::where('for_crm_leader', '1')->first();
        }
        if ($target->save()) {
            //IF Alreday Any User Assigned as CRM Leader
            if (!empty($request->for_crm_leader)) {
                if (!empty($prevLeader)) {
                    User::where('id', $prevLeader->id)->update(['for_crm_leader' => '0']);
                }
            }
            Session::flash('success', __('label.USER_UPDATED_SUCCESSFULLY'));
            return redirect('user' . $pageNumber);
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_UPDATED'));
            return redirect('user/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = User::find($id);
        $buyer = Buyer::select('id')->where('user_id', $id)->first();

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        if (!empty($buyer->id)) {
            Session::flash('error', __('label.THIS_IS_USER_CANNOT_BE_DELETED'));
        }

        //dependency
//dependency check
        $dependencyArr = [
            'User' => ['1' => 'created_by', '2' => 'updated_by', '3' => 'supervisor_id'],
            'Bank' => ['1' => 'created_by', '2' => 'updated_by'],
            'Branch' => ['1' => 'created_by', '2' => 'updated_by'],
            'Buyer' => ['1' => 'created_by', '2' => 'updated_by'],
            'BuyerCategory' => ['1' => 'created_by', '2' => 'updated_by'],
            'BuyerFactory' => ['1' => 'created_by', '2' => 'updated_by'],
            'BuyerToGsmVolume' => ['1' => 'created_by', '2' => 'updated_by'],
            'BuyerToProduct' => ['1' => 'created_by'],
            'CauseOfDeliveryFailure' => ['1' => 'created_by', '2' => 'updated_by'],
            'Certificate' => ['1' => 'created_by', '2' => 'updated_by'],
            'CommissionSetup' => ['1' => 'updated_by'],
            'CompanyInformation' => ['1' => 'created_by', '2' => 'updated_by'],
            'ContactDesignation' => ['1' => 'created_by', '2' => 'updated_by'],
            'Delivery' => ['1' => 'created_by'],
            'Department' => ['1' => 'created_by', '2' => 'updated_by'],
            'Designation' => ['1' => 'created_by', '2' => 'updated_by'],
            'FinishedGoods' => ['1' => 'created_by', '2' => 'updated_by'],
            'FollowUpHistory' => ['1' => 'updated_by'],
            'Grade' => ['1' => 'created_by', '2' => 'updated_by'],
            'Lead' => [
                '1' => 'created_by', '2' => 'updated_by'
                , '3' => 'salespersons_id', '4' => 'order_confirmed_by'
                , '5' => 'order_cancelled_by', '6' => 'order_accomplished_by'
                , '7' => 'inquiry_cancelled_updated_by', '8' => 'inquiry_confirm_updated_by'
            ],
            'Invoice' => ['1' => 'updated_by'],
            'KonitaBankAccount' => ['1' => 'created_by', '2' => 'updated_by'],
            'MeasureUnit' => ['1' => 'created_by', '2' => 'updated_by'],
            'PaymentTerm' => ['1' => 'created_by', '2' => 'updated_by'],
            'PreCarrier' => ['1' => 'created_by', '2' => 'updated_by'],
            'PreCarrier' => ['1' => 'created_by', '2' => 'updated_by'],
            'Product' => ['1' => 'created_by', '2' => 'updated_by'],
            'ProductCategory' => ['1' => 'created_by', '2' => 'updated_by'],
            'ProductPricing' => ['1' => 'updated_by'],
            'ProductPricingHistory' => ['1' => 'updated_by'],
            'ProductTechDataSheet' => ['1' => 'created_by'],
            'ProductToBrand' => ['1' => 'created_by'],
            'ProductToGrade' => ['1' => 'created_by'],
            'RwBreakdown' => ['1' => 'update_by'],
            'RwUnit' => ['1' => 'created_by', '2' => 'updated_by'],
            'SalesPersonToBuyer' => ['1' => 'created_by', '2' => 'sales_person_id'],
            'SalesPersonToProduct' => ['1' => 'created_by', '2' => 'sales_person_id'],
            'SupplierToProduct' => ['1' => 'created_by'],
            'SalesTarget' => ['1' => 'created_by', '2' => 'sales_person_id'],
            'ShippingLine' => ['1' => 'created_by', '2' => 'updated_by'],
            'ShippingTerm' => ['1' => 'created_by', '2' => 'updated_by'],
            'SignatoryInfo' => ['1' => 'updated_by'],
            'Supplier' => ['1' => 'created_by', '2' => 'updated_by'],
            'SupplierClassification' => ['1' => 'created_by', '2' => 'updated_by'],
            'UserWiseQuotationReq' => ['1' => 'user_id', '2' => 'updated_by', '3' => 'read_by'],
            'UserWiseBuyerMessage' => ['1' => 'user_id', '2' => 'updated_by', '3' => 'read_by'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('user' . $pageNumber);
                }
            }
        }
        //end :: dependency check

        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            Session::flash('error', __('label.USER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_DELETED'));
        }
        return redirect('user' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&user_group=' . $request->user_group . '&department=' . $request->department . '&designation=' . $request->designation . '&status=' . $request->status;
        return Redirect::to('user?' . $url);
    }

    public function changePassword() {
        return view('user.changePassword');
    }

    public function updatePassword(Request $request) {
        $target = User::find(Auth::user()->id);

        $rules = [
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required',
        ];
        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('changePassword')
                            ->withInput($request->except('current_password', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        $target->password = Hash::make($request->password);
        if ($target->save()) {
            Session::flash('success', __('label.PASSWORD_UPDATED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PASSWORD_COULD_NOT_BE_UPDATED'));
        }
        return view('user.changePassword');
    }

    public function setRecordPerPage(Request $request) {
        $referrerArr = explode('?', URL::previous());
        $queryStr = '';
        if (!empty($referrerArr[1])) {
            $queryParam = explode('&', $referrerArr[1]);
            foreach ($queryParam as $item) {
                $valArr = explode('=', $item);
                if ($valArr[0] != 'page') {
                    $queryStr .= $item . '&';
                }
            }
        }

        $url = $referrerArr[0] . '?' . trim($queryStr, '&');

        if ($request->record_per_page > 999) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_LESS_THAN_999'));
            return redirect($url);
        }

        if ($request->record_per_page < 1) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_GREATER_THAN_1'));
            return redirect($url);
        }

        $request->session()->put('paginatorCount', $request->record_per_page);
        return redirect($url);
    }

    public function getCheckCrmLeader(Request $request) {
        $target = User::where('for_crm_leader', '1')->first();
        $name = $target->first_name . ' ' . $target->last_name;
        return response()->json(['name' => $name]);
    }

}
