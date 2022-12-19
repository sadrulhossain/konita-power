@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.MARKET_ENGAGEMENT')
                <?php
                echo ' (' . Helper::formatDate($oneYearAgo) . ' - ' . Helper::formatDate($today) . ')';
                ?>
            </div>
            <div class="actions">
                <span class="text-right">
                    <?php
                    $view = Request::get('generate') == 'true' ? '&' : '?';
                    ?>
                    @if(!empty($userAccessArr[53][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().$view . 'view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[53][9]))
                    <a class="btn btn-sm btn-inline yellow btn-pdf tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl().$view . 'view=pdf') }}"  title="@lang('label.DOWNLOAD')">
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
            {!! Form::open(array('group' => 'form', 'url' => 'marketEngagement/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="country">@lang('label.COUNTRY')</label>
                        <div class="col-md-8">
                            <?php $countryArr = explode(",", Request::get('country')); ?>
                            {!! Form::select('country[]', $countryList, $countryArr, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'country', 'data-buyer-id' => Request::get('buyer_id'), 'data-product_id' => Request::get('product_id'), 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                            <span class="text-danger">{{ $errors->first('country') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER') </label>
                        <div class="col-md-8">
                            {!! Form::select('buyer_id', $buyersList, Request::get('buyer_id'), ['class' => 'form-control js-source-states', 'id' => 'buyerId', 'data-product-id' => Request::get('product_id')]) !!}
                            <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') </label>
                        <div class="col-md-8">
                            {!! Form::select('product_id', $productsList, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
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
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($productList))
                    <div class="row margin-top-20 margin-bottom-10">
                        <div class="col-md-12">
                            <span class="label label-red-haze" >
                                @lang('label.TOTAL_NUM_OF_BUYERS_FROM_WHOM_DEMAND_COLLECTED'): 
                                <strong>
                                    {!! !empty($importBuyerList)?count($importBuyerList):0 !!}
                                </strong>
                            </span>&nbsp;
                            <span class="label label-green-seagreen" >
                                @lang('label.TOTAL_NUM_OF_BUYERS_TO_WHOM_PRODUCTS_SOLD'): 
                                <strong>
                                    {!! !empty($salesBuyerList)?count($salesBuyerList):0 !!}
                                </strong>
                            </span>
                        </div>
                    </div>
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
                                        <th class="text-center vcenter">@lang('label.OUR_VOLUME')</th>
                                        <th class="text-center vcenter">@lang('label.ENGAGEMENT')</th>
                                        <th class="text-center vcenter">@lang('label.OPPORTUNITY')</th>
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

                                    $salesVolAlignment = 'text-center';
                                    $salesVol = __('label.N_A');
                                    if (!empty($salesVolumeArr[$productId])) {
                                        $salesVolAlignment = 'text-right';
                                        $salesVol = Helper::numberFormat2Digit($salesVolumeArr[$productId]) . ' ' . ($productUnitList[$productId] ?? __('label.UNIT'));
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $productName ?? '' !!}</td>
                                        <td class="{{$importVolAlignment}} vcenter">{!! $importVol !!}</td>
                                        <td class="{{$salesVolAlignment}} vcenter">{!! $salesVol !!}</td>
                                        <td class="text-right vcenter">{!! Helper::numberFormat2Digit($engagementArr[$productId]) !!}%</td>
                                        <td class="text-right vcenter">{!! Helper::numberFormat2Digit($opportunityArr[$productId]) !!}%</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="text-danger vcenter"  colspan="6">@lang('label.NO_DATA_FOUND')</td>
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
    var countryAllSelected = false;
    $('#country').multiselect({
        numberDisplayed: 0,
        includeSelectAllOption: true,
        buttonWidth: '194px',
        maxHeight: 250,
        nonSelectedText: "@lang('label.SELECT_COUNTRY')",
//        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        onSelectAll: function () {
            countryAllSelected = true;
        },
        onChange: function () {
            countryAllSelected = false;
        }
    });

//    var buyerAllSelected = false;
//    $('#buyer').multiselect({
//        numberDisplayed: 0,
//        includeSelectAllOption: true,
//        buttonWidth: '194px',
//        maxHeight: 250,
//        nonSelectedText: "@lang('label.SELECT_BUYER')",
////        enableFiltering: true,
//        enableCaseInsensitiveFiltering: true,
//        onSelectAll: function () {
//            buyerAllSelected = true;
//        },
//        onChange: function () {
//            buyerAllSelected = false;
//        }
//    });
//
//    var productAllSelected = false;
//    $('#product').multiselect({
//        numberDisplayed: 0,
//        includeSelectAllOption: true,
//        buttonWidth: '194px',
//        maxHeight: 250,
//        nonSelectedText: "@lang('label.SELECT_PRODUCT')",
////        enableFiltering: true,
//        enableCaseInsensitiveFiltering: true,
//        onSelectAll: function () {
//            productAllSelected = true;
//        },
//        onChange: function () {
//            productAllSelected = false;
//        }
//    });

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

    $(document).on("change", "#country", function () {
        var country = $(this).val();
        var buyerId = $(this).attr('data-buyer-id');
        var productId = $(this).attr('data-product-id');
        if (country == null) {
            return false;
        }
        $.ajax({
            url: '{{URL::to("marketEngagement/getBuyerProduct")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                country: country,
                buyer_id: buyerId,
                product_id: productId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#buyerId').html(res.buyer);
                $('#productId').html(res.product);
                App.unblockUI();
            }, error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }
        });
    });
    $(document).on("change", "#buyerId", function () {
        var buyerId = $(this).val();
        var productId = $(this).attr('data-product-id');
        if (buyerId == '0') {
            return false;
        }
        $.ajax({
            url: '{{URL::to("marketEngagement/getProduct")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                buyer_id: buyerId,
                product_id: productId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#productId').html(res.product);
                App.unblockUI();
            }, error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }
        });
    });

    //start :: graphical chart
    var options = {
        series: [
            {
                name: "@lang('label.OUR_VOLUME')",
                data: [
<?php
if (!empty($salesVolumeArr)) {
    foreach ($salesVolumeArr as $productId => $volume) {
        echo "'$volume', ";
    }
}
?>
                ]
            },
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
        colors: ['#1BA39C', '#F36A5A', '#8775A7', '#D05454'],
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