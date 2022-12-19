@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-check-circle"></i>@lang('label.ACCOMPLISHED_ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'accomplishedOrder/filter','class' => 'form-horizontal')) !!}
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
                            <th class="text-center vcenter">@lang('label.REMARKS')</th>
                            @if(!empty($userAccessArr[31][5]))
                            <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                            @endif
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
                        $iconCAS = '';
                        $btnColorCAS = 'yellow-mint';
                        if (!empty($commissionAlreadySetList)) {
                            if (in_array($target->id, $commissionAlreadySetList)) {
                                $iconCAS = '<br/><span class="badge badge-primary tooltips" title="' . __('label.COMMISSION_ALREADY_SET') . '"><i class="fa fa-usd"></i></span>';
                                $btnColorCAS = 'yellow-gold';
                            }
                        }

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
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl.$iconCAS.$iconFH !!}</td>
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
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->lc_date)?Helper::formatDate($target->lc_date):'' !!}</td>
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
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->order_accomplish_remarks !!}</td>

                            @if(!empty($userAccessArr[31][5]))
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                @if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr))
                                @foreach($deliveryArr[$target->id] as $deliveryId => $delivery)

                                <button class="btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                        title="
                                        <div class='text-left'>
                                        @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                        @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                        @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                        @lang('label.CLICK_TO_SEE_DETAILS')
                                        </div>
                                        " 
                                        href="#modalShipmentDetails" data-id="{!! $deliveryId !!}" data-toggle="modal">
                                    <i class="fa fa-{{$delivery['icon']}}"></i>
                                </button>
                                @endforeach
                                @else
                                <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                    <i class="fa fa-minus"></i>
                                </button>
                                @endif
                            </td>
                            @endif
                            <td class="td-actions text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                <div class="width-inherit">
                                    <!--commission setup-->
                                    @if(!empty($userAccessArr[31][18]))
                                    <button class="btn btn-xs {{$btnColorCAS}}  tooltips vcenter commission-setup-modal" href="#commissionSetUpModal" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.COMMISSION_SETUP')">
                                        <i class="fa fa-sitemap"></i>
                                    </button>
                                    @endif

                                    @if(!empty($userAccessArr[31][17]))
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="@lang('label.FOLLOW_UP')" data-inquiry-id ="{{$target->id}}" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[31][5]))
                                    <button class="btn btn-xs yellow tooltips vcenter order-details" title="Veiw Order Details" href="#modalOrderDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    @if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr))
                                    <!--                                    <button class="btn btn-xs red-haze tooltips vcenter lead-time" title="@lang('label.VIEW_LEAD_TIME')" href="#modalLeadTime" data-id="{!! $target->id !!}" data-toggle="modal">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </button>-->
                                    @endif
                                    @endif

                                    <!--RW BREAKDOWN--> 
                                    @if(!empty($userAccessArr[31][19]))
                                    @if(!empty($rwBreakdownStatusArr[$target->id]))
                                    <a class="btn btn-xs green-soft tooltips vcenter" title="@lang('label.RW_BREAKDOWN_EDIT')" href="{{ URL::to('accomplishedOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? '')) }}">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <!--rw breakdown view-->
                                    <a class="btn btn-xs yellow-casablanca tooltips vcenter rw-breakdown-view" title="@lang('label.RW_BREAKDOWN_VIEW')" href="#rwBreakdownViewModal"  data-toggle="modal" data-inquiry-id ="{{$target->id}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @else
                                    <a class="btn btn-xs yellow tooltips vcenter" title="@lang('label.RW_BREAKDOWN')" href="{{ URL::to('accomplishedOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? '')) }}">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    @endif
                                    @endif
                                    <!--ENDOF RW BREAKDOWN--> 

                                    @if(!empty($userAccessArr[31][13]))
                                    @if(!array_key_exists($target->id, $hasInvoiceList))
                                    <button class="btn btn-xs red-intense order-cancel tooltips vcenter" href="#modalOrderCancellation" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="Cancel Order">
                                        <i class="fa fa-ban"></i>
                                    </button>
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
                            <td colspan="18" class="vcenter">@lang('label.NO_ACCOMPLISHED_ORDER_FOUND')</td>
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
<!--RW BREAKDOWN VIEW MODAL-->
<div class="modal fade" id="rwBreakdownViewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showRwBreakdownViewModal">
        </div>
    </div>
</div>

<!-- Start commissionSetUpModal-->
<div class="modal fade" id="commissionSetUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowcommissionSetUpModal">
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

<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>

<!-- Start Lead Time Modal-->
<div class="modal fade" id="modalLeadTime" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showLeadTime">
        </div>
    </div>
</div>

<!--order cancellation-->
<div class="modal fade" id="modalOrderCancellation" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showOrderCancellation"></div>
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
                url: "{{ URL::to('/accomplishedOrder/getOrderDetails')}}",
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
                url: "{{ URL::to('accomplishedOrder/getFollowUpModal')}}",
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
                        url: "{{ URL::to('accomplishedOrder/setFollowUpSave')}}",
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

        //RW BREAKDOWN VIEW MODAL
        $(document).on("click", ".rw-breakdown-view", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "{{ URL::to('accomplishedOrder/leadRwBreakdownView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showRwBreakdownViewModal").html('');
                },
                success: function (res) {
                    $("#showRwBreakdownViewModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //END OF RW BREAKDOWN VIEW MODAL

        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('id');

            $.ajax({
                url: "{{ URL::to('accomplishedOrder/getCommissionSetupModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                    $("#ShowcommissionSetUpModal").html('');
                },
                success: function (res) {
                    $("#ShowcommissionSetUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowcommissionSetUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //After Click to Save new commission 
        $(document).on("click", "#cmsnSaveBtn", function (e) {
            e.preventDefault();
            var formData = new FormData($('#cmsnSubmitForm')[0]);
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
                        url: "{{ URL::to('accomplishedOrder/commissionSetupSave')}}",
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
                            $('#cmsnSaveBtn').prop('disabled', true);
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
                            $('#cmsnSaveBtn').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

//        endof commission setup

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
                url: "{{ URL::to('accomplishedOrder/quantitySummaryView')}}",
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

        //order cancellation modal 
        $(".order-cancel").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-id');
            $.ajax({
                url: "{{ URL::to('accomplishedOrder/orderCancellationModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showOrderCancellation").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/accomplishedOrder/getShipmentDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //show lead time modal
        $(".lead-time").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/accomplishedOrder/getLeadTime')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showLeadTime").html(res.html);
                    $(".tooltips").tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
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
                }
            }); //ajax
        });

    });

</script>

@stop