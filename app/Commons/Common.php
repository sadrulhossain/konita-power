<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\AclUserGroupToAccess;
use App\Product;
use App\User;
use App\Department;
use App\Branch;
use App\Country;
use App\Buyer;
use App\QuotationRequest;
use App\Division;
use App\District;
use App\Thana;
use App\BuyerFactory;
use App\SalesPersonToProduct;
use App\SalesTarget;
use App\MeasureUnit;
use App\Lead;
use App\FollowUpHistory;
use App\ContactDesignation;
use App\RwBreakdown;
use App\RwUnitConversion;
use App\RwUnit;
use App\CommissionSetup;
use App\Invoice;
use App\InquiryDetails;
use App\Brand;
use App\Grade;
use App\Delivery;
use App\DeliveryDetails;
use App\FollowupStatus;
use App\CauseOfFailure;
use App\SalesPersonToBuyer;
use App\ProductPricingHistory;
use App\ProductPricing;
use App\ProductToBrand;
use App\ProductToGrade;
use App\ShippingTrems;
use App\PoGenerate;
use App\BuyerPayment;
use App\BuyerFollowUpHistory;
use App\BuyerToProduct;
use App\BuyerToGsmVolume;
use App\BuyerMachineType;
use App\FinishedGoods;
use App\Receive;
use App\CompanyInformation;
use App\CrmOpportunity;
use App\CrmOpportunityToMember;
use App\CrmSource;
use App\CrmActivityLog;
use App\CrmActivityStatus;
use App\CrmActivityType;
use App\CrmQuotation;
use App\Supplier;
use App\SupplierClassification;
use App\SupplierToProduct;
use App\BeneficiaryBank;
use App\InvoiceCommissionHistory;
use App\CrmActivityPriority;
use App\OrderMessaging;
use App\UserWiseBuyerMessage;
use Illuminate\Http\Request;

class Common {

    public static function userAccess() {
        //ACL ACCESS LIST
        $accessGroupArr = AclUserGroupToAccess::where('group_id', Auth::user()->group_id)
                        ->select('*')->get();

        $userAccessArr = [];
        if (!$accessGroupArr->isEmpty()) {
            foreach ($accessGroupArr as $item) {
                $userAccessArr[$item->module_id][$item->access_id] = $item->access_id;
            }
        }
        //ENDOF ACL ACCESS LIST
        return $userAccessArr;
    }

