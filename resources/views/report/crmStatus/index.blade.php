@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CRM_STATUS_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!$targetArr->isEmpty())
                    @if(!empty($userAccessArr[83][6]))
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @if(!empty($userAccessArr[83][9]))
                    <!--                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>-->
                    @endif
                    @endif 
                    @endif 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'crmStatusReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('update_from_date', Request::get('update_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('update_from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('update_to_date', Request::get('update_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('update_to_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyer">@lang('label.BUYER')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer',  $buyerArr, Request::get('buyer'), ['class' => 'form-control js-source-states','id'=>'buyer']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="sourceId">@lang('label.SOURCE')</label>
                        <div class="col-md-8">
                            {!! Form::select('source_id',  $sourceList, Request::get('source_id'), ['class' => 'form-control js-source-states','id'=>'sourceId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="createdBy">@lang('label.CREATED_BY')</label>
                        <div class="col-md-8">
                            {!! Form::select('created_by',  $employeeList, Request::get('created_by'), ['class' => 'form-control js-source-states','id'=>'createdBy']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status', $statusList, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="assignedTo">@lang('label.ASSIGNED_TO')</label>
                        <div class="col-md-8">
                            {!! Form::select('assigned_to', $memberList, Request::get('assigned_to'), ['class' => 'form-control js-source-states','id'=>'assignedTo']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="product">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            {!! Form::select('product', $productArrFilter, Request::get('product'), ['class' => 'form-control js-source-states','id'=>'product']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brand">@lang('label.BRAND')</label>
                        <div class="col-md-8">
                            {!! Form::select('brand', $brandArr, Request::get('brand'), ['class' => 'form-control js-source-states','id'=>'brand']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
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
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($fromDate) ? Helper::formatDate($fromDate) : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($toDate) ? Helper::formatDate($toDate) : __('label.N_A') }} |</strong> 
                            {{__('label.BUYER')}} : <strong>{{  !empty($buyerArr[Request::get('buyer')]) && Request::get('buyer') != 0 ? $buyerArr[Request::get('buyer')] : __('label.N_A') }} |</strong> 
                            {{__('label.SOURCE')}} : <strong>{{  !empty($sourceList[Request::get('source_id')]) && Request::get('source_id') != 0 ? $sourceList[Request::get('source_id')] : __('label.N_A') }} |</strong> 
                            {{__('label.CREATED_BY')}} : <strong>{{  !empty($employeeList[Request::get('created_by')]) && Request::get('created_by') != 0 ? $employeeList[Request::get('created_by')] : __('label.N_A') }} |</strong> 
                            {{__('label.ASSIGNED_TO')}} : <strong>{{  !empty($memberList[Request::get('assigned_to')]) && Request::get('assigned_to') != 0 ? $memberList[Request::get('assigned_to')] : __('label.N_A') }} |</strong> 
                            {{__('label.STATUS')}} : <strong>{{  !empty($statusList[Request::get('status')]) && Request::get('status') != 0 ? $statusList[Request::get('status')] : __('label.N_A') }} |</strong> 
                            {{__('label.PRODUCT')}} : <strong>{{  !empty($productArrFilter[Request::get('product')]) && Request::get('product') != 0 ? $productArrFilter[Request::get('product')] : __('label.N_A') }} |</strong> 
                            {{__('label.BRAND')}} : <strong>{{  !empty($brandArr[Request::get('brand')]) && Request::get('brand') != 0 ? $brandArr[Request::get('brand')] : __('label.N_A') }} </strong> 
                        </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="padding-left-0">
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') .': ' . ($opportunityCountArr['new'] ?? 0) !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-steel">{!! __('label.IN_PROGRESS') .': ' . ($opportunityCountArr['in_progress'] ?? 0) !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-soft">{!! $activityStatusList['1'] .': ' . ($opportunityCountArr['dead'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-chambray">{!! $activityStatusList['2'] .': ' . ($opportunityCountArr['unreachable'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-hoki">{!! $activityStatusList['3'] .': ' . ($opportunityCountArr['answering_machine'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-blue-soft">{!! $activityStatusList['4'] .': ' . ($opportunityCountArr['sdc'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-steel">{!! $activityStatusList['5'] .': ' . ($opportunityCountArr['reached'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm  label-yellow-mint">{!! $activityStatusList['6'] .': ' . ($opportunityCountArr['not_interested'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-pink">{!! $activityStatusList['8'] .': ' . ($opportunityCountArr['not_booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple-sharp">{!! $activityStatusList['9'] .': ' . ($opportunityCountArr['halt'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-sharp">{!! $activityStatusList['10'] .': ' . ($opportunityCountArr['prospective'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-mint">{!! $activityStatusList['11'] .': ' . ($opportunityCountArr['none'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-yellow-casablanca">{!! $activityStatusList['12'] .': ' . ($opportunityCountArr['irrelevant'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-soft">{!! __('label.BOOKED') .': ' . ($opportunityCountArr['booked'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-purple">{!! __('label.DISPATCHED') .': ' . ($opportunityCountArr['dispatched'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-green-seagreen">{!! __('label.APPROVED') .': ' . ($opportunityCountArr['approved'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-mint">{!! __('label.DENIED') .': ' . ($opportunityCountArr['denied'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-flamingo">{!! __('label.CANCELLED') .': ' . ($opportunityCountArr['cancelled'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-grey-cascade">{!! __('label.VOID') .': ' . ($opportunityCountArr['void'] ?? 0)  !!}</span>
                        </li>
                        <li class="list-style-item-none display-inline-block margin-top-10">
                            <span class="label bold label-sm label-red-thunderbird">{!! __('label.REVOKED') .': ' . ($opportunityCountArr['revoked'] ?? 0)  !!}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive tableFixHead sample max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.SL_NO')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.BUYER')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.SOURCE')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.DATE_OF_CREATION')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.LAST_UPDATED')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.CREATED_BY')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.ASSIGNED_TO')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.REMARKS')</th>
                                    <th class="vcenter text-center" colspan="3">@lang('label.BUYER_CONTACT_PERSON')</th>
                                    <th class="vcenter text-center" colspan="8">@lang('label.PRODUCT_INFORMATION')</th>
                                    <th class="vcenter text-center" rowspan="2">@lang('label.STATUS')</th>
                                </tr>
                                <tr>
                                    <th class="vcenter text-center">@lang('label.NAME')</th>
                                    <th class="vcenter text-center">@lang('label.DESIGNATION')</th>
                                    <th class="vcenter text-center">@lang('label.PHONE')</th>
                                    <th class="vcenter text-center">@lang('label.PRODUCT')</th>
                                    <th class="vcenter text-center">@lang('label.BRAND')</th>
                                    <th class="vcenter text-center">@lang('label.GRADE')</th>
                                    <th class="vcenter text-center">@lang('label.ORIGIN')</th>
                                    <th class="vcenter text-center">@lang('label.GSM')</th>
                                    <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                    <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                    <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $page = Request::get('page');
                                $page = empty($page) ? 1 : $page;
                                $sl = ($page - 1) * Session::get('paginatorCount');
                                ?>
                                @foreach($targetArr as $target)
                                <?php
                                if ($target->buyer_has_id == '0') {
                                    $buyer = $target->buyer;
                                } elseif ($target->buyer_has_id == '1') {
                                    $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                                }

                                $iconActivityLog = '';
                                if (!empty($hasActivityLog)) {
                                    if (in_array($target->id, $hasActivityLog)) {
                                        $iconActivityLog = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips set-activity-log vcenter"'
                                                . ' href="#modalSetActivity"  data-toggle="modal" title="' . __('label.CLICK_TO_VIEW_ACTIVITY_LOG') . '" 
                                            data-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="vcenter text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! ++$sl.$iconActivityLog !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $buyer ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->source ?? '' !!}</td>
                                    <td class="vcenter text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</td>
                                    <td class="vcenter text-center" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! !empty($target->updated_at) ? Helper::formatDate($target->updated_at) : '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->opportunity_creator ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $assignedPersonList[$target->member_id] ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $target->remarks ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['name'] ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['designation'] ?? '' !!}</td>
                                    <td class="vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">{!! $contactArr[$target->id]['phone'] ?? '' !!}</td>

                                    @if(!empty($productArr[$target->id]))
                                    <?php $i = 0; ?>

                                    @foreach($productArr[$target->id] as $pKey => $pInfo)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    //product
                                    if ($pInfo['product_has_id'] == '0') {
                                        $product = $pInfo['product'];
                                    } elseif ($pInfo['product_has_id'] == '1') {
                                        $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                    }
                                    //brand
                                    if ($pInfo['brand_has_id'] == '0') {
                                        $brand = $pInfo['brand'];
                                    } elseif ($pInfo['brand_has_id'] == '1') {
                                        $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                    }
                                    //grade
                                    if ($pInfo['grade_has_id'] == '0') {
                                        $grade = $pInfo['grade'];
                                    } elseif ($pInfo['grade_has_id'] == '1') {
                                        $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                    }

                                    $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                    $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                    $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                    $statusTitle = __('label.FINAL_PRODUCT');
                                    ?>
                                    <td class="vcenter">
                                        {!! $product ?? __('label.N_A') !!}
                                        @if(!empty($pInfo['final']) && $pInfo['final'] == '1')
                                        <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ $statusTitle }}">

                                        </button>
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $brand ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $grade ?? __('label.N_A') !!}</td>
                                    <td class="text-center vcenter">{!! $country !!}</td>
                                    <td class="vcenter">{!! $pInfo['gsm'] ?? '' !!}</td>
                                    <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                    <td class="text-right vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                    <td class="text-right vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                    @if($i == 0)
                                    <td class="text-center vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">
                                        @if($target->status == '0')
                                        <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                        @elseif($target->status == '1')
                                        @if($target->revoked_status == '0')
                                        @if($target->last_activity_status == '0')
                                        <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                        @elseif($target->last_activity_status == '1')
                                        <span class="label label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '2')
                                        <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '3')
                                        <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '4')
                                        <span class="label label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '5')
                                        <span class="label label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '6')
                                        <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '8')
                                        <span class="label label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '9')
                                        <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '10')
                                        <span class="label label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '11')
                                        <span class="label label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '12')
                                        <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @endif
                                        @elseif($target->revoked_status == '1')
                                        <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                        @endif
                                        @elseif($target->status == '2')
                                        @if($target->dispatch_status == '0')
                                        <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                        @elseif($target->dispatch_status == '1')
                                        @if($target->approval_status == '0')
                                        <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                        @elseif($target->approval_status == '1')
                                        <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                        @elseif($target->approval_status == '2')
                                        <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                        @endif
                                        @endif
                                        @elseif($target->status == '3')
                                        <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                        @elseif($target->status == '4')
                                        <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                        @endif
                                    </td>
                                    @endif

                                    <?php
                                    if ($i > ($productRowspanArr[$target->id] - 1)) {
                                        echo '</tr>';
                                    }
                                    $i++;
                                    ?>
                                    @endforeach
                                    @else
                                    <td class="vcenter"></td>
                                    <td class="vcenter"></td>
                                    <td class="vcenter"></td>
                                    <td class="text-center vcenter"></td>
                                    <td class="vcenter"></td>
                                    <td class="text-right vcenter"></td>
                                    <td class="text-right vcenter"></td>
                                    <td class="text-right vcenter"></td>
                                    <td class="text-center vcenter" rowspan="{!! $productRowspanArr[$target->id] !!}">
                                        @if($target->status == '0')
                                        <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                        @elseif($target->status == '1')
                                        @if($target->revoked_status == '0')
                                        @if($target->last_activity_status == '0')
                                        <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                        @elseif($target->last_activity_status == '1')
                                        <span class="label label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '2')
                                        <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '3')
                                        <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '4')
                                        <span class="label label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '5')
                                        <span class="label label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '6')
                                        <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '8')
                                        <span class="label label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '9')
                                        <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '10')
                                        <span class="label label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '11')
                                        <span class="label label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @elseif($target->last_activity_status == '12')
                                        <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                        @endif
                                        @elseif($target->revoked_status == '1')
                                        <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                        @endif
                                        @elseif($target->status == '2')
                                        @if($target->dispatch_status == '0')
                                        <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                        @elseif($target->dispatch_status == '1')
                                        @if($target->approval_status == '0')
                                        <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                        @elseif($target->approval_status == '1')
                                        <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                        @elseif($target->approval_status == '2')
                                        <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                        @endif
                                        @endif
                                        @elseif($target->status == '3')
                                        <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                        @elseif($target->status == '4')
                                        <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="19">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!-- opportunity set activity log -->
<div class="modal fade" id="modalSetActivity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showActivityLog"></div>
    </div>
</div>
<!-- Modal end -->


<script type="text/javascript">
    $(function () {
        //table header fix
        $("#fixTable").tableHeadFixer();
        //        $('.sample').floatingScrollbar();

        //******************** Start :: activity log ***********************
        //activity log modal
        $(".set-activity-log").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr('data-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "{{ URL::to('crmStatusReport/getOpportunityActivityLogModal')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId,
                    history_status: historyStatus
                },
                beforeSend: function () {
                    $("#showActivityLog").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showActivityLog").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showActivityLog'),width: '100%'});
                    $('.form_datetime').datetimepicker({
                        autoclose: true,
                        todayHighlight: true,
                    });
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //******************** END :: activity log ***********************

    });
</script>
@stop