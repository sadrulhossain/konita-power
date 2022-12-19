<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_PRODUCT_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-6">
                @lang('label.SALES_PERSON'): <strong>{!! $salesPerson->name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                                <th class=" text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($productArr))
                            @php $sl = 0 @endphp
                            @foreach($productArr as $product)
                            <?php 
                            $rowspan = !empty($brandRelatedToSalesPerson[$product['id']]) ? count($brandRelatedToSalesPerson[$product['id']]) : 1; 
                            $productStatusColor = 'green-seagreen';
                            $productStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveProductArr) && in_array($productId, $inactiveProductArr)) {
                                $productStatusColor = 'red-soft';
                                $productStatusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">
                                    {!! $product['name'] ?? '' !!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$productStatusColor}} tooltips" title="{{ $productStatusTitle }}">
                                    </button>
                                </td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">{!! $product['product_category_name'] ?? '' !!}</td>
                                @if(!empty($brandRelatedToSalesPerson[$product['id']]))
                                <?php $i = 0; ?>
                                @foreach($brandRelatedToSalesPerson[$product['id']] as $relatedBrandId)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                $brandStatusColor = 'green-seagreen';
                                $brandStatusTitle = __('label.ACTIVE');
                                if (!empty($inactiveBrandrr) && in_array($relatedBrandId, $inactiveBrandrr)) {
                                    $brandStatusColor = 'red-soft';
                                    $brandStatusTitle = __('label.INACTIVE');
                                }
                                ?>

                                <td class="text-center vcenter">
                                    @if(!empty($brandInfo[$relatedBrandId]['logo']))
                                    <img class="pictogram-min-space" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brandInfo[$relatedBrandId]['logo'] }}" alt="{{ $brandInfo[$relatedBrandId]['name'] }}"/>
                                    @else 
                                    <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $brandInfo[$relatedBrandId]['name'] }}"/>
                                    @endif
                                </td>
                                <td class="vcenter">
                                    {!! $brandInfo[$relatedBrandId]['name'] ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$brandStatusColor}} tooltips" title="{{ $brandStatusTitle }}">
                                    </button>
                                </td>
                                <?php
                                if ($i > 0) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @else
                                <td colspan="3">
                                    <span class="text-danger">@lang('label.NO_BRAND_FOUND_RELATED_TO_THIS_PRODUCT'). </span>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_RELATED_PRODUCT_FOUND_FOR_THIS_SALES_PERSON')
                                </td>
                            </tr>
                            @endif      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $(".relation-view-2").tableHeadFixer();
});
</script>
