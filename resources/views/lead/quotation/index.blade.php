@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-calculator"></i>
                @lang('label.SET_QUOTATION')
            </div>

            <div class="actions">
                <span class="text-right">
                    <a href="{{ URL::to('/lead' . Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                        <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                    </a>
                    @if(!empty($quotationInfo))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'?view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <a class="btn btn-sm btn-inline green-seagreen tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                </span>
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'setQuotationForm','class' => 'form-horizontal','files' => true)) !!}
            {!! Form::hidden('inquiry_id', $target->id) !!}
            {!! Form::hidden('quotation_id', !empty($quotationInfo->id)?$quotationInfo->id:null) !!}
            <div class="row">
                <div class="col-md-12 form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <span> 
                                    <img src="{{URL::to('/')}}/public/img/konita_small_logo.png" style="width: 300px; height: 80px;">
                                </span>
                            </div>
                            <div class="col-md-6 text-right">
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.',':''}}</span>
                                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                            </div>
                        </div>
                        <!--End of Header-->
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12 text-center">
                            <span class="bold uppercase inv-border-bottom">@lang('label.QUOTATION')</span>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td rowspan="3" width="56%">
                                                <div class="row margin-bottom-10">
                                                    <div class="col-md-12">
                                                        <span class="bold">@lang('label.QUOTATION_FOR')</span>
                                                    </div>
                                                </div>
                                                <div class="row margin-bottom-1">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.COMPANY_NAME') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $target->buyer_name ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row margin-bottom-1">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.OFFICE_ADDRESS') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>{!! $target->office_address ?? __('label.N_A') !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row form-group margin-top-10">

                                                    <label class="col-md-3" for="attentionId">@lang('label.ATTENTION') :</label>
                                                    <div class="col-md-6">
                                                        {!! Form::select('attention_id', $attentionList, $quotationInfo->attention_id ?? null, ['class' => 'form-control js-source-states', 'id' => 'attentionId']) !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="vcenter" width="44%">
                                                <?php
                                                $quotationDate = !empty($quotationInfo->quotation_date) ? Helper::formatDate($quotationInfo->quotation_date) : date('d F Y');
                                                $quotationValidTill = !empty($quotationInfo->quotation_valid_till) ? Helper::formatDate($quotationInfo->quotation_valid_till) : null;
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-md-5 margin-top-10" for="quotationDate">@lang('label.DATE') :<span class="text-danger">*</span></label>
                                                    <div class="col-md-7 width-250">
                                                        <div class="input-group date datepicker2 width-inherit">
                                                            {!! Form::text('quotation_date', $quotationDate, ['id'=> 'quotationDate', 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'style' => 'width: 150px']) !!} 
                                                            <span class="input-group-btn">
                                                                <button class="btn default reset-date" type="button" remove="quotationDate">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                                <button class="btn default date-set" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vcenter" width="44%">
                                                <div class="form-group">
                                                    <label class="col-md-5 margin-top-10" for="quotationNo">@lang('label.QUOTATION_NO') :<span class="text-danger">*</span></label>
                                                    <div class="col-md-7 width-250">
                                                        {!! Form::text('quotation_no', $quotationNo, ['id'=> 'quotationNo', 'class' => 'form-control width-inherit', 'readonly']) !!} 
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vcenter" width="44%">
                                                <div class="form-group">
                                                    <label class="col-md-5 margin-top-10" for="quotationValidTill">@lang('label.QUOTATION_VALID_TILL') :<span class="text-danger">*</span></label>
                                                    <div class="col-md-7 width-250">
                                                        <div class="input-group date datepicker2 width-inherit">
                                                            {!! Form::text('quotation_valid_till', $quotationValidTill, ['id'=> 'quotationValidTill', 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'style' => 'width: 150px']) !!} 
                                                            <span class="input-group-btn">
                                                                <button class="btn default reset-date" type="button" remove="quotationValidTill">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                                <button class="btn default date-set" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="vcenter">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.SALES_PERSON') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $target->sales_person_name ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="vcenter">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.DESIGNATION') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $target->designation ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vcenter">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.EMAIL') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $target->email ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="vcenter">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.CONTACT_NO') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $target->contact_no ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.PRODUCT_DESCRIPTION')</th>
                                            <th class="text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.GRADE')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.GSM')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.QUANTITY')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.UNIT_PRICE')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.TOTAL_PRICE')</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center vcenter">@lang('label.LOGO')</th>
                                            <th class="text-center vcenter">@lang('label.NAME')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$inquiryDetailsArr->isEmpty())
                                        <?php
                                        $sl = 0;
                                        ?>
                                        @foreach($inquiryDetailsArr as $item)
                                        <?php
                                        $grade = !empty($item->grade_name) ? ' : ' . $item->grade_name : '';
                                        $brand = !empty($item->brand_name) ? ' (' . $item->brand_name . $grade . ')' : '';
                                        $product = !empty($item->product_name) ? $item->product_name . $brand : '';
                                        ?>
                                        {!! Form::hidden('item['.$item->id.']', $product) !!}
                                        <tr>
                                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                                            <td class="text-center vcenter">{!! $item->product_name ?? __('label.N_A') !!}</td>
                                            <td class="text-center vcenter" width="40px">
                                                @if(!empty($item->logo) && File::exists('public/uploads/brand/' . $item->logo))
                                                <img class="pictogram-min-space tooltips" width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{ $item->logo }}" alt="{{ $item->brand_name}}"/>
                                                @else 
                                                <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $item->brand_name}}"/>
                                                @endif
                                            </td>
                                            <td class="text-center vcenter">{!! $item->brand_name ?? __('label.N_A') !!}</td>
                                            <td class="text-center vcenter">{!! $item->grade_name ?? __('label.N_A') !!}</td>
                                            <td class="text-center vcenter">{!! $item->country_of_origin ?? __('label.N_A') !!}</td>
                                            <td class="text-center vcenter width-150">
                                                {!! !empty($item->gsm) ? $item->gsm : __('label.N_A') !!}
                                            </td>
                                            {!! Form::hidden('gsm['.$item->id.']', !empty($item->gsm) ? $item->gsm : null, ['id'=> 'gsm_'.$item->id, 'class' => 'form-control width-inherit', 'style' => 'width: 150px']) !!} 
                                            <td class="text-right vcenter">{!! (!empty($item->quantity) ? Helper::numberFormat2Digit($item->quantity) : Helper::numberFormat2Digit(0)).(!empty($item->unit_name) ? ' /'.$item->unit_name : '') !!}</td>
                                            <td class="text-right vcenter">${!! !empty($item->unit_price) ? Helper::numberFormat2Digit($item->unit_price) : Helper::numberFormat2Digit(0) !!}</td>
                                            <td class="text-right vcenter">${!! !empty($item->total_price) ? Helper::numberFormat2Digit($item->total_price) : Helper::numberFormat2Digit(0) !!}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="vcenter bold text-right" colspan="9">@lang('label.SUBTOTAL')</td>
                                            <td class="vcenter bold text-right">${!! !empty($subtotal) ? Helper::numberFormat2Digit($subtotal) : Helper::numberFormat2Digit(0) !!}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="vcenter text-danger" colspan="20">@lang('label.NO_DATA_FOUND')</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                                    <tbody>
                                        <tr>
                                            <th class="text-center vcenter">@lang('label.PAYMENT_TERMS') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.SHIPPING_TERMS') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.PORT_OF_LOADING') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.PORT_OF_DISCHARGE') <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <td class="text-center vcenter width-300">
                                                {!! Form::select('payment_term_id', $paymentTermList, $quotationInfo->payment_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'id' => 'paymentTermId']) !!}
                                            </td>
                                            <td class="text-center vcenter width-300">
                                                {!! Form::select('shipping_term_id', $shippingTermList, $quotationInfo->shipping_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'id' => 'shippingTermId']) !!}
                                            </td>
                                            <td class="text-center vcenter width-300">
                                                {!! Form::text('port_of_loading', $quotationInfo->port_of_loading ?? null, ['id'=> 'portOfLoading', 'class' => 'form-control width-inherit' ]) !!}
                                            </td>
                                            <td class="text-center vcenter width-300">
                                                {!! Form::text('port_of_discharge', $quotationInfo->port_of_discharge ?? null, ['id'=> 'portOfDischarge', 'class' => 'form-control width-inherit']) !!}
                                            </td>
                                        </tr>
                                    </tbody> 
                                </table>
                            </div>
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover"> 
                                    <tbody>   
                                        <tr>
                                            <th class="text-center vcenter">@lang('label.TOTAL_LEAD_TIME') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.CARRIER') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.ESTIMATED_SHIPMENT_DATE') <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <td class="text-center vcenter width-400">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('total_lead_time', $quotationInfo->total_lead_time ?? null, ['id'=> 'totalLeadTime', 'class' => 'form-control text-right integer-only text-input-width-100-per']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-postfix">@lang('label.DAY_S')</span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-400">
                                                {!! Form::select('pre_carrier_id', $preCarrierList, $quotationInfo->pre_carrier_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'id' => 'preCarrierId']) !!}
                                            </td>
                                            <td class="text-center vcenter width-400">
                                                <?php
                                                $estimatedShipmentDate = !empty($quotationInfo->estimated_shipment_date) ? Helper::formatDate($quotationInfo->estimated_shipment_date) : null;
                                                ?>
                                                <div class="input-group date datepicker2 width-inherit">
                                                    {!! Form::text('estimated_shipment_date', $estimatedShipmentDate, ['id'=> 'estimatedShipmentDate', 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="estimatedShipmentDate">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12" for="note">@lang('label.ADDITIONAL_NOTES') :</label>
                                <div class="col-md-12">
                                    {{ Form::textarea('note', $quotationInfo->note ?? null, ['id' => 'note', 'class' => 'form-control summer-note', 'size' =>'30x5']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-10">
                        <div class="col-md-12">
                            <div class="md-checkbox has-success">
                                <?php $removeTotal = !empty($quotationInfo->remove_total) && $quotationInfo->remove_total == '1' ? true : false; ?>
                                {!! Form::checkbox('remove_total',1,$removeTotal, ['id' => 'removeTotal', 'class'=> 'md-check remove-total-check']) !!}
                                <label for="removeTotal">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span class="text-success">@lang('label.REMOVE_TOTAL_PRICE_COLUMN_FOR_PRINTING_AND_PDF_DOWNLOAD')</span>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-20">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-inline green submit-quotation" type="button" data-status="1">
                                <i class="fa fa-check"></i> @lang('label.SAVE')
                            </button>
                            <a class="btn btn-inline btn-default tooltips" href="{{URL::to('/lead' . Helper::queryPageStr($qpArr))}}" title="@lang('label.CANCEL')"> @lang('label.CANCEL')</a>
                            @if(!empty($quotationInfo))
                            <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'?view=print') }}"  title="@lang('label.PRINT')">
                                <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
                            </a>
                            <a class="btn btn-inline green-seagreen tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                                <i class="fa fa-file-pdf-o"></i>&nbsp;@lang('label.PDF')
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>	
</div>
<!-- Modal start -->
<!--preview modal start-->

<!--preview modal End-->
<!-- Modal end-->

<script type="text/javascript">
    $(function () {

        $('.summer-note').summernote({
            height: 100, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true      // set focus to editable area after initializing summernote
        });



        //After Click to Save new po generate
        $(document).on("click", ".submit-quotation", function (e) {
            e.preventDefault();

            var status = $(this).attr('data-status');

            var formData = new FormData($('#setQuotationForm')[0]);
            formData.append('status', status);

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
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('lead/quotationSave')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
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
        // EOF Function for set po generate

    });
</script>
@stop
