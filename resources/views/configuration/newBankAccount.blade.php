<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12 contact-person-div">
    <div class="row">
        <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
        </button>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <div class="col-md-2 contact-div">
                    {!! Form::text('bank_info['.$v3.'][name_of_bank]', null, ['id'=> 'nameOfBank'.$v3,'class' => ' focus-input']) !!} 
                    <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME_OF_BANK') <span class="text-danger"> *</span></label>
                </div>
                <div class="col-md-2 contact-div">
                    {!! Form::text('bank_info['.$v3.'][account_no]', null, ['id'=> 'accountNo'.$v3,'class' => 'integer-only focus-input']) !!}
                    <label class="floating-label" id="spanEmail_{{$v3}}">@lang('label.ACCOUNT_NO') <span class="text-danger"> *</span></label>
                </div>
                <div class="col-md-2  contact-div">
                    {!! Form::text('bank_info['.$v3.'][account_name]', null, ['id'=> 'accountName'.$v3,'class' => 'focus-input']) !!} 
                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.ACCOUNT_NAME') <span class="text-danger"> *</span></label>
                </div> 
                <div class="col-md-2  contact-div">
                    {!! Form::text('bank_info['.$v3.'][branch]', null, ['id'=> 'branch'.$v3,'class' => 'focus-input']) !!} 
                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.BRANCH') <span class="text-danger"> *</span></label>
                </div> 
                <div class="col-md-2  contact-div">
                    {!! Form::text('bank_info['.$v3.'][swift]', null, ['id'=> 'swift'.$v3,'class' => 'focus-input']) !!} 
                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.SWIFT') <span class="text-danger"> *</span></label>
                </div> 
            </div>
        </div>
    </div>
</div> 
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('click', '.remove', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>