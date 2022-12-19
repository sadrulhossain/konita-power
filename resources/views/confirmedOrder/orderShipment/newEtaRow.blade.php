<?php
$v3 = 'b' . uniqid();
?>

<div class="row margin-bottom-10">
    <div class="col-md-4 col-sm-6">
        <!--<label for="etaDate_{{$v3}}">@lang('label.ETA_DATE')</label>-->
        <div class="input-group date datepicker2">
            {!! Form::text('eta_date['.$v3.']', null, ['id'=> 'etaDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="etaDate_{{$v3}}">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <!--<label for="etaNotificationDate_{{$v3}}">@lang('label.ETA_NOTIFICATION_DATE')</label>-->
        <div class="input-group date datepicker2">
            {!! Form::text('eta_notification_date['.$v3.']', null, ['id'=> 'etaNotificationDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="etaNotificationDate_{{$v3}}">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <button class="btn btn-inline btn-danger remove-eta-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove eta row
    $('.remove-eta-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
