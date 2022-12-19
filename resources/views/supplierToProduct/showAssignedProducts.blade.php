<div class="row relation-div-box-default">
    <div class="col-md-12 border-bottom-1-green-seagreen">
        <h5>
            <strong>@lang('label.ASSIGNED_PRODUCT_S') : {!! !empty($assignedProductList) ? count($assignedProductList) : 0 !!}</strong>
            @if(!empty($assignedProductList) && empty($dependentProductArr[$request->supplier_id]))
            <a class="btn btn-xs bold btn-circle red-intense pull-right remove-all-assignments tooltips " title="@lang('label.CLICK_TO_REMOVE_ALL')" 
               data-selected-product-id="{{ $request->product_id }}" 
               data-supplier-id="{{ $request->supplier_id }}" st>
                <i class="fa fa-times-circle"></i>&nbsp;@lang('label.REMOVE_ALL')
            </a>
            @endif
        </h5>
    </div>
    <div class="col-md-12">
        <div class="table-responsive webkit-scrollbar" style="max-height: 100px;">
            <table class="table">
                <tbody>
                    @if(!empty($assignedProductList))
                    <?php $sl = 0; ?>
                    @foreach($assignedProductList as $productId => $name)
                    <?php
                    $statusColor = 'green-seagreen';
                    $statusTitle = __('label.ACTIVE');
                    if (!empty($inactiveProductArr) && in_array($productId, $inactiveProductArr)) {
                        $statusColor = 'red-soft';
                        $statusTitle = __('label.INACTIVE');
                    }
                    ?>

                    <tr>
                        <td>{{ ++$sl.'.' }}</td>
                        <td>
                            {{ $name ?? '' }}
                            <button type="button" class="btn btn-xs padding-5 cursor-default btn-circle {{$statusColor}} tooltips" title="{{ $statusTitle }}">
                            </button>
                        </td>
                        <td>
                            @if(!empty($dependentProductArr[$request->supplier_id]) && in_array($productId, $dependentProductArr[$request->supplier_id]))
                            <span class="label label-sm label-purple-sharp tooltips vcenter" title="@lang('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES')">
                                <i class="fa fa-info-circle"></i>
                            </span>
                            @else
                            <a class="btn btn-xs red-intense remove-product tooltips vcenter" title="@lang('label.REMOVE')" 
                               data-assigned-product-id="{{ $productId }}" data-selected-product-id="{{ $request->product_id }}" 
                               data-supplier-id="{{ $request->supplier_id }}">
                                <i class="fa fa-times"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10" class="text-danger">@lang('label.NO_PRODUCT_IS_RELATED_TO_THIS_SALES_PERSON')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();
    });
</script>