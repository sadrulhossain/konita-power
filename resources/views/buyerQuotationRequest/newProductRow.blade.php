<tr>
    <td class="text-center vcenter new-product-sl width-50"></td>
    <td class="text-center vcenter width-240">
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="product-select-span">
                {!! Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control selected-product js-source-states product-item']) !!}
            </span>
<!--            {!! Form::text('product['.$v4.'][product_name]',null, ['id'=> 'productProductName_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-text display-none','autocomplete' => 'off']) !!} 
            <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                <button class="btn btn-sm blue-steel product-text-btn product-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                    <i class="fa fa-text-height bold"></i> 
                </button>
                <button class="btn btn-sm blue-steel product-select-btn product-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                    <i class="fa fa-angle-down bold"></i>
                </button>
            </span>
        </div>-->
        {!! Form::hidden('product['.$v4.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$v4]) !!}
    </td>
    
    <td class="text-center vcenter width-100">
        {!! Form::text('product['.$v4.'][gsm]', null, ['id'=> 'productGsm_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-gsm']) !!}
    </td>
    <td class="text-center vcenter width-100">
        {!! Form::text('product['.$v4.'][quantity]', null, ['id'=> 'productQuantity_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
    </td>
    <td class="text-center vcenter width-80">
        {!! Form::text('product['.$v4.'][unit]', null, ['id'=> 'productUnit_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-unit','readonly']) !!}
    </td>
    <td class="text-center vcenter width-50">
        <button class="btn btn-inline btn-danger remove-product-row tooltips" data-key="{{$v4}}" title="Remove" type="button">
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
});
</script>
