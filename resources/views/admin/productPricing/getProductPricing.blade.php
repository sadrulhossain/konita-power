@if(!empty($targetArr))
<div class="col-md-12 margin-bottom-10 text-right">
    <!--print-->
    <a class="btn btn-sm blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('dashboard/productPricingPrintpdf?view=print&product_id=' . $request->product_id) }}"  title="@lang('label.PRINT')">
        <i class="fa fa-print"></i>
    </a>
    <!--pdf-->
    <a class="btn btn-sm blue-sharp tooltips vcenter" target="_blank" href="{{ URL::to('dashboard/productPricingPrintpdf?view=pdf&product_id=' . $request->product_id) }}"  title="@lang('label.DOWNLOAD')">
        <i class="fa fa-download"></i>
    </a>
</div>
@endif
<div class="table-responsive col-md-12 webkit-scrollbar" style="max-height: 600px;">
    <table class="table table-bordered table-hover table-head-fixer-color">
        <thead>
            <tr  class="info">
                <th  class="vcenter" width="">@lang('label.BRAND')</th>
                <th  class="vcenter">@lang('label.GRADE')</th>
                @if($authorised->authorised_for_realization_price == '1')
                <th  class="text-center vcenter">@lang('label.REALIZATION_PRICE')</th>
                @endif
                <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')</th>
                <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')</th>
                <th  class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                <th  class="text-center vcenter">@lang('label.REMARKS')</th>
                @if($authorised->authorised_for_realization_price == '1')
                <th  class="text-center vcenter">@lang('label.SPECIAL_NOTE')</th>
                @endif
            </tr>
        </thead>
        <tbody class="access-check">
            @if(!empty($targetArr))
            <?php
            $product_id = null;
            ?>
            @foreach($targetArr as $productId=>$productData)
            <?php
            if ($productId != $product_id) {
                $product_id = $productId;
                $productName = !empty($productArr[$product_id]) ? $productArr[$product_id] : '';
                echo '<tr class="bg-grey-steel">
                                    <th colspan="' . ($authorised->authorised_for_realization_price == '1' ? 8 : 6) . '" '
                . 'class="text-center">' . $productName . '</th>
                                </tr>';
            }
            ?>
            @foreach($productData as $brandId=>$brandData)
            <?php
            $rowSpan = !empty($rowspanArr[$productId][$brandId]) ? count($rowspanArr[$productId][$brandId]) : 0;
            $i = 0;
            ?>
            <tr>
                <td class="vcenter text-left" rowspan="{{$rowSpan}}">
                    {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                </td>
                @foreach($brandData as $gradeId=>$target)
                <?php
                if ($i > 0) {
                    echo '<tr>';
                }
                ?>
                <td class="vcenter text-left">{{$target['grade_name']}}</td>
                @if($authorised->authorised_for_realization_price == '1')
                <td class="vcenter text-right">
                    ${{$target['realization_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                </td>
                @endif
                <td class="vcenter text-right">
                    ${{$target['minimum_selling_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                </td>
                <td class="vcenter text-right">
                    ${{$target['target_selling_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                </td>
                <td class="vcenter text-center">
                    {{Helper::formatDate($target['effective_date'])}}
                </td>
                <td class="vcenter text-left">
                    {{!empty($target['remarks']) ? $target['remarks'] : __('label.N_A') }}
                </td>
                @if($authorised->authorised_for_realization_price == '1')
                <td class="vcenter text-left">
                    {{!empty($target['special_note']) ? $target['special_note'] : __('label.N_A')}}
                </td>
                @endif
            </tr>
            <?php
            $i++;
            ?>
            @endforeach
            </tr>
            @endforeach
            @endforeach
            @else
            <tr>
                <td colspan="{{$authorised->authorised_for_realization_price == '1' ? 8 : 6}}">@lang('label.NO_DATA_FOUND')</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
