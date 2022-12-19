@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.SALES_VOLUME_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!$targetArr->isEmpty())
                    @if(!empty($userAccessArr[49][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[49][9]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    @endif
                    <button class="btn green-seagreen btn-sm btn-chart-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_GRAPHICAL_VIEW')">
                        <i class="fa fa-line-chart"></i>
                    </button>
                    <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_TABULAR_VIEW')">
                        <i class="fa fa-list"></i><!--
                    </button>-->
                        @endif 
                        @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'salesVolumeReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('pi_from_date', Request::get('pi_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('pi_from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('pi_to_date', Request::get('pi_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('pi_to_date') }}</span>
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
            </div>
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
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_id', $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row tabular-view">

                <!--SUMMARY-->
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter" colspan="2">@lang('label.COMMISSION_BREAKDOWN')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tooltips" title="@lang('label.KONITA_CMSN')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.KONITA_CMSN')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['konita_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.PRINCIPLE_COMMISSION')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.PRINCIPLE_COMMISSION')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['principal_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['principal_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.SALES_PERSON_COMMISSION')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.SALES_PERSON_COMMISSION')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['sales_person_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['sales_person_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.BUYER_COMMISSION')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.BUYER_COMMISSION')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['buyer_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['buyer_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.REBATE_COMMISSION')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.REBATE_COMMISSION')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['rebate_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['rebate_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.LC_TRANSMITTED_COPY_DONE')">
                                        <!--LC Transnitted Yes-->
                                        <td>@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                                        <td class="text-right">${{Helper::numberFormat2Digit($lcTransmitted)}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.LC_NOT_TRANSMITTED')">
                                        <!--LC Transnitted No-->
                                        <td>@lang('label.LC_NOT_TRANSMITTED')</td>
                                        <td class="text-right">${{Helper::numberFormat2Digit($notLcTransmitted)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter" colspan="2">@lang('label.INCOME_BREAKDOWN')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tooltips" title="@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')">
                                        <!--Total Sales Volume-->
                                        <td>@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</td>
                                        <td class="text-right">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')">
                                        <!--Total Sales Amount-->
                                        <td>@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</td>
                                        <td class="text-right">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.KONITA_CMSN')+@lang('label.REBATE_COMMISSION')">
                                        <!--Total Konita Net Commission-->
                                        <td>@lang('label.TOTAL')&nbsp;@lang('label.KONITA_NET_CMSN')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['total_konita_net_csmn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_net_csmn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.KONITA_CMSN') + @lang('label.SALES_PERSON_COMMISSION')
                                        + @lang('label.BUYER_COMMISSION') + @lang('label.REBATE_COMMISSION')">
                                        <!--Total Konita Commission-->
                                        <td>@lang('label.TOTAL')&nbsp;@lang('label.KONITA_CMSN')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['total_konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn']) : '0.00'}}</td>
                                    </tr>
                                    <tr class="tooltips" title="@lang('label.PRINCIPLE_COMMISSION')">
                                        <!--Total Admin cost-->
                                        <td>@lang('label.TOTAL')&nbsp;@lang('label.ADMIN_COST')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['total_admin_cost']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost']) : '0.00'}}</td>
                                    </tr>

                                    <tr class="tooltips" title="@lang('label.TOTAL_COMMISSION') = @lang('label.TOTAL')&nbsp;@lang('label.KONITA_CMSN')
                                        + @lang('label.TOTAL')&nbsp;@lang('label.ADMIN_COST')">
                                        <!--Total Commission-->
                                        <td>@lang('label.TOTAL_COMMISSION')</td>
                                        <td class="text-right">${{!empty($comsnIncomeArr['total_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn']) : '0.00'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        @if(!empty($countryWiseAccount))
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="vcenter text-center">@lang('label.COUNTRY')</th>
                                        <th class="vcenter text-center">@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</th>
                                        <th class="vcenter text-center">@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($countryWiseAccount as $countryId=>$val)
                                    <tr>
                                        <td class="vcenter text-center">
                                            {{!empty($countryList[$countryId])?$countryList[$countryId]:''}}
                                        </td>
                                        <td class="vcenter text-right">
                                            {{!empty($val['total_sales_volyme'])?Helper::numberFormat2Digit($val['total_sales_volyme']):0}}&nbsp;@lang('label.UNIT')
                                        </td>
                                        <td class="vcenter text-right">
                                            ${{!empty($val['total_sales_amount'])?Helper::numberFormat2Digit($val['total_sales_amount']):0}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                        @endif
                    </div>

                </div>
                <!--END OF SUMMARY-->


                <div class="col-md-12">
                    <div class="tableFixHead max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.PURCHASE_ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    <th class="vcenter">@lang('label.SUPPLIER')</th>
                                    <th class="vcenter text-center">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                    @if(!empty($userAccessArr[49][5]))
                                    <th class="text-center vcenter">@lang('label.SHIPMENT_DETAILS')</th>
                                    @endif
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter">@lang('label.GSM')</th>
                                    <th class="vcenter text-center">@lang('label.SALES_VOLUME')</th>
                                    <th class="vcenter text-center">@lang('label.SALES_AMOUNT')</th>
                                    <th class="vcenter text-center">@lang('label.KONITA_CMSN')</th>
                                    <th class="vcenter text-center">@lang('label.ADMIN_COST')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_COMMISSION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $sl = 0;
                                $totalSalesVolume = 0;
                                $totalSalesAmount = 0;
                                $totalKonitaCmsn = 0;
                                $totalAdminCost = 0;
                                $totalCmsn = 0;
                                ?>
                                @foreach($targetArr as $key=>$target)
                                <?php
                                $netCmsn = !empty($profitArr[$target->id]['net_commission']) ? $profitArr[$target->id]['net_commission'] : 0;
                                $expenditureCmsn = !empty($profitArr[$target->id]['expenditure']) ? $profitArr[$target->id]['expenditure'] : 0;
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        {!! $target->order_no !!}
                                        @if($expenditureCmsn > $netCmsn)
                                        <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle red-soft tooltips" title="@lang('label.NO_PROFIT')">

                                        </button>
                                        @endif
                                    </td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->purchase_order_no !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                                    <td class="vcenter" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($supplierList[$target->supplier_id]) ? $supplierList[$target->supplier_id] : '' !!}</td>
                                    <td class="vcenter text-center" rowspan="{{$rowSpan['inquiry']}}">
                                        @if($target->lc_transmitted_copy_done == '1')
                                        <span class="label label-sm label-info">@lang('label.YES')</span>
                                        @elseif($target->lc_transmitted_copy_done == '0')
                                        <span class="label label-sm label-warning">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                    @if(!empty($userAccessArr[49][5]))
                                    <td class="text-center vcenter" rowspan="{{$rowSpan['inquiry']}}">
                                        @if(in_array($target->order_status, ['2', '3', '4']))
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
                                        @endif
                                        @if(in_array($target->order_status, ['0', '1']))
                                        <button type="button" class="btn btn-xs cursor-default btn-circle grey-cascade tooltips vcenter" title="@lang('label.NOT_MATURED_YET')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        @endif
                                    </td>
                                    @endif

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

                                    $totalSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                    $totalSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    $konitaCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn'] : (!empty($commissionArr[$target->id][0]['total_konita_cmsn']) ? $commissionArr[$target->id][0]['total_konita_cmsn'] : 0));
                                    $adminCost = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost'] : (!empty($commissionArr[$target->id][0]['admin_cost']) ? $commissionArr[$target->id][0]['admin_cost'] : 0));
                                    $totalCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn'] : (!empty($commissionArr[$target->id][0]['total_cmsn']) ? $commissionArr[$target->id][0]['total_cmsn'] : 0));
                                    ?>
                                    <td class="vcenter">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                    <td class="vcenter text-right">{{Helper::numberFormat2Digit($item['sales_volume'])}}&nbsp;{{$item['unit_name']}}</td>
                                    <td class="vcenter text-right">${{Helper::numberFormat2Digit($item['sales_amount'])}}</td>
                                    <td class="vcenter text-right">${{Helper::numberFormat2Digit($konitaCommission)}}</td>
                                    <td class="vcenter text-right">${{Helper::numberFormat2Digit($adminCost)}}</td>
                                    <td class="vcenter text-right">${{Helper::numberFormat2Digit($totalCommission)}}</td> 

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
                                <tr>
                                    <td class="bold text-right" colspan="{{ !empty($userAccessArr[49][5]) ? 11 : 10}}">@lang('label.TOTAL')</td>
                                    <td class="bold text-right">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                    <td class="bold text-right">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                                    <td class="bold text-right">${{Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn'])}}</td>
                                    <td class="bold text-right">${{Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost'])}}</td>
                                    <td class="bold text-right">${{Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn'])}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="{{ !empty($userAccessArr[49][5]) ? 16 : 15}}" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row chart-view">
                <div class="col-md-6">
                    <div id="countryWiseSalesVolumeChart" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="countryWiseSalesAmountChart" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="commissionBreakdownPie" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="incomeBreakdownChart" class="chart-block"></div>
                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>



<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
//default setting
    $(".btn-chart-view").show();
    $(".btn-tabular-view").hide();
    $(".btn-print").show();
    $(".btn-pdf").show();
    $(".chart-view").hide();
    $(".tabular-view").show();
//when click tabular view button
    $(document).on("click", ".btn-tabular-view", function () {
        $(".btn-chart-view").show();
        $(".btn-tabular-view").hide();
        $(".btn-print").show();
        $(".btn-pdf").show();
        $(".chart-view").hide();
        $(".tabular-view").show();
    });
//when click graphical view button
    $(document).on("click", ".btn-chart-view", function () {
        $(".btn-chart-view").hide();
        $(".btn-tabular-view").show();
        $(".btn-print").hide();
        $(".btn-pdf").hide();
        $(".chart-view").show();
        $(".tabular-view").hide();
    });
//table header fix
    $("#fixTable").tableHeadFixer();
//        $('.sample').floatingScrollbar();

//shipment details modal
    $(".shipment-details").on("click", function (e) {
        e.preventDefault();
        var shipmentId = $(this).attr("data-id");
        $.ajax({
            url: "{{ URL::to('/salesVolumeReport/getShipmentDetails')}}",
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
    var colors = ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE'
                , '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3'
                , '#E87E04', '#D91E18', '#8E44AD', '#555555'];
//***************start :: country wise sales volume chart**********
    var countryWiseSalesVolumeChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "@lang('label.SALES_VOLUME')",
                data: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $volume = $val['total_sales_volyme'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "@lang('label.SALES_VOLUME') (@lang('label.COUNTRY_WISE'))",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $country = $countryList[$countryId] ?? 0;
        echo '"' . $country . '", ';
    }
}
?>
            ],
            title: {
                text: "@lang('label.COUNTRY')",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "@lang('label.VOLUME') (@lang('label.UNIT'))"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  parseFloat(val).toFixed(2) + "@lang('label.UNIT')"
                }
            }
        }
    };
    var countryWiseSalesVolumeChart = new ApexCharts(document.querySelector("#countryWiseSalesVolumeChart"), countryWiseSalesVolumeChartOptions);
    countryWiseSalesVolumeChart.render();
//***************end :: country wise sales volume chart**********

//***************start :: country wise sales amount chart**********
    var countryWiseSalesAmountChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "@lang('label.SALES_AMOUNT')",
                data: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $amount = $val['total_sales_amount'] ?? 0;
        echo "'$amount',";
    }
}
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "@lang('label.SALES_AMOUNT') (@lang('label.COUNTRY_WISE'))",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $country = !empty($countryList[$countryId]) ? $countryList[$countryId] : '';
        echo '"' . $country . '", ';
    }
}
?>
            ],
            title: {
                text: "@lang('label.COUNTRY')",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "@lang('label.AMOUNT') ($)"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + parseFloat(val).toFixed(2)
                }
            }
        }
    };
    var countryWiseSalesAmountChart = new ApexCharts(document.querySelector("#countryWiseSalesAmountChart"), countryWiseSalesAmountChartOptions);
    countryWiseSalesAmountChart.render();
