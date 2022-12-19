<?php
$v4 = 'np' . uniqid();
?>
<tr>
    <td class="text-center vcenter new-product-sl width-50"></td>
    <td class="text-center vcenter width-240">
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="product-select-span-{{$v4}}">
                {!! Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-select js-source-states product-item']) !!}
            </span>
<!--            {!! Form::text('product['.$v4.'][product_name]',null, ['id'=> 'productProductName_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-text display-none','autocomplete' => 'off']) !!} 
            <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                <button class="btn btn-sm blue-steel product-text-btn product-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                    <i class="fa fa-text-height bold"></i> 
                </button>
                <button class="btn btn-sm blue-steel product-select-btn product-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                    <i class="fa fa-angle-down bold"></i>
                </button>
            </span>-->
        </div>
        {!! Form::hidden('product['.$v4.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-240">
        <div class="input-group bootstrap-touchspin width-inherit" id="productWiseBrandId_{{$v4}}">
            <span class="brand-select-span-{{$v4}}">
                {!! Form::select('product['.$v4.'][brand_id]', $brandList, null, ['id'=> 'productBrandId_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-select js-source-states brand-item']) !!}
            </span>
<!--            {!! Form::text('product['.$v4.'][brand_name]',null, ['id'=> 'productBrandName_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-text display-none','autocomplete' => 'off']) !!} 
            <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                <button class="btn btn-sm blue-steel brand-text-btn brand-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                    <i class="fa fa-text-height bold"></i> 
                </button>
                <button class="btn btn-sm blue-steel brand-select-btn brand-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                    <i class="fa fa-angle-down bold"></i>
                </button>
            </span>-->
        </div>
        {!! Form::hidden('product['.$v4.'][brand_has_id]', '1', ['class' => 'brand-has-id', 'id' => 'brandHasId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-240">
        <div class="input-group bootstrap-touchspin width-inherit" id="brandWiseGrade_{{$v4}}">
            <span class="grade-select-span-{{$v4}}">
                {!! Form::select('product['.$v4.'][grade_id]', $gradeList, null, ['id'=> 'productGradeId_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-select js-source-states']) !!}
            </span>
<!--            {!! Form::text('product['.$v4.'][grade_name]',null, ['id'=> 'productGradeName_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-text display-none','autocomplete' => 'off']) !!} 
            <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                <button class="btn btn-sm blue-steel grade-text-btn grade-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                    <i class="fa fa-text-height bold"></i> 
                </button>
                <button class="btn btn-sm blue-steel grade-select-btn grade-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                    <i class="fa fa-angle-down bold"></i>
                </button>
            </span>-->
        </div>
        {!! Form::hidden('product['.$v4.'][grade_has_id]', '1', ['class' => 'grade-has-id', 'id' => 'gradeHasId_'.$v4]) !!}
    </td>
    <td class="text-center vcenter width-240">
        <div class="input-group bootstrap-touchspin width-inherit">
            <span id="brandWiseOrigin_{{$v4}}">
                <!--{!! Form::select('product['.$v4.'][origin]', $countryList, null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4, 'class' => 'form-control js-source-states']) !!}-->
            </span>
        </div>
    </td>
    {!! Form::hidden('product['.$v4.'][origin]', null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4]) !!}
    <td class="text-center vcenter width-100">
        {!! Form::text('product['.$v4.'][gsm]', null, ['id'=> 'productGsm_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-gsm']) !!}
    </td>
    <td class="text-center vcenter width-100">
        {!! Form::text('product['.$v4.'][quantity]', null, ['id'=> 'productQuantity_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
    </td>
    <td class="text-center vcenter width-80">
        {!! Form::text('product['.$v4.'][unit]', null, ['id'=> 'productUnit_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-unit','readonly']) !!}
    </td>
    <td class="text-center vcenter width-180">
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
            {!! Form::text('product['.$v4.'][unit_price]', null, ['id'=> 'productUnitPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-unit-price']) !!}
            <span class="input-group-addon bootstrap-touchspin-prefix bold product-per-unit-{{$v4}}"></span>
        </div>
    </td>
    <td class="text-center vcenter width-150">
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
            {!! Form::text('product['.$v4.'][total_price]', null, ['id'=> 'productTotalPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
        </div>
    </td>
    <td class="text-center vcenter width-50">
        <button class="btn btn-inline btn-danger remove-product-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </td>
</tr>


<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    //product input
    $('.product-text-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('.product-select-span-' + key).addClass('display-none');
        $('.product-select-btn-' + key).removeClass('display-none');
        $('#productProductName_' + key).removeClass('display-none');
        $('#productHasId_' + key).val('0');
    });
    $('.product-select-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('#productProductName_' + key).addClass('display-none');
        $('.product-text-btn-' + key).removeClass('display-none');
        $('.product-select-span-' + key).removeClass('display-none');
        $('#productHasId_' + key).val('1');
    });

    //brand input
    $('.brand-text-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('.brand-select-span-' + key).addClass('display-none');
        $('.brand-select-btn-' + key).removeClass('display-none');
        $('#productBrandName_' + key).removeClass('display-none');
        $('#brandHasId_' + key).val('0');
    });
    $('.brand-select-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('#productBrandName_' + key).addClass('display-none');
        $('.brand-text-btn-' + key).removeClass('display-none');
        $('.brand-select-span-' + key).removeClass('display-none');
        $('#brandHasId_' + key).val('1');
    });

    //grade input
    $('.grade-text-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('.grade-select-span-' + key).addClass('display-none');
        $('.grade-select-btn-' + key).removeClass('display-none');
        $('#productGradeName_' + key).removeClass('display-none');
        $('#gradeHasId_' + key).val('0');
    });
    $('.grade-select-btn').on('click', function () {
        var key = $(this).attr('data-key');
        $(this).addClass('display-none');
        $('#productGradeName_' + key).addClass('display-none');
        $('.grade-text-btn-' + key).removeClass('display-none');
        $('.grade-select-span-' + key).removeClass('display-none');
        $('#gradeHasId_' + key).val('1');
    });
});
</script>
