<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ETS_SUMMARY_OF_DATE', ['date' => $date])
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
                                @if (!empty($etsSummaryArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($etsSummaryArr as $deliveryId => $ets)
                                <?php
                                $rowSpan['delivery'] = !empty($rowspanArr['delivery'][$ets['id']]) ? $rowspanArr['delivery'][$ets['id']] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $ets['order_no'] ?? __('label.N_A') !!}</td>
                                    @if(Auth::user()->group_id != 0)
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $ets['buyer_name'] ?? __('label.N_A') !!}</td>
                                    @endif
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $ets['supplier_name'] ?? __('label.N_A') !!}</td>
                                    @if(!empty($ets['inquiryDetails']))
                                    <?php $i = 0; ?>
                                    @foreach($ets['inquiryDetails'] as $productId=> $productData)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$ets['id']][$productId]) ? $rowspanArr['product'][$ets['id']][$productId] : 1;
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
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$ets['id']][$productId][$brandId]) ? $rowspanArr['brand'][$ets['id']][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                        {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                    </td>
                                    @if($i == 0 && $j == 0)
                                    <!--:::::::: rowspan part :::::::-->
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $ets['lc_no'] ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">
                                        {!! !empty($ets['lc_date']) ? Helper::formatDate($ets['lc_date']) : __('label.N_A') !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['delivery']}}">
                                        {!! !empty($ets['bank']) ? $ets['bank'] : __('label.N_A') !!}
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['delivery']}}">{!! $ets['sales_person_name'] ?? __('label.N_A') !!}</td>
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
