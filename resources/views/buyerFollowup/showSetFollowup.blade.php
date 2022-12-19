<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ADD_FOLLOWUP')
        </h3>
    </div>

    {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'saveFollowupForm')) !!}
    {{csrf_field()}}
    <div class="modal-body">
        {!! Form::hidden('buyer_id',  $request->buyer_id) !!}

        <div class="form-body">
            <div class="row">
                <div class="col-md-offset-2 col-md-7">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyer">@lang('label.BUYER') :</label>
                        <div class="col-md-8 bold margin-top-8">
                            {!! $buyerInfo->name ?? '' !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="followUpDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php $followUpDate = date('d F Y'); ?>
                            <div class="input-group date datepicker2" style="z-index: 9994 !important">
                                {!! Form::text('follow_up_date', $followUpDate, ['id'=> 'followUpDate', 'class' => 'form-control', 'placeholder' => 'DD MM yyyy', 'readonly' => '']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="followUpDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('status', $statusList, Request::get('status'), ['class' => 'form-control js-source-states ','id'=>'status']) !!}
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                    </div>
                    @if(count($orderNoList) > 1)
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo">@lang('label.ORDER_NO') :</label>
                        <div class="col-md-8">
                            {!! Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states ','id'=>'orderNo']) !!}
                            <span class="text-danger">{{ $errors->first('order_no') }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="control-label col-md-4" for="remarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control', 'size' => '30x5']) !!} 
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn green"  id="saveFollowupHistory">
                    <i class="fa fa-check"></i> @lang('label.SAVE')
                </button>
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script>
$(function () {

});
</script>