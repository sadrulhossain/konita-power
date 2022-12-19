<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PENDING_FOR_SHIPMENT_LIST')
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
                                    <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    @if(Auth::user()->group_id != 0)
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    @endif
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter">@lang('label.GSM')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.INQUIRY_DATE')</th>
                                    <th class="vcenter text-center">@lang('label.PI_DATE')</th>
                                    <th class="vcenter">@lang('label.LC_NO')</th>
                                    <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                    <th class="vcenter">@lang('label.LC_ISSUE_DATE')</th>
                                    <th class="vcenter">@lang('label.LSD_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.STATUS')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $key=>$target)
                                <?php
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->order_no !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->purchase_order_no !!}</td>
                                    @if(Auth::user()->group_id != 0)
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                                    @endif
                                    @if(!empty($target->inquiryDetails))
                                    <?php $i = 0; ?>
                                    @foreach($target->inquiryDetails as $productId=> $productData)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
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
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                        {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                    </td>
                                    @if(!empty($brandData))
                                    <?php $k = 0; ?>
                                    @foreach($brandData as $gradeId=> $gradeData)
                                    <?php
                                    if ($k > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="{{$rowSpan['grade']}}">
                                        {{!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''}}
                                    </td>
                                    @if(!empty($gradeData))
                                    <?php $l = 0; ?>
                                    @foreach($gradeData as $gsm=> $item)
                                    <?php
                                    if ($l > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{{!empty($gsm)?$gsm:''}}</td>
                                    <td class="vcenter text-right">{{$item['quantity']}}&nbsp;{{$item['unit_name']}}</td>

                                    @if($i == 0 && $j == 0 && $k == 0)
                                    <!--:::::::: rowspan part :::::::-->
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->creation_date) !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->pi_date)?Helper::formatDate($target->pi_date):'' !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->lc_no !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->lc_date) !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->lc_transmitted_copy_done == '1')
                                        <span class="label label-sm label-info">@lang('label.YES')</span>
                                        @elseif($target->lc_transmitted_copy_done == '0')
                                        <span class="label label-sm label-warning">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        {!! !empty($target->lc_issue_date)?Helper::formatDate($target->lc_issue_date):'' !!}
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        {!! $target->lsd !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->order_status == '2')
                                        <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                        @elseif($target->order_status == '3')
                                        <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                        @elseif($target->order_status == '4')
                                        <span class="label label-sm label-success">@lang('label.ACCOMPLISHED')</span>
                                        @endif
                                    </td>
                                    @endif
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
                                @else
                                <tr>
                                    <td colspan="20" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