//***************end :: country wise sales volume chart**********


//***************start :: income breakdown chart**********
    var incomeBreakdownChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "@lang('label.AMOUNT')",
                data: [
<?php
$totalKonitaNetComsn = $comsnIncomeArr['total_konita_net_csmn'] ?? 0;
$totalKonitaComsn = $comsnIncomeArr['total_konita_cmsn'] ?? 0;
$totalAdminCost = $comsnIncomeArr['total_admin_cost'] ?? 0;
$totalComsn = $comsnIncomeArr['total_cmsn'] ?? 0;
echo $totalKonitaNetComsn . ', ' . $totalKonitaComsn . ', ' . $totalAdminCost . ', ' . $totalComsn;
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "@lang('label.INCOME_BREAKDOWN')",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
                "@lang('label.KONITA_NET_CMSN')", "@lang('label.KONITA_CMSN')", "@lang('label.ADMIN_COST')", "@lang('label.TOTAL_COMMISSION')",
            ],
            title: {
                text: "@lang('label.BREAKDOWN')",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "@lang('label.AMOUNT') ($)"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + parseFloat(val).toFixed(2)
                }
            }
        }
    };
    var incomeBreakdownChart = new ApexCharts(document.querySelector("#incomeBreakdownChart"), incomeBreakdownChartOptions);
    incomeBreakdownChart.render();
