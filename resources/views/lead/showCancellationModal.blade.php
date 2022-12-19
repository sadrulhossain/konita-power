<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INQUIRY_CANCELLATION')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'leadCencelForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="cancelCause">@lang('label.CAUSE_OF_FAILURE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('cancel_cause', $causeList, null, ['class' => 'form-control js-source-states ','id'=>'cancelCause']) !!}
                                    <span class="text-danger">{{ $errors->first('cancel_cause') }}</span>
                                </div>
                            </div>
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
                    <button class="btn btn-inline green" type="button" id='submitLeadCancel'>
                        <i class="fa fa-check"></i> @lang('label.SUBMIT')
                    </button> 
                    <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- END:: Contact Person Information-->
<script>
$(function(){
    $(".js-source-states").select2({dropdownParent: $('body')});
});

</script>