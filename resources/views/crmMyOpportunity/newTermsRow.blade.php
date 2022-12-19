<tr class="new-terms-tr-{{$v4}}">
    <td class="text-center vcenter initial-term-sl width-50" rowspan="2"></td>
    <td colspan="8" class="label-green-soft">
        @lang('label.PRODUCT_NAME'): <span class="product-name-{{$v4}} bold"></span> | @lang('label.BRAND_NAME'): <span class="brand-name-{{$v4}} bold"></span> | @lang('label.GRADE_NAME'): <span class="grade-name-{{ $v4 }} bold"></span>
    </td>
</tr>
<tr class="new-terms-tr-{{$v4}}">
    <td class="text-center vcenter width-200">
        {!! Form::select('payment_term_id['.$v4.']', $paymentTermList, $quotationInfo->payment_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'paymentTermId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-200">
        {!! Form::select('shipping_term_id['.$v4.']', $shippingTermList, $quotationInfo->shipping_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'shippingTermId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-150">
        {!! Form::text('port_of_loading['.$v4.']', $quotationInfo->port_of_loading ?? null, ['id'=> 'portOfLoading_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit' ]) !!}
    </td>
    <td class="text-center vcenter width-150">
        {!! Form::text('port_of_discharge['.$v4.']', $quotationInfo->port_of_discharge ?? null, ['id'=> 'portOfDischarge_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit']) !!}
    </td>
    <td class="text-center vcenter width-150">
        <div class="input-group bootstrap-touchspin width-inherit">
            {!! Form::text('total_lead_time['.$v4.']', $quotationInfo->total_lead_time ?? null, ['id'=> 'totalLeadTime_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-only text-input-width-100-per']) !!}
            <span class="input-group-addon bootstrap-touchspin-postfix">@lang('label.DAY_S')</span>
        </div>
    </td>
    <td class="text-center vcenter width-200">
        {!! Form::select('pre_carrier_id['.$v4.']', $preCarrierList, $quotationInfo->pre_carrier_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'preCarrierId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-250">
        <?php
        $estimatedShipmentDate = !empty($quotationInfo->estimated_shipment_date) ? Helper::formatDate($quotationInfo->estimated_shipment_date) : null;
        ?>
        <div class="input-group date datepicker2 width-inherit">
            {!! Form::text('estimated_shipment_date['.$v4.']', $estimatedShipmentDate, ['id'=> 'estimatedShipmentDate_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="estimatedShipmentDate">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
</tr>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {

});
</script>