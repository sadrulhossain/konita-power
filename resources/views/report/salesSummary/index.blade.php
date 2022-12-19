@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bar-chart"></i>@lang('label.SALES_SUMMARY_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!empty($userAccessArr[55][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[55][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif

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
            {!! Form::open(array('group' => 'form', 'url' => 'salesSummaryReport/filter','class' => 'form-horizontal')) !!}
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
                                    <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.MONTH')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.SALES_VOLUME')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.NET_INCOME')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.GROWTH_OR_DECLINE') (@lang('label.PERCENT_FROM_PREVOIUS_MONTH'))</th>
                                </tr>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.VOLUME')</th>
                                    <th class="text-center vcenter">@lang('label.INCOME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($monthArr))
                                <?php
                                $monthArr2 = $monthArr;
                                krsort($monthArr2);
                                $lastMonth = end($monthArr2);
                                $sl = 0;
                                ?>
                                @foreach($monthArr2 as $month => $monthName)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="text-center vcenter">{!! $monthName !!}</td>
                                    <td class="text-right vcenter">{!! (!empty($salesSummaryArr[$month]['volume']) ? Helper::numberFormat2Digit($salesSummaryArr[$month]['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</td>
                                    <td class="text-right vcenter">{!! '$' . (!empty($salesSummaryArr[$month]['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr[$month]['net_income']) : Helper::numberFormat2Digit(0)) !!}</td>
                                    <td class="text-right vcenter">{!! $lastMonth != $monthName ? $salesSummaryArr[$month]['volume_deviation']. '%' : __('label.N_A') !!}</td>
                                    <td class="text-right vcenter">{!! $lastMonth != $monthName ? $salesSummaryArr[$month]['net_income_deviation']. '%' : __('label.N_A') !!}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter" colspan="2">@lang('label.TOTAL')</th>
                                    <th class="text-right vcenter">{!! (!empty($salesSummaryArr['total']['volume']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT') !!}</th>
                                    <th class="text-right vcenter">{!! '$' . (!empty($salesSummaryArr['total']['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['net_income']) : Helper::numberFormat2Digit(0)) !!}</th>
                                    <th class="text-right vcenter"></th>
                                    <th class="text-right vcenter"></th>
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="6">@lang('label.NO_DATA_FOUND')</td>
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
                name: "@lang('label.SALES_VOLUME')",
                data: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $volume = $salesSummaryArr[$month]['volume'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
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
            text: "@lang('label.SALES_VOLUME')<?php
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
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $monthName = date("M Y", strtotime($monthName));
        echo "'$monthName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.MONTHS')"
            }
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
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return parseFloat(val).toFixed(2) + " @lang('label.UNIT')"
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
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

    //start :: net income chat
    var netIncomeChartptions = {
        series: [
            {
                name: "@lang('label.NET_INCOME')",
                data: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $netIncome = $salesSummaryArr[$month]['net_income'] ?? 0;
        echo "'$netIncome',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
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
        colors: ['#8E44AD'],
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
            text: "@lang('label.NET_INCOME')<?php
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
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $monthName = date("M Y", strtotime($monthName));
        echo "'$monthName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.MONTHS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.NET_INCOME') ($)",
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
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return "$" + parseFloat(val).toFixed(2)
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
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

    var netIncomeChart = new ApexCharts(document.querySelector(".net-income-chart"), netIncomeChartptions);
    netIncomeChart.render();
    //end :: net income chart

    //end :: graphical chart


    //table header fix
    $("#fixTable").tableHeadFixer();
    //        $('.sample').floatingScrollbar();

});

function growthOrDecline(thisMonth, prevMonth) {
    var rateText = '';
    var rate = 0;
    var defaultPrevMonth = 1;

    if (thisMonth >= prevMonth) {
        if (prevMonth > 0) {
            defaultPrevMonth = prevMonth;
        }
        rate = ((thisMonth - prevMonth) * 100) / defaultPrevMonth;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-green-seagreen'>&nbsp;(+" + rate + "% form previous month)</span>";
    } else if (thisMonth < prevMonth) {
        rate = ((prevMonth - thisMonth) * 100) / prevMonth;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-danger'>&nbsp;(-" + rate + "% form previous month)</span>";
    } else {
        rateText = "";
    }

    return rateText;
}
</script>
@stop