    public static function groupHasRoleAccess($groupId) {
        $accessGroupArr = AclUserGroupToAccess::where('group_id', $groupId)
                        ->select('*')->get();
        if ($groupId != 1 && $accessGroupArr->isEmpty()) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getDivision(Request $request) {
        //country wise division
        $divisionArr = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $request->country_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showDivision', compact('divisionArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getDistrict(Request $request) {
        //country wise division
        $districtArr = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->division_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showDistrict', compact('districtArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getThana(Request $request) {
        //country wise division
        $thanaArr = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $request->district_id)
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $view = view('branch.showThana', compact('thanaArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function loadProductName(Request $request) {
        $query = "%" . $request->product_name . "%";
        $nameArr = Product::where('name', 'LIKE', $query)->get(['name']);

        $view = view('product.showProductName', compact('nameArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function newContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $view = view('supplier.newContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function buyerContactPerson() {
        $designationList = array('' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $view = view('buyerContactPerson.showContactPerson', compact('designationList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function checkPrimaryFactory(Request $request) {
        $target = BuyerFactory::where('buyer_id', $request->buyer_id)->where('primary_factory', '1')->first();
        return response()->json(['name' => $target->name]);
        ;
    }

    //function
    public static function setOrLockSalesTarget(Request $request, $lockStatus, $successMessage, $failureMessage) {
        //get effective data
        $effectiveDate = date("Y-m-01", strtotime($request->effective_month));


        //validation
        $rules = [
            'effective_month' => 'required',
        ];
        //Helper::pr($request->all(), 1);
        $row = 0;
        $productList = Product::pluck('name', 'id')->toArray();
        foreach ($request->quantity as $productId => $quantity) {
            $rules['quantity.' . $productId] = 'required';
            $message['quantity.' . $productId . '.required'] = __('label.QUANTITY_IS_REQUIRED_FOR') . $productList[$productId];
            $row++;
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end: validation

        foreach ($request->quantity as $productId => $quantity) {
            $setTraget[$productId]['quantity'] = isset($quantity) ? $quantity : 0;
        }
        foreach ($request->remarks as $productId => $remarks) {
            $setTraget[$productId]['remarks'] = isset($remarks) ? $remarks : '';
        }

        $target = json_encode($setTraget);
        //Helper::pr($target,1);
        $salesTarget = new SalesTarget;
        $salesTarget->sales_person_id = $request->sales_person_id;
        $salesTarget->target = $target;
        $salesTarget->effective_date = $effectiveDate;
        $salesTarget->total_quantity = $request->total_quantity;
        $salesTarget->lock_status = $lockStatus;


        SalesTarget::where('sales_person_id', $request->sales_person_id)
                ->where('effective_date', $effectiveDate)->delete();
        if ($salesTarget->save()) {
            return Response::json(array('heading' => 'Success', 'message' => $successMessage), 200);
        } else {
            return Response::json(array('success' => false, 'message' => $failureMessage), 401);
        }
    }

    //function
    public static function loadLcValue(Request $request) {
        $lead = Lead::select('quantity')->where('id', $request->lead_id)->first();

        $view = view('order.loadLcValue', compact('lead'))->render();
        return response()->json(['html' => $view]);
    }

    //update this method

    public static function getOrderDetails(Request $request, $loadView) {

        $orderInfo = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->leftJoin('bank', 'bank.id', '=', 'inquiry.bank')
                        ->select('inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no'
                                , 'inquiry.order_status', 'inquiry.note'
                                , 'inquiry.lc_transmitted_copy_done', 'buyer.name as buyer_name'
                                , 'inquiry.id', 'inquiry.purchase_order_no', 'supplier.name as supplier_name'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.creation_date', 'inquiry.confirmation_date', 'inquiry.order_cancel_remarks'
                                , 'inquiry.order_accomplish_remarks', 'inquiry.note', 'inquiry.lc_issue_date'
                                , 'bank.name as lc_opening_bank', 'inquiry.branch as bank_barnch'
                                , 'inquiry.po_date', 'inquiry.pi_date', 'inquiry.order_cancel_cause')
                        ->where('inquiry.id', $request->inquiry_id)->first();

        //inquiry details
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.id')
                ->get();

        $causeList = CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        $view = view($loadView, compact('request', 'orderInfo', 'inquiryDetails', 'causeList'))->render();
        return response()->json(['html' => $view]);
    }

    //method
    //follow up 
    public static function getFollowUpModal(Request $request, $loadView) {
        //********************** Start :: Inquiry Followup ************************//
        //get inquiry details
        $productInfo = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.id')
                ->get();

        $statusList = ['0' => __('label.SELECT_STATUS_OPT')] + FollowupStatus::where('status', '1')->pluck('name', 'id')->toArray();

        $followupStatusInfo = FollowupStatus::select('id', 'color', 'icon')->get();

        $fStatArr = [];
        if (!$followupStatusInfo->isEmpty()) {
            foreach ($followupStatusInfo as $stat) {
                $fStatArr[$stat->id]['color'] = $stat->color;
                $fStatArr[$stat->id]['icon'] = $stat->icon;
            }
        }

        //get followup history
        $followUpPrevHistory = FollowUpHistory::where('inquiry_id', $request->inquiry_id)->first();

        $finalArr = $followUpHistoryArr = [];
        if (!empty($followUpPrevHistory)) {
            $followUpHistoryArr = json_decode($followUpPrevHistory->history, true);
            krsort($followUpHistoryArr);
            $i = 0;
            if (!empty($followUpHistoryArr)) {
                foreach ($followUpHistoryArr as $followUpHistory) {
                    $followUpDate = Helper::dateFormatConvert($followUpHistory['follow_up_date']);
                    $finalArr[$followUpDate][$i]['follow_up_date'] = $followUpHistory['follow_up_date'];
                    $finalArr[$followUpDate][$i]['status'] = $followUpHistory['status'];
                    $finalArr[$followUpDate][$i]['remarks'] = $followUpHistory['remarks'];
                    $finalArr[$followUpDate][$i]['updated_by'] = $followUpHistory['updated_by'] ?? 0;
                    $finalArr[$followUpDate][$i]['updated_at'] = $followUpHistory['updated_at'] ?? '';
                    $i++;
                }
            }
        }
        krsort($finalArr);
        //********************** End :: Inquiry Followup **************************//
        //********************** Start :: Activity Log ************************//
        $opportunityInfo = Lead::join('crm_opportunity', 'crm_opportunity.id', 'inquiry.opportunity_id')
                        ->select('crm_opportunity.id as opportunity_id', 'crm_opportunity.buyer_contact_person as buyer_contact_person')
                        ->where('inquiry.id', $request->inquiry_id)->first();

        $contactPersonArr = [];
        if (!empty($opportunityInfo->buyer_contact_person)) {
            $buyerContactPersonArr = json_decode($opportunityInfo->buyer_contact_person, true);
            if (!empty($buyerContactPersonArr)) {
                foreach ($buyerContactPersonArr as $key => $contactPersonData) {
                    $contactPersonArr[$key] = $contactPersonData['name'];
                }
            }
        }
        $opportunityId = $opportunityInfo->opportunity_id ?? 0;

        $contactPersonArr = ['0' => __('label.SELECT_CONTACT_PERSON_OPT')] + $contactPersonArr;

        $activityTypeArr = ['0' => __('label.SELECT_ACTIVITY_TYPE_OPT')] + CrmActivityType::where('status', '1')->pluck('name', 'id')->toArray();
        $activityStatusArr = ['0' => __('label.SELECT_ACTIVITY_STATUS_OPT')] + CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();
        $priorityArr = ['0' => __('label.SELECT_ACTIVITY_PRIORITY_OPT')] + CrmActivityPriority::pluck('name', 'id')->toArray();


        $statusIconArr = CrmActivityStatus::pluck('icon', 'id')->toArray();
        $statusColorArr = CrmActivityStatus::pluck('color', 'id')->toArray();

        //get Activity Log History
        $activityLogPrevHistory = CrmActivityLog::where('opportunity_id', $opportunityId)->first();

        $finalLogArr = $logHistoryArr = [];
        if (!empty($activityLogPrevHistory)) {
            $logHistoryArr = json_decode($activityLogPrevHistory->log, true);
            krsort($logHistoryArr);
            $i = 0;
            if (!empty($logHistoryArr)) {
                foreach ($logHistoryArr as $activityLog) {
                    $logDate = Helper::dateFormatConvert($activityLog['date']);

                    $finalLogArr[$logDate][$i]['date'] = $activityLog['date'];
                    $finalLogArr[$logDate][$i]['activity_type'] = (!empty($activityLog['activity_type_id']) && isset($activityTypeArr[$activityLog['activity_type_id']])) ? $activityTypeArr[$activityLog['activity_type_id']] : '';
                    $finalLogArr[$logDate][$i]['status'] = (!empty($activityLog['status']) && isset($activityStatusArr[$activityLog['status']])) ? $activityStatusArr[$activityLog['status']] : '';
                    $finalLogArr[$logDate][$i]['priority'] = (!empty($activityLog['priority']) && isset($priorityArr[$activityLog['priority']])) ? $priorityArr[$activityLog['priority']] : __('label.N_A');
                    $finalLogArr[$logDate][$i]['contact_person'] = (!empty($activityLog['contact_person']) && isset($contactPersonArr[$activityLog['contact_person']])) ? $contactPersonArr[$activityLog['contact_person']] : __('label.N_A');
                    $finalLogArr[$logDate][$i]['remarks'] = $activityLog['remarks'];
                    $finalLogArr[$logDate][$i]['has_schedule'] = $activityLog['has_schedule'];
                    $finalLogArr[$logDate][$i]['schedule_date_time'] = Helper::formatDateTime($activityLog['schedule_date_time']);
                    $finalLogArr[$logDate][$i]['schedule_purpose'] = $activityLog['schedule_purpose'];
                    $finalLogArr[$logDate][$i]['updated_by'] = $activityLog['updated_by'] ?? 0;
                    $finalLogArr[$logDate][$i]['updated_at'] = $activityLog['updated_at'] ?? '';
                    $finalLogArr[$logDate][$i]['ribbon'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'ribbon-color-' . $statusColorArr[$activityLog['status']] : '';
                    $finalLogArr[$logDate][$i]['label'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'label-' . $statusColorArr[$activityLog['status']] : '';
                    $finalLogArr[$logDate][$i]['font'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'font-' . $statusColorArr[$activityLog['status']] : '';
                    $finalLogArr[$logDate][$i]['background'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'bg-' . $statusColorArr[$activityLog['status']] . ' bg-font-' . $statusColorArr[$activityLog['status']] : '';
                    $finalLogArr[$logDate][$i]['icon'] = (!empty($activityLog['status']) && isset($statusIconArr[$activityLog['status']])) ? $statusIconArr[$activityLog['status']] : '';
                    $i++;
                }
            }
        }
        krsort($finalLogArr);
        //********************** End :: Activity Log **************************//

        $userInfoArr = User::select(DB::raw("CONCAT(first_name,' ', last_name) as full_name")
                        , 'employee_id', 'id', 'photo')->get();
        $userArr = [];
        if (!$userInfoArr->isEmpty()) {
            foreach ($userInfoArr as $user) {
                $userArr[$user->id]['full_name'] = $user->full_name;
                $userArr[$user->id]['employee_id'] = $user->employee_id;
                $userArr[$user->id]['photo'] = $user->photo;
            }
        }

        $view = view($loadView, compact('request', 'statusList', 'finalArr', 'userArr'
                        , 'fStatArr', 'statusColorArr', 'statusIconArr', 'finalLogArr'))->render();

        return response()->json(['html' => $view]);
    }

    public static function setFollowUpSave(Request $request) {
        //validation
        $rules = $message = [];
        $rules = [
            'follow_up_date' => 'required',
            'status' => 'required|not_in:0',
            'remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end of validation
        //create follow up history
        $historyData = [];
        $uniqId = uniqid();

        //create new follow up array
        $historyData[$uniqId]['follow_up_date'] = $request->follow_up_date;
        $historyData[$uniqId]['status'] = $request->status;
        $historyData[$uniqId]['remarks'] = $request->remarks;
        $historyData[$uniqId]['updated_by'] = Auth::user()->id;
        $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');


        //merge with previous history and pack in json
        $followUpHistory = FollowUpHistory::where('inquiry_id', $request->inquiry_id)->first();

        if (!empty($followUpHistory)) {
            $preHistoryArr = json_decode($followUpHistory->history, true);
            $historyArr = array_merge($preHistoryArr, $historyData);
        } else {
            $followUpHistory = new FollowUpHistory;
            $historyArr = $historyData;
        }


        $followUpHistory->inquiry_id = $request->inquiry_id;
        $followUpHistory->history = json_encode($historyArr);
        $followUpHistory->updated_at = date('Y-m-d H:i:s');
        $followUpHistory->updated_by = Auth::user()->id;


        if ($followUpHistory->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.FOLLOW_UP_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FOLLOW_UP_COULD_NOT_BE_CREATED')], 401);
        }
    }

    //endof follow up
    //RW BREAKDOWN PART
    //part-1
    public static function rwBreakdown(Request $request, $id, $loadView) {
        $qpArr = $request->all();
        $target = Lead::find($id);

        //inquiry details
        $inquiryDetails = InquiryDetails::where('inquiry_details.inquiry_id', $target->id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'inquiry_details.inquiry_id')
                ->get();



        if (!empty($inquiryDetails)) {
            foreach ($inquiryDetails as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
            }
        }

        $productList = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::whereIn('id', $productIdArr)
                        ->pluck('name', 'id')->toArray();

        $brandList = array('0' => __('label.SELECT_BRAND_OPT'));


        //endof inquiry details
        //rw breakdown
        $rwBreakdownInfo = RwBreakdown::where('inquiry_id', $id)->first();

        $gsmInfo = $gsmDetailsInfo = '';
        if (!empty($rwBreakdownInfo)) {
            $gsmInfo = json_decode($rwBreakdownInfo->gsm, true);
            $gsmDetailsInfo = json_decode($rwBreakdownInfo->gsm_details, true);
        }



        return view($loadView . '.rwBreakdown')->with(compact('target', 'gsmInfo', 'gsmDetailsInfo'
                                , 'productList', 'brandList', 'qpArr'));
    }

    //part 1.1
    public static function rwBreakdownGetBrand(Request $request, $loadView) {
        $brandArr = Brand::join('inquiry_details', 'inquiry_details.brand_id', '=', 'brand.id')
                        ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                        ->where('inquiry_details.product_id', $request->product_id)
                        ->pluck('brand.name', 'brand.id')->toArray();

        $brandList = array('0' => __('label.SELECT_BRAND_OPT')) + $brandArr;

        $view = view($loadView . '.showBrand', compact('request', 'brandList'))->render();
        return response()->json(['html' => $view]);
    }

    //part 1.2
    public static function rwBreakdownGetGrade(Request $request, $loadView) {

        $gradeArr = Grade::join('inquiry_details', 'inquiry_details.grade_id', '=', 'grade.id')
                        ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                        ->where('inquiry_details.product_id', $request->product_id)
                        ->where('inquiry_details.brand_id', $request->brand_id)
                        ->pluck('grade.name', 'grade.id')->toArray();

        if (!empty($gradeArr)) {
            $gradeList = array('0' => __('label.SELECT_GRADE_OPT')) + $gradeArr;
            $view = view($loadView . '.showGrade', compact('request', 'gradeList'))->render();
            return response()->json(['grade' => $view]);
        } else {

            $target = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                    ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                    ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                    ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                    ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                    ->where('inquiry_details.product_id', $request->product_id)
                    ->where('inquiry_details.brand_id', $request->brand_id);
            if (!empty($request->grade_id)) {
                $target = $target->where('inquiry_details.grade_id', $request->grade_id);
            } else {
                $target = $target->whereNull('inquiry_details.grade_id');
            }
            $target = $target->select('inquiry.id', 'inquiry_details.quantity', 'product.name as productName'
                            , 'product.id as product_id')
                    ->first();

            $gsmArrInfo = InquiryDetails::where('inquiry_id', $request->inquiry_id)
                    ->where('product_id', $request->product_id)
                    ->where('brand_id', $request->brand_id)
                    ->select('gsm', 'quantity')
                    ->get();
            $gsmFlag = 0;
            if (!$gsmArrInfo->isEmpty()) {
                foreach ($gsmArrInfo as $gsmInfo) {
                    if (!empty($gsmInfo->gsm)) {
                        $gsmFlag = 1;
                    }
                }
            }
//            echo '<pre>';
//            print_r($gsmArrInfo->toArray());
//            exit;

            $measureUnitInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                            ->where('product.id', $target->product_id)
                            ->select('measure_unit.name as unitName')->first();



            //rw breakdown
            $rwBreakdownInfo = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                    ->where('product_id', $request->product_id)
                    ->where('brand_id', $request->brand_id);
            if (!empty($request->grade_id)) {
                $rwBreakdownInfo = $rwBreakdownInfo->where('grade_id', $request->grade_id);
            } else {
                $rwBreakdownInfo = $rwBreakdownInfo->whereNull('grade_id');
            }
            $rwBreakdownInfo = $rwBreakdownInfo->first();


            $gsmInfo = $gsmDetailsInfo = '';
            if (!empty($rwBreakdownInfo)) {
                $gsmInfo = json_decode($rwBreakdownInfo->gsm, true);
                $gsmDetailsInfo = json_decode($rwBreakdownInfo->gsm_details, true);
            }


            $rwUnitIdArr = [];
            if (!empty($gsmDetailsInfo)) {
                foreach ($gsmDetailsInfo as $gsmId => $item) {
                    foreach ($item as $key => $values) {
                        foreach ($values as $rwUnitId => $val) {
                            if ($rwUnitId != 'quantity') {
                                $rwUnitIdArr[$rwUnitId] = $rwUnitId;
                            }
                        }
                    }
                }
            }


            $rwParameterArr = RwUnit::whereIn('id', $rwUnitIdArr)
                            ->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();
            $inputUnitArr = ['0' => __('label.SELECT_IPUT_UNIT')] + RwUnit::whereIn('id', $rwUnitIdArr)
                            ->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();
            //end rw breakdown data
            $rwParameterList = RwUnit::where('status', '1')->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();

            $inputUnitId = !empty($rwBreakdownInfo->input_unit_id) ? $rwBreakdownInfo->input_unit_id : 0;
            $convUnitList = RwUnitConversion::where('base_unit_id', $inputUnitId)
                            ->pluck('conv_unit_rate', 'conv_unit_id')->toArray();

            $formatArr = [
                '1' => __('label.FORMAT1'),
                '2' => __('label.FORMAT2'),
            ];

            $view = view($loadView . '.showRwBreakdownView', compact('request', 'rwParameterList', 'target'
                            , 'rwParameterArr', 'rwUnitIdArr', 'measureUnitInfo', 'rwBreakdownInfo'
                            , 'gsmInfo', 'gsmDetailsInfo', 'formatArr', 'gsmArrInfo'
                            , 'gsmFlag', 'inputUnitArr', 'convUnitList'))->render();
            return response()->json(['html' => $view]);
        }
    }

    //part 1.3
    public static function getRwBreakdownView(Request $request, $loadView) {

        $target = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->where('inquiry_details.product_id', $request->product_id)
                ->where('inquiry_details.brand_id', $request->brand_id)
                ->where('inquiry_details.grade_id', $request->grade_id)
                ->select('inquiry.id', 'inquiry_details.quantity', 'product.name as productName'
                        , 'product.id as product_id')
                ->first();

        $gsmArrInfo = InquiryDetails::where('inquiry_id', $request->inquiry_id)
                ->where('product_id', $request->product_id)
                ->where('brand_id', $request->brand_id)
                ->where('grade_id', $request->grade_id)
                ->select('gsm', 'quantity')
                ->get();
        $gsmFlag = 0;
        if (!$gsmArrInfo->isEmpty()) {
            foreach ($gsmArrInfo as $gsmInfo) {
                if (!empty($gsmInfo->gsm)) {
                    $gsmFlag = 1;
                }
            }
        }
//echo '<pre>';
//print_r($gsmFlag);
//exit;
        $measureUnitInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('product.id', $target->product_id)
                        ->select('measure_unit.name as unitName')->first();



        //rw breakdown
        $rwBreakdownInfo = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                        ->where('product_id', $request->product_id)
                        ->where('brand_id', $request->brand_id)
                        ->where('grade_id', $request->grade_id)->first();


        $gsmInfo = $gsmDetailsInfo = '';
        if (!empty($rwBreakdownInfo)) {
            $gsmInfo = json_decode($rwBreakdownInfo->gsm, true);
            $gsmDetailsInfo = json_decode($rwBreakdownInfo->gsm_details, true);
        }


        $rwUnitIdArr = [];
        if (!empty($gsmDetailsInfo)) {
            foreach ($gsmDetailsInfo as $gsmId => $item) {
                foreach ($item as $key => $values) {
                    foreach ($values as $rwUnitId => $val) {
                        if ($rwUnitId != 'quantity') {
                            $rwUnitIdArr[$rwUnitId] = $rwUnitId;
                        }
                    }
                }
            }
        }

        $rwParameterArr = RwUnit::whereIn('id', $rwUnitIdArr)
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $inputUnitArr = ['0' => __('label.SELECT_IPUT_UNIT')] + RwUnit::whereIn('id', $rwUnitIdArr)
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        //end rw breakdown data
        $rwParameterList = RwUnit::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $inputUnitId = !empty($rwBreakdownInfo->input_unit_id) ? $rwBreakdownInfo->input_unit_id : 0;
        $convUnitList = RwUnitConversion::where('base_unit_id', $inputUnitId)
                        ->pluck('conv_unit_rate', 'conv_unit_id')->toArray();

        $formatArr = [
            '1' => __('label.FORMAT1'),
            '2' => __('label.FORMAT2'),
        ];

        $view = view($loadView . '.showRwBreakdownView', compact('request', 'rwParameterList', 'target'
                        , 'rwParameterArr', 'rwUnitIdArr', 'measureUnitInfo', 'rwBreakdownInfo'
                        , 'gsmInfo', 'gsmDetailsInfo', 'formatArr', 'gsmArrInfo'
                        , 'gsmFlag', 'inputUnitArr', 'convUnitList'))->render();
        return response()->json(['html' => $view]);
    }

    //part-2
    public static function rwProceedRequest(Request $request, $loadView) {

        if ($request->rwProceedCheck == '1') {

            $errors = [];
            $target = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                    ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                    ->where('inquiry_details.product_id', $request->product_id)
                    ->where('inquiry_details.brand_id', $request->brand_id);

            if (!empty($request->grade_id)) {
                $target = $target->where('inquiry_details.grade_id', $request->grade_id);
            } else {
                $target = $target->whereNull('inquiry_details.grade_id');
            }
            $target = $target->select('inquiry_details.id as inquiry_details_id', 'inquiry.id'
                            , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                            , 'inquiry_details.product_id')
                    ->first();

            $rwParametersArr = $request->rw_parameters;

//            $gsmArr = $request->gsmVal;

            $gsmArr = $qtyArr = [];
            if (!empty($request->gsmVal)) {
                foreach ($request->gsmVal as $key => $item) {
                    $gsmArr[$key] = $item;
                }
            }
            if (!empty($request->qtyVal)) {
                foreach ($request->qtyVal as $key => $item) {
                    $qtyArr[$key] = $item;
                }
            }
//echo '<pre>';
//print_r($request->all());
//print_r($request->input_unit_id);
//exit;

            $measureUnitInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                            ->where('product.id', $target->product_id)
                            ->select('measure_unit.name as unitName')->first();


            if (empty($request->input_unit_id)) {
                $errors[] = __('label.INPUT_UNIT_FIELD_IS_REQUIRED');
            }
            if (empty($rwParametersArr)) {
                $errors[] = __('label.RW_PARAMETERS_FIELD_IS_REQUIRED');
            }


            foreach ($gsmArr as $key => $item) {
                if (empty($item)) {
                    $errors[] = __('label.GSM_FIELD_IS_REQUIRED');
                }
            }

            $gsmArrValueMatch = array_diff_assoc($gsmArr, array_unique($gsmArr));
            if (!empty($gsmArrValueMatch)) {
                $errors[] = __('label.GSM_VALUE_CANNOT_EQUAL');
            }

            if (!empty($errors)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
            }


            $rwParameter = RwUnit::whereIn('id', $rwParametersArr)
                            ->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();

            $convUnitList = RwUnitConversion::where('base_unit_id', $request->input_unit_id)
                            ->pluck('conv_unit_rate', 'conv_unit_id')->toArray();

            $view = view($loadView . '.getProceedData', compact('request', 'rwParameter', 'gsmArr'
                            , 'target', 'measureUnitInfo', 'qtyArr', 'convUnitList'))->render();
            return response()->json(['html' => $view]);
        } else {  //gsm save
//            $gsmArr = $request->gsmVal;
            $gsmArr = [];
            if (!empty($request->gsmVal)) {
                $i = 1;
                foreach ($request->gsmVal as $key => $item) {
                    $gsmArr[$i] = $item;
                    $i++;
                }
            }

            $jsonEncodeGsmArr = json_encode($gsmArr);

            foreach ($gsmArr as $key => $item) {
                if (empty($item)) {
                    $errors[] = __('label.GSM_FIELD_IS_REQUIRED');
                }
            }

            $gsmArrValueMatch = array_diff_assoc($gsmArr, array_unique($gsmArr));
            if (!empty($gsmArrValueMatch)) {
                $errors[] = __('label.GSM_VALUE_CANNOT_EQUAL');
            }


            if (!empty($errors)) {
                return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
            }



            $target = new RwBreakdown;
            $target->inquiry_id = $request->inquiry_id;
            $target->product_id = $request->product_id;
            $target->brand_id = $request->brand_id;
            $target->grade_id = !empty($request->grade_id) ? $request->grade_id : null;
            $target->gsm = $jsonEncodeGsmArr;
            $target->status = '1';
            $target->update_at = date('Y-m-d H:i:s');
            $target->update_by = Auth::user()->id;



            //prev data delete then save
            $delete = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                    ->where('product_id', $request->product_id)
                    ->where('brand_id', $request->brand_id);
            if (!empty($request->grade_id)) {
                $delete = $delete->where('grade_id', $request->grade_id);
            } else {
                $delete = $delete->whereNull('grade_id');
            }
            $delete = $delete->delete();
            //endof delete

            if ($target->save()) {
                return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.RW_BREAKDOWN_CREATED_SUCCESSFULLY')], 200);
            } else {
                return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.RW_BREAKDOWN_COULD_NOT_BE_CREATED')], 401);
            }


            //ENDOF GSM SAVE ELSE CONDITION 
        }
    }

    //part-3

    public static function rwProceedRequestEdit(Request $request, $loadView) {

        $errors = [];

        $rwParametersArr = $request->rw_parameters;
        $gsmArr = $request->gsmVal;

        $target = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->where('inquiry_details.product_id', $request->product_id)
                ->where('inquiry_details.brand_id', $request->brand_id);

        if (!empty($request->grade_id)) {
            $target = $target->where('inquiry_details.grade_id', $request->grade_id);
        } else {
            $target = $target->whereNull('inquiry_details.grade_id');
        }
        $target = $target->select('inquiry_details.id as inquiry_details_id', 'inquiry.id'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.product_id')
                ->first();




        $measureUnitInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('product.id', $target->product_id)
                        ->select('measure_unit.name as unitName')->first();


        if (empty($request->input_unit_id)) {
            $errors[] = __('label.INPUT_UNIT_FIELD_IS_REQUIRED');
        }
        if (empty($rwParametersArr)) {
            $errors[] = __('label.RW_PARAMETERS_FIELD_IS_REQUIRED');
        }

        foreach ($gsmArr as $key => $item) {
            if (empty($item)) {

                $errors[] = __('label.GSM_FIELD_IS_REQUIRED');
            }
        }

        $gsmArrValueMatch = array_diff_assoc($gsmArr, array_unique($gsmArr));
        if (!empty($gsmArrValueMatch)) {
            $errors[] = __('label.GSM_VALUE_CANNOT_EQUAL');
        }

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }


        //data rw breakdown 
        //rw breakdown
        $rwBreakdownInfo = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                ->where('product_id', $request->product_id)
                ->where('brand_id', $request->brand_id);
        if (!empty($request->grade_id)) {
            $rwBreakdownInfo = $rwBreakdownInfo->where('grade_id', $request->grade_id);
        } else {
            $rwBreakdownInfo = $rwBreakdownInfo->whereNull('grade_id');
        }
        $rwBreakdownInfo = $rwBreakdownInfo->first();



        $gsmInfo = $gsmDetailsInfo = '';
        if (!empty($rwBreakdownInfo)) {
            $gsmInfo = json_decode($rwBreakdownInfo->gsm, true);
            $gsmDetailsInfo = json_decode($rwBreakdownInfo->gsm_details, true);
        }


        $rwUnitIdArr = [];
        if (!empty($gsmDetailsInfo)) {
            foreach ($gsmDetailsInfo as $gsmId => $item) {
                foreach ($item as $key => $values) {
                    foreach ($values as $rwUnitId => $val) {
                        if ($rwUnitId != 'quantity') {
                            $rwUnitIdArr[$rwUnitId] = $rwUnitId;
                        }
                    }
                }
            }
        }

//start final gsm details arr
        $finalGsmDetailsArr = [];
        if (!empty($gsmArr)) {
            foreach ($gsmArr as $gsmIdKey => $gsmValue) {
                foreach ($rwParametersArr as $rwParamId) {
                    if (!empty($gsmDetailsInfo[$gsmIdKey])) {
                        foreach ($gsmDetailsInfo[$gsmIdKey] as $rKey => $rVal) {
                            $finalGsmDetailsArr[$gsmIdKey][$rKey][$rwParamId] = !empty($rVal[$rwParamId]) ? $rVal[$rwParamId] : 0;
                            $finalGsmDetailsArr[$gsmIdKey][$rKey]['quantity'] = !empty($rVal['quantity']) ? $rVal['quantity'] : 0;
                        }
                    } else {
                        $finalGsmDetailsArr[$gsmIdKey][0][$rwParamId] = 0;
                        $finalGsmDetailsArr[$gsmIdKey][0]['quantity'] = 0;
                    }
                }
            }
        }

        $convUnitList = RwUnitConversion::where('base_unit_id', $request->input_unit_id)
                        ->pluck('conv_unit_rate', 'conv_unit_id')->toArray();

//echo '<pre>';
//print_r($finalGsmDetailsArr);
//print_r($request->qtyVal);
//exit;
//endof gsm final details arr

        $finalRwIdArr = [];
        if (!empty($rwParametersArr)) {
            $finalRwIdArr = $rwParametersArr;
        } else {
            $finalRwIdArr = $rwUnitIdArr;
        }

        $rwParameterArr = RwUnit::whereIn('id', $finalRwIdArr)
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        //ENDOF DATA RW BREAKDOWN

        $view = view($loadView . '.getProceedDataEdit', compact('request', 'gsmArr'
                        , 'target', 'measureUnitInfo', 'rwParameterArr', 'convUnitList'
                        , 'finalGsmDetailsArr', 'gsmInfo', 'gsmDetailsInfo'))->render();
        return response()->json(['html' => $view]);
    }

    //part-4
    public static function rwPreviewRequest(Request $request, $loadView) {

        $rwBreakdownArr = $request->rw_breakdown;
        $gsmValueArr1 = $request->gsmValue;

        $target = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->where('inquiry_details.product_id', $request->product_id)
                ->where('inquiry_details.brand_id', $request->brand_id);
        if (!empty($request->grade_id)) {
            $target = $target->where('inquiry_details.grade_id', $request->grade_id);
        } else {
            $target = $target->whereNull('inquiry_details.grade_id');
        }
        $target = $target->select('inquiry.id', 'inquiry_details.quantity', 'product.name as productName'
                        , 'product.id as product_id', 'brand.name as brandName', 'grade.name as gradeName')
                ->first();


        $errors = [];
        $gsmValueArr2 = [];
        $totalMtquantity = [];
        if (!empty($rwBreakdownArr)) {
            foreach ($rwBreakdownArr as $gsmId => $item) {
                $gsmValueArr2[$gsmId] = $gsmId;
                foreach ($item as $key => $values) {
                    foreach ($values as $rwUnitId => $val) {
                        if ($rwUnitId != 'quantity') {
                            if (!isset($val)) {
                                $errors[] = __('label.RW_FIELD_IS_REQUIRED');
                            }
                        }
                        if ($request->gsm_flag == 1) {
                            if ($rwUnitId == 'quantity') {
                                $totalMtquantity[$gsmId] = !empty($totalMtquantity[$gsmId]) ? $totalMtquantity[$gsmId] : 0;
                                $totalMtquantity[$gsmId] += $val;

                                if (empty($val)) {
                                    $errors[] = __('label.QUANTITY_FIELD_IS_REQUIRED');
                                }
                            }
                        } else {
                            if ($rwUnitId == 'quantity') {
                                $totalMtquantity[] = $val;

                                if (empty($val)) {
                                    $errors[] = __('label.QUANTITY_FIELD_IS_REQUIRED');
                                }
                            }
                        }
                    }
                }
            }
        }


        if ($request->gsm_flag == 1) {
            if (!empty($request->qty)) {
                foreach ($request->qty as $gsmId => $val) {
                    if (round($request->qty[$gsmId], 3) != round($totalMtquantity[$gsmId], 3)) {
                        $errors[] = __('label.INQUIRY_QUANTITY_AND_TOTAL_QUANTITY_DONT_MATCH_FOR_GSM', ['gsm' => $gsmValueArr1[$gsmId]]);
                    }
                }
            }
        } else {
            $totalQeunatity = !empty($totalMtquantity) ? array_sum($totalMtquantity) : 0;
            if (round($totalQeunatity, 3) != round($target->quantity, 3)) {
                $errors[] = __('label.INQUIRY_QUANTITY_AND_TOTAL_QUANTITY_DONT_MATCH');
            }
        }
        //***END RW FIELD Empty VALIDATION

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }

        $gsmValArr = $rwUnitIdArr = $gsmDataArr = $gsmDataCountArr = [];
        $gsmDataCountSum = $totalQuantity = 0;
        if (!empty($rwBreakdownArr)) {
            foreach ($rwBreakdownArr as $gsmId => $item) {
                $gsmValArr[$gsmId] = $gsmId;
                foreach ($item as $key => $values) {
                    foreach ($values as $rwUnitId => $val) {
                        if ($rwUnitId != 'quantity') {
                            $rwUnitIdArr[$rwUnitId] = $rwUnitId;
                        }

                        if ($rwUnitId == 'quantity') {
                            $totalQuantity = !empty($totalQuantity) ? $totalQuantity : 0;
                            $totalQuantity += $val;
                        }

                        $gsmDataArr[$gsmId][$key][$rwUnitId] = $val;
                        $gsmDataCountArr[$gsmId] = !empty($gsmDataArr[$gsmId]) ? count($gsmDataArr[$gsmId]) : 0;
                        $gsmDataCountSum = !empty($gsmDataCountArr) ? array_sum($gsmDataCountArr) : 0;
                    }
                }
            }
        }


        $rwParameter = RwUnit::whereIn('id', $rwUnitIdArr)
                        ->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();



        $rwBreakdownInfo = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                ->where('product_id', $request->product_id)
                ->where('brand_id', $request->brand_id);
        if (!empty($request->grade_id)) {
            $rwBreakdownInfo = $rwBreakdownInfo->where('grade_id', $request->grade_id);
        } else {
            $rwBreakdownInfo = $rwBreakdownInfo->whereNull('grade_id');
        }
        $rwBreakdownInfo = $rwBreakdownInfo->first();


        $rwUnitIdArr2 = $bfArr = [];
        if (!empty($rwBreakdownInfo)) {
            $rwUnitIdArr2 = json_decode($rwBreakdownInfo->rw_unit_id, true);
            $bfArr = json_decode($rwBreakdownInfo->bf, true);
        }


        $rwInfo = '';
        if (!empty($rwUnitIdArr2)) {
            $rwUnitInfo = RwUnit::whereIn('id', $rwUnitIdArr2)
                            ->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();

            if (!empty($rwUnitInfo)) {
                $rwInfo = Helper::arrayToString($rwUnitInfo);
            }
        }

        $measureUnitInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('product.id', $target->product_id)
                        ->select('measure_unit.name as unitName')->first();


        $rwParameterList = RwUnit::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();


        $view = view($loadView . '.showPreviewModal', compact('request', 'target', 'rwParameter', 'gsmValArr'
                        , 'gsmDataArr', 'gsmDataCountArr', 'gsmDataCountSum'
                        , 'rwBreakdownInfo', 'measureUnitInfo', 'totalQuantity'
                        , 'gsmValueArr1', 'rwParameterList', 'rwUnitIdArr2'
                        , 'rwInfo', 'bfArr'))->render();
        return response()->json(['html' => $view]);
    }

//    part-5
    public static function rwBreakDownSave(Request $request) {
        //bf validation check
        $errors = [];
        if (!empty($request->has_bf)) {
            $row = 1;
            foreach ($request->bf as $key => $val) {
                if (empty($val)) {
                    $errors[] = __('label.THE_BF_FIELD_IS_REQUIRED', ['row' => $row]);
                }
                $row++;
            }
        }


        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }
        //***END RW FIELD Empty VALIDATION


        $bfArr = $request->bf;
        $gsmArr = $request->gsm;
        $gsmDetailsArr = $request->gsmDetails;
        $rwParameterArr = $request->rw_parameter;
        $jsonEncodeGsmArr = json_encode($gsmArr);
        $jsonEncodeGsmDetailsArr = json_encode($gsmDetailsArr);
        $jsonEncodeRwParameterArr = json_encode($rwParameterArr);
        $jsonEncodeBfArr = json_encode($bfArr);



        $target = new RwBreakdown;
        $target->inquiry_id = $request->inquiry_id;
        $target->product_id = $request->product_id;
        $target->brand_id = $request->brand_id;
        $target->grade_id = !empty($request->grade_id) ? $request->grade_id : null;
        $target->core_and_dia = !empty($request->core_and_dia) ? $request->core_and_dia : null;
        $target->rw_unit_id = !empty($jsonEncodeRwParameterArr) ? $jsonEncodeRwParameterArr : null;

        if (!empty($request->has_bf)) {
            $target->has_bf = '1';
            $target->bf = $jsonEncodeBfArr;
        } else {
            $target->has_bf = '0';
            $target->bf = null;
        }
        $target->gsm = $jsonEncodeGsmArr;
        $target->format = $request->format;
        $target->input_unit_id = $request->input_unit_id;
        $target->gsm_details = $jsonEncodeGsmDetailsArr;
        $target->status = '2';
        $target->update_at = date('Y-m-d H:i:s');
        $target->update_by = Auth::user()->id;


        //prev data delete then save
        $delete = RwBreakdown::where('inquiry_id', $request->inquiry_id)
                ->where('product_id', $request->product_id)
                ->where('brand_id', $request->brand_id);
        if (!empty($request->grade_id)) {
            $delete = $delete->where('grade_id', $request->grade_id);
        } else {
            $delete = $delete->whereNull('grade_id');
        }
        $delete = $delete->delete();
        //endof delete

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.RW_BREAKDOWN_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.RW_BREAKDOWN_COULD_NOT_BE_CREATED')], 401);
        }
    }

    //part-6
    public static function leadRwBreakdownView(Request $request, $loadView) {

        $measureUnitArr = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                        ->pluck('measure_unit.name', 'product.id')->toArray();

        $rwBreakdownInfo = RwBreakdown::join('inquiry', 'inquiry.id', '=', 'rw_breakdown.inquiry_id')
                        ->join('product', 'product.id', '=', 'rw_breakdown.product_id')
                        ->join('brand', 'brand.id', '=', 'rw_breakdown.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'rw_breakdown.grade_id')
                        ->where('rw_breakdown.inquiry_id', $request->inquiry_id)
                        ->select('rw_breakdown.*', 'product.name as productName', 'brand.name as brandName'
                                , 'grade.name as gradeName')->get();



        $targetArr = [];
        //fianl arr
        if (!$rwBreakdownInfo->isEmpty()) {
            foreach ($rwBreakdownInfo as $values) {

                $targetArr[$values->id]['unit_name'] = !empty($measureUnitArr[$values->product_id]) ? $measureUnitArr[$values->product_id] : '';
                $targetArr[$values->id]['product_name'] = $values->productName;
                $targetArr[$values->id]['brand_name'] = $values->brandName;
                $targetArr[$values->id]['grade_name'] = $values->gradeName;
                $targetArr[$values->id]['gsm_info'] = json_decode($values->gsm, true);
                $targetArr[$values->id]['bf_info'] = json_decode($values->bf, true);
                $targetArr[$values->id]['gsm_details'] = json_decode($values->gsm_details, true);
                $targetArr[$values->id]['rw_unit'] = json_decode($values->rw_unit_id, true);
                $targetArr[$values->id]['core_and_dia'] = $values->core_and_dia;
                $targetArr[$values->id]['format'] = $values->format;
            }
        }


        $rw_unit = $rwInfo = $rwParameter = $rwUnitIdArr = $gsmDataCountArr = [];
        $totalQuantity = $gsmDataCountSum = $gsmDataArr = [];
        if (!empty($targetArr)) {
            foreach ($targetArr as $id => $item) {
                if (!empty($item['gsm_details'])) {
                    //    Core & Dia Rw Unit Arr
                    if (!empty($item['rw_unit'])) {
                        $rw_unit[$id] = RwUnit::whereIn('id', $item['rw_unit'])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                        $rwInfo[$id] = Helper::arrayToString($rw_unit[$id]);
                    }
                    foreach ($item['gsm_details'] as $gsmId => $item) {
                        foreach ($item as $key => $values) {
                            foreach ($values as $rwUnitId => $val) {

                                if ($rwUnitId != 'quantity') {
                                    // RW PARAMETER Arr
                                    $rwUnitIdArr[$id][$rwUnitId] = $rwUnitId;
                                    $rwParameter[$id] = RwUnit::whereIn('id', $rwUnitIdArr[$id])->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
                                }

                                if ($rwUnitId == 'quantity') {
                                    //total quantity arr
                                    $totalQuantity[$id] = !empty($totalQuantity[$id]) ? $totalQuantity[$id] : 0;
                                    $totalQuantity[$id] += $val;
                                }

                                $gsmDataArr[$id][$gsmId][$key][$rwUnitId] = $val;
                                //gsm wise rowspan arr
                                $gsmDataCountArr[$id][$gsmId] = !empty($gsmDataArr[$id][$gsmId]) ? count($gsmDataArr[$id][$gsmId]) : 0;
                                //product wise rowsapn arr
                                $gsmDataCountSum[$id] = !empty($gsmDataCountArr[$id]) ? array_sum($gsmDataCountArr[$id]) : 0;
                            }
                        }
                    }
                } //end of if
            }
        }

        $view = view($loadView . '.showRwbreakdownViewModal', compact('targetArr', 'rwInfo', 'rwParameter'
                        , 'gsmDataCountSum', 'gsmDataCountArr', 'totalQuantity'))->render();
        return response()->json(['html' => $view]);
    }

    //part-7

    public static function getLeadRwParametersName(Request $request) {
        $rwParameterInfo = '';
        if (!empty($request->rw_unit_id)) {
            $rwParameterInfo = RwUnit::whereIn('id', $request->rw_unit_id)
                            ->orderBy('order', 'asc')
                            ->pluck('name', 'id')->toArray();
        }

        $rwInfo = '';
        if (!empty($rwParameterInfo)) {
            $rwInfo = Helper::arrayToString($rwParameterInfo);
        }
        return response()->json(['html' => $rwInfo]);
    }

    //ENDOF RW BREAKDOWN PART
    //commission setup modal
    public static function getCommissionSetupModal(Request $request, $loadView) {
        $target = Lead::find($request->inquiry_id);
        $prevComsn = [];
        $comsnInquiryId = 0;
        $commissionInfo = CommissionSetup::where('inquiry_id', $request->inquiry_id)->get();
        if (!$commissionInfo->isEmpty()) {
            $comsnInquiryId = $request->inquiry_id;
            foreach ($commissionInfo as $comsn) {
                $prevComsn[$comsn->inquiry_details_id] = $comsn->toArray();
            }
        }

        //Show inquiryId From Invoice Table
        $invoiceInfo = Invoice::select('order_no_history')
                ->get();
        $oredrNoArr = [];
        if (!$invoiceInfo->isEmpty()) {
            foreach ($invoiceInfo as $key => $item) {
                $oredrNoArr[$key] = json_decode($item->order_no_history, true);
            }
        }

        $inquiryIdArr = [];
        if (!empty($oredrNoArr)) {
            foreach ($oredrNoArr as $values) {
                foreach ($values as $inquiryId => $item) {
                    $inquiryIdArr[$inquiryId] = $inquiryId;
                }
            }
        }

        //End of Invoice Info
        //inquiry Details
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->select('product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.gsm', 'grade.name as grade_name', 'inquiry_details.id as inquiry_details_id')
                ->get();

        $view = view($loadView . '.showCommissionSetupModal', compact('target', 'commissionInfo'
                        , 'inquiryIdArr', 'inquiryDetails', 'prevComsn', 'comsnInquiryId'))->render();
        return response()->json(['html' => $view]);
    }

    public static function commissionSetupSave(Request $request) {

        //validation
        $rules = $message = [];

        if (!empty($request->comsn_setup)) {
            $row = 1;
            foreach ($request->comsn_setup as $inquery_details_id => $info) {
                $rules['comsn_setup.' . $inquery_details_id . '.konita_cmsn'] = 'required';
                $rules['comsn_setup.' . $inquery_details_id . '.principle_cmsn'] = 'required';
                $rules['comsn_setup.' . $inquery_details_id . '.sales_person_cmsn'] = 'required';
                $rules['comsn_setup.' . $inquery_details_id . '.buyer_cmsn'] = 'required';
                $rules['comsn_setup.' . $inquery_details_id . '.rebate_cmsn'] = 'required';

                $message['comsn_setup.' . $inquery_details_id . '.konita_cmsn' . '.required'] = __('label.KONITA_COMSN_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['comsn_setup.' . $inquery_details_id . '.principle_cmsn' . '.required'] = __('label.PRINCIPLE_COMSN_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['comsn_setup.' . $inquery_details_id . '.sales_person_cmsn' . '.required'] = __('label.SALES_PERSON_COMSN_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['comsn_setup.' . $inquery_details_id . '.buyer_cmsn' . '.required'] = __('label.BUYER_COMSN_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $message['comsn_setup.' . $inquery_details_id . '.rebate_cmsn' . '.required'] = __('label.REBATE_COMSN_FIELD_IS_REQUIRED_FOR_ROW_NO', ['row' => $row]);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        DB::beginTransaction();
        try {
            if (!empty($request->comsn_setup)) {
                foreach ($request->comsn_setup as $inquery_details_id => $info) {
                    $target = CommissionSetup::where('inquiry_id', $request->inquiry_id)
                                    ->where('inquiry_details_id', $inquery_details_id)->first();
                    if (empty($target)) {
                        $target = new CommissionSetup;
                        $target->inquiry_id = $request->inquiry_id;
                        $target->inquiry_details_id = $inquery_details_id;
                    }
                    $target->konita_cmsn = $info['konita_cmsn'];
                    $target->principle_cmsn = $info['principle_cmsn'];
                    $target->sales_person_cmsn = $info['sales_person_cmsn'];
                    $target->buyer_cmsn = $info['buyer_cmsn'];
                    $target->rebate_cmsn = $info['rebate_cmsn'];
                    $target->konita_remarks = $info['konita_remarks'];
                    $target->principle_remarks = $info['principle_remarks'];
                    $target->sales_person_remarks = $info['sales_person_remarks'];
                    $target->buyer_remarks = $info['buyer_remarks'];
                    $target->rebate_remarks = $info['rebate_remarks'];
                    $target->updated_at = date('Y-m-d H:i:s');
                    $target->updated_by = Auth::user()->id;
                    if ($target->save()) {
                        CommissionSetup::where('inquiry_id', $request->inquiry_id)
                                ->where('inquiry_details_id', 0)->delete();
                    }
                }
            }
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.COMMISSION_SET_CREATED_SUCCESSFULLY')), 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.COMMISSION_SET_COULD_NOT_BE_CREATED')), 401);
        }
    }

    //endof commission setup function
    //get sales target popup 
    public static function getSalesTarget(Request $request, $loadView) {
        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime($request->effective_month));
        $deadline = date("Y-m-t", strtotime($request->effective_month));

        $salesPersonToProduct = SalesPersonToProduct::select('product_id')
                        ->where('sales_person_id', $request->sales_person_id)->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = $salesPersonToProductArr = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $salesPersonToProductArr[$productId] = $productId;
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        } else if (!$salesPersonToProduct->isEmpty()) {
            foreach ($salesPersonToProduct as $product) {
                $salesPersonToProductArr[$product->product_id] = $product->product_id;
            }
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();



        $view = view('salesTarget.' . $loadView, compact('request', 'effectiveDate'
                        , 'deadline', 'productList', 'targetArr', 'salesTarget', 'remarks', 'quantity'))->render();

        $setsubmitLock = view('salesTarget.setSubmitLockbtn', compact('request', 'salesTarget', 'productList'))->render();
        return response()->json(['html' => $view, 'setsubmitLock' => $setsubmitLock]);
    }

    //get sales target detail popup
    public static function getSalesTargetDetail(Request $request, $loadView) {
        //get fitst and last day of the month
        $effectiveDate = date("Y-m-01", strtotime($request->effective_month));
        $deadline = date("Y-m-t", strtotime($request->effective_month));

        //get sales target by effective date
        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();

        $targetArr = $quantity = $remarks = $salesPersonToProductArr = [];
        if (!empty($salesTarget)) {
            $targetArr = json_decode($salesTarget->target, true);
        }

        if (!empty($targetArr)) {
            foreach ($targetArr as $productId => $target) {
                $salesPersonToProductArr[$productId] = $productId;
                $quantity[$productId] = $target['quantity'];
                $remarks[$productId] = $target['remarks'];
            }
        }

        $productList = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('product.id', 'product.name', 'measure_unit.name as measure_unit_name')
                        ->whereIn('product.id', $salesPersonToProductArr)
                        ->orderBy('name', 'asc')->get();

        $salesTarget = SalesTarget::select('target', 'lock_status', 'total_quantity')
                        ->where('sales_person_id', $request->sales_person_id)
                        ->where('effective_date', $effectiveDate)->first();



        $view = view('salesTarget.' . $loadView, compact('request', 'effectiveDate'
                        , 'deadline', 'productList', 'targetArr', 'salesTarget', 'remarks', 'quantity'))->render();

        $setsubmitLock = view('salesTarget.setSubmitLockbtn', compact('request', 'salesTarget', 'productList'))->render();
        return response()->json(['html' => $view, 'setsubmitLock' => $setsubmitLock]);
    }

    //product wise total quantiry summary
    public static function quantitySummaryView(Request $request, $loadView, $isConfirmedOrder, $statusType, $status) {

        //product wise quantity summary
        $allowedAllInquiry = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->where('user_group.allowed_for_all_inquiry_access', '1')
                        ->where('users.status', '1')
                        ->where('users.id', Auth::user()->id)->first();

        //sales person access system arr
        $userIdArr = User::where('supervisor_id', Auth::user()->id)->pluck('id')->toArray();
        $userIdArr2 = User::where('id', Auth::user()->id)->pluck('id')->toArray();
        $finalUserIdArr = array_merge($userIdArr, $userIdArr2);

        $quantitySummary = lead::join('inquiry_details', 'inquiry_details.inquiry_id', '=', 'inquiry.id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id', 'measure_unit.name', 'brand.name');

        if ($isConfirmedOrder == 1) {
            $quantitySummary = $quantitySummary->whereIn('inquiry.' . $statusType, $status);
        } else {
            $quantitySummary = $quantitySummary->where('inquiry.' . $statusType, $status);
        }

        if (Auth::user()->group_id != '1' && empty($allowedAllInquiry)) {
            $quantitySummary = $quantitySummary->whereIn('inquiry.salespersons_id', $finalUserIdArr);
        }

        //FILTER START
        if (!empty($request->buyer_id)) {
            $quantitySummary = $quantitySummary->where('inquiry.buyer_id', $request->buyer_id);
        }
        if (!empty($request->salespersons_id)) {
            $quantitySummary = $quantitySummary->where('inquiry.salespersons_id', $request->salespersons_id);
        }
        if (!empty($request->product_id)) {
            $quantitySummary = $quantitySummary->where('inquiry_details.product_id', $request->product_id);
        }
        if (!empty($request->brand_id)) {
            $quantitySummary = $quantitySummary->where('inquiry_details.brand_id', $request->brand_id);
        }

        $fromDate = '';
        if (!empty($request->from_date)) {
            $fromDate = Helper::dateFormatConvert($request->from_date);
            $quantitySummary = $quantitySummary->where('inquiry.creation_date', '>=', $fromDate);
        }
        $toDate = '';
        if (!empty($request->to_date)) {
            $toDate = Helper::dateFormatConvert($request->to_date);
            $quantitySummary = $quantitySummary->where('inquiry.creation_date', '<=', $toDate);
        }

        if (!empty($request->order_no)) {
            $quantitySummary = $quantitySummary->where('inquiry.order_no', $request->order_no);
        }

        $searchText = $request->lc_no;
        if (!empty($searchText)) {
            $quantitySummary = $quantitySummary->where(function ($query) use ($searchText) {
                $query->where('inquiry.lc_no', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->purchase_order_no)) {
            $quantitySummary = $quantitySummary->where('inquiry.purchase_order_no', $request->purchase_order_no);
        }

        $piFromDate = '';
        if (!empty($request->pi_from_date)) {
            $piFromDate = Helper::dateFormatConvert($request->pi_from_date);
            $quantitySummary = $quantitySummary->where('inquiry.pi_date', '>=', $piFromDate);
        }
        $piToDate = '';
        if (!empty($request->pi_to_date)) {
            $piToDate = Helper::dateFormatConvert($request->pi_to_date);
            $quantitySummary = $quantitySummary->where('inquiry.pi_date', '<=', $piToDate);
        }

        //FILTER END
        $quantitySummary = $quantitySummary->select(DB::raw("SUM(inquiry_details.quantity) as total_quantity")
                        , 'inquiry_details.product_id', 'measure_unit.name as measure_unit_name'
                        , 'brand.name as brand_name', 'inquiry_details.brand_id')->get();


        $quantitySummaryArr = $rowspanArr = $productIdArr = $productTotalQty = $unitArr = [];
        if (!$quantitySummary->isEmpty()) {
            foreach ($quantitySummary as $item) {
                $productIdArr[$item->product_id] = $item->product_id;
                $unitArr[$item->product_id] = $item->measure_unit_name;
                $quantitySummaryArr[$item->product_id][$item->brand_id]['brand_id'] = $item->brand_id;
                $quantitySummaryArr[$item->product_id][$item->brand_id]['total_quantity'] = $item->total_quantity;
                $quantitySummaryArr[$item->product_id][$item->brand_id]['brand_name'] = $item->brand_name;
                $quantitySummaryArr[$item->product_id][$item->brand_id]['unit_name'] = $item->measure_unit_name;

                $productTotalQty[$item->product_id] = !empty($productTotalQty[$item->product_id]) ? $productTotalQty[$item->product_id] : 0;
                $productTotalQty[$item->product_id] += $item->total_quantity;
                $rowspanArr[$item->product_id][$item->brand_id] = $item->brand_id;
            }
        }



        $productArr = Product::whereIn('id', $productIdArr)->pluck('name', 'id')->toArray();

        $view = view($loadView, compact('quantitySummaryArr', 'rowspanArr', 'productArr', 'productTotalQty'
                        , 'unitArr'))->render();
        return response()->json(['html' => $view]);
    }

    //end of summary
    //get shipment detail  modal load
    public static function getShipmentFullDetail(Request $request, $loadView) {
//        echo '<pre>';
//        print_r($request->shipment_id);
//        exit;
        //shipment info
        $shipmentInfo = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                        ->leftJoin('po_generate', 'po_generate.inquiry_id', '=', 'delivery.inquiry_id')
                        ->leftJoin('pi_generate', 'pi_generate.inquiry_id', '=', 'delivery.inquiry_id')
                        ->leftJoin('shipping_terms', 'shipping_terms.id', '=', 'po_generate.shipping_term_id')
                        ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->leftJoin('bank', 'bank.id', '=', 'inquiry.bank')
                        ->leftJoin('beneficiary_bank', 'beneficiary_bank.id', '=', 'pi_generate.beneficiary_bank_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->leftJoin('shipping_line', 'shipping_line.id', '=', 'delivery.shipping_line')
                        ->select('delivery.*', 'inquiry.order_no', 'inquiry.lc_date', 'inquiry.lc_no', 'inquiry.order_status'
                                , 'inquiry.note', 'inquiry.lc_transmitted_copy_done', 'buyer.name as buyer_name'
                                , 'inquiry.id as inquiry_id', 'inquiry.purchase_order_no', 'supplier.name as supplier_name'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as salesPersonName")
                                , 'inquiry.creation_date', 'inquiry.confirmation_date', 'inquiry.po_date'
                                , 'shipping_line.name as shipping_line_name', 'inquiry.lc_issue_date'
                                , 'bank.name as lc_opening_bank', 'inquiry.branch as bank_barnch'
                                , 'inquiry.pi_date', 'shipping_terms.name as shipping_terms'
                                , 'po_generate.final_destination as destination_port', 'beneficiary_bank.name as beneficiary_bank_name')
                        ->where('delivery.id', $request->shipment_id)->first();


        //inquiry Details 
        $inquiryDetails = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                        ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                                , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                                , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                                , 'inquiry_details.id')
                        ->where('inquiry_details.inquiry_id', $shipmentInfo->inquiry_id)->get();

        //shipment quantity details
        $deliveryDetailsArr = DeliveryDetails::join('delivery', 'delivery.id', '=', 'delivery_details.delivery_id')
                        ->select('delivery_details.shipment_quantity', 'delivery_details.inquiry_details_id'
                                , 'delivery_details.delivery_id')
                        ->where('delivery.inquiry_id', $shipmentInfo->inquiry_id)->get();

        //commission Info 
        $commissionInfo = CommissionSetup::where('inquiry_id', $shipmentInfo->inquiry_id)->first();

        $shipmentQuantityArr = $quantitySumArr = $dueQuantityArr = [];
        if (!$deliveryDetailsArr->isEmpty()) {
            foreach ($deliveryDetailsArr as $deliveryDetails) {
                $shipmentQuantityArr[$deliveryDetails->inquiry_details_id][$deliveryDetails->delivery_id] = $deliveryDetails->shipment_quantity;
                $quantitySumArr[$deliveryDetails->inquiry_details_id] = array_sum($shipmentQuantityArr[$deliveryDetails->inquiry_details_id]);
            }
        }

        $surplusQuantityArr = [];
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $item) {
                //find remaining quantity of order
                $quantitySum = !empty($quantitySumArr[$item->id]) ? Helper::numberFormatDigit2($quantitySumArr[$item->id]) : 0.00;
                $dueQuantityArr[$item->id] = $item->quantity - $quantitySum;
                $surplusQuantityArr[$item->id] = $quantitySum - $item->quantity;
            }
        }

        $etsInfo = $etaInfo = $containerNo = [];
        $lastEts = $lastEta = [];
        if (!empty($shipmentInfo->ets_info)) {
            $etsInfo = json_decode($shipmentInfo->ets_info, true);
            $lastEts = end($etsInfo);
            krsort($etsInfo);
        }
        if (!empty($shipmentInfo->eta_info)) {
            $etaInfo = json_decode($shipmentInfo->eta_info, true);
            $lastEta = end($etaInfo);
            krsort($etaInfo);
        }
        if (!empty($shipmentInfo->container_no)) {
            $containerNo = json_decode($shipmentInfo->container_no, true);
        }

        $deliveryTime = $transitTime = $totalLeadTime = '--';

        if (!empty($shipmentInfo->lc_issue_date)) {
            $lcIssueDate = date_create($shipmentInfo->lc_issue_date);
            $etsDate = date_create($lastEts['ets_date']);

            $deliveryDay = date_diff($lcIssueDate, $etsDate);
            $deliveryTime = $deliveryDay->format("%a") . Helper::daySpan($deliveryDay->format("%a"));

            if (!empty($lastEta['eta_date'])) {
                $etaDate = date_create($lastEta['eta_date']);
                $transitDay = date_diff($etsDate, $etaDate);
                $transitTime = $transitDay->format("%a") . Helper::daySpan($transitDay->format("%a"));

                $totalLeadDay = date_diff($lcIssueDate, $etaDate);
                $totalLeadTime = $totalLeadDay->format("%a") . Helper::daySpan($totalLeadDay->format("%a"));
            }
        } else {
            $etsDate = date_create($lastEts['ets_date']);

            if (!empty($lastEta['eta_date'])) {
                $etaDate = date_create($lastEta['eta_date']);
                $transitDay = date_diff($etsDate, $etaDate);
                $transitTime = $transitDay->format("%a") . Helper::daySpan($transitDay->format("%a"));
            }
        }


        $leadTimeArr['delivery_time'] = $deliveryTime;
        $leadTimeArr['transit_time'] = $transitTime;
        $leadTimeArr['total_lead_time'] = $totalLeadTime;

        $measureUnit = '';
        $perMeasureUnit = '';

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        if ($request->view == 'print') {
            return view($loadView)->with(compact('shipmentInfo', 'etsInfo', 'etaInfo', 'containerNo'
                                    , 'request', 'quantitySumArr', 'dueQuantityArr', 'inquiryDetails'
                                    , 'shipmentQuantityArr', 'surplusQuantityArr', 'commissionInfo'
                                    , 'leadTimeArr', 'konitaInfo', 'phoneNumber'));
        }

        $view = view($loadView, compact('shipmentInfo', 'etsInfo', 'etaInfo', 'containerNo'
                        , 'request', 'quantitySumArr', 'dueQuantityArr', 'inquiryDetails'
                        , 'shipmentQuantityArr', 'surplusQuantityArr', 'commissionInfo'
                        , 'leadTimeArr'))->render();
        return response()->json(['html' => $view]);
    }

    //end :: shipment details
    //update tracking no
    public static function updateTrackingNo(Request $request, $action = 0) {
        $done = $action == 1 ? 'added' : 'updated';
        $do = $action == 1 ? 'add' : 'update';
        $target = Delivery::find($request->shipment_id);
        $target->express_tracking_no = !empty($request->tracking_no) ? $request->tracking_no : '';

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.EXPRESS_TRACKING_NO_DONE_SUCCESSFULLY', ['done' => $done])), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FALIED_TO_DO_EXPRESS_TRACKING_NO', ['do' => $do])), 401);
        }
    }

    /*     * ********************** satrt :: inquiry reassignment ************************* */

    public static function getInquiryReassigned(Request $request, $loadView) {


        $inquiryInfo = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->join('users', 'users.id', '=', 'inquiry.salespersons_id')
                        ->select('inquiry.order_status', 'buyer.name as buyer_name'
                                , 'inquiry.buyer_id', 'inquiry.id'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person_name")
                                , 'inquiry.creation_date', 'inquiry.salespersons_id')
                        ->where('inquiry.id', $request->inquiry_id)->first();

        //inquiry details
        $inquiryDetailsInfo = InquiryDetails::join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry_details.inquiry_id', $request->inquiry_id)
                ->select('inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.quantity', 'inquiry_details.unit_price'
                        , 'inquiry_details.total_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'grade.name as grade_name', 'inquiry_details.inquiry_id'
                        , 'inquiry_details.id')
                ->get();

        /*         * ********* preparing sales person list ************* */
        //get arrays from inquiry details
        $productIdArr = $brandIdArr = $itemArr = [];
        if (!$inquiryDetailsInfo->isEmpty()) {
            foreach ($inquiryDetailsInfo as $item) {
                $gradeId = $item->grade_id ?? 0;
                $productIdArr[$item->product_id] = $item->product_id;
                $brandIdArr[$item->brand_id] = $item->brand_id;
                $itemArr[$item->product_id][$item->brand_id][$gradeId] = $gradeId;
            }
        }

        //getting all sales persons related to the inquiry products and brands 
        $salesPersonToProductArr = SalesPersonToProduct::select('sales_person_id', 'product_id', 'brand_id')
                        ->whereIn('product_id', $productIdArr)->whereIn('brand_id', $brandIdArr)->get();
        $salesPersonToBuyerArr = SalesPersonToBuyer::where('buyer_id', $inquiryInfo->buyer_id)->pluck('sales_person_id')->toArray();

        //preparing array of set of supplier
        $salesPersonToProductList = [];
        if (!$salesPersonToProductArr->isEmpty()) {
            foreach ($salesPersonToProductArr as $salesPersonToProduct) {
                $salesPersonToProductList[$salesPersonToProduct->product_id][$salesPersonToProduct->brand_id][$salesPersonToProduct->sales_person_id] = $salesPersonToProduct->sales_person_id;
            }
        }

        //preparing array of sales person of the inquiry item sets
        $salesPersonToProductListArr = $salesPersonArr = [];
        if (!empty($itemArr)) {
            foreach ($itemArr as $productId => $brandList) {
                foreach ($brandList as $brandId => $gradeList) {
                    foreach ($gradeList as $gradeId) {
                        if (!empty($salesPersonToProductList[$productId][$brandId])) {
                            $salesPersonToProductListArr[$productId][$brandId][$gradeId] = $salesPersonToProductList[$productId][$brandId];
                            $salesPersonArr[] = $salesPersonToProductListArr[$productId][$brandId][$gradeId] + $salesPersonToBuyerArr;
                        }
                    }
                }
            }
        }



        $commonSalesPersonArr = [];
        if (!empty($salesPersonArr)) {
            //if more than 1 supplier set
            if (count($salesPersonArr) > 1) {
                foreach ($salesPersonArr as $key => $value) {
                    //for 1st supplier set
                    if ($key == 0) {
                        //find common suppliers
                        $commonSalesPersonArr = array_intersect($salesPersonArr[$key], $salesPersonArr[$key + 1]);
                    } else if (count($salesPersonArr) >= 2) {
                        //if 2 or more than 2 supplier set
                        $commonSalesPersonArr = array_intersect($commonSalesPersonArr, $salesPersonArr[$key]);
                    }
                }
            } else {
                //if 1 supplier set
                $commonSalesPersonArr = $salesPersonArr[0];
            }
        }
        $relatedSalesPersonArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                        ->whereIn('users.id', $commonSalesPersonArr)
                        ->where('users.id', '<>', $inquiryInfo->salespersons_id)
                        ->where('users.status', '1')->where('users.allowed_for_sales', '1')
                        ->where('users.supervisor_id', Auth::user()->id)
                        ->pluck('name', 'users.id')->toArray();


        if (in_array(Auth::user()->id, $commonSalesPersonArr)) {
            $authUser = User::join('designation', 'designation.id', '=', 'users.designation_id')
                            ->select(DB::raw("CONCAT(users.employee_id, '-', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                            ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')
                            ->where('users.id', Auth::user()->id)
                            ->where('users.id', '<>', $inquiryInfo->salespersons_id)
                            ->where('users.status', '1')->where('users.allowed_for_sales', '1')
                            ->pluck('name', 'users.id')->toArray();
            if (!empty($authUser)) {
                $relatedSalesPersonArr = $relatedSalesPersonArr + $authUser;
            }
        }
        $salesPersonList = array('0' => __('label.SELECT_SALES_PERSON_OPT')) + $relatedSalesPersonArr;

        /*         * ********* end of preparing suplier list ************* */


        $view = view($loadView, compact('request', 'salesPersonList', 'inquiryInfo'
                        , 'inquiryDetailsInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public static function setInquiryReassigned(Request $request) {

        $rules = [
            'sales_person_id' => 'required|not_in:0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $target = Lead::find($request->inquiry_id);
        $target->salespersons_id = $request->sales_person_id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.INQUIRY_REASSIGNED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FALIED_TO_REASSIGN_INQUIRY')), 401);
        }
    }

    /*     * ********************** end :: inquiry reassignment ************************* */

    //get lead time  modal load
    public static function getLeadTime(Request $request, $loadView) {
        $orderInfo = Lead::select('order_no', 'lc_issue_date')->where('id', $request->inquiry_id)->first();
        $deliveryInfo = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->select('inquiry.id as inquiry_id', 'inquiry.order_no', 'inquiry.lc_issue_date'
                                , 'delivery.id as delivery_id', 'delivery.bl_no', 'delivery.ets_info', 'delivery.eta_info')
                        ->where('inquiry.id', $request->inquiry_id)->get();

        $leadTimeArr = [];
        if (!$deliveryInfo->isEmpty()) {
            foreach ($deliveryInfo as $item) {
                $etsInfoArr = json_decode($item->ets_info, true);
                $etaInfoArr = json_decode($item->eta_info, true);

                $lastEts = end($etsInfoArr);
                $lastEta = end($etaInfoArr);
                $deliveryTime = $transitTime = $totalLeadTime = '--';

                if (!empty($item->lc_issue_date)) {
                    $lcIssueDate = date_create($item->lc_issue_date);
                    $etsDate = date_create($lastEts['ets_date']);

                    $deliveryDay = date_diff($lcIssueDate, $etsDate);
                    $deliveryTime = $deliveryDay->format("%a") . Helper::daySpan($deliveryDay->format("%a"));

                    if (!empty($lastEta['eta_date'])) {
                        $etaDate = date_create($lastEta['eta_date']);
                        $transitDay = date_diff($etsDate, $etaDate);
                        $transitTime = $transitDay->format("%a") . Helper::daySpan($transitDay->format("%a"));

                        $totalLeadDay = date_diff($lcIssueDate, $etaDate);
                        $totalLeadTime = $totalLeadDay->format("%a") . Helper::daySpan($totalLeadDay->format("%a"));
                    }
                } else {
                    $etsDate = date_create($lastEts['ets_date']);

                    if (!empty($lastEta['eta_date'])) {
                        $etaDate = date_create($lastEta['eta_date']);
                        $transitDay = date_diff($etsDate, $etaDate);
                        $transitTime = $transitDay->format("%a") . Helper::daySpan($transitDay->format("%a"));
                    }
                }

                $leadTimeArr[$item->delivery_id]['bl_no'] = $item->bl_no ?? '';
                $leadTimeArr[$item->delivery_id]['ets_date'] = $lastEts['ets_date'] ?? '--';
                $leadTimeArr[$item->delivery_id]['eta_date'] = $lastEta['eta_date'] ?? '--';
                $leadTimeArr[$item->delivery_id]['delivery_time'] = $deliveryTime;
                $leadTimeArr[$item->delivery_id]['transit_time'] = $transitTime;
                $leadTimeArr[$item->delivery_id]['total_lead_time'] = $totalLeadTime;
            }
        }

//        echo '<pre>';
//        print_r($leadTimeArr);
//        exit;

        $view = view($loadView, compact('request', 'orderInfo', 'leadTimeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function orderCancellationModal(Request $request, $loadView) {
        $target = Lead::find($request->inquiry_id);
        $causeList = ['0' => __('label.SELECT_CAUSE_OF_FAILURE_OPT')] + CauseOfFailure::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $view = view($loadView, compact('target', 'causeList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function cancel(Request $request) {
        $target = Lead::find($request->inquiry_id);

        //validation
        $rules = [
            'order_cancel_cause' => 'required|not_in:0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end validation

        $deliveryIdArr = Delivery::where('inquiry_id', $request->inquiry_id)
                        ->pluck('id')->toArray();

        $target->order_status = '6';
        $target->order_cancel_cause = $request->order_cancel_cause;
        $target->order_cancel_remarks = $request->order_cancel_remarks;
        $target->order_cancelled_at = date('Y-m-d H:i:s');
        $target->order_cancelled_by = Auth::user()->id;


        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (!empty($deliveryIdArr)) {
                    DeliveryDetails::whereIn('delivery_id', $deliveryIdArr)->delete();
                }
                Delivery::where('inquiry_id', $request->inquiry_id)->delete();
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.ORDER_CANCELLED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.ORDER_COULD_NOT_BE_CANCELLED')], 401);
        }
    }

    public static function getProductPricingSetup(Request $request, $loadView, $loadFooterView) {
        //check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();

        //product brand list
        $brandArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand.id', 'brand.logo', 'brand.name')
                        ->where('brand.status', '1')
                        ->where('product_to_brand.product_id', $request->product_id)
                        ->get()->toArray();

        $gradeArr = Grade::orderBy('order', 'asc')
                        ->where('status', '1')->pluck('name', 'id')->toArray();
        //product grade list
        $productToGradeArr = ProductToGrade::join('brand', 'brand.id', '=', 'product_to_grade.brand_id')
                ->join('grade', 'grade.id', '=', 'product_to_grade.grade_id')
                ->select('product_to_grade.grade_id', 'product_to_grade.brand_id')
                ->where('brand.status', '1')->where('grade.status', '1')
                ->where('product_to_grade.product_id', $request->product_id)
                ->get();

        $brandWiseGrade = [];
        if (!$productToGradeArr->isEmpty()) {
            foreach ($productToGradeArr as $productToGrade) {
                $brandWiseGrade[$productToGrade->brand_id][$productToGrade->grade_id] = $productToGrade->grade_id;
            }
        }

        $product = Product::select('name')->where('id', $request->product_id)->first();

        //measurement unit
        $unit = Product::join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                        ->select('measure_unit.name')
                        ->where('product.id', $request->product_id)->first();

        //check existing pricing
        $productPricingArr = ProductPricing::select('brand_id', 'grade_id', 'realization_price'
                                , 'target_selling_price', 'minimum_selling_price', 'effective_date'
                                , 'remarks', 'special_note')
                        ->where('product_id', $request->product_id)->get();

        $pricingHistoryBrandArr = $realizationPriceArr = $targetSellingPriceArr = [];
        $minimumSellingPriceArr = $effectiveDateArr = $remarksArr = $specialNoteArr = [];
        if (!$productPricingArr->isEmpty()) {
            foreach ($productPricingArr as $productPricing) {
                $pricingHistoryBrandArr[] = $productPricing->brand_id;
                $gradeId = $productPricing->grade_id ?? 0;
                $realizationPriceArr[$productPricing->brand_id][$gradeId] = $productPricing->realization_price;
                $targetSellingPriceArr[$productPricing->brand_id][$gradeId] = $productPricing->target_selling_price;
                $minimumSellingPriceArr[$productPricing->brand_id][$gradeId] = $productPricing->minimum_selling_price;
                $effectiveDateArr[$productPricing->brand_id][$gradeId] = $productPricing->effective_date;
                $remarksArr[$productPricing->brand_id][$gradeId] = !empty($productPricing->remarks) ? $productPricing->remarks : '';
                $specialNoteArr[$productPricing->brand_id][$gradeId] = !empty($productPricing->special_note) ? $productPricing->special_note : '';
            }
        }

        $view = view($loadView, compact('request', 'brandArr', 'unit'
                        , 'realizationPriceArr', 'targetSellingPriceArr', 'minimumSellingPriceArr'
                        , 'effectiveDateArr', 'pricingHistoryBrandArr', 'product', 'authorised'
                        , 'gradeArr', 'brandWiseGrade', 'remarksArr', 'specialNoteArr'))->render();
        $footer = view($loadFooterView, compact('request', 'brandArr'))->render();
        return response()->json(['html' => $view, 'footer' => $footer]);
    }

    public static function setProductPricing(Request $request) {
        //validation
        $rules = $messages = [];
        $brandName = $request->brand_name;
        $gradeName = $request->grade_name;

        if ($request->authorised_for_realization_price == 1) {
            $realizationPrice = $request->realization_price;
            $specialNote = $request->special_note;
        }

        $targetSellingPrice = $request->target_selling_price;
        $minimumSellingPrice = $request->minimum_selling_price;
        $effectiveDate = $request->effective_date;
        $remarks = $request->remarks;

        if (!empty($request->brand)) {
            foreach ($request->brand as $brandId) {
                if (!empty($request->grade[$brandId])) {
                    foreach ($request->grade[$brandId] as $gradeId) {
                        //for realization price
                        if ($request->authorised_for_realization_price == 1) {
                            if (empty($realizationPrice[$brandId][$gradeId])) {
                                $rules['realization_price.' . $brandId . '.' . $gradeId] = 'required';
                                if ($gradeId == 0) {
                                    $realizationPriceMessage = __('label.REALIZATION_PRICE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                                } else {
                                    $realizationPriceMessage = __('label.REALIZATION_PRICE_IS_REQUIRED_FOR_GRADE_OF_THIS_BRAND', ['grade' => $gradeName[$brandId][$gradeId], 'brand' => $brandName[$brandId]]);
                                }

                                $messages['realization_price.' . $brandId . '.' . $gradeId . '.required'] = $realizationPriceMessage;

                                $rules['special_note.' . $brandId . '.' . $gradeId] = 'required';
                                if ($gradeId == 0) {
                                    $specialNoteMessage = __('label.SPECIAL_NOTE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                                } else {
                                    $specialNoteMessage = __('label.SPECIAL_NOTE_IS_REQUIRED_FOR_GRADE_OF_THIS_BRAND', ['grade' => $gradeName[$brandId][$gradeId], 'brand' => $brandName[$brandId]]);
                                }

                                $messages['special_note.' . $brandId . '.' . $gradeId . '.required'] = $specialNoteMessage;
                            }
                        }

                        //for target selling price
                        if (empty($targetSellingPrice[$brandId][$gradeId])) {
                            $rules['target_selling_price.' . $brandId . '.' . $gradeId] = 'required';
                            if ($gradeId == 0) {
                                $targetSellingPriceMessage = __('label.TARGET_SELLING_PRICE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                            } else {
                                $targetSellingPriceMessage = __('label.TARGET_SELLING_PRICE_IS_REQUIRED_FOR_GRADE_OF_THIS_BRAND', ['grade' => $gradeName[$brandId][$gradeId], 'brand' => $brandName[$brandId]]);
                            }

                            $messages['target_selling_price.' . $brandId . '.' . $gradeId . '.required'] = $targetSellingPriceMessage;
                        }

                        //for minimum selling price
                        if (empty($minimumSellingPrice[$brandId][$gradeId])) {
                            $rules['minimum_selling_price.' . $brandId . '.' . $gradeId] = 'required';
                            if ($gradeId == 0) {
                                $minimumSellingPriceMessage = __('label.MINIMUM_SELLING_PRICE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                            } else {
                                $minimumSellingPriceMessage = __('label.MINIMUM_SELLING_PRICE_IS_REQUIRED_FOR_GRADE_OF_THIS_BRAND', ['grade' => $gradeName[$brandId][$gradeId], 'brand' => $brandName[$brandId]]);
                            }
                            $messages['minimum_selling_price.' . $brandId . '.' . $gradeId . '.required'] = $minimumSellingPriceMessage;
                        }

                        //for effective date
                        if (empty($effectiveDate[$brandId][$gradeId])) {
                            $rules['effective_date.' . $brandId . '.' . $gradeId] = 'required';
                            if ($gradeId == 0) {
                                $effectiveDateMessage = __('label.EFFECTIVE_DATE_IS_REQUIRED_FOR_BRAND', ['brand' => $brandName[$brandId]]);
                            } else {
                                $effectiveDateMessage = __('label.EFFECTIVE_DATE_IS_REQUIRED_FOR_GRADE_OF_THIS_BRAND', ['grade' => $gradeName[$brandId][$gradeId], 'brand' => $brandName[$brandId]]);
                            }
                            $messages['effective_date.' . $brandId . '.' . $gradeId . '.required'] = $effectiveDateMessage;
                        }
                    }
                }
            }
        } else {
            $rules['brand'] = 'required';
            $messages['brand.required'] = __('label.PLEASE_SET_PRICES_TO_ATLEAST_ONE_BRAND');
        }


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $price = $prevPricingHistoryArr = $history = $priceHistory = $brandWisePriceHistory = [];
        $i = 0;
        if (!empty($request->brand)) {

            //get previous pricing history
            $prevPricingHistoryArr = ProductPricingHistory::select('history', 'brand_id', 'grade_id')
                            ->where('product_id', $request->product_id)
                            ->whereIn('brand_id', $request->brand)->get();

            if (!$prevPricingHistoryArr->isEmpty()) {
                foreach ($prevPricingHistoryArr as $prevPricingHistory) {
                    $gradeId = $prevPricingHistory->grade_id ?? 0;
                    $brandWisePriceHistory[$prevPricingHistory->brand_id][$gradeId] = json_decode($prevPricingHistory->history, true);
                }
            }

            //get previous realization pricing
            $prevPricingArr = ProductPricing::select('realization_price', 'brand_id', 'grade_id'
                                    , 'effective_date', 'remarks', 'special_note')
                            ->where('product_id', $request->product_id)
                            ->whereIn('brand_id', $request->brand)->get();

            $prevRealizationPrice = $prevSpecialNote = [];
            if (!$prevPricingArr->isEmpty()) {
                foreach ($prevPricingArr as $prevPricing) {
                    $gradeId = $prevPricing->grade_id ?? 0;
                    $prevRealizationPrice[$prevPricing->brand_id][$gradeId][Helper::formatDate($prevPricing->effective_date)] = $prevPricing->realization_price;
                    $prevSpecialNote[$prevPricing->brand_id][$gradeId][Helper::formatDate($prevPricing->effective_date)] = $prevPricing->special_note;
                }
            }


            foreach ($request->brand as $brandId) {
                if (!empty($request->grade[$brandId])) {
                    foreach ($request->grade[$brandId] as $gradeId) {
                        //data entry to product pricing table
                        $price[$i]['product_id'] = $request->product_id;
                        $price[$i]['brand_id'] = $brandId;
                        $price[$i]['grade_id'] = $gradeId != 0 ? $gradeId : null;
                        $price[$i]['realization_price'] = $request->authorised_for_realization_price == 1 ? $realizationPrice[$brandId][$gradeId] : (!empty($prevRealizationPrice[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]]) ? $prevRealizationPrice[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]] : 0);
                        $price[$i]['target_selling_price'] = $targetSellingPrice[$brandId][$gradeId];
                        $price[$i]['minimum_selling_price'] = $minimumSellingPrice[$brandId][$gradeId];
                        $price[$i]['effective_date'] = Helper::dateFormatConvert($effectiveDate[$brandId][$gradeId]);
                        $price[$i]['remarks'] = $remarks[$brandId][$gradeId];
                        $price[$i]['special_note'] = $request->authorised_for_realization_price == 1 ? $specialNote[$brandId][$gradeId] : (!empty($prevSpecialNote[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]]) ? $prevSpecialNote[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]] : '');
                        $price[$i]['updated_by'] = Auth::user()->id;
                        $price[$i]['updated_at'] = date('Y-m-d H:i:s');

                        $uniqueId = $i . uniqid() . $i;

                        //creating new pricing history
                        $history[$i][$uniqueId]['realization_price'] = $request->authorised_for_realization_price == 1 ? Helper::numberFormatDigit2($realizationPrice[$brandId][$gradeId]) : (!empty($prevRealizationPrice[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]]) ? $prevRealizationPrice[$brandId][$effectiveDate[$brandId][$gradeId]] : Helper::numberFormatDigit2(0));
                        $history[$i][$uniqueId]['target_selling_price'] = Helper::numberFormatDigit2($targetSellingPrice[$brandId][$gradeId]);
                        $history[$i][$uniqueId]['minimum_selling_price'] = Helper::numberFormatDigit2($minimumSellingPrice[$brandId][$gradeId]);
                        $history[$i][$uniqueId]['effective_date'] = $effectiveDate[$brandId][$gradeId];
                        $history[$i][$uniqueId]['remarks'] = $remarks[$brandId][$gradeId];
                        $history[$i][$uniqueId]['special_note'] = $request->authorised_for_realization_price == 1 ? $specialNote[$brandId][$gradeId] : (!empty($prevSpecialNote[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]]) ? $prevSpecialNote[$brandId][$gradeId][$effectiveDate[$brandId][$gradeId]] : '');

                        //merging with previous one
                        if (!empty($brandWisePriceHistory[$brandId][$gradeId])) {
                            $historyArr = array_merge($brandWisePriceHistory[$brandId][$gradeId], $history[$i]);
                        } else {
                            $historyArr = $history[$i];
                        }

                        //data entry to product pricing history table
                        $priceHistory[$i]['product_id'] = $request->product_id;
                        $priceHistory[$i]['brand_id'] = $brandId;
                        $priceHistory[$i]['grade_id'] = $gradeId != 0 ? $gradeId : null;
                        $priceHistory[$i]['history'] = json_encode($historyArr);
                        $priceHistory[$i]['updated_by'] = Auth::user()->id;
                        $priceHistory[$i]['updated_at'] = date('Y-m-d H:i:s');

                        $i++;
                    }
                }
            }
        }

        ProductPricing::where('product_id', $request->product_id)->delete();
        ProductPricingHistory::where('product_id', $request->product_id)->delete();

        if (ProductPricing::insert($price) && ProductPricingHistory::insert($priceHistory)) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.NEW_PRICING_ADDED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_ADD_NEW_PRICING')), 401);
        }
    }

    //************* start :: buyer profile ****************//
    public static function buyerProfile(Request $request, $id, $loadView) {
        $qpArr = $request->all();

        //buyer information
        $target = Buyer::join('buyer_category', 'buyer_category.id', 'buyer.buyer_category_id')
                        ->leftJoin('country', 'country.id', 'buyer.country_id')
                        ->leftJoin('division', 'division.id', 'buyer.division_id')
                        ->select('buyer.id', 'buyer.code', 'buyer.logo', 'buyer.status'
                                , 'buyer.name', 'buyer.head_office_address', 'buyer.created_at'
                                , 'buyer_category.name as category', 'country.name as country'
                                , 'division.name as division', 'buyer.contact_person_data'
                                , 'buyer.fsc_certified', 'buyer.iso_certified', 'buyer.customer_type as type'
                                , 'buyer.related_competitors_product as competitors_product'
                                , 'buyer.related_finished_goods as finished_goods', 'buyer.machine_brand')
                        ->where('buyer.id', $id)->first();
        $typeArr = [];

        //buyer type
        if (!empty($target->type)) {
            $typeArr = explode(",", $target->type);
        }

        //buyer primary factory
        $primaryFactory = BuyerFactory::select('name', 'address')->where('buyer_id', $id)
                        ->where('primary_factory', '1')->where('status', '1')->first();

        //start :: buyer latest followup 
        $followUpPrevHistory = BuyerFollowUpHistory::select('history')
                        ->where('buyer_id', $id)->first();


        if (!empty($followUpPrevHistory)) {
            $followUpHistoryArr = json_decode($followUpPrevHistory->history, true);
            krsort($followUpHistoryArr);
            $i = 0;

            if (!empty($followUpHistoryArr)) {
                foreach ($followUpHistoryArr as $followUpHistory) {
                    $finalArr[$followUpHistory['updated_at']][$i]['status'] = $followUpHistory['status'];
                    $i++;
                }
            }

            krsort($finalArr);
        }

        $latestFollowupArr = $latestFollowup = [];
        if (!empty($finalArr)) {
            foreach ($finalArr as $followUpHistory) {
                $latestFollowup = reset($followUpHistory);
                $latestFollowupArr['status'] = $latestFollowup['status'];
            }
        }

        //end :: buyer latest followup 
        //business start date - pi date of confirmed order for the first time
        $businessInitationDate = Lead::select(DB::raw('MIN(pi_date) as start'))
                ->where('buyer_id', $id)->whereIn('order_status', ['2', '3', '4'])
                ->first();

        //buyer contact person
        $contactPersonArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
        }

        //fineshed goods
        $finishedGoodsArr = [];
        if (!empty($target->finished_goods)) {
            $finishedGoodsArr = json_decode($target->finished_goods, true);
        }

        //competitors' product
        $competitorsProductArr = [];
        if (!empty($target->competitors_product)) {
            $competitorsProductArr = json_decode($target->competitors_product, true);
        }

        $finishedGoodsList = FinishedGoods::pluck('name', 'id')->toArray();
        $competitorsProductList = Product::where('competitors_product', '1')
                        ->pluck('name', 'id')->toArray();

        $contactDesignationList = ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //start :: actively engaged sales person
        $activelyEngagedSalesPersonArr = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                ->join('designation', 'designation.id', 'users.designation_id');
        $activelyEngagedSalesPersonIdArr = $activelyEngagedSalesPersonArr->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')->pluck('users.id', 'users.id')
                ->toArray();
        $activelyEngagedSalesPersonArr = $activelyEngagedSalesPersonArr->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                        , 'designation.title as designation', 'users.photo', 'users.phone'
                        , 'users.id', 'users.employee_id', 'users.email')
                ->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')
                ->orderBy('designation.order', 'asc')
                ->get();

        $activelyEngagedSalesPersonOrderList = [];
        if (!empty($activelyEngagedSalesPersonIdArr)) {
            $activelyEngagedSalesPersonOrderList = Lead::select(DB::raw('COUNT(id) as total_order'), 'salespersons_id')
                            ->groupBy('salespersons_id')->where('buyer_id', $id)
                            ->whereIn('salespersons_id', $activelyEngagedSalesPersonIdArr)
                            ->where('status', '<>', '3')->where('order_status', '<>', '6')
                            ->pluck('total_order', 'salespersons_id')->toArray();
        }
        //end :: actively engaged sales person
        //start :: product info
        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $productInfoArr = $productRowSpanArr = [];
        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;

                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
            }
        }
        //machine type
        $buyerMachineTypeInfoArr = BuyerMachineType::select('product_id', 'brand_id'
                                , 'machine_type_id', 'machine_length')
                        ->where('buyer_id', $id)->get();

        if (!$buyerMachineTypeInfoArr->isEmpty()) {
            foreach ($buyerMachineTypeInfoArr as $machine) {
                $productInfoArr[$machine->product_id]['brand'][$machine->brand_id]['machine_type'] = $machine->machine_type_id;
                $productInfoArr[$machine->product_id]['brand'][$machine->brand_id]['machine_length'] = $machine->machine_length;
            }
        }

        //import volume
        $buyerImportVolumeInfo = BuyerToGsmVolume::join('product', 'product.id', 'buyer_to_gsm_volume.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_gsm_volume.set_gsm_volume', 'buyer_to_gsm_volume.product_id'
                                , 'measure_unit.name as unit')
                        ->where('buyer_to_gsm_volume.buyer_id', $id)->get();

        $importBuyerList = $importVolArr = [];
        if (!$buyerImportVolumeInfo->isEmpty()) {
            foreach ($buyerImportVolumeInfo as $volume) {
                $volumeArr = json_decode($volume->set_gsm_volume, true);
                $gsmVol = 0;
                if (!empty($volumeArr)) {
                    foreach ($volumeArr as $key => $gsmVal) {
                        $gsmVol += (!empty($gsmVal['volume']) ? $gsmVal['volume'] : 0);
                    }
                }

                $importVolArr[$volume->product_id]['unit'] = $volume->unit ?? '';
                $importVolArr[$volume->product_id]['volume'] = $importVolArr[$volume->product_id]['volume'] ?? 0;
                $importVolArr[$volume->product_id]['volume'] += $gsmVol;
            }
        }
        //end :: product info
        //start :: inquiry count
        $inquiryCountInfoArr = Lead::select('id', 'status', 'order_status', 'order_cancel_cause', 'cancel_cause')
                        ->where('buyer_id', $id)->get();

        $inquiryCountArr = $cancelCauseArr = $mostFrequentCancelCauseArr = [];
        if (!$inquiryCountInfoArr->isEmpty()) {
            foreach ($inquiryCountInfoArr as $item) {
                if ($item->status == '1') {
                    $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                    $inquiryCountArr['immatured'] += 1;
                    $inquiryCountArr['upcoming'] = !empty($inquiryCountArr['upcoming']) ? $inquiryCountArr['upcoming'] : 0;
                    $inquiryCountArr['upcoming'] += 1;
                } elseif ($item->status == '2') {
                    if ($item->order_status == '1') {
                        $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                        $inquiryCountArr['immatured'] += 1;
                        $inquiryCountArr['pipeline'] = !empty($inquiryCountArr['pipeline']) ? $inquiryCountArr['pipeline'] : 0;
                        $inquiryCountArr['pipeline'] += 1;
                    } elseif ($item->order_status == '2') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '3') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '4') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['accomplished'] = !empty($inquiryCountArr['accomplished']) ? $inquiryCountArr['accomplished'] : 0;
                        $inquiryCountArr['accomplished'] += 1;
                    } elseif ($item->order_status == '5') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                    } elseif ($item->order_status == '6') {
                        $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                        $inquiryCountArr['failed'] += 1;
                    }
                } elseif ($item->status == '3') {
                    $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                    $inquiryCountArr['failed'] += 1;
                }

                $inquiryCountArr['total'] = !empty($inquiryCountArr['total']) ? $inquiryCountArr['total'] : 0;
                $inquiryCountArr['total'] += 1;

                if (!empty($item->cancel_cause) && $item->cancel_cause != 0) {
                    $cancelCauseArr[$item->cancel_cause] = !empty($cancelCauseArr[$item->cancel_cause]) ? $cancelCauseArr[$item->cancel_cause] : 0;
                    $cancelCauseArr[$item->cancel_cause] += 1;
                }

                if (!empty($item->order_cancel_cause) && $item->order_cancel_cause != 0) {
                    $cancelCauseArr[$item->order_cancel_cause] = !empty($cancelCauseArr[$item->order_cancel_cause]) ? $cancelCauseArr[$item->order_cancel_cause] : 0;
                    $cancelCauseArr[$item->order_cancel_cause] += 1;
                }
            }
        }
        //end :: inquiry count

        if (!empty($cancelCauseArr)) {
            $mostFrequentCancelCauseArr = array_keys($cancelCauseArr, max($cancelCauseArr));
        }

        $cancelCauseList = CauseOfFailure::pluck('title', 'id')->toArray();

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
        $fiveYearsAgo = date("Y-m-d", strtotime("-5 year"));

        $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), DB::raw("SUM(inquiry_details.total_price) as total_amount"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4']);

        $overAllSalesSummaryArr = $salesSummaryInfoArr->first();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($overAllSalesSummaryArr->total_volume) && $overAllSalesSummaryArr->total_volume != 0) ? $overAllSalesSummaryArr->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id] = $volumeRate;
            }
        }

//        echo '<pre>';
//        print_r($overAllSalesSummaryArr->total_volume);
//        print_r($brandWiseVolumeRateArr);
//        exit;

        $lastOneYearSalesSummaryArr = $salesSummaryInfoArr->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])->first();

        $buyerPaymentInfoArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("(delivery_details.shipment_quantity * inquiry_details.unit_price) as amount")
                                , 'delivery.buyer_payment_status', 'delivery_details.shipment_quantity', 'delivery_details.delivery_id'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $buyerPaymentArr = $deliveryIdArr = [];
        if (!$buyerPaymentInfoArr->isEmpty()) {
            foreach ($buyerPaymentInfoArr as $payment) {
                $deliveryIdArr[$payment->delivery_id] = $payment->delivery_id;

                $buyerPaymentArr['due'] = !empty($buyerPaymentArr['due']) ? $buyerPaymentArr['due'] : 0;
                $buyerPaymentArr['paid'] = !empty($buyerPaymentArr['paid']) ? $buyerPaymentArr['paid'] : 0;

                $buyerPaymentArr['shipped_quantity'] = !empty($buyerPaymentArr['shipped_quantity']) ? $buyerPaymentArr['shipped_quantity'] : 0;
                $buyerPaymentArr['shipped_quantity'] += !empty($payment->shipment_quantity) ? $payment->shipment_quantity : 0;

                $buyerPaymentArr['payable'] = !empty($buyerPaymentArr['payable']) ? $buyerPaymentArr['payable'] : 0;
                $buyerPaymentArr['payable'] += !empty($payment->amount) ? $payment->amount : 0;

                if ($payment->buyer_payment_status == '0') {
                    $buyerPaymentArr['due'] += (!empty($payment->amount) ? $payment->amount : 0);
                } else {
                    $buyerPaymentArr['paid'] += (!empty($payment->amount) ? $payment->amount : 0);
                }
            }
        }

        //start :: invoiced amount
        $invoiceInfoArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select('invoice.id as invoice_id', 'invoice.bl_no_history')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $blHistoryArr = [];
        if (!$invoiceInfoArr->isEmpty()) {
            foreach ($invoiceInfoArr as $inv) {
                $blHistoryArr[$inv->invoice_id] = json_decode($inv->bl_no_history, true);
            }
        }
        $invoicedAmount = 0;
        if (!empty($blHistoryArr)) {
            foreach ($blHistoryArr as $invoiceId => $blHistory) {
                if (!empty($blHistory)) {
                    foreach ($blHistory as $deliveryId => $bl) {
                        if (array_key_exists($deliveryId, $deliveryIdArr)) {
                            foreach ($bl as $deliveryDetailsId => $details) {
                                $invoicedAmount = !empty($invoicedAmount) ? $invoicedAmount : 0;
                                $invoicedAmount += !empty($details['shipment_total_price']) ? $details['shipment_total_price'] : 0;
                            }
                        }
                    }
                }
            }
        }
        //end :: invoiced amount
        //start :: received amount & commission
        $received = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.buyer_commission) as total_buyer_commission")
                                , DB::raw("SUM(receive.company_commission + rebate_commission) as net_income")
                                , DB::raw("SUM(receive.collection_amount) as total_collection"))
                        ->where('inquiry.buyer_id', $id)->first();

