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
                    <a href="{{ URL::to('/crmMyOpportunity' . Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
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
            {!! Form::hidden('opportunity_id', $target->id) !!}
            {!! Form::hidden('quotation_id', !empty($quotationInfo->id)?$quotationInfo->id:null) !!}
            {!! Form::hidden('buyer_id', $target->buyer,['id' => 'buyerId']) !!}
            {!! Form::hidden('sales_person_id', $target->created_by, ['id' => 'salesPersonId']) !!}
            {!! Form::hidden('sales_person_group_id', $generator->group_id, ['id' => 'salesPersonGroupId']) !!}
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
                                                        <?php
                                                        if ($target->buyer_has_id == '0') {
                                                            $buyer = $target->buyer;
                                                        } elseif ($target->buyer_has_id == '1') {
                                                            $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                                                        }
                                                        ?>
                                                        <span>
                                                            {!! $buyer ?? __('label.N_A') !!}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row margin-bottom-1">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.ADDRESS') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>{!! $target->address ?? __('label.N_A') !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row form-group margin-top-10">

                                                    <label class="col-md-3" for="attentionId">@lang('label.ATTENTION') :</label>
                                                    <div class="col-md-6">
                                                        {!! Form::select('attention_id', $attentionList, $quotationInfo->attention_id ?? null, ['class' => 'form-control js-source-states', 'id' => 'attentionId']) !!}
                                                    </div>
                                                    <!--                                                    <div class="col-md-2">
                                                                                                            <button type="button" class="btn blue add-contact"  id="" data-opportunity-id="{{ $target->id}}">
                                                                                                                @lang('label.ADD_ATTENTION')
                                                                                                            </button>
                                                                                                        </div>-->
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
                    <div id="contactForm"></div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="vcenter">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>@lang('label.RESPONSIBLE_AGENT') :</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <span>
                                                            {!! $responsibleAgent->name ?? __('label.N_A') !!}
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
                                                            {!! $responsibleAgent->designation ?? __('label.N_A') !!}
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
                                                            {!! $responsibleAgent->email ?? __('label.N_A') !!}
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
                                                            {!! $responsibleAgent->contact_no ?? __('label.N_A') !!}
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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                            <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                            <th class="text-center vcenter">@lang('label.BRAND')</th>
                                            <th class="text-center vcenter">@lang('label.GRADE')</th>
                                            <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                            <th class="text-center vcenter">@lang('label.GSM')</th>
                                            <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                            <th class="text-center vcenter">@lang('label.UNIT')</th>
                                            <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                            <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                            <th class="text-center vcenter"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $v4 = 'p' . uniqid();
                                        $productNameArr = $brandNameArr = $gradeNameArr = [];
                                        ?>
                                        @if(!empty($productDataList))
                                        <?php
                                        $pCounter = 0;
                                        $pSl = 0;
                                        ?>
                                        @foreach($productDataList as $pKey => $pInfo)
                                        <?php
                                        //product
                                        if (empty($pInfo['product_has_id']) || $pInfo['product_has_id'] == '0') {
                                            $productId = null;
                                            $productName = $pInfo['product'] ?? null;
                                            $productSelectBtnDisplay = '';
                                            $productSelectDisplay = 'display-none';
                                            $productTextDisplay = '';
                                            $productTextBtnDisplay = 'display-none';
                                        } elseif ($pInfo['product_has_id'] == '1') {
                                            $productId = $pInfo['product'] ?? null;
                                            $productNameArr[$pKey] = $productList[$productId] ?? '';
                                            $productName = null;
                                            $productSelectBtnDisplay = 'display-none';
                                            $productSelectDisplay = '';
                                            $productTextDisplay = 'display-none';
                                            $productTextBtnDisplay = '';
                                        }

                                        //brand
                                        if (empty($pInfo['brand_has_id']) || $pInfo['brand_has_id'] == '0') {
                                            $brandId = null;
                                            $brandName = $pInfo['brand'] ?? null;
                                            $brandSelectBtnDisplay = '';
                                            $brandSelectDisplay = 'display-none';
                                            $brandTextDisplay = '';
                                            $brandTextBtnDisplay = 'display-none';
                                        } elseif ($pInfo['brand_has_id'] == '1') {
                                            $brandId = $pInfo['brand'] ?? null;
                                            $brandNameArr[$pKey] = $brandList[$productId][$brandId] ?? '';
                                            $brandName = null;
                                            $brandSelectBtnDisplay = 'display-none';
                                            $brandSelectDisplay = '';
                                            $brandTextDisplay = 'display-none';
                                            $brandTextBtnDisplay = '';
                                        }

                                        //grade
                                        if (empty($pInfo['grade_has_id']) || $pInfo['grade_has_id'] == '0') {
                                            $gradeId = null;
                                            $gradeName = $pInfo['grade'] ?? null;
                                            $gradeSelectBtnDisplay = '';
                                            $gradeSelectDisplay = 'display-none';
                                            $gradeTextDisplay = '';
                                            $gradeTextBtnDisplay = 'display-none';
                                        } elseif ($pInfo['grade_has_id'] == '1') {
                                            $gradeId = $pInfo['grade'] ?? null;
                                            $gradeNameArr[$pKey] = $gradeList[$productId][$brandId][$gradeId] ?? '';
                                            $gradeName = null;
                                            $gradeSelectBtnDisplay = 'display-none';
                                            $gradeSelectDisplay = '';
                                            $gradeTextDisplay = 'display-none';
                                            $gradeTextBtnDisplay = '';
                                        }

                                        //Origin
                                        if (!empty($pInfo['origin'])) {
                                            $origin = $pInfo['origin'];
                                        } else {
                                            $origin = null;
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center vcenter initial-product-sl width-50">{!! ++$pSl !!}</td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="product-select-span">
                                                        {!! Form::select('product['.$pKey.'][product_id]', $productList, $productId, ['id'=> 'productId_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control selected-product js-source-states product-item']) !!}
                                                    </span>
                                                    <!--                                                    {!! Form::text('product['.$pKey.'][product_name]', $productName, ['id'=> 'productProductName_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control product-text ' . $productTextDisplay,'autocomplete' => 'off']) !!} 
                                                                                                        <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                                                            <button class="btn btn-sm blue-steel product-text-btn product-text-btn-{{$pKey}} bold tooltips {{$productTextBtnDisplay}}" data-key="{{$pKey}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                                                                <i class="fa fa-text-height bold"></i> 
                                                                                                            </button>
                                                                                                            <button class="btn btn-sm blue-steel product-select-btn product-select-btn-{{$pKey}} bold tooltips {{$productSelectBtnDisplay}}"  data-key="{{$pKey}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                                                                <i class="fa fa-angle-down bold"></i>
                                                                                                            </button>
                                                                                                        </span>-->
                                                </div>
                                                {!! Form::hidden('product['.$pKey.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="productWiseBrandId_{{$pKey}}">
                                                    <span class="brand-select-span">
                                                        {!! Form::select('product['.$pKey.'][brand_id]', ['0' => __('label.SELECT_BRAND_OPT')] + (!empty($brandList[$productId]) ? $brandList[$productId] : []), $brandId, ['id'=> 'productBrandId_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control brand-select js-source-states brand-item']) !!}
                                                    </span>
                                                    <!--                                                    {!! Form::text('product['.$pKey.'][brand_name]',$brandName, ['id'=> 'productBrandName_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control brand-text ' . $brandTextDisplay,'autocomplete' => 'off']) !!} 
                                                                                                        <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                                                            <button class="btn btn-sm blue-steel brand-text-btn brand-text-btn-{{$pKey}} bold tooltips {{$brandTextBtnDisplay}}" data-key="{{$pKey}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                                                                <i class="fa fa-text-height bold"></i> 
                                                                                                            </button>
                                                                                                            <button class="btn btn-sm blue-steel brand-select-btn brand-select-btn-{{$pKey}} bold tooltips {{$brandSelectBtnDisplay}}"  data-key="{{$pKey}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                                                                <i class="fa fa-angle-down bold"></i>
                                                                                                            </button>
                                                                                                        </span>-->
                                                </div>
                                                {!! Form::hidden('product['.$pKey.'][brand_has_id]', '1', ['class' => 'brand-has-id', 'id' => 'brandHasId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="brandWiseGrade_{{$pKey}}">
                                                    <span class="grade-select-span">
                                                        {!! Form::select('product['.$pKey.'][grade_id]', ['0' => __('label.SELECT_GRADE_OPT')] + (!empty($gradeList[$productId][$brandId]) ? $gradeList[$productId][$brandId] : []), $gradeId, ['id'=> 'productGradeId_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control grade-select js-source-states grade-item']) !!}
                                                    </span>
                                                    <!--                                                    {!! Form::text('product['.$pKey.'][grade_name]',$gradeName, ['id'=> 'productGradeName_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control grade-text ' . $gradeTextDisplay,'autocomplete' => 'off']) !!} 
                                                                                                        <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                                                            <button class="btn btn-sm blue-steel grade-text-btn grade-text-btn-{{$pKey}} bold tooltips {{$gradeTextBtnDisplay}}" data-key="{{$pKey}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                                                                <i class="fa fa-text-height bold"></i> 
                                                                                                            </button>
                                                                                                            <button class="btn btn-sm blue-steel grade-select-btn grade-select-btn-{{$pKey}} bold tooltips {{$gradeSelectBtnDisplay}}"  data-key="{{$pKey}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                                                                <i class="fa fa-angle-down bold"></i>
                                                                                                            </button>
                                                                                                        </span>-->
                                                </div>
                                                {!! Form::hidden('product['.$pKey.'][grade_has_id]', '1', ['class' => 'grade-has-id', 'id' => 'gradeHasId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span id="brandWiseOrigin_{{$pKey}}">
                                                        {!! !empty($countryList[$origin]) ? $countryList[$origin] : '' !!}
                                                        <!--{!! Form::select('product['.$pKey.'][origin]', $countryList, $origin, ['id'=> 'productOrigin_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control js-source-states']) !!}-->
                                                    </span>
                                                </div>
                                            </td>
                                            {!! Form::hidden('product['.$pKey.'][origin]',!empty($origin) ? $origin : null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4]) !!}
                                            <td class="text-center vcenter width-100">
                                                {!! Form::text('product['.$pKey.'][gsm]', $pInfo['gsm'] ?? null, ['id'=> 'productGsm_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control width-inherit product-gsm']) !!}
                                            </td>
                                            <td class="text-center vcenter width-100">
                                                {!! Form::text('product['.$pKey.'][quantity]', $pInfo['quantity'] ?? null, ['id'=> 'productQuantity_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
                                            </td>
                                            <td class="text-center vcenter width-80">
                                                {!! Form::text('product['.$pKey.'][unit]', $pInfo['unit'] ?? null, ['id'=> 'productUnit_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control width-inherit product-unit','readonly']) !!}
                                            </td>
                                            <td class="text-center vcenter width-180">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('product['.$pKey.'][unit_price]', $pInfo['unit_price'] ?? null, ['id'=> 'productUnitPrice_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-unit-price']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold product-per-unit-{{$pKey}}">{!! !empty($pInfo['unit']) ? '/ '. $pInfo['unit'] : '' !!}</span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('product['.$pKey.'][total_price]', $pInfo['total_price'] ?? null, ['id'=> 'productTotalPrice_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-50">
                                                @if($pCounter == 0)
                                                <button class="btn btn-inline green-haze add-new-product-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_PRODUCT_INFO')" type="button">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                @else
                                                <button class="btn btn-inline btn-danger remove-product-row tooltips" data-key="{{$pKey}}" title="Remove" type="button">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $pCounter++; ?>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center vcenter initial-product-sl width-50">1</td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="product-select-span">
                                                        {!! Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control selected-product js-source-states product-item']) !!}
                                                    </span>
                                                    <!--            {!! Form::text('product['.$v4.'][product_name]',null, ['id'=> 'productProductName_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-text display-none','autocomplete' => 'off']) !!} 
                                                                <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                    <button class="btn btn-sm blue-steel product-text-btn product-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                        <i class="fa fa-text-height bold"></i> 
                                                                    </button>
                                                                    <button class="btn btn-sm blue-steel product-select-btn product-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                        <i class="fa fa-angle-down bold"></i>
                                                                    </button>
                                                                </span>
                                                            </div>-->
                                                    {!! Form::hidden('product['.$v4.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="productWiseBrandId_{{$v4}}">
                                                    <span class="brand-select-span">
                                                        {!! Form::select('product['.$v4.'][brand_id]', $brandList, null, ['id'=> 'productBrandId_'.$v4, 'data-key' => $v4, 'class' => 'form-control selected-brand js-source-states brand-item']) !!}
                                                    </span>
                                                    <!--            {!! Form::text('product['.$v4.'][brand_name]',null, ['id'=> 'productBrandName_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-text display-none','autocomplete' => 'off']) !!} 
                                                                <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                    <button class="btn btn-sm blue-steel brand-text-btn brand-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                        <i class="fa fa-text-height bold"></i> 
                                                                    </button>
                                                                    <button class="btn btn-sm blue-steel brand-select-btn brand-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                        <i class="fa fa-angle-down bold"></i>
                                                                    </button>
                                                                </span>-->
                                                </div>
                                                {!! Form::hidden('product['.$v4.'][brand_has_id]', '1', ['class' => 'brand-has-id', 'id' => 'brandHasId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="brandWiseGrade_{{$v4}}">
                                                    <span class="grade-select-span">
                                                        {!! Form::select('product['.$v4.'][grade_id]', $gradeList, null, ['id'=> 'productGradeId_'.$v4, 'data-key' => $v4, 'class' => 'form-control selected-grade js-source-states grade-item']) !!}
                                                    </span>
                                                    <!--            {!! Form::text('product['.$v4.'][grade_name]',null, ['id'=> 'productGradeName_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-text display-none','autocomplete' => 'off']) !!} 
                                                                <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                    <button class="btn btn-sm blue-steel grade-text-btn grade-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                        <i class="fa fa-text-height bold"></i> 
                                                                    </button>
                                                                    <button class="btn btn-sm blue-steel grade-select-btn grade-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                        <i class="fa fa-angle-down bold"></i>
                                                                    </button>
                                                                </span>-->
                                                </div>
                                                {!! Form::hidden('product['.$v4.'][grade_has_id]', '1', ['class' => 'grade-has-id', 'id' => 'gradeHasId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span id="brandWiseOrigin_{{$v4}}">
                                                        <!--{!! Form::select('product['.$v4.'][origin]', $countryList, null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4, 'class' => 'form-control js-source-states']) !!}-->
                                                    </span>
                                                </div>
                                            </td>
                                            {!! Form::hidden('product['.$v4.'][origin]',null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4]) !!}
                                            <td class="text-center vcenter width-100">
                                                {!! Form::text('product['.$v4.'][gsm]', null, ['id'=> 'productGsm_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-gsm']) !!}
                                            </td>
                                            <td class="text-center vcenter width-100">
                                                {!! Form::text('product['.$v4.'][quantity]', null, ['id'=> 'productQuantity_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
                                            </td>
                                            <td class="text-center vcenter width-80">
                                                {!! Form::text('product['.$v4.'][unit]', null, ['id'=> 'productUnit_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-unit','readonly']) !!}
                                            </td>
                                            <td class="text-center vcenter width-180">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('product['.$v4.'][unit_price]', null, ['id'=> 'productUnitPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-unit-price']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold product-per-unit-{{$v4}}"></span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('product['.$v4.'][total_price]', null, ['id'=> 'productTotalPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']) !!}
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-50">
                                                <button class="btn btn-inline green-haze add-new-product-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_PRODUCT_INFO')" type="button">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tbody id="newProductTbody"></tbody>
                                    <tbody>
                                        <tr>
                                            <td class="vcenter bold text-right" colspan="9">@lang('label.SUBTOTAL')</td>
                                            <td class="vcenter bold text-right sub-total">${!! !empty($subtotal) ? Helper::numberFormat2Digit($subtotal) : Helper::numberFormat2Digit(0) !!}</td>
                                            <td class="vcenter bold"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered"> 
                                    <thead>   
                                        <tr class="active">
                                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                            <th class="text-center vcenter">@lang('label.PAYMENT_TERMS') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.SHIPPING_TERMS') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.PORT_OF_LOADING') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.PORT_OF_DISCHARGE') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.TOTAL_LEAD_TIME') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.CARRIER') <span class="text-danger">*</span></th>
                                            <th class="text-center vcenter">@lang('label.ESTIMATED_SHIPMENT_DATE') <span class="text-danger">*</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($quotationTermArr))
                                        <?php $qsl = 0; ?>
                                        @foreach($quotationTermArr as $qkey => $qtInfo)
                                        <tr class="new-terms-tr-{{$qkey}}">
                                            <td class="text-center vcenter initial-term-sl width-50" rowspan="2">{{ ++$qsl }}</td>
                                            <td colspan="7" class="label-green-soft">
                                                @lang('label.PRODUCT_NAME'): <span class="product-name-{{$qkey}} bold">{!! !empty($productNameArr[$qkey]) ? $productNameArr[$qkey] : '' !!}</span> | 
                                                @lang('label.BRAND_NAME'): <span class="brand-name-{{$qkey}} bold">{!! !empty($brandNameArr[$qkey]) ? $brandNameArr[$qkey] : '' !!}</span> | 
                                                @lang('label.GRADE_NAME'): <span class="grade-name-{{ $qkey }} bold">{!! !empty($gradeNameArr[$qkey]) ? $gradeNameArr[$qkey] : '' !!}</span>
                                            </td>
                                        </tr>
                                        <tr class="new-terms-tr-{{$qkey}}">
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('payment_term_id['.$qkey.']', $paymentTermList, $qtInfo['payment_term_id'] ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $qkey, 'id' => 'paymentTermId_'.$qkey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('shipping_term_id['.$qkey.']', $shippingTermList, $qtInfo['shipping_term_id'] ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $qkey, 'id' => 'shippingTermId_'.$qkey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_loading['.$qkey.']', $qtInfo['port_of_loading'] ?? null, ['id'=> 'portOfLoading_'.$qkey, 'data-key' => $qkey, 'class' => 'form-control width-inherit' ]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_discharge['.$qkey.']', $qtInfo['port_of_discharge'] ?? null, ['id'=> 'portOfDischarge_'.$qkey, 'data-key' => $qkey, 'class' => 'form-control width-inherit']) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('total_lead_time['.$qkey.']', $qtInfo['total_lead_time'] ?? null, ['id'=> 'totalLeadTime_'.$qkey, 'data-key' => $qkey, 'class' => 'form-control text-right integer-only text-input-width-100-per']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-postfix">@lang('label.DAY_S')</span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('pre_carrier_id['.$qkey.']', $preCarrierList, $qtInfo['pre_carrier_id'] ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $qkey, 'id' => 'preCarrierId_'.$qkey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-250">
                                                <?php
                                                $estimatedShipmentDate = !empty($qtInfo['estimated_shipment_date']) ? Helper::formatDate($qtInfo['estimated_shipment_date']) : null;
                                                ?>
                                                <div class="input-group date datepicker2 width-inherit">
                                                    {!! Form::text('estimated_shipment_date['.$qkey.']', $estimatedShipmentDate, ['id'=> 'estimatedShipmentDate_'.$qkey, 'data-key' => $qkey, 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
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
                                        @endforeach
                                        @else
                                        @if(!empty($productDataList))
                                        <?php
                                        $qsl = 0;
                                        ?>
                                        @foreach($productDataList as $pKey => $pInfo)
                                        <?php
                                        //product
                                        if (empty($pInfo['product_has_id']) || $pInfo['product_has_id'] == '0') {
                                            $productId = null;
                                            $productTextBtnDisplay = 'display-none';
                                        } elseif ($pInfo['product_has_id'] == '1') {
                                            $productId = $pInfo['product'] ?? null;
                                        }

                                        //brand
                                        if (empty($pInfo['brand_has_id']) || $pInfo['brand_has_id'] == '0') {
                                            $brandId = null;
                                        } elseif ($pInfo['brand_has_id'] == '1') {
                                            $brandId = $pInfo['brand'] ?? null;
                                        }

                                        //grade
                                        if (empty($pInfo['grade_has_id']) || $pInfo['grade_has_id'] == '0') {
                                            $gradeId = null;
                                        } elseif ($pInfo['grade_has_id'] == '1') {
                                            $gradeId = $pInfo['grade'] ?? null;
                                        }
                                        ?>
                                        <tr class="new-terms-tr-{{$pKey}}">
                                            <td class="text-center vcenter initial-term-sl width-50" rowspan="2">{{ ++$qsl }}</td>
                                            <td colspan="7" class="label-green-soft">
                                                @lang('label.PRODUCT_NAME'): <span class="product-name-{{$pKey}} bold">{!! !empty($productList[$productId]) ? $productList[$productId] : '' !!}</span> | 
                                                @lang('label.BRAND_NAME'): <span class="brand-name-{{$pKey}} bold">{!! !empty($brandList[$productId][$brandId]) ? $brandList[$productId][$brandId] : '' !!}</span> | 
                                                @lang('label.GRADE_NAME'): <span class="grade-name-{{ $pKey }} bold">{!! !empty($gradeList[$productId][$brandId][$gradeId]) ? $gradeList[$productId][$brandId][$gradeId] : '' !!}</span>
                                            </td>
                                        </tr>
                                        <tr class="new-terms-tr-{{$pKey}}">
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('payment_term_id['.$pKey.']', $paymentTermList, $quotationInfo->payment_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $pKey, 'id' => 'paymentTermId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('shipping_term_id['.$pKey.']', $shippingTermList, $quotationInfo->shipping_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $pKey, 'id' => 'shippingTermId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_loading['.$pKey.']', $quotationInfo->port_of_loading ?? null, ['id'=> 'portOfLoading_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control width-inherit' ]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_discharge['.$pKey.']', $quotationInfo->port_of_discharge ?? null, ['id'=> 'portOfDischarge_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control width-inherit']) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('total_lead_time['.$pKey.']', $quotationInfo->total_lead_time ?? null, ['id'=> 'totalLeadTime_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control text-right integer-only text-input-width-100-per']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-postfix">@lang('label.DAY_S')</span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('pre_carrier_id['.$pKey.']', $preCarrierList, $quotationInfo->pre_carrier_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $pKey, 'id' => 'preCarrierId_'.$pKey]) !!}
                                            </td>
                                            <td class="text-center vcenter width-250">
                                                <?php
                                                $estimatedShipmentDate = !empty($quotationInfo->estimated_shipment_date) ? Helper::formatDate($quotationInfo->estimated_shipment_date) : null;
                                                ?>
                                                <div class="input-group date datepicker2 width-inherit">
                                                    {!! Form::text('estimated_shipment_date['.$pKey.']', $estimatedShipmentDate, ['id'=> 'estimatedShipmentDate_'.$pKey, 'data-key' => $pKey, 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
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
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center vcenter initial-term-sl width-50" rowspan="2">1</td>
                                            <td colspan="7" class="label-green-soft">
                                                @lang('label.PRODUCT_NAME'): <span class="product-name-{{$v4}} bold"></span> | @lang('label.BRAND_NAME'): <span class="brand-name-{{$v4}} bold"></span> | @lang('label.GRADE_NAME'): <span class="grade-name-{{ $v4 }} bold"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('payment_term_id['.$v4.']', $paymentTermList, $quotationInfo->payment_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'paymentTermId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('shipping_term_id['.$v4.']', $shippingTermList, $quotationInfo->shipping_term_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'shippingTermId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_loading['.$v4.']', $quotationInfo->port_of_loading ?? null, ['id'=> 'portOfLoading_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit' ]) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                {!! Form::text('port_of_discharge['.$v4.']', $quotationInfo->port_of_discharge ?? null, ['id'=> 'portOfDischarge_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit']) !!}
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    {!! Form::text('total_lead_time['.$v4.']', $quotationInfo->total_lead_time ?? null, ['id'=> 'totalLeadTime_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-only text-input-width-100-per']) !!}
                                                    <span class="input-group-addon bootstrap-touchspin-postfix">@lang('label.DAY_S')</span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-200">
                                                {!! Form::select('pre_carrier_id['.$v4.']', $preCarrierList, $quotationInfo->pre_carrier_id ?? null, ['class' => 'form-control js-source-states width-inherit', 'data-key' => $v4, 'id' => 'preCarrierId_'.$v4]) !!}
                                            </td>
                                            <td class="text-center vcenter width-250">
                                                <?php
                                                $estimatedShipmentDate = !empty($quotationInfo->estimated_shipment_date) ? Helper::formatDate($quotationInfo->estimated_shipment_date) : null;
                                                ?>
                                                <div class="input-group date datepicker2 width-inherit">
                                                    {!! Form::text('estimated_shipment_date['.$v4.']', $estimatedShipmentDate, ['id'=> 'estimatedShipmentDate_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-input-width-100-per', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
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
                                        @endif
                                        @endif


                                    </tbody>
                                    {!! Form::hidden('quotation_info', $id, ['class' => 'quotation-info', 'id' => 'quotationId']) !!}
                                    <tbody id="newTermsRowTbody"></tbody>
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
                            <a class="btn btn-inline btn-default tooltips" href="{{URL::to('/crmMyOpportunity' . Helper::queryPageStr($qpArr))}}" title="@lang('label.CANCEL')"> @lang('label.CANCEL')</a>
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

        $("#addFullMenuClass").addClass("page-sidebar-closed");
        $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");

        //product input
        $('.product-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.product-select-span-' + key).addClass('display-none');
            $('.product-select-btn-' + key).removeClass('display-none');
            $('#productProductName_' + key).removeClass('display-none');
            $('#productHasId_' + key).val('0');
        });
        $('.product-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productProductName_' + key).addClass('display-none');
            $('.product-text-btn-' + key).removeClass('display-none');
            $('.product-select-span-' + key).removeClass('display-none');
            $('.product-select-span-' + key + ' span.select2').css('width', '100%');
            $('#productHasId_' + key).val('1');
        });

        //brand input
        $('.brand-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.brand-select-span-' + key).addClass('display-none');
            $('.brand-select-btn-' + key).removeClass('display-none');
            $('#productBrandName_' + key).removeClass('display-none');
            $('#brandHasId_' + key).val('0');
        });
        $('.brand-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productBrandName_' + key).addClass('display-none');
            $('.brand-text-btn-' + key).removeClass('display-none');
            $('.brand-select-span-' + key).removeClass('display-none');
            $('.brand-select-span-' + key + ' span.select2').css('width', '100%');
            $('#brandHasId_' + key).val('1');
        });

        //grade input
        $('.grade-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.grade-select-span-' + key).addClass('display-none');
            $('.grade-select-btn-' + key).removeClass('display-none');
            $('#productGradeName_' + key).removeClass('display-none');
            $('#gradeHasId_' + key).val('0');
        });
        $('.grade-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productGradeName_' + key).addClass('display-none');
            $('.grade-text-btn-' + key).removeClass('display-none');
            $('.grade-select-span-' + key).removeClass('display-none');
            $('.grade-select-span-' + key + ' span.select2').css('width', '100%');
            $('#gradeHasId_' + key).val('1');
        });

        $('.summer-note').summernote({
            height: 100, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true      // set focus to editable area after initializing summernote
        });

        //add new product row
        $(document).on("click", ".add-new-product-row", function (e) {
            e.preventDefault();
            var buyerId = $("#buyerId").val();
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


            $.ajax({
                url: "{{URL::to('crmMyOpportunity/newProductRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {buyer_id: buyerId},
                success: function (res) {
                    $("#newProductTbody").append(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('product');
                    var v4 = res.v4;
                    var quotationId = $("#quotationId").val();
                    addNewTermsRow(v4, quotationId);
                },
            });
        });


        //remove product row
        $(document).on('click', '.remove-product-row', function () {
            var key = $(this).attr('data-key');
            $(this).parent().parent().remove();
            $('.new-terms-tr-' + key).remove();
            rearrangeSL('product');
            rearrangeSL('term');
            getSubtotal();
            return false;
        });

        //load per unit
        $(document).on('keyup', '.product-unit', function () {
            var key = $(this).attr('data-key');
            var unit = $(this).val();
            $('span.product-per-unit-' + key).text('/' + unit);
        });
        //load total price
        $(document).on('keyup', '.product-unit-price', function () {
            var key = $(this).attr('data-key');
            var unitPrice = $(this).val();
            var quantity = $('#productQuantity_' + key).val();
            if (quantity == '') {
                quantity = 0;
            }
            var totalPrice = unitPrice * quantity;
            $('#productTotalPrice_' + key).val(parseFloat(totalPrice).toFixed(2));
            getSubtotal();
            return false;
        });
        $(document).on('keyup', '.product-quantity', function () {
            var key = $(this).attr('data-key');
            var quantity = $(this).val();
            var unitPrice = $('#productUnitPrice_' + key).val();
            if (unitPrice == '') {
                unitPrice = 0;
            }
            var totalPrice = unitPrice * quantity;
            $('#productTotalPrice_' + key).val(parseFloat(totalPrice).toFixed(2));
            getSubtotal();
            return false;
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
                        url: "{{ URL::to('crmMyOpportunity/quotationSave')}}",
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

        //START:: Ajax for Allow User for CRM  
        $(document).on("click", '.add-contact', function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-opportunity-id");

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


            $.ajax({
                url: "{{URL::to('crmMyOpportunity/getActivityContactPersonData')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    opportunity_id: opportunityId,
                },
                beforeSend: function () {
                    $("#contactForm").html('');
                },
                success: function (res) {
                    $("#contactForm").html(res.html);
                },
            });
        });
        //END:: Ajax for Allow User for CRM

        $(document).on('click', '.primary-contact', function () {
            var key = $(this).attr('data-key');
            if ($(this).prop('checked')) {
                $('.primary-contact').prop('checked', false);
                $('#contactPrimary_' + key).prop('checked', true);
            }
        });

        //add new contact row
        $(document).on("click", ".add-new-contact-row", function (e) {
            e.preventDefault();
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


            $.ajax({
                url: "{{URL::to('crmMyOpportunity/newContactRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#newContactTbody").html('');
                },
                success: function (res) {
                    $("#newContactTbody").append(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('contact');
                },
            });
        });
        //remove contact row
        $(document).on('click', '.remove-contact-row', function () {
            $(this).parent().parent().remove();
            rearrangeSL('contact');
            return false;
        });

        $(document).on('click', '.close-btn', function () {
            $("#contactForm").html('');
        });

        $(document).on('click', "#saveContactData", function (e) {
            var opportunityId = $(this).data('opportunity-id');
            e.preventDefault();
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

            var formData = new FormData($("#contactFormData")[0]);
            formData.append('contact_type', 1);
            $.ajax({
                url: "{{ URL::to('/crmMyOpportunity/saveActivityContactPersonData')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function () {
                    $('#saveContactData').prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    $('#saveContactData').prop('disabled', false);
                    App.unblockUI();
                    $("#contactForm").html('');
                    $("#attentionId").html(res.contactView);
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
                    $('#saveContactData').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });

    });

    function rearrangeSL(type) {
        var sl = 0;
        $('.initial-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
        $('.new-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
    }
    function getSubtotal() {
        var subtotal = 0;
        $('.product-total-price').each(function () {
            var productTotalPrice = $(this).val();
            if (productTotalPrice == '') {
                productTotalPrice = 0;
            }

            subtotal = [subtotal, productTotalPrice].reduce((a, b) => a + Number(b), 0);
        });
        $('td.sub-total').text("$" + parseFloat(subtotal).toFixed(2));
    }

    // add new terms & condition
    function addNewTermsRow(v4, quotationId) {
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


        $.ajax({
            url: "{{URL::to('crmMyOpportunity/newTermsRow')}}",
            method: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                v_4: v4,
                quotationId: quotationId
            },
            success: function (res) {
                $("#newTermsRowTbody").append(res.html);
                $(".tooltips").tooltip();
                rearrangeSL('term');

            },
        });
    }

    // Load for Grade Name
    $(document).on('change', '.grade-item', function () {
        var key = $(this).attr('data-key');
        var gradeName = $("#productGradeId_" + key).find(":selected").text();
        var langValue = $("#productGradeId_" + key).val();
        var textName = $("#productGradeName_" + key).val();
        if (langValue != 0) {
            $('.grade-name-' + key).text(gradeName);
        }
    });
    // End Load for Grade Name

    //product under product unit
    $(document).on('change', '.product-item', function (e) {
        var productId = $(this).val();
        var buyerId = $('#buyerId').val();
        var salesPersonId = $('#salesPersonId').val();
        var salesPersonGroupId = $('#salesPersonGroupId').val();
        var dataKey = $(this).attr('data-key');

        $('#brandWiseGrade_' + dataKey).html("<select class='form-control grade-select js-source-states'><option value='0'>@lang('label.SELECT_GRADE_OPT')</option></select>");
        $('#brandWiseOrigin_' + dataKey).text('');
        $('#productOrigin_' + dataKey).val('');

        var productName = $("#productId_" + dataKey).find(':selected').text();
        if (productId != 0) {
            $('.product-name-' + dataKey).text(productName);
        } else {
            $('.product-name-' + dataKey).text('');
        }

        $.ajax({
            url: "{{ URL::to('crmMyOpportunity/getProductUnit')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                product_key: dataKey,
                sales_person_id: salesPersonId,
                sales_person_group_id: salesPersonGroupId,
                buyer_id: buyerId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#productWiseBrandId_' + dataKey).html(res.brand);
                $('#productUnit_' + dataKey).val(res.unit);
                $('.product-unit').trigger('keyup');
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });


    //brand under brand unit
    $(document).on('change', '.brand-item', function (e) {

        var dataKey = $(this).attr('data-key');
        var productId = $('#productId_' + dataKey).val();
        var brandId = $(this).val();

        var brandName = $("#productBrandId_" + dataKey).find(":selected").text();
        if (brandId != 0) {
            $('.brand-name-' + dataKey).text(brandName);
        } else {
            $('.brand-name-' + dataKey).text(brandName);
        }
        $.ajax({
            url: "{{ URL::to('crmMyOpportunity/getGradeOrigin')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
                product_key: dataKey
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#brandWiseGrade_' + dataKey).html(res.html);
                $('#brandWiseOrigin_' + dataKey).text(res.originName);
                $('#productOrigin_' + dataKey).val(res.originId);
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });
</script>
@stop
