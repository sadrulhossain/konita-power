@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CANCELLED_OPPORTUNITY_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'crmCancelledOpportunity/filter','class' => 'form-horizontal')) !!}
                <div class="row">
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
                                <label class="control-label col-md-4" for="product">@lang('label.PRODUCT')</label>
                                <div class="col-md-8">
                                    {!! Form::select('product', $productArr, Request::get('product'), ['class' => 'form-control js-source-states','id'=>'product']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="brand">@lang('label.BRAND')</label>
                                <div class="col-md-8">
                                    {!! Form::select('brand', $brandArr, Request::get('brand'), ['class' => 'form-control js-source-states','id'=>'brand']) !!}
                                </div>
                            </div>
                        </div>
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
                    </div>
                    <div class="col-md-12">
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
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
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
                            <th class="vcenter text-center">@lang('label.CANCELLED_REMARKS')</th>
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
                            <td class="vcenter">{!! $target->cancelled_remarks ?? '' !!}</td>

                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[75][21]))
                                    <button class="btn btn-xs red-haze tooltips vcenter reactivate-opportunity" title="@lang('label.CLICK_TO_REACTIVATE')" data-id="{!! $target->id !!}">
                                        <i class="fa fa-power-off"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[75][5]))
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
                            <td colspan="10">@lang('label.NO_CANCELLED_OPPORTUNITY_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- START:: Opportunity Details Modal -->
<div class="modal fade" id="modalOpportunityDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityDetails"></div>
    </div>
</div>
<!-- END:: Opportunity Details Modal -->


<!-- opportunity set activity log -->
<div class="modal fade" id="modalSetActivity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showActivityLog"></div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //START:: Ajax for Reactivate Cancelled Opportunity
        $(document).on("click", ".reactivate-opportunity", function (e) {
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
                title: 'Are you sure you want to reactivate this Opportunity?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Reactivate',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('crmCancelledOpportunity/reactivate')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            opportunity_id: opportunityId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('.reactivate-opportunity').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
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
                            $('.reactivate-opportunity').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //END:: Ajax for Reactivate Cancelled Opportunity

        //START:: Ajax for Opportunity details modal
        $(".opportunity-details").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/crmCancelledOpportunity/getOpportunityDetails')}}",
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
        //END:: Ajax for Opportunity details modal

        //******************** Start :: activity log ***********************
        //activity log modal
        $(".set-activity-log").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('crmCancelledOpportunity/getOpportunityActivityLogModal')}}",
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