        $paid = BuyerPayment::select(DB::raw("SUM(amount) as amount"))
                        ->where('buyer_id', $id)->first();

        $commissionReceived = !empty($received->total_buyer_commission) ? $received->total_buyer_commission : 0;
        $commissionPaid = !empty($paid->amount) ? $paid->amount : 0;
        $commissionDue = $commissionReceived - $commissionPaid;
        //end :: received amount & commission


        $startDay = new DateTime($fiveYearsAgo);
        $endDay = new DateTime($today);

        //start :: net income
        $overAllSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.buyer_id', $id)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();

        $netIncome = !empty($overAllSalesSummaryInfoArr) ? array_sum($overAllSalesSummaryInfoArr) : 0;
        //end :: net income
        $last5YearsSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.total_price', 'inquiry_details.quantity', 'inquiry.pi_date'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.buyer_id', $id)
                        ->whereBetween('inquiry.pi_date', [$fiveYearsAgo, $today])->get();

        //start :: sales summary
        if (!$last5YearsSalesSummaryInfoArr->isEmpty()) {
            foreach ($last5YearsSalesSummaryInfoArr as $summary) {
                $summaryArr[$summary->pi_date]['volume'] = $summaryArr[$summary->pi_date]['volume'] ?? 0;
                $summaryArr[$summary->pi_date]['volume'] += $summary->quantity ?? 0;

                $summaryArr[$summary->pi_date]['amount'] = $summaryArr[$summary->pi_date]['amount'] ?? 0;
                $summaryArr[$summary->pi_date]['amount'] += $summary->total_price ?? 0;

                $summaryArr[$summary->pi_date]['net_income'] = $summaryArr[$summary->pi_date]['net_income'] ?? 0;
                $summaryArr[$summary->pi_date]['net_income'] += $summary->net_income ?? 0;
            }
        }

        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $year = $j->format("Y");

            $salesSummaryArr[$year]['volume'] = !empty($salesSummaryArr[$year]['volume']) ? $salesSummaryArr[$year]['volume'] : 0;
            $salesSummaryArr[$year]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr[$year]['amount'] = !empty($salesSummaryArr[$year]['amount']) ? $salesSummaryArr[$year]['amount'] : 0;
            $salesSummaryArr[$year]['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr[$year]['net_income'] = !empty($salesSummaryArr[$year]['net_income']) ? $salesSummaryArr[$year]['net_income'] : 0;
            $salesSummaryArr[$year]['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
            $salesSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr['total']['amount'] = $salesSummaryArr['total']['amount'] ?? 0;
            $salesSummaryArr['total']['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
            $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $yearArr[$year] = $j->format("Y");
        }

        if (!empty($salesSummaryArr)) {
            foreach ($salesSummaryArr as $year => $sales) {
                $prevYear = date("Y", strtotime("-1 year", strtotime($year)));
                $thisYearVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                $thisYearAmount = !empty($sales['amount']) ? $sales['amount'] : 0;
                $thisYearIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                $prevYearVolume = !empty($salesSummaryArr[$prevYear]['volume']) ? $salesSummaryArr[$prevYear]['volume'] : 0;
                $prevYearAmount = !empty($salesSummaryArr[$prevYear]['amount']) ? $salesSummaryArr[$prevYear]['amount'] : 0;
                $prevYearIncome = !empty($salesSummaryArr[$prevYear]['net_income']) ? $salesSummaryArr[$prevYear]['net_income'] : 0;

                $volumeDeviation = (($thisYearVolume - $prevYearVolume) * 100) / ($prevYearVolume > 0 ? $prevYearVolume : 1);
                $amountDeviation = (($thisYearAmount - $prevYearAmount) * 100) / ($prevYearAmount > 0 ? $prevYearAmount : 1);
                $incomeDeviation = (($thisYearIncome - $prevYearIncome) * 100) / ($prevYearIncome > 0 ? $prevYearIncome : 1);

                $salesSummaryArr[$year]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                $salesSummaryArr[$year]['amount_deviation'] = Helper::numberFormatDigit2($amountDeviation);
                $salesSummaryArr[$year]['income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
            }
        }
        //end :: sales summary
//        echo '<pre>';
//        print_r($buyerPaymentArr);
//        print_r($netIncome);
//        exit;
//        echo '<pre>';
//        print_r($yearArr);
//        exit;

        return view($loadView)->with(compact('target', 'qpArr', 'request', 'typeArr'
                                , 'primaryFactory', 'latestFollowupArr', 'businessInitationDate'
                                , 'contactPersonArr', 'contactDesignationList', 'activelyEngagedSalesPersonOrderList'
                                , 'activelyEngagedSalesPersonArr', 'productInfoArr', 'productRowSpanArr'
                                , 'importVolArr', 'competitorsProductArr', 'finishedGoodsArr'
                                , 'finishedGoodsList', 'competitorsProductList', 'inquiryCountArr'
                                , 'cancelCauseList', 'mostFrequentCancelCauseArr', 'overAllSalesSummaryArr'
                                , 'lastOneYearSalesSummaryArr', 'commissionDue', 'invoicedAmount'
                                , 'salesSummaryArr', 'yearArr', 'buyerPaymentArr', 'received'
                                , 'commissionReceived', 'commissionPaid', 'netIncome'
                                , 'brandWiseVolumeRateArr'));
    }

    public static function buyerPrintProfile(Request $request, $id, $loadView, $modueId) {
        $qpArr = $request->all();
        $target = Buyer::join('buyer_category', 'buyer_category.id', 'buyer.buyer_category_id')
                        ->leftJoin('country', 'country.id', 'buyer.country_id')
                        ->leftJoin('division', 'division.id', 'buyer.division_id')
                        ->select('buyer.id', 'buyer.code', 'buyer.logo', 'buyer.status'
                                , 'buyer.name', 'buyer.head_office_address', 'buyer.created_at'
                                , 'buyer_category.name as category', 'country.name as country'
                                , 'division.name as division', 'buyer.contact_person_data'
                                , 'buyer.fsc_certified', 'buyer.iso_certified', 'buyer.customer_type as type'
                                , 'buyer.related_competitors_product as competitors_product'
                                , 'buyer.related_finished_goods as finished_goods', 'buyer.machine_brand')
                        ->where('buyer.id', $id)->first();
        $typeArr = [];
        if (!empty($target->type)) {
            $typeArr = explode(",", $target->type);
        }

        $primaryFactory = BuyerFactory::select('name', 'address')->where('buyer_id', $id)
                        ->where('primary_factory', '1')->where('status', '1')->first();

        //get followup history
        $followUpPrevHistory = BuyerFollowUpHistory::select('history')
                        ->where('buyer_id', $id)->first();


        $finalArr = $followUpHistoryArr = [];
        if (!empty($followUpPrevHistory)) {
            $followUpHistoryArr = json_decode($followUpPrevHistory->history, true);
            krsort($followUpHistoryArr);
            $i = 0;

            if (!empty($followUpHistoryArr)) {
                foreach ($followUpHistoryArr as $followUpHistory) {
                    $finalArr[$followUpHistory['updated_at']][$i]['status'] = $followUpHistory['status'];
                    $i++;
                }
            }

            krsort($finalArr);
        }

        $latestFollowupArr = $latestFollowup = [];
        if (!empty($finalArr)) {
            foreach ($finalArr as $followUpHistory) {
                $latestFollowup = reset($followUpHistory);
                $latestFollowupArr['status'] = $latestFollowup['status'];
            }
        }


        $businessInitationDate = Lead::select(DB::raw('MIN(pi_date) as start'))
                ->where('buyer_id', $id)->whereIn('order_status', ['2', '3', '4'])
                ->first();

        $contactPersonArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
        }

        $finishedGoodsArr = [];
        if (!empty($target->finished_goods)) {
            $finishedGoodsArr = json_decode($target->finished_goods, true);
        }

        $competitorsProductArr = [];
        if (!empty($target->competitors_product)) {
            $competitorsProductArr = json_decode($target->competitors_product, true);
        }

        $finishedGoodsList = FinishedGoods::pluck('name', 'id')->toArray();
        $competitorsProductList = Product::where('competitors_product', '1')
                        ->pluck('name', 'id')->toArray();

        $contactDesignationList = ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        $activelyEngagedSalesPersonArr = SalesPersonToBuyer::join('users', 'users.id', 'sales_person_to_buyer.sales_person_id')
                ->join('designation', 'designation.id', 'users.designation_id');
        $activelyEngagedSalesPersonIdArr = $activelyEngagedSalesPersonArr->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')->pluck('users.id', 'users.id')
                ->toArray();
        $activelyEngagedSalesPersonArr = $activelyEngagedSalesPersonArr->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                        , 'designation.title as designation', 'users.photo', 'users.phone'
                        , 'users.id', 'users.employee_id', 'users.email')
                ->where('sales_person_to_buyer.buyer_id', $id)
                ->where('sales_person_to_buyer.business_status', '1')
                ->orderBy('designation.order', 'asc')
                ->get();

        $activelyEngagedSalesPersonOrderList = [];
        if (!empty($activelyEngagedSalesPersonIdArr)) {
            $activelyEngagedSalesPersonOrderList = Lead::select(DB::raw('COUNT(id) as total_order'), 'salespersons_id')
                            ->groupBy('salespersons_id')->where('buyer_id', $id)
                            ->whereIn('salespersons_id', $activelyEngagedSalesPersonIdArr)
                            ->where('status', '<>', '3')->where('order_status', '<>', '6')
                            ->pluck('total_order', 'salespersons_id')->toArray();
        }

        $buyerToProductInfoArr = BuyerToProduct::join('product', 'product.id', 'buyer_to_product.product_id')
                        ->join('brand', 'brand.id', 'buyer_to_product.brand_id')
                        ->select('buyer_to_product.product_id', 'buyer_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo')
                        ->where('buyer_to_product.buyer_id', $id)->get();

        $productInfoArr = $productRowSpanArr = [];
        if (!$buyerToProductInfoArr->isEmpty()) {
            foreach ($buyerToProductInfoArr as $item) {
                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;

                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
            }
        }
        $buyerMachineTypeInfoArr = BuyerMachineType::select('product_id', 'brand_id'
                                , 'machine_type_id', 'machine_length')
                        ->where('buyer_id', $id)->get();

        if (!$buyerMachineTypeInfoArr->isEmpty()) {
            foreach ($buyerMachineTypeInfoArr as $machine) {
                $productInfoArr[$machine->product_id]['brand'][$machine->brand_id]['machine_type'] = $machine->machine_type_id;
                $productInfoArr[$machine->product_id]['brand'][$machine->brand_id]['machine_length'] = $machine->machine_length;
            }
        }

        $buyerImportVolumeInfo = BuyerToGsmVolume::join('product', 'product.id', 'buyer_to_gsm_volume.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->select('buyer_to_gsm_volume.set_gsm_volume', 'buyer_to_gsm_volume.product_id'
                                , 'measure_unit.name as unit')
                        ->where('buyer_to_gsm_volume.buyer_id', $id)->get();

        $importBuyerList = $importVolArr = [];
        if (!$buyerImportVolumeInfo->isEmpty()) {
            foreach ($buyerImportVolumeInfo as $volume) {
                $volumeArr = json_decode($volume->set_gsm_volume, true);
                $gsmVol = 0;
                if (!empty($volumeArr)) {
                    foreach ($volumeArr as $key => $gsmVal) {
                        $gsmVol += (!empty($gsmVal['volume']) ? $gsmVal['volume'] : 0);
                    }
                }

                $importVolArr[$volume->product_id]['unit'] = $volume->unit ?? '';
                $importVolArr[$volume->product_id]['volume'] = $importVolArr[$volume->product_id]['volume'] ?? 0;
                $importVolArr[$volume->product_id]['volume'] += $gsmVol;
            }
        }

        $inquiryCountInfoArr = Lead::select('id', 'status', 'order_status', 'order_cancel_cause', 'cancel_cause')
                        ->where('buyer_id', $id)->get();

        $inquiryCountArr = $cancelCauseArr = $mostFrequentCancelCauseArr = [];
        if (!$inquiryCountInfoArr->isEmpty()) {
            foreach ($inquiryCountInfoArr as $item) {
                if ($item->status == '1') {
                    $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                    $inquiryCountArr['immatured'] += 1;
                    $inquiryCountArr['upcoming'] = !empty($inquiryCountArr['upcoming']) ? $inquiryCountArr['upcoming'] : 0;
                    $inquiryCountArr['upcoming'] += 1;
                } elseif ($item->status == '2') {
                    if ($item->order_status == '1') {
                        $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                        $inquiryCountArr['immatured'] += 1;
                        $inquiryCountArr['pipeline'] = !empty($inquiryCountArr['pipeline']) ? $inquiryCountArr['pipeline'] : 0;
                        $inquiryCountArr['pipeline'] += 1;
                    } elseif ($item->order_status == '2') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '3') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '4') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['accomplished'] = !empty($inquiryCountArr['accomplished']) ? $inquiryCountArr['accomplished'] : 0;
                        $inquiryCountArr['accomplished'] += 1;
                    } elseif ($item->order_status == '5') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                    } elseif ($item->order_status == '6') {
                        $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                        $inquiryCountArr['failed'] += 1;
                    }
                } elseif ($item->status == '3') {
                    $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                    $inquiryCountArr['failed'] += 1;
                }

                $inquiryCountArr['total'] = !empty($inquiryCountArr['total']) ? $inquiryCountArr['total'] : 0;
                $inquiryCountArr['total'] += 1;

                if (!empty($item->cancel_cause) && $item->cancel_cause != 0) {
                    $cancelCauseArr[$item->cancel_cause] = !empty($cancelCauseArr[$item->cancel_cause]) ? $cancelCauseArr[$item->cancel_cause] : 0;
                    $cancelCauseArr[$item->cancel_cause] += 1;
                }

                if (!empty($item->order_cancel_cause) && $item->order_cancel_cause != 0) {
                    $cancelCauseArr[$item->order_cancel_cause] = !empty($cancelCauseArr[$item->order_cancel_cause]) ? $cancelCauseArr[$item->order_cancel_cause] : 0;
                    $cancelCauseArr[$item->order_cancel_cause] += 1;
                }
            }
        }

        if (!empty($cancelCauseArr)) {
            $mostFrequentCancelCauseArr = array_keys($cancelCauseArr, max($cancelCauseArr));
        }

        $cancelCauseList = CauseOfFailure::pluck('title', 'id')->toArray();

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
        $fiveYearsAgo = date("Y-m-d", strtotime("-5 year"));

        $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), DB::raw("SUM(inquiry_details.total_price) as total_amount"))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4']);

        $overAllSalesSummaryArr = $salesSummaryInfoArr->first();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($overAllSalesSummaryArr->total_volume) && $overAllSalesSummaryArr->total_volume != 0) ? $overAllSalesSummaryArr->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id] = $volumeRate;
            }
        }

        $lastOneYearSalesSummaryArr = $salesSummaryInfoArr->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])->first();

        $buyerPaymentInfoArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("(delivery_details.shipment_quantity * inquiry_details.unit_price) as amount")
                                , 'delivery.buyer_payment_status', 'delivery_details.shipment_quantity', 'delivery_details.delivery_id'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $buyerPaymentArr = $deliveryIdArr = [];
        if (!$buyerPaymentInfoArr->isEmpty()) {
            foreach ($buyerPaymentInfoArr as $payment) {
                $deliveryIdArr[$payment->delivery_id] = $payment->delivery_id;

                $buyerPaymentArr['due'] = !empty($buyerPaymentArr['due']) ? $buyerPaymentArr['due'] : 0;
                $buyerPaymentArr['paid'] = !empty($buyerPaymentArr['paid']) ? $buyerPaymentArr['paid'] : 0;

                $buyerPaymentArr['shipped_quantity'] = !empty($buyerPaymentArr['shipped_quantity']) ? $buyerPaymentArr['shipped_quantity'] : 0;
                $buyerPaymentArr['shipped_quantity'] += !empty($payment->shipment_quantity) ? $payment->shipment_quantity : 0;

                $buyerPaymentArr['payable'] = !empty($buyerPaymentArr['payable']) ? $buyerPaymentArr['payable'] : 0;
                $buyerPaymentArr['payable'] += !empty($payment->amount) ? $payment->amount : 0;

                if ($payment->buyer_payment_status == '0') {
                    $buyerPaymentArr['due'] += (!empty($payment->amount) ? $payment->amount : 0);
                } else {
                    $buyerPaymentArr['paid'] += (!empty($payment->amount) ? $payment->amount : 0);
                }
            }
        }

        $invoiceInfoArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select('invoice.id as invoice_id', 'invoice.bl_no_history')
                        ->where('inquiry.buyer_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $blHistoryArr = [];
        if (!$invoiceInfoArr->isEmpty()) {
            foreach ($invoiceInfoArr as $inv) {
                $blHistoryArr[$inv->invoice_id] = json_decode($inv->bl_no_history, true);
            }
        }
        $invoicedAmount = 0;
        if (!empty($blHistoryArr)) {
            foreach ($blHistoryArr as $invoiceId => $blHistory) {
                if (!empty($blHistory)) {
                    foreach ($blHistory as $deliveryId => $bl) {
                        if (array_key_exists($deliveryId, $deliveryIdArr)) {
                            foreach ($bl as $deliveryDetailsId => $details) {
                                $invoicedAmount = !empty($invoicedAmount) ? $invoicedAmount : 0;
                                $invoicedAmount += !empty($details['shipment_total_price']) ? $details['shipment_total_price'] : 0;
                            }
                        }
                    }
                }
            }
        }

        $received = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.buyer_commission) as total_buyer_commission")
                                , DB::raw("SUM(receive.company_commission + rebate_commission) as net_income")
                                , DB::raw("SUM(receive.collection_amount) as total_collection"))
                        ->where('inquiry.buyer_id', $id)->first();

        $paid = BuyerPayment::select(DB::raw("SUM(amount) as amount"))
                        ->where('buyer_id', $id)->first();

        $commissionReceived = !empty($received->total_buyer_commission) ? $received->total_buyer_commission : 0;
        $commissionPaid = !empty($paid->amount) ? $paid->amount : 0;
        $commissionDue = $commissionReceived - $commissionPaid;


        $startDay = new DateTime($fiveYearsAgo);
        $endDay = new DateTime($today);

        $overAllSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.buyer_id', $id)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();

        $netIncome = !empty($overAllSalesSummaryInfoArr) ? array_sum($overAllSalesSummaryInfoArr) : 0;


        $last5YearsSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.total_price', 'inquiry_details.quantity', 'inquiry.pi_date'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.buyer_id', $id)
                        ->whereBetween('inquiry.pi_date', [$fiveYearsAgo, $today])->get();


        if (!$last5YearsSalesSummaryInfoArr->isEmpty()) {
            foreach ($last5YearsSalesSummaryInfoArr as $summary) {
                $summaryArr[$summary->pi_date]['volume'] = $summaryArr[$summary->pi_date]['volume'] ?? 0;
                $summaryArr[$summary->pi_date]['volume'] += $summary->quantity ?? 0;

                $summaryArr[$summary->pi_date]['amount'] = $summaryArr[$summary->pi_date]['amount'] ?? 0;
                $summaryArr[$summary->pi_date]['amount'] += $summary->total_price ?? 0;

                $summaryArr[$summary->pi_date]['net_income'] = $summaryArr[$summary->pi_date]['net_income'] ?? 0;
                $summaryArr[$summary->pi_date]['net_income'] += $summary->net_income ?? 0;
            }
        }

        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $year = $j->format("Y");

            $salesSummaryArr[$year]['volume'] = !empty($salesSummaryArr[$year]['volume']) ? $salesSummaryArr[$year]['volume'] : 0;
            $salesSummaryArr[$year]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr[$year]['amount'] = !empty($salesSummaryArr[$year]['amount']) ? $salesSummaryArr[$year]['amount'] : 0;
            $salesSummaryArr[$year]['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr[$year]['net_income'] = !empty($salesSummaryArr[$year]['net_income']) ? $salesSummaryArr[$year]['net_income'] : 0;
            $salesSummaryArr[$year]['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
            $salesSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr['total']['amount'] = $salesSummaryArr['total']['amount'] ?? 0;
            $salesSummaryArr['total']['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
            $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $yearArr[$year] = $j->format("Y");
        }

        if (!empty($salesSummaryArr)) {
            foreach ($salesSummaryArr as $year => $sales) {
                $prevYear = date("Y", strtotime("-1 year", strtotime($year)));
                $thisYearVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                $thisYearAmount = !empty($sales['amount']) ? $sales['amount'] : 0;
                $thisYearIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                $prevYearVolume = !empty($salesSummaryArr[$prevYear]['volume']) ? $salesSummaryArr[$prevYear]['volume'] : 0;
                $prevYearAmount = !empty($salesSummaryArr[$prevYear]['amount']) ? $salesSummaryArr[$prevYear]['amount'] : 0;
                $prevYearIncome = !empty($salesSummaryArr[$prevYear]['net_income']) ? $salesSummaryArr[$prevYear]['net_income'] : 0;

                $volumeDeviation = (($thisYearVolume - $prevYearVolume) * 100) / ($prevYearVolume > 0 ? $prevYearVolume : 1);
                $amountDeviation = (($thisYearAmount - $prevYearAmount) * 100) / ($prevYearAmount > 0 ? $prevYearAmount : 1);
                $incomeDeviation = (($thisYearIncome - $prevYearIncome) * 100) / ($prevYearIncome > 0 ? $prevYearIncome : 1);

                $salesSummaryArr[$year]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                $salesSummaryArr[$year]['amount_deviation'] = Helper::numberFormatDigit2($amountDeviation);
                $salesSummaryArr[$year]['income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
            }
        }

        $inquiryInfoArr = Lead::leftJoin('supplier', 'supplier.id', 'inquiry.supplier_id')
                        ->join('users', 'users.id', 'inquiry.salespersons_id')
                        ->select('inquiry.*', 'supplier.name as supplier'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                        ->where('inquiry.buyer_id', $id)->get();



        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                        ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                                , 'grade.name as grade_name', 'measure_unit.name as unit')
                        ->where('inquiry.buyer_id', $id)->get();

        $inquiryDetailsArr = $inquryRowSpanArr = $productRowSpanArr2 = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            $inquryRowSpanArr[$inquiryId] = !empty($inquryRowSpanArr[$inquiryId]) ? $inquryRowSpanArr[$inquiryId] : 0;
                            $inquryRowSpanArr[$inquiryId] += 1;

                            $productRowSpanArr2[$inquiryId][$productId] = !empty($productRowSpanArr2[$inquiryId][$productId]) ? $productRowSpanArr2[$inquiryId][$productId] : 0;
                            $productRowSpanArr2[$inquiryId][$productId] += 1;

                            $brandRowSpanArr[$inquiryId][$productId][$brandId] = !empty($brandRowSpanArr[$inquiryId][$productId][$brandId]) ? $brandRowSpanArr[$inquiryId][$productId][$brandId] : 0;
                            $brandRowSpanArr[$inquiryId][$productId][$brandId] += 1;
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                                , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                        ->where('inquiry.buyer_id', $id)->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


//        echo '<pre>';
//        print_r($inquiryInfoArr->toArray());
//        exit;


        $userAccessArr = self::userAccess();
        if (empty($userAccessArr[$modueId][6])) {
            return redirect('/dashboard');
        }
        return view($loadView)->with(compact('target', 'qpArr', 'request', 'typeArr'
                                , 'primaryFactory', 'latestFollowupArr', 'businessInitationDate'
                                , 'contactPersonArr', 'contactDesignationList', 'activelyEngagedSalesPersonOrderList'
                                , 'activelyEngagedSalesPersonArr', 'productInfoArr', 'productRowSpanArr'
                                , 'importVolArr', 'competitorsProductArr', 'finishedGoodsArr'
                                , 'finishedGoodsList', 'competitorsProductList', 'inquiryCountArr'
                                , 'cancelCauseList', 'mostFrequentCancelCauseArr', 'overAllSalesSummaryArr'
                                , 'lastOneYearSalesSummaryArr', 'commissionDue', 'buyerPaymentArr', 'received'
                                , 'commissionReceived', 'commissionPaid', 'netIncome', 'invoicedAmount'
                                , 'salesSummaryArr', 'yearArr', 'konitaInfo', 'phoneNumber'
                                , 'inquiryInfoArr', 'inquiryDetailsArr', 'inquryRowSpanArr'
                                , 'productRowSpanArr2', 'brandRowSpanArr', 'lsdArr', 'deliveryArr'
                                , 'brandWiseVolumeRateArr'));
    }

    public static function getInvolvedOrderList(Request $request, $loadView, $buyerLogin = 0) {
        $typeList = [
            '1' => __('label.UPCOMING'),
            '2' => !empty($buyerLogin) ? __('label.CONFIRMED') : __('label.PIPE_LINE'),
            '3' => !empty($buyerLogin) ? __('label.IN_PROGRESS') : __('label.CONFIRMED'),
            '4' => __('label.ACCOMPLISHED'),
            '5' => __('label.CANCELLED'),
        ];
        $buyerInfo = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $salesPersonInfo = User::join('designation', 'designation.id', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                                , 'designation.short_name as designation')
                        ->where('users.id', $request->sales_person_id)->first();

        $inquiryInfoArr = Lead::leftJoin('supplier', 'supplier.id', 'inquiry.supplier_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->select('inquiry.*', 'supplier.name as supplier'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', '6');
            } else {
                $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $inquiryInfoArr = $inquiryInfoArr->get();

        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'measure_unit.name as unit')
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', '6');
            } else {
                $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->get();

        $inquiryDetailsArr = $rowspanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;
                $gsm = !empty($details->gsm) ? $details->gsm : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['gsm'] = $details->gsm;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            foreach ($grade['gsm'] as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                        , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', '6');
            } else {
                $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $deliveryInfoArr = $deliveryInfoArr->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

//        echo '<pre>';
//        print_r($inquiryDetailsArr);
//        print_r($inquryRowSpanArr);
//        print_r($productRowSpanArr);
//        print_r($brandRowSpanArr);
//        exit;

        $view = view($loadView, compact('request', 'buyerInfo', 'salesPersonInfo'
                        , 'inquiryInfoArr', 'inquiryDetailsArr', 'rowspanArr'
                        , 'lsdArr', 'deliveryArr', 'typeList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function printInvolvedOrderList(Request $request, $loadView, $modueId, $buyerLogin = 0) {

        $typeList = [
            '1' => __('label.UPCOMING'),
            '2' => !empty($buyerLogin) ? __('label.CONFIRMED') : __('label.PIPE_LINE'),
            '3' => !empty($buyerLogin) ? __('label.IN_PROGRESS') : __('label.CONFIRMED'),
            '4' => __('label.ACCOMPLISHED'),
            '5' => __('label.CANCELLED'),
        ];
        $buyerInfo = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $salesPersonInfo = User::join('designation', 'designation.id', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                                , 'designation.short_name as designation')
                        ->where('users.id', $request->sales_person_id)->first();

        $inquiryInfoArr = Lead::leftJoin('supplier', 'supplier.id', 'inquiry.supplier_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->select('inquiry.*', 'supplier.name as supplier'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', '6');
            } else {
                $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $inquiryInfoArr = $inquiryInfoArr->get();

        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'measure_unit.name as unit')
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', '6');
            } else {
                $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->get();

        $inquiryDetailsArr = $rowspanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;
                $gsm = !empty($details->gsm) ? $details->gsm : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['gsm'] = $details->gsm;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['gsm'][$gsm]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            foreach ($grade['gsm'] as $gsm => $item) {
                                //rowspan for grade
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 0;
                                $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] += 1;
                                //rowspan for brand
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 0;
                                $rowspanArr['brand'][$inquiryId][$productId][$brandId] += 1;
                                //rowspan for product
                                $rowspanArr['product'][$inquiryId][$productId] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 0;
                                $rowspanArr['product'][$inquiryId][$productId] += 1;
                                //rowspan for inquiry
                                $rowspanArr['inquiry'][$inquiryId] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 0;
                                $rowspanArr['inquiry'][$inquiryId] += 1;
                            }
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                        , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                ->where('inquiry.buyer_id', $request->buyer_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $stat = !empty($buyerLogin) ? '2' : '1';
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', $stat);
        } elseif ($request->type_id == 3) {
            $stat = !empty($buyerLogin) ? ['3'] : ['2', '3'];
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', $stat);
        } elseif ($request->type_id == 4) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            if (!empty($buyerLogin)) {
                $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', '6');
            } else {
                $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '1')
                        ->where('inquiry.order_status', '<>', '2')
                        ->where('inquiry.order_status', '<>', '3')
                        ->where('inquiry.order_status', '<>', '4')
                        ->where('inquiry.order_status', '<>', '5');
            }
        }

        $deliveryInfoArr = $deliveryInfoArr->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


        if (empty($buyerLogin)) {
            $userAccessArr = self::userAccess();

            if (empty($userAccessArr[$modueId][6])) {
                return redirect('/dashboard');
            }
        }
        return view($loadView)->with(compact('request', 'buyerInfo', 'salesPersonInfo'
                                , 'inquiryInfoArr', 'inquiryDetailsArr', 'rowspanArr'
                                , 'lsdArr', 'deliveryArr', 'konitaInfo', 'phoneNumber', 'typeList'));
    }

    //************* end :: buyer profile *****************//
    //Opportunity Details
    public static function getOpportunityDetails(Request $request, $loadView) {
        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.id', $request->opportunity_id)->first();

        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $productArr = !empty($target->product_data) ? json_decode($target->product_data, true) : [];

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $buyerList = Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = Product::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = Brand::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = Grade::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = Country::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        $view = view($loadView, compact('request', 'target', 'contactArr', 'productArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getRelatedSalesPersonList(Request $request, $loadView) {
//        echo "<pre>";
//        print_r($request->all());
//        exit;
        $buyerInfo = Buyer::select('name')->where('id', $request->buyer_id)->first();

        $relatedSalesPersonInfoArr = User::join('sales_person_to_buyer', 'sales_person_to_buyer.sales_person_id', 'users.id')
                ->join('designation', 'designation.id', 'users.designation_id')
                ->join('department', 'department.id', 'users.department_id')
                ->join('branch', 'branch.id', 'users.branch_id')
                ->select('users.photo', 'users.employee_id', 'designation.title as designation'
                        , 'department.name as department', 'branch.name as branch', 'users.phone'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name")
                        , 'sales_person_to_buyer.business_status', 'sales_person_to_buyer.sales_person_id')
                ->where('sales_person_to_buyer.buyer_id', $request->buyer_id)
                ->orderBy('department.order', 'asc')
                ->orderBy('designation.order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

        $activeSalesPersonArr = [];
        if (!$relatedSalesPersonInfoArr->isEmpty()) {
            foreach ($relatedSalesPersonInfoArr as $list) {
                if ($list->business_status == '1') {
                    $activeSalesPersonArr[$list->sales_person_id] = $list->sales_person_id;
                }
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        if ($request->view == 'print') {
            return view($loadView, compact('request', 'buyerInfo', 'relatedSalesPersonInfoArr'
                            , 'activeSalesPersonArr', 'konitaInfo', 'phoneNumber'));
        }

        $view = view($loadView, compact('request', 'buyerInfo', 'relatedSalesPersonInfoArr'
                        , 'activeSalesPersonArr'))->render();
        return response()->json(['html' => $view]);
    }

    //******************* Start :: Opportunity Assignment **********************
    public static function getOpportunityToMemberToRelate(Request $request, $loadView) {
        //get employee list
        $memberArr = ['0' => __('label.SELECT_MEMBER_OPT')] + User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.employee_id, ' - ', users.first_name, ' ', users.last_name, ' (', designation.short_name, ')') AS name"), 'users.id')
                        ->orderBy('designation.order', 'asc')->orderBy('name', 'asc')->where('users.status', '1')
                        ->where('users.allowed_for_crm', '1')->pluck('name', 'users.id')->toArray();

        //for opportunity details
        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.id', $request->opportunity_id)->first();

        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $productArr = !empty($target->product_data) ? json_decode($target->product_data, true) : [];


        $buyerList = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = ['0' => __('label.SELECT_BRAND_OPT')] + Brand::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = ['0' => __('label.SELECT_GRADE_OPT')] + Grade::where('status', '1')
                        ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();

        $view = view($loadView, compact('request', 'target', 'contactArr', 'productArr', 'memberArr', 'buyerList'
                        , 'productList', 'brandList', 'gradeList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function relateOpportunityToMember(Request $request) {
        //validation
        $rules = [
            'member_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $opportunityToMember = [
            'member_id' => $request->member_id,
            'opportunity_id' => $request->opportunity_id,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        //delete from opportunity_to_member before insert
        CrmOpportunityToMember::where('opportunity_id', $request->opportunity_id)->delete();
        DB::beginTransaction();
        try {
            if (CrmOpportunityToMember::insert($opportunityToMember)) {
                CrmOpportunity::where('id', $request->opportunity_id)->update(['status' => '1', 'revoked_status' => '0']);
            }
            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.OPPORTUNITY_HAS_RELATED_TO_THIS_MEMBER_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.FAILED_TO_RELATE_OPPORTUNITY_TO_MEMBER')], 401);
        }
    }

    //******************* End :: Opportunity Assignment ************************

    /**
     * 
     * @param type $type(0=my opportunity, 1=all opportunity)
     * @return array $statusList
     * 
     */
    public static function getOpportunityStatusList($type) {
        //$type
        $statusList = ['0' => __('label.SELECT_STATUS_OPT')];
        $statusIndex = 0;
        $crmActivityStatusList = CrmActivityStatus::orderBy('order', 'asc');
        if ($type === 0) {
            $statusList = $statusList + [
                '1' => __('label.IN_PROGRESS'),
            ];
            $statusIndex = 1;
            $crmActivityStatusList = $crmActivityStatusList->where('id', '<>', 7);
        } elseif ($type === 1) {
            $statusList = $statusList + [
                '1' => __('label.NEW_OPPORTUNITY'),
                '2' => __('label.IN_PROGRESS'),
                '3' => __('label.CANCELLED'),
                '4' => __('label.VOID'),
                '5' => __('label.DISPATCHED'),
                '6' => __('label.APPROVED'),
                '7' => __('label.DENIED'),
                '8' => __('label.REVOKED'),
            ];
            $statusIndex = 8;
        }
        $crmActivityStatusList = $crmActivityStatusList->pluck('name', DB::raw("id+'$statusIndex'"))->toArray();

        $statusList = $statusList + $crmActivityStatusList;
        return $statusList;
    }

    /**
     * 
     * @param type $opportunityCountInfo
     * @return array $opportunityCountArr
     * 
     * if new status added, new status to be added on 
     * Method :: getOpportunityCount()  of class :: Common 
     * and also label to be added for new status on view files
     * 
     */
    public static function getOpportunityCount($opportunityCountInfo) {
        $opportunityCountArr = [];
        if (!$opportunityCountInfo->isEmpty()) {
            foreach ($opportunityCountInfo as $op) {
                if ($op->status == '0') {
                    $opportunityCountArr['new'] = !empty($opportunityCountArr['new']) ? $opportunityCountArr['new'] : 0;
                    $opportunityCountArr['new'] += 1;
                } elseif ($op->status == '1') {
                    if ($op->revoked_status == '0') {
                        if ($op->last_activity_status == 0) {
                            $opportunityCountArr['in_progress'] = !empty($opportunityCountArr['in_progress']) ? $opportunityCountArr['in_progress'] : 0;
                            $opportunityCountArr['in_progress'] += 1;
                        } elseif ($op->last_activity_status == 1) {
                            $opportunityCountArr['dead'] = !empty($opportunityCountArr['dead']) ? $opportunityCountArr['dead'] : 0;
                            $opportunityCountArr['dead'] += 1;
                        } elseif ($op->last_activity_status == 2) {
                            $opportunityCountArr['unreachable'] = !empty($opportunityCountArr['unreachable']) ? $opportunityCountArr['unreachable'] : 0;
                            $opportunityCountArr['unreachable'] += 1;
                        } elseif ($op->last_activity_status == 3) {
                            $opportunityCountArr['answering_machine'] = !empty($opportunityCountArr['answering_machine']) ? $opportunityCountArr['answering_machine'] : 0;
                            $opportunityCountArr['answering_machine'] += 1;
                        } elseif ($op->last_activity_status == 4) {
                            $opportunityCountArr['sdc'] = !empty($opportunityCountArr['sdc']) ? $opportunityCountArr['sdc'] : 0;
                            $opportunityCountArr['sdc'] += 1;
                        } elseif ($op->last_activity_status == 5) {
                            $opportunityCountArr['reached'] = !empty($opportunityCountArr['reached']) ? $opportunityCountArr['reached'] : 0;
                            $opportunityCountArr['reached'] += 1;
                        } elseif ($op->last_activity_status == 6) {
                            $opportunityCountArr['not_interested'] = !empty($opportunityCountArr['not_interested']) ? $opportunityCountArr['not_interested'] : 0;
                            $opportunityCountArr['not_interested'] += 1;
                        } elseif ($op->last_activity_status == 8) {
                            $opportunityCountArr['not_booked'] = !empty($opportunityCountArr['not_booked']) ? $opportunityCountArr['not_booked'] : 0;
                            $opportunityCountArr['not_booked'] += 1;
                        } elseif ($op->last_activity_status == 9) {
                            $opportunityCountArr['halt'] = !empty($opportunityCountArr['halt']) ? $opportunityCountArr['halt'] : 0;
                            $opportunityCountArr['halt'] += 1;
                        } elseif ($op->last_activity_status == 10) {
                            $opportunityCountArr['prospective'] = !empty($opportunityCountArr['prospective']) ? $opportunityCountArr['prospective'] : 0;
                            $opportunityCountArr['prospective'] += 1;
                        } elseif ($op->last_activity_status == 11) {
                            $opportunityCountArr['none'] = !empty($opportunityCountArr['none']) ? $opportunityCountArr['none'] : 0;
                            $opportunityCountArr['none'] += 1;
                        } elseif ($op->last_activity_status == 12) {
                            $opportunityCountArr['irrelevant'] = !empty($opportunityCountArr['irrelevant']) ? $opportunityCountArr['irrelevant'] : 0;
                            $opportunityCountArr['irrelevant'] += 1;
                        }
                    } elseif ($op->revoked_status == '1') {
                        $opportunityCountArr['revoked'] = !empty($opportunityCountArr['revoked']) ? $opportunityCountArr['revoked'] : 0;
                        $opportunityCountArr['revoked'] += 1;
                    }
                } elseif ($op->status == '2') {
                    if ($op->dispatch_status == '0') {
                        $opportunityCountArr['booked'] = !empty($opportunityCountArr['booked']) ? $opportunityCountArr['booked'] : 0;
                        $opportunityCountArr['booked'] += 1;
                    } elseif ($op->dispatch_status == '1') {
                        if ($op->approval_status == '0') {
                            $opportunityCountArr['dispatched'] = !empty($opportunityCountArr['dispatched']) ? $opportunityCountArr['dispatched'] : 0;
                            $opportunityCountArr['dispatched'] += 1;
//                        } elseif ($op->approval_status == '1') {
//                            $opportunityCountArr['approved'] = !empty($opportunityCountArr['approved']) ? $opportunityCountArr['approved'] : 0;
//                            $opportunityCountArr['approved'] += 1;
//                        } elseif ($op->approval_status == '2') {
//                            $opportunityCountArr['denied'] = !empty($opportunityCountArr['denied']) ? $opportunityCountArr['denied'] : 0;
//                            $opportunityCountArr['denied'] += 1;
                        }
                    }
                } elseif ($op->status == '3') {
                    $opportunityCountArr['cancelled'] = !empty($opportunityCountArr['cancelled']) ? $opportunityCountArr['cancelled'] : 0;
                    $opportunityCountArr['cancelled'] += 1;
                } elseif ($op->status == '4') {
                    $opportunityCountArr['void'] = !empty($opportunityCountArr['void']) ? $opportunityCountArr['void'] : 0;
                    $opportunityCountArr['void'] += 1;
                }

                $opportunityCountArr['total'] = !empty($opportunityCountArr['total']) ? $opportunityCountArr['total'] : 0;
                $opportunityCountArr['total'] += 1;
            }
        }

        return $opportunityCountArr;
    }

    //************* start :: supplier profile ****************//
    public static function supplierProfile(Request $request, $id, $loadView) {
        $qpArr = $request->all();

        //buyer information
        $target = Supplier::join('supplier_classification', 'supplier_classification.id', 'supplier.supplier_classification_id')
                        ->join('country', 'country.id', 'supplier.country_id')
                        ->select('supplier.id', 'supplier.code', 'supplier.logo', 'supplier.status'
                                , 'supplier.name', 'supplier.address', 'supplier.sign_off_date'
                                , 'supplier_classification.name as classification', 'country.name as country'
                                , 'supplier.contact_person_data', 'supplier.fsc_certified'
                                , 'supplier.fsc_attachment')
                        ->where('supplier.id', $id)->first();


        //business start date - pi date of confirmed order for the first time
        $businessInitationDate = Lead::select(DB::raw('MIN(pi_date) as start'))
                ->where('supplier_id', $id)->whereIn('order_status', ['2', '3', '4'])
                ->first();

        //buyer contact person
        $contactPersonArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
        }

        $contactDesignationList = ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();


        //start :: product info
        $supplierToProductInfoArr = SupplierToProduct::join('product', 'product.id', 'supplier_to_product.product_id')
                        ->join('brand', 'brand.id', 'supplier_to_product.brand_id')
                        ->select('supplier_to_product.product_id', 'supplier_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo')
                        ->where('supplier_to_product.supplier_id', $id)->get();

        $productInfoArr = $productRowSpanArr = [];
        if (!$supplierToProductInfoArr->isEmpty()) {
            foreach ($supplierToProductInfoArr as $item) {
                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;

                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
            }
        }
        //end :: product info
        //beneficiary bank
        $beneficiaryBankInfo = BeneficiaryBank::select('name', 'account_no', 'customer_id', 'branch', 'status')
                        ->where('supplier_id', $id)->get();

        //start :: inquiry count
        $inquiryCountInfoArr = Lead::select('id', 'status', 'order_status', 'order_cancel_cause', 'cancel_cause')
                        ->where('supplier_id', $id)->get();

        $inquiryCountArr = $cancelCauseArr = $mostFrequentCancelCauseArr = [];
        if (!$inquiryCountInfoArr->isEmpty()) {
            foreach ($inquiryCountInfoArr as $item) {
                if ($item->status == '1') {
                    $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                    $inquiryCountArr['immatured'] += 1;
                    $inquiryCountArr['upcoming'] = !empty($inquiryCountArr['upcoming']) ? $inquiryCountArr['upcoming'] : 0;
                    $inquiryCountArr['upcoming'] += 1;
                } elseif ($item->status == '2') {
                    if ($item->order_status == '1') {
                        $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                        $inquiryCountArr['immatured'] += 1;
                        $inquiryCountArr['pipeline'] = !empty($inquiryCountArr['pipeline']) ? $inquiryCountArr['pipeline'] : 0;
                        $inquiryCountArr['pipeline'] += 1;
                    } elseif ($item->order_status == '2') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '3') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '4') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['accomplished'] = !empty($inquiryCountArr['accomplished']) ? $inquiryCountArr['accomplished'] : 0;
                        $inquiryCountArr['accomplished'] += 1;
                    } elseif ($item->order_status == '5') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                    } elseif ($item->order_status == '6') {
                        $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                        $inquiryCountArr['failed'] += 1;
                    }
                } elseif ($item->status == '3') {
                    $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                    $inquiryCountArr['failed'] += 1;
                }

                $inquiryCountArr['total'] = !empty($inquiryCountArr['total']) ? $inquiryCountArr['total'] : 0;
                $inquiryCountArr['total'] += 1;

                if (!empty($item->cancel_cause) && $item->cancel_cause != 0) {
                    $cancelCauseArr[$item->cancel_cause] = !empty($cancelCauseArr[$item->cancel_cause]) ? $cancelCauseArr[$item->cancel_cause] : 0;
                    $cancelCauseArr[$item->cancel_cause] += 1;
                }

                if (!empty($item->order_cancel_cause) && $item->order_cancel_cause != 0) {
                    $cancelCauseArr[$item->order_cancel_cause] = !empty($cancelCauseArr[$item->order_cancel_cause]) ? $cancelCauseArr[$item->order_cancel_cause] : 0;
                    $cancelCauseArr[$item->order_cancel_cause] += 1;
                }
            }
        }
        //end :: inquiry count

        if (!empty($cancelCauseArr)) {
            $mostFrequentCancelCauseArr = array_keys($cancelCauseArr, max($cancelCauseArr));
        }

        $cancelCauseList = CauseOfFailure::pluck('title', 'id')->toArray();

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
        $fiveYearsAgo = date("Y-m-d", strtotime("-5 year"));

        $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), DB::raw("SUM(inquiry_details.total_price) as total_amount"))
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4']);

        $overAllSalesSummaryArr = $salesSummaryInfoArr->first();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($overAllSalesSummaryArr->total_volume) && $overAllSalesSummaryArr->total_volume != 0) ? $overAllSalesSummaryArr->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id] = $volumeRate;
            }
        }

