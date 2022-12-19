<hr/>
<div class="row">
    <div class="col-md-5">
        @lang('label.EFFECTIVE_DATE') : <strong>{!! Helper::formatDate($effectiveDate) !!}</strong>
    </div>
    <div class="col-md-5">
        @lang('label.DEADLINE') : <strong>{!! Helper::formatDate($deadline) !!}</strong>
    </div>
    <?php $disabled = ''; ?>
    @if(!empty($salesTarget) && $salesTarget->lock_status == '1')
    <?php
    $disabled = 'disabled';
    ?>
    <div class="col-md-2">
        <span class="label label-danger pull-right"><i class="fa fa-lock"></i>&nbsp;@lang('label.LOCKED')</span>
    </div>
    @endif
</div>
<br/>
<div class="row">
    <div class="table-responsive col-md-12 webkit-scrollbar">
        <table class="table table-bordered table-hover module-access-view" id="headerFix">
            <thead>
                <tr class="info">
                    <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                    <th  class="vcenter">@lang('label.PRODUCT')</th>
                    <th  class="text-center vcenter">@lang('label.QUANTITY')</th>
                    <th class="text-center vcenter">@lang('label.REMARKS')</th>
                </tr>

            </thead>
            <tbody>
                @if(!$productList->isEmpty())
                <?php $sl = 0; ?>
                @foreach($productList as $product)
                <tr>
                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                    <td class="vcenter">{!! $product->name !!}</td>
                    <td class="text-center vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            {!! Form::text('quantity['.$product->id.']', !empty($quantity[$product->id])?$quantity[$product->id]:null, ['id'=> 'quantity_'.$product->id, 'class' => 'form-control integer-decimal-only text-right text-input-width-100-per product-quantity', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix">{!! $product->measure_unit_name !!}</span>
                        </div>
                    </td>
                    <td class="text-center vcenter">
                        {{ Form::textarea('remarks['.$product->id.']', !empty($remarks[$product->id])?$remarks[$product->id]:null, ['id'=> 'remarks_'.$product->id, 'class' => 'form-control','size' => '10x2', $disabled]) }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right vcenter"><strong>@lang('label.TOTAL_QUANTITY')</strong></td>
                    <td class="vcenter text-right total-quantity"><strong>{!! !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0 !!}</strong></td>
                    {!! Form::hidden('total_quantity', !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0, ['id' => 'totalQuantity']) !!}
                    <td></td>
                </tr>
                @else
                <tr>
                    <td colspan="4" class="vcenter text-danger">@lang('label.NO_RELATED_PRODUCT_FOUND_FOR_THIS_SALES_PERSON')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $("#headerFix").tableHeadFixer();
    //total quantity
    $(".product-quantity").each(function () {
        $(this).on("keyup", function () {
            var totalQuantity = 0;
            $(".product-quantity").each(function () {
                var val = $(this).val();
                if (val == '') {
                    val = 0;
                }
                totalQuantity += parseInt(val);
            });
            //alert(totalQuantity);
            $("td.total-quantity strong").html(totalQuantity);
            $("#totalQuantity").val(totalQuantity);
        });
    });
});
</script>