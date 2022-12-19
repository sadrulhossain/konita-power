<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INVOICE')
        </h3>
    </div>
    <div class="modal-body">
        {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'invoiceSaveForm')) !!}
        {{csrf_field()}}
        {!! Form::hidden('supplier_id',  $request->supplier_id) !!}
        {!! Form::hidden('konita_bank_id',  $request->konita_bank_id) !!}

        <!--BL_history data-->
        @if(!empty($targetArr))
        @foreach($targetArr as $inqueryId=>$target)
        @foreach($target as $deliveryId=>$deliveryDetails)
        @foreach($deliveryDetails as $deliveryDetailsId=>$item)
        <?php
        $totalKonitaCmsn = ((!empty($item['total_konita_cmsn']) ? $item['total_konita_cmsn'] : 0) - (!empty($item['total_principle_cmsn']) ? $item['total_principle_cmsn'] : 0));
        ?>
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($item['shipmentQty'])?$item['shipmentQty']:null) !!}
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][unit_price]',!empty($item['unit_price'])?$item['unit_price']:null) !!}
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][shipment_total_price]',!empty($item['shipment_total_price'])?$item['shipment_total_price']:null) !!} 
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:null) !!} 
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',!empty($item['principle_cmsn'])?$item['principle_cmsn']:null) !!}
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',!empty($item['total_konita_cmsn'])?$item['total_konita_cmsn']:null) !!}
        {!! Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',!empty($item['total_principle_cmsn'])?$item['total_principle_cmsn']:null) !!}

        <!--commission history-->
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($item['shipmentQty'])?$item['shipmentQty']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][company_konita_cmsn]',!empty($item['company_konita_cmsn'])?$item['company_konita_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_company_konita_cmsn]',!empty($item['total_company_konita_cmsn'])?$item['total_company_konita_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][sales_person_cmsn]',!empty($item['sales_person_cmsn'])?$item['sales_person_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_sales_person_cmsn]',!empty($item['total_sales_person_cmsn'])?$item['total_sales_person_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][buyer_cmsn]',!empty($item['buyer_cmsn'])?$item['buyer_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_buyer_cmsn]',!empty($item['total_buyer_cmsn'])?$item['total_buyer_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][rebate_cmsn]',!empty($item['rebate_cmsn'])?$item['rebate_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_rebate_cmsn]',!empty($item['total_rebate_cmsn'])?$item['total_rebate_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:0) !!} 
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',!empty($totalKonitaCmsn)?$totalKonitaCmsn:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',!empty($item['principle_cmsn'])?$item['principle_cmsn']:0) !!}
        {!! Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',!empty($item['total_principle_cmsn'])?$item['total_principle_cmsn']:0) !!}
        <!--END OF commission history-->
        @endforeach
        @endforeach
        @endforeach
        @endif
        <!--Endof_BL_history data-->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php
                                $currentDate = date('d F Y');
                                ?>
                                <div class="input-group date datepicker2">
                                    {!! Form::text('invoice_date', $currentDate, ['id'=> 'invoiceDate', 'class' => 'form-control', 'placeholder' =>'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="invoiceDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('invoice_date') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceNo">@lang('label.INVOICE_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('invoice_no',null, ['id'=> 'invoiceNo', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('invoice_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceNo">@lang('label.ATTN') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8 margin-top-10">
                                {!! Form::select('supplier_contact_person_identify',  $contactPersonList, null, ['class' => 'form-control js-source-states','id'=>'identify']) !!}

                                <span class="bold">{{!empty($supplierInfo->name)?$supplierInfo->name:''}}</span><br/>
                                <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                <span>{{!empty($supplierInfo->countryName)?$supplierInfo->countryName:''}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="subject">@lang('label.SUBJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-10 margin-top-10">
                                {!! Form::text('subject',__('label.SUBJECT_TITLE'), ['id'=> 'subject','rows'=>'3', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                            <th class="vcenter" rowspan="2">@lang('label.BUYER')</th>
                                            <th class="vcenter" rowspan="2">@lang('label.ORDER_NO')</th>
                                            <th class="vcenter text-center" rowspan="2">@lang('label.QUANTITY')</th>
                                            <!--<th class="vcenter" rowspan="2">@lang('label.COMMISSION')</th>-->
                                            <th class="vcenter text-center" rowspan="2">@lang('label.TOTAL')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($orderNoHistoryArr))
                                        <?php
                                        $sl = 0;
                                        ?>
                                        @foreach($orderNoHistoryArr as $inqueryId=>$item)
                                        <tr>
                                            <td class="vcenter">{{++$sl}}</td>  
                                            <td class="vcenter">{{$item['buyer']}}</td>  
                                            <td class="vcenter">{{$item['order_no']}}</td> 
                                            <td class="vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('order_no_history['.$inqueryId.'][qty]',!empty($item['total_shipmentQty'])?$item['total_shipmentQty']:null, ['class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                                        @lang('label.UNIT')
                                                    </span>
                                                </div>
                                                @foreach($item['shipmentQty'] as $measureUnitId=>$quantity)
                                                {!! Form::hidden('order_no_history['.$inqueryId.'][unit_wise_gty]['.$measureUnitId.']',!empty($quantity)?$quantity:null) !!}
                                                @endforeach
                                            </td>  
<!--                                            <td class="vcenter">
                                                <div class="input-group bootstrap-touchspin">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>-->
                                                    {!! Form::hidden('order_no_history['.$inqueryId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:0, ['class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
<!--                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                                        @lang('label.PER')&nbsp;@lang('label.UNIT')
                                                    </span>
                                                </div>-->
                                            <!--</td>-->  
                                            <td class="vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('order_no_history['.$inqueryId.'][total_konita_cmsn]',!empty($item['total_konita_cmsn'])?$item['total_konita_cmsn']:0, ['class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <!--sub_total-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">@lang('label.SUBTOTAL')</td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('sub_total',!empty($orderWiseTotalKonitaCmsn)?$orderWiseTotalKonitaCmsn:0, ['id'=> 'subTotal', 'class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                </div>
                                            </td>
                                        </tr>
                                        <!--admin_cost-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">@lang('label.ADMIN_COST')</td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">(-) $</span>
                                                    {!! Form::text('admin_cost',!empty($orderWiseTotalPrincipleCmsn)?$orderWiseTotalPrincipleCmsn:0, ['id'=> 'adminCost', 'class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                </div>
                                            </td>
                                        </tr>
                                        <!--net_receivable-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">@lang('label.NET_RECEIVABLE')</td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('net_receivable',!empty($netReceivable)?$netReceivable:0, ['id'=> 'netReceivable', 'class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                </div>
                                            </td>
                                        </tr>
                                        <!--gift-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">
                                                <div class="md-checkbox has-success margin-left-check">
                                                    {!! Form::checkbox('has_gift', null,false, ['id' => 'hasGift', 'class'=> 'md-check text-right has-gift-check']) !!}
                                                    <label for="hasGift">
                                                        <span class="inc text-right"></span>
                                                        <span class="check mark-caheck text-right"></span>
                                                        <span class="box mark-caheck text-right"></span>
                                                    </label>
                                                    &nbsp;&nbsp;<span>@lang('label.GIFT')</span>
                                                </div>
                                            </td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">(+) $</span>
                                                    {!! Form::text('gift', null, ['id'=> 'gift', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per', 'disabled']) !!}

                                                </div>
                                            </td>
                                        </tr>
                                        <!--total amount-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">@lang('label.TOTAL_AMOUNT')</td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('total_amount',!empty($netReceivable)?$netReceivable:0, ['id'=> 'totalAmount', 'class' => 'form-control text-right text-input-width-100-per','readonly']) !!}
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--koniat bank information-->
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="col-md-6">
                            <div>
                                <span class="bold">@lang('label.BANK_INFORMATION')</span>
                            </div>
                            <div>
                                <span class="bold">@lang('label.BANK_NAME'): </span>{{!empty($konitaBankInfo->bank_name)?$konitaBankInfo->bank_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NO'): </span>{{!empty($konitaBankInfo->account_no)?$konitaBankInfo->account_no:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NAME'): </span>{{!empty($konitaBankInfo->account_name)?$konitaBankInfo->account_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.BRANCH'): </span>{{!empty($konitaBankInfo->branch)?$konitaBankInfo->branch:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.SWIFT'): </span>{{!empty($konitaBankInfo->swift)?$konitaBankInfo->swift:''}}
                            </div>

                            <!--Signatory part-->
                            <!--                            <div class="margin-top-20">
                                                            <h5>@lang('label.REGARDS')</h5>
                                                            <span>
                                                                @if(!empty($signatoryInfo->seal))
                                                                <img src="{{URL::to('/')}}/public/img/signatoryInfo/{{$signatoryInfo->seal }}" style="width:100px; height: 100px;">
                                                                @else
                                                                <img src="{{URL::to('/')}}/public/img/no_image.png" style="width:100px; height: 100px;">
                                                                @endif
                                                            </span><br/>
                                                            <span>{{!empty($signatoryInfo->name)?$signatoryInfo->name:''}}</span><br/> 
                                                            <span>{{!empty($signatoryInfo->designation)?$signatoryInfo->designation:''}}</span>
                                                        </div>-->
                        </div>
                    </div>
                </div>
                <!--end of konita bank information-->
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <button class="btn btn-inline green" type="button" id="submitInvoiceSave" data-status="1">
                    <i class="fa fa-check"></i> @lang('label.SAVE_AND_CONFIRM')
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script>
$(function () {
    $(".has-gift-check").on("click", function () {
        if ($(this).prop('checked')) {
            $("#gift").prop('disabled', false);
        } else {
            $("#gift").prop('disabled', true);
        }
    });

    $("#gift").on("keyup", function () {
        var gift = $(this).val();
        var netReceivable = $("#netReceivable").val();
        if(gift == ''){
            gift = 0;
        }
        var totalAmount = parseFloat(netReceivable) + parseFloat(gift);
        
        $("#totalAmount").val(totalAmount.toFixed(2));
    });
});
</script>