//        echo '<pre>';
//        print_r($overAllSalesSummaryArr->total_volume);
//        print_r($brandWiseVolumeRateArr);
//        exit;

        $lastOneYearSalesSummaryArr = $salesSummaryInfoArr->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])->first();

        $supplierPaymentInfoArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("(delivery_details.shipment_quantity * inquiry_details.unit_price) as amount")
                                , 'delivery.buyer_payment_status', 'delivery_details.shipment_quantity', 'delivery_details.delivery_id'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $supplierPaymentArr = $deliveryIdArr = [];
        if (!$supplierPaymentInfoArr->isEmpty()) {
            foreach ($supplierPaymentInfoArr as $payment) {
                $deliveryIdArr[$payment->delivery_id] = $payment->delivery_id;

                $supplierPaymentArr['due'] = !empty($supplierPaymentArr['due']) ? $supplierPaymentArr['due'] : 0;
                $supplierPaymentArr['paid'] = !empty($supplierPaymentArr['paid']) ? $supplierPaymentArr['paid'] : 0;

                $supplierPaymentArr['shipped_quantity'] = !empty($supplierPaymentArr['shipped_quantity']) ? $supplierPaymentArr['shipped_quantity'] : 0;
                $supplierPaymentArr['shipped_quantity'] += !empty($payment->shipment_quantity) ? $payment->shipment_quantity : 0;

                $supplierPaymentArr['payable'] = !empty($supplierPaymentArr['payable']) ? $supplierPaymentArr['payable'] : 0;
                $supplierPaymentArr['payable'] += !empty($payment->amount) ? $payment->amount : 0;

                if ($payment->supplier_payment_status == '0') {
                    $supplierPaymentArr['due'] += (!empty($payment->amount) ? $payment->amount : 0);
                } else {
                    $supplierPaymentArr['paid'] += (!empty($payment->amount) ? $payment->amount : 0);
                }
            }
        }

        //start :: invoiced amount
        $invoiceInfoArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select('invoice.id as invoice_id', 'invoice.bl_no_history')
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $blHistoryArr = [];
        if (!$invoiceInfoArr->isEmpty()) {
            foreach ($invoiceInfoArr as $inv) {
                $blHistoryArr[$inv->invoice_id] = json_decode($inv->bl_no_history, true);
            }
        }
        $invoicedAmount = 0;
        if (!empty($blHistoryArr)) {
            foreach ($blHistoryArr as $invoiceId => $blHistory) {
                if (!empty($blHistory)) {
                    foreach ($blHistory as $deliveryId => $bl) {
                        if (array_key_exists($deliveryId, $deliveryIdArr)) {
                            foreach ($bl as $deliveryDetailsId => $details) {
                                $invoicedAmount = !empty($invoicedAmount) ? $invoicedAmount : 0;
                                $invoicedAmount += !empty($details['shipment_total_price']) ? $details['shipment_total_price'] : 0;
                            }
                        }
                    }
                }
            }
        }
        //end :: invoiced amount
        //start :: received amount & commission
        $received = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.company_commission + rebate_commission) as net_income")
                                , DB::raw("SUM(receive.collection_amount) as total_collection"))
                        ->where('inquiry.supplier_id', $id)->first();

        $commissionOnSalesVolumeArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->select(DB::raw('(commission_setup.principle_cmsn * inquiry_details.quantity) as total')
                                , 'inquiry_details.id')
                        ->pluck('total', 'inquiry_details.id')->toArray();
        $commissionOnSalesVolume = !empty($commissionOnSalesVolumeArr) ? array_sum($commissionOnSalesVolumeArr) : 0;

        $commissionOnShippedVolumeArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry.id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->select(DB::raw('(commission_setup.principle_cmsn * delivery_details.shipment_quantity) as total')
                                , 'delivery_details.id')
                        ->pluck('total', 'delivery_details.id')->toArray();
        $commissionOnShippedVolume = !empty($commissionOnShippedVolumeArr) ? array_sum($commissionOnShippedVolumeArr) : 0;

        $commissionPaidThroughInvoice = InvoiceCommissionHistory::join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as total'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)->first();

