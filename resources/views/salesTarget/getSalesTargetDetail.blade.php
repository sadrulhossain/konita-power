<hr/>

@if(!$productList->isEmpty())
<div class="row">
    <div class="col-md-5">
        @lang('label.EFFECTIVE_DATE') : <strong>{!! Helper::formatDate($effectiveDate) !!}</strong>
    </div>
    <div class="col-md-5">
        @lang('label.DEADLINE') : <strong>{!! Helper::formatDate($deadline) !!}</strong>
    </div>
    @if(!empty($salesTarget) && $salesTarget->lock_status == '1')
    <div class="col-md-2">
        <span class="label label-danger pull-right"><i class="fa fa-lock"></i>&nbsp;@lang('label.LOCKED')</span>
    </div>
    @endif
</div>
<br/>
<div class="row">
    <div class="table-responsive col-md-12 webkit-scrollbar" style="max-height: 350px;">
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
                    <td class="text-right vcenter">
                        {!! !empty($quantity[$product->id])?$quantity[$product->id]:0 !!}&nbsp;{!! $product->measure_unit_name !!}
                    </td>
                    <td class="vcenter">
                        {!! !empty($remarks[$product->id])?$remarks[$product->id]:'' !!}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right vcenter"><strong>@lang('label.TOTAL_QUANTITY')</strong></td>
                    <td class="vcenter text-right total-quantity"><strong>{!! !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0 !!}</strong></td>
                    <td></td>
                </tr>
                @else
                <tr>
                    <td colspan="4" class="vcenter">@lang('label.NO_RELATED_PRODUCT_FOUND_FOR_THIS_SALES_PERSON')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12 text-center">
        <div class="alert alert-danger">
            <p>
                <i class="fa fa-warning"></i>
                @lang('label.SALES_TARGET_IS_NOT_SET_YET_FOR_THIS_MONTH', ['month' => $request->effective_month ]).
            </p>
        </div>
    </div>
</div>
@endif

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $("#headerFix").tableHeadFixer();
});
</script>