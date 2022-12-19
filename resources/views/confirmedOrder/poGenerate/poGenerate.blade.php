@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-sticky-note-o"></i>
                @lang('label.PO_GENERATE')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(!empty($poInfo))
                    <a class="btn btn-xs btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/poGenerate/'.$inquiryId.'?view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <!--                    <a class="btn btn-xs btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/poGenerate/'.$inquiryId.'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>-->
                    @endif 
                </span>
            </div>

        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Proced-->
                {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'poGenerateForm','class' => 'form-horizontal')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!} 
                {!! Form::hidden('po_generate_id', !empty($poInfo->id)?$poInfo->id:null) !!} 

                <!--COMPANY INFORAMTION-->
                <!--header-->
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

                <div class="col-md-12">
                    <div class="col-md-10 margin-bottom-10">
                        <div class="text-center bold uppercase">
                            <span class="inv-border-bottom">@lang('label.PURCHASE_ORDER')</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td class="vcenter bold">@lang('label.DEALER_AGENT')</td>
                                    <td class="vcenter width-250">
                                        <span>@lang('label.DATE'):<span class="text-danger">*</span></span>
                                        <?php
                                        $poData = !empty($target->po_date) ? Helper::formatDate($target->po_date) : null;
                                        ?>
                                        @if(!empty($poInfo) && $poInfo->status== '2')
                                        <span>{{!empty($poInfo->po_date) ? Helper::formatDate($poInfo->po_date) : ''}}</span>
                                        @else
                                        <?php
                                        $finalDate = !empty($poInfo->po_date) ? Helper::formatDate($poInfo->po_date) : $poData;
                                        ?>
                                        <div class="input-group width-inherit date datepicker2">
                                            {!! Form::text('po_date', $finalDate, ['id'=> 'poDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="poDate">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">
                                        <span class="bold">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                        <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                        <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                        <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.'.':''}}</span>
                                    </td>
                                    <td class="vcenter">@lang('label.PO_NO'): {{!empty($target->purchase_order_no)?$target->purchase_order_no:''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--SUPPLIER AND BUYER INFORMATION-->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th class="vcenter">@lang('label.SUPPLIER')</th>
                            <th class="vcenter">@lang('label.BUYER') <span class="text-danger">*</span></th>
                            </thead>
                            <tbody>
                            <td class="vcenter" width="40%">
                                <span>{{!empty($supplierInfo->supplier_name)?$supplierInfo->supplier_name:''}}</span><br/>
                                <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                <span>{{!empty($supplierInfo->country_name)?$supplierInfo->country_name.'.':''}}</span>
                            </td>
                            <td class="vcenter" width="60%">
                                @if(empty($poInfo) || $poInfo->status== '1')
                                <!--Shipment Address-->
                                <div class="form">
                                    <div class="col-md-3 margin-top-10">
                                        <?php
                                        $shipmentAddress1 = $shipmentAddress2 = '';
                                        if ($shipmentAddressStatus == '1') {
                                            $shipmentAddress1 = 'checked';
                                        } elseif ($shipmentAddressStatus == '2') {
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

                                @if($shipmentAddressStatus == '1')
                                <div class="form" id="addressShow">
                                    <div class="col-md-3">
                                        <label class="control-label" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                        {!! Form::text('head_office_address', !empty($buyerOfficeAddress)? $buyerOfficeAddress:null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
                                        <span class="text-danger">{{ $errors->first('head_office_address') }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="form" id="addressShow" style="display: none">
                                    <div class="col-md-3">
                                        <label class="control-label" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                        {!! Form::text('head_office_address', !empty($buyerInfo->head_office_address)?$buyerInfo->head_office_address:null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
                                        <span class="text-danger">{{ $errors->first('head_office_address') }}</span>
                                    </div>
                                </div>
                                @endif
                                <!--factory-->
                                @if($shipmentAddressStatus == '2')
                                <div class="form" id="factoryShow">
                                    <div class="col-md-3">
                                        <label class="control-label" for="factoryId">@lang('label.FACTORY') :<span class="text-danger"> *</span></label>
                                        {!! Form::select('factory_id', $factoryList, !empty($factoryId)?$factoryId:null, ['class' => 'form-control js-source-states', 'id' => 'factoryId']) !!}
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="form" id="factoryShow" style="display: none">
                                    <div class="col-md-3">
                                        <label class="control-label" for="factoryId">@lang('label.FACTORY') :<span class="text-danger"> *</span></label>
                                        {!! Form::select('factory_id', $factoryList, null, ['class' => 'form-control js-source-states', 'id' => 'factoryId']) !!}
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    </div>
                                </div>
                                @endif
                                <!--end of factory-->
                                <!--endof div shipment info-->

                                <!--end of shipment Address-->
                                @endif

                                <span>{{!empty($buyerInfo->name)?$buyerInfo->name:''}}</span><br/>
                                <span id="buyerFactoryAddress">{!! str_replace(array("\r\n", "\r", "\n"), "<br />",$buyerOfficeAddress) !!}</span>
                            </td>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--DATA INPUT INFO-->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <!--step 1-->
                                <tr>
                                    <td class="vcenter bold">@lang('label.PRECARRIER') <span class="text-danger">*</span></td>
                                    <td class="vcenter bold">@lang('label.SHIPPINGTERMS') <span class="text-danger">*</span></td>
                                    <td class="vcenter bold">@lang('label.FINAL_DESTINATION') <span class="text-danger">*</span></td>
                                    <td class="vcenter bold">@lang('label.TERMS_OF_PAYMENT') <span class="text-danger">*</span></td>
                                    <td class="vcenter bold">@lang('label.ESTIMATED_DELIVERY') <span class="text-danger">*</span></td>
                                </tr>
                                @if(!empty($poInfo) && $poInfo->status== '2')
                                <tr>
                                    <td class="vcenter ">{{!empty($poInfo->pre_carrier_name)?$poInfo->pre_carrier_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($poInfo->shipping_terms_name)?$poInfo->shipping_terms_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($poInfo->final_destination)?$poInfo->final_destination:''}}</td> 
                                    <td class="vcenter ">{{!empty($poInfo->payment_terms_name)?$poInfo->payment_terms_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($poInfo->delivery_date) ? Helper::formatDate($poInfo->delivery_date):''}}</td> 
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('pre_carrier_id', $preCarrierList,!empty($poInfo->pre_carrier_id)?$poInfo->pre_carrier_id:null, ['class' => 'form-control js-source-states','id'=>'preCarrier']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('shipping_term_id', $shippingTermList,!empty($poInfo->shipping_term_id)?$poInfo->shipping_term_id:null, ['class' => 'form-control js-source-states','id'=>'shippingTerm']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::text('final_destination',!empty($poInfo->final_destination)?$poInfo->final_destination:null, ['id'=> 'finalDestination', 'class' => 'form-control']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('payment_term_id', $PaymentTermList,!empty($poInfo->payment_term_id)?$poInfo->payment_term_id:null, ['class' => 'form-control js-source-states','id'=>'paymentTerm']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="input-group width-inherit date datepicker2" style="z-index: 9994 !important">
                                            <?php
                                            $currentDate = date('d F Y');
                                            $deliveryDate = !empty($poInfo->delivery_date) ? Helper::formatDate($poInfo->delivery_date) : $currentDate;
                                            ?>
                                            {!! Form::text('delivery_date',$deliveryDate, ['id'=> 'deliveryDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="deliveryDate">
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
                                <!--step 2-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--RW BREAKDOWN DATA-->
                <!--first loop-->
                <?php
                $flag = 1;
                ?>
                @if(!empty($targetArr))
                @foreach($targetArr as $id=>$target)
                @if(!empty($target['gsm_details']))
                <?php
                $flag = 0;
                ?>
                @break
                @endif
                @endforeach
                @endif

                <!--final loop-->
                @if(!empty($targetArr))
                @foreach($targetArr as $id=>$target)
                @if(!empty($target['gsm_details']))
                @if($target['format'] == '1')
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="vcenter">@lang('label.DESCRIPTION_OF_GOODS')</th>
                                    <th class="vcenter">@lang('label.HS_CODE') <span class="text-danger">*</span></th>
                                    <th class="vcenter">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                    <th class="vcenter">@lang('label.CORE_AND_DIA')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                    <th class="text-center vcenter">@lang('label.GSM')</th>
                                    @if(!empty($rwParameter[$id]))
                                    @foreach($rwParameter[$id] as $rwId=>$rwName)
                                    <th class="text-center vcenter">@lang('label.RW')&nbsp;({{$rwName}})</th>
                                    @endforeach
                                    @endif
                                    <th class="text-center vcenter">@lang('label.UNIT_PRICE')&nbsp;($)</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_PRICE')&nbsp;($)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">
                                <span class="bold">{{$target['product_name']}}</span><br/>
                                <span class="bold"> @lang('label.BRAND'):</span> <span>{{$target['brand_name']}}</span><br/>
                                @if(!empty($target['grade_name']))
                                <span class="bold">@lang('label.GRADE'):</span> <span>{{$target['grade_name']}}</span>
                                @endif
                            </td>
                            <td class="vcenter width-200" rowspan="{{$gsmDataCountSum[$id]}}">
                                <div class="width-inherit">
                                    {!! Form::select('hs_code['.$id.'][]', $target['hs_code'],!empty($hsCodeArr[$id])?$hsCodeArr[$id]:null, ['class' => 'form-control mt-multiselect btn btn-default','id'=>'hsCode', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                                </div>
                            </td>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['country_name']}}</td>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['core_and_dia']}}&nbsp;{{!empty($rwInfo[$id])?$rwInfo[$id]:''}}</td>
                            @if(!empty($target['gsm_details']))
                            <?php
                            $i = 0;
                            ?>
                            @foreach($target['gsm_details'] as $gsmId=>$gsmVal)
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }

                            $j = 0;
                            ?>



                            @foreach($gsmVal as $values)
                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            ?>
                            <td class="vcenter text-right">
                                {{!empty($values['quantity'])?$values['quantity']:''}}
                            </td>
                            <td class="vcenter text-right">
                                {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                            </td>
                            @if(!empty($rwParameter[$id]))
                            @foreach($rwParameter[$id] as $rwId=>$rwName)
                            <td class="vcenter text-right">
                                {{!empty($values[$rwId])?$values[$rwId]:''}}
                            </td>
                            @endforeach
                            @endif
                            <?php
                            $gradeId = !empty($target['grade_id']) ? $target['grade_id'] : 0;
                            $unitPrice = !empty($finalPriceArr[$id][$target['product_id']][$target['brand_id']][$gradeId]['unit_price'][$target['gsm_info'][$gsmId]]) ? $finalPriceArr[$id][$target['product_id']][$target['brand_id']][$gradeId]['unit_price'][$target['gsm_info'][$gsmId]] : 0;
                            ?>
                            <td class="vcenter text-right">${{$unitPrice}}</td>
                            <?php
                            $totalPrice = (!empty($values['quantity']) ? $values['quantity'] : 0) * (!empty($unitPrice) ? $unitPrice : 0);
                            $grandTotal = !empty($grandTotal) ? $grandTotal : 0;
                            $grandTotal += $totalPrice;
                            ?>
                            <td class="vcenter text-right">${{$totalPrice}}</td>
                            </tr>
                            <?php
                            $j++;
                            ?>
                            @endforeach
                            </tr>
                            <?php
                            $i++;
                            ?>
                            @endforeach
                            @endif
                            </tr>
                            <tr>
                                <td class="bold text-right" colspan="4">@lang('label.TOTAL')</td>
                                <td class="bold text-right">{{!empty($totalQuantity[$id])?$totalQuantity[$id]:''}} &nbsp;{{$target['unit_name']}}</td>
                                <td class="bold text-right" colspan="{{3+count($rwParameter[$id])}}">${{ Helper::numberFormat2Digit($grandTotal) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @elseif($target['format'] == '2')
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="vcenter">@lang('label.DESCRIPTION_OF_GOODS')</th>
                                    <th class="vcenter">@lang('label.HS_CODE') <span class="text-danger">*</span></th>
                                    <th class="vcenter">@lang('label.COUNTRY_OF_ORIGIN')</th>
                                    <th class="vcenter">@lang('label.CORE_AND_DIA')</th>
                                    <th class="text-center vcenter">@lang('label.GSM')</th>
                                    @if(!empty($rwParameter[$id]))
                                    @foreach($rwParameter[$id] as $rwId=>$rwName)
                                    <th class="text-center vcenter">@lang('label.RW')&nbsp;({{$rwName}})</th>
                                    @endforeach
                                    @endif
                                    <th class="text-center vcenter">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                    <th class="text-center vcenter">@lang('label.UNIT_PRICE')&nbsp;($)</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_PRICE')&nbsp;($)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">
                                <span class="bold">{{$target['product_name']}}</span><br/>
                                <span class="bold"> @lang('label.BRAND'):</span> <span>{{$target['brand_name']}}</span><br/>
                                @if(!empty($target['grade_name']))
                                <span class="bold">@lang('label.GRADE'):</span> <span>{{$target['grade_name']}}</span>
                                @endif
                            </td>
                            <td class="vcenter width-200" rowspan="{{$gsmDataCountSum[$id]}}">
                                <div class="width-inherit">
                                    {!! Form::select('hs_code['.$id.'][]', $target['hs_code'],!empty($hsCodeArr[$id])?$hsCodeArr[$id]:null, ['class' => 'form-control mt-multiselect btn btn-default','id'=>'hsCode', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                                </div>
                            </td>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['country_name']}}</td>
                            <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['core_and_dia']}}&nbsp;{{!empty($rwInfo[$id])?$rwInfo[$id]:''}}</td>
                            @if(!empty($target['gsm_details']))
                            <?php
                            $i = 0;
                            ?>
                            @foreach($target['gsm_details'] as $gsmId=>$gsmVal)
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }

                            $j = 0;
                            ?>

                            <td class="vcenter text-right" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                            </td>

                            @foreach($gsmVal as $values)
                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            ?>
                            @if(!empty($rwParameter[$id]))
                            @foreach($rwParameter[$id] as $rwId=>$rwName)
                            <td class="vcenter text-right">
                                {{!empty($values[$rwId])?$values[$rwId]:''}}
                            </td>
                            @endforeach
                            @endif
                            <td class="vcenter text-right">
                                {{!empty($values['quantity'])?$values['quantity']:''}}
                            </td>


                            <!--unit Price and Total price-->
                            @if($j == 0 && $i==0)

                            <td class="vcenter text-right" rowspan="{{$gsmDataCountSum[$id]}}">${{$target['unit_price']}}</td>
                            <td class="vcenter text-right" rowspan="{{$gsmDataCountSum[$id]}}">${{$target['total_price']}}</td>
                            @endif
                            <!--End of td unit Price and Total price-->

                            </tr>
                            <?php
                            $j++;
                            ?>
                            @endforeach
                            </tr>
                            <?php
                            $i++;
                            ?>
                            @endforeach
                            @endif
                            </tr>
                            <tr>
                                <td class="bold text-right" colspan="{{5+count($rwParameter[$id])}}">@lang('label.TOTAL')</td>
                                <td class="bold text-right">{{!empty($totalQuantity[$id])?$totalQuantity[$id]:''}} &nbsp;{{$target['unit_name']}}</td>
                                <td class="bold text-right">${{$target['unit_price']}}</td>
                                <td class="bold text-right">${{$target['total_price']}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @else
                @if($flag == 1)
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <td class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @endif
                @endforeach
                @endif
                <!--ENDOF RW BREAKDOWN DATA-->
            </div>

            @if(!empty($poInfo) && $poInfo->status== '2')
            @if($poInfo->summary_status == '1')
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('label.PRODUCT')</th>   
                                <th>@lang('label.REALIZATION_PRICE')</th>   
                                <th>@lang('label.KONITA_CMSN')</th>   
                                <th>@lang('label.REBATE_BUYER_COMMISSION')</th>   
                                <th>@lang('label.TOTAL_PRICE')</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($summaryArr))
                            @foreach($summaryArr as $inquiryDetailsId=>$item)
                            <tr>
                                <td class="vcenter">
                                    <span>{{$item['product_name']}}</span><br/>
                                    <span>@lang('label.BRAND') :{{$item['brand_name']}}</span><br/>
                                    <span>@lang('label.GRADE') :{{!empty($item['grade_name'])?$item['grade_name']:''}}</span>
                                </td>
                                <td class="vcenter text-right">
                                    <span>$ {{!empty($poSummaryArr[$inquiryDetailsId]['realization_price'])?$poSummaryArr[$inquiryDetailsId]['realization_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                </td>
                                <td class="vcenter text-right">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['konita_commission'])?$poSummaryArr[$inquiryDetailsId]['konita_commission']:''}}&nbsp;/{{$item['unit_name']}}</span>

                                </td>
                                <td class="vcenter text-right">
                                    <span>${{!empty($poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission'])?$poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission']:''}}&nbsp;/{{$item['unit_name']}}</span>

                                </td>
                                <td class="vcenter text-right">
                                    <span>$ {{!empty($poSummaryArr[$inquiryDetailsId]['unit_price'])?$poSummaryArr[$inquiryDetailsId]['unit_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @else
            <div class="row">
                <div class="col-md-12 margin-bottom-10">
                    <div class="col-md-7 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('summary_status',1,!empty($poInfo->summary_status)?$poInfo->summary_status:null, ['id' => 'summaryStatus', 'class'=> 'md-check']) !!}
                        <label for="summaryStatus">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_SUMMARY')</span>
                    </div>
                </div>
                @if(!empty($poInfo->summary_status) && $poInfo->summary_status=='1' )
                <div class="col-md-12" id="summaryIdShow">
                    @else
                    <div class="col-md-12" id="summaryIdShow" style="display: none">
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('label.PRODUCT')</th>   
                                        <th>@lang('label.REALIZATION_PRICE')</th>   
                                        <th>@lang('label.KONITA_CMSN')</th>   
                                        <th>@lang('label.REBATE_BUYER_COMMISSION')</th>  
                                        <th>@lang('label.TOTAL_PRICE')</th>   
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($summaryArr))

                                    @foreach($summaryArr as $inquiryDetailsId=>$item)
                                    <?php
                                    $unitPrice = !empty($item['unit_price']) ? $item['unit_price'] : 0;
                                    ?>
                                    <tr>
                                        <td class="vcenter width-250">
                                            <div class="width-inherit">
                                                <span>{{$item['product_name']}}</span><br/>
                                                <span>@lang('label.BRAND') :{{$item['brand_name']}}</span><br/>
                                                <span>@lang('label.GRADE') :{{!empty($item['grade_name'])?$item['grade_name']:''}}</span><br/>
                                                <span>@lang('label.GSM') :{{!empty($item['gsm'])?$item['gsm']:''}}</span>
                                            </div>
                                        </td>
                                        <td class="vcenter width-250">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('summaryArr['.$inquiryDetailsId.'][realization_price]',!empty($poSummaryArr[$inquiryDetailsId]['realization_price'])?$poSummaryArr[$inquiryDetailsId]['realization_price']:$unitPrice, ['id'=> 'realizationPrice_'.$inquiryDetailsId, 'data-id'=>$inquiryDetailsId, 'class' => 'form-control text-input-width-100-per text-right integer-decimal-only realization-price','autocomplete' => 'off','readonly']) !!} 
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">${{!empty($item['realization_price'])?$item['realization_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                            </div>
                                        </td>
                                        <td class="vcenter width-250">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('summaryArr['.$inquiryDetailsId.'][konita_commission]', !empty($poSummaryArr[$inquiryDetailsId]['konita_commission'])?$poSummaryArr[$inquiryDetailsId]['konita_commission']:null, ['id'=> 'konitaCommission_'.$inquiryDetailsId, 'data-id'=>$inquiryDetailsId,'data-unitPrice'=>$unitPrice, 'class' => 'form-control text-input-width-100-per text-right integer-decimal-only konita-commission','autocomplete' => 'off']) !!} 
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">${{(!empty($prevComsn[$inquiryDetailsId]['konita_commission']) ? $prevComsn[$inquiryDetailsId]['konita_commission'] : (!empty($prevComsn[0]['konita_commission']) ? $prevComsn[0]['konita_commission'] : 0))}}&nbsp;/{{$item['unit_name']}}</span>
                                            </div>
                                        </td>
                                        <td class="vcenter width-250">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('summaryArr['.$inquiryDetailsId.'][rebate_buyer_commission]', !empty($poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission'])?$poSummaryArr[$inquiryDetailsId]['rebate_buyer_commission']:null, ['id'=> 'rebateBuyerCommission_'.$inquiryDetailsId, 'data-id'=>$inquiryDetailsId, 'data-unitPrice'=>$unitPrice, 'class' => 'form-control text-input-width-100-per text-right integer-decimal-only rebateBuyer-commission','autocomplete' => 'off']) !!} 
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">${{(!empty($prevComsn[$inquiryDetailsId]['rebate_buyer_commission']) ? $prevComsn[$inquiryDetailsId]['rebate_buyer_commission'] : (!empty($prevComsn[0]['rebate_buyer_commission']) ? $prevComsn[0]['rebate_buyer_commission'] : 0))}}&nbsp;/{{$item['unit_name']}}</span>
                                            </div>
                                        </td>
                                        <td class="vcenter width-250">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('summaryArr['.$inquiryDetailsId.'][unit_price]', !empty($unitPrice)?$unitPrice:null, ['id'=> 'unit_price_'.$inquiryDetailsId, 'data-id'=>$inquiryDetailsId,'class' => 'form-control text-input-width-100-per integer-decimal-only text-right','autocomplete' => 'off','readonly']) !!} 
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">${{!empty($item['unit_price'])?$item['unit_price']:0}}&nbsp;/{{$item['unit_name']}}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        @if(!empty($poInfo) && $poInfo->status== '2')
                        <span>@lang('label.NOTE_') :</span>{!! !empty($poInfo->note)?$poInfo->note:''!!}
                        @else
                        <div class="form-group">
                            <div class="control-label text-left margin-bottom-10" for="poNo">@lang('label.NOTE_') :</div>
                            <div class="col-md-9">
                                <textarea class="form-control summernote_1" id="note" rows="10"  name="note">
                                              {{!empty($poInfo->note)?$poInfo->note:null}}
                                </textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!--            <div class="row margin-top-10">
                                <div class="col-md-5">
                                    <span>@lang('label.FOR')</span><br/>
                                    <span> 
                                        @if(!empty($signatoryInfo->seal))
                                        <img src="{{URL::to('/')}}/public/img/signatoryInfo/{{$signatoryInfo->seal }}" style="width:100px; height: 100px;"> 
                                        @else
                                        <img src="{{URL::to('/')}}/public/img/no_image.png" style="width:100px; height: 100px;">
                                        @endif
                                    </span><br/>
                                    <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span>
                                </div>
                            </div>-->
                <div class="row margin-top-10">
                    <div class="col-md-offset-5 col-md-7">

                        <button class="btn btn-inline green submit-po-Save" type="button" data-status="1">
                            <!--<i class="fa fa-check"></i> @lang('label.SAVE_AS_DRAFT')-->
                            <i class="fa fa-check"></i> @lang('label.SAVE')
                        </button> 
                        <!--                        <button class="btn btn-inline green-haze submit-po-Save" type="button" data-status="2">
                                                    <i class="fa fa-check"></i> @lang('label.SAVE_AND_CONFIRM')
                                                </button> -->

                        <a class="btn btn-inline btn-default tooltips" href="{{URL::to('confirmedOrder')}}" title="@lang('label.CANCEL')"> @lang('label.CANCEL')</a>
                        @if(!empty($poInfo))
                        <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/poGenerate/'.$inquiryId.'?view=print') }}"  title="@lang('label.PRINT')">
                            <i class="fa fa-print"></i>
                        </a>
                        <!--                        <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/poGenerate/'.$inquiryId.'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>-->
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>	
    </div>
    <!-- Modal start -->
    <!--preview modal start-->
    <div class="modal fade" id="previewModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="showPreviewModal">
            </div>
        </div>
    </div>
    <!--preview modal End-->
    <!-- Modal end-->

    <script type="text/javascript">
        $(function () {
            $('.summernote_1').summernote({
                height: 100, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: true      // set focus to editable area after initializing summernote
            });


            $("#summaryStatus").click(function () {
                if ($(this).is(":checked")) {
                    $("#summaryIdShow").show();
                } else {
                    $("#summaryIdShow").hide();
                }
            });



            $('.konita-commission').keyup(function (e) {
                var id = $(this).attr('data-id');
                var unitPrice = parseFloat($(this).attr('data-unitPrice'));

                var konitaCommission = parseFloat($(this).val());
                var rebateBuyerCommission = parseFloat($('#rebateBuyerCommission_' + id).val());

                if (isNaN(unitPrice)) {
                    unitPrice = 0;
                }
                if (isNaN(konitaCommission)) {
                    konitaCommission = 0;
                }
                if (isNaN(rebateBuyerCommission)) {
                    rebateBuyerCommission = 0;
                }

                var totalUnitPrice = 0;
                var cmsn = 0;
                cmsn = (konitaCommission + rebateBuyerCommission);
                totalUnitPrice = unitPrice - cmsn;


                $('#realizationPrice_' + id).val(totalUnitPrice.toFixed(2));

            });

            $('.rebateBuyer-commission').keyup(function (e) {
                var id = $(this).attr('data-id');
                var unitPrice = parseFloat($(this).attr('data-unitPrice'));


                var konitaCommission = parseFloat($('#konitaCommission_' + id).val());
                var rebateBuyerCommission = parseFloat($(this).val());

                if (isNaN(unitPrice)) {
                    unitPrice = 0;
                }
                if (isNaN(rebateBuyerCommission)) {
                    rebateBuyerCommission = 0;
                }
                if (isNaN(konitaCommission)) {
                    konitaCommission = 0;
                }


                var totalUnitPrice = 0;
                var cmsn = 0;
                cmsn = (konitaCommission + rebateBuyerCommission);
                totalUnitPrice = unitPrice - cmsn;


                $('#realizationPrice_' + id).val(totalUnitPrice.toFixed(2));

            });

            var hsCodeAllSelected = false;
            $('#hsCode').multiselect({
                numberDisplayed: 0,
                includeSelectAllOption: true,
                buttonWidth: '194px',
                nonSelectedText: "@lang('label.SELECT_HS_CODE')",
//        enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onSelectAll: function () {
                    hsCodeAllSelected = true;
                },
                onChange: function () {
                    hsCodeAllSelected = false;
                }
            });

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
                    url: "{{ URL::to('confirmedOrder/getFactoryAddress')}}",
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

            //After Click to Save new po generate
            $(document).on("click", ".submit-po-Save", function (e) {
                e.preventDefault();

                var status = $(this).attr('data-status');

                var formData = new FormData($('#poGenerateForm')[0]);
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
                    confirmButtonText: 'Yes,Confirm',
                    cancelButtonText: 'No, Cancel',
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{ URL::to('confirmedOrder/poGenerateSave')}}",
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
                                $('.submit-po-Save').prop('disabled', true);
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
                                $('.submit-po-Save').prop('disabled', false);
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
