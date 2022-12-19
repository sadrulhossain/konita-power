<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="row tech-data-sheet-div padding-10">
        <div class="col-md-5">
            <label class="control-label" for="{!! 'gsm_'.$v3 !!}">@lang('label.GSM'):<span class="text-danger">*</span></label>
            {!! Form::text('gsm['.$v3.']', null, ['id'=> 'gsm_'.$v3, 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!}
            <span class="text-danger">{!! $errors->first('gsm['.$v3.']') !!}</span>
        </div>
        <div class="col-md-5">
            <label class="control-label" for="{!! 'volume_'.$v3 !!}">@lang('label.VOLUME'):<span class="text-danger">*</span></label>
            {!! Form::text('volume['.$v3.']', null, ['id'=> 'volume_'.$v3, 'class' => 'form-control','autocomplete' => 'off']) !!}
            <span class="text-danger">{!! $errors->first('volume['.$v3.']') !!}</span>
        </div>
        <div class="col-md-2 margin-top-27 pull-left">
            <button class="btn btn-danger remove tooltips pull-right gsm-remove" title="@lang('label.REMOVE')" type="button">
                <i class="fa fa-remove"></i>
            </button>
        </div>
        <br/>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.tooltips').tooltip({container: 'body'});
    $('.remove').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>