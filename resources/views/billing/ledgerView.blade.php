@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.INVOICE')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'billing/filter','class' => 'form-horizontal')) !!}
            @csrf 
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="invoiceNO">@lang('label.INVOICE_NO') </label>
                        <div class="col-md-8">
                            {!! Form::text('invoice_no', Request::get('invoice_no'), ['class' => 'form-control','id'=>'invoiceNO']) !!}
                            <span class="text-danger">{{ $errors->first('invoice_no') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']) !!}
                            <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                        </div>
                    </div>
                </div> 


            </div>
            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="approvalStatus">@lang('label.APPROVAL_STATUS') </label>
                        <div class="col-md-8">
                            {!! Form::select('approval_status',  $approvalStatusList, Request::get('approval_status'), ['class' => 'form-control js-source-states','id'=>'approvalStatus']) !!}
                            <span class="text-danger">{{ $errors->first('approval_status') }}</span>
                        </div>
                    </div>
                </div>     
                <div class="col-md-4 text-center">
                    <div class="form-group">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-check"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover" id="fixedHeadTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter" >@lang('label.INVOICE_NO')</th>
                                    <th class="text-center vcenter" >@lang('label.DATE')</th>
                                    <th class="text-center vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="text-center vcenter">@lang('label.KONITA_BANK')</th>
                                    <th class="text-center vcenter">@lang('label.APPROVAL_STATUS')</th>
                                    <th class="text-center vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$targetArr->isEmpty())
                                <?php
                                $i = 0;
                                ?>
                                @foreach($targetArr as $target)
                                <tr>
                                    <td class="text-center vcenter">{{++$i}}</td>
                                    <td class="vcenter">{{$target->invoice_no}}</td>
                                    <td class="text-center vcenter">{{Helper::formatDate($target->date)}}</td>
                                    <td class="vcenter">{{$target->supplierName}}</td>
                                    <td class="vcenter">{{$target->bank_name}}</td>
                                    <td class="text-center vcenter">
                                        @if($target->approval_status == '1')
                                        <span class="label label-sm label-green-seagreen">@lang('label.APPROVED')</span>
                                        @else
                                        <span class="label label-sm label-blue-steel">@lang('label.PENDING_FOR_APPROVAL')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @if($target->approval_status == '0')
                                            @if(!empty($userAccessArr[41][25]))
                                            <button class="btn btn-xs green-seagreen tooltips approve vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_APPROVE')" data-id ="{{$target->id}}">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
                                            @endif
                                            @if(!empty($userAccessArr[41][26]))
                                            <button class="btn btn-xs red-soft tooltips deny vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_DENY')" data-id ="{{$target->id}}">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            @endif
                                            @endif
                                            <!--view details-->
                                            @if(!empty($userAccessArr[41][5]))
                                            <button class="btn btn-xs yellow invoice-details tooltips vcenter" href="#invoiceDetailsModal" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.INVOICE_DETAILS')">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-xs blue full-invoice-details tooltips vcenter" href="#fullInvoiceDetailsModal" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.FULL_INVOICE_DETAILS')">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            @endif
                                            <!--print-->
                                            @if(!empty($userAccessArr[41][6]))
                                            <a class="btn btn-xs blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('billing/billingLedgerPrint?view=print&invoice_id=' . $target->id) }}"  title="@lang('label.PRINT')">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            @endif
                                            <!--pdf-->
                                            @if(!empty($userAccessArr[41][9]))
                                            <a class="btn btn-xs blue-sharp tooltips vcenter" target="_blank" href="{{ URL::to('billing/billingLedgerPdf?view=pdf&invoice_id=' . $target->id) }}"  title="@lang('label.DOWNLOAD')">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            @endif

                                            <!--Commission Details-->
                                            @if(!empty($userAccessArr[41][5]))
                                            <button class="btn btn-xs yellow-gold  cmsn-details tooltips vcenter" href="#cmsnDetailsModal" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.COMMISSION_DETAILS')">
                                                <i class="fa fa-sitemap"></i>
                                            </button>
                                            @endif
                                            <!--Delete-->
                                            @if(!array_key_exists($target->id, $alreadyReceivedInvoiceList))
                                            @if(!empty($userAccessArr[41][4]))
                                            {{ Form::open(array('url' => 'billing/ledger/' . $target->id . '?url=' .urlencode(Helper::getUrlRequestText(URL::to($request->fullUrl()))), 'class' => 'delete-form-inline')) }}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            {{ Form::close() }}
                                            @endif
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">@lang('label.NO_DATA_FOUND')</td>
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
<div class="modal fade" id="fullInvoiceDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showFullInvoiceDetailsModal">
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
            var invoiceId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to approve this invoice?',
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
                        url: "{{ URL::to('billing/approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            invoice_id: invoiceId,
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
            var invoiceId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to deny this invoice?',
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
                        url: "{{ URL::to('billing/deny')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            invoice_id: invoiceId,
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
        
        $(document).on("click", ".full-invoice-details", function () {
            var invoiceId = $(this).attr('data-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('billing/billingFullLedgerDetails') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showFullInvoiceDetailsModal").html(res.html);
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