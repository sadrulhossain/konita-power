@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.SALES_STATUS_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!$targetArr->isEmpty())
                    @if(!empty($userAccessArr[51][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[51][9]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                    @endif 
                    @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'salesStatusReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('creation_from_date', Request::get('creation_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
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
                                {!! Form::text('creation_to_date', Request::get('creation_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
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
                        <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER') </label>
                        <div class="col-md-8">
                            {!! Form::select('buyer_id', $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states', 'id' => 'buyerId']) !!}
                            <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_id', $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="salespersonsId">@lang('label.SALES_PERSON') </label>
                        <div class="col-md-8">
                            {!! Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']) !!}
                            <span class="text-danger">{{ $errors->first('salespersons_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId">@lang('label.BRAND')</label>
                        <div class="col-md-8">
                            {!! Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                                        <td class="vcenter bold">@lang('label.SALES_PARAMETER')</td>
                                        <td class="vcenter bold">@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</td>
                                        <td class="vcenter bold">@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</td>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter">@lang('label.UPCOMING')</td>
                                        <td class="text-right vcenter">{{Helper::numberFormat2Digit($upcomingSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{Helper::numberFormat2Digit($upcomingSalesAmount)}}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.PIPE_LINE')</td>
                                        <td class="text-right vcenter">{{Helper::numberFormat2Digit($pipeLineSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{Helper::numberFormat2Digit($pipeLineSalesAmount)}}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.CONFIRMED')</td>
                                        <td class="text-right vcenter">{{Helper::numberFormat2Digit($confirmedSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{Helper::numberFormat2Digit($confirmedSalesAmount)}}</td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.ACCOMPLISHED')</td>
                                        <td class="text-right vcenter">{{Helper::numberFormat2Digit($accomplishedSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                        <td class="text-right vcenter">${{Helper::numberFormat2Digit($accomplishedSalesAmount)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
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
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    <th class="vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter text-center">@lang('label.INQUIRY_DATE')</th>
                                    <th class="vcenter text-center">@lang('label.STATUS')</th>
                                    @if(!empty($userAccessArr[51][5]))
                                    <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                                    @endif
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
                                $sl = 0;
                                $totalSalesVolume = $totalSalesAmount = 0;
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
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if(!empty($supplierList[$target->supplier_id]))
                                        {!! $supplierList[$target->supplier_id] !!}
                                        @endif
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->inquiry_date) !!}</td>
                                    <td class="vcenter text-center" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->status == '1')
                                        <span class="label label-sm label-warning">@lang('label.UPCOMING')</span>
                                        @endif
                                        @if($target->order_status == '1')
                                        <span class="label label-sm label-primary">@lang('label.PIPE_LINE')</span>
                                        @elseif($target->order_status == '2' || $target->order_status == '3')
                                        <span class="label label-sm label-success">@lang('label.CONFIRMED')</span>
                                        @elseif($target->order_status == '4')
                                        <span class="label label-sm label-danger">@lang('label.ACCOMPLISHED')</span>
                                        @endif
                                    </td>
                                    @if(!empty($userAccessArr[51][5]))
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
                                        @endif
                                        @if(in_array($target->order_status, ['0', '1']))
                                        <button type="button" class="btn btn-xs cursor-default btn-circle grey-cascade tooltips vcenter" title="@lang('label.NOT_MATURED_YET')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        @endif
                                    </td>
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

                                    $totalSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                    $totalSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
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
                                    <td class="bold text-right" colspan="{{ !empty($userAccessArr[51][5]) ? 12 : 11}}">@lang('label.TOTAL')</td>
                                    <td class="bold text-right">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                    <td class="bold text-right">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="{{ !empty($userAccessArr[51][5]) ? 14 : 13}}" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
                url: "{{ URL::to('/salesStatusReport/getShipmentDetails')}}",
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