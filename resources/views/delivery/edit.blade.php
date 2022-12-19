@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i>@lang('label.EDIT_DELIVERY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('delivery.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-4" for="orderId">@lang('label.ORDER_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::select('order_id', $orderArr, null, ['class' => 'form-control js-source-states', 'id' => 'orderId']) !!}
                        <span class="text-danger">{{ $errors->first('order_id') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="totalQuantity">@lang('label.TOTAL_QUANTITY') :</label>
                    <div class="col-md-4">
                        <div id="loadTotalQuantity">
                            {!! Form::text('tot_quantity', $totalQuantity->lc_value, ['id'=> 'totalQuantity', 'class' => 'form-control integer-only','autocomplete' => 'off', 'disabled']) !!}
                            {!! Form::hidden('total_quantity', $totalQuantity->lc_value) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="remainingQuantity">@lang('label.REMAINING_QUANTITY') :</label>
                    <div class="col-md-4">
                        <div id="loadRemainingQuantity">
                            {!! Form::text('remain_quantity', ($remainingQuantity > 0)?$remainingQuantity:0, ['id'=> 'remainingQuantity', 'class' => 'form-control integer-only','autocomplete' => 'off', 'disabled']) !!} 
                            {!! Form::hidden('remaining_quantity', ($remainingQuantity > 0)?$remainingQuantity:0) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="quantity">@lang('label.QUANTITY') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::text('quantity', null, ['id'=> 'quantity', 'class' => 'form-control integer-only','autocomplete' => 'off']) !!} 
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="latestShipmentDate">@lang('label.LATEST_SHIPMENT_DATE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('latest_shipment_date', null, ['id'=> 'latestShipmentDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="latestShipmentDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('latest_shipment_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="notificationDate">@lang('label.NOTIFICATION_DATE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('notification_date', null, ['id'=> 'notificationDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="notificationDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('notification_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="ets">@lang('label.ESTIMATED_TIME_OF_SHIPMENT') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('ets', null, ['id'=> 'ets', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="ets">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('ets') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="etsNotificationDate">@lang('label.ETS_NOTIFICATION_DATE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('ets_notification_date', null, ['id'=> 'etsNotificationDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="etsNotificationDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('ets_notification_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="eta">@lang('label.ESTIMATED_TIME_OF_ARRIVAL') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('eta', null, ['id'=> 'eta', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="eta">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{ $errors->first('eta') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcDoc">@lang('label.LC_DOC') :</label>
                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('lc_doc',1,null, ['id' => 'lcDoc', 'class'=> 'md-check']) !!}
                        <label for="lcDoc">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_IF_HAS_DOC')</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="shipmentDoc">@lang('label.SHIPMENT_DOC') :</label>
                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('shipment_doc',1,null, ['id' => 'shipmentDoc', 'class'=> 'md-check']) !!}
                        <label for="shipmentDoc">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_IF_HAS_DOC')</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="finalDelivery">@lang('label.FINAL_DELIVERY') :</label>
                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('final_delivery',1,null, ['id' => 'finalDelivery', 'class'=> 'md-check']) !!}
                        <label for="finalDelivery">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_FINAL_DELIVERY_OF_THE_SELECTED_ORDER')</span>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/delivery'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#orderId").on("change", function () {
            var orderId = $(this).val();
            $.ajax({
                url: "{{URL::to('delivery/loadTotalQuantity')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_id: orderId
                },
                success: function (res) {
                    $("#loadTotalQuantity").html(res.html);
                    $("#loadRemainingQuantity").html(res.remainingQty);
                }
            });
        });
    });
</script>
@stop