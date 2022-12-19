
<?php
$v3 = 'z' . uniqid();
$v4 = 'z' . uniqid();
?>

<div class="">
    <div class="col-md-3  contact-div">
        {!! Form::text('contact_phone['.$request->key.']['.$v4.']', null, ['id'=> 'contactPhone'.$v3,'class' => 'integer-only focus-input']) !!} 
        <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
    </div>
    <div class="col-md-1 margin-top-10">
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
