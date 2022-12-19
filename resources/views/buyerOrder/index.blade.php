@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.ORDER_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'buyerOrder/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <!--<div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="salespersonsId">@lang('label.SALES_PERSON') </label>
                        <div class="col-md-8">
                            {!! Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']) !!}
                            <span class="text-danger">{{ $errors->first('salespersons_id') }}</span>
                        </div>
                    </div>
                </div>-->
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
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status',  $statusList, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4 tooltips" title="@lang('label.PI_DATE')" for="fromDate">@lang('label.FROM_DATE') :</label>
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
                        <label class="control-label col-md-4 tooltips" title="@lang('label.PI_DATE')" for="toDate">@lang('label.TO_DATE') </label>
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
                <div class="col-md-4 text-center">
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
            <!--            <div class="row">
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
                        </div>-->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.ORDER_NO')</th>
                            <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.GRADE')</th>
                            <th class="vcenter">@lang('label.GSM')</th>
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.CREATION_DATE')</th>
                            <th class="vcenter text-center">@lang('label.PI_DATE')</th>
                            <th class="vcenter">@lang('label.LC_NO')</th>
                            <th class="text-center vcenter">@lang('label.LC_DATE')</th>
                            <th class="text-center vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                            <th class="vcenter">@lang('label.LC_ISSUE_DATE')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $key=>$target)
                        <?php
                        $iconMsg = '';
                        if (!empty($hasMessageList)) {
                            if (in_array($target->id, $hasMessageList)) {
                                $iconMsg = '<br/><i class="fa fa-comment text-blue-madison" ></i>';
                            }
                        }
                        //inquiry rowspan
                        $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                        ?>
                        <tr>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl.$iconMsg !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->order_no !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->purchase_order_no !!}</td>
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
                            <td class="vcenter text-right">{{$item['quantity']}}&nbsp;{{$item['unit_name']}}</td>

                            @if($i == 0 && $j == 0 && $k == 0)
                            <!--:::::::: rowspan part :::::::-->
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->creation_date) !!}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->pi_date)?Helper::formatDate($target->pi_date):'' !!}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->lc_no !!}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                {!! !empty($target->lc_date) ? Helper::formatDate($target->lc_date) : '' !!}
                            </td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                @if($target->lc_transmitted_copy_done == '1')
                                <span class="label label-sm label-info">@lang('label.YES')</span>
                                @elseif($target->lc_transmitted_copy_done == '0')
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="vcenter" rowspan="{{ $rowSpan['inquiry'] }}">
                                {!! !empty($target->lc_issue_date)?Helper::formatDate($target->lc_issue_date):'' !!}
                            </td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                @if($target->order_status == '2')
                                <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                @elseif($target->order_status == '3')
                                <span class="label label-sm label-purple">@lang('label.IN_PROGRESS')</span>
                                @elseif($target->order_status == '4')
                                <span class="label label-sm label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                                @elseif($target->order_status == '6')
                                <span class="label label-sm label-red-intense">@lang('label.CANCELLED')</span>
                                @endif
                            </td>
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
                                <button type="button" class="btn btn-xs cursor-default btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                    <i class="fa fa-minus"></i>
                                </button>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                <div class="width-inherit">
                                    <button class="btn btn-xs yellow tooltips vcenter order-details" title="Veiw Order Details" href="#modalOrderDetails" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <!--VIEW_LSD_INFORMATION-->
                                    <button class="btn btn-xs blue tooltips vcenter lsd-info" title="@lang('label.VIEW_LSD_INFORMATION')" href="#lsdInfo" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-server"></i>
                                    </button>
                                    @if(!in_array($target->order_status, ['4', '6']))
                                    <button class="btn btn-xs purple-sharp tooltips vcenter order-messaging" title="@lang('label.VIEW_MESSAGES')" href="#modalOrderMessaging" data-id="{!! $target->id !!}" data-buyer-id="{!! $buyerId !!}" data-toggle="modal">
                                        <i class="fa fa-commenting"></i>
                                    </button>
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
                            <td colspan="18" class="vcenter">@lang('label.NO_CONFIRMED_ORDER_FOUND')</td>
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

<!--order messaging-->
<div class="modal fade" id="modalOrderMessaging" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderMessaging"></div>
    </div>
</div>

<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>

<!--lsd info details-->
<div class="modal fade" id="lsdInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="lsdInfoDetails"></div>
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
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        //order details modal
        $(".order-details").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/buyerOrder/getOrderDetails')}}",
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

        //order messaging modal
        $(".order-messaging").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            var buyerId = $(this).attr("data-buyer-id");
            var count = $('span.badge-order-messaging').text();
            $.ajax({
                url: "{{ URL::to('/buyerOrder/getOrderMessaging')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    buyer_id: buyerId,
                },
                beforeSend: function () {
                    $("#showOrderMessaging").html('');

                },
                success: function (res) {
                    if (typeof count != 'undefined') {
                        count = count - 1;
                        if (count == 0) {
                            $('span.badge-order-messaging').remove();
                        } else {
                            $('span.badge-order-messaging').text(count)
                        }
                    }
                    $("#showOrderMessaging").html(res.html);

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

//        //Send Message
//        $(document).on("click", ".send-message", function (e) {
////            e.preventDefault();
//            var formData = new FormData($('#setMessageFrom')[0]);
//
//            $.ajax({
//                url: "{{ URL::to('buyerOrder/setMessage')}}",
//                type: 'POST',
//                cache: false,
//                contentType: false,
//                processData: false,
//                dataType: 'json',
//                data: formData,
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                beforeSend: function () {
//                    $('.send-message').prop('disabled', true);
//                    App.blockUI({boxed: true});
//                },
//                success: function (res) {
//                    $('.send-message').prop('disabled', false);
//                    $('#message').val('');
//                    $('.message-body').html(res.messageBody);
//                    App.unblockUI();
//
//                },
//                error: function (jqXhr, ajaxOptions, thrownError) {
//                    if (jqXhr.status == 400) {
//                        var errorsHtml = '';
//                        var errors = jqXhr.responseJSON.message;
//                        $.each(errors, function (key, value) {
//                            errorsHtml += '<li>' + value + '</li>';
//                        });
//                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
//                    } else if (jqXhr.status == 401) {
//                        toastr.error(jqXhr.responseJSON.message, '', options);
//                    } else {
//                        toastr.error('Error', 'Something went wrong', options);
//                    }
//                    $('.send-message').prop('disabled', false);
//                    App.unblockUI();
//                }
//            });
//        });



        //view lsd info modal
        $(".lsd-info").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/buyerOrder/lsdInfo')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#lsdInfoDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

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
                url: "{{ URL::to('buyerOrder/quantitySummaryView')}}",
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


        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/buyerOrder/getShipmentDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                beforeSend: function () {
                    $("#showShipmentDetails").html('');
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });


    });

</script>

@stop