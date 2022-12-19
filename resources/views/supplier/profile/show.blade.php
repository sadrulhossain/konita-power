@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SUPPLIER_PROFILE')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[13][1]))
                <a href="{{ URL::to('/supplier'.Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>
                @endif
                @if(!empty($userAccessArr[13][6]))
                <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('supplier/'.$target->id.'/printProfile') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
                    <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!--Start :: Basic Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.BASIC_INFORMATION')</strong></h4>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 margin-top-10 text-center">
                    @if (!empty($target->logo) && File::exists('public/uploads/supplier/' . $target->logo))
                    <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/supplier/{{$target->logo}}" width="100" height="100"/>
                    @else
                    <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="100" height="100"/>
                    @endif
                    @if(!empty($target->name))
                    <h5 class="bold text-center margin-top-10">
                        {!! $target->name . (!empty($target->code) ? ' (' . $target->code . ')' : '') !!}
                    </h5>
                    @endif
                </div>
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info">@lang('label.CLASSIFICATION')</td>
                                    <td class="active"colspan="5">{!! $target->classification ?? __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.FSC_CERTIFIED')</td>
                                    <td class="active"colspan="5">
                                        @if($target->fsc_certified == '1')
                                        <span class="label label-sm label-blue-steel">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm label-red-flamingo">@lang('label.NO')</span>
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.COUNTRY')</td>
                                    <td class="active"colspan="5">{!! $target->country ?? __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.STATUS')</td>
                                    <td class="active"colspan="5">
                                        @if($target->status == '1')
                                        <span class="label label-sm label-green-sharp">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm label-red-mint">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.SIGN_OFF_DATE')</td>
                                    <td class="active"colspan="5">{!! !empty($target->sign_off_date) ? Helper::formatDate($target->sign_off_date) : __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.DATE_OF_BUSINESS_STARTED')</td>
                                    <td class="active"colspan="5">{!! !empty($businessInitationDate->start) ? Helper::formatDate($businessInitationDate->start) : __('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.ADDRESS')</td>
                                    <td class="active"colspan="11">{!! $target->address ?? __('label.N_A') !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Basic Information-->
            
            

            <!--Start :: Contact Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.PHOTO')</th>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.DESIGNATION')</th>
                                    <th class="vcenter text-center">@lang('label.EMAIL')</th>
                                    <th class="vcenter text-center">@lang('label.PHONE')</th>
                                    <th class="vcenter text-center">@lang('label.INTRODUCTION_DATE')</th>
                                    <th class="vcenter text-center">@lang('label.NOTE_')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($contactPersonArr))
                                <?php $sl = 0; ?>
                                @foreach($contactPersonArr as $key => $contact)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="text-center vcenter" width="30px">
                                        @if(!empty($contact['photo']) && File::exists('public/uploads/supplier/contact_person/' . $contact['photo']))
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/uploads/supplier/contact_person/{{$contact['photo']}}" alt="{{ $contact['name'] }}"/>
                                        @else
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $contact['name'] }}"/>
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $contact['name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! !empty($contact['designation_id']) && !empty($contactDesignationList[$contact['designation_id']]) ? $contactDesignationList[$contact['designation_id']] : __('label.N_A') !!}</td>
                                    <td class="vcenter text-primary">{!! $contact['email'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">
                                        @if(is_array($contact['phone']))

                                        <?php
                                        $lastValue = end($contact['phone']);
                                        ?>
                                        @foreach($contact['phone'] as $keyP => $p)
                                        {{$p ?? __('label.N_A') }}
                                        @if($lastValue !=$p)
                                        <span>,</span>
                                        @endif
                                        @endforeach
                                        @else
                                        {!! $contact['phone'] ?? __('label.N_A') !!}
                                        @endif
                                    </td>
                                    <td class="vcenter text-center">{!! !empty($contact['introduction_date']) ? Helper::formatDate($contact['introduction_date']) : __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $contact['note'] ?? __('label.N_A') !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Contact Person Information-->
            
            <!--Start :: Product Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.PRODUCT_INFORMATION')</strong></h4>
                </div>
                @if(!empty($productInfoArr))
                <div class="col-md-12 margin-top-10 text-center">
                    <span class="text-green bold">
                        (@lang('label.ASTERIC_SIGN_REFERS_TO_BRAND_IN_BUSINESS'))
                    </span>
                </div>
                @endif
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.PRODUCT')</th>
                                    <th class="vcenter text-center" colspan="2">@lang('label.BRAND')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($productInfoArr))
                                <?php $sl = 0; ?>
                                @foreach($productInfoArr as $productId => $product)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $product['product_name'] ?? __('label.N_A') !!}</td>

                                    <?php
                                    $volume = __('label.N_A');
                                    $textAlignment = 'center';
                                    if (!empty($importVolArr[$productId]['volume']) && $importVolArr[$productId]['volume'] != 0) {
                                        $unit = !empty($importVolArr[$productId]['unit']) ? ' ' . $importVolArr[$productId]['unit'] : '';
                                        $volume = Helper::numberFormat2Digit($importVolArr[$productId]['volume']) . $unit;
                                        $textAlignment = 'right';
                                    }
                                    ?>
                                    
                                    @if(!empty($product['brand']))
                                    <?php $i = 0; ?>
                                    @foreach($product['brand'] as $brandId => $brand)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="text-center vcenter" width="30px">
                                        @if(!empty($brand['logo']) && File::exists('public/uploads/brand/' . $brand['logo']))
                                        <img class="pictogram-min-space tooltips" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['brand_name']}}" title="{{ $brand['brand_name'] }}"/>
                                        @else 
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {!! $brand['brand_name'] ?? __('label.N_A') !!}
                                        @if(!empty($brandWiseVolumeRateArr[$productId]))
                                        @if(array_key_exists($brandId, $brandWiseVolumeRateArr[$productId]))
                                        <span class="text-green bold">*</span>&nbsp;
                                        <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]) : '0.00'; ?>
                                        <span class="text-green bold">
                                            (@lang('label.PERCENTAGE_OF_TOTAL_SALES_VOLUME', ['percentage' => $percentage]))
                                        </span>
                                        @endif
                                        @endif
                                    </td>
                                    <?php
                                    if ($i < ($productRowSpanArr[$productId]['brand'] - 1)) {
                                        echo '</tr>';
                                    }
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Product Information-->
            
            <!--Start :: Beneficiary Bank Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.BENEFICIARY_BANK_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.ACCOUNT_NO')</th>
                                    <th class="vcenter text-center">@lang('label.CUSTOMER_ID')</th>
                                    <th class="vcenter text-center">@lang('label.BRANCH')</th>
                                    <th class="vcenter text-center">@lang('label.STATUS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$beneficiaryBankInfo->isEmpty())
                                <?php $sl = 0; ?>
                                @foreach($beneficiaryBankInfo as $bank)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $bank->name ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $bank->account_no ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $bank->customer_id ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $bank->branch ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter">
                                        @if($bank->status == '1')
                                        <span class="label label-sm label-green-sharp">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm label-red-mint">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Beneficiary Bank Information-->
            
            <!--Start :: Business Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.BUSINESS_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info">@lang('label.TOTAL_ORDERS')</td>
                                    <td class="active text-center"colspan="5">{!! $inquiryCountArr['total'] ?? 0 !!}</td>
                                    <td class="fit bold info"colspan="1">@lang('label.MOST_FREQUENT_CAUSE_OF_FAILURE')</td>
                                    <td class="active"colspan="11">
                                        @if(!empty($mostFrequentCancelCauseArr))
                                        @foreach($mostFrequentCancelCauseArr as $key => $causeId)
                                        <?php
                                        $labelColor = ($key == 0 || $key % 2 == 0) ? 'red-soft' : 'red-mint';
                                        ?>
                                        <span class="label margin-2 bold label-sm label-{{$labelColor}}">{!! $cancelCauseList[$causeId] !!}</span>
                                        @endforeach
                                        @else
                                        <span class="label margin-2 bold label-sm label-gray-mint">@lang('label.N_A')</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div id="orderSummaryPie" class="chart-block"></div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="12">
                                                @lang('label.SALES_N_SHIPMENT_SUMMARY')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.SALES_VOLUME')</td>
                                            <td class="active text-right"colspan="5">{!! !empty($overAllSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_volume) : '0.00' !!} @lang('label.UNIT')</td>
                                            <td class="fit bold info">@lang('label.SALES_AMOUNT')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($overAllSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_amount) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.SALES_VOLUME') (@lang('label.LAST_1_YEAR'))</td>
                                            <td class="active text-right"colspan="5">{!! !empty($lastOneYearSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_volume) : '0.00' !!} @lang('label.UNIT')</td>
                                            <td class="fit bold info">@lang('label.SALES_AMOUNT') (@lang('label.LAST_1_YEAR'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($lastOneYearSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_amount) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.SHIPPED_VOLUME')</td>
                                            <td class="active text-right"colspan="5">{!! !empty($supplierPaymentArr['shipped_quantity']) ? Helper::numberFormat2Digit($supplierPaymentArr['shipped_quantity']) : '0.00' !!} @lang('label.UNIT')</td>
                                            <td class="fit bold info">@lang('label.SHIPMENT_PAYABLE')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($supplierPaymentArr['payable']) ? Helper::numberFormat2Digit($supplierPaymentArr['payable']) : '0.00' !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($inquiryCountArr['total']) && $inquiryCountArr['total'] != 0)
                <div class="row">
                    <div class="col-md-12 margin-top-10 text-center">
                        @if(!empty($inquiryCountArr['confirmed']) && $inquiryCountArr['confirmed'] != 0)
                        <button class="btn btn-xs bold yellow-casablanca tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_CONFIRMED_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-supplier-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="3" data-toggle="modal">
                            @lang('label.CONFIRMED_ORDER_LIST')
                        </button>
                        @endif

                        @if(!empty($inquiryCountArr['accomplished']) && $inquiryCountArr['accomplished'] != 0)
                        <button class="btn btn-xs bold green-seagreen tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_ACCOMPLISHED_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-supplier-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="4" data-toggle="modal">
                            @lang('label.ACCOMPLISHED_ORDER_LIST')
                        </button>
                        @endif

                        @if(!empty($inquiryCountArr['failed']) && $inquiryCountArr['failed'] != 0)
                        <button class="btn btn-xs bold red-flamingo tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_CANCELLED_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-supplier-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="5" data-toggle="modal">
                            @lang('label.CANCELLED_ORDER_LIST')
                        </button>
                        @endif
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-top-10">
                            <div id="salesVolumeLastFiveYears" class="chart-block"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-top-10">
                            <div id="salesAmountLastFiveYears" class="chart-block"></div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="6">
                                                @lang('label.PAYMENT_SUMMARY')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.PAID_AMOUNT') (@lang('label.FROM_BUYER_TO_SUPPLIER'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($supplierPaymentArr['paid']) ? Helper::numberFormat2Digit($supplierPaymentArr['paid']) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.PAYMENT_DUE') (@lang('label.FROM_BUYER_TO_SUPPLIER'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($supplierPaymentArr['due']) ? Helper::numberFormat2Digit($supplierPaymentArr['due']) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.INVOICED_AMOUNT') (@lang('label.TO_SUPPLIER'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($invoicedAmount) ? Helper::numberFormat2Digit($invoicedAmount) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.RECEIVED_AMOUNT') (@lang('label.FROM_SUPPLIER'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($received->total_collection) ? Helper::numberFormat2Digit($received->total_collection) : '0.00' !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="12">
                                                @lang('label.INCOME_SUMMARY')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.NET_INCOME') (@lang('label.EXPECTED'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($netIncome) ? Helper::numberFormat2Digit($netIncome) : '0.00' !!}</td>
                                            <td class="fit bold info">@lang('label.NET_INCOME') (@lang('label.RECEIVED_AT_ACTUAL'))</td>
                                            <td class="active text-right"colspan="5">${!! !empty($received->net_income) ? Helper::numberFormat2Digit($received->net_income) : '0.00' !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="18">
                                                @lang('label.PRINCIPLE_COMMISSION_SUMMARY')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.COMMISSION_ON_SALES_VOLUME')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($commissionOnSalesVolume) ? Helper::numberFormat2Digit($commissionOnSalesVolume) : '0.00' !!}</td>
                                            <td class="fit bold info">@lang('label.COMMISSION_ON_SHIPPED_VOLUME')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($commissionOnShippedVolume) ? Helper::numberFormat2Digit($commissionOnShippedVolume) : '0.00' !!}</td>
                                            <td class="fit bold info">@lang('label.PAID_THROUGH_INVOICE')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($commissionPaidThroughInvoice->total) ? Helper::numberFormat2Digit($commissionPaidThroughInvoice->total) : '0.00' !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End :: Business Information-->
        </div>
    </div>
</div>


<!-- Modal start -->
<!--related sales person list-->
<div class="modal fade" id="modalInvolvedOrderList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInvolvedOrderList"></div>
    </div>
</div>

<!-- Modal end -->

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

//related sales person list modal
    $(".involved-order-list").on("click", function (e) {
        e.preventDefault();
        var supplierId = $(this).attr("data-supplier-id");
        var salesPersonId = $(this).attr("data-sales-person-id");
        var typeId = $(this).attr("data-type-id");
        $.ajax({
            url: "{{ URL::to('/supplier/getInvolvedOrderList')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                supplier_id: supplierId,
                sales_person_id: salesPersonId,
                type_id: typeId,
            },
            beforeSend: function () {
                $("#showInvolvedOrderList").html('');
            },
            success: function (res) {
                $("#showInvolvedOrderList").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

    //start :: order summary pie
    var orderSummaryPieOptions = {
<?php
$confirmed = $inquiryCountArr['confirmed'] ?? 0;
$accomplished = $inquiryCountArr['accomplished'] ?? 0;
$cancelled = $inquiryCountArr['failed'] ?? 0;
?>
        series: [
<?php
echo $confirmed . ', ' . $accomplished . ', ' . $cancelled;
?>
        ],
        labels: ["@lang('label.CONFIRMED')", "@lang('label.ACCOMPLISHED')"
                    , "@lang('label.CANCELLED')"],
        chart: {
            width: 380,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#F2784B", "#1BA39C", "#EF4836"],
        fill: {
            type: 'gradient',
        },
        legend: {
            fontSize: '12px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 600,
            formatter: function (val, opts) {
                var indx = opts.w.globals.series[opts.seriesIndex];
                return val + ': ' + indx
            },
            labels: {
                colors: ['#FFFFFF'],
                useSeriesColors: true
            },
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: '#fff',
                fillColors: [],
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  val
                },

            }
        },
        responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
    };
    var orderSummaryPie = new ApexCharts(document.querySelector("#orderSummaryPie"), orderSummaryPieOptions);
    orderSummaryPie.render();
    //end :: order summary pie

    //start :: sales volume last five years
    var salesVolumeLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_VOLUME')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $volume = $salesSummaryArr[$year]['volume'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#1BA39C'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_VOLUME') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.VOLUME') (@lang('label.UNIT'))",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return val + " @lang('label.UNIT')"
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesVolumeLastFiveYears = new ApexCharts(document.querySelector("#salesVolumeLastFiveYears"), salesVolumeLastFiveYearsOptions);
    salesVolumeLastFiveYears.render();
    //end :: sales volume last five years

    //start :: sales amount last five years
    var salesAmountLastFiveYearsOptions = {
        series: [
            {
                name: "@lang('label.SALES_AMOUNT')",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $amount = $salesSummaryArr[$year]['amount'] ?? 0;
        echo "'$amount',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#8E44AD'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "@lang('label.SALES_AMOUNT') (@lang('label.LAST_5_YEAR'))",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.YEARS')"
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.SALES_AMOUNT') ($)",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return "$" + val
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesAmountLastFiveYears = new ApexCharts(document.querySelector("#salesAmountLastFiveYears"), salesAmountLastFiveYearsOptions);
    salesAmountLastFiveYears.render();
    //end :: sales amount last five years

});

function growthOrDecline(thisYear, prevYear) {
    var rateText = '';
    var rate = 0;
    var defaultPrevYear = 1;

    if (thisYear >= prevYear) {
        if (prevYear > 0) {
            defaultPrevYear = prevYear;
        }
        rate = ((thisYear - prevYear) * 100) / defaultPrevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-green-seagreen'>&nbsp;(+" + rate + "% form previous year)</span>";
    } else if (thisYear < prevYear) {
        rate = ((prevYear - thisYear) * 100) / prevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-danger'>&nbsp;(-" + rate + "% form previous year)</span>";
    } else {
        rateText = "";
    }

    return rateText;
}
</script>
@stop