//        echo '<pre>';
//        print_r($commissionOnSalesVolume->toArray());
//        print_r($commissionOnShippedVolume->toArray());
//        print_r($commissionPaidThroughInvoice->toArray());
//        exit;
        //end :: received amount & commission


        $startDay = new DateTime($fiveYearsAgo);
        $endDay = new DateTime($today);

        //start :: net income
        $overAllSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.supplier_id', $id)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();

        $netIncome = !empty($overAllSalesSummaryInfoArr) ? array_sum($overAllSalesSummaryInfoArr) : 0;

        //end :: net income

        $last5YearsSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.total_price', 'inquiry_details.quantity', 'inquiry.pi_date'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->whereBetween('inquiry.pi_date', [$fiveYearsAgo, $today])->get();

        //start :: sales summary
        if (!$last5YearsSalesSummaryInfoArr->isEmpty()) {
            foreach ($last5YearsSalesSummaryInfoArr as $summary) {
                $summaryArr[$summary->pi_date]['volume'] = $summaryArr[$summary->pi_date]['volume'] ?? 0;
                $summaryArr[$summary->pi_date]['volume'] += $summary->quantity ?? 0;

                $summaryArr[$summary->pi_date]['amount'] = $summaryArr[$summary->pi_date]['amount'] ?? 0;
                $summaryArr[$summary->pi_date]['amount'] += $summary->total_price ?? 0;

                $summaryArr[$summary->pi_date]['net_income'] = $summaryArr[$summary->pi_date]['net_income'] ?? 0;
                $summaryArr[$summary->pi_date]['net_income'] += $summary->net_income ?? 0;
            }
        }

        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $year = $j->format("Y");

            $salesSummaryArr[$year]['volume'] = !empty($salesSummaryArr[$year]['volume']) ? $salesSummaryArr[$year]['volume'] : 0;
            $salesSummaryArr[$year]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr[$year]['amount'] = !empty($salesSummaryArr[$year]['amount']) ? $salesSummaryArr[$year]['amount'] : 0;
            $salesSummaryArr[$year]['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr[$year]['net_income'] = !empty($salesSummaryArr[$year]['net_income']) ? $salesSummaryArr[$year]['net_income'] : 0;
            $salesSummaryArr[$year]['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
            $salesSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr['total']['amount'] = $salesSummaryArr['total']['amount'] ?? 0;
            $salesSummaryArr['total']['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
            $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $yearArr[$year] = $j->format("Y");
        }

        if (!empty($salesSummaryArr)) {
            foreach ($salesSummaryArr as $year => $sales) {
                $prevYear = date("Y", strtotime("-1 year", strtotime($year)));
                $thisYearVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                $thisYearAmount = !empty($sales['amount']) ? $sales['amount'] : 0;
                $thisYearIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                $prevYearVolume = !empty($salesSummaryArr[$prevYear]['volume']) ? $salesSummaryArr[$prevYear]['volume'] : 0;
                $prevYearAmount = !empty($salesSummaryArr[$prevYear]['amount']) ? $salesSummaryArr[$prevYear]['amount'] : 0;
                $prevYearIncome = !empty($salesSummaryArr[$prevYear]['net_income']) ? $salesSummaryArr[$prevYear]['net_income'] : 0;

                $volumeDeviation = (($thisYearVolume - $prevYearVolume) * 100) / ($prevYearVolume > 0 ? $prevYearVolume : 1);
                $amountDeviation = (($thisYearAmount - $prevYearAmount) * 100) / ($prevYearAmount > 0 ? $prevYearAmount : 1);
                $incomeDeviation = (($thisYearIncome - $prevYearIncome) * 100) / ($prevYearIncome > 0 ? $prevYearIncome : 1);

                $salesSummaryArr[$year]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                $salesSummaryArr[$year]['amount_deviation'] = Helper::numberFormatDigit2($amountDeviation);
                $salesSummaryArr[$year]['income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
            }
        }
        //end :: sales summary
//        echo '<pre>';
//        print_r($buyerPaymentArr);
//        print_r($netIncome);
//        exit;
//        echo '<pre>';
//        print_r($yearArr);
//        exit;

        return view($loadView)->with(compact('target', 'qpArr', 'request', 'businessInitationDate'
                                , 'contactPersonArr', 'contactDesignationList', 'productInfoArr', 'productRowSpanArr'
                                , 'inquiryCountArr', 'cancelCauseList', 'mostFrequentCancelCauseArr'
                                , 'overAllSalesSummaryArr', 'lastOneYearSalesSummaryArr', 'invoicedAmount'
                                , 'salesSummaryArr', 'yearArr', 'received', 'supplierPaymentArr'
                                , 'netIncome', 'brandWiseVolumeRateArr', 'beneficiaryBankInfo'
                                , 'commissionOnSalesVolume', 'commissionOnShippedVolume', 'commissionPaidThroughInvoice'));
    }

    public static function supplierPrintProfile(Request $request, $id, $loadView, $modueId) {
        $qpArr = $request->all();

        //buyer information
        $target = Supplier::join('supplier_classification', 'supplier_classification.id', 'supplier.supplier_classification_id')
                        ->join('country', 'country.id', 'supplier.country_id')
                        ->select('supplier.id', 'supplier.code', 'supplier.logo', 'supplier.status'
                                , 'supplier.name', 'supplier.address', 'supplier.sign_off_date'
                                , 'supplier_classification.name as classification', 'country.name as country'
                                , 'supplier.contact_person_data', 'supplier.fsc_certified'
                                , 'supplier.fsc_attachment')
                        ->where('supplier.id', $id)->first();


        //business start date - pi date of confirmed order for the first time
        $businessInitationDate = Lead::select(DB::raw('MIN(pi_date) as start'))
                ->where('supplier_id', $id)->whereIn('order_status', ['2', '3', '4'])
                ->first();

        //buyer contact person
        $contactPersonArr = [];
        if (!empty($target->contact_person_data)) {
            $contactPersonArr = json_decode($target->contact_person_data, true);
        }

        $contactDesignationList = ContactDesignation::where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //start :: product info
        $supplierToProductInfoArr = SupplierToProduct::join('product', 'product.id', 'supplier_to_product.product_id')
                        ->join('brand', 'brand.id', 'supplier_to_product.brand_id')
                        ->select('supplier_to_product.product_id', 'supplier_to_product.brand_id'
                                , 'product.name as product_name', 'brand.name as brand_name'
                                , 'brand.logo as logo')
                        ->where('supplier_to_product.supplier_id', $id)->get();

        $productInfoArr = $productRowSpanArr = [];
        if (!$supplierToProductInfoArr->isEmpty()) {
            foreach ($supplierToProductInfoArr as $item) {
                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['brand_name'] = $item->brand_name;
                $productInfoArr[$item->product_id]['brand'][$item->brand_id]['logo'] = $item->logo;

                $productRowSpanArr[$item->product_id]['brand'] = !empty($productInfoArr[$item->product_id]['brand']) ? count($productInfoArr[$item->product_id]['brand']) : 1;
            }
        }
        //end :: product info
        //beneficiary bank
        $beneficiaryBankInfo = BeneficiaryBank::select('name', 'account_no', 'customer_id', 'branch', 'status')
                        ->where('supplier_id', $id)->get();

        //start :: inquiry count
        $inquiryCountInfoArr = Lead::select('id', 'status', 'order_status', 'order_cancel_cause', 'cancel_cause')
                        ->where('supplier_id', $id)->get();

        $inquiryCountArr = $cancelCauseArr = $mostFrequentCancelCauseArr = [];
        if (!$inquiryCountInfoArr->isEmpty()) {
            foreach ($inquiryCountInfoArr as $item) {
                if ($item->status == '1') {
                    $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                    $inquiryCountArr['immatured'] += 1;
                    $inquiryCountArr['upcoming'] = !empty($inquiryCountArr['upcoming']) ? $inquiryCountArr['upcoming'] : 0;
                    $inquiryCountArr['upcoming'] += 1;
                } elseif ($item->status == '2') {
                    if ($item->order_status == '1') {
                        $inquiryCountArr['immatured'] = !empty($inquiryCountArr['immatured']) ? $inquiryCountArr['immatured'] : 0;
                        $inquiryCountArr['immatured'] += 1;
                        $inquiryCountArr['pipeline'] = !empty($inquiryCountArr['pipeline']) ? $inquiryCountArr['pipeline'] : 0;
                        $inquiryCountArr['pipeline'] += 1;
                    } elseif ($item->order_status == '2') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '3') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['confirmed'] = !empty($inquiryCountArr['confirmed']) ? $inquiryCountArr['confirmed'] : 0;
                        $inquiryCountArr['confirmed'] += 1;
                    } elseif ($item->order_status == '4') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                        $inquiryCountArr['accomplished'] = !empty($inquiryCountArr['accomplished']) ? $inquiryCountArr['accomplished'] : 0;
                        $inquiryCountArr['accomplished'] += 1;
                    } elseif ($item->order_status == '5') {
                        $inquiryCountArr['matured'] = !empty($inquiryCountArr['matured']) ? $inquiryCountArr['matured'] : 0;
                        $inquiryCountArr['matured'] += 1;
                    } elseif ($item->order_status == '6') {
                        $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                        $inquiryCountArr['failed'] += 1;
                    }
                } elseif ($item->status == '3') {
                    $inquiryCountArr['failed'] = !empty($inquiryCountArr['failed']) ? $inquiryCountArr['failed'] : 0;
                    $inquiryCountArr['failed'] += 1;
                }

                $inquiryCountArr['total'] = !empty($inquiryCountArr['total']) ? $inquiryCountArr['total'] : 0;
                $inquiryCountArr['total'] += 1;

                if (!empty($item->cancel_cause) && $item->cancel_cause != 0) {
                    $cancelCauseArr[$item->cancel_cause] = !empty($cancelCauseArr[$item->cancel_cause]) ? $cancelCauseArr[$item->cancel_cause] : 0;
                    $cancelCauseArr[$item->cancel_cause] += 1;
                }

                if (!empty($item->order_cancel_cause) && $item->order_cancel_cause != 0) {
                    $cancelCauseArr[$item->order_cancel_cause] = !empty($cancelCauseArr[$item->order_cancel_cause]) ? $cancelCauseArr[$item->order_cancel_cause] : 0;
                    $cancelCauseArr[$item->order_cancel_cause] += 1;
                }
            }
        }
        //end :: inquiry count

        if (!empty($cancelCauseArr)) {
            $mostFrequentCancelCauseArr = array_keys($cancelCauseArr, max($cancelCauseArr));
        }

        $cancelCauseList = CauseOfFailure::pluck('title', 'id')->toArray();

        $today = date("Y-m-d");
        $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
        $fiveYearsAgo = date("Y-m-d", strtotime("-5 year"));

        $salesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), DB::raw("SUM(inquiry_details.total_price) as total_amount"))
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4']);

        $overAllSalesSummaryArr = $salesSummaryInfoArr->first();

        $brandWiseSalesVolumeInfo = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("SUM(inquiry_details.quantity) as total_volume"), 'inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->groupBy('inquiry_details.product_id', 'inquiry_details.brand_id')
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $brandWiseVolumeRateArr = [];
        if (!$brandWiseSalesVolumeInfo->isEmpty()) {
            $totalSalesVolume = (!empty($overAllSalesSummaryArr->total_volume) && $overAllSalesSummaryArr->total_volume != 0) ? $overAllSalesSummaryArr->total_volume : 1;
            foreach ($brandWiseSalesVolumeInfo as $info) {
                $volumeRate = ($info->total_volume / $totalSalesVolume) * 100;
                $brandWiseVolumeRateArr[$info->product_id][$info->brand_id] = $volumeRate;
            }
        }

