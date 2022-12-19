@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.RECEIVABLE')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'billing/getBillingCreateData','class' => 'form-horizontal')) !!}
                    @csrf
                    <div class="col-md-offset-2 col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                            <div class="col-md-8">
                                {!! Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']) !!}
                                <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                <i class="fa fa-check"></i> @lang('label.GENERATE')
                            </button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>
            @if($request->generate == 'true')
            <div class="row" id="divHide">
                {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'previewForm','class' => 'form-horizontal')) !!}
                {!! Form::hidden('supplier_id',$request->supplier_id) !!} 
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center vcenter" rowspan="3">@lang('label.SL_NO')</th>
                                    <th class="vcenter" rowspan="3">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter" rowspan="3">@lang('label.BUYER')</th>
                                    <th class="text-center vcenter"colspan="14">@lang('label.SHIPMENT')</th>
                                </tr>
                                <tr>
                                    <!--<th class="vcenter" rowspan="2">@lang('label.CHECK')</th>-->
                                    <th class="vcenter" colspan="2" rowspan="2">@lang('label.BL_NO')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.PRODUCT')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.BRAND')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.GRADE')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.GSM')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.TOTAL_QUANTITY')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.RECEIVED_QUANTITY')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.UNIT_PRICE')</th>
                                    <th class="vcenter" rowspan="2">@lang('label.RECEIVED_TOTAL_PRICE')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.COMMISSION')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.TOTAL_COMMISSION')</th>
                                </tr>

                                <tr>
                                    <th class="vcenter">@lang('label.KONITA_COMMISSION')</th>
                                    <th class="vcenter">@lang('label.PRINCIPLE')</th>
                                    <th class="vcenter">@lang('label.KONITA_COMMISSION')</th>
                                    <th class="vcenter">@lang('label.PRINCIPLE')</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if(!empty($billingArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($billingArr as $inquiryId=>$target)
                                <?php
                                $rowspan = !empty($rowspanOrder[$inquiryId]) ? $rowspanOrder[$inquiryId] : 0;
                                $iconCmsn = $commissionSet = '';

                                if (array_key_exists($inquiryId, $commissionArr)) {
                                    $iconCmsn = '<br/><span class="badge badge-primary tooltips"'
                                            . 'title="' . __('label.COMMISSION_ALREADY_SET') . '"><i class="fa fa-usd"></i></span>';
                                } else {
                                    //commission Set
                                    if (!empty($userAccessArr[41][18])) {
                                        $commissionSet = '<br/><button class="btn btn-xs yellow-mint  btn-circle btn-rounded tooltips commission-setup-modal vcenter"'
                                                . ' href="#commissionSetUpModal"  data-toggle="modal" title="' . __('label.COMMISSION_SETUP') . '" 
                                            data-inquiry-id ="' . $inquiryId . '" type="button">
                                        <i class="fa fa-sitemap"></i>
                                    </button>';
                                    }
                                }
                                ?>
                            <td class="text-center vcenter" rowspan="{{$rowspan}}">{!!++$sl!!}</td>
                            <td class="vcenter" rowspan="{{$rowspan}}">{!!$target['order_no'].$commissionSet.$iconCmsn!!}</td>
                            <td class="vcenter" rowspan="{{$rowspan}}">{{$target['buyer_name']}}</td>

                            <?php
                            $i = 0;
                            ?>
                            @foreach($billingArr2[$inquiryId] as $deliveryId=> $item)

                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }

                            $rowspanBl = !empty($rowspanArr[$inquiryId][$deliveryId]) ? $rowspanArr[$inquiryId][$deliveryId] : 0;

                            $shipmentQty = !empty($sipmentQtyArr[$deliveryId]) ? $sipmentQtyArr[$deliveryId] : 0;
                            $totalKonitaCmsn = !empty($totalKonitaCmsnArr[$deliveryId]) ? $totalKonitaCmsnArr[$deliveryId] : 0;
                            $totalPrincipleCmsn = !empty($totalPrincipleCmsnArr[$deliveryId]) ? $totalPrincipleCmsnArr[$deliveryId] : 0;
                            ?>

                            <td class="text-center vcenter" rowspan="{{$rowspanBl}}">
                                <div class="md-checkbox has-success">
                                    {!! Form::checkbox('checkbox['.$inquiryId.']['.$deliveryId.']', null,false, ['id' => $deliveryId,'data-qty'=>$shipmentQty,'data-id'=> $deliveryId,'data-totalKonitaCmsn'=>$totalKonitaCmsn,'data-totalPrincipleCmsn'=>$totalPrincipleCmsn,'class'=> 'md-check sp-check']) !!}
                                    <label for="{!! $deliveryId !!}">
                                        <span class="inc checkbox-text-center"></span>
                                        <span class="check mark-caheck checkbox-text-center"></span>
                                        <span class="box mark-caheck checkbox-text-center"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="text-center vcenter" rowspan="{{$rowspanBl}}">
                                {!! Form::text('bl_no['.$inquiryId.']['.$deliveryId.']',!empty($item['bl_no'])?$item['bl_no']:null, ['id'=> 'blNo'.$deliveryId, 'class' => 'form-control w-200','readonly']) !!} 
                            </td>
                            <?php
                            $j = 0;
                            ?>
                            @foreach($item['bl_details'] as $deliveryDetailsId=>$deliveryDetails)

                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            $totalKonitaComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn'] : 0;
                            $totalPrincipleComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_principal_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_principal_cmsn'] : 0;

                            $konitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                            $principalCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['principal_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['principal_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['principal_cmsn']) ? $prevComsnArr[$inquiryId][0]['principal_cmsn'] : 0));
                            $companyKonitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['company_konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['company_konita_cmsn'] : 0));
                            $salesPersonCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['sales_person_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][0]['sales_person_cmsn'] : 0));
                            $buyerCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['buyer_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][0]['buyer_cmsn'] : 0));
                            $rebateCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['rebate_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][0]['rebate_cmsn'] : 0));

                            $comsnBreakdownTitle = 'Konita:$' . $companyKonitaCmsn
                                    . '&#13;Principal:$' . $principalCmsn
                                    . '&#13;Salesperson:$' . $salesPersonCmsn
                                    . '&#13;Buyer:$' . $buyerCmsn
                                    . '&#13;Rebate:$' . $rebateCmsn;
                            ?>
                            <td>
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][company_konita_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['company_konita_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['company_konita_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_company_konita_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_company_konita_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_company_konita_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][sales_person_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['sales_person_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['sales_person_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_sales_person_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_sales_person_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_sales_person_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][buyer_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['buyer_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['buyer_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_buyer_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_buyer_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_buyer_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][rebate_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['rebate_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['rebate_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_rebate_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_rebate_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_rebate_cmsn']:null) !!}
                                {!! Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][measure_unit_id]',!empty($deliveryDetails['measure_unit_id'])?$deliveryDetails['measure_unit_id']:null) !!}

                                {{!empty($deliveryDetails['product_name'])?$deliveryDetails['product_name']:''}}
                            </td>
                            <td>
                                {{!empty($deliveryDetails['brand_name'])?$deliveryDetails['brand_name']:''}}
                            </td>
                            <td>
                                {{!empty($deliveryDetails['grade_name'])?$deliveryDetails['grade_name']:''}}
                            </td>
                            <td>
                                {{!empty($deliveryDetails['gsm'])?$deliveryDetails['gsm']:''}}
                            </td>
                            <td class="text-right vcenter">
                                {{!empty($deliveryDetails['total_quantity'])?$deliveryDetails['total_quantity']:0}}&nbsp;{{!empty($deliveryDetails['unit_name'])?$deliveryDetails['unit_name']:''}}
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-150">
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($deliveryDetails['shipment_qty'])?$deliveryDetails['shipment_qty']:null, ['id'=> 'shipmentQty'.$deliveryDetailsId, 'data-shipmentQty'=> $deliveryDetailsId, 'class' => 'form-control  shipment_qty text-right','readonly']) !!}
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">{{!empty($deliveryDetails['unit_name'])?$deliveryDetails['unit_name']:''}}</span>
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-150">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][unit_price]',!empty($deliveryDetails['unit_price'])?$deliveryDetails['unit_price']:null, ['id'=> 'unitPrice'.$deliveryDetailsId, 'class' => 'form-control  shipment_qty text-right','readonly']) !!}
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                        {{!empty($deliveryDetails['unit_name'])?'/'.' '.$deliveryDetails['unit_name']:''}}
                                    </span>
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipment_total_price]',!empty($deliveryDetails['total_price'])?$deliveryDetails['total_price']:null, ['id'=> 'shipmentTotalPrice'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']) !!} 
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',$konitaCmsn, ['id'=> 'konitaCmsn'.$deliveryDetailsId
                                    ,'title'=> $comsnBreakdownTitle
                                    ,'class' => 'form-control text-right tooltips','readonly']) !!} 
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',$principalCmsn, ['id'=> 'principleCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']) !!} 
                                </div>
                            </td>

                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',$totalKonitaComsn, ['id'=> 'totalKonitaCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']) !!}
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',$totalPrincipleComsn, ['id'=> 'totalPrincipleCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']) !!}
                                </div>
                            </td>
                            <?php
                            $j++;
                            ?>
                            </tr>
                            @endforeach
                            <?php
                            $i++;
                            ?>
                            </tr>
                            @endforeach
                            </tr>
                            @endforeach
                            <tr>
                                <td class="vcenter text-right bold" colspan="10">@lang('label.TOTAL')</td>
                                <td class="vcenter bold" colspan="5">
                                    <span id="totalQty">0</span>
                                </td>
                                <td class="vcenter bold">$
                                    <span id="totalKonitaCmsnId">0</span>
                                    <input type="hidden" name="total_konita_cmsn" value="" id="totalKonitaCmsnInput"/>
                                </td>
                                <td class="vcenter bold">$
                                    <span id="totalPrincipleCmsnId">0</span>
                                    <input type="hidden" name="total_principle_cmsn" value="" id="totalPrincipleCmsnInput"/>
                                </td>
                            </tr>
