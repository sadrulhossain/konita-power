<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.DENY_OPPORTUNITY')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'denyOpportunityForm')) !!}
                {!! Form::hidden('opportunity_id', $request->opportunity_id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.REMARKS') :</label>
                                <div class="col-md-8">
                                    {!! Form::textarea('remarks', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off','rows' => 4, 'cols' => 40,]) !!} 
                                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-inline green" type="button" id='submitDenyOpportunity'>
                        <i class="fa fa-check"></i> @lang('label.SUBMIT')
                    </button> 
                    <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>