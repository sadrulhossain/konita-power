<?php

namespace App\Http\Controllers;

use Validator;
use App\Lead; //model class
use App\SupplierToProduct; //model class
use App\Supplier; //model class
use App\Delivery; //model class
use App\KonitaBankAccount; //model class
use App\MeasureUnit; //model class
use App\SignatoryInfo; //model class
use App\Invoice; //model class
use App\CommissionSetup; //model class
use App\InquiryDetails;
use App\DeliveryDetails;
use App\InvoiceCommissionHistory;
use App\CompanyInformation;
use App\Receive;
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use DB;
use PDF;
use Illuminate\Http\Request;
use Helper;
use URL;

//LEAD/INQUIRY Controller
class BillingController extends Controller {

    //billingCreate
    public function billingCreate(Request $request) {

        $deliveryArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->where('inquiry.supplier_id', $request->supplier_id)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '1')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id'
                        , 'inquiry.buyer_id', 'inquiry.order_no', 'buyer.name as buyer_name')
                ->get();


        $deliveryDetailsInfo = Delivery::join('delivery_details', 'delivery_details.delivery_id', '=', 'delivery.id')
                ->join('inquiry_details', 'inquiry_details.id', '=', 'delivery_details.inquiry_details_id')
                ->join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->where('inquiry.supplier_id', $request->supplier_id)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '1')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('delivery_details.id as delivery_details_id', 'inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id', 'inquiry.buyer_id'
                        , 'inquiry.order_no', 'buyer.name as buyer_name'
                        , 'delivery_details.inquiry_details_id', 'delivery_details.shipment_quantity'
                        , 'inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'inquiry_details.unit_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'inquiry_details.quantity as total_quantity'
                        , 'measure_unit.id as measure_unit_id'
                        , 'commission_setup.konita_cmsn', 'commission_setup.principle_cmsn'
                        , 'commission_setup.sales_person_cmsn', 'commission_setup.buyer_cmsn'
                        , 'commission_setup.rebate_cmsn')
                ->get();


        $deliveryDetailsArr = $rowCountArr = $prevComsnArr = $shipmentComsnArr = $inquiryId = $shipmentQtyArr = [];
        if (!$deliveryDetailsInfo->isEmpty()) {
            foreach ($deliveryDetailsInfo as $item) {

                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['delivery_details_id'] = $item->delivery_details_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['product_name'] = $item->product_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_name'] = $item->unit_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['measure_unit_id'] = $item->measure_unit_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['brand_name'] = $item->brand_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['grade_name'] = $item->grade_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_quantity'] = $item->total_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['shipment_qty'] = $item->shipment_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_price'] = $item->unit_price;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['gsm'] = $item->gsm;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['inquiry_details_id'] = $item->inquiry_details_id;
                $totalPrice = (!empty($item->shipment_quantity) ? $item->shipment_quantity : 0) * (!empty($item->unit_price) ? $item->unit_price : 0);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_price'] = $totalPrice;

                $inquiryId[$item->inquiry_id] = $item->inquiry_id;
                $shipmentQtyArr[$item->inquiry_id][$item->inquiry_details_id][$item->delivery_details_id] = $item->shipment_quantity;

                $konitaCmsn = !empty($item->konita_cmsn) ? $item->konita_cmsn : 0;
                $principleCmsn = !empty($item->principle_cmsn) ? $item->principle_cmsn : 0;
                $salesPersonCmsn = !empty($item->sales_person_cmsn) ? $item->sales_person_cmsn : 0;
                $buyerCmsn = !empty($item->buyer_cmsn) ? $item->buyer_cmsn : 0;
                $rebateCmsn = !empty($item->rebate_cmsn) ? $item->rebate_cmsn : 0;

                $konitaCommission = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['konita_cmsn'] = $konitaCommission;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['principle_cmsn'] = $principleCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_konita_cmsn'] = ($konitaCommission * $item->shipment_quantity);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_principle_cmsn'] = ($principleCmsn * $item->shipment_quantity);

                //commission 
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['company_konita_cmsn'] = $konitaCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_company_konita_cmsn'] = ($item->shipment_quantity * $konitaCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['sales_person_cmsn'] = $salesPersonCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_sales_person_cmsn'] = ($item->shipment_quantity * $salesPersonCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['buyer_cmsn'] = $buyerCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_buyer_cmsn'] = ($item->shipment_quantity * $buyerCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['rebate_cmsn'] = $rebateCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_rebate_cmsn'] = ($item->shipment_quantity * $rebateCmsn);

                $rowCountArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id] = $item->delivery_details_id;
            }
        }

        $commissionInfo = CommissionSetup::whereIn('inquiry_id', $inquiryId)
                ->select('konita_cmsn', 'principle_cmsn', 'sales_person_cmsn', 'buyer_cmsn'
                        , 'rebate_cmsn', 'inquiry_details_id', 'inquiry_id')
                ->get();
        if (!$commissionInfo->isEmpty()) {
            foreach ($commissionInfo as $cmsn) {
                $konitaCmsn = !empty($cmsn->konita_cmsn) ? $cmsn->konita_cmsn : 0;
                $principleCmsn = !empty($cmsn->principle_cmsn) ? $cmsn->principle_cmsn : 0;
                $salesPersonCmsn = !empty($cmsn->sales_person_cmsn) ? $cmsn->sales_person_cmsn : 0;
                $buyerCmsn = !empty($cmsn->buyer_cmsn) ? $cmsn->buyer_cmsn : 0;
                $rebateCmsn = !empty($cmsn->rebate_cmsn) ? $cmsn->rebate_cmsn : 0;

                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['konita_cmsn'] = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['company_konita_cmsn'] = $konitaCmsn;
                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['principal_cmsn'] = $principleCmsn;
                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['sales_person_cmsn'] = $salesPersonCmsn;
                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['buyer_cmsn'] = $buyerCmsn;
                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['rebate_cmsn'] = $rebateCmsn;
            }
        }


        if (!empty($shipmentQtyArr)) {
            foreach ($shipmentQtyArr as $inquiryId => $inqDetailsInfo) {
                foreach ($inqDetailsInfo as $inqDetId => $delvDetailsInfo) {
                    foreach ($delvDetailsInfo as $delvDetId => $shipQty) {
                        $inquiryDetailsId = !empty($prevComsnArr[$inquiryId]) && array_key_exists($inqDetId, $prevComsnArr[$inquiryId]) ? $inqDetId : 0;

                        $konitaComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : 0;
                        $principalComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['principal_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['principal_cmsn'] : 0;
                        $companyKonitaCmsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['company_konita_cmsn'] : 0;
                        $salesPersonCmsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn'] : 0;
                        $buyerCmsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn'] : 0;
                        $rebateCmsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn'] : 0;


                        $shipmentComsnArr[$delvDetId]['total_konita_cmsn'] = $konitaComsn * $shipQty;
                        $shipmentComsnArr[$delvDetId]['total_principal_cmsn'] = $principalComsn * $shipQty;

                        //commission 
                        $shipmentComsnArr[$delvDetId]['company_konita_cmsn'] = $companyKonitaCmsn;
                        $shipmentComsnArr[$delvDetId]['total_company_konita_cmsn'] = ($shipQty * $companyKonitaCmsn);
                        $shipmentComsnArr[$delvDetId]['sales_person_cmsn'] = $salesPersonCmsn;
                        $shipmentComsnArr[$delvDetId]['total_sales_person_cmsn'] = ($shipQty * $salesPersonCmsn);
                        $shipmentComsnArr[$delvDetId]['buyer_cmsn'] = $buyerCmsn;
                        $shipmentComsnArr[$delvDetId]['total_buyer_cmsn'] = ($shipQty * $buyerCmsn);
                        $shipmentComsnArr[$delvDetId]['rebate_cmsn'] = $rebateCmsn;
                        $shipmentComsnArr[$delvDetId]['total_rebate_cmsn'] = ($shipQty * $rebateCmsn);
                    }
                }
            }
        }

        //ROWSPAN ARR
        $rowspanArr = $rowspanOrder = [];
        if (!empty($rowCountArr)) {
            foreach ($rowCountArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $deliveryId => $deliveryData) {
                    $rowspanArr[$inquiryId][$deliveryId] = count($deliveryData);
                    $rowspanOrder[$inquiryId] = array_sum($rowspanArr[$inquiryId]);
                }
            }
        }


        //TOTAL SHIPMENT QTY & KONITA & PRINCIPLE CMSN UNDER BL NO WISE
        $sipmentQtyArr = $totalKonitaCmsnArr = $totalPrincipleCmsnArr = [];
        if (!empty($deliveryDetailsArr)) {
            foreach ($deliveryDetailsArr as $deliveryId => $deliveryData) {
                foreach ($deliveryData as $devlDetId => $deliveryDetails) {
                    $sipmentQtyArr[$deliveryId] = !empty($sipmentQtyArr[$deliveryId]) ? $sipmentQtyArr[$deliveryId] : 0;
                    $sipmentQtyArr[$deliveryId] += $deliveryDetails['shipment_qty'];

                    $totalKonitaComsn = !empty($shipmentComsnArr[$devlDetId]['total_konita_cmsn']) ? $shipmentComsnArr[$devlDetId]['total_konita_cmsn'] : 0;
                    $totalPrincipalComsn = !empty($shipmentComsnArr[$devlDetId]['total_principal_cmsn']) ? $shipmentComsnArr[$devlDetId]['total_principal_cmsn'] : 0;

                    $totalKonitaCmsnArr[$deliveryId] = !empty($totalKonitaCmsnArr[$deliveryId]) ? $totalKonitaCmsnArr[$deliveryId] : 0;
                    $totalKonitaCmsnArr[$deliveryId] += $totalKonitaComsn;
                    $totalPrincipleCmsnArr[$deliveryId] = !empty($totalPrincipleCmsnArr[$deliveryId]) ? $totalPrincipleCmsnArr[$deliveryId] : 0;
                    $totalPrincipleCmsnArr[$deliveryId] += $totalPrincipalComsn;
                }
            }
        }



        $billingArr = $billingArr2 = $colspanArr = $inquiryIdArr = [];
        if (!empty($deliveryArr)) {
            foreach ($deliveryArr as $item) {
                $inquiryIdArr[$item->inquiry_id] = $item->inquiry_id;
                $billingArr[$item->inquiry_id]['buyer_name'] = $item->buyer_name;
                $billingArr[$item->inquiry_id]['order_no'] = $item->order_no;

                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_no'] = $item->bl_no;
                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_details'] = !empty($deliveryDetailsArr[$item->delivery_id]) ? $deliveryDetailsArr[$item->delivery_id] : '';
                $colspanArr[$item->inquiry_id][$item->delivery_id] = $item->delivery_id;
            }
        }



        $commissionArr = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)->pluck('inquiry_id', 'inquiry_id')->toArray();


        $konitaBankList = ['0' => __('label.SELECT_KONITA_BANK_OPT')] + KonitaBankAccount::pluck('bank_name', 'id')->toArray();

        $supplierArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                        ->join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                        ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                        ->where('delivery.shipment_status', '2')
                        ->where('delivery.payment_status', '1')
                        ->where('delivery.buyer_payment_status', '1')
                        ->pluck('supplier.name', 'supplier.id')->toArray();
        $supplierList = array('0' => __('label.SELECT_SUPPLIER_OPT')) + $supplierArr;



        return view('billing.index')->with(compact('request', 'supplierList', 'billingArr', 'billingArr2'
                                , 'colspanArr', 'konitaBankList', 'rowspanArr', 'rowspanOrder'
                                , 'sipmentQtyArr', 'totalKonitaCmsnArr', 'totalPrincipleCmsnArr'
                                , 'commissionArr', 'prevComsnArr', 'shipmentComsnArr'));
    }

    public function getBillingCreateData(Request $request) {

        $validator = Validator::make($request->all(), [
                    'supplier_id' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('billing/billingCreate')
                            ->withInput()
                            ->withErrors($validator);
        }

        $url = 'generate=true' . '&supplier_id=' . $request->supplier_id;
        return Redirect::to('billing/billingCreate?' . $url);
    }

    public function billingPreviewData(Request $request) {



        $errors = [];
        $checkboxArr = $request->checkbox;
        $deliveryArr = $request->deliveryArr;
//        $gift = Helper::numberFormatDigit2($request->gift);
        $konitaBankId = $request->konita_bank_id;
        $supplierId = $request->supplier_id;

        if (empty($checkboxArr)) {
            $errors [] = __('label.MUST_BE_ONE_BOX_CHECKED');
        }

        if (empty($request->konita_bank_id)) {
            $errors [] = __('label.THE_KONITA_BANK_FIELDS_IS_REQUIRED');
        }

        //Commission Set Check validation
        $inquiryIdArr = [];
        if (!empty($checkboxArr)) {
            foreach ($checkboxArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
            }
        }

        $commissionArr = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)->pluck('inquiry_id', 'inquiry_id')->toArray();
        $orderNoArr = Lead::whereIn('id', $inquiryIdArr)->pluck('order_no', 'id')->toArray();

        if (!empty($inquiryIdArr)) {
            foreach ($inquiryIdArr as $inquiryId) {
                if (!array_key_exists($inquiryId, $commissionArr)) {
                    $errors [] = __('label.ORDER_NO') . ': ' . $orderNoArr[$inquiryId] . ' ' . __('label.COMMISSION_IS_NOT_SET_YET');
                }
            }
        }

        //end of commission Set Check

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $errors), 400);
        }

        //CHECKARR MATCH DATA
        $targetArr = $inquiryId2 = [];
        foreach ($checkboxArr as $orderId => $values) {
            foreach ($values as $deliveryId => $item) {
                foreach ($deliveryArr as $orderId2 => $values2) {
                    if ($orderId == $orderId2) {
                        foreach ($values2 as $deliveryId2 => $item2) {
                            if ($deliveryId == $deliveryId2) {
                                $inquiryId2[$orderId] = $orderId;
                                $targetArr[$orderId][$deliveryId] = $item2;
                                $rowspanArr[$orderId][$deliveryId] = $deliveryId;
                            }
                        }
                    }
                }
            }
        }

        $orderWiseBuyerList = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->whereIn('inquiry.id', $inquiryId2)
                        ->pluck('buyer.name', 'inquiry.id')->toArray();



        $orderNoList = Lead::whereIn('inquiry.id', $inquiryId2)
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();




        //FIANL ARR
        $orderNoHistoryArr = $quantityArr = [];
        if (!empty($targetArr)) {
            foreach ($targetArr as $inquiryId => $target) {
                foreach ($target as $deliveryId => $deliveryDetails) {
                    foreach ($deliveryDetails as $deliveryDetailsId => $item) {
                        $orderNoHistoryArr[$inquiryId]['buyer'] = !empty($orderWiseBuyerList[$inquiryId]) ? $orderWiseBuyerList[$inquiryId] : '';
                        $orderNoHistoryArr[$inquiryId]['order_no'] = !empty($orderNoList[$inquiryId]) ? $orderNoList[$inquiryId] : '';
                        //total qty
                        $totalQuantityArr[$inquiryId][$deliveryId] = !empty($totalQuantityArr[$inquiryId][$deliveryId]) ? $totalQuantityArr[$inquiryId][$deliveryId] : 0;
                        $totalQuantityArr[$inquiryId][$deliveryId] += $item['shipmentQty'];
                        $orderNoHistoryArr[$inquiryId]['total_shipmentQty'] = !empty($totalQuantityArr[$inquiryId]) ? array_sum($totalQuantityArr[$inquiryId]) : 0;

                        //measureUnit wise gty
                        $quantityArr[$inquiryId][$item['measure_unit_id']] = !empty($quantityArr[$inquiryId][$item['measure_unit_id']]) ? $quantityArr[$inquiryId][$item['measure_unit_id']] : 0;
                        $quantityArr[$inquiryId][$item['measure_unit_id']] += $item['shipmentQty'];
                        $orderNoHistoryArr[$inquiryId]['shipmentQty'] = !empty($quantityArr[$inquiryId]) ? $quantityArr[$inquiryId] : [];

                        $orderNoHistoryArr[$inquiryId]['konita_cmsn'] = $item['konita_cmsn'];
                        $orderNoHistoryArr[$inquiryId]['principle_cmsn'] = $item['principle_cmsn'];
                        $orderNoHistoryArr[$inquiryId]['total_konita_cmsn'] = ($item['konita_cmsn']) * (!empty($quantityArr[$inquiryId]) ? array_sum($quantityArr[$inquiryId]) : 0);
                        $orderNoHistoryArr[$inquiryId]['total_principle_cmsn'] = ($item['principle_cmsn']) * (!empty($quantityArr[$inquiryId]) ? array_sum($quantityArr[$inquiryId]) : 0);
                    }
                }
            }
        }

        $orderWiseTotalKonitaCmsn = Helper::numberFormatDigit2($request->total_konita_cmsn);
        $orderWiseTotalPrincipleCmsn = Helper::numberFormatDigit2($request->total_principle_cmsn);
        $netReceivable = ($orderWiseTotalKonitaCmsn - $orderWiseTotalPrincipleCmsn);
        $netReceivable = Helper::numberFormatDigit2($netReceivable);


        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $request->konita_bank_id)->first();

        $signatoryInfo = SignatoryInfo::first();

        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $request->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();

        //supplier contact person list
        if (!empty($supplierInfo)) {
            $contactPersonInfo = json_decode($supplierInfo->contact_person_data, true);
        }

        $contactPersonList = ['0' => __('label.SELECT_ATTN_OPT')];
        if (!empty($contactPersonInfo)) {
            foreach ($contactPersonInfo as $key => $item) {
                $contactPersonList[$key] = $item['name'];
            }
        }
        //end

        $view = view('billing.showPreviewModal', compact('request', 'targetArr', 'orderWiseBuyerList'
                        , 'rowspanArr', 'orderNoList', 'konitaBankInfo', 'signatoryInfo'
                        , 'supplierInfo', 'orderNoHistoryArr'
                        , 'contactPersonList', 'orderWiseTotalKonitaCmsn'
                        , 'orderWiseTotalPrincipleCmsn', 'netReceivable'))->render();
        return response()->json(['html' => $view]);
    }

    public function billingInvoiceStore(Request $request) {

        $blNoHistoryArr = $request->bl_no_history;
        $orderNohistoryArr = $request->order_no_history;
        $commissionHistoryArr = $request->commission_history;


        $rules = $message = [];
        $rules = [
            'invoice_date' => 'required',
            'invoice_no' => 'required|unique:invoice',
            'subject' => 'required',
            'supplier_contact_person_identify' => 'required|not_in:0',
        ];

        $message = [
            'supplier_contact_person_identify.not_in' => __('label.THE_ATTN_FIELDS_IS_REQUIRED'),
        ];

        if (!empty($request->has_gift)) {
            $rules['gift'] = 'required';
            $message['gift.required'] = __('label.MISC_IS_REQUIRED');
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        // contactPersonName
        $supplierInfo = Supplier::where('supplier.id', $request->supplier_id)
                ->select('supplier.contact_person_data')
                ->first();

        if (!empty($supplierInfo)) {
            $contactPersonInfo = json_decode($supplierInfo->contact_person_data, true);
        }

        $contactPersonName = '';
        if (!empty($contactPersonInfo)) {
            foreach ($contactPersonInfo as $key => $item) {
                if ($key == $request->supplier_contact_person_identify) {
                    $contactPersonName = $item['name'];
                }
            }
        }
        //End contactPersonName

        $jsonEncodeBlArr = json_encode($blNoHistoryArr);
        $jsonEncodeOrderArr = json_encode($orderNohistoryArr);
        $jsonEncodeCommissionHistoryArr = json_encode($commissionHistoryArr);


        $deliveryIdArr = [];
        if (!empty($blNoHistoryArr)) {
            foreach ($blNoHistoryArr as $deliveryId => $item) {
                $deliveryIdArr[$deliveryId] = $deliveryId;
            }
        }

        $target = new Invoice;
        $target->supplier_id = $request->supplier_id;
        $target->konita_bank_id = $request->konita_bank_id;
        $target->invoice_no = $request->invoice_no;
        $target->date = Helper::dateFormatConvert($request->invoice_date);
        $target->subject = $request->subject;
        $target->bl_no_history = $jsonEncodeBlArr;
        $target->order_no_history = $jsonEncodeOrderArr;
        $target->supplier_contact_person_identify = $request->supplier_contact_person_identify;
        $target->supplier_contact_person = $contactPersonName;
        $target->sub_total = $request->sub_total;
        $target->admin_cost = $request->admin_cost;
        $target->net_receivable = $request->net_receivable;
        if (!empty($request->has_gift)) {
            $target->gift = $request->gift;
        }
        $target->total_amount = $request->total_amount;
        $target->updated_at = date('Y-m-d H:i:s');
        $target->updated_by = Auth::user()->id;

//        echo '<pre>';
//        print_r($target->toArray());
//        exit;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                Delivery::whereIn('id', $deliveryIdArr)->update(['payment_status' => '2']);

                $data = [];
                $i = 0;
                if (!empty($request->commission_history)) {
                    foreach ($request->commission_history as $inquiryId => $inquiryVal) {
                        foreach ($inquiryVal as $deliveryId => $deliveryVal) {
                            foreach ($deliveryVal as $deliveryDetailsId => $item) {
                                $data[$i]['invoice_id'] = $target->id;
                                $data[$i]['inquiry_id'] = $inquiryId;
                                $data[$i]['delivery_id'] = $deliveryId;
                                $data[$i]['delivery_details_id'] = $deliveryDetailsId;
                                $data[$i]['shipment_qty'] = $item['shipmentQty'] ?? 0;
                                $data[$i]['total_company_konita_cmsn'] = $item['total_company_konita_cmsn'] ?? 0;
                                $data[$i]['total_sales_person_cmsn'] = $item['total_sales_person_cmsn'] ?? 0;
                                $data[$i]['total_buyer_cmsn'] = $item['total_buyer_cmsn'] ?? 0;
                                $data[$i]['total_rebate_cmsn'] = $item['total_rebate_cmsn'] ?? 0;
                                $data[$i]['total_principle_cmsn'] = $item['total_principle_cmsn'] ?? 0;
                                $data[$i]['total_konita_cmsn'] = $item['total_konita_cmsn'] ?? 0;
                                $i++;
                            }
                        }
                    }
                }
                InvoiceCommissionHistory::insert($data);
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INVOICE_CREATED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {

            //            echo '<pre>';
//            print_r($e->getMessage());
//            exit;
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INVOICE_COULD_NOT_BE_CREATED')], 401);
        }
    }

    //new commission setup modal function
    public function getCommissionSetupModal(Request $request) {
        $loadView = 'billing';
        return Common::getCommissionSetupModal($request, $loadView);
    }

    public function commissionSetupSave(Request $request) {

        return Common::commissionSetupSave($request);
    }

    //end of commission
    //ledger part
    public function billingLedgerView(Request $request) {
        $approvalStatusList = [
            '0' => __('label.SELECT_APPROVAL_STATUS_OPT')
            , '1' => __('label.PENDING_FOR_APPROVAL')
            , '2' => __('label.APPROVED')
        ];

        $inquiryIdArr = InvoiceCommissionHistory::pluck('inquiry_id', 'inquiry_id')->toArray();

        $supplierArr = $orderNoArr = [];
        if (!empty($inquiryIdArr)) {
            $supplierArr = Lead::join('supplier', 'supplier.id', '=', 'inquiry.supplier_id')
                            ->whereIn('inquiry.id', $inquiryIdArr)
                            ->pluck('supplier.name', 'supplier.id')->toArray();
            $orderNoArr = Lead::whereIn('id', $inquiryIdArr)
                            ->orderBy('order_no', 'asc')
                            ->pluck('order_no', 'order_no')->toArray();
        }

        $supplierList = array('0' => __('label.SELECT_SUPPLIER_OPT')) + $supplierArr;
        $orderNoList = array('0' => __('label.SELECT_ORDER_NO_OPT')) + $orderNoArr;


        $targetArr = Invoice::join('supplier', 'supplier.id', '=', 'invoice.supplier_id')
                ->join('konita_bank_account', 'konita_bank_account.id', '=', 'invoice.konita_bank_id');
        if (!empty($request->invoice_no)) {
            $targetArr = $targetArr->where('invoice.invoice_no', 'LIKE', '%' . $request->invoice_no . '%');
        }
        $invoiceIdArr = [];
        if (!empty($request->order_no)) {
            $invoiceIdArr = InvoiceCommissionHistory::join('inquiry', 'inquiry.id', 'invoice_commission_history.inquiry_id')
                    ->where('inquiry.order_no', $request->order_no)
                    ->pluck('invoice_commission_history.invoice_id', 'invoice_commission_history.invoice_id')
                    ->toArray();
            if (!empty($invoiceIdArr)) {
                $targetArr = $targetArr->whereIn('invoice.id', $invoiceIdArr);
            }
        }
        if (!empty($request->supplier_id)) {
            $targetArr = $targetArr->where('invoice.supplier_id', $request->supplier_id);
        }
        $approvalStatus = $request->approval_status;
        if (!empty($approvalStatus)) {
            if ($approvalStatus == '1') {
                $targetArr = $targetArr->where('invoice.approval_status', '0');
            } else if ($approvalStatus == '2') {
                $targetArr = $targetArr->where('invoice.approval_status', '1');
            }
        }
        $targetArr = $targetArr->select('invoice.id', 'invoice.invoice_no', 'invoice.date', 'invoice.gift'
                        , 'supplier.name as supplierName', 'konita_bank_account.bank_name'
                        , 'order_no_history', 'invoice.total_amount', 'invoice.approval_status')
                ->orderBy('invoice.approval_status', 'asc')
                ->orderBy('invoice.date', 'desc')
                ->get();

        //invoice already received
        $alreadyReceivedInvoiceList = Receive::pluck('invoice_id', 'invoice_id')->toArray();

        return view('billing.ledgerView')->with(compact('request', 'supplierList', 'targetArr'
                                , 'orderNoList', 'approvalStatusList', 'alreadyReceivedInvoiceList'));
    }

    public function filter(Request $request) {
        $url = 'invoice_no=' . urlencode($request->invoice_no) . '&order_no=' . urlencode($request->order_no)
                . '&supplier_id=' . $request->supplier_id . '&approval_status=' . $request->approval_status;
        return Redirect::to('billing/billingLedgerView?' . $url);
    }

    public function approve(Request $request) {
        $target = Invoice::find($request->invoice_id);

        $target->approval_status = '1';
        $target->approved_at = date('Y-m-d H:i:s');
        $target->approved_by = Auth::user()->id;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.INVOICE_APPROVED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.INVOICE_COULD_NOT_BE_APPROVED')), 401);
        }
    }

    public function deny(Request $request) {
        $deliveryIdArr = InvoiceCommissionHistory::where('invoice_id', $request->invoice_id)
                        ->pluck('delivery_id', 'delivery_id')->toArray();

        DB::beginTransaction();
        try {
            if (Invoice::where('id', $request->invoice_id)->delete()) {
                Delivery::whereIn('id', $deliveryIdArr)->update([
                    'payment_status' => '1'
                ]);
                InvoiceCommissionHistory::where('invoice_id', $request->invoice_id)->delete();
            }

            DB::commit();
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.INVOICE_DENIED_SUCCESSFULLY')], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INVOICE_COULD_NOT_BE_DENIED')], 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Invoice::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

