@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SALES_PERSON_PAYMENT_VOUCHER')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'salesPersonPaymentVoucher/filter','class' => 'form-horizontal')) !!}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="salesPersonId">@lang('label.SALES_PERSON') </label>
                            <div class="col-md-8">
                                {!! Form::select('sales_person_id',  $salesPersonList, Request::get('sales_person_id'), ['class' => 'form-control js-source-states','id'=>'salesPersonId']) !!}
                                <span class="text-danger">{{ $errors->first('sales_person_id') }}</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="approvalStatus">@lang('label.APPROVAL_STATUS') </label>
                            <div class="col-md-8">
                                {!! Form::select('approval_status',  $approvalStatusList, Request::get('approval_status'), ['class' => 'form-control js-source-states','id'=>'approvalStatus']) !!}
                                <span class="text-danger">{{ $errors->first('approval_status') }}</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-check"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover" id="fixedHeadTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter" >@lang('label.SALES_PERSON')</th>
                                    <th class="text-center vcenter" >@lang('label.DESIGNATION')</th>
                                    <th class="text-center vcenter">@lang('label.PAYMENT_DATE')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_BALANCE')</th>
                                    <th class="text-center vcenter">@lang('label.PAID_AMOUNT')</th>
                                    <th class="text-center vcenter">@lang('label.NET_DUE')</th>
                                    <th class="text-center vcenter">@lang('label.APPROVAL_STATUS')</th>
                                    <th class="text-center vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$targetArr->isEmpty())
                                <?php
                                $page = Request::get('page');
                                $page = empty($page) ? 1 : $page;
                                $sl = ($page - 1) * Session::get('paginatorCount');
                                ?>
                                @foreach($targetArr as $target)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $target->name ?? '' !!}</td>
                                    <td class="vcenter">{!! $target->designation ?? '' !!}</td>
                                    <td class="text-center vcenter">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</td>
                                    <td class="text-right vcenter">${!! !empty($target->commission_due) ? Helper::numberFormat2Digit($target->commission_due) : '0.00' !!}</td>
                                    <td class="text-right vcenter">${!! !empty($target->amount) ? Helper::numberFormat2Digit($target->amount) : '0.00' !!}</td>
                                    <td class="text-right vcenter">${!! !empty($target->net_due) ? Helper::numberFormat2Digit($target->net_due) : '0.00' !!}</td>
                                    <td class="text-center vcenter">
                                        @if($target->approval_status == '1')
                                        <span class="label label-sm label-green-seagreen">@lang('label.APPROVED')</span>
                                        @else
                                        <span class="label label-sm label-blue-steel">@lang('label.PENDING_FOR_APPROVAL')</span>
                                        @endif
                                    </td>
                                    <td class="td-actions text-center vcenter">
                                        <div class="width-inherit">
                                            <!--print-->
                                            <?php
                                            $view = Request::get('generate') == 'true' ? '&' : '?';
                                            ?>
                                            @if(!empty($userAccessArr[61][6]))
                                            <a class="btn btn-xs blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('salesPersonPaymentVoucher/voucherPrint?payment_id=' . $target->id) }}"  title="@lang('label.PRINT')">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            @endif
                                            @if($target->approval_status == '0')
                                            @if(!empty($userAccessArr[61][25]))
                                            <button class="btn btn-xs green-seagreen tooltips approve vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_APPROVE')" data-id ="{{$target->id}}">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
                                            @endif
                                            @if(!empty($userAccessArr[61][26]))
                                            <button class="btn btn-xs red-soft tooltips deny vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_DENY')" data-id ="{{$target->id}}">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9">@lang('label.NO_DATA_FOUND')</td>
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
<!-- Modal start -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInvoiceDetailsModal">
        </div>
    </div>
</div>

<!--commission details-->
<div class="modal fade" id="cmsnDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCmsnDetailsModal">
        </div>
    </div>
</div>
<!--endof commission details-->
<!-- Modal end--> 

<script type="text/javascript">
    $(document).ready(function () {
        $('#fixedHeadTable').tableHeadFixer();
        //approve payment
        $(document).on("click", ".approve", function (e) {
            e.preventDefault();
            var paymentId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to approve this payment?',
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
                        url: "{{ URL::to('salesPersonPaymentVoucher/approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            payment_id: paymentId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        
        //deny payment
        $(document).on("click", ".deny", function (e) {
            e.preventDefault();
            var paymentId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to deny this payment?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Deny',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('salesPersonPaymentVoucher/deny')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            payment_id: paymentId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                            App.unblockUI();
                        }
                    });
                }
            });
        });

//   invoice details modal script
        $(document).on("click", ".invoice-details", function () {
            var invoiceId = $(this).attr('data-id');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('billing/billingLedgerDetails') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showInvoiceDetailsModal").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            }); //ajax

        });



//   cmsn details modal script
        $(document).on("click", ".cmsn-details", function () {
            var invoiceId = $(this).attr('data-id');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('billing/commissionDetails') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showCmsnDetailsModal").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            }); //ajax

        });

    });
</script>
@stop