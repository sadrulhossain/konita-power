<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PRODUCT_WISE_TOTAL_QUANTITY')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PRODUCT_QUANTITY')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($quantitySummaryArr))
                            <?php
                            $sl = 0;
                            $totalProductQty = 0;
                            ?>
                            @foreach($quantitySummaryArr as $productId=>$item)
                            <tr>
                                <td class="vcenter text-center" rowspan="{{count($rowspanArr[$productId])}}">{{++$sl}}</td>
                                <td class="vcenter" rowspan="{{count($rowspanArr[$productId])}}">
                                    {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                                </td>
                                <?php
                                $i = 0;
                                ?>
                                @foreach($item as $val)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }

                                $totalProductQty += !empty($val['total_quantity']) ? $val['total_quantity'] : 0;
                                ?>
                                <td class="vcenter">
                                    {{!empty($val['brand_name'])?$val['brand_name']:''}}
                                </td>
                                <td class="vcenter text-center">
                                    {{!empty($val['total_quantity'])?Helper::numberFormat2Digit($val['total_quantity']):0}}&nbsp;{{!empty($val['unit_name'])?$val['unit_name']:''}}
                                </td>

                                @if($i==0)
                                <td class="vcenter text-right" rowspan="{{count($rowspanArr[$productId])}}">
                                    {{!empty($productTotalQty[$productId])?Helper::numberFormat2Digit($productTotalQty[$productId]):0}}&nbsp;{{!empty($unitArr[$productId])?$unitArr[$productId]:''}}
                                </td>
                                @endif
                            </tr>

                            <?php
                            $i++;
                            ?>
                            @endforeach
                            </tr>
                            @endforeach
                            <tr>
                                <td class="bold text-right" colspan="4">@lang('label.TOTAL')</td>
                                <td class="text-right bold">
                                    {{Helper::numberFormat2Digit($totalProductQty)}}&nbsp;@lang('label.UNIT')
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="4">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->