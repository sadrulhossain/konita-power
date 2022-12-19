@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.CREATE_ORDER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'order', 'class' => 'form-horizontal','files' => true)) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-4" for="leadId">@lang('label.LEAD') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::select('lead_id', $leadArr, null, ['class' => 'form-control js-source-states', 'id' => 'leadId']) !!}
                        <span class="text-danger">{{ $errors->first('lead_id') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="orderUniqueNo">@lang('label.ORDER_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::text('order_unique_no', null, ['id'=> 'orderUniqueNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                        <span class="text-danger">{{ $errors->first('order_unique_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcValue">@lang('label.LC_VALUE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div id="loadLcValue">
                            {!! Form::text('lc_value', null, ['id'=> 'lcValue', 'class' => 'form-control integer-only','autocomplete' => 'off']) !!} 
                        </div>
                        <span class="text-danger">{{ $errors->first('lc_value') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcNo">@lang('label.LC_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::text('lc_no', null, ['id'=> 'lcNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                        <span class="text-danger">{{ $errors->first('lc_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcDate">@lang('label.LC_DATE') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        <div class="input-group date datepicker">
                            {!! Form::text('lc_date', null, ['id'=> 'lcDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
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
                <div class="form-group">
                    <label class="control-label col-md-4" for="expressTrackingNo">@lang('label.EXPRESS_TRACKING_NO') :<span class="text-danger"> *</span></label>
                    <div class="col-md-4">
                        {!! Form::text('express_tracking_no', null, ['id'=> 'expressTrackingNo', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                        <span class="text-danger">{{ $errors->first('express_tracking_no') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="note">@lang('label.NOTE_') :</label>
                    <div class="col-md-4">
                        {{ Form::textarea('note', null, ['id'=> 'note', 'class' => 'form-control','size' => '30x5']) }}
                        <span class="text-danger">{{ $errors->first('note') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcDraftDone">@lang('label.LC_DRAFT_DONE') :</label>
                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('lc_draft_done',1,null, ['id' => 'lcDraftDone', 'class'=> 'md-check']) !!}
                        <label for="lcDraftDone">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_DRAFT_DONE')</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="lcTransmittedCopyDone">@lang('label.LC_TRANSMITTED_COPY_DONE') :</label>
                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('lc_transmitted_copy_done',1,null, ['id' => 'lcTransmittedCopyDone', 'class'=> 'md-check']) !!}
                        <label for="lcTransmittedCopyDone">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_TRANSMITTED_COPY_DONE')</span>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/order'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>	
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#leadId").on("change", function () {
            var leadId = $(this).val();
            $.ajax({
                url: "{{URL::to('order/loadLcValueToCreate')}}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    lead_id: leadId
                },
                success: function (res) {
                    $("#loadLcValue").html(res.html);
                }
            });
        });
    });
</script>

@stop