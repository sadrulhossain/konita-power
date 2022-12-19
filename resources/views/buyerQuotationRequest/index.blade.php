@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.QUOTATION_REQUEST_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('buyerQuotationRequest/quotation'.Helper::queryPageStr($qpArr)) }}"> @lang('label.SET_QUOTATION_REQUEST')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'buyerQuotationRequest/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
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


            <div class="table-responsive margin-top-10">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.QUOTATION_REQUEST')</th>
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
                        <tr>
                            <td class="vcenter text-center width-50">{{ ++$sl }}</td>
                            <td class="vcenter text-justify">
                                {!! Helper::trimString($target->description) !!}
                            </td>
                            <td class="text-center vcenter width-50">
                                @if($target->status == '0')
                                <span class="label label-sm label-success">@lang('label.PENDING')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.READ')</span>
                                @endif
                            </td>
                            <td class="vcenter text-center">
                                <button class="btn btn-sm bg-yellow-casablanca bg-font-yellow-casablanca tooltips vcenter" href="#quotationReqDetails" id="quotationRequestData"  data-toggle="modal" title="@lang('label.SHOW_QUOTATION_REQUEST_DETAILS')" data-buyer-id = {{$buyerInfo->id }} data-quotation-id = {{$target->id }}>
                                    <i class="fa fa-eye"></i>
                                </button>
                                <?php
                                $printUrl = '';
                                if (Auth::user()->group_id != '0') {
                                    $printUrl = 'quotationRequest/buyerQuotationReqDetails/';
                                } else {
                                    $printUrl = 'buyerQuotationRequest/quotationReqDetails/';
                                }
                                ?>
                                <a href="{{ URL::to($printUrl.$target->id.'?view=print') }}" target="_blank" class="btn btn-sm btn-primary tooltips vcenter">
                                    <i class="fa fa-print"></i>
                                </a>
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
<div class="modal fade" id="quotationReqDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showQuotationReqDetails">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#quotationRequestData", function (e) {
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
                url: "{{ route('buyerQuotationRequest.quotationReqDetails')}}",
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
                    $("#showQuotationReqDetails").html(res.html);
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
    });
</script>
@stop