//***************end :: income breakdown chart**********commissionBreakdownPie


//***************start :: commission breakdown chart**********
    var commissionBreakdownPieOptions = {
        series: [
<?php
$conitaComsn = $comsnIncomeArr['konita_cmsn'] ?? 0;
$salesPersonComsn = $comsnIncomeArr['sales_person_cmsn'] ?? 0;
$buyerComsn = $comsnIncomeArr['buyer_cmsn'] ?? 0;
$rebateComsn = $comsnIncomeArr['rebate_cmsn'] ?? 0;
$principalComsn = $comsnIncomeArr['principal_cmsn'] ?? 0;
echo $conitaComsn . ', ' . $salesPersonComsn . ', ' . $buyerComsn . ', ' . $rebateComsn . ', ' . $principalComsn;
?>
        ],
        labels: ["@lang('label.KONITA_CMSN')", "@lang('label.SALES_PERSON_COMMISSION')"
                    , "@lang('label.BUYER_COMMISSION')", "@lang('label.REBATE_COMMISSION')"
                    , "@lang('label.PRINCIPLE_COMMISSION')"],
        chart: {
            width: 500,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#525E64"],
        title: {
            text: "@lang('label.COMMISSION_BREAKDOWN')",
            align: 'left'
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            fontSize: '11px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 600,
            formatter: function (val, opts) {
                var indx = opts.w.globals.series[opts.seriesIndex];
                return val + ': $' + parseFloat(indx).toFixed(2)
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
                    return  '$' + parseFloat(val).toFixed(2)
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
    var commissionBreakdownPie = new ApexCharts(document.querySelector("#commissionBreakdownPie"), commissionBreakdownPieOptions);
    commissionBreakdownPie.render();
//***************end :: commission breakdown chart**********commissionBreakdownPie

});
function trimString(str) {
    var dot = '';
    if (str.length > 20) {
        dot = '...';
    }

    var returnStr = str.substring(0, 20) + dot;
    return returnStr;
}
</script>
@stop