<!--                            <tr>
                                <td class="vcenter text-right bold" colspan="12">@lang('label.GIFT')</td>
                                <td class="vcenter">
                                    {!! Form::text('gift_title',null, ['id'=> 'giftTitle','class' => 'form-control tooltips','placeholder'=>__('label.TITLE'),'title'=>__('label.TITLE')]) !!}
                                </td>
                                <td class="vcenter text-right bold" colspan="2">
                                    <div class="input-group bootstrap-touchspin">
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                        {!! Form::text('gift',null, ['id'=> 'gift', 'class' => 'form-control integer-decimal-only text-right','autocomplete'=>'off']) !!}
                                    </div>
                                </td>
                            </tr>-->
                            <tr>
                                <td class="vcenter text-right bold" colspan="15">@lang('label.NET_RECEIVABLE')</td>
                                <td class="vcenter bold" colspan="2">$
                                    <span id="netReceivableId">0</span>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="18">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @if(!empty($billingArr))
                    <div class="col-md-4 margin-top-20">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="bankId">@lang('label.BANK'): <span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('konita_bank_id',  $konitaBankList, null, ['class' => 'form-control js-source-states','id'=>'bankId']) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <!--submit button-->
                <div class="col-md-12">
                    <div class="col-md-offset-4 col-md-8">
                        @if(!empty($billingArr))
                        <button class="btn btn-circle green" href="#previewModal" type="button" data-toggle="modal" id="submitPreview">
                            <i class="fa fa-check"></i> @lang('label.SAVE_AND_PREVIEW')
                        </button>
                        <a href="{{ URL::to('billing/billingCreate') }}" class="btn btn-circle btn-outline grey-salsa">
                            <i class="fa fa-close"></i> @lang('label.CANCEL')
                        </a>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="previewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showPreviewModal">
        </div>
    </div>
