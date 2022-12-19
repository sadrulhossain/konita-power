@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.MY_OPPORTUNITY_LIST')
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'crmMyOpportunity/filter','class' => 'form-horizontal')) !!}
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyer">@lang('label.BUYER')</label>
                            <div class="col-md-8">
                                {!! Form::select('buyer',  $buyerArr, Request::get('buyer'), ['class' => 'form-control js-source-states','id'=>'buyer']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="sourceId">@lang('label.SOURCE')</label>
                            <div class="col-md-8">
                                {!! Form::select('source_id', $sourceList, Request::get('source_id'), ['class' => 'form-control js-source-states','id'=>'sourceId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="createdBy">@lang('label.CREATED_BY')</label>
                            <div class="col-md-8">
                                {!! Form::select('created_by', $employeeList, Request::get('created_by'), ['class' => 'form-control js-source-states','id'=>'createdBy']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                            <div class="col-md-8">
                                {!! Form::select('status', $statusList, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="product">@lang('label.PRODUCT')</label>
                            <div class="col-md-8">
                                {!! Form::select('product', $productArr, Request::get('product'), ['class' => 'form-control js-source-states','id'=>'product']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="brand">@lang('label.BRAND')</label>
                            <div class="col-md-8">
                                {!! Form::select('brand', $brandArr, Request::get('brand'), ['class' => 'form-control js-source-states','id'=>'brand']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE')</label>
                            <div class="col-md-8">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('update_from_date', Request::get('update_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="fromDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE')</label>
                            <div class="col-md-8">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('update_to_date', Request::get('update_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="toDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                </div>


                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="padding-left-0">
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-steel">{!! __('label.IN_PROGRESS') .': ' . ($opportunityCountArr['in_progress'] ?? 0) !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-soft">{!! $activityStatusList['1'] .': ' . ($opportunityCountArr['dead'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-chambray">{!! $activityStatusList['2'] .': ' . ($opportunityCountArr['unreachable'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-hoki">{!! $activityStatusList['3'] .': ' . ($opportunityCountArr['answering_machine'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-soft">{!! $activityStatusList['4'] .': ' . ($opportunityCountArr['sdc'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-steel">{!! $activityStatusList['5'] .': ' . ($opportunityCountArr['reached'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm  label-yellow-mint">{!! $activityStatusList['6'] .': ' . ($opportunityCountArr['not_interested'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-pink">{!! $activityStatusList['8'] .': ' . ($opportunityCountArr['not_booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple-sharp">{!! $activityStatusList['9'] .': ' . ($opportunityCountArr['halt'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-sharp">{!! $activityStatusList['10'] .': ' . ($opportunityCountArr['prospective'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-mint">{!! $activityStatusList['11'] .': ' . ($opportunityCountArr['none'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-yellow-casablanca">{!! $activityStatusList['12'] .': ' . ($opportunityCountArr['irrelevant'] ?? 0)  !!}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-responsive margin-top-10">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter text-center">@lang('label.BUYER')</th>
                            <th class="vcenter text-center">@lang('label.SOURCE')</th>
                            <th class="vcenter text-center">@lang('label.DATE_OF_CREATION')</th>
                            <th class="vcenter text-center">@lang('label.LAST_UPDATED')</th>
                            <th class="vcenter text-center">@lang('label.CREATED_BY')</th>
                            <th class="vcenter text-center">@lang('label.ASSIGNED_TO')</th>
                            <th class="vcenter text-center">@lang('label.REMARKS')</th>
                            <th class="vcenter text-center">@lang('label.STATUS')</th>
                            <th class="vcenter text-center">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <?php
                        if ($target->buyer_has_id == '0') {
                            $buyer = $target->buyer;
                        } elseif ($target->buyer_has_id == '1') {
                            $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                        }
                        ?>
                        <tr>
                            <td class="vcenter text-center">{{ ++$sl }}</td>
                            <td class="vcenter">{!! $buyer ?? '' !!}</td>
                            <td class="vcenter">{!! $target->source ?? '' !!}</td>
                            <td class="vcenter text-center">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</td>
                            <td class="vcenter text-center">{!! !empty($target->updated_at) ? Helper::formatDate($target->updated_at) : '' !!}</td>
                            <td class="vcenter">{!! $target->opportunity_creator ?? '' !!}</td>
                            <td class="vcenter">{!! $assignedPersonList[$target->id] ?? '' !!}</td>
                            <td class="vcenter">{!! $target->remarks ?? '' !!}</td>
                            <td class="text-center vcenter">
                                @if($target->last_activity_status == '1')
                                <span class="label label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '2')
                                <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '3')
                                <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '4')
                                <span class="label label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '5')
                                <span class="label label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '6')
                                <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '7')
                                <span class="label label-sm label-green-seagreen">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '8')
                                <span class="label label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '9')
                                <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '10')
                                <span class="label label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '11')
                                <span class="label label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @elseif($target->last_activity_status == '12')
                                <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                @else
                                <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[71][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('crmMyOpportunity/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[71][13]))
                                    <button class="btn btn-xs red-intense opportunity-cancel tooltips vcenter" href="#modalOpportunityCancellation" data-id="{!! $target->id !!}" data-toggle="modal" title="@lang('label.CLICK_TO_CANCEL_OPPORTUNITY')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[71][15]))
                                    <button class="btn btn-xs grey-cascade tooltips vcenter opportunity-void" title="@lang('label.CLICK_TO_VOID_OPPORTUNITY')" href="#modalOpportunityVoid" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[71][17]))
                                    <button class="btn btn-xs label-yellow-casablanca tooltips vcenter set-activity-log" title="@lang('label.CLICK_TO_SET_ACTIVITY_LOG')" href="#modalSetActivity" data-id="{!! $target->id !!}" data-history-status="0" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    @endif
                                    <!--quotation-->
                                    @if(!empty($userAccessArr[71][23]))
                                    <a class="btn btn-xs grey-mint tooltips vcenter" href="{{ URL::to('crmMyOpportunity/quotation/' . $target->id . Helper::queryPageStr($qpArr)) }}" data-placement="top" data-rel="tooltip" title="@lang('label.SET_QUOTATION')">
                                        <i class="fa fa-calculator"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[71][5]))
                                    <button class="btn btn-xs yellow tooltips vcenter opportunity-details" title="@lang('label.CLICK_TO_VIEW_OPPORTUNITY_DETAILS')" href="#modalOpportunityDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_OPPORTUNITY_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- Modal start -->

<!--opportunity details-->
<div class="modal fade" id="modalOpportunityDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityDetails"></div>
    </div>
</div>

<!--opportunity cancellation-->
<div class="modal fade" id="modalOpportunityCancellation" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityCancellation"></div>
    </div>
</div>

<!--opportunity void-->
<div class="modal fade" id="modalOpportunityVoid" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityVoid"></div>
    </div>
</div>

<!-- opportunity set activity log -->
<div class="modal fade" id="modalSetActivity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showActivityLog"></div>
    </div>
</div>

<!-- Modal end -->

<script type="text/javascript">
    $(function () {
        //opportunity details modal
        $(".opportunity-details").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/crmMyOpportunity/getOpportunityDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showOpportunityDetails").html('');
                },
                success: function (res) {
                    $("#showOpportunityDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //******************** Start :: cancel opportunity **********************
        $(".opportunity-cancel").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            $.ajax({
                url: "{{ URL::to('crmMyOpportunity/opportunityCancellationModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showOpportunityCancellation").html('');
                },
                success: function (res) {
                    $("#showOpportunityCancellation").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        $(document).on('click', "#cancelOpportunity", function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "This opportunity will be cancelled!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Cancel it",
                cancelButtonText: "No, Don't cancel it",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#opportunityCancelForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/crmMyOpportunity/cancel')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $("#cancelOpportunity").prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmMyOpportunity'.Helper::queryPageStr($qpArr)) }}";
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
                            $("#cancelOpportunity").prop('disabled', false);
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });
        //******************** End :: cancel opportunity ***********************

        //******************** Start :: void opportunity **********************
        $(".opportunity-void").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            $.ajax({
                url: "{{ URL::to('crmMyOpportunity/opportunityVoidModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showOpportunityVoid").html('');
                },
                success: function (res) {
                    $("#showOpportunityVoid").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        $(document).on('click', "#voidOpportunity", function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "This opportunity will be void!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Void it",
                cancelButtonText: "No, Don't void it",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#opportunityVoidForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/crmMyOpportunity/void')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $("#voidOpportunity").prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmMyOpportunity'.Helper::queryPageStr($qpArr)) }}";
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
                            $("#voidOpportunity").prop('disabled', false);
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });
        //******************** End :: void opportunity ***********************

        //******************** Start :: activity log ***********************
        //activity log modal
        $(".set-activity-log").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('crmMyOpportunity/getOpportunityActivityLogModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId,
                    history_status: historyStatus
                },
                beforeSend: function () {
                    $("#showActivityLog").html('');
                    $("#contactForm").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showActivityLog").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showActivityLog'),width: '100%'});
                    $('.form_datetime').datetimepicker({
                        autoclose: true,
                        todayHighlight: true,
                    });
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //save activity log
        $(document).on('click', "#saveActivityLog", function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "You want to Set Activity Log!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Save",
                cancelButtonText: "No, Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    var formData = new FormData($("#submitActivityForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/crmMyOpportunity/saveActivityModal')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $("#saveActivityLog").prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmMyOpportunity'.Helper::queryPageStr($qpArr)) }}";
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
                            $("#saveActivityLog").prop('disabled', false);
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });

        $(document).on('click', '.primary-contact', function () {
            var key = $(this).attr('data-key');
            if ($(this).prop('checked')) {
                $('.primary-contact').prop('checked', false);
                $('#contactPrimary_' + key).prop('checked', true);
            }
        });

        //add new contact row
        $(document).on("click", ".add-new-contact-row", function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('crmMyOpportunity/newContactRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#newContactTbody").html('');
                },
                success: function (res) {
                    $("#newContactTbody").prepend(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('contact');
                },
            });
        });
        //remove contact row
        $(document).on('click', '.remove-contact-row', function () {
            $(this).parent().parent().remove();
            rearrangeSL('contact');
            return false;
        });

        // START:: Hide Div After Click Close 
        $(document).on('click', '.close-btn', function () {
            $("#contactForm").html('');
        });



        //START:: Ajax for Allow User for CRM  
        $(document).on("click", '.add-contact', function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-opportunity-id");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('crmMyOpportunity/getActivityContactPersonData')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    opportunity_id: opportunityId,
                },
                beforeSend: function () {
                    $("#contactForm").html('');
                },
                success: function (res) {
                    $("#contactForm").html(res.html);
                },
            });
        });
        //END:: Ajax for Allow User for CRM

        $(document).on('click', "#saveContactData", function (e) {
            var opportunityId = $(this).data('opportunity-id');
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            var formData = new FormData($("#contactFormData")[0]);
            formData.append('contact_type', 0);
            $.ajax({
                url: "{{ URL::to('/crmMyOpportunity/saveActivityContactPersonData')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function () {
                    $('#saveContactData').prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    $('#saveContactData').prop('disabled', false);
                    App.unblockUI();
                    $("#contactForm").html('');
                    $("#contactPersonKey").html(res.contactView);
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
                    $('#saveContactData').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });
        //******************** End :: activity log *************************
    });
</script>
@stop