@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PENDING_INQUIRY_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'pendingInquiry/filter','class' => 'form-horizontal')) !!}
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
                                <label class="control-label col-md-4" for="approvalStatus">@lang('label.APPROVAL_STATUS')</label>
                                <div class="col-md-8">
                                    {!! Form::select('approval_status',  $approvalStatusList, Request::get('approval_status'), ['class' => 'form-control js-source-states','id'=>'approvalStatus']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="assignedTo">@lang('label.ASSIGNED_TO')</label>
                                <div class="col-md-8">
                                    {!! Form::select('assigned_to', $memberList, Request::get('assigned_to'), ['class' => 'form-control js-source-states','id'=>'assignedTo']) !!}
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
                            <th class="vcenter text-center">@lang('label.APPROVAL_STATUS')</th>
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
                                @if($target->approval_status == '1')
                                <span class="label label-sm label-green-seagreen">@lang('label.APPROVED')</span>
                                @elseif($target->approval_status == '2')
                                <span class="label label-sm label-danger">@lang('label.DENIED')</span>
                                @else
                                <span class="label label-sm label-blue-steel">@lang('label.PENDING_FOR_APPROVAL')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if($target->approval_status == '0')
                                    @if(!empty($userAccessArr[77][25]))
                                    <button class="btn btn-xs green-seagreen tooltips approve-opportunity vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_APPROVE')" data-id ="{{$target->id}}">
                                        <i class="fa fa-check-circle"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @if($target->approval_status == '0')
                                    @if(!empty($userAccessArr[77][26]))
                                    <button class="btn btn-xs red-soft tooltips deny-opportunity vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_DENY')" data-id ="{{$target->id}}" href="#modalShowRemarks">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @if(!empty($userAccessArr[77][5]))
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
                            <td colspan="10">@lang('label.NO_PENDING_INQUIRY_FOUND')</td>
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


<!--opportunity details-->
<div class="modal fade" id="modalShowRemarks" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityRemarks"></div>
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
        //START:: Ajax for Approve Opportunity

        $(document).on("click", ".approve-opportunity", function (e) {
            e.preventDefault();
            var oppotunityId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to approve this Opportunity?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('pendingInquiry/approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            opportunity_id: oppotunityId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(
                                    window.location.replace('{{ route("pendingInquiry.index")}}'),
                                    3000);
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
        //END:: Ajax for Approve Opportunity


        //START:: Ajax for Deny Opportunity
        $(document).on("click", ".deny-opportunity", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "{{ URL::to('pendingInquiry/showRemarksModal')}}",
                type: 'POST',
                dataType: 'json',
                data: {
                    opportunity_id: opportunityId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showOpportunityRemarks").html('');
                },
                success: function (res) {
                    $("#showOpportunityRemarks").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });
        });
        //END:: Ajax for Deny Opportunity

        //START:: Ajax for Opportunity details modal
        $(".opportunity-details").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/pendingInquiry/getOpportunityDetails')}}",
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


        //START:: Ajax Submit form for Deny Opportunity Remarks
        $(document).on("click", "#submitDenyOpportunity", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#denyOpportunityForm')[0]);
            swal({
                title: 'Are you sure you want to deny this Opportunity?',
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
                        url: "{{ URL::to('pendingInquiry/deny') }}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#submitDenyOpportunity').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('{{ route("pendingInquiry.index")}}'),
                                    3000);
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
                            $('#submitDenyOpportunity').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });
        //endof pending order cancellation script

        //******************** Start :: activity log ***********************
        //activity log modal
        $(".set-activity-log").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('pendingInquiry/getOpportunityActivityLogModal')}}",
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