//        echo '<pre>';
//        print_r($overAllSalesSummaryArr->total_volume);
//        print_r($brandWiseVolumeRateArr);
//        exit;

        $lastOneYearSalesSummaryArr = $salesSummaryInfoArr->whereBetween('inquiry.pi_date', [$oneYearAgo, $today])->first();

        $supplierPaymentInfoArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry_details', 'inquiry_details.id', 'delivery_details.inquiry_details_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select(DB::raw("(delivery_details.shipment_quantity * inquiry_details.unit_price) as amount")
                                , 'delivery.buyer_payment_status', 'delivery_details.shipment_quantity', 'delivery_details.delivery_id'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $supplierPaymentArr = $deliveryIdArr = [];
        if (!$supplierPaymentInfoArr->isEmpty()) {
            foreach ($supplierPaymentInfoArr as $payment) {
                $deliveryIdArr[$payment->delivery_id] = $payment->delivery_id;

                $supplierPaymentArr['due'] = !empty($supplierPaymentArr['due']) ? $supplierPaymentArr['due'] : 0;
                $supplierPaymentArr['paid'] = !empty($supplierPaymentArr['paid']) ? $supplierPaymentArr['paid'] : 0;

                $supplierPaymentArr['shipped_quantity'] = !empty($supplierPaymentArr['shipped_quantity']) ? $supplierPaymentArr['shipped_quantity'] : 0;
                $supplierPaymentArr['shipped_quantity'] += !empty($payment->shipment_quantity) ? $payment->shipment_quantity : 0;

                $supplierPaymentArr['payable'] = !empty($supplierPaymentArr['payable']) ? $supplierPaymentArr['payable'] : 0;
                $supplierPaymentArr['payable'] += !empty($payment->amount) ? $payment->amount : 0;

                if ($payment->supplier_payment_status == '0') {
                    $supplierPaymentArr['due'] += (!empty($payment->amount) ? $payment->amount : 0);
                } else {
                    $supplierPaymentArr['paid'] += (!empty($payment->amount) ? $payment->amount : 0);
                }
            }
        }

        //start :: invoiced amount
        $invoiceInfoArr = InvoiceCommissionHistory::join('invoice', 'invoice.id', 'invoice_commission_history.invoice_id')
                        ->join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select('invoice.id as invoice_id', 'invoice.bl_no_history')
                        ->where('inquiry.supplier_id', $id)->whereIn('inquiry.order_status', ['2', '3', '4'])->get();

        $blHistoryArr = [];
        if (!$invoiceInfoArr->isEmpty()) {
            foreach ($invoiceInfoArr as $inv) {
                $blHistoryArr[$inv->invoice_id] = json_decode($inv->bl_no_history, true);
            }
        }
        $invoicedAmount = 0;
        if (!empty($blHistoryArr)) {
            foreach ($blHistoryArr as $invoiceId => $blHistory) {
                if (!empty($blHistory)) {
                    foreach ($blHistory as $deliveryId => $bl) {
                        if (array_key_exists($deliveryId, $deliveryIdArr)) {
                            foreach ($bl as $deliveryDetailsId => $details) {
                                $invoicedAmount = !empty($invoicedAmount) ? $invoicedAmount : 0;
                                $invoicedAmount += !empty($details['shipment_total_price']) ? $details['shipment_total_price'] : 0;
                            }
                        }
                    }
                }
            }
        }
        //end :: invoiced amount
        //start :: received amount & commission
        $received = Receive::join('inquiry', 'inquiry.id', 'receive.inquiry_id')
                        ->select(DB::raw("SUM(receive.company_commission + rebate_commission) as net_income")
                                , DB::raw("SUM(receive.collection_amount) as total_collection"))
                        ->where('inquiry.supplier_id', $id)->first();

        $commissionOnSalesVolumeArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->select(DB::raw('(commission_setup.principle_cmsn * inquiry_details.quantity) as total')
                                , 'inquiry_details.id')
                        ->pluck('total', 'inquiry_details.id')->toArray();
        $commissionOnSalesVolume = !empty($commissionOnSalesVolumeArr) ? array_sum($commissionOnSalesVolumeArr) : 0;

        $commissionOnShippedVolumeArr = DeliveryDetails::join('delivery', 'delivery.id', 'delivery_details.delivery_id')
                        ->join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry.id')
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->select(DB::raw('(commission_setup.principle_cmsn * delivery_details.shipment_quantity) as total')
                                , 'delivery_details.id')
                        ->pluck('total', 'delivery_details.id')->toArray();
        $commissionOnShippedVolume = !empty($commissionOnShippedVolumeArr) ? array_sum($commissionOnShippedVolumeArr) : 0;

        $commissionPaidThroughInvoice = InvoiceCommissionHistory::join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                        ->select(DB::raw('SUM(invoice_commission_history.total_principle_cmsn) as total'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)->first();

//        echo '<pre>';
//        print_r($commissionOnSalesVolume->toArray());
//        print_r($commissionOnShippedVolume->toArray());
//        print_r($commissionPaidThroughInvoice->toArray());
//        exit;
        //end :: received amount & commission


        $startDay = new DateTime($fiveYearsAgo);
        $endDay = new DateTime($today);

        //start :: net income
        $overAllSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                ->whereIn('inquiry.order_status', ['2', '3', '4'])
                ->where('inquiry.supplier_id', $id)
                ->select(DB::raw("((commission_setup.konita_cmsn+commission_setup.rebate_cmsn)*inquiry_details.quantity) as total"), 'inquiry_details.id')
                ->pluck('total', 'inquiry_details.id')
                ->toArray();

        $netIncome = !empty($overAllSalesSummaryInfoArr) ? array_sum($overAllSalesSummaryInfoArr) : 0;

        //end :: net income

        $last5YearsSalesSummaryInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', 'inquiry_details.inquiry_id')
                        ->select('inquiry_details.total_price', 'inquiry_details.quantity', 'inquiry.pi_date'
                                , DB::raw('((commission_setup.konita_cmsn + commission_setup.rebate_cmsn) * inquiry_details.quantity) as net_income'))
                        ->whereIn('inquiry.order_status', ['2', '3', '4'])
                        ->where('inquiry.supplier_id', $id)
                        ->whereBetween('inquiry.pi_date', [$fiveYearsAgo, $today])->get();

        //start :: sales summary
        if (!$last5YearsSalesSummaryInfoArr->isEmpty()) {
            foreach ($last5YearsSalesSummaryInfoArr as $summary) {
                $summaryArr[$summary->pi_date]['volume'] = $summaryArr[$summary->pi_date]['volume'] ?? 0;
                $summaryArr[$summary->pi_date]['volume'] += $summary->quantity ?? 0;

                $summaryArr[$summary->pi_date]['amount'] = $summaryArr[$summary->pi_date]['amount'] ?? 0;
                $summaryArr[$summary->pi_date]['amount'] += $summary->total_price ?? 0;

                $summaryArr[$summary->pi_date]['net_income'] = $summaryArr[$summary->pi_date]['net_income'] ?? 0;
                $summaryArr[$summary->pi_date]['net_income'] += $summary->net_income ?? 0;
            }
        }

        for ($j = $startDay; $j <= $endDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $year = $j->format("Y");

            $salesSummaryArr[$year]['volume'] = !empty($salesSummaryArr[$year]['volume']) ? $salesSummaryArr[$year]['volume'] : 0;
            $salesSummaryArr[$year]['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr[$year]['amount'] = !empty($salesSummaryArr[$year]['amount']) ? $salesSummaryArr[$year]['amount'] : 0;
            $salesSummaryArr[$year]['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr[$year]['net_income'] = !empty($salesSummaryArr[$year]['net_income']) ? $salesSummaryArr[$year]['net_income'] : 0;
            $salesSummaryArr[$year]['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $salesSummaryArr['total']['volume'] = $salesSummaryArr['total']['volume'] ?? 0;
            $salesSummaryArr['total']['volume'] += !empty($summaryArr[$day]['volume']) ? $summaryArr[$day]['volume'] : 0;

            $salesSummaryArr['total']['amount'] = $salesSummaryArr['total']['amount'] ?? 0;
            $salesSummaryArr['total']['amount'] += !empty($summaryArr[$day]['amount']) ? $summaryArr[$day]['amount'] : 0;

            $salesSummaryArr['total']['net_income'] = $salesSummaryArr['total']['net_income'] ?? 0;
            $salesSummaryArr['total']['net_income'] += !empty($summaryArr[$day]['net_income']) ? $summaryArr[$day]['net_income'] : 0;

            $yearArr[$year] = $j->format("Y");
        }

        if (!empty($salesSummaryArr)) {
            foreach ($salesSummaryArr as $year => $sales) {
                $prevYear = date("Y", strtotime("-1 year", strtotime($year)));
                $thisYearVolume = !empty($sales['volume']) ? $sales['volume'] : 0;
                $thisYearAmount = !empty($sales['amount']) ? $sales['amount'] : 0;
                $thisYearIncome = !empty($sales['net_income']) ? $sales['net_income'] : 0;
                $prevYearVolume = !empty($salesSummaryArr[$prevYear]['volume']) ? $salesSummaryArr[$prevYear]['volume'] : 0;
                $prevYearAmount = !empty($salesSummaryArr[$prevYear]['amount']) ? $salesSummaryArr[$prevYear]['amount'] : 0;
                $prevYearIncome = !empty($salesSummaryArr[$prevYear]['net_income']) ? $salesSummaryArr[$prevYear]['net_income'] : 0;

                $volumeDeviation = (($thisYearVolume - $prevYearVolume) * 100) / ($prevYearVolume > 0 ? $prevYearVolume : 1);
                $amountDeviation = (($thisYearAmount - $prevYearAmount) * 100) / ($prevYearAmount > 0 ? $prevYearAmount : 1);
                $incomeDeviation = (($thisYearIncome - $prevYearIncome) * 100) / ($prevYearIncome > 0 ? $prevYearIncome : 1);

                $salesSummaryArr[$year]['volume_deviation'] = Helper::numberFormatDigit2($volumeDeviation);
                $salesSummaryArr[$year]['amount_deviation'] = Helper::numberFormatDigit2($amountDeviation);
                $salesSummaryArr[$year]['income_deviation'] = Helper::numberFormatDigit2($incomeDeviation);
            }
        }

        $inquiryInfoArr = Lead::join('buyer', 'buyer.id', 'inquiry.buyer_id')
                        ->join('users', 'users.id', 'inquiry.salespersons_id')
                        ->select('inquiry.*', 'buyer.name as buyer'
                                , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                        ->where('inquiry.supplier_id', $id)->get();



        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                        ->join('product', 'product.id', 'inquiry_details.product_id')
                        ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                        ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                        ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                        ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                                , 'grade.name as grade_name', 'measure_unit.name as unit')
                        ->where('inquiry.supplier_id', $id)->get();

        $inquiryDetailsArr = $inquryRowSpanArr = $productRowSpanArr2 = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            $inquryRowSpanArr[$inquiryId] = !empty($inquryRowSpanArr[$inquiryId]) ? $inquryRowSpanArr[$inquiryId] : 0;
                            $inquryRowSpanArr[$inquiryId] += 1;

                            $productRowSpanArr2[$inquiryId][$productId] = !empty($productRowSpanArr2[$inquiryId][$productId]) ? $productRowSpanArr2[$inquiryId][$productId] : 0;
                            $productRowSpanArr2[$inquiryId][$productId] += 1;

                            $brandRowSpanArr[$inquiryId][$productId][$brandId] = !empty($brandRowSpanArr[$inquiryId][$productId][$brandId]) ? $brandRowSpanArr[$inquiryId][$productId][$brandId] : 0;
                            $brandRowSpanArr[$inquiryId][$productId][$brandId] += 1;
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                        ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                                , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                        ->where('inquiry.supplier_id', $id)->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }


//        echo '<pre>';
//        print_r($inquiryInfoArr->toArray());
//        exit;


        $userAccessArr = self::userAccess();
        if (empty($userAccessArr[$modueId][6])) {
            return redirect('/dashboard');
        }
        return view($loadView)->with(compact('target', 'qpArr', 'request', 'businessInitationDate'
                                , 'contactPersonArr', 'contactDesignationList', 'productInfoArr', 'productRowSpanArr'
                                , 'inquiryCountArr', 'cancelCauseList', 'mostFrequentCancelCauseArr'
                                , 'overAllSalesSummaryArr', 'lastOneYearSalesSummaryArr', 'invoicedAmount'
                                , 'salesSummaryArr', 'yearArr', 'received', 'supplierPaymentArr'
                                , 'netIncome', 'brandWiseVolumeRateArr', 'beneficiaryBankInfo'
                                , 'commissionOnSalesVolume', 'commissionOnShippedVolume', 'commissionPaidThroughInvoice', 'konitaInfo', 'phoneNumber'
                                , 'inquiryInfoArr', 'inquiryDetailsArr', 'inquryRowSpanArr'
                                , 'productRowSpanArr2', 'brandRowSpanArr', 'lsdArr', 'deliveryArr'));
    }

    public static function getSupplierInvolvedOrderList(Request $request, $loadView) {
        $typeList = [
            '1' => __('label.UPCOMING'),
            '2' => __('label.PIPE_LINE'),
            '3' => __('label.CONFIRMED'),
            '4' => __('label.ACCOMPLISHED'),
            '5' => __('label.CANCELLED'),
        ];
        $supplierInfo = Supplier::select('name')->where('id', $request->supplier_id)->first();

        $salesPersonInfo = User::join('designation', 'designation.id', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                                , 'designation.short_name as designation')
                        ->where('users.id', $request->sales_person_id)->first();

        $inquiryInfoArr = Lead::join('buyer', 'buyer.id', 'inquiry.buyer_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->select('inquiry.*', 'buyer.name as buyer'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $inquiryInfoArr = $inquiryInfoArr->get();

        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'measure_unit.name as unit')
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->get();

        $inquiryDetailsArr = $inquryRowSpanArr = $productRowSpanArr = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            $inquryRowSpanArr[$inquiryId] = !empty($inquryRowSpanArr[$inquiryId]) ? $inquryRowSpanArr[$inquiryId] : 0;
                            $inquryRowSpanArr[$inquiryId] += 1;

                            $productRowSpanArr[$inquiryId][$productId] = !empty($productRowSpanArr[$inquiryId][$productId]) ? $productRowSpanArr[$inquiryId][$productId] : 0;
                            $productRowSpanArr[$inquiryId][$productId] += 1;

                            $brandRowSpanArr[$inquiryId][$productId][$brandId] = !empty($brandRowSpanArr[$inquiryId][$productId][$brandId]) ? $brandRowSpanArr[$inquiryId][$productId][$brandId] : 0;
                            $brandRowSpanArr[$inquiryId][$productId][$brandId] += 1;
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                        , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $deliveryInfoArr = $deliveryInfoArr->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

//        echo '<pre>';
//        print_r($inquiryDetailsArr);
//        print_r($inquryRowSpanArr);
//        print_r($productRowSpanArr);
//        print_r($brandRowSpanArr);
//        exit;

        $view = view($loadView, compact('request', 'supplierInfo', 'salesPersonInfo'
                        , 'inquiryInfoArr', 'inquiryDetailsArr', 'inquryRowSpanArr'
                        , 'productRowSpanArr', 'brandRowSpanArr', 'lsdArr', 'deliveryArr'
                        , 'typeList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function printSupplierInvolvedOrderList(Request $request, $loadView, $modueId) {
        $typeList = [
            '1' => __('label.UPCOMING'),
            '2' => __('label.PIPE_LINE'),
            '3' => __('label.CONFIRMED'),
            '4' => __('label.ACCOMPLISHED'),
            '5' => __('label.CANCELLED'),
        ];
        $supplierInfo = Supplier::select('name')->where('id', $request->supplier_id)->first();

        $salesPersonInfo = User::join('designation', 'designation.id', 'users.designation_id')
                        ->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as name")
                                , 'designation.short_name as designation')
                        ->where('users.id', $request->sales_person_id)->first();

        $inquiryInfoArr = Lead::join('buyer', 'buyer.id', 'inquiry.buyer_id')
                ->join('users', 'users.id', 'inquiry.salespersons_id')
                ->select('inquiry.*', 'buyer.name as buyer'
                        , DB::raw("CONCAT(users.first_name,' ', users.last_name) as sales_person"))
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $inquiryInfoArr = $inquiryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $inquiryInfoArr = $inquiryInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $inquiryInfoArr = $inquiryInfoArr->get();

        $lsdArr = [];
        if (!$inquiryInfoArr->isEmpty()) {
            foreach ($inquiryInfoArr as $item) {
                if (!empty($item->lsd_info)) {
                    $lsdInfoArr = json_decode($item->lsd_info, true);
                    $lsdInfo = end($lsdInfoArr);
                    $lsdArr[$item->id] = $lsdInfo['lsd'];
                }
            }
        }

        $inquiryDetailsInfoArr = InquiryDetails::join('inquiry', 'inquiry.id', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', 'product.measure_unit_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', 'inquiry_details.grade_id')
                ->select('inquiry_details.*', 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'measure_unit.name as unit')
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $inquiryDetailsInfoArr = $inquiryDetailsInfoArr->get();

        $inquiryDetailsArr = $inquryRowSpanArr = $productRowSpanArr = $brandRowSpanArr = [];
        if (!$inquiryDetailsInfoArr->isEmpty()) {
            foreach ($inquiryDetailsInfoArr as $details) {
                $gradeId = !empty($details->grade_id) ? $details->grade_id : 0;

                $unit = !empty($details->unit) ? ' ' . $details->unit : '';
                $perUnit = !empty($details->unit) ? ' /' . $details->unit : '';

                $quantity = (!empty($details->quantity) ? Helper::numberFormat2Digit($details->quantity) : '0.00') . $unit;
                $unitPrice = '$' . (!empty($details->unit_price) ? Helper::numberFormat2Digit($details->unit_price) : '0.00') . $perUnit;
                $totalPrice = '$' . (!empty($details->total_price) ? Helper::numberFormat2Digit($details->total_price) : '0.00');

                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['product_name'] = $details->product_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['brand_name'] = $details->brand_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['grade_name'] = $details->grade_name;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['quantity'] = $quantity;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['unit_price'] = $unitPrice;
                $inquiryDetailsArr[$details->inquiry_id]['product'][$details->product_id]['brand'][$details->brand_id]['grade'][$gradeId]['total_price'] = $totalPrice;
            }
        }

        if (!empty($inquiryDetailsArr)) {
            foreach ($inquiryDetailsArr as $inquiryId => $inquiry) {
                foreach ($inquiry['product'] as $productId => $product) {
                    foreach ($product['brand'] as $brandId => $brand) {
                        foreach ($brand['grade'] as $gradeId => $grade) {
                            $inquryRowSpanArr[$inquiryId] = !empty($inquryRowSpanArr[$inquiryId]) ? $inquryRowSpanArr[$inquiryId] : 0;
                            $inquryRowSpanArr[$inquiryId] += 1;

                            $productRowSpanArr[$inquiryId][$productId] = !empty($productRowSpanArr[$inquiryId][$productId]) ? $productRowSpanArr[$inquiryId][$productId] : 0;
                            $productRowSpanArr[$inquiryId][$productId] += 1;

                            $brandRowSpanArr[$inquiryId][$productId][$brandId] = !empty($brandRowSpanArr[$inquiryId][$productId][$brandId]) ? $brandRowSpanArr[$inquiryId][$productId][$brandId] : 0;
                            $brandRowSpanArr[$inquiryId][$productId][$brandId] += 1;
                        }
                    }
                }
            }
        }

        $deliveryInfoArr = Delivery::join('inquiry', 'inquiry.id', 'delivery.inquiry_id')
                ->select('delivery.inquiry_id', 'delivery.bl_no', 'delivery.id', 'delivery.payment_status'
                        , 'delivery.buyer_payment_status', 'delivery.shipment_status')
                ->where('inquiry.supplier_id', $request->supplier_id);

        if (!empty($request->sales_person_id) && $request->sales_person_id != 0) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.salespersons_id', $request->sales_person_id)
                            ->where('inquiry.status', '<>', '3')->where('inquiry.order_status', '<>', '6');
        }
        if ($request->type_id == 1) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '1');
        } elseif ($request->type_id == 2) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.order_status', '1');
        } elseif ($request->type_id == 3) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['2', '3']);
        } elseif ($request->type_id == 4) {
            $deliveryInfoArr = $deliveryInfoArr->whereIn('inquiry.order_status', ['4', '5']);
        } elseif ($request->type_id == 5) {
            $deliveryInfoArr = $deliveryInfoArr->where('inquiry.status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '1')
                    ->where('inquiry.order_status', '<>', '2')
                    ->where('inquiry.order_status', '<>', '3')
                    ->where('inquiry.order_status', '<>', '4')
                    ->where('inquiry.order_status', '<>', '5');
        }

        $deliveryInfoArr = $deliveryInfoArr->get();

        $deliveryArr = [];
        if (!$deliveryInfoArr->isEmpty()) {
            foreach ($deliveryInfoArr as $item) {
                $deliveryArr[$item->inquiry_id][$item->id]['inquiry_id'] = $item->inquiry_id;
                $deliveryArr[$item->inquiry_id][$item->id]['bl_no'] = $item->bl_no ?? __('label.N_A');
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $item->payment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['shipment_status'] = $item->shipment_status;
                $deliveryArr[$item->inquiry_id][$item->id]['buyer_payment_status'] = $item->buyer_payment_status;

                $btnColor = 'purple';
                $paymentStatus = 'Unpaid';
                if ($item->buyer_payment_status == '1') {
                    $btnColor = 'green-seagreen';
                    $paymentStatus = 'Paid';
                }

                $status = 'Draft';
                $icon = 'file-text';
                $btnRounded = '';
                if ($item->shipment_status == '2') {
                    $status = 'Shipped';
                    $icon = 'ship';
                    $btnRounded = 'btn-rounded';
                }

                $deliveryArr[$item->inquiry_id][$item->id]['btn_color'] = $btnColor;
                $deliveryArr[$item->inquiry_id][$item->id]['payment_status'] = $paymentStatus;
                $deliveryArr[$item->inquiry_id][$item->id]['status'] = $status;
                $deliveryArr[$item->inquiry_id][$item->id]['icon'] = $icon;
                $deliveryArr[$item->inquiry_id][$item->id]['btn_rounded'] = $btnRounded;
            }
        }

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

//        echo '<pre>';
//        print_r($inquiryDetailsArr);
//        print_r($inquryRowSpanArr);
//        print_r($productRowSpanArr);
//        print_r($brandRowSpanArr);
//        exit;

        $userAccessArr = self::userAccess();
        if (empty($userAccessArr[$modueId][6])) {
            return redirect('/dashboard');
        }
        return view($loadView)->with(compact('request', 'supplierInfo', 'salesPersonInfo'
                                , 'inquiryInfoArr', 'inquiryDetailsArr', 'inquryRowSpanArr'
                                , 'productRowSpanArr', 'brandRowSpanArr', 'lsdArr', 'deliveryArr'
                                , 'konitaInfo', 'phoneNumber', 'typeList'));
    }

    //************* end :: supplier profile *****************//
    //************************* Start :: Activity Log ***********************//
    public static function getOpportunityActivityLogModal(Request $request, $loadView) {
        $opportunityInfo = CrmOpportunity::find($request->opportunity_id);

        $contactPersonArr = [];
        if (!empty($opportunityInfo->buyer_contact_person)) {
            $buyerContactPersonArr = json_decode($opportunityInfo->buyer_contact_person, true);
            if (!empty($buyerContactPersonArr)) {
                foreach ($buyerContactPersonArr as $key => $contactPersonData) {
                    $contactPersonArr[$key] = $contactPersonData['name'];
                }
            }
        }
        $contactPersonArr = ['0' => __('label.SELECT_CONTACT_PERSON_OPT')] + $contactPersonArr;

        $activityTypeArr = ['0' => __('label.SELECT_ACTIVITY_TYPE_OPT')] + CrmActivityType::where('status', '1')->pluck('name', 'id')->toArray();
        $activityStatusArr = ['0' => __('label.SELECT_ACTIVITY_STATUS_OPT')] + CrmActivityStatus::where('status', '1')->pluck('name', 'id')->toArray();
        $priorityArr = ['0' => __('label.SELECT_ACTIVITY_PRIORITY_OPT')] + CrmActivityPriority::pluck('name', 'id')->toArray();


        // START::Preview of Activity Log History
        $statusIconArr = CrmActivityStatus::pluck('icon', 'id')->toArray();
        $statusColorArr = CrmActivityStatus::pluck('color', 'id')->toArray();

        //get Activity Log History
        $activityLogPrevHistory = CrmActivityLog::where('opportunity_id', $request->opportunity_id)->first();

        $finalArr = $logHistoryArr = [];
        if (!empty($activityLogPrevHistory)) {
            $logHistoryArr = json_decode($activityLogPrevHistory->log, true);
            krsort($logHistoryArr);
            $i = 0;
            if (!empty($logHistoryArr)) {
                foreach ($logHistoryArr as $activityLog) {
                    $logDate = Helper::dateFormatConvert($activityLog['date']);

                    $finalArr[$logDate][$i]['date'] = $activityLog['date'];
                    $finalArr[$logDate][$i]['activity_type'] = (!empty($activityLog['activity_type_id']) && isset($activityTypeArr[$activityLog['activity_type_id']])) ? $activityTypeArr[$activityLog['activity_type_id']] : '';
                    $finalArr[$logDate][$i]['status'] = (!empty($activityLog['status']) && isset($activityStatusArr[$activityLog['status']])) ? $activityStatusArr[$activityLog['status']] : '';
                    $finalArr[$logDate][$i]['priority'] = (!empty($activityLog['priority']) && isset($priorityArr[$activityLog['priority']])) ? $priorityArr[$activityLog['priority']] : __('label.N_A');
                    $finalArr[$logDate][$i]['contact_person'] = (!empty($activityLog['contact_person']) && isset($contactPersonArr[$activityLog['contact_person']])) ? $contactPersonArr[$activityLog['contact_person']] : __('label.N_A');
                    $finalArr[$logDate][$i]['remarks'] = $activityLog['remarks'];
                    $finalArr[$logDate][$i]['has_schedule'] = $activityLog['has_schedule'];
                    $finalArr[$logDate][$i]['schedule_date_time'] = Helper::formatDateTime($activityLog['schedule_date_time']);
                    $finalArr[$logDate][$i]['schedule_purpose'] = $activityLog['schedule_purpose'];
                    $finalArr[$logDate][$i]['updated_by'] = $activityLog['updated_by'] ?? 0;
                    $finalArr[$logDate][$i]['updated_at'] = $activityLog['updated_at'] ?? '';
                    $finalArr[$logDate][$i]['ribbon'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'ribbon-color-' . $statusColorArr[$activityLog['status']] : '';
                    $finalArr[$logDate][$i]['label'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'label-' . $statusColorArr[$activityLog['status']] : '';
                    $finalArr[$logDate][$i]['font'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'font-' . $statusColorArr[$activityLog['status']] : '';
                    $finalArr[$logDate][$i]['background'] = (!empty($activityLog['status']) && isset($statusColorArr[$activityLog['status']])) ? 'bg-' . $statusColorArr[$activityLog['status']] . ' bg-font-' . $statusColorArr[$activityLog['status']] : '';
                    $finalArr[$logDate][$i]['icon'] = (!empty($activityLog['status']) && isset($statusIconArr[$activityLog['status']])) ? $statusIconArr[$activityLog['status']] : '';
                    $i++;
                }
            }
        }
        krsort($finalArr);

        // Assigned person list
        $assignedPersonList = CrmOpportunityToMember::join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                        ->select('crm_opportunity_to_member.opportunity_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as assigned_person"))
                        ->pluck('assigned_person', 'crm_opportunity_to_member.opportunity_id')->toArray();


        $userInfoArr = User::select(DB::raw("CONCAT(first_name,' ', last_name) as full_name")
                        , 'employee_id', 'id', 'photo')->get();
        $userArr = [];
        if (!$userInfoArr->isEmpty()) {
            foreach ($userInfoArr as $user) {
                $userArr[$user->id]['full_name'] = $user->full_name;
                $userArr[$user->id]['employee_id'] = $user->employee_id;
                $userArr[$user->id]['photo'] = $user->photo;
            }
        }
        // END::Preview of Activity Log History
        //for opportunity details
        $target = CrmOpportunity::join('users', 'users.id', 'crm_opportunity.created_by')
                        ->leftJoin('crm_source', 'crm_source.id', 'crm_opportunity.source_id')
                        ->select('crm_opportunity.*', 'crm_source.name as source', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as opportunity_creator"))
                        ->where('crm_opportunity.id', $request->opportunity_id)->first();

        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $productArr = !empty($target->product_data) ? json_decode($target->product_data, true) : [];
        $productTempArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $pKey => $pInfo) {
                if (!empty($pInfo['product']) && !empty($pInfo['brand']) && !empty($pInfo['quantity'])) {
                    $productTempArr[$pKey] = $pInfo;
                }
            }
        }
        $productArr = $productTempArr;
        $buyerList = Buyer::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productList = Product::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $brandList = Brand::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $gradeList = Grade::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryList = Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $view = view($loadView, compact('request', 'opportunityInfo', 'activityTypeArr', 'activityStatusArr'
                        , 'priorityArr', 'contactPersonArr', 'statusColorArr', 'statusIconArr', 'finalArr', 'userArr'
                        , 'target', 'contactArr', 'productArr', 'buyerList', 'productList', 'brandList', 'gradeList'
                        , 'countryList', 'assignedPersonList'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getActivityContactPersonData(Request $request, $loadView) {
        $target = CrmOpportunity::find($request->opportunity_id);
        $contactArr = !empty($target->buyer_contact_person) ? json_decode($target->buyer_contact_person, true) : [];
        $view = view($loadView, compact('contactArr', 'target'))->render();
        return response()->json(['html' => $view]);
    }

    public static function saveActivityContactPersonData(Request $request, $loadView) {
        $target = CrmOpportunity::find($request->opportunity_id);
        $contactArr = [];
        if (!empty($request->contact)) {
            foreach ($request->contact as $cKey => $cInfo) {
                if (count(array_filter($cInfo)) != 0) {
                    $contactArr[$cKey]['name'] = $cInfo['name'] ?? '';
                    $contactArr[$cKey]['designation'] = $cInfo['designation'] ?? '';
                    $contactArr[$cKey]['email'] = $cInfo['email'] ?? '';
                    $contactArr[$cKey]['phone'] = $cInfo['phone'] ?? '';
                    $contactArr[$cKey]['primary'] = !empty($cInfo['primary']) ? $cInfo['primary'] : '0';
                }
            }
        }

        $target->buyer_contact_person = json_encode($contactArr);

        if ($target->save()) {
            $opportunityInfo = CrmOpportunity::find($request->opportunity_id);
            $contactPersonList = [];
            if (!empty($opportunityInfo->buyer_contact_person)) {
                $buyerContactPersonArr = json_decode($opportunityInfo->buyer_contact_person, true);
                if (!empty($buyerContactPersonArr)) {
                    foreach ($buyerContactPersonArr as $key => $contactPersonData) {
                        $contactPersonList[$key] = $contactPersonData['name'];
                    }
                }
            }

            $contactPersonArr = $request->contact_type == 1 ? [__('label.N_A') => __('label.SELECT_ATTENTION_OPT')] : ['0' => __('label.SELECT_CONTACT_PERSON_OPT')];
            $contactPersonArr = $contactPersonArr + $contactPersonList;

            $contactView = view($loadView, compact('contactPersonArr'))->render();

            return Response::json(array('heading' => 'Success', 'message' => __('label.CONTACT_ADDED_SUCCESSFULLY'), 'contactView' => $contactView), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.CONTACT_COULD_NOT_BE_ADDED')), 401);
        }
    }

    public static function saveActivityModal(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        //validation
        $rules = $message = $errors = [];
        $rules = [
            'activity_type' => 'required|not_in:0',
            'activity_status' => 'required|not_in:0',
            'remarks' => 'required',
        ];

        if (!empty($request->has_schedule)) {
            $rules = [
                'schedule_date_time' => 'required',
                'schedule_purpose' => 'required',
            ];
        }

        if (empty($request->final_product) && $request->activity_status == 7) {
            $errors[] = __('label.PLEASE_SELECT_AT_LEAST_ONE_PRODUCT');
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $oppDataInfo = CrmOpportunity::select('product_data', 'buyer_has_id')->where('id', $request->opportunity_id)->first();
        $productArray = [];
        if (!empty($oppDataInfo) && $oppDataInfo->buyer_has_id == '0' && $request->activity_status == 7) {
            $errorMsg = __('label.BUYER_INPUT_IS_INVALID');
            return Response::json(array('success' => false, 'message' => $errorMsg), 401);
        }
        if (!empty($errors)) {
            return Response::json(array('success' => false, 'message' => $errors), 401);
        }
        //end of validation
        //create Activity Log
        $activityLogData = [];
        $uniqId = uniqid();

        //create new Activity Log array
        $activityLogData[$uniqId]['activity_type_id'] = $request->activity_type;
        $activityLogData[$uniqId]['status'] = $request->activity_status;
        $activityLogData[$uniqId]['priority'] = $request->activity_priority;
        $activityLogData[$uniqId]['date'] = $request->date;
        $activityLogData[$uniqId]['remarks'] = $request->remarks;
        $activityLogData[$uniqId]['contact_person'] = $request->contact_person_key;
        $activityLogData[$uniqId]['updated_by'] = Auth::user()->id;
        $activityLogData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');
        $activityLogData[$uniqId]['has_schedule'] = (!empty($request->has_schedule)) ? $request->has_schedule : '0';
        $activityLogData[$uniqId]['schedule_date_time'] = $request->schedule_date_time;
        $activityLogData[$uniqId]['schedule_purpose'] = $request->schedule_purpose;


        //merge with previous log and pack in json
        $activityLogHistory = CrmActivityLog::where('opportunity_id', $request->opportunity_id)->first();

        if (!empty($activityLogHistory)) {
            $preActivityLogArr = json_decode($activityLogHistory->log, true);
            $activityLogArr = array_merge($preActivityLogArr, $activityLogData);
        } else {
            $activityLogHistory = new CrmActivityLog;
            $activityLogArr = $activityLogData;
        }

        // Get product data from crm opportunity

        if (!empty($oppDataInfo->product_data)) {
            $productArrList = json_decode($oppDataInfo->product_data, TRUE);
            if (!empty($productArrList)) {
                foreach ($productArrList as $pKey => $pInfo) {
                    $productArray[$pKey]['product'] = !empty($pInfo['product']) ? $pInfo['product'] : '';
                    $productArray[$pKey]['product_has_id'] = !empty($pInfo['product_has_id']) ? $pInfo['product_has_id'] : '';
                    $productArray[$pKey]['brand'] = !empty($pInfo['brand']) ? $pInfo['brand'] : '';
                    $productArray[$pKey]['brand_has_id'] = !empty($pInfo['brand_has_id']) ? $pInfo['brand_has_id'] : '';
                    $productArray[$pKey]['grade'] = !empty($pInfo['grade']) ? $pInfo['grade'] : '';
                    $productArray[$pKey]['grade_has_id'] = !empty($pInfo['grade_has_id']) ? $pInfo['grade_has_id'] : '';
                    $productArray[$pKey]['origin'] = !empty($pInfo['origin']) ? $pInfo['origin'] : '';
                    $productArray[$pKey]['gsm'] = !empty($pInfo['gsm']) ? $pInfo['gsm'] : '';
                    $productArray[$pKey]['quantity'] = !empty($pInfo['quantity']) ? $pInfo['quantity'] : '';
                    $productArray[$pKey]['unit'] = !empty($pInfo['unit']) ? $pInfo['unit'] : '';
                    $productArray[$pKey]['unit_price'] = !empty($pInfo['unit_price']) ? $pInfo['unit_price'] : '';
                    $productArray[$pKey]['total_price'] = !empty($pInfo['total_price']) ? $pInfo['total_price'] : '';
                    if (!empty($request->final_product) && (array_key_exists($pKey, $request->final_product))) {
                        $productArray[$pKey]['final'] = '1';
                    }
                }
            }
        }
        $productInfo = json_encode($productArray);

        $activityLogHistory->opportunity_id = $request->opportunity_id;
        $activityLogHistory->log = json_encode($activityLogArr);
        $activityLogHistory->updated_at = date('Y-m-d H:i:s');
        $activityLogHistory->updated_by = Auth::user()->id;

        $status = '1';
        if ($request->activity_status == 7) {
            $status = '2';
        }
        if ($activityLogHistory->save()) {
            CrmOpportunity::where('id', $request->opportunity_id)->update(['status' => $status, 'last_activity_status' => $request->activity_status, 'product_data' => $productInfo]);
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.ACTIVITY_LOG_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.ACTIVITY_LOG_COULD_NOT_BE_CREATED')], 401);
        }
    }

    //************************* End :: Activity Log *************************//
    //************************ Start :: Messaging **************************//
    public static function getOrderMessaging(Request $request, $loadView) {
        $user = User::where('id', Auth::user()->id)->select('id', 'photo', 'group_id', 'first_name')->first();


        $messageBodyArr = Self::getMessageBody($request->buyer_id, $request->inquiry_id);
        $finalArr = !empty($messageBodyArr['final_arr']) ? $messageBodyArr['final_arr'] : [];
        $userArr = !empty($messageBodyArr['user_arr']) ? $messageBodyArr['user_arr'] : [];

        if (Auth::user()->group_id == 0) {
            OrderMessaging::where('inquiry_id', $request->inquiry_id)
                    ->where('buyer_id', $request->buyer_id)->where('buyer_read', '0')
                    ->update(['buyer_read' => '1']);
        } else {
            UserWiseBuyerMessage::where('buyer_id', $request->buyer_id)->where('inquiry_id', $request->inquiry_id)
                    ->where('user_id', Auth::user()->id)->where('status', '0')
                    ->update(['status' => '1']);
        }

        $view = view($loadView, compact('request', 'user', 'userArr', 'finalArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getMessageBody($buyerId, $inquiryId) {
        //get messaging history
        $messagingPrevHistory = OrderMessaging::where('inquiry_id', $inquiryId)
                        ->where('buyer_id', $buyerId)->first();

        $finalArr = $messagingHistoryArr = [];
        if (!empty($messagingPrevHistory)) {
            $messagingHistoryArr = json_decode($messagingPrevHistory->history, true);
            krsort($messagingHistoryArr);
            $i = 0;
            if (!empty($messagingHistoryArr)) {
                foreach ($messagingHistoryArr as $messagingHistory) {
                    $date = Helper::dateFormatConvert($messagingHistory['updated_at']);
                    $finalArr[$date][$i]['user_group_id'] = $messagingHistory['user_group_id'];
                    $finalArr[$date][$i]['message'] = $messagingHistory['message'];
                    $finalArr[$date][$i]['updated_by'] = $messagingHistory['updated_by'] ?? 0;
                    $finalArr[$date][$i]['updated_at'] = $messagingHistory['updated_at'] ?? '';
                    $i++;
                }
            }
        }
        krsort($finalArr);

        $userInfoArr = User::leftJoin('designation', 'designation.id', 'users.designation_id')
                ->select('users.first_name', 'users.last_name', 'users.id', 'users.photo'
                        , 'designation.title as designation')
                ->get();
        $userArr = [];
        if (!$userInfoArr->isEmpty()) {
            foreach ($userInfoArr as $user) {
                $fName = !empty($user->first_name) ? $user->first_name : '';
                $lName = !empty($user->last_name) ? $user->last_name : '';
                $fullName = $fName . ' ' . $lName;
                $userArr[$user->id]['full_name'] = $fullName;
                $userArr[$user->id]['photo'] = $user->photo;
                $userArr[$user->id]['designation'] = $user->designation;
            }
        }

        $messageBodyArr = [
            'final_arr' => $finalArr,
            'user_arr' => $userArr,
        ];

        return $messageBodyArr;
    }

    public static function setMessage(Request $request, $loadView) {
        //validation
        $rules = $message = [];
        $rules = [
            'buyer_id' => 'required|not_in:0',
            'message' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //end of validation
        //create follow up history
        $historyData = [];
        $uniqId = uniqid();

        //create new follow up array
        $historyData[$uniqId]['user_group_id'] = $request->user_group_id;
        $historyData[$uniqId]['message'] = $request->message;
        $historyData[$uniqId]['updated_by'] = Auth::user()->id;
        $historyData[$uniqId]['updated_at'] = date('Y-m-d H:i:s');


        //merge with previous history and pack in json
        $messagingHistory = OrderMessaging::where('inquiry_id', $request->inquiry_id)->where('buyer_id', $request->buyer_id)->first();

        if (!empty($messagingHistory)) {
            $preHistoryArr = json_decode($messagingHistory->history, true);
            $historyArr = array_merge($preHistoryArr, $historyData);
        } else {
            $messagingHistory = new OrderMessaging;
            $historyArr = $historyData;
        }


        $messagingHistory->inquiry_id = $request->inquiry_id;
        $messagingHistory->buyer_id = $request->buyer_id;
        $messagingHistory->history = json_encode($historyArr);
        $messagingHistory->buyer_read = !empty($request->user_group_id) ? '0' : '1';
        $messagingHistory->updated_at = date('Y-m-d H:i:s');
        $messagingHistory->updated_by = Auth::user()->id;


        DB::beginTransaction();
        try {
            $messageBody = '';
            if ($messagingHistory->save()) {
                $messageBodyArr = Self::getMessageBody($request->buyer_id, $request->inquiry_id);
                $finalArr = !empty($messageBodyArr['final_arr']) ? $messageBodyArr['final_arr'] : [];
                $userArr = !empty($messageBodyArr['user_arr']) ? $messageBodyArr['user_arr'] : [];

                $messageBody = view($loadView, compact('request', 'userArr', 'finalArr'))->render();

                $allowedUsers = Self::getMessagingAllowedUsers();

                $messageForUserArr = [];
                $mI = 0;
                if (!empty($allowedUsers)) {
                    foreach ($allowedUsers as $userId => $userId) {
                        $status = (Auth::user()->group_id != 0 && Auth::user()->id == $userId) ? '1' : '0';
                        $messageForUserArr[$mI]['user_id'] = $userId;
                        $messageForUserArr[$mI]['buyer_id'] = $request->buyer_id;
                        $messageForUserArr[$mI]['inquiry_id'] = $request->inquiry_id;
                        $messageForUserArr[$mI]['status'] = $status;
                        $messageForUserArr[$mI]['read_by'] = 0;
                        $messageForUserArr[$mI]['updated_at'] = date('Y-m-d H:i:s');
                        $messageForUserArr[$mI]['updated_by'] = Auth::user()->id;
                        $mI++;
                    }
                }

                UserWiseBuyerMessage::where('buyer_id', $request->buyer_id)->where('inquiry_id', $request->inquiry_id)
                        ->delete();
                UserWiseBuyerMessage::insert($messageForUserArr);
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'messageBody' => $messageBody], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.MESSAGE_COULD_NOT_BE_SENT')], 401);
        }
    }

    //************************ End :: Messaging **************************//
    //************************* Start :: Activity Log ***********************//
    public static function getDetails(Request $request, $quotationId = null, $buyerId = null) {

        //konita info
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        //end :: konita info

        $quotationInfoArr = QuotationRequest::join('buyer', 'buyer.id', '=', 'quotation_request.buyer_id')
                        ->join('user_wise_quotation_req', 'user_wise_quotation_req.quotation_id', '=', 'quotation_request.id')
                        ->select('quotation_request.*', 'buyer.name as buyer_name', 'buyer.id as buyer_id', 'user_wise_quotation_req.status as read_status', 'user_wise_quotation_req.quotation_id')
                        ->where('quotation_request.id', $quotationId)->where('user_wise_quotation_req.buyer_id', $buyerId);

        if (Auth::user()->group_id != 0) {
            $quotationInfoArr = $quotationInfoArr->where('user_wise_quotation_req.user_id', Auth::user()->id);
        }


        $quotationInfoArr = $quotationInfoArr->first();


        if ($request->view == 'print') {
            $buyerId = $quotationInfoArr->buyer_id;
        }
        $buyerInfo = Buyer::find($buyerId);
        $productArr = !empty($quotationInfoArr->product_data) ? json_decode($quotationInfoArr->product_data, true) : [];

        $productTempArr = [];
        if (!empty($productArr)) {
            foreach ($productArr as $pKey => $pInfo) {
                if (!empty($pInfo['product_id']) && !empty($pInfo['unit']) && !empty($pInfo['quantity'])) {
                    $productTempArr[$pKey] = $pInfo;
                }
            }
        }
        $productArr = $productTempArr;

        $productListArr = Product::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $userAccessArr = self::userAccess();

        if ($request->view == 'print') {

            if (empty($userAccessArr[88][6]) && Auth::user()->group_id != '0') {
                return redirect('dashboard');
            }
            return view('quotationRequestDetails.print.index')->with(compact('request', 'konitaInfo', 'phoneNumber', 'productArr', 'productListArr', 'quotationInfoArr', 'buyerInfo', 'productArr'));
        } else {
            $view = view('quotationRequestDetails.index', compact('konitaInfo', 'phoneNumber', 'productArr', 'productListArr', 'quotationInfoArr', 'buyerInfo', 'productArr'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public static function getMessagingAllowedUsers() {
        $allowedUsers = User::where('allowed_for_messaging', '1')->pluck('id', 'id')->toArray();
        return $allowedUsers;
    }

    public static function getViewQuotationAllowedUsers() {
        $allowedUsers = User::where('allowed_to_view_quotation', '1')->pluck('id', 'id')->toArray();
        return $allowedUsers;
    }

}
