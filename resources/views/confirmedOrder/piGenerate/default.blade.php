@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-sticky-note-o"></i>
                @lang('label.PI_GENERATE')
            </div>
            @if(!empty($piInfo))
            <div class="actions">
                <span class="text-right">
                    <a class="btn btn-xs btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/piGenerate/'.$inquiryId.'?view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <!--                    <a class="btn btn-xs btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/piGenerate/'.$inquiryId.'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>-->
                </span>
            </div>
            @endif 
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Proced-->
                {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'piGenerateForm','class' => 'form-horizontal','files' => true)) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!} 
                {!! Form::hidden('pi_generate_id', !empty($piInfo->id)?$piInfo->id:null) !!} 

                <!--COMPANY INFORAMTION-->
                <div class="col-md-12">
                    <!--header img part-->
                    <div>
                        <span> 
                            @if(!empty($supplierInfo->header_image))
                            <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/headerImage/{{$supplierInfo->header_image }}" style="width: 100%; height: 100px;"> 
                            @endif
                        </span>
                    </div>
                    <div class="pi-border-style margin-bottom-30 margin-top-10"></div>
                    <!--end of header part-->
                    <div class="col-md-10 margin-bottom-10">
                        <div class="text-center bold">
                            <span>@lang('label.PRO_FORMA_INVOICE')</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td class="vcenter">
                                        <div class="form">
                                            <div class="col-md-12">
                                                <label class="control-label col-md-3" for="buyerPoNo">@lang('label.DATE'): <span class="text-danger">*</span></label>
                                                <?php
                                                $poData = !empty($target->po_date) ? Helper::formatDate($target->po_date) : null;
                                                ?>
                                                @if(!empty($piInfo) && $piInfo->status== '2')
                                                <div class="col-md-8 margin-top-8">
                                                    {{!empty($piInfo->po_date) ? Helper::formatDate($piInfo->po_date) : ''}}
                                                </div>
                                                @else
                                                <div class="col-md-8">
                                                    <?php
                                                    $finalDate = !empty($piInfo->po_date) ? Helper::formatDate($piInfo->po_date) : $poData;
                                                    ?>
                                                    <div class="input-group date datepicker2">
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
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                    </td> 
                                    <td class="vcenter">
                                        <span> @lang('label.PI_NO'): {{!empty($target->order_no)?$target->order_no:''}}</span>
                                    </td>
                                    <td class="vcenter">
                                        <div class="form">
                                            <div class="col-md-12">
                                                <label class="control-label col-md-4" for="buyerPoNo">@lang('label.BUYER') @lang('label.PO_NO'): <span class="text-danger">*</span></label>
                                                @if(!empty($piInfo) && $piInfo->status== '2')
                                                <div class="col-md-8 margin-top-8">
                                                    {{!empty($piInfo->buyer_po_no) ? $piInfo->buyer_po_no:''}}
                                                </div>
                                                @else
                                                <div class="col-md-8">
                                                    {!! Form::text('buyer_po_no',!empty($piInfo->buyer_po_no) ? $piInfo->buyer_po_no:null, ['id'=> 'buyerPoNo', 'class' => 'form-control']) !!}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
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
                                @if(empty($piInfo) || $piInfo->status== '1')
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
                                        {!! Form::text('head_office_address', !empty($buyerOfficeAddress)?$buyerOfficeAddress:null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']) !!} 
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
                                <span id="buyerFactoryAddress">{{$buyerOfficeAddress}}</span>
                            </td>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>                               
                                <tr>
                                    <td class="vcenter bold">@lang('label.DEALER_AGENT')</td>
                                </tr>
                                <tr>
                                    <td class="vcenter">
                                        <span class="bold">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                        <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                        <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                        <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.'.':''}}</span>
                                    </td>

                                </tr>
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
                                    <td class="vcenter bold">@lang('label.SHIPPING_MARKS') <span class="text-danger">*</span></td>
                                </tr>
                                @if(!empty($piInfo) && $piInfo->status== '2')
                                <tr>
                                    <td class="vcenter ">{{!empty($piInfo->pre_carrier_name)?$piInfo->pre_carrier_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($piInfo->shipping_terms_name)?$piInfo->shipping_terms_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($piInfo->final_destination)?$piInfo->final_destination:''}}</td> 
                                    <td class="vcenter ">{{!empty($piInfo->payment_terms_name)?$piInfo->payment_terms_name:''}}</td> 
                                    <td class="vcenter ">{{!empty($piInfo->delivery_date) ? Helper::formatDate($piInfo->delivery_date):''}}</td> 
                                    <td class="vcenter ">{{!empty($piInfo->shipping_marks) ? $piInfo->shipping_marks:''}}</td> 
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('pre_carrier_id', $preCarrierList,!empty($piInfo->pre_carrier_id)?$piInfo->pre_carrier_id:null, ['class' => 'form-control js-source-states','id'=>'preCarrier']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('shipping_term_id', $shippingTermList,!empty($piInfo->shipping_term_id)?$piInfo->shipping_term_id:null, ['class' => 'form-control js-source-states','id'=>'shippingTerm']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::text('final_destination',!empty($piInfo->final_destination)?$piInfo->final_destination:null, ['id'=> 'finalDestination', 'class' => 'form-control','autocomplete'=>'off']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::select('payment_term_id', $PaymentTermList,!empty($piInfo->payment_term_id)?$piInfo->payment_term_id:null, ['class' => 'form-control js-source-states','id'=>'paymentTerm']) !!}
                                        </div>
                                    </td>
                                    <td class="vcenter width-200">
                                        <div class="input-group width-inherit date datepicker2" style="z-index: 9994 !important">
                                            <?php
                                            $currentDate = date('d F Y');
                                            $deliveryDate = !empty($piInfo->delivery_date) ? Helper::formatDate($piInfo->delivery_date) : $currentDate;
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
                                    <td class="vcenter width-200">
                                        <div class="width-inherit">
                                            {!! Form::text('shipping_marks',!empty($piInfo->shipping_marks)?$piInfo->shipping_marks:null, ['id'=> 'shippingMarks', 'class' => 'form-control','autocomplete'=>'off']) !!}
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
                                    <th class="vcenter">@lang('label.BF')</th>
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

                            <td class="vcenter text-right" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                {{!empty($target['bf_info'][$gsmId])?$target['bf_info'][$gsmId]:''}}
                            </td>



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

                            <!--unit Price and Total price-->
                            @if($j == 0 && $i==0)


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
                                <td class="bold text-right" colspan="5">@lang('label.TOTAL')</td>
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
                                    <th class="vcenter">@lang('label.BF')</th>
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
                                {{!empty($target['bf_info'][$gsmId])?$target['bf_info'][$gsmId]:''}}
                            </td>

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
                                <td class="bold text-right" colspan="{{6+count($rwParameter[$id])}}">@lang('label.TOTAL')</td>
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

            <!--2nd part-->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.TOTAL_QUANTITY')</th>
                                    <th class="vcenter">@lang('label.UNIT_PRICE')</th>
                                    <th class="vcenter">@lang('label.TOTAL_INVOICE_VALUE')</th>
                                    <th class="vcenter">@lang('label.PRICE_FOB') <span class="text-danger">*</span></th>
                                    <th class="vcenter">@lang('label.FREIGHT_CHARGE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$inquiryDetailsInfo->isEmpty())
                                @if(!empty($piInfo) && $piInfo->status== '2')
                                @foreach($inquiryDetailsInfo  as $item)

                                <tr>
                                    <td class="vcenter">
                                        <span class="bold">{{$item->productName}}</span><br/>
                                        <span class="bold"> @lang('label.BRAND'):</span> <span>{{$item->brandName}}</span><br/>
                                        @if(!empty($item->gradeName))
                                        <span class="bold">@lang('label.GRADE'):</span> <span>{{$item->gradeName}}</span>
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {{$item->quantity}}&nbsp;{{$item->unit_name}}
                                        {{!empty($summaryArr[$item->id]['total_quantity_title'])?'('.$summaryArr[$item->id]['total_quantity_title'].')':''}}
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {{$item->unit_price}}&nbsp;/{{$item->unit_name}}
                                    </td>

                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {{$item->total_price}}
                                        {{!empty($summaryArr[$item->id]['invoice_value_title'])?'('.$summaryArr[$item->id]['invoice_value_title'].')':''}}
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        <span>{{!empty($summaryArr[$item->id]['price_fob'])?$summaryArr[$item->id]['price_fob']:''}}</span>
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        <span>{{!empty($summaryArr[$item->id]['freight_charge'])?$summaryArr[$item->id]['freight_charge']:''}}</span>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                @foreach($inquiryDetailsInfo  as $item)

                                <tr>
                                    <td class="vcenter">
                                        <span class="bold">{{$item->productName}}</span><br/>
                                        <span class="bold"> @lang('label.BRAND'):</span> <span>{{$item->brandName}}</span><br/>
                                        @if(!empty($item->gradeName))
                                        <span class="bold">@lang('label.GRADE'):</span> <span>{{$item->gradeName}}</span>
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {{$item->quantity}}&nbsp;{{$item->unit_name}}
                                        {!! Form::text('summaryArr['.$item->id.'][total_quantity_title]',!empty($summaryArr[$item->id]['total_quantity_title'])?$summaryArr[$item->id]['total_quantity_title']:__('label.10_PERCENT'), ['class' => '']) !!}
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {{$item->unit_price}}&nbsp;/{{$item->unit_name}}
                                    </td>

                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {{$item->total_price}}
                                        {!! Form::text('summaryArr['.$item->id.'][invoice_value_title]',!empty($summaryArr[$item->id]['invoice_value_title'])?$summaryArr[$item->id]['invoice_value_title']:__('label.10_PERCENT'), ['class' => '']) !!}
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {!! Form::text('summaryArr['.$item->id.'][price_fob]',!empty($summaryArr[$item->id]['price_fob'])?$summaryArr[$item->id]['price_fob']:null, ['id'=> 'priceFob_'.$item->id,'data-id'=>$item->id,'data-total-price'=>$item->total_price, 'class' => 'text-right price-fob']) !!}
                                    </td>
                                    <td class="vcenter">
                                        @lang('label.USD')
                                        {!! Form::text('summaryArr['.$item->id.'][freight_charge]',!empty($summaryArr[$item->id]['freight_charge'])?$summaryArr[$item->id]['freight_charge']:null, ['id'=> 'freightCharge_'.$item->id, 'class' => 'text-right tooltips input-bg-color'
                                        ,'readonly','title'=>'Freight Charge=Total Invoice Value - Price Fob']) !!}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            @if(!empty($piInfo) && $piInfo->status== '2')
                            <tbody>
<!--                                <tr>
                                    <td class="vcenter">@lang('label.PAYMENT_TERMS')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        {{!empty($PaymentTermList[$piInfo->payment_terms_id_2])?$PaymentTermList[$piInfo->payment_terms_id_2]:''}}
                                    </td>
                                </tr>-->
                                <tr>
                                    <td class="vcenter">@lang('label.BENEFICIARY_NAME_AND_ADDRESS')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <span>{{!empty($supplierInfo->supplier_name)?$supplierInfo->supplier_name:''}}</span><br/>
                                        <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                        <span>{{!empty($supplierInfo->country_name)?$supplierInfo->country_name.'.':''}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.BENEFICIARY_BANK')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <span>@lang('label.BANK_NAME')&nbsp;-&nbsp;{{!empty($piInfo->beneficiaryBank_name)?$piInfo->beneficiaryBank_name:''}}</span><br/>
                                        <span>@lang('label.ACCOUNT_NO')&nbsp;-&nbsp;{{!empty($piInfo->account_no)?$piInfo->account_no:''}}</span><br/>
                                        <span>@lang('label.CUSTOMER_ID')&nbsp;-&nbsp;{{!empty($piInfo->customer_id)?$piInfo->customer_id:''}}</span><br/>
                                        <span>@lang('label.BRANCH_ADDRESS')&nbsp;-&nbsp;{{!empty($piInfo->branch)?$piInfo->branch:''}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.REMARKS')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        {!! !empty($piInfo->remarks)?$piInfo->remarks:''!!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.LATEST_DATA_OF_SHIPMENT')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        {{!empty($piInfo->latest_date_shipment) ? Helper::formatDate($piInfo->latest_date_shipment) : ''}}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            @else
                            <tbody>
<!--                                <tr>
                                    <td class="vcenter">@lang('label.PAYMENT_TERMS') <span class="text-danger">*</span></td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <div class="col-md-4">
                                            {!! Form::select('payment_terms_id_2', $PaymentTermList,!empty($piInfo->payment_terms_id_2)?$piInfo->payment_terms_id_2:null, ['class' => 'form-control js-source-states','id'=>'paymentTermsId']) !!}
                                        </div>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td class="vcenter">@lang('label.BENEFICIARY_NAME_AND_ADDRESS')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <span>{{!empty($supplierInfo->supplier_name)?$supplierInfo->supplier_name:''}}</span><br/>
                                        <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                        <span>{{!empty($supplierInfo->country_name)?$supplierInfo->country_name.'.':''}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.BENEFICIARY_BANK') <span class="text-danger">*</span></td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <div class="col-md-4">
                                            {!! Form::select('beneficiary_bank_id', $beneficiaryBankList,!empty($piInfo->beneficiary_bank_id)?$piInfo->beneficiary_bank_id:null, ['class' => 'form-control js-source-states','id'=>'beneficiaryBankId']) !!}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.REMARKS')</td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <div class="form-group">
                                            <textarea class="form-control summernote_1" rows="10" name="remarks">
                                              {{!empty($piInfo->remarks)?$piInfo->remarks:__('label.PI_REMARKS')}}
                                            </textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vcenter">@lang('label.LATEST_DATA_OF_SHIPMENT') <span class="text-danger">*</span></td>
                                    <td class="vcenter">:</td>
                                    <td class="vcenter">
                                        <div class="col-md-4">
                                            <div class="input-group date datepicker2" style="z-index: 9994 !important">
                                                <?php
                                                $currentDate = date('d F Y');
                                                $latestDateShipment = !empty($piInfo->latest_date_shipment) ? Helper::formatDate($piInfo->latest_date_shipment) : $currentDate;
                                                ?>
                                                {!! Form::text('latest_date_shipment',$latestDateShipment, ['id'=> 'latestDateShipment', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                                <span class="input-group-btn">
                                                    <button class="btn default reset-date" type="button" remove="latestDateShipment">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <button class="btn default date-set" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <!--endof 2nd part-->

            <div class="row margin-top-10 margin-bottom-10">
                <div class="col-md-6">
                    <div class="margin-bottom-10">
                        @if(!empty($supplierInfo->signature_image))
                        <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/signatureImage/{{$supplierInfo->signature_image }}" style="width: 250px; height: 150px;"> 
                        @endif      
                    </div>
                    <span class="border-top-signature margin-left-30">
                        @lang('label.SELLERS_SIGNATURE')  
                    </span>
                </div>
                <div class="col-md-6 text-right margin-top-155">
                    <span class="border-top-signature margin-right-30">
                        @lang('label.BUYERS_SIGNATURE') 
                    </span>

                </div>

            </div>

            <div class="row">
                <div class="col-md-offset-5 col-md-7">
                    <button class="btn btn-inline green submit-pi-Save" type="button" data-status="1">
                        <i class="fa fa-check"></i> @lang('label.SAVE')
                    </button> 
                    <!--                    <button class="btn btn-inline green-haze submit-pi-Save" type="button" data-status="2">
                                            <i class="fa fa-check"></i> @lang('label.SAVE_AND_CONFIRM')
                                        </button> -->

                    <a class="btn btn-inline btn-default tooltips" href="{{URL::to('confirmedOrder')}}" title="@lang('label.CANCEL')"> @lang('label.CANCEL')</a>
                    @if(!empty($piInfo))
                    <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/piGenerate/'.$inquiryId.'?view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    <!--                    <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('confirmedOrder/piGenerate/'.$inquiryId.'?view=pdf') }}"  title="@lang('label.DOWNLOAD')">
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

        $('.summernote_1').summernote();

        $(".price-fob").keyup(function () {
            var id = $(this).attr('data-id');
            var totalPrice = $(this).attr('data-total-price');
            var priceFob = $(this).val();
            var freightCharge = 0;

            freightCharge = (totalPrice - priceFob);
            $('#freightCharge_' + id).val(freightCharge.toFixed(2));
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
        $(document).on("click", ".submit-pi-Save", function (e) {
            e.preventDefault();

            var status = $(this).attr('data-status');

            var formData = new FormData($('#piGenerateForm')[0]);
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
                        url: "{{ URL::to('confirmedOrder/piGenerateSave')}}",
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
                            $('.submit-pi-Save').prop('disabled', true);
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
                            $('.submit-pi-Save').prop('disabled', false);
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
