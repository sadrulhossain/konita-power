{!! Form::text('lc_value', !empty($lead)?$lead->quantity:'', ['id'=> 'lcValue', 'class' => 'form-control integer-only','autocomplete' => 'off']) !!}