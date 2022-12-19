@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-ban"></i>@lang('label.CANCELLED_ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'cancelledOrder/filter','class' => 'form-horizontal')) !!}
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
                        <label class="control-label col-md-4" for="salespersonsId">@lang('label.SALES_PERSON') </label>
                        <div class="col-md-8">
                            {!! Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']) !!}
                            <span class="text-danger">{{ $errors->first('salespersons_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId">@lang('label.BRAND')</label>
                        <div class="col-md-8">
                            {!! Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $uniqueNoArr, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="purchaseOrderNo">@lang('label.PURCHASE_ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('purchase_order_no', $purchaseOrderNoArr, Request::get('purchase_order_no'), ['class' => 'form-control js-source-states','id'=>'purchaseOrderNo']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="lcNo">@lang('label.LC_NO')</label>
                        <div class="col-md-8">
                            {!! Form::text('lc_no',  Request::get('lc_no'), ['class' => 'form-control tooltips', 'title' => __('label.LC_NO'), 'placeholder' => __('label.LC_NO'), 'list'=>'lcNo', 'autocomplete'=>'off']) !!} 
                            <datalist id="lcNo">
                                @if(!$lcNoArr->isEmpty())
                                @foreach($lcNoArr as $lc)
                                <option value="{{$lc->lc_no}}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>  
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
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
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
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

            <!--product wise Quantity Summary-->
            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <button class="btn btn-sm blue-soft  tooltips vcenter" href="#quantitySummaryModal" id="quantitySummary" 
                            data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.PRODUCT_WISE_TOTAL_QUANTITY')"
                            data-product_id="{{$request->product_id}}" data-brand_id="{{$request->brand_id}}"
                            data-buyer_id="{{$request->buyer_id}}" data-order_no="{{$request->order_no}}"
                            data-from_date="{{$request->from_date}}" data-to_date="{{$request->to_date}}"
                            data-purchase_order_no="{{$request->purchase_order_no}}" data-lc_no="{{$request->lc_no}}"
                            data-salespersons_id="{{$request->salespersons_id}}">
                        <i class="fa fa-balance-scale"></i> <span class="bold">@lang('label.PRODUCT_WISE_TOTAL_QUANTITY')</span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                            <th class="vcenter">@lang('label.BUYER')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.GRADE')</th>
                            <th class="vcenter">@lang('label.GSM')</th>
                            <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                            <th class="vcenter text-center">@lang('label.CREATION_DATE')</th>
                            <th class="vcenter text-center">@lang('label.PI_DATE')</th>
                            <th class="vcenter">@lang('label.LC_NO')</th>
                            <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                            <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                            <th class="vcenter">@lang('label.LC_ISSUE_DATE')</th>
                            <th class="vcenter">@lang('label.CAUSE_OF_FAILURE')</th>
                            <th class="vcenter">@lang('label.REMARKS')</th>
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
                        <?php
                        $iconFH = '';
                        if (!empty($hasFollowupList)) {
                            if (in_array($target->id, $hasFollowupList)) {
                                $iconFH = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips followup-history vcenter"'
                                        . ' href="#followUpModal"  data-toggle="modal" title="' . __('label.VIEW_FOLLOWUP_HISTORY') . '" 
                                            data-inquiry-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                            }
                        }
                        //inquiry rowspan
                        $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                        ?>
                        <tr>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl.$iconFH !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->order_no !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->purchase_order_no !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                            @if(!empty($target->inquiryDetails))
                            <?php $i = 0; ?>
                            @foreach($target->inquiryDetails as $productId=> $productData)
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
                            ?>
                            <td class="vcenter" rowspan="{{$rowSpan['product']}}">
                                {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                            </td>
                            @if(!empty($productData))
                            <?php $j = 0; ?>
                            @foreach($productData as $brandId=> $brandData)
                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                            ?>
                            <td class="vcenter" rowspan="{{$rowSpan['brand']}}">
                                {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                            </td>
                            @if(!empty($brandData))
                            <?php $k = 0; ?>
                            @foreach($brandData as $gradeId=> $gradeData)
                            <?php
                            if ($k > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                            ?>
                            <td class="vcenter" rowspan="{{$rowSpan['grade']}}">
                                {{!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''}}
                            </td>
                            @if(!empty($gradeData))
                            <?php $l = 0; ?>
                            @foreach($gradeData as $gsm=> $item)
                            <?php
                            if ($l > 0) {
                                echo '<tr>';
                            }
                            ?>
                            <td class="vcenter">{{!empty($gsm)?$gsm:''}}</td>
                            <td class="vcenter text-right">${{$item['unit_price']}}&nbsp;<span>/</span>{{$item['unit_name']}}</td>

                            @if($i == 0 && $j == 0 && $k == 0)
                            <!--:::::::: rowspan part :::::::-->
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->creation_date) !!}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->pi_date)?Helper::formatDate($target->pi_date):'' !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->lc_no !!}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->lc_date) ? Helper::formatDate($target->lc_date) : '' !!}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                @if($target->lc_transmitted_copy_done == '1')
                                <span class="label label-sm label-info">@lang('label.YES')</span>
                                @elseif($target->lc_transmitted_copy_done == '0')
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                {!! !empty($target->lc_issue_date)?Helper::formatDate($target->lc_issue_date):'' !!}
                            </td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $causeList[$target->order_cancel_cause] ?? '' !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->order_cancel_remarks !!}</td>
                            <td class="td-actions text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[30][21]))
                                    <button class="btn btn-xs green-seagreen tooltips reactivate-inquiry vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_REACTIVATE')" data-inquiry-id ="{{$target->id}}">
                                        <i class="fa fa-power-off"></i>
                                    </button>
                                    @endif

                                    @if(!empty($userAccessArr[30][17]))
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="@lang('label.FOLLOW_UP')" data-inquiry-id ="{{$target->id}}" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[30][5]))
                                    <button class="btn btn-xs yellow tooltips vcenter order-details" title="Veiw Order Details" href="#modalOrderDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[30][4]))
                                    @if(!array_key_exists($target->id,$hasDeliveryList))
                                    {{ Form::open(array('url' => 'cancelledOrder/' . $target->id.'/'.Helper::queryPageStr($qpArr ?? ''), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @endif
                                </div>
                            </td>
                            <!--:::::::: endof rowspan part :::::::-->
                            @endif
                            <?php
                            if ($l < ($rowSpan['grade'] - 1)) {
                                echo '</tr>';
                            }

                            $i++;
                            $j++;
                            $k++;
                            $l++;
                            ?>
                            @endforeach
                            @endif
                            @endforeach
                            @endif
                            @endforeach
                            @endif
                            @endforeach
                            @endif
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="20" class="vcenter">@lang('label.NO_CANCELLED_ORDER_FOUND')</td>
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


<!--order details-->
<div class="modal fade" id="modalOrderDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderDetails"></div>
    </div>
</div>

<!--followUp modal-->
<div class="modal fade" id="followUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showFollowUpModal">
        </div>
    </div>
</div>

<!-- Start quantity Summary Modal-->
<div class="modal fade" id="quantitySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowQuantitySummaryModal">
        </div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //order details modal
        $(".order-details").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/cancelledOrder/getOrderDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showOrderDetails").html('');
                },
                success: function (res) {
                    $("#showOrderDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //followUp modal
        $(document).on("click", ".followup-history", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-inquiry-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('cancelledOrder/getFollowUpModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    history_status: historyStatus,
                },
                beforeSend: function () {
                    $("#showFollowUpModal").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showFollowUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showFollowUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //endof followUp modal

        //After Click to Save New Follow Up
        $(document).on("click", "#saveHistory", function (e) {
            e.preventDefault();
            var formData = new FormData($('#submitForm')[0]);
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
                        url: "{{ URL::to('cancelledOrder/setFollowUpSave')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        data_id: 'inquiry_id',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveHistory').prop('disabled', true);
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
                            $('#saveHistory').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        // EOF Function for Set Lead Follow Up

        //Quantity Summary  modal
        $(document).on("click", "#quantitySummary", function (e) {
            var productId = $(this).data('product_id');
            var brandId = $(this).data('brand_id');
            var buyerId = $(this).data('buyer_id');
            var orderNo = $(this).data('order_no');
            var piFromDate = $(this).data('from_date');
            var piToDate = $(this).data('to_date');
            var purchaseOrderNo = $(this).data('purchase_order_no');
            var lcNo = $(this).data('lc_no');
            var salespersonsId = $(this).data('salespersons_id');

            $.ajax({
                url: "{{ URL::to('cancelledOrder/quantitySummaryView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    buyer_id: buyerId,
                    order_no: orderNo,
                    pi_from_date: piFromDate,
                    pi_to_date: piToDate,
                    purchase_order_no: purchaseOrderNo,
                    lc_no: lcNo,
                    salespersons_id: salespersonsId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#ShowQuantitySummaryModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowQuantitySummaryModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });



        //reactivate cancelled inquiry
        $(document).on("click", ".reactivate-inquiry", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-inquiry-id");
            //alert(inquiryId);return false;
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to reactivate this inquiry?',
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
                        url: "{{ URL::to('cancelledOrder/reactivate')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            inquiry_id: inquiryId,
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

    });

</script>
@stop