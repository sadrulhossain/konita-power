<?php
$v3 = 't' . uniqid();
?>

<div class="row margin-bottom-10">
    <div class="col-md-4 col-sm-6">
        <!--<label for="etsDate_{{$v3}}">@lang('label.ETS_DATE')<span class="text-danger"> *</span></label>-->
        <div class="input-group date datepicker2">
            {!! Form::text('ets_date['.$v3.']', null, ['id'=> 'etsDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="etsDate_{{$v3}}">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <!--<label for="etsNotificationDate_{{$v3}}">@lang('label.ETS_NOTIFICATION_DATE')<span class="text-danger"> *</span></label>-->
        <div class="input-group date datepicker2">
            {!! Form::text('ets_notification_date['.$v3.']', null, ['id'=> 'etsNotificationDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="etsNotificationDate_{{$v3}}">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <button class="btn btn-inline btn-danger remove-ets-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove ets row
    $('.remove-ets-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
