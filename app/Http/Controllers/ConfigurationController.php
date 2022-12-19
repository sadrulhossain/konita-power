<?php

namespace App\Http\Controllers;

use Validator;
use App\KonitaBankAccount;
use App\SignatoryInfo;
use App\CompanyInformation;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use File;
use Image;
use Illuminate\Http\Request;

class ConfigurationController extends Controller {

    private $controller = 'Configuration';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SignatoryInfo::select('signatory_info.*');
        $target = SignatoryInfo::select('id', 'name', 'designation', 'seal')->first();
        $companyInfo = CompanyInformation::select('*')->first();


        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/configuration?page=' . $page);
        }
        return view('configuration.index')->with(compact('targetArr', 'qpArr', 'target', 'companyInfo'));
    }

    public function store(Request $request) {
        $qpArr = $request->all();
        $target = SignatoryInfo::select('id', 'name', 'designation', 'seal')->first();
        $target = SignatoryInfo::where('id', $request->id)->first();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //validation
        $rules = [
            'name' => 'required',
            'designation' => 'required',
        ];
        if (empty($request->seal) && empty($target->seal)) {
            $rules['seal'] = 'required';
        }
        if (!empty($request->seal)) {
            $rules['seal'] = 'required|mimes:png,jpg,jpeg|max:500';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation
        $file = $request->file('seal');

        if (!empty($target->seal)) {

            $prevfileName = 'public/img/signatoryInfo/' . $target->seal;

            if (!empty($file)) {
                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
            }
        }

        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/img/signatoryInfo', $fileName);
        }
        if (empty($target)) {
            $target = new SignatoryInfo;
        }

        $target->name = $request->name;
        $target->designation = $request->designation;
        $target->seal = !empty($fileName) ? $fileName : $target->seal;

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.BANK_INFO_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BANK_INFO_COULD_NOT_BE_CREATED_SUCCESSFULLY')], 401);
        }
    }

    public function newPhoneNumberRow(Request $request) {

        $view = view('configuration.addPhoneNumber')->render();
        return response()->json(['html' => $view]);
    }

    public function saveCompanyInfo(Request $request) {
        $qpArr = $request->all();

        $target = CompanyInformation::select('*')->first();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //validation
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation
        $jsonEncodedPhoneNumber = json_encode($request->phone_number);

        if (empty($target)) {
            $target = new CompanyInformation;
        }
        $target->name = $request->name;
        $target->address = $request->address;
        $target->phone_number = $jsonEncodedPhoneNumber;
        $target->email = $request->email;
        $target->website = $request->website;
        
        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.COMPANY_INFO_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.BANK_INFO_COULD_NOT_BE_CREATED_SUCCESSFULLY')], 401);
        }
    }

}
