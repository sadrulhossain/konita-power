<?php
$v3 = 'nc' . uniqid();
?>
<tr>
    <td class="text-center vcenter new-contact-sl width-50"></td>
    <td class="text-center vcenter">
        {!! Form::text('contact['.$v3.'][name]', null, ['id'=> 'contactName_'.$v3, 'class' => 'form-control']) !!}
    </td>
    <td class="text-center vcenter">
        {!! Form::text('contact['.$v3.'][designation]', null, ['id'=> 'contactDesignation_'.$v3, 'class' => 'form-control']) !!}
    </td>
    <td class="text-center vcenter">
        {!! Form::text('contact['.$v3.'][email]', null, ['id'=> 'contactEmail_'.$v3, 'class' => 'form-control']) !!}
    </td>
    <td class="text-center vcenter">
        {!! Form::text('contact['.$v3.'][phone]', null, ['id'=> 'contactPhone_'.$v3, 'class' => 'form-control']) !!}
    </td>
    <td class="text-center vcenter width-50">
        <div class="checkbox-center md-checkbox has-success width-inherit">
            {!! Form::checkbox('contact['.$v3.'][primary]',1,null, ['id' => 'contactPrimary_'.$v3, 'data-key' => $v3, 'class'=> 'md-check primary-contact']) !!}
            <label for="contactPrimary_{{$v3}}">
                <span class="inc"></span>
                <span class="check box-double-rounded mark-caheck"></span>
                <span class="box box-rounded mark-caheck"></span>
            </label>
        </div>
    </td>
    <td class="text-center vcenter width-50">
        <button class="btn btn-inline btn-danger remove-contact-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </td>
</tr>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {

});
</script>