</div>

<!-- Start commissionSetUpModal-->
<div class="modal fade" id="commissionSetUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowcommissionSetUpModal">
        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {

        //TOTAL QTY&KONITA CMSN & PRINCIPLE CMSN SCRIPT
        $('.sp-check').on("change", function () {
            var deliveryId = $(this).attr('data-id');
            var sum = 0;
            var totalKonitaCmsn = 0;
            var totalPrincipleCmsn = 0;
            // var gift = $('#gift').val(); //gift value
            var netReceivable = 0;

            $(".sp-check").each(function (index) {
                if ($(this).is(":checked")) {
                    sum += parseFloat($(this).attr("data-qty"));
                    totalKonitaCmsn += parseFloat($(this).attr("data-totalkonitacmsn"));
                    totalPrincipleCmsn += parseFloat($(this).attr("data-totalPrincipleCmsn"));
                }
            });

            $('#totalQty').html(sum);
            $('#totalKonitaCmsnId').html(totalKonitaCmsn);
            $('#totalPrincipleCmsnId').html(totalPrincipleCmsn);

            $('#totalKonitaCmsnInput').val(totalKonitaCmsn);
            $('#totalPrincipleCmsnInput').val(totalPrincipleCmsn);

            //total konita commission
            netReceivable = (totalKonitaCmsn - totalPrincipleCmsn);
            $('#netReceivableId').html(netReceivable.toFixed(2));

        });


//        $(document).keyup('#gift', function () {
//            var gift = $('#gift').val();
//            var totalKonitaCmsnVal = $('#totalKonitaCmsnInput').val();
//            var totalPrincipleCmsnVal = $('#totalPrincipleCmsnInput').val();
//
//            var netReceivable = 0;
//            netReceivable = (totalKonitaCmsnVal - totalPrincipleCmsnVal - gift);
//
//            $('#netReceivableId').html(netReceivable.toFixed(2));
//
//        });

        //ENDOF SUM cSCRIPT



        //buyer and Sales Person under product**
        $(document).on('change', '#supplierId', function (e) {
            $('#divHide').html('');
        });

        //preview submit form function
        $(document).on("click", "#submitPreview", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#previewForm')[0]);
            $.ajax({
                url: "{{ URL::to('billing/billingPreviewData') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showPreviewModal").html('');
                },
                success: function (res) {
                    $("#showPreviewModal").html(res.html);
                    $(".js-source-states").select2({dropdownParent: $('#showPreviewModal'), width: '100%'});
                    $(".js-source-states").select2({});
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }

                    $("#showPreviewModal").html('');
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof preview form


        //invoice save submit form function
        $(document).on("click", "#submitInvoiceSave", function (e) {

            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#invoiceSaveForm')[0]);
            $('#submitInvoiceSave').prop('disabled', true);
            $.ajax({
                url: "{{ URL::to('billing/billingInvoiceStore') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#submitInvoiceSave').prop('disabled', true);
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(
                            window.location.replace('{{ URL::to("billing/billingLedgerView")}}'
                                    ), 1000);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $('#submitInvoiceSave').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof invoce save form

        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('inquiry-id');

            $.ajax({
                url: "{{ URL::to('billing/getCommissionSetupModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                    $("#ShowcommissionSetUpModal").html('');
                },
                success: function (res) {
                    $("#ShowcommissionSetUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowcommissionSetUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //After Click to Save new commission 
        $(document).on("click", "#cmsnSaveBtn", function (e) {
            e.preventDefault();
            var formData = new FormData($('#cmsnSubmitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('billing/commissionSetupSave')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#cmsnSaveBtn').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#cmsnSaveBtn').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof commission setup modal

    });
</script>    
@stop