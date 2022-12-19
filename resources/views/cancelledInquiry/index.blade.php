@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-server"></i>@lang('label.CANCELLED_INQUIRY_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'cancelledInquiry/filter','class' => 'form-horizontal')) !!}
                    {!! Form::hidden('page', Helper::queryPageStr($qpArr ?? '')) !!}


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
                            <label class="control-label col-md-4" for="buyerId">@lang('label.PRODUCT')</label>
                            <div class="col-md-8">
                                {!! Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyerId">@lang('label.BRAND')</label>
                            <div class="col-md-8">
                                {!! Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') </label>
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
                    <div class="col-md-offset-4 col-md-2">
                        <div class="form  text-right">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>

            <!--product wise Quantity Summary-->
            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <button class="btn btn-sm blue-soft  tooltips vcenter" href="#quantitySummaryModal" id="quantitySummary"
                            data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.PRODUCT_WISE_TOTAL_QUANTITY')"
                            data-product_id="{{$request->product_id}}" data-brand_id="{{$request->brand_id}}"
                            data-buyer_id="{{$request->buyer_id}}" data-salespersons_id="{{$request->salespersons_id}}"
                            data-from_date="{{$request->from_date}}" data-to_date="{{$request->to_date}}">
                        <i class="fa fa-balance-scale"></i> <span class="bold">@lang('label.PRODUCT_WISE_TOTAL_QUANTITY')</span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.BUYER_NAME')</th>
                            <th class="vcenter">@lang('label.BUYER_CONTACT_PERSON')</th>
                            <th class="vcenter">@lang('label.SALES_PERSON')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="vcenter">@lang('label.GRADE')</th>
                            <th class="vcenter">@lang('label.GSM')</th>
                            <th class="vcenter">@lang('label.QUANTITY')</th>
                            <th class="vcenter">@lang('label.UNIT_PRICE')</th>
                            <th class="vcenter">@lang('label.TOTAL_PRICE')</th>
                            <th class="text-center vcenter">@lang('label.CREATION_DATE')</th>
                            <th class="vcenter">@lang('label.CAUSE_OF_FAILURE')</th>
                            <th class="vcenter">@lang('label.REMARKS')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
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
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{{ $target->buyerName }}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{{ $target->buyer_contact_person }}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{{ $target->salesPersonName }}</td>
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
                            <td class="vcenter text-right">${{$item['unit_price']}}&nbsp;<span>/</span>{{$item['unit_name']}}</td>
                            <td class="vcenter text-right">${{$item['total_price']}}</td>

                            @if($i == 0 && $j == 0 && $k == 0)
                            <!--:::::::: rowspan part :::::::-->
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}" >{{ Helper::formatDate($target->creation_date) }}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{{ $causeList[$target->cancel_cause] ?? '' }}</td>
                            <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{{ $target->cancel_remarks }}</td>
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}" >
                                @if($target->status == '1')
                                <span class="label label-sm label-warning">@lang('label.INQUIRY')</span>
                                @elseif($target->status == '2')
                                <span class="label label-sm label-success">@lang('label.ORDERED')</span>
                                @elseif($target->status == '3')
                                <span class="label label-sm label-danger">@lang('label.CANCELLED')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter" rowspan="{{$rowSpan['inquiry']}}" >
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[29][17]))
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="@lang('label.FOLLOW_UP')" data-inquiry-id ="{{$target->id}}" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[29][21]))
                                    <button class="btn btn-xs green-seagreen tooltips reactivate-inquiry vcenter" data-toggle="modal" title="@lang('label.CLICK_TO_REACTIVATE')" data-inquiry-id ="{{$target->id}}">
                                        <i class="fa fa-power-off"></i>
                                    </button>
                                    @endif

                                    @if(!empty($userAccessArr[29][4]))
                                    {{ Form::open(array('url' => 'cancelledInquiry/' . $target->id.'/'.Helper::queryPageStr($qpArr ?? ''), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
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
                            <td colspan="16" class="vcenter">@lang('label.NO_CANCELLED_INQUIRY_FOUND')</td>
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
<!--<div class="modal fade" id="cancellationModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCancellationModal">
        </div>
    </div>
</div>-->


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
    $(document).ready(function () {
        //followUp modal
        $(document).on("click", ".followup-history", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-inquiry-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('cancelledInquiry/getFollowUpModal')}}",
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
                        url: "{{ URL::to('cancelledInquiry/setFollowUpSave')}}",
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
                        url: "{{ URL::to('cancelledInquiry/reactivate')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            inquiry_id: inquiryId,
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
                            $('.reactivate-inquiry').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //Quantity Summary  modal
        $(document).on("click", "#quantitySummary", function (e) {

            var productId = $(this).data('product_id');
            var brandId = $(this).data('brand_id');
            var buyerId = $(this).data('buyer_id');
            var salespersonsId = $(this).data('salespersons_id');
            var fromDate = $(this).data('from_date');
            var toDate = $(this).data('to_date');

            $.ajax({
                url: "{{ URL::to('cancelledInquiry/quantitySummaryView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId,
                    from_date: fromDate,
                    to_date: toDate,
                },
                beforeSend: function () {
                    $("#ShowQuantitySummaryModal").html('');
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
    });
</script>
@stop