//        //Dependency
//        $dependencyArr = [
//            'ProductCategory' => ['1' => 'parent_id'],
//            'Product' => ['1' => 'product_category_id'],
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('crmNewOpportunity' . $pageNumber);
//                }
//            }
//        }

        DB::beginTransaction();
        try {
            if ($target->delete()) {
                Delivery::join('invoice_commission_history', 'invoice_commission_history.delivery_id', 'delivery.id')
                        ->where('invoice_commission_history.invoice_id', $id)
                        ->update(['payment_status' => '1']);
                InvoiceCommissionHistory::where('invoice_id', $id)->delete();
            }
            DB::commit();
            Session::flash('error', __('label.INVOICE_DELETED_SUCCESSFULLY'));
        } catch (\Throwable $e) {

            DB::rollback();
            Session::flash('error', __('label.INVOICE_COULD_NOT_BE_DELETED'));
        }
        return redirect('billing/billingLedgerView' . $request->url);
    }

    public function billingLedgerDetails(Request $request) {

        $invoiceInfo = Invoice::where('id', $request->invoice_id)->first();

//        echo '<pre>';
//        print_r($invoiceInfo->toArray());
//        exit;

        $orderNoHistoryArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoHistoryArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = $totalQty = [];
        if (!empty($orderNoHistoryArr)) {
            foreach ($orderNoHistoryArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
                $totalQty['total_gty'] = !empty($totalQty['total_gty']) ? $totalQty['total_gty'] : 0;
                $totalQty['total_gty'] += $item['qty'];
            }
        }


        $orderWiseBuyerList = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('buyer.name', 'inquiry.id')->toArray();
        $orderNoList = Lead::whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();

        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $invoiceInfo->konita_bank_id)->first();
        $signatoryInfo = SignatoryInfo::first();
        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $invoiceInfo->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();



        $view = view('billing.showInvoiceDetailsModal', compact('request', 'invoiceInfo'
                        , 'konitaBankInfo', 'signatoryInfo', 'supplierInfo'
                        , 'orderNoList', 'orderWiseBuyerList'
                        , 'orderNoHistoryArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function billingFullLedgerDetails(Request $request) {

        $invoiceInfo = Invoice::where('id', $request->invoice_id)->first();

        $orderNoArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = [];
        if (!empty($orderNoArr)) {
            foreach ($orderNoArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
            }
        }

        $deliveryArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->whereIn('inquiry.id', $inquiryIdArr)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '2')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id'
                        , 'inquiry.buyer_id', 'inquiry.order_no', 'buyer.name as buyer_name')
                ->get();

        $deliveryDetailsInfo = Delivery::join('delivery_details', 'delivery_details.delivery_id', '=', 'delivery.id')
                ->join('inquiry_details', 'inquiry_details.id', '=', 'delivery_details.inquiry_details_id')
                ->join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry.id', $inquiryIdArr)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '2')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('delivery_details.id as delivery_details_id', 'inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id', 'inquiry.buyer_id'
                        , 'inquiry.order_no', 'buyer.name as buyer_name'
                        , 'delivery_details.inquiry_details_id', 'delivery_details.shipment_quantity'
                        , 'inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'inquiry_details.unit_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'inquiry_details.quantity as total_quantity'
                        , 'measure_unit.id as measure_unit_id'
                        , 'commission_setup.konita_cmsn', 'commission_setup.principle_cmsn'
                        , 'commission_setup.sales_person_cmsn', 'commission_setup.buyer_cmsn'
                        , 'commission_setup.rebate_cmsn')
                ->get();


        $deliveryDetailsArr = $rowCountArr = $prevComsnArr = $shipmentComsnArr = $inquiryId = $shipmentQtyArr = [];
        if (!$deliveryDetailsInfo->isEmpty()) {
            foreach ($deliveryDetailsInfo as $item) {

                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['delivery_details_id'] = $item->delivery_details_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['product_name'] = $item->product_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_name'] = $item->unit_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['measure_unit_id'] = $item->measure_unit_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['brand_name'] = $item->brand_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['grade_name'] = $item->grade_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_quantity'] = $item->total_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['shipment_qty'] = $item->shipment_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_price'] = $item->unit_price;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['gsm'] = $item->gsm;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['inquiry_details_id'] = $item->inquiry_details_id;
                $totalPrice = (!empty($item->shipment_quantity) ? $item->shipment_quantity : 0) * (!empty($item->unit_price) ? $item->unit_price : 0);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_price'] = $totalPrice;


                $shipmentQtyArr[$item->inquiry_id][$item->inquiry_details_id][$item->delivery_details_id] = $item->shipment_quantity;

                $konitaCmsn = !empty($item->konita_cmsn) ? $item->konita_cmsn : 0;
                $principleCmsn = !empty($item->principle_cmsn) ? $item->principle_cmsn : 0;
                $salesPersonCmsn = !empty($item->sales_person_cmsn) ? $item->sales_person_cmsn : 0;
                $buyerCmsn = !empty($item->buyer_cmsn) ? $item->buyer_cmsn : 0;
                $rebateCmsn = !empty($item->rebate_cmsn) ? $item->rebate_cmsn : 0;

                $konitaCommission = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['konita_cmsn'] = $konitaCommission;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['principle_cmsn'] = $principleCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_konita_cmsn'] = ($konitaCommission * $item->shipment_quantity);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_principle_cmsn'] = ($principleCmsn * $item->shipment_quantity);

                //commission 
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['company_konita_cmsn'] = $konitaCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_company_konita_cmsn'] = ($item->shipment_quantity * $konitaCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['sales_person_cmsn'] = $salesPersonCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_sales_person_cmsn'] = ($item->shipment_quantity * $salesPersonCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['buyer_cmsn'] = $buyerCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_buyer_cmsn'] = ($item->shipment_quantity * $buyerCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['rebate_cmsn'] = $rebateCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_rebate_cmsn'] = ($item->shipment_quantity * $rebateCmsn);

                $rowCountArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id] = $item->delivery_details_id;
            }
        }

        $commissionInfo = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)
                ->select('konita_cmsn', 'principle_cmsn', 'sales_person_cmsn', 'buyer_cmsn'
                        , 'rebate_cmsn', 'inquiry_details_id', 'inquiry_id')
                ->get();
        if (!$commissionInfo->isEmpty()) {
            foreach ($commissionInfo as $cmsn) {
                $konitaCmsn = !empty($cmsn->konita_cmsn) ? $cmsn->konita_cmsn : 0;
                $principleCmsn = !empty($cmsn->principle_cmsn) ? $cmsn->principle_cmsn : 0;
                $salesPersonCmsn = !empty($cmsn->sales_person_cmsn) ? $cmsn->sales_person_cmsn : 0;
                $buyerCmsn = !empty($cmsn->buyer_cmsn) ? $cmsn->buyer_cmsn : 0;
                $rebateCmsn = !empty($cmsn->rebate_cmsn) ? $cmsn->rebate_cmsn : 0;

                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['konita_cmsn'] = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
            }
        }


        if (!empty($shipmentQtyArr)) {
            foreach ($shipmentQtyArr as $inquiryId => $inqDetailsInfo) {
                foreach ($inqDetailsInfo as $inqDetId => $delvDetailsInfo) {
                    foreach ($delvDetailsInfo as $delvDetId => $shipQty) {
                        $inquiryDetailsId = !empty($prevComsnArr[$inquiryId]) && array_key_exists($inqDetId, $prevComsnArr[$inquiryId]) ? $inqDetId : 0;

                        $konitaComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : 0;
                        $shipmentComsnArr[$delvDetId]['total_konita_cmsn'] = $konitaComsn * $shipQty;
                    }
                }
            }
        }

        //ROWSPAN ARR
        $rowspanArr = $rowspanOrder = [];
        if (!empty($rowCountArr)) {
            foreach ($rowCountArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $deliveryId => $deliveryData) {
                    $rowspanArr[$inquiryId][$deliveryId] = count($deliveryData);
                    $rowspanOrder[$inquiryId] = array_sum($rowspanArr[$inquiryId]);
                }
            }
        }


        //TOTAL SHIPMENT QTY & KONITA & PRINCIPLE CMSN UNDER BL NO WISE
        $sipmentQtyArr = $totalKonitaCmsnArr = $totalPrincipleCmsnArr = [];
        if (!empty($deliveryDetailsArr)) {
            foreach ($deliveryDetailsArr as $deliveryId => $deliveryData) {
                foreach ($deliveryData as $devlDetId => $deliveryDetails) {
                    $sipmentQtyArr[$deliveryId] = !empty($sipmentQtyArr[$deliveryId]) ? $sipmentQtyArr[$deliveryId] : 0;
                    $sipmentQtyArr[$deliveryId] += $deliveryDetails['shipment_qty'];
                }
            }
        }

        $billingArr = $billingArr2 = $colspanArr = [];
        if (!empty($deliveryArr)) {
            foreach ($deliveryArr as $item) {
                $billingArr[$item->inquiry_id]['buyer_name'] = $item->buyer_name;
                $billingArr[$item->inquiry_id]['order_no'] = $item->order_no;

                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_no'] = $item->bl_no;
                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_details'] = !empty($deliveryDetailsArr[$item->delivery_id]) ? $deliveryDetailsArr[$item->delivery_id] : '';
                $colspanArr[$item->inquiry_id][$item->delivery_id] = $item->delivery_id;
            }
        }
        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $invoiceInfo->konita_bank_id)->first();
        $signatoryInfo = SignatoryInfo::first();
        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $invoiceInfo->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();



        $view = view('billing.showFullInvoiceDetailsModal', compact('request', 'invoiceInfo'
                        , 'konitaBankInfo', 'signatoryInfo', 'supplierInfo', 'rowspanArr', 'rowspanOrder'
                        , 'prevComsnArr', 'shipmentComsnArr', 'billingArr', 'billingArr2'))->render();
        return response()->json(['html' => $view]);
    }

    public function billingFullLedgerDetailsPrint(Request $request) {

        $invoiceInfo = Invoice::where('id', $request->invoice_id)->first();

        $orderNoArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = [];
        if (!empty($orderNoArr)) {
            foreach ($orderNoArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
            }
        }

        $deliveryArr = Delivery::join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->whereIn('inquiry.id', $inquiryIdArr)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '2')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id'
                        , 'inquiry.buyer_id', 'inquiry.order_no', 'buyer.name as buyer_name')
                ->get();

        $deliveryDetailsInfo = Delivery::join('delivery_details', 'delivery_details.delivery_id', '=', 'delivery.id')
                ->join('inquiry_details', 'inquiry_details.id', '=', 'delivery_details.inquiry_details_id')
                ->join('inquiry', 'inquiry.id', '=', 'delivery.inquiry_id')
                ->leftJoin('commission_setup', 'commission_setup.inquiry_id', '=', 'delivery.inquiry_id')
                ->join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.measure_unit_id')
                ->join('brand', 'brand.id', '=', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry.id', $inquiryIdArr)
                ->whereIn('inquiry.order_status', ['3', '4'])
                ->where('delivery.payment_status', '2')
                ->where('delivery.shipment_status', '2')
                ->where('delivery.buyer_payment_status', '1')
                ->select('delivery_details.id as delivery_details_id', 'inquiry.id as inquiry_id', 'delivery.bl_no'
                        , 'delivery.id as delivery_id', 'inquiry.buyer_id'
                        , 'inquiry.order_no', 'buyer.name as buyer_name'
                        , 'delivery_details.inquiry_details_id', 'delivery_details.shipment_quantity'
                        , 'inquiry_details.product_id', 'inquiry_details.brand_id', 'inquiry_details.grade_id'
                        , 'inquiry_details.unit_price', 'inquiry_details.gsm', 'measure_unit.name as unit_name'
                        , 'product.name as product_name', 'brand.name as brand_name'
                        , 'grade.name as grade_name', 'inquiry_details.quantity as total_quantity'
                        , 'measure_unit.id as measure_unit_id'
                        , 'commission_setup.konita_cmsn', 'commission_setup.principle_cmsn'
                        , 'commission_setup.sales_person_cmsn', 'commission_setup.buyer_cmsn'
                        , 'commission_setup.rebate_cmsn')
                ->get();


        $deliveryDetailsArr = $rowCountArr = $prevComsnArr = $shipmentComsnArr = $inquiryId = $shipmentQtyArr = [];
        if (!$deliveryDetailsInfo->isEmpty()) {
            foreach ($deliveryDetailsInfo as $item) {

                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['delivery_details_id'] = $item->delivery_details_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['product_name'] = $item->product_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_name'] = $item->unit_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['measure_unit_id'] = $item->measure_unit_id;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['brand_name'] = $item->brand_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['grade_name'] = $item->grade_name;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_quantity'] = $item->total_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['shipment_qty'] = $item->shipment_quantity;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['unit_price'] = $item->unit_price;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['gsm'] = $item->gsm;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['inquiry_details_id'] = $item->inquiry_details_id;
                $totalPrice = (!empty($item->shipment_quantity) ? $item->shipment_quantity : 0) * (!empty($item->unit_price) ? $item->unit_price : 0);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_price'] = $totalPrice;


                $shipmentQtyArr[$item->inquiry_id][$item->inquiry_details_id][$item->delivery_details_id] = $item->shipment_quantity;

                $konitaCmsn = !empty($item->konita_cmsn) ? $item->konita_cmsn : 0;
                $principleCmsn = !empty($item->principle_cmsn) ? $item->principle_cmsn : 0;
                $salesPersonCmsn = !empty($item->sales_person_cmsn) ? $item->sales_person_cmsn : 0;
                $buyerCmsn = !empty($item->buyer_cmsn) ? $item->buyer_cmsn : 0;
                $rebateCmsn = !empty($item->rebate_cmsn) ? $item->rebate_cmsn : 0;

                $konitaCommission = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['konita_cmsn'] = $konitaCommission;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['principle_cmsn'] = $principleCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_konita_cmsn'] = ($konitaCommission * $item->shipment_quantity);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_principle_cmsn'] = ($principleCmsn * $item->shipment_quantity);

                //commission 
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['company_konita_cmsn'] = $konitaCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_company_konita_cmsn'] = ($item->shipment_quantity * $konitaCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['sales_person_cmsn'] = $salesPersonCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_sales_person_cmsn'] = ($item->shipment_quantity * $salesPersonCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['buyer_cmsn'] = $buyerCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_buyer_cmsn'] = ($item->shipment_quantity * $buyerCmsn);
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['rebate_cmsn'] = $rebateCmsn;
                $deliveryDetailsArr[$item->delivery_id][$item->delivery_details_id]['total_rebate_cmsn'] = ($item->shipment_quantity * $rebateCmsn);

                $rowCountArr[$item->inquiry_id][$item->delivery_id][$item->delivery_details_id] = $item->delivery_details_id;
            }
        }

        $commissionInfo = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)
                ->select('konita_cmsn', 'principle_cmsn', 'sales_person_cmsn', 'buyer_cmsn'
                        , 'rebate_cmsn', 'inquiry_details_id', 'inquiry_id')
                ->get();
        if (!$commissionInfo->isEmpty()) {
            foreach ($commissionInfo as $cmsn) {
                $konitaCmsn = !empty($cmsn->konita_cmsn) ? $cmsn->konita_cmsn : 0;
                $principleCmsn = !empty($cmsn->principle_cmsn) ? $cmsn->principle_cmsn : 0;
                $salesPersonCmsn = !empty($cmsn->sales_person_cmsn) ? $cmsn->sales_person_cmsn : 0;
                $buyerCmsn = !empty($cmsn->buyer_cmsn) ? $cmsn->buyer_cmsn : 0;
                $rebateCmsn = !empty($cmsn->rebate_cmsn) ? $cmsn->rebate_cmsn : 0;

                $prevComsnArr[$cmsn->inquiry_id][$cmsn->inquiry_details_id]['konita_cmsn'] = ($konitaCmsn + $principleCmsn + $salesPersonCmsn + $buyerCmsn + $rebateCmsn);
            }
        }


        if (!empty($shipmentQtyArr)) {
            foreach ($shipmentQtyArr as $inquiryId => $inqDetailsInfo) {
                foreach ($inqDetailsInfo as $inqDetId => $delvDetailsInfo) {
                    foreach ($delvDetailsInfo as $delvDetId => $shipQty) {
                        $inquiryDetailsId = !empty($prevComsnArr[$inquiryId]) && array_key_exists($inqDetId, $prevComsnArr[$inquiryId]) ? $inqDetId : 0;

                        $konitaComsn = !empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : 0;
                        $shipmentComsnArr[$delvDetId]['total_konita_cmsn'] = $konitaComsn * $shipQty;
                    }
                }
            }
        }

        //ROWSPAN ARR
        $rowspanArr = $rowspanOrder = [];
        if (!empty($rowCountArr)) {
            foreach ($rowCountArr as $inquiryId => $inquiryData) {
                foreach ($inquiryData as $deliveryId => $deliveryData) {
                    $rowspanArr[$inquiryId][$deliveryId] = count($deliveryData);
                    $rowspanOrder[$inquiryId] = array_sum($rowspanArr[$inquiryId]);
                }
            }
        }


        //TOTAL SHIPMENT QTY & KONITA & PRINCIPLE CMSN UNDER BL NO WISE
        $sipmentQtyArr = $totalKonitaCmsnArr = $totalPrincipleCmsnArr = [];
        if (!empty($deliveryDetailsArr)) {
            foreach ($deliveryDetailsArr as $deliveryId => $deliveryData) {
                foreach ($deliveryData as $devlDetId => $deliveryDetails) {
                    $sipmentQtyArr[$deliveryId] = !empty($sipmentQtyArr[$deliveryId]) ? $sipmentQtyArr[$deliveryId] : 0;
                    $sipmentQtyArr[$deliveryId] += $deliveryDetails['shipment_qty'];
                }
            }
        }

        $billingArr = $billingArr2 = $colspanArr = [];
        if (!empty($deliveryArr)) {
            foreach ($deliveryArr as $item) {
                $billingArr[$item->inquiry_id]['buyer_name'] = $item->buyer_name;
                $billingArr[$item->inquiry_id]['order_no'] = $item->order_no;

                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_no'] = $item->bl_no;
                $billingArr2[$item->inquiry_id][$item->delivery_id]['bl_details'] = !empty($deliveryDetailsArr[$item->delivery_id]) ? $deliveryDetailsArr[$item->delivery_id] : '';
                $colspanArr[$item->inquiry_id][$item->delivery_id] = $item->delivery_id;
            }
        }
        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $invoiceInfo->konita_bank_id)->first();
        $signatoryInfo = SignatoryInfo::first();
        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $invoiceInfo->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();



        return view('billing.print.fullInvoice', compact('request', 'invoiceInfo'
                        , 'konitaBankInfo', 'signatoryInfo', 'supplierInfo', 'rowspanArr', 'rowspanOrder'
                        , 'prevComsnArr', 'shipmentComsnArr', 'billingArr', 'billingArr2'));
    }

    public function billingLedgerPrint(Request $request) {

        $invoiceInfo = Invoice::where('id', $request->invoice_id)->first();

        $orderNoHistoryArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoHistoryArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = $totalQty = [];
        if (!empty($orderNoHistoryArr)) {
            foreach ($orderNoHistoryArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
                $totalQty['total_gty'] = !empty($totalQty['total_gty']) ? $totalQty['total_gty'] : 0;
                $totalQty['total_gty'] += $item['qty'];
            }
        }

        $orderWiseBuyerList = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('buyer.name', 'inquiry.id')->toArray();
        $orderNoList = Lead::whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();

        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $invoiceInfo->konita_bank_id)->first();
        $signatoryInfo = SignatoryInfo::first();
        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $invoiceInfo->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }




        return view('billing.print.index')->with(compact('request', 'invoiceInfo'
                                , 'konitaBankInfo', 'signatoryInfo', 'supplierInfo'
                                , 'orderNoList', 'orderWiseBuyerList'
                                , 'orderNoHistoryArr', 'konitaInfo', 'phoneNumber'));
    }

    public function billingLedgerPdf(Request $request) {
        $invoiceInfo = Invoice::where('id', $request->invoice_id)->first();

        $orderNoHistoryArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoHistoryArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = $totalQty = [];
        if (!empty($orderNoHistoryArr)) {
            foreach ($orderNoHistoryArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
                $totalQty['total_gty'] = !empty($totalQty['total_gty']) ? $totalQty['total_gty'] : 0;
                $totalQty['total_gty'] += $item['qty'];
            }
        }

        $orderWiseBuyerList = Lead::join('buyer', 'buyer.id', '=', 'inquiry.buyer_id')
                        ->whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('buyer.name', 'inquiry.id')->toArray();
        $orderNoList = Lead::whereIn('inquiry.id', $inquiryIdArr)
                        ->pluck('inquiry.order_no', 'inquiry.id')->toArray();

        //Endof Arr Data

        $konitaBankInfo = KonitaBankAccount::where('id', $invoiceInfo->konita_bank_id)->first();
        $signatoryInfo = SignatoryInfo::first();
        $supplierInfo = Supplier::join('country', 'country.id', '=', 'supplier.country_id')
                ->where('supplier.id', $invoiceInfo->supplier_id)
                ->select('supplier.name', 'supplier.address', 'supplier.contact_person_data'
                        , 'country.name as countryName')
                ->first();

        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }

        $pdf = PDF::loadView('billing.print.index', compact('request', 'invoiceInfo'
                                , 'konitaBankInfo', 'signatoryInfo', 'supplierInfo'
                                , 'orderNoList', 'orderWiseBuyerList'
                                , 'orderNoHistoryArr', 'konitaInfo', 'phoneNumber'))
                ->setPaper('a4', 'portrait')
                ->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('Inv-' . $invoiceInfo->invoice_no . '.pdf');
    }

    public function commissionDetails(Request $request) {

        $invoiceInfo = Invoice::where('id', $request->invoice_id)
                        ->select('order_no_history')->first();

        $orderNoArr = [];
        if (!empty($invoiceInfo)) {
            $orderNoArr = json_decode($invoiceInfo->order_no_history, true);
        }

        $inquiryIdArr = [];
        if (!empty($orderNoArr)) {
            foreach ($orderNoArr as $inquiryId => $item) {
                $inquiryIdArr[$inquiryId] = $inquiryId;
            }
        }

        $prevComsnArr = $targetArr = $inqDetailsArr = $rowspanArr = [];
        $commissionInfoArr = CommissionSetup::whereIn('inquiry_id', $inquiryIdArr)->get();
        if (!$commissionInfoArr->isEmpty()) {
            foreach ($commissionInfoArr as $comsn) {
                $prevComsnArr[$comsn->inquiry_id][$comsn->inquiry_details_id] = $comsn->toArray();
            }
        }

        //inquiry Details
        $inquiryDetails = InquiryDetails::join('inquiry', 'inquiry.id', '=', 'inquiry_details.inquiry_id')
                ->join('product', 'product.id', '=', 'inquiry_details.product_id')
                ->join('brand', 'brand.id', 'inquiry_details.brand_id')
                ->leftJoin('grade', 'grade.id', '=', 'inquiry_details.grade_id')
                ->whereIn('inquiry_details.inquiry_id', $inquiryIdArr)
                ->select('product.name as product_name', 'brand.name as brand_name'
                        , 'inquiry_details.gsm', 'grade.name as grade_name', 'inquiry_details.id as inquiry_details_id'
                        , 'inquiry.order_no', 'inquiry_details.inquiry_id')
                ->get();
        if (!$inquiryDetails->isEmpty()) {
            foreach ($inquiryDetails as $inqDetails) {
                $targetArr[$inqDetails->inquiry_id]['order_no'] = $inqDetails->order_no;
                $inqDetailsArr[$inqDetails->inquiry_id][$inqDetails->inquiry_details_id]['product_name'] = $inqDetails->product_name;
                $inqDetailsArr[$inqDetails->inquiry_id][$inqDetails->inquiry_details_id]['brand_name'] = $inqDetails->brand_name;
                $inqDetailsArr[$inqDetails->inquiry_id][$inqDetails->inquiry_details_id]['grade_name'] = $inqDetails->grade_name;
                $inqDetailsArr[$inqDetails->inquiry_id][$inqDetails->inquiry_details_id]['gsm'] = $inqDetails->gsm;
            }
        }

        if (!empty($inqDetailsArr)) {
            foreach ($inqDetailsArr as $inqId => $inqDet) {
                foreach ($inqDet as $indDetId => $info) {
                    $rowspanArr[$inqId]['order_row_span'] = !empty($rowspanArr[$inqId]['order_row_span']) ? $rowspanArr[$inqId]['order_row_span'] : 0;
                    $rowspanArr[$inqId]['order_row_span'] += 1;
                }
            }
        }

        $view = view('billing.showCmsnDetailsModal', compact('request', 'prevComsnArr', 'targetArr', 'inqDetailsArr', 'rowspanArr'))->render();
        return response()->json(['html' => $view]);
    }

}
