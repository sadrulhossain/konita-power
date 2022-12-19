@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.MY_PROFILE')
            </div>
            <div class="actions">

                <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to($request->fullUrl(). '?view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
                    <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!--Start :: Basic Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.BASIC_INFORMATION')</strong></h4>
                </div>
				{!! Form::open(array('files'=> true, 'class' => 'form-horizontal','id' => 'updateLogoData')) !!}
				{!! Form::hidden('buyer_id', $target->id) !!}
				{!! Form::hidden('username', Auth::user()->username) !!}
				{{csrf_field()}}
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 margin-top-10">
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
							@if (!empty($target->logo) && File::exists('public/uploads/buyer/' . $target->logo))
							<img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$target->logo}}" width="150" height="150"/>
							@else
							<img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="150" height="150"/>
							@endif
							
						</div>
						<div>
							<span class="btn red btn-outline btn-file">
								<span class="fileinput-new"> Select image </span>
								<span class="fileinput-exists"> Change </span>
								{!! Form::file('logo', null, ['id'=> 'logo', 'class' => 'form-control']) !!}
							</span>
							<a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
						</div>
						<span class="text-danger">{{ $errors->first('logo') }}</span>
						
						<button class="btn btn-default green margin-top-10" type="button" id="updateLogoBtn">
							<i class=""></i> @lang('label.UPDATE_LOGO')
						</button>
						@if(!empty($target->name))
						<h5 class="bold text-center margin-top-10">
							{!! $target->name . (!empty($target->code) ? ' (' . $target->code . ')' : '') !!}
						</h5>
						@endif
					</div>
					<div class="clearfix margin-top-10">
						<span class="label label-danger">@lang('label.NOTE')</span><span class="text-danger bold">&nbsp;@lang('label.SUPPLIER_IMAGE_FOR_IMAGE_DESCRIPTION')</span>
					</div>
                </div>
				{!! Form::close() !!}
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info">@lang('label.CATEGORY')</td>
                                    <td class="active"colspan="5">{!! $target->category ?? __('label.N_A') !!}</td>
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
                                    <td class="fit bold info">@lang('label.ISO_CERTIFIED')</td>
                                    <td class="active"colspan="5">
                                        @if($target->iso_certified == '1')
                                        <span class="label label-sm label-blue-steel">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm label-red-flamingo">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.DIVISION')</td>
                                    <td class="active"colspan="5">{!! $target->division ?? __('label.N_A') !!}</td>

                                    <td class="fit bold info">@lang('label.STATUS')</td>
                                    <td class="active"colspan="5">
                                        @if($target->status == '1')
                                        <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.DATE_OF_ENTRY')</td>
                                    <td class="active"colspan="5">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.TYPE')</td>
                                    <td class="active"colspan="5">
                                        @if(!empty($typeArr))
                                        @foreach($typeArr as $type)
                                        @if($type == 1)
                                        <span class="label bold label-sm label-yellow-casablanca">@lang('label.BONDED')</span>
                                        @elseif($type == 2)
                                        <span class="label bold label-sm label-purple-sharp">@lang('label.COMMERCIAL')</span>
                                        @endif
                                        @endforeach
                                        @else
                                        <span class="label bold label-sm label-red-soft">@lang('label.N_A')</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.DATE_OF_BUSINESS_STARTED')</td>
                                    <td class="active"colspan="5">{!! !empty($businessInitationDate->start) ? Helper::formatDate($businessInitationDate->start) : __('label.N_A') !!}</td>
                                    <td class="fit bold info">@lang('label.BRAND_OF_MACHINE')</td>
                                    <td class="active"colspan="5">{!! $target->machine_brand ?? __('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.HEAD_OFFICE')</td>
                                    <td class="active"colspan="11">{!! $target->head_office_address ?? __('label.N_A') !!}</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info">@lang('label.PRIMARY_FACTORY')</td>
                                    <td class="active"colspan="11">
                                        @if(!empty($primaryFactory->name))
                                        <span class="bold">{!! $primaryFactory->name !!}</span>
                                        @if(!empty($primaryFactory->address))
                                        <br/><span>{!! $primaryFactory->address !!}</span>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Basic Information-->

            <!--Start :: Product Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.ALREADY_PURCHASED_PRODUCTS')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.PRODUCT')</th>
                                    <th class="vcenter text-center" colspan="3">@lang('label.BRAND')</th>
                                    <th class="vcenter text-center">@lang('label.PURCHASED_VOLUME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($productInfoArr))
                                <?php $sl = 0; ?>
                                @foreach($productInfoArr as $productId => $product)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $product['product_name'] ?? __('label.N_A') !!}</td>

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
                                        <br/>
                                        <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) : '0.00'; ?>
                                        <span class="text-green bold">
                                            (@lang('label.PERCENTAGE_OF_TOTAL_PURCHASED_VOLUME', ['percentage' => $percentage]))
                                        </span>
                                        @endif
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {!! $brand['origin'] ?? __('label.N_A') !!}
                                    </td>
                                    <td class="vcenter text-right">
                                        {!! (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) ? Helper::numberFormat2Digit($brandWiseVolumeRateArr[$productId][$brandId]['volume']) : 0.00) . (!empty($product['unit']) ? ' ' . $product['unit'] : '') !!}
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
                                    <td colspan="6"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Product Information-->


            <!--Start :: Business Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.ORDER_HISTORY')</strong></h4>
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
                                                @lang('label.PURCHASE_N_SHIPMENT_SUMMARY')
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
                                            <td class="active text-right"colspan="5">{!! !empty($buyerPaymentArr['shipped_quantity']) ? Helper::numberFormat2Digit($buyerPaymentArr['shipped_quantity']) : '0.00' !!} @lang('label.UNIT')</td>
                                            <td class="fit bold info">@lang('label.SHIPMENT_PAYABLE')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($buyerPaymentArr['payable']) ? Helper::numberFormat2Digit($buyerPaymentArr['payable']) : '0.00' !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info" colspan="12">
                                                @lang('label.PAYMENT_SUMMARY')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info">@lang('label.PAID_AMOUNT')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($buyerPaymentArr['paid']) ? Helper::numberFormat2Digit($buyerPaymentArr['paid']) : '0.00' !!}</td>
                                            <td class="fit bold info">@lang('label.PAYMENT_DUE')</td>
                                            <td class="active text-right"colspan="5">${!! !empty($buyerPaymentArr['due']) ? Helper::numberFormat2Digit($buyerPaymentArr['due']) : '0.00' !!}</td>
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
                                href="#modalInvolvedOrderList" data-buyer-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="2" data-toggle="modal">
                            @lang('label.CONFIRMED_ORDER_LIST')
                        </button>
                        @endif
                        @if(!empty($inquiryCountArr['processing']) && $inquiryCountArr['processing'] != 0)
                        <button class="btn btn-xs bold purple tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_IN_PROGRESS_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-buyer-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="3" data-toggle="modal">
                            @lang('label.IN_PROGRESS_ORDER_LIST')
                        </button>
                        @endif

                        @if(!empty($inquiryCountArr['accomplished']) && $inquiryCountArr['accomplished'] != 0)
                        <button class="btn btn-xs bold green-seagreen tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_ACCOMPLISHED_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-buyer-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="4" data-toggle="modal">
                            @lang('label.ACCOMPLISHED_ORDER_LIST')
                        </button>
                        @endif

                        @if(!empty($inquiryCountArr['failed']) && $inquiryCountArr['failed'] != 0)
                        <button class="btn btn-xs bold red-flamingo tooltips vcenter involved-order-list"  
                                title="@lang('label.CLICK_TO_VIEW_CANCELLED_ORDER_LIST')" 
                                href="#modalInvolvedOrderList" data-buyer-id="{!! $target->id !!}" 
                                data-sales-person-id="0" data-type-id="5" data-toggle="modal">
                            @lang('label.CANCELLED_ORDER_LIST')
                        </button>
                        @endif
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        @if(!empty($productInfoArr))
                        @foreach($productInfoArr as $productId => $product)
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-top-10">
                            <div id="purchaseVolume{{$productId}}LastFiveYears" class="chart-block"></div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <!--End :: Business Information-->


            <!--Start :: Contact Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.OUR_CONTACT_PERSON_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.DESIGNATION')</th>
                                    <th class="vcenter text-center">@lang('label.EMAIL')</th>
                                    <th class="vcenter text-center">@lang('label.PHONE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($contactPersonArr))
                                <?php $sl = 0; ?>
                                @foreach($contactPersonArr as $key => $contact)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="vcenter">{!! $contact['name'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! !empty($contact['designation_id']) && !empty($contactDesignationList[$contact['designation_id']]) ? $contactDesignationList[$contact['designation_id']] : __('label.N_A') !!}</td>
                                    <td class="vc text-primary">{!! $contact['email'] ?? __('label.N_A') !!}</td>
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
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Contact Person Information-->

            <!--Start :: Sales Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong>@lang('label.KTI_CONTACT_PERSON_INFORMATION')</strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.PHOTO')</th>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.EMPLOYEE_ID')</th>
                                    <th class="vcenter text-center">@lang('label.DESIGNATION')</th>
                                    <th class="vcenter text-center">@lang('label.EMAIL')</th>
                                    <th class="vcenter text-center">@lang('label.PHONE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$activelyEngagedSalesPersonArr->isEmpty())
                                <?php $sl = 0; ?>
                                @foreach($activelyEngagedSalesPersonArr as $sp)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="text-center vcenter" width="30px">
                                        @if(!empty($sp->photo) && File::exists('public/uploads/user/' . $sp->photo))
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/uploads/user/{{$sp->photo}}" alt="{{ $sp->name}}"/>
                                        @else
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $sp->name}}"/>
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $sp->name ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $sp->employee_id ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $sp->designation ?? __('label.N_A') !!}</td>
                                    <td class="vcenter text-primary">{!! $sp->email ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $sp->phone ?? __('label.N_A') !!}</td>

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
            <!--End :: Sales Person Information-->
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
        var buyerId = $(this).attr("data-buyer-id");
        var salesPersonId = $(this).attr("data-sales-person-id");
        var typeId = $(this).attr("data-type-id");
        $.ajax({
            url: "{{ URL::to('/buyerProfile/getInvolvedOrderList')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                buyer_id: buyerId,
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
$processing = $inquiryCountArr['processing'] ?? 0;
$accomplished = $inquiryCountArr['accomplished'] ?? 0;
$cancelled = $inquiryCountArr['failed'] ?? 0;
?>
        series: [
<?php
echo $confirmed . ', ' . $processing . ', ' . $accomplished . ', ' . $cancelled;
?>
        ],
        labels: ["@lang('label.CONFIRMED')", "@lang('label.IN_PROGRESS')", "@lang('label.ACCOMPLISHED')"
                    , "@lang('label.CANCELLED')"],
        chart: {
            width: 400,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#EF4836"],
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

    //start :: purchase volume last five years
<?php
if (!empty($productInfoArr)) {
    ?>
        var sl = 0;
        var colors = ['#1f441e', '#440a67', '#C62700', '#ABC400', '#26001b', '#ff005c', '#21209c', '#04BC06', '#013C38', '#8f4f4f', '#435560', '#025955', '#8c0000', '#763857', '#28527a', '#413c69', '#484018', '#1687a7', '#ff0000', '#41584b', '#dd9866', '#16a596', '#649d66', '#7a4d1d', '#630B0B', '#FF5600', '#AF00A0', '#000000', '#290262', '#9D0233'];
    <?php
    foreach ($productInfoArr as $productId => $product) {
        ?>
            var productId = <?php echo $productId; ?>;

            var purchaseVolumeLastFiveYearsOptions = {
                series: [
                    {
                        name: "@lang('label.PURCHASE_VOLUME')",
                        data: [
        <?php
        if (!empty($yearArr)) {
            foreach ($yearArr as $year => $yearName) {
                $volume = $salesSummaryArr[$productId][$year]['volume'] ?? 0;
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
                colors: [colors[sl]],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth',
                },
                title: {
                    text: "{{$product['product_name']}} @lang('label.PURCHASE_VOLUME') (@lang('label.LAST_5_YEAR'))",
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
                        text: "@lang('label.VOLUME') ({{$product['unit']}})",
                    },
                },
                tooltip: {
                    y: [
                        {
                            formatter: function (val) {
                                return val + " {{$product['unit']}}"
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
            sl++;
            var purchaseVolumeLastFiveYears = new ApexCharts(document.querySelector("#purchaseVolume" + productId + "LastFiveYears"), purchaseVolumeLastFiveYearsOptions);
            purchaseVolumeLastFiveYears.render();

        <?php
    }
}
?>

    //end :: purchase volume last five years



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
<script type="text/javascript">
	//After Click to Save new commission 
        $(document).on("click", "#updateLogoBtn", function (e) {
            e.preventDefault();
            var formData = new FormData($('#updateLogoData')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
				text:'You want to update your logo',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Sure',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('buyerProfile/updateLogo')}}",
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
        //endof commission setup modal
</script>
@stop