<?php $v3 = 'b' . uniqid(); ?>
<div class="row margin-bottom-10">
    <div class="col-md-9 col-sm-9 col-xs-9">
        {!! Form::text('container_no['.$v3.']',  null, ['class'=>'form-control', 'id' => 'containerNo_'.$v3,'autocomplete' => 'off']) !!}
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">
        <button class="btn btn-inline btn-danger remove-container-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove eta row
    $('.remove-container-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
