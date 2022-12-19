{!! Form::select('division_id', $divisionArr, null, ['class' => 'form-control js-source-states', 'id' => 'divisionId']) !!}
<span class="text-danger">{{ $errors->first('division_id') }}</span>