@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.NEW_MARKET_FORECAST')
                <?php
                echo ' (' . Helper::formatDate($oneYearAgo) . ' - ' . Helper::formatDate($today) . ')';
                ?>
            </div>
            <div class="actions">
                <span class="text-right">

                    @if(!empty($userAccessArr[54][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'?view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[54][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif

                    <button class="btn green-seagreen btn-sm btn-chart-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_GRAPHICAL_VIEW')">
                        <i class="fa fa-area-chart"></i>
                    </button>
                    <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_TABULAR_VIEW')">
                        <i class="fa fa-list"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($productList))
                    <div class="chart-view"></div>
                    @else
                    <div class="no-chart row margin-top-10 margin-bottom-10">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                            <div class="alert alert-danger">
                                <p>
                                    <i class="fa fa-warning"></i>
                                    @lang('label.NO_DATA_FOUND')
                                </p>
                            </div>
                        </div>

                    </div>
                    @endif
                    <div class="tabular-view">
                        <div class=" max-height-500 table-responsive tableFixHead sample webkit-scrollbar">
                            <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                        <th class="text-center vcenter">@lang('label.MARKET_VOLUME')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productList))
                                    <?php $sl = 0; ?>
                                    @foreach($productList as $productId => $productName)
                                    <?php
                                    $importVolAlignment = 'text-center';
                                    $importVol = __('label.N_A');
                                    if (!empty($importVolumeArr[$productId])) {
                                        $importVolAlignment = 'text-right';
                                        $importVol = Helper::numberFormat2Digit($importVolumeArr[$productId]) . ' ' . ($productUnitList[$productId] ?? __('label.UNIT'));
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $productName ?? '' !!}</td>
                                        <td class="{{$importVolAlignment}} vcenter">{!! $importVol !!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="text-danger vcenter" colspan="3">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>
<!-- Modal start -->
<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
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
    var options = {
        series: [

            {
                name: "@lang('label.MARKET_VOLUME')",
                data: [
<?php
if (!empty($importVolumeArr)) {
    foreach ($importVolumeArr as $productId => $volume) {
        echo "'$volume', ";
    }
}
?>
                ]
            },
        ],
        chart: {
            type: 'area',
            height: 450,
            stacked: true,
            events: {

            },
        },
        colors: ['#F36A5A', '#1BA39C', '#8775A7', '#D05454'],
        dataLabels: {
            enabled: true
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.85,
                opacityTo: .95,
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            offsetY: 10,
            offsetX: 0,
            width: undefined,
            height: 100,
        },
        xaxis: {
            type: 'category',
            title: {
                text: "@lang('label.PRODUCTS')",
                offsetX: -40,
                offsetY: 50,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
            categories: [
<?php
if (!empty($productList)) {
    foreach ($productList as $productId => $productName) {
        echo "'$productName', ";
    }
}
?>
            ],
            labels: {
                show: true,
                rotate: -75,
                rotateAlways: false,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: true,
                minHeight: undefined,
                maxHeight: 100,
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
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val) {
                        return val + " @lang('label.UNIT')"
                    }

                },
                {
                    formatter: function (val) {
                        return val + " @lang('label.UNIT')"
                    }

                },
            ]
        },

    };

    var chart = new ApexCharts(document.querySelector(".chart-view"), options);
    chart.render();
    //end :: graphical chart

    //table header fix
    $("#fixTable").tableHeadFixer();
    //        $('.sample').floatingScrollbar();

});
</script>
@stop