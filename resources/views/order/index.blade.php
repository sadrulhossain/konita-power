@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.ORDER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[15][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('order/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_ORDER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'order/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderUniqueNo">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::text('order_unique_no', Request::get('order_unique_no'), ['class' => 'form-control tooltips', 'title' => 'Order No.', 'placeholder' => 'Order No.', 'list' => 'orderUniqueNo', 'autocomplete' => 'off']) !!} 
                            <datalist id="orderUniqueNo">
                                @if (!$uniqueNoArr->isEmpty())
                                @foreach($uniqueNoArr as $uniqueNo)
                                <option value="{{$uniqueNo->order_unique_no}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="lcDate">@lang('label.LC_DATE')</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker" style="z-index: 9994 !important">
                                {!! Form::text('lc_date', null, ['id'=> 'lcDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="lcDate">
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
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status', $statusArr, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.LEAD')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="text-center vcenter">@lang('label.LC_VALUE')</th>
                            <th class="vcenter">@lang('label.LC_NO')</th>
                            <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                            <th class="vcenter">@lang('label.EXPRESS_TRACKING_NO')</th>
                            <th class="vcenter">@lang('label.NOTE_')</th>
                            <th class="text-center vcenter">@lang('label.LC_DRAFT_DONE')</th>
                            <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
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
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->lead_name !!}</td>
                            <td class="vcenter">{!! $target->order_unique_no !!}</td>
                            <td class="text-center vcenter">{!! $target->lc_value !!}</td>
                            <td class="vcenter">{!! $target->lc_no !!}</td>
                            <td class="text-center vcenter">{!! Helper::formatDate($target->lc_date) !!}</td>
                            <td class="vcenter">{!! $target->express_tracking_no !!}</td>
                            <td class="vcenter">{!! $target->note !!}</td>
                            <td class="text-center vcenter">
                                @if($target->lc_draft_done == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($target->lc_draft_done == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->lc_transmitted_copy_done == '1')
                                <span class="label label-info">@lang('label.YES')</span>
                                @elseif($target->lc_transmitted_copy_done == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-primary">@lang('label.PENDING')</span>
                                @elseif($target->status == '2')
                                <span class="label label-danger">@lang('label.CANCELLED')</span>
                                @elseif($target->status == '3')
                                <span class="label label-info">@lang('label.PROCESSING')</span>
                                @elseif($target->status == '4')
                                <span class="label label-success">@lang('label.ACCOMPLISHED')</span>
                                @elseif($target->status == '5')
                                <span class="label label-warning">@lang('label.PAYMENT_DONE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[26][3]) && in_array($target->status, ['1', '3']))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('order/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[26][13]) && in_array($target->status, ['1', '3']))
                                    <button class="btn btn-xs btn-danger cancel tooltips vcenter" title="Cancel Order" data-id="{!! $target->id !!}" data-placement="top" data-rel="tooltip" data-original-title="Cancel Order">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[26][5]))
                                    <button class="btn btn-xs yellow tooltips vcenter order-details" title="Veiw Order Details" href="#modalOrderDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_ORDER_FOUND')</td>
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


<!--delivery details-->
<div class="modal fade" id="modalOrderDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderDetails"></div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //cancel order
        $('.cancel').on('click', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "Your will not be able to edit this order!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, cancel it",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    // data
                    var orderId = $(".cancel").attr("data-id");
                    $.ajax({
                        url: "{{ URL::to('/order/cancel')}}",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            order_id: orderId
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
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                        }
                    }); //ajax
                }
            });
        });

        //order details modal
        $(".order-details").on("click", function (e) {
            e.preventDefault();
            var orderId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/order/getOrderDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_id: orderId
                },
                success: function (res) {
                    $("#showOrderDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
    });

</script>

@stop