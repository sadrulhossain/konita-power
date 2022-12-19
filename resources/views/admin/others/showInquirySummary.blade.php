<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INQUIRY_SUMMARY_OF_DATE', ['date' => Helper::formatDate($date)])
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr >
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter">@lang('label.BUYER')</th>
                                    <th class="text-center vcenter">@lang('label.BUYER_CONTACT_PERSON')</th>
                                    <th class="text-center vcenter">@lang('label.SALES_PERSON')</th>
                                    <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                    <th class="text-center vcenter">@lang('label.BRAND')</th>
                                    <th class="text-center vcenter">@lang('label.GRADE')</th>
                                    <th class="text-center vcenter">@lang('label.GSM')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($inquiryArr))
                                <?php $sl = 0; ?>
                                @foreach($inquiryArr as $inquiryId => $inquiry)
                                <?php
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry['buyer'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry['buyer_contact_person'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry['sales_person'] !!}</td>

                                    @if(!empty($inquiry['product']))
                                    <?php $i = 0; ?>
                                    @foreach($inquiry['product'] as $productId => $product)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['product']}}">{!! $product['product_name'] !!}</td>

                                    @if(!empty($product['brand']))
                                    <?php $j = 0; ?>
                                    @foreach($product['brand'] as $brandId => $brand)
                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['brand']}}">{!! $brand['brand_name'] !!}</td>
                                    @if(!empty($brand['grade']))
                                    <?php $k = 0; ?>
                                    @foreach($brand['grade'] as $gradeId => $grade)
                                    <?php
                                    if ($k > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['grade'] = !empty($rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiryId][$productId][$brandId][$gradeId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['grade']}}">{!! $grade['grade_name'] !!}</td>
                                    @if(!empty($grade['gsm']))
                                    <?php $l = 0; ?>
                                    @foreach($grade['gsm'] as $gsm => $item)
                                    <?php
                                    if ($l > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{{ !empty($item['gsm']) ? $item['gsm'] : '' }}</td>
                                    <td class="text-right vcenter">{!! Helper::numberFormat2Digit($item['quantity']) . $item['unit'] !!}</td>
                                    <td class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($item['unit_price']) . $item['per_unit'] !!}</td>
                                    <td class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($item['total_price']) !!}</td>


                                    <?php
                                    if ($l < ($rowSpan['grade'] - 1)) {
                                        echo '</tr>';
                                    }

                                    $i++;
                                    $j++;
                                    $k++;
                                    $l++;
                                    ?>
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter" colspan="8">@lang('label.TOTAL_QUANTITY')</th>
                                    <th class="text-right vcenter">{!! Helper::numberFormat2Digit($inquirySummaryArr['total_quantity']). ' ' . __('label.UNIT') !!}</th>
                                    <th class="text-right vcenter">@lang('label.TOTAL_AMOUNT')</th>
                                    <th class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($inquirySummaryArr['total_amount']) !!}</th>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="11" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
