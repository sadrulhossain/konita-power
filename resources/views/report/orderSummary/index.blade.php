@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.ORDER_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!$targetArr->isEmpty())
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif 
                    @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'orderSummaryReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('pi_from_date', Request::get('pi_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('creation_from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('pi_to_date', Request::get('pi_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('creation_to_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_id', $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId">@lang('label.BRAND')</label>
                        <div class="col-md-8">
                            {!! Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row">

                <!--SUMMARY-->
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <td class="vcenter bold">@lang('label.STATUS')</td>
                                        <td class="vcenter text0center bold">@lang('label.TOTAL')&nbsp;@lang('label.PURCHASE_VOLUME')</td>
                                        <td class="vcenter text0center bold">@lang('label.TOTAL')&nbsp;@lang('label.EXPENDITURE')</td>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter">@lang('label.CONFIRMED')</td>
                                        <td class="text-right vcenter">{{!empty($purchaseSummary['volume']['confirmed']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['confirmed']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{!empty($purchaseSummary['amount']['confirmed']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['confirmed']) : 0.00 }}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter">@lang('label.IN_PROGRESS')</td>
                                        <td class="text-right vcenter">{{!empty($purchaseSummary['volume']['in_progress']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['in_progress']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{!empty($purchaseSummary['amount']['in_progress']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['in_progress']) : 0.00 }}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter">@lang('label.ACCOMPLISHED')</td>
                                        <td class="text-right vcenter">{{!empty($purchaseSummary['volume']['accomplished']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['accomplished']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{!empty($purchaseSummary['amount']['accomplished']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['accomplished']) : 0.00 }}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter">@lang('label.CANCELLED')</td>
                                        <td class="text-right vcenter">{{!empty($purchaseSummary['volume']['cancelled']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['cancelled']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{!empty($purchaseSummary['amount']['cancelled']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['cancelled']) : 0.00 }}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter bold">@lang('label.TOTAL')</td>
                                        <td class="text-right bold vcenter">{{!empty($purchaseSummary['volume']['total']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['total']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right bold vcenter">${{!empty($purchaseSummary['amount']['total']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['total']) : 0.00 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
                <!--END OF SUMMARY-->


                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter text-center">@lang('label.PI_DATE')</th>
                                    <th class="vcenter">@lang('label.LC_NO')</th>
                                    <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                                    <th class="vcenter text-center">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                    <th class="vcenter text-center">@lang('label.STATUS')</th>
                                    <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter">@lang('label.GSM')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $sl = $totalSalesVolume = $totalSalesAmount = 0;
                                ?>
                                @foreach($targetArr as $key=>$target)
                                <?php
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->order_no)?$target->order_no:'' !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->purchase_order_no)?$target->purchase_order_no:'' !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->supplier !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->pi_date) ? Helper::formatDate($target->pi_date) : '' !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->lc_no !!}</td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        {!! !empty($target->lc_date) ? Helper::formatDate($target->lc_date) : '' !!}
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->lc_transmitted_copy_done == '1')
                                        <span class="label label-sm label-info">@lang('label.YES')</span>
                                        @elseif($target->lc_transmitted_copy_done == '0')
                                        <span class="label label-sm label-warning">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                    <td class="vcenter text-center" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->order_status == '2')
                                        <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                        @elseif($target->order_status == '3')
                                        <span class="label label-sm label-purple">@lang('label.IN_PROGRESS')</span>
                                        @elseif($target->order_status == '4')
                                        <span class="label label-sm label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                                        @elseif($target->order_status == '6')
                                        <span class="label label-sm label-red-intense">@lang('label.CANCELLED')</span>
                                        @endif
                                    </td>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if(in_array($target->order_status, ['2', '3', '4']))
                                        @if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr))
                                        @foreach($deliveryArr[$target->id] as $deliveryId => $delivery)

                                        <button class="btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                                title="
                                                <div class='text-left'>
                                                @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                                @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                                @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                                @lang('label.CLICK_TO_SEE_DETAILS')
                                                </div>
                                                " 
                                                href="#modalShipmentDetails" data-id="{!! $deliveryId !!}" data-toggle="modal">
                                            <i class="fa fa-{{$delivery['icon']}}"></i>
                                        </button>
                                        @endforeach
                                        @else
                                        <button type="button" class="btn btn-xs cursor-default btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        @endif
                                        @else
                                        <button type="button" class="btn btn-xs cursor-default btn-circle grey-cascade tooltips vcenter" title="@lang('label.CANCELLED')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        @endif
                                    </td>

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
                                    <td class="vcenter">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                    <td class="vcenter text-right">{{Helper::numberFormat2Digit($item['sales_volume'])}}&nbsp;{{$item['unit_name']}}</td>
                                    <td class="vcenter text-right">${{Helper::numberFormat2Digit($item['sales_amount'])}}</td>
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
                                    <td class="vcenter bold text-right" colspan="15">@lang('label.TOTAL')</td>
                                    <td class="vcenter bold text-right">{{!empty($purchaseSummary['volume']['total']) ? Helper::numberFormat2Digit($purchaseSummary['volume']['total']) : 0.00 }}&nbsp;@lang('label.UNIT')</td>
                                    <td class="vcenter bold text-right">${{!empty($purchaseSummary['amount']['total']) ? Helper::numberFormat2Digit($purchaseSummary['amount']['total']) : 0.00 }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="17" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //table header fix
        $("#fixTable").tableHeadFixer();
        //        $('.sample').floatingScrollbar();

        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/orderSummaryReport/getShipmentDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
    });
</script>
@stop