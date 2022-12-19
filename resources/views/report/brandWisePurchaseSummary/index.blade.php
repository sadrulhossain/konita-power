@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bar-chart"></i>@lang('label.BRAND_WISE_PURCHASE_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>

                    <button class="btn green-seagreen btn-sm btn-chart-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_GRAPHICAL_VIEW')">
                        <i class="fa fa-line-chart"></i>
                    </button>
                    <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_TABULAR_VIEW')">
                        <i class="fa fa-list"></i>
                    </button>
                    @endif
                </span>
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'brandWisePurchaseSummaryReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4 tooltips" title="@lang('label.FROM_DATE') (@lang('label.PI_DATE'))"  for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date month-picker">
                                {!! Form::text('pi_from_date', Request::get('pi_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('pi_from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4 tooltips" title="@lang('label.TO_DATE') (@lang('label.PI_DATE'))" for="toDate">@lang('label.TO_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date month-picker">
                                {!! Form::text('pi_to_date', Request::get('pi_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('pi_to_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="product">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            <?php $productArr = explode(",", Request::get('product')); ?>
                            {!! Form::select('product[]', $productList, $productArr, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'product', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                            <span class="text-danger">{{ $errors->first('product') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            @if(Request::get('generate') == 'true')
            <div class="row margin-top-20">
                <div class="col-md-12 chart-view">
                    <div class="volume-chart"></div>
                </div>
                <div class="col-md-12 chart-view">
                    <div class="net-income-chart"></div>
                </div>
                <div class="col-md-12 tabular-view">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                    <div class="max-height-500 tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                                    <th class="text-center vcenter">@lang('label.PURCHASE_VOLUME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($brandList))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($brandList as $brandId => $brandName)
								@if(!empty($summaryArr))
								@if(array_key_exists($brandId, $summaryArr))
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="text-center vcenter" width="40px">
                                        @if(!empty($brandLogoList[$brandId]) && File::exists('public/uploads/brand/'. $brandLogoList[$brandId]))
                                        <img class="pictogram-min-space tooltips" width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{ $brandLogoList[$brandId] }}" alt="{{ $brandName }}" title="{{ $brandName }}"/>
                                        @else 
                                        <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{$brandName}}"/>
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $brandName !!}</td>
                                    <td class="text-right vcenter text-green">{!! (!empty($purchaseSummaryArr[$brandId]['volume']) ? Helper::numberFormat2Digit($purchaseSummaryArr[$brandId]['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</td>
                                </tr>
								@endif
								@endif
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter" colspan="3">@lang('label.TOTAL')</th>
                                    <th class="text-right vcenter text-green">{!! (!empty($purchaseSummaryArr['total']['volume']) ? Helper::numberFormat2Digit($purchaseSummaryArr['total']['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</th>
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="4">@lang('label.NO_DATA_FOUND')</td>
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

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $('.month-picker').datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
    });

    var allSelected = false;
    $('#product').multiselect({
        numberDisplayed: 0,
        includeSelectAllOption: true,
        buttonWidth: '194px',
        nonSelectedText: "@lang('label.SELECT_PRODUCT')",
//        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        onSelectAll: function () {
            allSelected = true;
        },
        onChange: function () {
            allSelected = false;
        }
    });

    //default setting
    $(".btn-chart-view").hide();
    $(".btn-tabular-view").show();
    $(".btn-print").hide();
    $(".btn-pdf").hide();
    $(".chart-view").show();
    $(".no-chart").show();
    $(".tabular-view").hide();

    //when click tabular view button
    $(document).on("click", ".btn-tabular-view", function () {
        $(".btn-chart-view").show();
        $(".btn-tabular-view").hide();
        $(".btn-print").show();
        $(".btn-pdf").show();
        $(".chart-view").hide();
        $(".no-chart").hide();
        $(".tabular-view").show();
    });

    //when click graphical view button
    $(document).on("click", ".btn-chart-view", function () {
        $(".btn-chart-view").hide();
        $(".btn-tabular-view").show();
        $(".btn-print").hide();
        $(".btn-pdf").hide();
        $(".chart-view").show();
        $(".no-chart").show();
        $(".tabular-view").hide();
    });

    //start :: graphical chart

    //start :: volume chat
    var volumeChartptions = {
        series: [
            {
                name: "@lang('label.PURCHASE_VOLUME')",
                data: [
<?php
if (!empty($brandList)) {
    foreach ($brandList as $brandId => $brandName) {
		if(!empty($summaryArr)){
			if(array_key_exists($brandId, $summaryArr)){
				$volume = $purchaseSummaryArr[$brandId]['volume'] ?? 0;
				echo "'$volume',";
			}
		}
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 300,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#1BA39C'],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            }
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.PURCHASE_VOLUME')<?php
echo ' (' . Helper::formatDate($fromDate) . ' - ' . Helper::formatDate($toDate) . ')';
?>",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($brandList)) {
    foreach ($brandList as $brandId => $brandName) {
		if(!empty($summaryArr)){
			if(array_key_exists($brandId, $summaryArr)){
				echo "'$brandName',";
			}
		}	
    }
}
?>
            ],
            title: {
                text: "@lang('label.BRANDS')",
                offsetX: -40,
                offsetY: -5,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 800,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
            labels: {
                show: true,
                rotate: -40,
                rotateAlways: false,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: true,
                minHeight: 100,
                maxHeight: undefined,
                style: {
                    colors: [],
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                },
                offsetX: 0,
                offsetY: 0,
                format: undefined,
                formatter: undefined,
                datetimeUTC: true,

            },
            axisBorder: {
                show: true,
                color: '#78909C',
                height: 1,
                width: '100%',
                offsetX: 0,
                offsetY: 0
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: '#78909C',
                height: 6,
                offsetX: 0,
                offsetY: 0
            },
            tickAmount: undefined,
            tickPlacement: 'between',
            min: undefined,
            max: undefined,
            range: undefined,
            floating: false,
            position: 'bottom',
        },
        yaxis: {
            title: {
                text: "@lang('label.VOLUME') (@lang('label.UNIT'))",
            },
            label: {
                formatter: function (val) {
                    return parseFloat(val).toFixed(2)
                }
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val) {
                        return parseFloat(val).toFixed(2) + " @lang('label.UNIT')"
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var volumeChart = new ApexCharts(document.querySelector(".volume-chart"), volumeChartptions);
    volumeChart.render();
    //end :: volume chart


    //end :: graphical chart

    //table header fix
    $("#fixTable").tableHeadFixer();
    //        $('.sample').floatingScrollbar();

});
</script>
@stop