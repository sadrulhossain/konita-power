@extends('layouts.default.master')

@section('data_count')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}

</div>
@endif


<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb margin-top-10">
            <li>
                <a href="{{url('dashboard')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Dashboard</span>
            </li>
        </ul>
        <div class="page-toolbar margin-top-15">
            <h5 class="dashboard-date font-blue-madison"><span class="icon-calendar"></span> Today is <span class="font-blue-madison">{!! date('d F Y') !!}</span> </h5>   
        </div>
    </div>
    <div class="row margin-top-10">
        <!--**** PENDING FOR LC-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat yellow-gold tooltips" href="#pendingLcModal" id="pendingLcId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PENDING_FOR_LC')">
                <div class="visual">
                    <i class="fa fa-hourglass-start"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$pendingForLc}}">
                            {{$pendingForLc}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PENDING_FOR_LC')</div>
                </div>
            </a>
        </div>
        <!--**** END OF PENDING FOR LC--> 

        <!--*** START PENDING FOR SHIPMENT-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat purple-studio tooltips" href="#pendingShipmentModal" id="pendingShipmentId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PENDING_FOR_SHIPMENT')">
                <div class="visual">
                    <i class="fa fa-ship"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$pendingForShipment}}">
                            {{$pendingForShipment}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PENDING_FOR_SHIPMENT')</div>
                </div>
            </a>
        </div>
        <!--*** END OF PENDING FOR SHIPMENT-->

        <!--*** PARTIALLY SHIPPED-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat blue-madison tooltips" href="#partiallyShippedModal" id="partiallyShippedId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PARTIALLY_SHIPPED')">
                <div class="visual">
                    <i class="fa fa-ship"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <!--<i class="fa fa-dollar"></i>-->
                        <span class="bold" data-counter="counterup" data-value="{{$partiallyShippedCount}}">
                            {{$partiallyShippedCount}}
                        </span>
                    </div>
                    <div class="desc">@lang('label.PARTIALLY_SHIPPED')</div>
                </div>
            </a>
        </div>
        <!--*** END OF PARTIALLY SHIPPED-->
        <!--*** PARTIALLY SHIPPED-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat-v2  dashboard-stat green-steel tooltips" href="#productCatalogModal" id="productCatalogId" data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_PARTIALLY_SHIPPED')">
                <div class="visual">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <i class="fa fa-cubes"></i>
                    </div>
                    <div class="desc">@lang('label.PRODUCT_CATALOG')</div>
                </div>
            </a>
        </div>
        <!--*** END OF PARTIALLY SHIPPED-->
    </div>
    <div class="row">
        <!--******** START NEXT 15 DAYS ETS SUMMARY ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NEXT_15_DAYS_ETS_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="next15DaysEtsSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF NEXT 15 DAYS ETS SUMMARY ************-->

        <!--******** START NEXT 15 DAYS ETA SUMMARY ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NEXT_15_DAYS_ETA_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="next15DaysEtaSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  NEXT 15 DAYS ETA SUMMARY ************-->
        <!--******** START ORDER SUMMARY LAST 6 MONTHS ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.ORDER_SUMMARY_LAST_6_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last6MonthsOrderSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  ORDER SUMMARY LAST 6 MONTHS ************-->
        <!--******** START SHIPMENT SUMMARY LAST 6 MONTHS ************-->
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.SHIPMENT_SUMMARY_LAST_6_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last6MonthsShipmentSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  SHIPMENT SUMMARY LAST 6 MONTHS ************-->
        <!--******** START IMPORT SUMMARY LAST 6 MONTHS ************-->
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.IMPORT_SUMMARY_LAST_6_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last6MonthsImportSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  IMPORT SUMMARY LAST 6 MONTHS ************-->
        <!--******** START BRAND WISE IMPORT SUMMARY LAST 6 MONTHS ************-->
        <div class="col-md-12 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.BRAND_WISE_IMPORT_SUMMARY_LAST_6_MONTHS')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last6MonthsBrandImportSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <!--******** END OF  BRAND WISE IMPORT SUMMARY LAST 6 MONTHS ************-->
    </div>
</div>
<!--***************MODAL******************-->
<!--product pricing modal-->
<div class="modal fade" id="productCatalogModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showProductCatalog">
        </div>
    </div>
</div>

<!--Pending for LC modal-->
<div class="modal fade" id="pendingLcModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingLcViewModal">
        </div>
    </div>
</div>

<!--Pending for Shipment modal-->
<div class="modal fade" id="pendingShipmentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="pendingShipmentViewModal">
        </div>
    </div>
</div>

<!--Partially Shipped modal-->
<div class="modal fade" id="partiallyShippedModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="partiallyShippedViewModal">
        </div>
    </div>
</div>


<!--Ets Summary modal-->
<div class="modal fade" id="etsSummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtsSummary">
        </div>
    </div>
</div>
<!--Eta Summary modal-->
<div class="modal fade" id="etaSummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showEtaSummary">
        </div>
    </div>
</div>

<!--inquiry Summary modal-->
<div class="modal fade" id="inquirySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showInquirySummary">
        </div>
    </div>
</div>

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
//product pricing MODAL
$(document).on("click", "#productCatalogId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getProductCatalog')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#showProductCatalog").html('');
        },
        success: function (res) {
        $("#showProductCatalog").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END PRODUCT PRICING MODAL

//PENDING FOR LC  Details MODAL
$(document).on("click", "#pendingLcId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/pendingForLc')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#pendingLcViewModal").html('');
        },
        success: function (res) {
        $("#pendingLcViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PENDING FOR LC DETAILS MODAL

//PENDING FOR Shipment Details MODAL
$(document).on("click", "#pendingShipmentId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/pendingForShipment')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#pendingShipmentViewModal").html('');
        },
        success: function (res) {
        $("#pendingShipmentViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PENDING FOR SHIPMENT DETAILS MODAL

//PARTIALLY SHIPPED Details MODAL
$(document).on("click", "#partiallyShippedId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/getPartiallyShipped')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#partiallyShippedViewModal").html('');
        },
        success: function (res) {
        $("#partiallyShippedViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END of PARTIALLY SHIPPED DETAILS MODAL
//******************* NEXT 15 DAYS ETS SUMMARY ******************

var next15DaysEtsSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
        $.ajax({
        url: "{{ URL::to('dashboard/getEtsSummary')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                date_index: dateIndex,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#etsSummaryModal").modal("show");
                $("#showEtsSummary").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
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
                //                        App.unblockUI();
                }
        }); //ajax
        },
        },
},
        series: [{
        name: "@lang('label.NO_OF_SHIPMENT')",
                data: [
<?php
if (!empty($next15DaysEtsSummaryArr)) {
    foreach ($next15DaysEtsSummaryArr as $item) {
        ?>
                        "{{$item}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($next15DaysEtsSummaryArr)) {
    foreach ($next15DaysEtsSummaryArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_SHIPMENT')"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var next15DaysEtsSummary = new ApexCharts(document.querySelector("#next15DaysEtsSummary"), next15DaysEtsSummaryOptions);
next15DaysEtsSummary.render();
//****************** END OF NEXT 15 DAYS ETS SUMMARY ************

//*************** NEXT 15 DAYS ETA SUMMARY ******************
var next15DaysEtaSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        },
        events: {
        click:function(event, chartContext, config) {
        var dateIndex = config.dataPointIndex;
        $.ajax({
        url: "{{ URL::to('dashboard/getEtaSummary')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                date_index: dateIndex,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#etaSummaryModal").modal("show");
                $("#showEtaSummary").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
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
                //                        App.unblockUI();
                }
        }); //ajax
        },
        },
},
        series: [{
        name: "@lang('label.NO_OF_SHIPMENT')",
                data: [
<?php
if (!empty($next15DaysEtaSummaryArr)) {
    foreach ($next15DaysEtaSummaryArr as $item) {
        ?>
                        "{{$item}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: 'Date',
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                },
                categories: [
<?php
if (!empty($next15DaysEtaSummaryArr)) {
    foreach ($next15DaysEtaSummaryArr as $date => $item) {
        $date = date("d M Y", strtotime($date));
        echo "'$date', ";
    }
}
?>

                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_SHIPMENT')"
        },
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var next15DaysEtaSummary = new ApexCharts(document.querySelector("#next15DaysEtaSummary"), next15DaysEtaSummaryOptions);
next15DaysEtaSummary.render();
//****************** END OF NEXT 15 DAYS ETA SUMMARY ************

//*************** LAST 6 MONTHS ORDER SUMMARY ******************
var last6MonthsOrderSummaryOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#4C87B9', '#8E44AD', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#4C87B9', '#8E44AD', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "@lang('label.CONFIRMED')",
                data: [
<?php
if (!empty($last6MonthsOrderArr)) {
    foreach ($last6MonthsOrderArr as $month => $noOfOrder) {
        echo $noOfOrder['confirmed'] . ', ';
    }
}
?>
                ]
        },
        {
        name: "@lang('label.IN_PROGRESS')",
                data: [
<?php
if (!empty($last6MonthsOrderArr)) {
    foreach ($last6MonthsOrderArr as $month => $noOfOrder) {
        echo $noOfOrder['in_progress'] . ', ';
    }
}
?>
                ]
        },
        {
        name: "@lang('label.ACCOMPLISHED')",
                data: [
<?php
if (!empty($last6MonthsOrderArr)) {
    foreach ($last6MonthsOrderArr as $month => $noOfOrder) {
        echo $noOfOrder['accomplished'] . ', ';
    }
}
?>
                ]
        },
        {
        name: "@lang('label.CANCELLED')",
                data: [
<?php
if (!empty($last6MonthsOrderArr)) {
    foreach ($last6MonthsOrderArr as $month => $noOfOrder) {
        echo $noOfOrder['cancelled'] . ', ';
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($last6MonthsOrderArr)) {
    foreach ($last6MonthsOrderArr as $month => $noOfOrder) {
        echo '"' . $month . '", ';
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
        text: "@lang('label.NO_OF_ORDER')"
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        },
        tooltip: {
        y: [
        {
        formatter: function (val) {
        return val
        }
        },
        {
        formatter: function (val) {
        return val
        }
        },
        {
        formatter: function (val) {
        return val
        }
        },
        {
        formatter: function (val) {
        return val
        }
        },
        ]
        },
};
var last6MonthsOrderSummary = new ApexCharts(document.querySelector("#last6MonthsOrderSummary"), last6MonthsOrderSummaryOptions);
last6MonthsOrderSummary.render();
//****************** END OF LAST 6 MONTHS ORDER SUMMARY ************


//*************** LAST 6 MONTHS SHIPMENT SUMMARY ******************
var last6MonthsShipmentSummaryOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#8E44AD', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#8E44AD', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "@lang('label.NO_OF_SHIPMENT')",
                data: [
<?php
if (!empty($last6MonthsShipmentSummaryArr)) {
    foreach ($last6MonthsShipmentSummaryArr as $month => $noOfShipment) {
        echo $noOfShipment . ', ';
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($last6MonthsShipmentSummaryArr)) {
    foreach ($last6MonthsShipmentSummaryArr as $month => $noOfShipment) {
        echo '"' . $month . '", ';
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
        text: "@lang('label.NO_OF_SHIPMENT')"
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        },
        tooltip: {
        y: [
        {
        formatter: function (val) {
        return val
        }
        },
        ]
        },
};
var last6MonthsShipmentSummary = new ApexCharts(document.querySelector("#last6MonthsShipmentSummary"), last6MonthsShipmentSummaryOptions);
last6MonthsShipmentSummary.render();
//****************** END OF LAST 6 MONTHS SHIPMENT SUMMARY ************


//*************** LAST 6 MONTHS IMPORT SUMMARY ******************
var last6MonthsImportSummaryOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#1BA39C', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1BA39C', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "@lang('label.VOLUME')",
                data: [
<?php
if (!empty($last6MonthsImportSummaryArr)) {
    foreach ($last6MonthsImportSummaryArr as $month => $volume) {
        echo $volume . ', ';
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($last6MonthsImportSummaryArr)) {
    foreach ($last6MonthsImportSummaryArr as $month => $volume) {
        echo '"' . $month . '", ';
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
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
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
};
var last6MonthsImportSummary = new ApexCharts(document.querySelector("#last6MonthsImportSummary"), last6MonthsImportSummaryOptions);
last6MonthsImportSummary.render();
//****************** END OF LAST 6 MONTHS IMPORT SUMMARY ************


//*************** LAST 6 MONTHS BRAND WISE IMPORT SUMMARY ******************
var last6MonthsBrandImportSummaryOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: ['#1BA39C', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#1BA39C', '#4C87B9', '#1BA39C', '#E43A45', '#C49F47', '#E08283', '#E35B5A', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C', '#E08283', '#F2784B', '#369EAD', '#C24642', '#E35B5A', '#5E738B', '#E08283', '#9A12B3', '#E87E04', '#D91E18', '#555555', '#C49F47'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
        {
        name: "@lang('label.VOLUME')",
                data: [
<?php
if (!empty($last6MonthsBrandImportList)) {
    foreach ($last6MonthsBrandImportList as $brandId => $brandName) {
		if(!empty($last6MonthsBrandImportArr)){
			if(array_key_exists($brandId,$last6MonthsBrandImportArr)){
				$volume = !empty($last6MonthsBrandImportArr[$brandId]) ? $last6MonthsBrandImportArr[$brandId] : 0;
				echo $volume . ', ';	
			}
		}	
    }
}
?>
                ]
        },
        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: false,
                showDuplicates: true,
                trim: false,
                minHeight: 160,
                maxHeight: 250,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($last6MonthsBrandImportList)) {
    foreach ($last6MonthsBrandImportList as $brandId => $brandName) {
        if(!empty($last6MonthsBrandImportArr)){
			if(array_key_exists($brandId,$last6MonthsBrandImportArr)){
				echo '"' . $brandName . '", ';
			}
		}
        
    }
}
?>
                ],
                title: {
                text: "@lang('label.BRANDS')"
                }
        },
        yaxis: {
        title: {
        text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
        },
                labels: {
                show: true,
                        formatter: function(val) {
                        return parseFloat(val).toFixed(2)
                        },
                },
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        },
        tooltip: {
        y:
        {
        formatter: function (val) {
        return parseFloat(val).toFixed(2) + " @lang('label.UNIT')"
        }
        },
        },
};
var last6MonthsBrandImportSummary = new ApexCharts(document.querySelector("#last6MonthsBrandImportSummary"), last6MonthsBrandImportSummaryOptions);
last6MonthsBrandImportSummary.render();
//****************** END OF LAST 6 MONTHS BRAND WISE IMPORT SUMMARY ************



});
function trimString(str) {
var returnStr = str;
if (typeof (str) != 'undefined'){
var dot = '';
if (str.length > 20) {
dot = '...';
}

var returnStr = str.substring(0, 20) + dot;
}
return returnStr;
}
</script>
@endsection