@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_SUPPLIER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['files'=> true, 'class' => 'form-horizontal', 'id' => 'supplierEditForm'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.SUPPLIER_INFORMATION')</h3>
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="supplierClassificationId">@lang('label.CLASSIFICATION') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('supplier_classification_id', $supplierClassificationArr, null, ['class' => 'form-control js-source-states supplier-classification-id', 'id' => 'supplierClassificationId']) !!}
                                    <span class="text-danger">{{ $errors->first('supplier_classification_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="supplier_code">@lang('label.CODE') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('code', null, ['id'=> 'code', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="countryId">@lang('label.COUNTRY') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('country_id', $countryList, null, ['class' => 'form-control js-source-states country-id', 'id' => 'countryId']) !!}
                                    <span class="text-danger">{{ $errors->first('country_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :</label>
                                <div class="col-md-8">
                                    {{ Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control','size' => '30x5']) }}
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-4" for="signOffDate">@lang('label.SIGN_OFF_DATE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('sign_off_date', !empty($target->sign_off_date) ? Helper::formatDate($target->sign_off_date) : null, ['id'=> 'signOffDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="signOffDate">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-danger">{{ $errors->first('sign_off_date') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="fscCertified">@lang('label.FSC_CERTIFIED') :</label>
                                <div class="col-md-8 checkbox-center md-checkbox has-success">
                                    {!! Form::checkbox('fsc_certified',1,null, ['id' => 'fscCertified', 'class'=> 'md-check']) !!}
                                    <label for="fscCertified">
                                        <span class="inc"></span>
                                        <span class="check mark-caheck"></span>
                                        <span class="box mark-caheck"></span>
                                    </label>
                                    <span class="text-success">@lang('label.PUT_TICK_IF_FSC_CERTIFIED')</span>
                                </div>
                            </div>
                            <div class="form-group fsc-attachment" >
                                <label class="control-label col-md-4" for="fscAttachment">@lang('label.ATTACHMENT') :</label>
                                <div class="col-md-8 fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn green-seagreen btn-file">
                                        <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                        <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                        {!! Form::file('fsc_attachment',['id'=> 'fscAttachment']) !!}
                                        @if(!empty($target->fsc_certified))
                                        {!! Form::hidden('fsc_attachment',$target->fsc_attachment,['id'=> 'fscAttachment']) !!}
                                        @endif
                                    </span>
                                    @if(isset($target->fsc_attachment))
                                    <a href="{{URL::to('public/uploads/supplierFscCertificate/'.$target->fsc_attachment)}}"
                                       class="btn yellow-crusta btn-md tooltips" title="FCS Attachment Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                    @endif
                                    <span class="fileinput-filename"></span> &nbsp;
                                    <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                    <span class="text-danger">{{ $errors->first('fsc_attachment') }}</span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-4" for="piRequired">@lang('label.PI_REQUIRED') :</label>
                                <div class="col-md-8 checkbox-center md-checkbox has-success">
                                    {!! Form::checkbox('pi_required',1,null, ['id' => 'piRequired', 'class'=> 'md-check']) !!}
                                    <label for="piRequired">
                                        <span class="inc"></span>
                                        <span class="check mark-caheck"></span>
                                        <span class="box mark-caheck"></span>
                                    </label>
                                    <span class="text-success">@lang('label.PUT_TICK_IF_PI_REQUIRED')</span>
                                </div>
                            </div>

                            <div class="pi-parameter-block">
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="headerImage">@lang('label.HEADER_IMAGE') : <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 350px; height: 100px;">
                                                @if(!empty($target->header_image))
                                                <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/headerImage/{{$target->header_image}}" alt="{{ $target->header_image}}"/>
                                                @else
                                                <img src="{{URL::to('/')}}/public/img/no_image.png" alt=""> 
                                                @endif
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 350px; max-height: 100px;"> </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                    {!! Form::file('header_image',['id'=> 'headerImage']) !!}
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">@lang('label.REMOVE')</a>
                                            </div>
                                        </div>
                                        <div class="clearfix margin-top-10">
                                            <span class="label label-success">@lang('label.NOTE')</span>&nbsp;<span class="text-danger bold">@lang('label.PI_HEADER_IMAGE_FOR_IMAGE_DESCRIPTION')</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="signatureImage">@lang('label.SIGNATURE_IMAGE') : <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 250px; height: 80px;">
                                                @if(!empty($target->signature_image))
                                                <img src="{{URL::to('/')}}/public/uploads/supplier/PIFormat/signatureImage/{{$target->signature_image}}" alt="{{ $target->signature_image}}"/>
                                                @else
                                                <img src="{{URL::to('/')}}/public/img/no_image.png" alt=""> 
                                                @endif
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 250px; max-height: 80px;"> </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                    {!! Form::file('signature_image',['id'=> 'signatureImage']) !!}
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">@lang('label.REMOVE')</a>
                                            </div>
                                        </div>
                                        <div class="clearfix margin-top-10">
                                            <span class="label label-success">@lang('label.NOTE')</span>&nbsp;<span class="text-danger bold">@lang('label.PI_SIGNATURE_IMAGE_FOR_IMAGE_DESCRIPTION')</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="defaultFormat">@lang('label.DEFAULT_FORMAT') :</label>
                                    <div class="col-md-8 checkbox-center md-checkbox has-success">
                                        {!! Form::checkbox('default_format',1,null, ['id' => 'defaultFormat', 'class'=> 'md-check']) !!}
                                        <label for="defaultFormat">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                        <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_DEFAULT_FORMAT')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="logo">@lang('label.LOGO') : <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            @if(!empty($target->logo))
                                            <img src="{{URL::to('/')}}/public/uploads/supplier/{{$target->logo}}" alt="{{ $target->name}}"/>
                                            @else
                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt=""> 
                                            @endif

                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                {!! Form::file('logo',['id'=> 'logo']) !!}
                                            </span>
                                            <span class="help-block text-danger">{!! $errors->first('logo') !!}</span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                        </div>
                                    </div>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger">@lang('label.NOTE')</span><span class="text-danger bold">@lang('label.SUPPLIER_IMAGE_FOR_IMAGE_DESCRIPTION')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- START:: Contact Person Data -->
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.CONTACT_PERSON')</h3>
                        <div class="form-body">
                            <div class="form-group">
                                {!! Form::hidden('add_btn', '1', ['id' => 'addBtn']) !!}
                                <div class="col-md-1">
                                    <button  type="button" class="btn purple-soft add-contact-person tooltips" title="@lang('label.CLICK_HERE_TO_ADD_MORE_CONTACT_PERSON')">
                                        @lang('label.ADD_CONTACT_PERSON') &nbsp;<i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="" id="newContactPerson"> </div>
                                @if(!empty($prevContactPersonArr))
                                <?php
                                $count = 1;
                                ?>
                                @foreach($prevContactPersonArr as $identifier => $contactPerson)
                                <div class="col-md-12 contact-person-div">
                                    <div class="row">
                                        @if($count > 1)
                                        <button class="btn btn-danger remove tooltips pull-right block-remove" data-count="{{ $count }}" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button" 
                                                id="deleteBtn_"{{$count}}>
                                            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for='contactPhoto'.{{$identifier}}>@lang('label.PHOTO') :</label>
                                            <div class="col-md-9">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;">

                                                        @if(!empty($contactPerson['photo']))
                                                        <img src="{{URL::to('/')}}/public/uploads/supplier/contact_person/{{$contactPerson['photo']}}" alt="{{ $contactPerson['name']}}"/>
                                                        @else
                                                        <img src="{{URL::to('/')}}/public/img/unknown.png" alt=""> 
                                                        @endif
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: 150px; height: 120px;"> </div>
                                                    <div>
                                                        <span class="btn red btn-outline btn-file">
                                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                            {!! Form::file('contact_photo['.$identifier.']',null,['id'=> 'contactPhoto'.$identifier]) !!}

                                                        </span>
                                                        {!!Form::hidden('prev_contact_photo['.$identifier.']',$contactPerson['photo']) !!}
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
                                                    {!! Form::text('contact_name['.$identifier.']', $contactPerson['name'], ['id'=> 'contactName'.$identifier,'class' => 'focus-input']) !!} 
                                                    <label class="floating-label" id="spanName_{{$identifier}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::select('designation_id['.$identifier.']',$designationList, $contactPerson['designation_id'], ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$identifier,'data-width' => '100%']) !!} 
                                                    <label class="floating-label" id="spanDeg_{{$identifier}}">@lang('label.DESIGNATION') </label>
                                                </div>
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::email('contact_email['.$identifier.']', $contactPerson['email'], ['id'=> 'contactEmail'.$identifier,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanEmail_{{$identifier}}">@lang('label.EMAIL') <span class="text-danger"> *</span></label>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-4  contact-div">
                                                    {!! Form::text('contact_phone['.$identifier.']', $contactPerson['phone'], ['id'=> 'contactPhone'.$identifier,'class' => 'integer-only focus-input']) !!} 
                                                    <label class="floating-label" id="spanPhone_{{$identifier}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div> 
                                                <div class="col-md-4 contact-div">
                                                    <div class="input-group date datepicker2">
                                                        {!! Form::text('first_introduction_date['.$identifier.']', !empty($contactPerson['introduction_date']) ? Helper::formatDate($contactPerson['introduction_date']) : null, ['id'=> 'introductionDate'.$identifier, 'class' => 'form-control', 'placeholder' => __('label.FIRST_INTRODUCTION_DATE'), 'readonly' => '']) !!} 
                                                        <span class="input-group-btn">
                                                            <button class="btn default reset-date" type="button" remove="introductionDate{{$identifier}}">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 contact-div">
                                                    {!! Form::textarea('contact_note['.$identifier.']', $contactPerson['note'], ['id'=> 'contactNote'.$identifier, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!} 
                                                    <label class="floating-label" id="spanNote_{{$identifier}}">@lang('label.CONTACT_NOTE')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $count++;
                                ?>
                                @endforeach
                                @else
                                <div class="">
                                    <?php
                                    $v3 = 'z' . uniqid();
                                    ?>
                                    <div class="col-md-12 contact-person-div">
                                        <!--                                    <div class="row">
                                                                                <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
                                                                                    &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
                                                                                </button>
                                                                            </div>-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for='contactPhoto'.{{$v3}}>@lang('label.PHOTO') :</label>
                                                <div class="col-md-9">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;">
                                                            <img src="{{URL::to('/')}}/public/img/no_image.png" alt="">
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
                                                        <div class="input-group date datepicker">
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
                                </div>
                                <!-- END:: Contact Person Data -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="submitEditSupplier" disabled="">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/supplier'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        //start :: PI parameter block

        //initially hide PI parameter block
        piCheck("#piRequired", 0);

        //on click PI required checkbox
        //hide or show PI parameter block
        $(document).on("click", "#piRequired", function () {
            piCheck(this, 500);
        });
        
        function piCheck(selector, delay){
            if ($(selector).prop("checked")) {
                $(".pi-parameter-block").show(delay);
            } else {
                $(".pi-parameter-block").hide(delay);
            }
        }

        //end :: PI parameter block

        // START:: For FSC Certified 
        
        //start has fsc attachment check
        $('#fscCertified').on('click', function () {
            fscCheck(this, 500);
        });
        //end has fsc attachment check

        fscCheck('#fscCertified', 0);

        function fscCheck(selector, delay) {
            if ($(selector).prop("checked")) {
                $(".fsc-attachment").show(delay);
            } else {
                $(".fsc-attachment").hide(delay);
            }
        }
        // END:: For FSC Certified

        $("#submitEditSupplier").prop('disabled', false);
        $(document).on("click", ".add-contact-person", function () {
            $.ajax({
                url: "{{ route('supplier.contactPersonToEdit') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newContactPerson").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });

        //Function for Update Supplier Data
        $(document).on("click", "#submitEditSupplier", function (e) {
            // Serialize the form data
            var formData = new FormData($('#supplierEditForm')[0]);
            e.preventDefault();
            if ($('#fscCertified').is(":checked")) {
<?php if (!empty($target->fsc_attachment)) { ?>
                    updateSupplier(formData);
<?php } else { ?>
                    if ($('#fscAttachment').get(0).files.length === 0) {
                        swal({
                            title: "@lang('label.YOU_HAVE_NOT_ATTACH_ANY_FSC_ATTACHMENT')",
                            text: "@lang('label.DO_YOU_WANT_TO_CONTINUE_IT')",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "@lang('label.YES_CONTINUE_IT')",
                            closeOnConfirm: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                $("#submitEditSupplier").prop('disabled', true);
                                updateSupplier(formData);
                            }
                        });
                    } else {
                        $("#submitEditSupplier").prop('disabled', false);
                        updateSupplier(formData);
                    }
<?php } ?>

            } else {
                $("#submitEditSupplier").prop('disabled', true);
                updateSupplier(formData);
            }

        });


        $(document).on('click', '.remove', function () {
            var rowCount = $(this).data('count');
            if (rowCount > 1) {
                $(this).parent().parent().remove();
                return false;
            }
        });


        function updateSupplier(formData) {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{ route('supplier.update') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(window.location.replace('{{ route("supplier.index")}}'), 7000);

                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {

                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $("#submitEditSupplier").prop('disabled', false);
                    App.unblockUI();
                }
            });
        }
    });
</script>
@stop