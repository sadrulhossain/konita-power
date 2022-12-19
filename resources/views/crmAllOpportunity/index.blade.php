@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.ALL_OPPORTUNITY_LIST')
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'crmAllOpportunity/filter','class' => 'form-horizontal')) !!}                
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
                                {!! Form::select('source_id',  $sourceList, Request::get('source_id'), ['class' => 'form-control js-source-states','id'=>'sourceId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="createdBy">@lang('label.CREATED_BY')</label>
                            <div class="col-md-8">
                                {!! Form::select('created_by',  $employeeList, Request::get('created_by'), ['class' => 'form-control js-source-states','id'=>'createdBy']) !!}
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
                            <label class="control-label col-md-4" for="assignedTo">@lang('label.ASSIGNED_TO')</label>
                            <div class="col-md-8">
                                {!! Form::select('assigned_to', $memberList, Request::get('assigned_to'), ['class' => 'form-control js-source-states','id'=>'assignedTo']) !!}
                            </div>
                        </div>
                    </div>
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
                </div>
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="padding-left-0">
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') .': ' . ($opportunityCountArr['new'] ?? 0) !!}</span>
                        </li>
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
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-soft">{!! __('label.BOOKED') .': ' . ($opportunityCountArr['booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple">{!! __('label.DISPATCHED') .': ' . ($opportunityCountArr['dispatched'] ?? 0)  !!}</span>
                        </li>
                        <!--                        <li class="list-style-item-none display-inline-block margin-top-10">
                                                    <span class="label bold label-sm label-green-seagreen">{!! __('label.APPROVED') .': ' . ($opportunityCountArr['approved'] ?? 0)  !!}</span>
                                                </li>
                                                <li class="list-style-item-none display-inline-block margin-top-10">
                                                    <span class="label bold label-sm label-red-mint">{!! __('label.DENIED') .': ' . ($opportunityCountArr['denied'] ?? 0)  !!}</span>
                                                </li>-->
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-flamingo">{!! __('label.CANCELLED') .': ' . ($opportunityCountArr['cancelled'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-cascade">{!! __('label.VOID') .': ' . ($opportunityCountArr['void'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-thunderbird">{!! __('label.REVOKED') .': ' . ($opportunityCountArr['revoked'] ?? 0)  !!}</span>
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

                        $iconActivityLog = '';
                        if (!empty($hasActivityLog)) {
                            if (in_array($target->id, $hasActivityLog)) {
                                $iconActivityLog = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips set-activity-log vcenter"'
                                        . ' href="#modalSetActivity"  data-toggle="modal" title="' . __('label.CLICK_TO_SET_ACTIVITY_LOG') . '" 
                                            data-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                            }
                        }
                        ?>
                        <tr>
                            <td class="vcenter text-center">{!! ++$sl.$iconActivityLog !!}</td>
                            <td class="vcenter">{!! $buyer ?? '' !!}</td>
                            <td class="vcenter">{!! $target->source ?? '' !!}</td>
                            <td class="vcenter text-center">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</td>
                            <td class="vcenter text-center">{!! !empty($target->updated_at) ? Helper::formatDate($target->updated_at) : '' !!}</td>
                            <td class="vcenter">{!! $target->opportunity_creator ?? '' !!}</td>
                            <td class="vcenter">{!! $assignedPersonList[$target->id] ?? '' !!}</td>
                            <td class="vcenter">{!! $target->remarks ?? '' !!}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '0')
                                <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                @elseif($target->status == '1')
                                @if($target->revoked_status == '0')
                                @if($target->last_activity_status == '0')
                                <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                @elseif($target->last_activity_status == '1')
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
                                @endif
                                @elseif($target->revoked_status == '1')
                                <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                @endif
                                @elseif($target->status == '2')
                                @if($target->dispatch_status == '0')
                                <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                @elseif($target->dispatch_status == '1')
                                @if($target->approval_status == '0')
                                <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                @elseif($target->approval_status == '1')
                                <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                @elseif($target->approval_status == '2')
                                <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                @endif
                                @endif
                                @elseif($target->status == '3')
                                <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                @elseif($target->status == '4')
                                <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($crmLeader))
                                    @if($target->status == '0' || $target->revoked_status == '1')
                                    @if(!empty($userAccessArr[74][7]))
                                    <button class="btn btn-xs green-soft tooltips vcenter assign-opportunity" title="@lang('label.CLICK_TO_ASSIGN_OPPORTUNITY')" href="#modalAssignOpportunity" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-external-link-square"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @if($target->status == '1' && $target->revoked_status == '0')
                                    @if(!empty($userAccessArr[74][24]))
                                    <button class="btn btn-xs blue-soft tooltips vcenter reassign-opportunity" title="@lang('label.CLICK_TO_REASSIGN_OPPORTUNITY')" href="#modalReassignOpportunity" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-share-square"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[74][11]))
                                    <button class="btn btn-xs red-soft tooltips vcenter revoke-assignment" title="@lang('label.CLICK_TO_REVOKE_ASSIGNMENT')" data-id="{!! $target->id !!}">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    @if(!empty($userAccessArr[74][5]))
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
                            <td colspan="8">@lang('label.NO_OPPORTUNITY_FOUND')</td>
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


<!--assign opportunity-->
<div class="modal fade" id="modalAssignOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAssignOpportunity"></div>
    </div>
</div>

<!--reassign opportunity-->
<div class="modal fade" id="modalReassignOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showReassignOpportunity"></div>
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
                url: "{{ URL::to('/crmAllOpportunity/getOpportunityDetails')}}",
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

        //opportunity reassignment modal
        $(".reassign-opportunity").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/crmAllOpportunity/getOpportunityReassigned')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId,
                },
                beforeSend: function () {
                    $("#showReassignOpportunity").html('');
                },
                success: function (res) {
                    $("#showReassignOpportunity").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //After Click to Save 
        $(document).on("click", "#saveOpportunityReassignment", function (e) {
            e.preventDefault();
            var formData = new FormData($('#opportunityReassignmentForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('crmAllOpportunity/setOpportunityReassigned')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveOpportunityReassignment').prop('disabled', true);
                            App.blockUI({
                                boxed: true,
                            });
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmAllOpportunity'.Helper::queryPageStr($qpArr)) }}";

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#saveOpportunityReassignment').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //revoke assignment
        $(document).on("click", ".revoke-assignment", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            //alert(opportunityId);return false;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "@lang('label.ARE_YOU_SURE_YOU_WANT_TO_REVOKE_ASSINMENT_OF_THIS_OPPORTUNITY')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Revoke',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('crmAllOpportunity/revoke')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            opportunity_id: opportunityId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmAllOpportunity'.Helper::queryPageStr($qpArr)) }}";
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
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
                }
            });
        });

        //opportunity assignment modal
        $(".assign-opportunity").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/crmAllOpportunity/getOpportunityToMemberToRelate')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId,
                },
                beforeSend: function () {
                    $("#showAssignOpportunity").html('');
                },
                success: function (res) {
                    $("#showAssignOpportunity").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });


        //After Click to Save new opportunity assignment 
        $(document).on("click", "#saveOpportunityAssignment", function (e) {
            e.preventDefault();
            var formData = new FormData($('#opportunityAssignmentForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('crmAllOpportunity/relateOpportunityToMember')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveOpportunityAssignment').prop('disabled', true);
                            App.blockUI({
                                boxed: true,
                            });
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "{{ URL::to('/crmAllOpportunity'.Helper::queryPageStr($qpArr)) }}";

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#saveOpportunityAssignment').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //******************** Start :: activity log ***********************
        //activity log modal
        $(".set-activity-log").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('crmAllOpportunity/getOpportunityActivityLogModal')}}",
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
        //******************** END :: activity log ***********************
    });
</script>
@stop