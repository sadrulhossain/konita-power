<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[58][6]))
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('buyerSummaryReport/printInvolvedOrderList?buyer_id='.$request->buyer_id.'&sales_person_id='.$request->sales_person_id.'&type_id='.$request->type_id) }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
        </a>
        @endif
        <h4 class="modal-title text-center bold">
            @if(!empty($request->sales_person_id) && $request->sales_person_id != 0)
            @lang('label.INVOLVED_ORDER_LIST')
            @else
            @lang('label.ORDER_LIST_WITH_TYPE', ['type' => $typeList[$request->type_id]])
            @endif
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.BUYER'): <strong>{!! $buyerInfo->name ?? __('label.N_A') !!}</strong>
            </div>
            @if(!empty($request->sales_person_id) && $request->sales_person_id != 0)
            <div class="col-md-6">
                @lang('label.SALES_PERSON') (@lang('label.ACTIVELY_ENGAGED')): <strong>{!! !empty($salesPersonInfo->name) ? $salesPersonInfo->name. (!empty($salesPersonInfo->designation) ? ' (' . $salesPersonInfo->designation . ')' : '') : __('label.N_A') !!}</strong>
            </div>
            @endif
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover  table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.ORDER_NO')</th>
                                <th class="text-center vcenter">@lang('label.PO_NO')</th>
                                @if(empty($request->sales_person_id) || $request->sales_person_id == 0)
                                <th class="text-center vcenter">@lang('label.SALES_PERSON')</th>
                                @endif
                                <th class="text-center vcenter">@lang('label.SUPPLIER')</th>
                                <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                <th class="text-center vcenter">@lang('label.BRAND')</th>
                                <th class="text-center vcenter">@lang('label.GRADE')</th>
                                <th class="text-center vcenter">@lang('label.GSM')</th>
                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                <th class="text-center vcenter">@lang('label.INQUIRY_DATE')</th>
                                <th class="text-center vcenter">@lang('label.PI_DATE')</th>
                                <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                                <th class="text-center vcenter">@lang('label.LC_NO')</th>
                                <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                <th class="text-center vcenter">@lang('label.LC_ISSUE_DATE')</th>
                                <th class="text-center vcenter">@lang('label.LSD_DATE')</th>
                                <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                                <th class="text-center vcenter">@lang('label.STATUS')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$inquiryInfoArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($inquiryInfoArr as $inquiry)
                            <?php
                            //inquiry rowspan
                            $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$inquiry->id]) ? $rowspanArr['inquiry'][$inquiry->id] : 1;
                            ?>
                            <tr>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry->order_no ?? '' !!}</td>
                                <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                                @if(empty($request->sales_person_id) || $request->sales_person_id == 0)
                                <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry->sales_person ?? '' !!}</td>
                                @endif
                                <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry->supplier ?? '' !!}</td>
                                @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                                <?php $i = 0 ?>
                                @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['product'] = !empty($rowspanArr['product'][$inquiry->id][$productId]) ? $rowspanArr['product'][$inquiry->id][$productId] : 1;
                                ?>
                                <td class="vcenter" rowspan="{{ $rowSpan['product'] }}">
                                    {!! $product['product_name'] ?? '' !!}
                                </td>

                                @if(!empty($product['brand']))
                                <?php $j = 0 ?>
                                @foreach($product['brand'] as $brandId => $brand)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['brand'] = !empty($rowspanArr['brand'][$inquiry->id][$productId][$brandId]) ? $rowspanArr['brand'][$inquiry->id][$productId][$brandId] : 1;
                                ?>
                                <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                    {!! $brand['brand_name'] ?? '' !!}
                                </td>

                                @if(!empty($brand['grade']))
                                <?php $k = 0 ?>
                                @foreach($brand['grade'] as $gradeId => $grade)
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['grade'] = !empty($rowspanArr['grade'][$inquiry->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$inquiry->id][$productId][$brandId][$gradeId] : 1;
                                ?>
                                <td class="vcenter" rowspan="{{$rowSpan['grade']}}">{!! $grade['grade_name'] ?? '' !!}</td>
                                @if(!empty($grade['gsm']))
                                <?php $l = 0 ?>
                                @foreach($grade['gsm'] as $gsm => $item)
                                <?php
                                if ($l > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="text-right vcenter">{!! $item['gsm'] ?? '' !!}</td>
                                <td class="text-right vcenter">{!! $item['quantity'] ?? '' !!}</td>
                                <td class="text-right vcenter">{!! $item['unit_price'] ?? '' !!}</td>
                                <td class="text-right vcenter">{!! $item['total_price'] ?? '' !!}</td>

                                @if($i == 0 && $j == 0 && $k == 0)
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $inquiry->lc_no ?? '' !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($inquiry->lc_transmitted_copy_done == '1')
                                    <span class="label label-sm label-blue-steel">@lang('label.YES')</span>
                                    @else
                                    <span class="label label-sm label-red-flamingo">@lang('label.NO')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                    @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                                    @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)

                                    <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                            title="
                                            <div class='text-left'>
                                            @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                            @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                            @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                            </div>
                                            " 
                                            >
                                        <i class="fa fa-{{$delivery['icon']}}"></i>
                                    </button>
                                    @endforeach
                                    @else
                                    <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    @endif
                                </td>
                                <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($inquiry->status == '1')
                                    <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                                    @elseif($inquiry->status == '2')
                                    @if($inquiry->order_status == '1')
                                    <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                                    @elseif($inquiry->order_status == '2')
                                    <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                                    @elseif($inquiry->order_status == '3')
                                    <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                                    @elseif($inquiry->order_status == '4')
                                    <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                                    @elseif($inquiry->order_status == '5')
                                    <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                                    @elseif($inquiry->order_status == '6')
                                    <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                                    @endif
                                    @elseif($inquiry->status == '3')
                                    <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                                    @endif
                                </td>
                                @endif

                                <?php
                            if($l < ($rowSpan['grade']-1)){
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
                                <td class="vcenter text-danger" colspan="{!! (empty($request->sales_person_id) || $request->sales_person_id == 0) ? 21 : 20 !!}">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

});
</script>