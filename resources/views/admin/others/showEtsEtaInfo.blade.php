<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @if($request->ref == '1')
            @lang('label.ESTIMATED_TIME_OF_SHIPMENT')
            @elseif($request->ref == '2')
            @lang('label.ESTIMATED_TIME_OF_ARRIVAL')
            @endif
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
                                    <th class="text-center vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="text-center vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    @if(Auth::user()->group_id != 0)
                                    <th class="text-center vcenter">@lang('label.BUYER')</th>
                                    @endif
                                    <th class="text-center vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.LC_NO')</th>
                                    <th class="vcenter">@lang('label.LC_DATE')</th>
                                    <th class="vcenter">@lang('label.BANK')</th>
                                    @if($request->ref == '1')
                                    <th class="text-center vcenter">@lang('label.ETS_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.ETS_NOTIFICATION_DATE')</th>
                                    @elseif($request->ref == '2')
                                    <th class="text-center vcenter">@lang('label.ETA_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.ETA_NOTIFICATION_DATE')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($targetArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $inquiryId=>$target)
                                <?php
                                $rowspan = !empty($rowspanArr['inquiry'][$inquiryId]) ? $rowspanArr['inquiry'][$inquiryId] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['order_no'] !!}</td>
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['purchase_order_no'] !!}</td>
                                    @if(Auth::user()->group_id != 0)
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['buyer_name'] !!}</td>
                                    @endif
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['supplier_name'] !!}</td>
                                    @if(!empty($target['inquiryDetails']))
                                    <?php $i = 0; ?>
                                    @foreach($target['inquiryDetails'] as $productId=> $productData)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$inquiryId][$productId]) ? $rowspanArr['product'][$inquiryId][$productId] : 1;
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
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$inquiryId][$productId][$brandId]) ? $rowspanArr['brand'][$inquiryId][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                        {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                    </td>
                                    @if($i == 0 && $j == 0)
                                    <!--:::::::: rowspan part :::::::-->
                                    <td class="vcenter" rowspan="{{$rowspan}}">{!! $target['lc_no'] ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowspan}}">
                                        {!! !empty($target['lc_date']) ? Helper::formatDate($target['lc_date']) : __('label.N_A') !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowspan}}">
                                        {!! !empty($target['bank']) ? $target['bank'] : __('label.N_A') !!}
                                    </td>
                                        @if(!empty($blNoArr[$inquiryId]))
                                        @foreach($blNoArr[$inquiryId] as $blNO)

                                        @if($request->ref == '1')
                                        <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! $blNO['ets'] ?? __('label.N_A') !!}</td>
                                        <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! $blNO['ets_notification'] ?? __('label.N_A') !!}</td>
                                        @elseif($request->ref == '2')
                                        <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! $blNO['eta'] ?? __('label.N_A') !!}</td>
                                        <td class="text-center vcenter" rowspan="{{$rowspan}}">{!! $blNO['eta_notification'] ?? __('label.N_A') !!}</td>
                                        @endif
                                        @endforeach
                                        @endif
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
                                    <td colspan="12" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
