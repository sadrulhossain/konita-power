{!! Form::select('district_id', $districtArr, null, ['class' => 'form-control js-source-states', 'id' => 'districtId']) !!}
<span class="text-danger">{{ $errors->first('district_id') }}</span>