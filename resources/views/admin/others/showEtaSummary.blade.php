<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ETA_SUMMARY_OF_DATE', ['date' => $date])
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    @if(Auth::user()->group_id != 0)
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    @endif
                                    <th class="vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.LC_NO')</th>
                                    <th class="vcenter">@lang('label.LC_DATE')</th>
                                    <th class="vcenter">@lang('label.BANK')</th>
                                    <th class="vcenter">@lang('label.SALES_PERSON')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($etaSummaryArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($etaSummaryArr as $deliveryId => $eta)
                                <?php
                                $rowSpan['delivery'] = !empty($rowspanArr['delivery'][$eta['id']]) ? $rowspanArr['delivery'][$eta['id']] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $eta['order_no'] ?? __('label.N_A') !!}</td>
                                    @if(Auth::user()->group_id != 0)
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $eta['buyer_name'] ?? __('label.N_A') !!}</td>
                                    @endif
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $eta['supplier_name'] ?? __('label.N_A') !!}</td>
                                    
                                    @if(!empty($eta['inquiryDetails']))
                                    <?php $i = 0; ?>
                                    @foreach($eta['inquiryDetails'] as $productId=> $productData)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$eta['id']][$productId]) ? $rowspanArr['product'][$eta['id']][$productId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['product']}}">
                                        {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                                    </td>
                                    @if(!empty($productData))
                                    <?php $j = 0; ?>
                                    @foreach($productData as $brandId=> $brandData)
                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$eta['id']][$productId][$brandId]) ? $rowspanArr['brand'][$eta['id']][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                        {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                    </td>
                                    @if($i == 0 && $j == 0)
                                    <!--:::::::: rowspan part :::::::-->
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $eta['lc_no'] ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">
                                        {!! !empty($eta['lc_date']) ? Helper::formatDate($eta['lc_date']) : __('label.N_A') !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">
                                        {!! !empty($eta['bank']) ? $eta['bank'] : __('label.N_A') !!}
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $eta['sales_person_name'] ?? __('label.N_A') !!}</td>
                                    @endif

                                    <?php
                                    $i++;
                                    $j++;
                                    ?>
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
