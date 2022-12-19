
<?php $v3 = 'b' . uniqid() ?>

<div class="form-group">
    <label class="control-label col-md-4" for="phone_number_{{$v3}}"></label>
    <div class="col-md-7">
        {!! Form::text('phone_number['.$v3.']',null, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
    </div>
    <div class="col-md-1">
        <button class="btn btn-inline btn-danger remove-phone-number-row  tooltips"  title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </div>
</div>




<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove  row
    $('.remove-phone-number-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
