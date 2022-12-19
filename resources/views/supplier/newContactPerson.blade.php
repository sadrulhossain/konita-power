<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12 contact-person-div">
    <div class="row">
        <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
        </button>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label col-md-3" for='contactPhoto'.{{$v3}}>@lang('label.PHOTO') :</label>
            <div class="col-md-9">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;">
                        <img src="{{URL::to('/')}}/public/img/unknown.png" alt="">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: 150px; height: 120px;"> </div>
                    <div>
                        <span class="btn red btn-outline btn-file">
                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                            {!! Form::file('contact_photo['.$v3.']',['id'=> 'contactPhoto'.$v3]) !!}
                        </span>
                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                    </div>
                </div>
                <div class="clearfix margin-top-10">
                    <span class="label label-success">@lang('label.NOTE')</span> <span class="text-danger bold">@lang('label.CONTACT_IMAGE_FOR_IMAGE_DESCRIPTION') </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 contact-div">
                    {!! Form::text('contact_name['.$v3.']', null, ['id'=> 'contactName'.$v3,'class' => 'focus-input']) !!} 
                    <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                </div>
                <div class="col-md-4 contact-div">
                    {!! Form::select('designation_id['.$v3.']',$designationList, null, ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$v3,'data-width' => '100%']) !!} 
                    <label class="floating-label" id="spanDeg_{{$v3}}">@lang('label.DESIGNATION') </label>
                </div>
                <div class="col-md-4 contact-div">
                    {!! Form::email('contact_email['.$v3.']', null, ['id'=> 'contactEmail'.$v3,'class' => 'focus-input']) !!}
                    <label class="floating-label" id="spanEmail_{{$v3}}">@lang('label.EMAIL') <span class="text-danger"> *</span></label>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-4  contact-div">
                    {!! Form::text('contact_phone['.$v3.']', null, ['id'=> 'contactPhone'.$v3,'class' => 'integer-only focus-input']) !!} 
                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                </div> 
                <div class="col-md-4 contact-div">
                    <div class="input-group date datepicker2">
                        {!! Form::text('first_introduction_date['.$v3.']', null, ['id'=> 'introductionDate'.$v3, 'class' => 'form-control', 'placeholder' => __('label.FIRST_INTRODUCTION_DATE'), 'readonly' => '']) !!} 
                        <span class="input-group-btn">
                            <button class="btn default reset-date" type="button" remove="introductionDate{{$v3}}">
                                <i class="fa fa-times"></i>
                            </button>
                            <button class="btn default date-set" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 contact-div">
                    {!! Form::textarea('contact_note['.$v3.']', null, ['id'=> 'contactNote'.$v3, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!} 
                    <label class="floating-label" id="spanNote_{{$v3}}">@lang('label.CONTACT_NOTE')</label>
                </div>
            </div>
            <br/>

        </div>
    </div>
</div> 
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.remove', function() {
            $(this).parent().parent().remove();
            return false;
        });
    });
</script>