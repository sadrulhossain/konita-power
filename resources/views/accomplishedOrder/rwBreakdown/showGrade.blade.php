<div class="form-group">
    <label class="control-label col-md-4" for="gradeId">@lang('label.GRADE') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {!! Form::select('grade_id', $gradeList, null, ['class' => 'form-control js-source-states', 'id' => 'gradeId']) !!}
        <span class="text-danger">{{ $errors->first('grade_id') }}</span>
    </div>
</div>