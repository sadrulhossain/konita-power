<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier; //model class
use App\SupplierClassification; //model class
use App\Country; //model class
use App\ContactDesignation; //model class
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Illuminate\Http\Request;

class SupplierController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $supplierClassificationArr = array('0' => __('label.SELECT_SUPPLIER_CLASSIFICATION_OPT')) + SupplierClassification::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $targetArr = Supplier::leftJoin('supplier_classification', 'supplier_classification.id', '=', 'supplier.supplier_classification_id')
                        ->leftJoin('country', 'country.id', '=', 'supplier.country_id')
                        ->select('supplier.*', 'supplier_classification.name as supplier_classification', 'country.name as country')->orderBy('supplier.name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Supplier::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('supplier.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->supplier_classification_id)) {
            $targetArr = $targetArr->where('supplier.supplier_classification_id', '=', $request->supplier_classification_id);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('supplier.status', '=', $request->status);
        }


        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/supplier?page=' . $page);
        }


        return view('supplier.index')->with(compact('qpArr', 'targetArr', 'supplierClassificationArr', 'nameArr', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $supplierClassificationArr = array('0' => __('label.SELECT_SUPPLIER_CLASSIFICATION_OPT')) + SupplierClassification::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = array('0' => __('label.SELECT_COUNTRY_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        return view('supplier.create')->with(compact('qpArr', 'supplierClassificationArr', 'countryList', 'designationList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = $message = array();
        $rules = [
            'name' => 'required',
            'country_id' => 'required|not_in:0',
            'sign_off_date' => 'required',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        if (!empty($request->pi_required)) {
            $rules['header_image'] = 'required|max:2048|mimes:jpeg,png,jpg';
            $rules['signature_image'] = 'required|max:1024|mimes:jpeg,png,jpg';
        }

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                //set messages for error

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if ($request->hasFile('contact_photo')) {
            foreach ($request->contact_photo as $key => $photo) {
                $rules['contact_photo.' . $key] = 'max:1024|mimes:jpeg,png,jpg';
                $index = array_search($key, array_keys($request->contact_photo));
                $message['contact_photo.' . $key . '.mimes'] = __('label.INVALID_FILE_FORMAT_FOR_ROW_NO') . ($index + 1);
            }
        }

        //Validation Rules for FSC Certification
        if (!empty($request->fsc_certified)) {
            if ($request->file('fsc_attachment')) {
                $rules['fsc_attachment'] = 'max:' . __('label.FILE_SIZE') . '|mimes:pdf';
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //logo upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/supplier', $logoName);
        }

        $headerImageName = $signatureImage = '';
        $defaultFormat = '0';
        if (!empty($request->pi_required)) {
            //upload header image
            $headerImage = $request->file('header_image');
            if (!empty($headerImage)) {
                $headerImageName = uniqid() . "_" . Auth::user()->id . "." . $headerImage->getClientOriginalExtension();
                $headerImage->move('public/uploads/supplier/PIFormat/headerImage', $headerImageName);
            }

            //upload signature image
            $signatureImage = $request->file('signature_image');
            if (!empty($signatureImage)) {
                $signatureImageName = uniqid() . "_" . Auth::user()->id . "." . $signatureImage->getClientOriginalExtension();
                $signatureImage->move('public/uploads/supplier/PIFormat/signatureImage', $signatureImageName);
            }

            $defaultFormat = $request->default_format ?? '0';
        }

        $photoArr = $request->file('contact_photo');
        $data = [];
        //Make Contact Person Photo Array Unified
        if ($request->hasFile('contact_photo')) {
            if (!empty($photoArr)) {
                foreach ($photoArr as $key => $fileName) {
                    $photoName = uniqid() . "_" . Auth::user()->id . "." . $fileName->getClientOriginalExtension();
                    $fileName->move('public/uploads/supplier/contact_person', $photoName);
                    $data[$key]['contact_photo'] = $photoName;
                }
            }
        }

        $contactPersonDataArr = [];
        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $uniqueKey => $name) {
                $contactPersonDataArr[$uniqueKey]['name'] = $name;
                $contactPersonDataArr[$uniqueKey]['designation_id'] = !empty($request->designation_id[$uniqueKey]) ? $request->designation_id[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['email'] = !empty($request->contact_email[$uniqueKey]) ? $request->contact_email[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['phone'] = !empty($request->contact_phone[$uniqueKey]) ? $request->contact_phone[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['introduction_date'] = !empty($request->first_introduction_date[$uniqueKey]) ? Helper::formatDate($request->first_introduction_date[$uniqueKey]) : '';
                $contactPersonDataArr[$uniqueKey]['note'] = !empty($request->contact_note[$uniqueKey]) ? $request->contact_note[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['photo'] = !empty($data[$uniqueKey]['contact_photo']) ? $data[$uniqueKey]['contact_photo'] : '';
            }
        }

        $target = new Supplier;
        $target->supplier_classification_id = $request->supplier_classification_id;
        $target->country_id = $request->country_id;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->address = $request->address;
        $target->logo = !empty($logoName) ? $logoName : null;
        $target->sign_off_date = Helper::dateFormatConvert($request->sign_off_date);
        $target->pi_required = $request->pi_required ?? '0';
        $target->header_image = $headerImageName ?? null;
        $target->signature_image = $signatureImageName ?? null;
        $target->default_format = $defaultFormat;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->fsc_certified = !empty($request->fsc_certified) ? $request->fsc_certified : '0';
        $target->status = $request->status;

        //save file if certified
        if (!empty($request->fsc_certified)) {
            $file = $request->file('fsc_attachment');
            if ($file) {
                $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/supplierFscCertificate', $fileName);
            }
        }
        $target->fsc_attachment = !empty($fileName) ? $fileName : null;



        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SUPPLIER_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SUPPLIER_NOT_BE_CREATED')], 401);
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();

        $target = Supplier::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('supplier');
        }

        $supplierClassificationArr = array('0' => __('label.SELECT_SUPPLIER_CLASSIFICATION_OPT')) + SupplierClassification::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = array('0' => __('label.SELECT_COUNTRY_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $prevContactPersonArr = json_decode($target->contact_person_data, true);

        return view('supplier.edit')->with(compact('qpArr', 'target', 'supplierClassificationArr', 'countryList', 'designationList', 'prevContactPersonArr'));
    }

    public function update(Request $request) {
        //sleep(3);
//        echo '<pre>';
//        print_r($request->all());
//        exit;

        $target = Supplier::find($request->id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:supplier,name,' . $request->id,
            'country_id' => 'required|not_in:0',
            'sign_off_date' => 'required',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        if (!empty($request->pi_required)) {
            if (empty($target->header_image)) {
                $rules['header_image'] = 'required|max:2048|mimes:jpeg,png,jpg';
            }
            if (empty($target->signature_image)) {
                $rules['signature_image'] = 'required|max:1024|mimes:jpeg,png,jpg';
            }
        }

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);

                $row++;
            }
        }

        if ($request->hasFile('contact_photo')) {
            foreach ($request->contact_photo as $key => $photo) {
                $rules['contact_photo.' . $key] = 'max:1024|mimes:jpeg,png,jpg';
                $index = array_search($key, array_keys($request->contact_photo));
                $message['contact_photo.' . $key . '.mimes'] = __('label.INVALID_FILE_FORMAT_FOR_ROW_NO') . ($index + 1);
            }
        }

        //Validation Rules for FSC Certification
        if (!empty($request->fsc_certified)) {
            if ($request->file('fsc_attachment')) {
                $rules['fsc_attachment'] = 'max:' . __('label.FILE_SIZE') . '|mimes:pdf';
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/supplier/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        //logo upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/supplier', $logoName);
        }

        $headerImageName = $signatureImage = '';
        $defaultFormat = '0';
        if (!empty($request->pi_required)) {

            //upload header image
            $headerImage = $request->file('header_image');
            if (!empty($headerImage)) {
                $prevHeaderImageName = 'public/uploads/supplier/PIFormat/headerImage/' . $target->header_image;

                if (File::exists($prevHeaderImageName)) {
                    File::delete($prevHeaderImageName);
                }
                $headerImageName = uniqid() . "_" . Auth::user()->id . "." . $headerImage->getClientOriginalExtension();
                $headerImage->move('public/uploads/supplier/PIFormat/headerImage', $headerImageName);
            }
            
            //upload signature image
            $signatureImage = $request->file('signature_image');
            if (!empty($signatureImage)) {
                $prevSignatureImageName = 'public/uploads/supplier/PIFormat/signatureImage/' . $target->signature_image;

                if (File::exists($prevSignatureImageName)) {
                    File::delete($prevSignatureImageName);
                }
                $signatureImageName = uniqid() . "_" . Auth::user()->id . "." . $signatureImage->getClientOriginalExtension();
                $signatureImage->move('public/uploads/supplier/PIFormat/signatureImage', $signatureImageName);
            }

            $defaultFormat = $request->default_format ?? '0';
        } else {
            if (!empty($target->header_image)) {
                $prevHeaderImageName = 'public/uploads/supplier/PIFormat/headerImage/' . $target->header_image;

                if (File::exists($prevHeaderImageName)) {
                    File::delete($prevHeaderImageName);
                }
            }
            if (!empty($target->signature_image)) {
                $prevSignatureImageName = 'public/uploads/supplier/PIFormat/signatureImage/' . $target->signature_image;

                if (File::exists($prevSignatureImageName)) {
                    File::delete($prevSignatureImageName);
                }
            }
        }

        $photoArr = $request->file('contact_photo');
        $data = [];
        //Make Contact Person Photo Array Unified
        if ($request->hasFile('contact_photo')) {
            if (!empty($photoArr)) {
                foreach ($photoArr as $uniqIndex => $fileName) {
                    //START::previous file delete from folder
                    $prevfileName = 'public/uploads/supplier/contact_person/' . $request->prev_contact_photo[$uniqIndex];

                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                    //END::previous file delete from folder
                    //New Photo added here
                    $fileNames = uniqid() . "_" . Auth::user()->id . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $fileName->move('public/uploads/supplier/contact_person', $fileNames);
                    $data[$uniqIndex]['contact_photo'] = $fileNames;
                }
            }
        }
        $contactPersonDataArr = [];
        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $identifier => $name) {
                $contactPersonDataArr[$identifier]['name'] = $name;
                $contactPersonDataArr[$identifier]['designation_id'] = !empty($request->designation_id[$identifier]) ? $request->designation_id[$identifier] : '';
                $contactPersonDataArr[$identifier]['email'] = !empty($request->contact_email[$identifier]) ? $request->contact_email[$identifier] : '';
                $contactPersonDataArr[$identifier]['phone'] = !empty($request->contact_phone[$identifier]) ? $request->contact_phone[$identifier] : '';
                $contactPersonDataArr[$identifier]['introduction_date'] = !empty($request->first_introduction_date[$identifier]) ? Helper::formatDate($request->first_introduction_date[$identifier]) : '';
                $contactPersonDataArr[$identifier]['note'] = !empty($request->contact_note[$identifier]) ? $request->contact_note[$identifier] : '';
                $contactPersonDataArr[$identifier]['photo'] = !empty($data[$identifier]['contact_photo']) ? $data[$identifier]['contact_photo'] : (!empty($request->prev_contact_photo[$identifier]) ? $request->prev_contact_photo[$identifier] : '');
            }
        }

        $target->supplier_classification_id = $request->supplier_classification_id;
        $target->country_id = $request->country_id;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->address = $request->address;
        $target->logo = !empty($logoName) ? $logoName : $target->logo;
        $target->sign_off_date = Helper::dateFormatConvert($request->sign_off_date);
        $target->pi_required = $request->pi_required ?? '0';
        
        if(!empty($headerImageName)){
            $target->header_image = $headerImageName;
        }else{
            $target->header_image = $target->header_image ?? null;
        }
        
        if(!empty($signatureImageName)){
            $target->signature_image = $signatureImageName;
        }else{
            $target->signature_image = $target->signature_image ?? null;
        }
        
        $target->default_format = $defaultFormat;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->fsc_certified = !empty($request->fsc_certified) ? $request->fsc_certified : '0';
        $target->status = $request->status;

        //save file if certified	
        if (!empty($request->fsc_certified) && !empty($request->file('fsc_attachment'))) {
            $file = $request->file('fsc_attachment');
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/supplierFscCertificate', $fileName);
        }
        $target->fsc_attachment = !empty($request->fsc_certified) ? !empty($fileName) ? $fileName : $target->fsc_attachment : null;


        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SUPPLIER_UPDATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SUPPLIER_NOT_BE_UPDATED')], 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Supplier::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        //Dependency
        $dependencyArr = [
            'SupplierToProduct' => ['1' => 'supplier_id'],
            'Lead' => ['1' => 'supplier_id'],
            'Invoice' => ['1' => 'supplier_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('supplier' . $pageNumber);
                }
            }
        }


        //END OF Dependency


        $fileName = 'public/uploads/supplier/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        //Code for Delete Contact Person Images from Base Folder
        $prevContactPersonArr = json_decode($target->contact_person_data, true);
        if (!empty($prevContactPersonArr)) {
            foreach ($prevContactPersonArr as $identifier => $contactPersonData) {
                $fileName = 'public/uploads/supplier/contact_person/' . $contactPersonData['photo'];
                if (File::exists($fileName)) {
                    File::delete($fileName);
                }
            }
        }

        //If Previous FSC File Attached and Moved to public folder,Please Delete it from public folder
        $prevfileName = 'public/uploads/supplierFscCertificate/' . $target->fsc_attachment;
        if (File::exists($prevfileName)) {
            File::delete($prevfileName);
        }//Endif


        if ($target->delete()) {
            Session::flash('error', __('label.SUPPLIER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SUPPLIER_COULD_NOT_BE_DELETED'));
        }
        return redirect('supplier' . $pageNumber);
    }

    public function newContactPersonToCreate() {
        return Common::newContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::newContactPerson();
    }

    public function getDetailsOfContactPerson(Request $request) {
        $target = Supplier::find($request->supplier_id);
        $supplierName = $target->name;
        $contactPersonArr = json_decode($target->contact_person_data, true);
        $view = view('supplier.showContactPersonDetails', compact('contactPersonArr', 'request', 'supplierName'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&supplier_classification_id=' . $request->supplier_classification_id . '&status=' . $request->status;
        return Redirect::to('supplier?' . $url);
    }
    
    //****************************** start :: buyer profile ********************************//
    public function profile(Request $request, $id) {
        $loadView = 'supplier.profile.show';
        return Common::supplierProfile($request, $id, $loadView);
    }

    public function printProfile(Request $request, $id) {
        $loadView = 'supplier.profile.print.show';
        $modueId = 13;
        return Common::supplierPrintProfile($request, $id, $loadView, $modueId);
    }

    public static function getInvolvedOrderList(Request $request) {
        $loadView = 'supplier.profile.showInvolvedOrderList';
        return Common::getSupplierInvolvedOrderList($request, $loadView);
    }

    public static function printInvolvedOrderList(Request $request) {
        $loadView = 'supplier.profile.print.showInvolvedOrderList';
        $modueId = 13;
        return Common::printSupplierInvolvedOrderList($request, $loadView, $modueId);
    }

    //****************************** end :: buyer profile *********************************//

}
