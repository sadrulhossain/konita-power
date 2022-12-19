<div class="col-md-3">
    <label class="control-label" for="gradeId">@lang('label.GRADE') :</label>
    {!! Form::select('grade_id', $gradeList, null, ['class' => 'form-control js-source-states', 'id' => 'gradeId']) !!}
    {!! Form::hidden('grade_value', $gradeVal, ['id' => 'gradeValue']) !!}
    <span class="text-danger">{{ $errors->first('grade_id') }}</span>
</div>