@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.QUOTATION_REQUEST_LIST')
            </div>

        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'quotationRequest/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status',  $statusList, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>


                <div class="col-md-4 text-center">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.FILTER')
                    </button>
                </div>
            </div>
            {!! Form::close() !!}


            <div class="table-responsive margin-top-10 ">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.BUYER')</th>
                            <th class="vcenter">@lang('label.QUOTATION_REQUEST')</th>
                            <th class="vcenter text-center">@lang('label.STATUS')</th>
                            <th class="vcenter text-center">@lang('label.READ')</th>
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

                        <tr>
                            <td class="vcenter text-center width-50">{{ ++$sl }}</td>
                            <td class="vcenter">
                                {!! $target->buyer_name ?? __('label.N_A') !!}
                            </td>
                            <td class="vcenter text-justify">
                                {!! Helper::trimString($target->description) !!}
                            </td>
                            <td class="text-center vcenter width-50">
                                @if($target->read_status == '0')
                                <span class="label label-sm label-success">@lang('label.PENDING')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.READ')</span>
                                @endif
                            </td>

                            <td class="text-center vcenter width-50">
                                @if($target->read_status == '0')
                                <button class="btn btn-sm btn-danger"><i class="fa fa-commenting tooltips" title="@lang('label.QUOTATION_REQ_HAS_NOT_BEEN_READ_YET')"></i></button>
                                @else
                                <button class="btn btn-sm btn-success"><i class="fa fa-commenting-o tooltips" title="@lang('label.QUOTATION_REQ_HAS_BEEN_READ')"></i></button>
                                @endif
                            </td>

                            <td class="vcenter text-center">
                                @if($target->read_status == '0')
                                <button class="btn btn-sm green-seagreen tooltips read-req vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_READ_QUOTATION_REQ')" data-quotation-id = {{$target->id }} data-buyer-id = {{$target->buyer_id }}>
                                    <i class="fa fa-check"></i>
                                </button>
                                @endif
                                @if(!empty($userAccessArr[88][5]))
                                <button class="btn btn-sm bg-yellow-casablanca bg-font-yellow-casablanca tooltips vcenter" href="#buyerQuotationReqDetails" id="buyerQuotationRequestData"  data-toggle="modal" title="@lang('label.SHOW_QUOTATION_REQUEST_DETAILS')" data-buyer-id = {{$target->buyer_id }} data-quotation-id = {{$target->id }}>
                                    <i class="fa fa-eye"></i>
                                </button>
                                @endif
                                @if(!empty($userAccessArr[88][6]))
                                <?php
                                $printUrl = '';
                                if (Auth::user()->group_id != '0') {
                                    $printUrl = 'quotationRequest/buyerQuotationReqDetails/';
                                } else {
                                    $printUrl = 'buyerQuotationRequest/quotationReqDetails/';
                                }
                                ?>
                                <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($printUrl.$target->id.'?view=print') }}"  title="@lang('label.PRINT')">
                                    <i class="fa fa-print"></i>
                                </a>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_QUOTATION_REQUEST_FOUND')</td>
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
<div class="modal fade" id="buyerQuotationReqDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showBuyerQuotationReqDetails">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#buyerQuotationRequestData", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var buyerId = $(this).data('buyer-id');
            var quotationId = $(this).data('quotation-id');
            $.ajax({
                url: "{{ route('quotationRequest.buyerQuotationReqDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId,
                    quotation_id: quotationId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showBuyerQuotationReqDetails").html(res.html);
                    App.unblockUI();
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
                    App.unblockUI();
                }
            }); //ajax
        });

        //reactivate cancelled inquiry
        $(document).on("click", ".read-req", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var quotationId = $(this).data('quotation-id');
            //alert(inquiryId);return false;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to Read this Quotation?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Read',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('quotationRequest/markAsRead')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            buyer_id: buyerId,
                            quotation_id: quotationId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('.reactivate-inquiry').prop('disabled', true);
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
                            $('.read-req').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });


    });
</script>
@stop