@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.INQUIRY_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[23][2]) && Auth::user()->group_id == 1)
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('lead/create'.Helper::queryPageStr($qpArr ?? '')) }}"> @lang('label.CREATE_NEW_INQUIRY')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'lead/filter','class' => 'form-horizontal')) !!}
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
                            <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}" >{{ Helper::formatDate($target->creation_date) }}</td>
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
                                    @if(!empty($userAccessArr[23][3]))
                                    @if(!empty($rwBreakdownStatusArr[$target->id]))
                                    <!--this inquiry created of RW breakdown--> 
                                    <button class="btn btn-xs btn-primary tooltips vcenter edit-confirmation" title="Edit" data-id="{{$target->id}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @else
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('lead/' . $target->id . '/edit'.Helper::queryPageStr($qpArr ?? '')) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @endif

                                    @if(!empty($userAccessArr[23][4]))
                                    {{ Form::open(array('url' => 'lead/' . $target->id.'/'.Helper::queryPageStr($qpArr ?? ''), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif

                                    <!--RW BREAKDOWN STATUS-->
                                    @if(!empty($userAccessArr[23][19]))
                                    @if(!empty($rwBreakdownStatusArr[$target->id]))
                                    <a class="btn btn-xs green-soft tooltips vcenter" title="@lang('label.RW_BREAKDOWN_EDIT')" href="{{ URL::to('lead/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? '')) }}">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <!--rw breakdown view-->
                                    <a class="btn btn-xs yellow-casablanca tooltips vcenter" title="@lang('label.RW_BREAKDOWN_VIEW')" href="#rwBreakdownViewModal" id="rwBreakdownViewId" data-toggle="modal" data-inquiry-id ="{{$target->id}}">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @else
                                    <a class="btn btn-xs yellow tooltips vcenter" title="@lang('label.RW_BREAKDOWN')" href="{{ URL::to('lead/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? '')) }}">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    @endif
                                    <!--END RW BREAKDOWN STATUS-->
                                    @endif  <!--ENDOF CONFIRMATION IF-->


                                    <!--confirmation-->
                                    @if(!empty($userAccessArr[23][12]))
                                    <!--CONFIRMATION-->
                                    <button class="btn btn-xs btn-success tooltips vcenter" href="#confirmationModal" id="confirmationModalId" data-toggle="modal" sl-no="{{$sl}}" data-inquiry-id="{{$target->id}}" title="@lang('label.CLICK_TO_CONFIRMATION')" >
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @endif
                                    <!--endif confirmation btn-->

                                    <!--CANCELLATION-->
                                    @if(!empty($userAccessArr[23][13]))
                                    <button class="btn btn-xs btn-warning tooltips vcenter" href="#cancellationModal" id="cancellationId"  data-toggle="modal" title="@lang('label.CLICK_TO_CANCELLATION')" data-inquiry-id ="{{$target->id}}">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    @endif


                                    @if(!empty($userAccessArr[23][17]))
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="@lang('label.FOLLOW_UP')" data-inquiry-id ="{{$target->id}}" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    @endif

                                    <!--commission setup-->
                                    @if(!empty($userAccessArr[23][18]))
                                    <button class="btn btn-xs {{$btnColorCAS}}  tooltips vcenter commission-setup-modal" href="#commissionSetUpModal" data-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.COMMISSION_SETUP')">
                                        <i class="fa fa-sitemap"></i>
                                    </button>
                                    @endif

                                    <!--inquiry reassignment-->
                                    @if(!empty($userAccessArr[23][24]))
                                    <button class="btn btn-xs grey-cascade tooltips vcenter reassign-inquiry" href="#inquiryReassignmentModal" data-inquiry-id="{!! $target->id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip" title="@lang('label.INQUIRY_REASSIGNMENT')">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    @endif

                                    <!--quotation-->
                                    @if(!empty($userAccessArr[23][23]))
                                    <a class="btn btn-xs grey-mint tooltips vcenter" href="{{ URL::to('lead/quotation/' . $target->id . Helper::queryPageStr($qpArr)) }}" data-placement="top" data-rel="tooltip" title="@lang('label.SET_QUOTATION')">
                                        <i class="fa fa-calculator"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
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
                            <td colspan="18" class="vcenter">@lang('label.NO_INQUIRY_FOUND')</td>
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
<div class="modal fade" id="cancellationModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCancellationModal">
        </div>
    </div>
</div>

<!--preview modal start-->
<div class="modal fade" id="rwBreakdownViewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showRwBreakdownViewModal">
        </div>
    </div>
</div>
<!--preview modal End-->

<!--confirmation modal-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showConfirmationModal">
        </div>
    </div>
</div>
<!--end confirmation modal-->
<!--followUp modal-->
<div class="modal fade" id="followUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showFollowUpModal">
        </div>
    </div>
</div>
<!--end followUp modal-->
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

<!-- Start inquiry reassignment Modal-->
<div class="modal fade" id="inquiryReassignmentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInquiryReassignment">
        </div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on("click", ".edit-confirmation", function (e) {
            var inquiryId = $(this).attr('data-id');
            swal({
                title: 'Are you sure,You want to Edit?',
                text: '@lang("label.RW_BREAKDOWN_DATA_DELETE_TITLE")',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    location = "lead/" + inquiryId + "/edit";
                }
            });

        });

        //camcellation modal
        $(document).on("click", "#cancellationId", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "{{ URL::to('lead/leadCancellationModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showCancellationModal").html('');
                },
                success: function (res) {
                    $("#showCancellationModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //endof cancellation modal

        //camcellation modal
        $(document).on("click", "#confirmationModalId", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            var slNo = $(this).attr('sl-no');
            $.ajax({
                url: "{{ URL::to('lead/leadConfirmation')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    sl_no: slNo
                },
                beforeSend: function () {
                    $("#showConfirmationModal").html('');
                },
                success: function (res) {
                    $("#showConfirmationModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //endof cancellation modal

        //followUp modal
        $(document).on("click", ".followup-history", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-inquiry-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('lead/getFollowUpModal')}}",
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

        //Function for CONFIRMATION submit form
        $(document).on("click", "#submitConfirmation", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#confirmationForm')[0]);
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
                        url: "{{ URL::to('lead/leadConfirmationSave') }}",
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
                            $('#submitConfirmation').prop('disabled', true);
                            $('#cancellationId').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('{{ route("lead.index")}}'
                                            ), 7000);
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
                            $('#submitConfirmation').prop('disabled', false);
                            $('#cancellationId').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });
        //ENDOF CONFIRMATION SUBMIT FORM

        //Function for cancellation submit form
        $(document).on("click", "#submitLeadCancel", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#leadCencelForm')[0]);
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
                        url: "{{ URL::to('lead/leadCancellation') }}",
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
                            $('#submitLeadCancel').prop('disabled', true);
                            $('#confirmationModalId').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('{{ route("lead.index")}}'
                                            ), 7000);
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
                            $('#submitLeadCancel').prop('disabled', false);
                            $('#confirmationModalId').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });

        //RW BREAKDOWN VIEW MODAL
        $(document).on("click", "#rwBreakdownViewId", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "{{ URL::to('lead/leadRwBreakdownView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
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
                        url: "{{ URL::to('lead/setFollowUpSave')}}",
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

        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('id');

            $.ajax({
                url: "{{ URL::to('lead/getCommissionSetupModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                },
                beforeSend: function () {
                    $("#ShowcommissionSetUpModal").html('');
                    App.blockUI({
                        boxed: true
                    });
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
                        url: "{{ URL::to('lead/commissionSetupSave')}}",
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
        //end of commission

        //Quantity Summary  modal
        $(document).on("click", "#quantitySummary", function (e) {

            var productId = $(this).data('product_id');
            var brandId = $(this).data('brand_id');
            var buyerId = $(this).data('buyer_id');
            var salespersonsId = $(this).data('salespersons_id');
            var fromDate = $(this).data('from_date');
            var toDate = $(this).data('to_date');

            $.ajax({
                url: "{{ URL::to('lead/quantitySummaryView')}}",
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

        //inquiry reassignment modal
        $(document).on("click", ".reassign-inquiry", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-inquiry-id");
            $.ajax({
                url: "{{ URL::to('lead/getInquiryReassigned')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showInquiryReassignment").html('');
                },
                success: function (res) {
                    $("#showInquiryReassignment").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //Function for inquiry reassignment submit form
        $(document).on("click", "#submitinquiryReassignment", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#inquiryReassignmentForm')[0]);
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
                        url: "{{ URL::to('lead/setInquiryReassigned') }}",
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
                            $('#submitinquiryReassignment').prop('disabled', true);
                            $('.reassign-inquiry').prop('disabled', true);
                            App.blockUI();
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('{{ route("lead.index")}}'
                                            ), 7000);
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
                            $('#submitinquiryReassignment').prop('disabled', false);
                            $('.reassign-inquiry').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });

    });
</script>    
@stop