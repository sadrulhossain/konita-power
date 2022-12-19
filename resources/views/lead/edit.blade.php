@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_INQUIRY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['files'=> true, 'class' => 'form-horizontal', 'id' => 'leadEditForm'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="buyerId">@lang('label.BUYER') :<span class="text-danger"> *</span></label>
                                {!! Form::select('buyer_id', $buyerList, null, ['class' => 'form-control js-source-states country-id', 'id' => 'buyerId']) !!}
                                <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="buyerContactPerson">@lang('label.BUYER_CONTACT_PERSON') :<span class="text-danger"> *</span></label>
                                {!! Form::select('buyer_contact_person', $buyerContPersonList, !empty($target->contact_person_identifier)?$target->contact_person_identifier:null, ['class' => 'form-control js-source-states country-id', 'id' => 'buyerContactPerson']) !!}
                                <span class="text-danger">{{ $errors->first('buyer_contact_person') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="salespersonsId">@lang('label.SALES_PERSON') :<span class="text-danger"> *</span></label>
                                {!! Form::select('salespersons_id', $salesPersonList, null, ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']) !!}
                                <span class="text-danger">{{ $errors->first('salespersons_id') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="creationDate">@lang('label.INQUIRY_DATE') :<span class="text-danger"> *</span></label>
                                <?php
                                $currentDate = Helper::formatDate($target->creation_date);
                                ?>
                                <div class="input-group date datepicker2" data-date-end-date="+0d">
                                    {!! Form::text('creation_date', $currentDate, ['id'=> 'creationDate', 'class' => 'form-control', 'placeholder' => __('label.CREATION_DATE'), 'readonly' => '','autocomplete' => 'off']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="creationDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('creation_date') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                                {!! Form::select('product_id', $productList, null, ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="brandId">@lang('label.BRAND') :<span class="text-danger"> *</span></label>
                                {!! Form::select('brand_id', $brandList, null, ['class' => 'form-control js-source-states', 'id' => 'brandId']) !!}
                                <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div id="showGrade">
                                <div class="col-md-3">
                                    <label class="control-label" for="gradeId">@lang('label.GRADE') :</label>
                                    {!! Form::select('grade_id', $gradeList, null, ['class' => 'form-control js-source-states', 'id' => 'gradeId']) !!}
                                    {!! Form::hidden('grade_value', '0', ['id' => 'gradeValue']) !!}
                                    <span class="text-danger">{{ $errors->first('grade_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="gsm">@lang('label.GSM') :<span class="text-danger"> *</span></label>
                                <div class="input-group bootstrap-touchspin col-md-12">
                                    {!! Form::text('gsm', null, ['id'=> 'gsm', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                </div>
                                <span class="text-danger">{{ $errors->first('gsm') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="quantity">@lang('label.QUANTITY') :<span class="text-danger"> *</span></label>
                                <div class="input-group bootstrap-touchspin">
                                    {!! Form::text('quantity', null, ['id'=> 'quantity', 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!} 
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold" id="quantityUnit"></span>
                                </div>
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="unitPrice">@lang('label.UNIT_PRICE') :</label>
                                <div class="input-group bootstrap-touchspin">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('unit_price', null, ['id'=> 'unitPrice', 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!} 
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold" id="unitPricePerUnit"></span>
                                </div>
<!--                                    <span class="unit-price-status bold"></span>
                                <span class="price-status bold"></span>-->
                                <span class="text-danger">{{ $errors->first('unit_price') }}</span>
                                <span id="product-pricing"></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="totalPrice">@lang('label.TOTAL_PRICE') :</label>
                                <div class="input-group bootstrap-touchspin">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    {!! Form::text('total_price',!empty($totalPrice)?$totalPrice: null, ['id'=> 'totalPrice', 'class' => 'form-control','readonly']) !!} 
                                </div>
                                <span class="text-danger">{{ $errors->first('total_price') }}</span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3 margin-top-10">
                                <?php
                                $shipmentAddress1 = $shipmentAddress2 = '';
                                if ($target->shipment_address_status == '1') {
                                    $shipmentAddress1 = 'checked';
                                } elseif ($target->shipment_address_status == '2') {
                                    $shipmentAddress2 = 'checked';
                                }
                                ?>
                                <div class="mt-radio-inline">
                                    <label class="mt-radio">
                                        <input type="radio" name="shipment_address_status" id="shipmentAddress1" value="1" {{$shipmentAddress1}}> @lang('label.HEAD_OFFICE')
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input type="radio" name="shipment_address_status" id="shipmentAddress2" value="2" {{$shipmentAddress2}}> @lang('label.FACTORY')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--endof div shipment info-->

                    <!--add item div start-->
                    <!--factory-->
                    <div class="col-md-12">
                        <!-- START:: shipment address Data -->


                        @if($target->shipment_address_status == '1')
                        <div class="form" id="addressShow">
                            <div class="col-md-3">
                                <label class="control-label" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                {!! Form::text('head_office_address', null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('head_office_address') }}</span>
                            </div>
                        </div>
                        @else
                        <div class="form" id="addressShow" style="display: none">
                            <div class="col-md-3">
                                <label class="control-label" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                {!! Form::text('head_office_address', null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('head_office_address') }}</span>
                            </div>
                        </div>
                        @endif
                        @if($target->shipment_address_status == '2')
                        <div class="form" id="factoryShow">
                            <div class="col-md-3">
                                <label class="control-label" for="factoryId">@lang('label.FACTORY') :<span class="text-danger"> *</span></label>
                                {!! Form::select('factory_id', $factoryList, null, ['class' => 'form-control js-source-states', 'id' => 'factoryId']) !!}
                                <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                <span id="buyerFactoryAddress">{{$factoryAddress}}</span>
                            </div>
                        </div>
                        @else
                        <div class="form" id="factoryShow" style="display: none">
                            <div class="col-md-3">
                                <label class="control-label" for="factoryId">@lang('label.FACTORY') :<span class="text-danger"> *</span></label>
                                {!! Form::select('factory_id', $factoryList, null, ['class' => 'form-control js-source-states', 'id' => 'factoryId']) !!}
                                <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                <span id="buyerFactoryAddress"></span>
                            </div>
                        </div>
                        @endif
                        <!--end of factory-->
                        <div class="form">
                            <div class="col-md-3 margin-top-27">
                                <span class="btn green tooltips" type="button" id="addItem"  title="Add Item">
                                    <i class="fa fa-plus text-white"></i>&nbsp;<span>@lang('label.ADD_ITEM')</span>
                                </span>
                            </div>
                        </div>
                    </div><!--END OF ADD ITEM DIV-->
                </div>
            </div> 

            <!--new lead item list-->
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 table-responsive webkit-scrollbar">
                        <p><b><u>@lang('label.NEW_LEAD_ITEM_LIST'):</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter">@lang('label.GSM')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-right vcenter">@lang('label.UNIT_PRICE')</th>
                                    <th class="text-right vcenter">@lang('label.TOTAL_PRICE')</th>
                                    <th class="text-center vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                @if(!$inquiryDetails->isempty())
                                <?php
                                $countNumber = 1;
                                ?>
                                @foreach($inquiryDetails as $item)
                                <?php $gradeId = !empty($item->grade_id) ? $item->grade_id : 0; ?>
                                <tr id="rowId_{{$item->product_id}}_{{$countNumber}}" class="item-list">

                                    <td class="vcenter">
                                        <input type="hidden" name="add_btn" value="1">
                                        <input type="hidden" id="editFlag_{{$item->product_id}}_{{$countNumber}}"  value="">
                                        <input type="hidden" id="quantity_{{$item->product_id}}_{{$countNumber}}"  name="quantity[]"  value="{{$item->quantity}}">
                                        <input type="hidden" id="unitPrice_{{$item->product_id}}_{{$countNumber}}"  name="unit_price[]"  value="{{!empty($item->unit_price)?$item->unit_price:0.00}}">
                                        <input type="hidden" id="totalPrice_{{$item->product_id}}_{{$countNumber}}"  name="total_price[]" class="item-amount"  value="{{!empty($item->total_price)?$item->total_price:0.00}}">
                                        <input type="hidden" id="productId_{{$item->product_id}}_{{$countNumber}}" name="product_id[]"  value="{{$item->product_id}}">
                                        <input type="hidden" id="brandId_{{$item->product_id}}_{{$countNumber}}" name="brand_id[]"  value="{{$item->brand_id}}">
                                        <input type="hidden" id="gradeId_{{$item->product_id}}_{{$countNumber}}" name="grade_id[]"  value="{{$item->grade_id}}">
                                        <input type="hidden" id="gsm_{{$item->product_id}}_{{$countNumber}}" name="gsm[]"  value="{{!empty($item->gsm) ? $item->gsm : ''}}">
                                        <input type="hidden" id="prevItem_{{$item->product_id}}_{{$item->brand_id}}_{{$gradeId}}" name="prev_item[{{$item->product_id}}][{{$item->brand_id}}][{{$gradeId}}]"  value="1">

                                        {{$item->product_name}}
                                    </td>
                                    <td class="vcenter">{{$item->brand_name}}</td>
                                    <td class="vcenter">{{$item->grade_name}}</td>
                                    <td class="vcenter">{{!empty($item->gsm) ? $item->gsm : ''}}</td>
                                    <td class="vcenter text-right">{{$item->quantity}}&nbsp;{{!empty($item->unit_name)?$item->unit_name:''}}</td>
                                    <td class="vcenter text-right">$
                                        {{!empty($item->unit_price)?$item->unit_price:0.00}} &nbsp;<span>/</span>{{!empty($item->unit_name)?$item->unit_name:''}}
                                    </td>
                                    <td class="vcenter text-right">$
                                        {{!empty($item->total_price)?$item->total_price:0.00}}
                                    </td>
                                    <td class="vcenter text-center">
                                        <button type="button" class="btn btn-xs btn-primary vcenter edit-show" id="editBtn{{$item->product_id}}_{{$countNumber}}" title="Edit Product" onclick="editProduct({{$item->product_id}},{{$countNumber}});"><i class="fa fa-edit text-white"></i></button>
                                        <button type="button" onclick="removeItem({{$item->product_id}},{{$countNumber}});" class="btn btn-xs btn-danger vcenter remove-show" id="deleteBtn{{$item->product_id}}_{{$countNumber}}"  title="Remove Item"><i class="fa fa-trash text-white"></i></button> 
                                    </td>
                                </tr>
                                <?php
                                $countNumber++;
                                ?>
                                @endforeach
                                <tr id="hideNodata" style="display: none">
                                    <td colspan="8">@lang('label.NO_DATA_SELECT_YET')</td>
                                </tr>
                                <tr id="netTotalRow">
                                    <td colspan="6" class="text-right">Total</td>
                                    <td class="text-right interger-decimal-only">$
                                        <span id="netTotal">{{$netTotalPrice}}</span>
                                        <input type="hidden" value="{{$netTotalPrice}}" id="netTotalPrice">
                                    </td>
                                    <td></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="editRowId" value="">
                    <input type="hidden" id="total" value="">
                </div>
            </div>
            <div class="form-body">
                <div class="row first-followup-block">
                    <!--                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 margin-bottom-10">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="opportunityId">@lang('label.OPPORTUNITY'):</label>
                                                <div class="col-md-8">
                                                    <button class="btn btn-sm bold blue-steel tooltips vcenter choose-opportunity" title="@lang('label.CLICK_TO_CHOOSE_OPPORTUNITY')" href="#modalChooseOpportunity" data-id="{!! !empty($target->opportunity_id) ? $target->opportunity_id : 0 !!}" data-toggle="modal">
                                                        @lang('label.CHOOSE_OPPORTUNITY')
                                                    </button>
                                                    <button class="btn btn-sm bold yellow tooltips vcenter opportunity-details" title="@lang('label.CLICK_TO_VIEW_OPPORTUNITY_DETAILS')" href="#modalOpportunityDetails" data-id="{!! !empty($target->opportunity_id) ? $target->opportunity_id : 0 !!}" data-toggle="modal">
                                                        <i class="fa fa-bars"></i>
                                                    </button>
                                                    <button class="btn btn-sm bold red-intense tooltips vcenter clear-opportunity-choice" type="button" title="@lang('label.CLICK_TO_CLEAR_OPPORTUNITY_CHOICE')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    {!! Form::hidden('opportunity_id', !empty($target->opportunity_id) ? $target->opportunity_id : 0, ['id'=>'opportunityId']) !!}
                                                </div>
                                            </div>
                                        </div>-->
                    <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-2 col-lg-6 col-md-6 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h5><strong>@lang('label.FIRST_FOLLOWUP')</strong></h5>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4" for="followupStatus">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('followup_status', $followupStatusList, $firstFollowupStatus, ['class' => 'form-control js-source-states ','id'=>'followupStatus']) !!}
                                <span class="text-danger">{{ $errors->first('followup_status') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4" for="followupRremarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('followup_remarks', null, ['id'=> 'followupRremarks', 'class' => 'form-control']) !!} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="submitButton">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/lead'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>


<!-- Modal start -->

<!--opportunity details-->
<div class="modal fade" id="modalOpportunityDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityDetails"></div>
    </div>
</div>

<!--assign opportunity-->
<div class="modal fade" id="modalChooseOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showChooseOpportunity"></div>
    </div>
</div>

<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function () {

    //***************** Start :: choose opportunity *********************
    //initialy hide opportunity details
    var leadOpportunityId = $('#opportunityId').val();
    if (leadOpportunityId == 0){
    $('.opportunity-details').hide();
    $('.clear-opportunity-choice').hide();
    }
    //clear opportunity choice
    $('.clear-opportunity-choice').on('click', function () {
    $('.opportunity-details').attr('data-id', 0);
    $('.choose-opportunity').attr('data-id', 0);
    $('#opportunityId').val(0);
    $('.opportunity-details').hide();
    $('.clear-opportunity-choice').hide();
    });
    //choose opportunity modal
    $(".choose-opportunity").on("click", function (e) {
    e.preventDefault();
    var opportunityId = $(this).attr("data-id");
    $.ajax({
    url: "{{ URL::to('/lead/getChooseOpportunity')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            selected_opportunity_id: opportunityId
            },
            beforeSend: function () {
            $("#showChooseOpportunity").html('');
            },
            success: function (res) {
            $("#showChooseOpportunity").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
    }); //ajax
    });
    //set oppornunity choice
    $(document).on("click", "#saveOpportunityChoice", function (e) {
    e.preventDefault();
    var formData = new FormData($('#chooseOpportunityForm')[0]);
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
            confirmButtonText: 'Yes,Confirm as done',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
    }, function (isConfirm) {
    if (isConfirm) {
    $.ajax({
    url: "{{ URL::to('lead/setChooseOpportunity')}}",
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
            $('#saveOpportunityChoice').prop('disabled', true);
            App.blockUI({
            boxed: true,
            });
            },
            success: function (res) {
            $('#saveOpportunityChoice').prop('disabled', false);
            App.unblockUI();
            $('#modalChooseOpportunity').modal('hide');
            var opportunityId = res.opportunityId;
            $('.opportunity-details').show();
            $('.clear-opportunity-choice').show();
            $('.opportunity-details').attr('data-id', opportunityId);
            $('.choose-opportunity').attr('data-id', opportunityId);
            $('#opportunityId').val(opportunityId);
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
            $('#saveOpportunityChoice').prop('disabled', false);
            App.unblockUI();
            }
    });
    }
    });
    });
    //show opportunity details
    $(".opportunity-details").on("click", function (e) {
    e.preventDefault();
    var opportunityId = $(this).attr("data-id");
    $.ajax({
    url: "{{ URL::to('/lead/getOpportunityDetails')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            opportunity_id: opportunityId
            },
            beforeSend: function () {
            $("#showOpportunityDetails").html('');
            },
            success: function (res) {
            $("#showOpportunityDetails").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
    }); //ajax
    });
    //***************** End :: choose opportunity ***********************

    //SHOW TOTAL PRICE MULTIPLICATION
    $('#unitPrice').keyup(function (e) {
    var totalPrice = 0;
    var totalQuantity = $('#quantity').val();
    $.each($('#unitPrice'), function () {
    totalPrice = totalQuantity * $(this).val();
    });
    $('#totalPrice').val(totalPrice.toFixed(2));
    });
    $('#quantity').keyup(function (e) {
    var totalPrice = 0;
    var unitPrice = $('#unitPrice').val();
    $.each($('#quantity'), function () {
    totalPrice = unitPrice * $(this).val();
    });
    $('#totalPrice').val(totalPrice.toFixed(2));
    });
    //ENDOF MULTIPLICATION SCRIPT



    //hide & show
    $(document).on('change', '#shipmentAddress2', function (e) {
    $('#factoryShow').show('100');
    $('#addressShow').hide('100');
    $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
    });
    $(document).on('change', '#shipmentAddress1', function (e) {
    $('#factoryShow').hide('100');
    $('#addressShow').show('100');
    $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
    });
    //factory Under Show Factory Address
    $(document).on('change', '#factoryId', function (e) {
    var factoryId = $('#factoryId').val();
    $.ajax({
    url: "{{ URL::to('lead/getFactoryAddress')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            factory_id: factoryId
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#buyerFactoryAddress').html(res.address);
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    //buyer under buyer contact persons
    $(document).on('change', '#buyerId', function (e) {
    var buyerId = $('#buyerId').val();
    $('#buyerFactoryAddress').html('');
    $.ajax({
    url: "{{ URL::to('lead/getBuyerContPerson')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            buyer_id: buyerId
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#buyerContactPerson').html(res.html);
            $('#factoryId').html(res.factory);
            $('#salespersonsId').html(res.salesPerson);
            if (res.headOffice != '') {
            $('#address').val(res.headOffice);
            $('#address').prop('readonly', true)
            } else {
            $('#address').val('');
            $('#address').prop('readonly', true)
            }


            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    $(document).on('change', '#buyerId,#salespersonsId', function (e) {
    var buyerId = $('#buyerId').val();
    var salespersonsId = $('#salespersonsId').val();
    $("#brandId").html("<option value='0'>@lang('label.SELECT_BRAND_OPT')</option>");
    $.ajax({
    url: "{{ URL::to('lead/getLeadProduct')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            buyer_id: buyerId,
                    salespersons_id: salespersonsId
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#productId').html(res.html);
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    //product  under Brand**
    $(document).on('change', '#productId', function (e) {
    var productId = $('#productId').val();
    var buyerId = $('#buyerId').val();
    var salespersonsId = $('#salespersonsId').val();
    $("#product-pricing").html('');
    $.ajax({
    url: "{{ URL::to('lead/getLeadBrand')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#brandId').html(res.html);
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    //product measurement unit**


    //***** GET Product && Brand Wise Grade ********
    $(document).on('change', '#productId,#brandId', function (e) {
    var productId = $('#productId').val();
    var brandId = $('#brandId').val();
    $.ajax({
    url: "{{ URL::to('lead/getLeadGrade')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    brand_id: brandId,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#showGrade').html(res.html);
//            $('#gradeId').html(res.html);
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    //Endof GET Product && Brand Wise Grade




    $(document).on('change', '#productId', function (e) {
    var productId = $('#productId').val();
    $.ajax({
    url: "{{ URL::to('lead/getLeadProductUnit')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            var unit = res.measureUnitName;
            var perUnit = res.perMeasureUnitName;
            $("span#quantityUnit").text(unit);
            $("span#unitPricePerUnit").text(perUnit);
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    });
    //Function for Update Supplier Data
    $(document).on("click", "#submitButton", function (e) {
    e.preventDefault();
    var options = {
    closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
    };
    // Serialize the form data
    var formData = new FormData($('#leadEditForm')[0]);
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
    url: "{{ route('lead.update') }}",
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
            $('#submitButton').prop('disabled', true);
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
            $('#submitButton').prop('disabled', false);
            App.unblockUI();
            }
    });
    }
    });
    });
    //set unit price status
    $('#unitPrice').keyup(function (e) {
    var priceStatus = 0;
    var minimumPrice = $('#minimumSellingPrice').val();
    $.each($('#unitPrice'), function () {
    priceStatus = $(this).val() - minimumPrice;
    });
    var minus = '';
    $('span#priceStatus').css("color", "#333");
    if (priceStatus < 0) {
    priceStatus = ( - 1) * priceStatus;
    minus = '-';
    $('span#priceStatus').css("color", "red");
    $('span#priceStatus').text(minus + "$" + priceStatus);
    } else {

    $('span#priceStatus').css("color", "#333");
    $('#priceStatus').text(priceStatus);
    }
    });
    //START product pricing 
    $(document).on('change', '#brandId', function (e) {
    var productId = $('#productId').val();
    var brandId = $('#brandId').val();
    var unitPrice = $('#unitPrice').val()

            $.ajax({
            url: "{{ URL::to('lead/getProductPricing')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                    product_id: productId,
                            brand_id: brandId,
                            unit_price: unitPrice,
                    },
                    beforeSend: function () {
                    App.blockUI({boxed: true});
                    },
                    success: function (res) {
                    $('#product-pricing').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                    }
            }); //ajax

    });
    $(document).on('change', '#gradeId', function (e) {
    var productId = $('#productId').val();
    var brandId = $('#brandId').val();
    var gradeId = $('#gradeId').val();
    var unitPrice = $('#unitPrice').val()

            $.ajax({
            url: "{{ URL::to('lead/getProductPricing')}}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                    product_id: productId,
                            brand_id: brandId,
                            grade_id: gradeId,
                            unit_price: unitPrice,
                    },
                    beforeSend: function () {
                    App.blockUI({boxed: true});
                    },
                    success: function (res) {
                    $('#product-pricing').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                    }
            }); //ajax

    });
    //ENDOF Product Priceing

    //************************ NEW ADD ITEM SCRIPT *****************************
    var count = {{$countNumber}};
    $('#addItem').on('click', function () {

    $('.edit-show').attr("disabled", false);
    $('.remove-show').attr("disabled", false);
    var buyerId = $('#buyerId').val();
    var buyerContactPerson = $('#buyerContactPerson').val();
    var salespersonsId = $('#salespersonsId').val();
    var creationDate = $('#creationDate').val();
    var productId = $('#productId').val();
    var brandId = $('#brandId').val();
    var gradeId = $('#gradeId').val();
    var gradeValue = $('#gradeValue').val();
    var quantity = $('#quantity').val();
    var gsm = $('#gsm').val();
    var unitPrice = $('#unitPrice').val();
    var totalPrice = $('#totalPrice').val();
    var shipmentAddress1 = $('#shipmentAddress1').val();
    var shipmentAddress2 = $('#shipmentAddress2').val();
    var address = $('#address').val();
    var factoryId = $('#factoryId').val();
    var countNumber = count++;
    if (unitPrice == '') {
    unitPrice = 0.00;
    }


    var options = {
    closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
    };
    if (buyerId == '0') {
    toastr.error("Please select  Buyer", "Error", options);
    return false;
    }

    if (buyerContactPerson == '0') {
    toastr.error("Please select  Buyer Contact Person", "Error", options);
    return false;
    }

    if (salespersonsId == '0') {
    toastr.error("Please select  Sales Persons", "Error", options);
    return false;
    }

    if (creationDate == '') {
    toastr.error("Please select  Creation Date", "Error", options);
    return false;
    }

    if (productId == '0') {
    toastr.error("Please select  Product", "Error", options);
    return false;
    }


    if (brandId == '0') {
    toastr.error("Please select Brand", "Error", options);
    return false;
    }

    if (gradeValue == '1') {
    if (gradeId == '0') {
    toastr.error("Please select Grade", "Error", options);
    return false;
    }
    }

    if (gsm == '') {
    toastr.error("Please insert  gsm", "Error", options);
    return false;
    }

    if (quantity == '') {
    toastr.error("Please insert  quantity", "Error", options);
    return false;
    }

    var grade = 0;
    if (gradeId != ''){
    grade = gradeId;
    }

    var prevItemVal = $("#prevItem_" + productId + "_" + brandId + "_" + grade + "_" + gsm).val();
    if (typeof prevItemVal !== 'undefined'){
    toastr.error("This item has already been added", "Error", options);
    return false;
    }


    //when i edit one row then delete previous row
    var editRow = $("#editRowId").val();
    if (editRow != '') {
    $('#rowId_' + editRow).remove();
    }


    $.ajax({
    url: "{{ URL::to('lead/getProductBrandData')}}",
            type: "POST",
            dataType: 'json',
            cache: false,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    brand_id: brandId,
                    grade_id: gradeId,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            }
    }).done(function (result) {

    $("#hideNodata").css({"display": "none"});
    var rowCount = $('tbody#itemRows tr').length;
    row = '<tr id="rowId_' + productId + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + productId + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + productId + '_' + countNumber + '"  name="quantity[]"  value="' + parseFloat(quantity).toFixed(2) + '">\n\
                    <input type="hidden" id="unitPrice_' + productId + '_' + countNumber + '"  name="unit_price[]"  value="' + parseFloat(unitPrice).toFixed(2) + '">\n\
                    <input type="hidden" id="totalPrice_' + productId + '_' + countNumber + '"  name="total_price[]" class="item-amount"  value="' + totalPrice + '">\n\
                    <input type="hidden" id="productId_' + productId + '_' + countNumber + '" name="product_id[]"  value="' + productId + '">\n\
                    <input type="hidden" id="brandId_' + productId + '_' + countNumber + '" name="brand_id[]"  value="' + brandId + '">\n\
                    <input type="hidden" id="gradeId_' + productId + '_' + countNumber + '" name="grade_id[]"  value="' + gradeId + '">\n\
                    <input type="hidden" id="gsm_' + productId + '_' + countNumber + '" name="gsm[]"  value="' + gsm + '">\n\
\n\
                    <input type="hidden" id="prevItem_' + productId + '_' + brandId + '_' + grade + '_' + gsm + '" name="prev_item[' + productId + '][' + brandId + '][' + grade + '][' + gsm + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.brandName + '</td>\n\
                <td>' + result.gradeName + '</td>\n\
                <td>' + gsm + '</td>\n\
                <td class="text-right">' + parseFloat(quantity).toFixed(2) + ' ' + result.productUnit + '</td>\n\
                <td class="text-right">$' + parseFloat(unitPrice).toFixed(2) + ' ' + '/' + result.productUnit + '</td>\n\
                <td class="text-right">$' + totalPrice + '</td>\n\
                <td class="text-center">\n\
                    <button type="button" class="btn btn-xs btn-primary vcenter edit-show" id="editBtn' + productId + '_' + countNumber + '" title="Edit Product" onclick="editProduct(' + productId + ',' + countNumber + ');"><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" onclick="removeItem(' + productId + ',' + countNumber + ');" class="btn btn-xs btn-danger vcenter remove-show" id="deleteBtn' + productId + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
                </td></tr>';
    // get total amount

    if (rowCount == 1) {
    row += '<tr id="netTotalRow">\n\
                    <td colspan="6" class="text-right">Total</td>\n\
                    <td id="netTotal" class="text-right interger-decimal-only"></td>\n\
                    <td></td>\n\
                    </tr>';
    $('#itemRows').append(row);
    } else {
    $('#itemRows tr:last').before(row);
    }

    var netTotal = 0;
    $(".item-amount").each(function () {
    netTotal += parseFloat($(this).val());
    });
    $('#netTotal').text(netTotal.toFixed(2));
    $('#productId').focus();
    $('#submitButton').attr("disabled", false);
    App.unblockUI();
    });
    });
    //if buyer && salesperson change then remove existing item from table
    $(document).on('change', '#buyerId,#salespersonsId', function () {
    $('tr#netTotalRow').remove();
    $('tr.item-list').remove();
    $('tr#hideNodata').show();
    $('#submitButton').attr("disabled", true);
    });
    //************** END OF NEW ITEM ADD ********************

    });
    //****************** Remove Item *****************
    function removeItem(productId, countNumber) {

    $('#rowId_' + productId + '_' + countNumber).remove();
    var rowCount = $('tbody#itemRows tr').length;
    if (rowCount == 2) {
    $('tr#netTotalRow').remove();
    $('#hideNodata').show();
    }

    var netTotal = 0;
    $(".item-amount").each(function () {
    netTotal += parseFloat($(this).val());
    });
    $('#netTotal').text(netTotal);
    $('#submitButton').attr("disabled", false);
    }

    //*************Endof remove Item *****************

    //****************** edit item ***********************
    function editProduct(editId, countNumber) {
    var quantity1 = $('#quantity_' + editId + '_' + countNumber).val();
    var unitPrice1 = $('#unitPrice_' + editId + '_' + countNumber).val();
    var totalPrice1 = $('#totalPrice_' + editId + '_' + countNumber).val();
    var productId = $('#productId_' + editId + '_' + countNumber).val();
    var brandId = $('#brandId_' + editId + '_' + countNumber).val();
    var gradeId = $('#gradeId_' + editId + '_' + countNumber).val();
    var gsm = $('#gsm_' + editId + '_' + countNumber).val();
    var editRowId = $('#editRowId').val();
    var quantity = parseFloat(quantity1).toFixed(2);
    var unitPrice = parseFloat(unitPrice1).toFixed(2);
    var totalPrice = parseFloat(totalPrice1).toFixed(2);
    var buyerId = $('#buyerId').val();
    var salespersonsId = $('#salespersonsId').val();
    //ajax call product wise brand
    $.ajax({
    url: "{{ URL::to('lead/getLeadBrand')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#brandId').html(res.html);
            $('#brandId').val(brandId).select2();
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax


    //Get Product && Brand wise Grade

    $.ajax({
    url: "{{ URL::to('lead/getLeadGrade')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    brand_id: brandId,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#showGrade').html(res.html);
            $('#gradeId').val(gradeId).select2();
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    //Product priceing
    $.ajax({
    url: "{{ URL::to('lead/getProductPricing')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId,
                    brand_id: brandId,
                    grade_id: gradeId,
                    unit_price: unitPrice,
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            $('#product-pricing').html(res.html);
            $('.tooltips').tooltip();
            $(".js-source-states").select2({dropdownParent: $('body')});
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    $.ajax({
    url: "{{ URL::to('lead/getLeadProductUnit')}}",
            type: "POST",
            dataType: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            product_id: productId
            },
            beforeSend: function () {
            App.blockUI({boxed: true});
            },
            success: function (res) {
            var unit = res.measureUnitName;
            var perUnit = res.perMeasureUnitName;
            $("span#quantityUnit").text(unit);
            $("span#unitPricePerUnit").text(perUnit);
            App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            App.unblockUI();
            }
    }); //ajax

    //end of ajax call

    if (gradeId == ''){
    gradeId = 0;
    }
    $('#gsm').val(gsm);
    $('#quantity').val(quantity);
    $('#unitPrice').val(unitPrice);
    $('#totalPrice').val(totalPrice);
    $('#productId').val(productId).select2();
    $('#brandId').val(brandId).select2();
    $('#gradeId').val(gradeId).select2();
    $("#editRowId").val(editId + '_' + countNumber);
    $('#editBtn' + editId + '_' + countNumber).attr('disabled', true);
    $('#deleteBtn' + editId + '_' + countNumber).attr('disabled', true);
    if (editRowId != '') {
    $('#editBtn' + editRowId).prop('disabled', true);
    $('#deleteBtn' + editRowId).prop('disabled', true);
    }
    }

    //*********************END OF EDIT ITEM ******************
</script>
@stop