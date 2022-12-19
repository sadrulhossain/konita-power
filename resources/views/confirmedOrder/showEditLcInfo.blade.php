
<div class="modal-content modal-lg">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.EDIT_LC_INFO')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

        </div>
    </div>
    <div class="modal-body">
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BASIC_INQUIRY_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.BUYER_NAME')</td>
                                <td width="50%">{!! !empty($inquiry->buyerName)?$inquiry->buyerName:'' !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.SALES_PERSON')</td>
                                <td width="50%">{!! !empty($inquiry->salesPersonName)?$inquiry->salesPersonName:'' !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.CREATION_DATE')</td>
                                <td width="50%">
                                    {!! !empty($inquiry->creation_date)?Helper::formatDate($inquiry->creation_date):'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($inquiry->order_status == '2')
                                    <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                    @elseif($inquiry->order_status == '3')
                                    <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="row padding-2 margin-top-15">
            {!! Form::open(array('group' => 'form', 'url' => '','id' => 'editLcInfoFrom', 'class' => 'form-horizontal','files' => true)) !!}
            {{csrf_field()}}
            {!! Form::hidden('inquiry_id', $target->id) !!}
            <!--form part 1-->
            <div class="col-md-6 col-lg-6 col-sm-6 form-body confirm-order-border">
                <div class="form-group">
                    <label class="control-label col-md-5" for="poNo">@lang('label.PO_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        {!! Form::text('purchase_order_no',!empty($target->purchase_order_no)?$target->purchase_order_no:null, ['id'=> 'poNo', 'class' => 'form-control']) !!} 
                        <span class="text-danger">{{ $errors->first('purchase_order_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="poDate">@lang('label.PO_DATE') :</label>
                    <div class="col-md-7 bold margin-top-8">
                        {!! !empty($target->po_date)?Helper::formatDate($target->po_date):__('label.N_A') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="orderNo">@lang('label.ORDER_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        {!! Form::text('order_no', !empty($target->order_no)?$target->order_no:null, ['id'=> 'orderNo', 'class' => 'form-control tooltips', 'autocomplete' => 'off']) !!}  
                        <span class="text-danger">{{ $errors->first('order_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="piDate">@lang('label.PI_DATE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        <div class="input-group date datepicker2">
                            {!! Form::text('pi_date', !empty($target->pi_date)?Helper::formatDate($target->pi_date):null, ['id'=> 'piDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="piDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('lc_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="supplierId">@lang('label.SUPPLIER') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        {!! Form::select('supplier_id', $supplierList, !empty($target->supplier_id) ? $target->supplier_id : null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                        <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcNo">@lang('label.LC_NO') :</label>
                    <div class="col-md-7">
                        {!! Form::text('lc_no', !empty($target->lc_no) ? $target->lc_no : null, ['id'=> 'lcNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                        <span class="text-danger">{{ $errors->first('lc_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcDate">@lang('label.LC_DATE') :</label>
                    <div class="col-md-7">
                        <div class="input-group date datepicker2">
                            {!! Form::text('lc_date', !empty($target->lc_date)?Helper::formatDate($target->lc_date):null, ['id'=> 'lcDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="lcDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('lc_date') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--form part 2-->
            <div class="col-md-6 col-lg-6 col-sm-6 form-body">

                <div class="form-group">
                    <label class="control-label col-md-5" for="note">@lang('label.NOTE_') :</label>
                    <div class="col-md-7">
                        {{ Form::textarea('note', !empty($target->note) ? $target->note : null, ['id'=> 'note', 'class' => 'form-control','size' => '20x3']) }}
                        <span class="text-danger">{{ $errors->first('note') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcTransmittedCopyDone">@lang('label.LC_TRANSMITTED_COPY_DONE') :</label>
                    @if($target->lc_transmitted_copy_done == '1')
                    <div class="col-md-7 margin-top-10">
                        <span class="label label-sm label-success">@lang('label.YES')</span>
                        {!! Form::hidden('lc_transmitted_copy_done', '1') !!}
                    </div>
                    @else
                    <div class="col-md-7 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('lc_transmitted_copy_done',1,!empty($target->lc_transmitted_copy_done) ? $target->lc_transmitted_copy_done : null, ['id' => 'lcTransmittedCopyDone', 'class'=> 'md-check']) !!}
                        <label for="lcTransmittedCopyDone">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_TRANSMITTED_COPY_DONE')</span>
                    </div>
                    @endif
                </div>
                <div class="col-md-12"  id="show">
                    <div class="form-group">
                        <label class="control-label col-md-5" for="lcIssueDate">@lang('label.LC_ISSUE_DATE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <div class="input-group date datepicker2">
                                {!! Form::text('lc_issue_date', date('d F Y'), ['id'=> 'lcIssueDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="lcIssueDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <div>
                                <span class="text-danger">{{ $errors->first('lc_issue_date') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5" for="bank">@lang('label.BANK') :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            {!! Form::select('bank', $bankList, !empty($target->bank) ? $target->bank : null, ['class' => 'form-control js-source-states', 'id' => 'bank']) !!}
                            <span class="text-danger">{{ $errors->first('bank') }}</span>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="control-label col-md-5" for="branch">@lang('label.BRANCH') :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            {!! Form::text('branch', !empty($target->branch) ? $target->branch : null, ['id'=> 'branch', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                            <span class="text-danger">{{ $errors->first('branch') }}</span>
                        </div>
                    </div>
                    <!--lsd code starts here -->
                    <div class="row margin-top-20">
                        <div class="col-md-12 ">
                            @if(!empty($previousLsdInfoArr))
                            <?php
                            $lsdCounter = 0;
                            $i = 1;
                            ?>
                            @foreach($previousLsdInfoArr as $lsdKey => $lsdInfo)
                            @if($i == '1')
                            <div class="border-styel  margin-bottom-10">
                                <div class="row">
                                    <div class="col-md-10 form-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 tooltips" title="Latest Shipment Date" for="lsd_{{$lsdKey}}">@lang('label.LSD') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8 margin-bottom-10">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lsd['.$lsdKey.']', $lsdInfo['lsd'], ['id'=> 'lsd_'.$lsdKey, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lsd_{{$lsdKey}}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="lc_expiry_date_{{$lsdKey}}">@lang('label.LC_EXPIRY_DATE') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lc_expiry_date['.$lsdKey.']',$lsdInfo['lc_expiry_date'], ['id'=> 'lc_expiry_date_'.$lsdKey, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lc_expiry_date_{{$lsdKey}}">
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
                                    <div class="col-md-2">
                                        @if($lsdCounter == 0)
                                        <button class="btn btn-inline green-haze add-lsd-info lsd-row-icon tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_LC_DATE_INFO')" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-inline btn-danger remove-lsd-row lsd-row-icon tooltips"  title="Remove" type="button">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="border-styel  margin-bottom-10">
                                <div class="row">
                                    <div class="col-md-10 form-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 tooltips" title="Latest Shipment Date" for="lsd_{{$lsdKey}}">@lang('label.REVISED_LSD') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8 margin-bottom-10">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lsd['.$lsdKey.']', $lsdInfo['lsd'], ['id'=> 'lsd_'.$lsdKey, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lsd_{{$lsdKey}}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="lc_expiry_date_{{$lsdKey}}">@lang('label.LC_EXPIRY_DATE') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lc_expiry_date['.$lsdKey.']',$lsdInfo['lc_expiry_date'], ['id'=> 'lc_expiry_date_'.$lsdKey, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lc_expiry_date_{{$lsdKey}}">
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
                                    <div class="col-md-2">
                                        @if($lsdCounter == 0)
                                        <button class="btn btn-inline green-haze add-lsd-info lsd-row-icon tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_LC_DATE_INFO')" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-inline btn-danger remove-lsd-row lsd-row-icon tooltips"  title="Remove" type="button">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <?php
                            $lsdCounter++;
                            $i++;
                            ?>
                            @endforeach
                            @else
                            <div class="border-styel  margin-bottom-10">
                                <?php $v3 = 'a' . uniqid() ?>
                                <div class="row">
                                    <div class="col-md-10 form-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 tooltips" title="Latest Shipment Date" for="lsd_{{$v3}}">@lang('label.LSD') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8 margin-bottom-10">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lsd['.$v3.']', date('d F Y'), ['id'=> 'lsd_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lsd_{{$v3}}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="lc_expiry_date_{{$v3}}">@lang('label.LC_EXPIRY_DATE') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                <div class="input-group date datepicker2">
                                                    {!! Form::text('lc_expiry_date['.$v3.']', date('d F Y'), ['id'=> 'lc_expiry_date_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="lc_expiry_date_{{$v3}}">
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
                                    <div class="col-md-2">
                                        <button class="btn btn-inline green-haze add-lsd-info lsd-row-icon tooltips" id="lsdNewRowRemoveIcon" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_LC_DATE_INFO')" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div id="addLsdRow"></div>
                        </div>
                    </div>
                    <!--LSD code ends here -->
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <!--product details-->
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="vcenter">@lang('label.GRADE')</th>
                                <th class="vcenter">@lang('label.GSM')</th>
                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$inquiryDetails->isEmpty())
                            @foreach($inquiryDetails as $item)
                            <?php
                            $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                            $perUnit = !empty($item->unit_name) ? ' / ' . $item->unit_name : '';
                            ?>
                            <tr>
                                <td class="vcenter">{{ !empty($item->product_name)?$item->product_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->brand_name)?$item->brand_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->grade_name)?$item->grade_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->gsm)?$item->gsm:'' }}</td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->quantity) ? $item->quantity . $unit : __('label.N_A') }}
                                </td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->unit_price) ? '$' . $item->unit_price . $perUnit : __('label.N_A') }}
                                </td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->total_price) ? '$' . $item->total_price : __('label.N_A') }}
                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($userAccessArr[26][12]))
        <button class="btn green-seagreen update-lc-info tooltips vcenter">
            <i class="fa fa-check"></i>&nbsp;@lang('label.SUBMIT')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

//cancel order
    $('.update-lc-info').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Confirm",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null,
                };

                // data
                var formData = new FormData($("#editLcInfoFrom")[0]);
                $.ajax({
                    url: "{{URL::to('confirmedOrder/update')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('.update-lc-info').prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
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
                        $('.update-lc-info').prop('disabled', false);
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
//add new lsd row
<?php if ($target->lc_transmitted_copy_done == '0') { ?>
        $("#show").hide();
<?php }
?>

    $("#lcTransmittedCopyDone").click(function () {
        if ($(this).is(":checked")) {
            $("#show").show();
            $(".js-source-states").select2({dropdownParent: $('body')});
        } else {
            $("#show").hide();
        }
    });

    //remove lsd row
    $('.remove-lsd-row').on('click', function () {
        $(this).parent().parent().parent().remove();
        return false;
    });

});
</script>