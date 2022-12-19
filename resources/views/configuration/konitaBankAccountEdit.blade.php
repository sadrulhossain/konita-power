@extends('layouts.default.master')
@section('data_count')	
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CONFIGURATION')
            </div>
        </div>
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs ">
                            <li class="active" >
                                <a href="#tab_15_1" data-toggle="tab"> @lang('label.KONITA_BANK_ACCOUNT') </a>
                            </li>

                            <li>
                                <!--<a href="#tab_15_2" data-toggle="tab"> @lang('label.SIGNATORY_INFORMATION') </a>-->
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Start:: konita bank account tab -->
                            <div class="tab-pane active" id="tab_15_1">
                                <div class="col-md-12">
                                    <div class="portlet box green">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-list"></i>@lang('label.EDIT_KONITA_BANK_ACCOUNT')
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            {!! Form::model($target, ['route' => array('configuration.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal'] ) !!}
                                            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
                                            {{csrf_field()}}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-offset-1 col-md-7">

                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="name">@lang('label.BANK_NAME') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-8">
                                                                {!! Form::text('bank_name', $target->bank_name, ['id'=> 'bankName', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                                <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="accountNo">@lang('label.ACCOUNT_NO') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-8">
                                                                {!! Form::text('account_no', $target->account_no, ['id'=> 'accountNo', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                                <span class="text-danger">{{ $errors->first('account_no') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="accountName">@lang('label.ACCOUNT_NAME') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-8">
                                                                {!! Form::text('account_name', $target->account_name, ['id'=> 'accountName', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                                <span class="text-danger">{{ $errors->first('account_name') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label  col-md-4" for="branch">@lang('label.BRANCH') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-8">
                                                                {!! Form::text('branch', $target->branch, ['id'=> 'branch', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                                <span class="text-danger">{{ $errors->first('branch') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="swift">@lang('label.SWIFT') :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-8">
                                                                {!! Form::text('swift', $target->swift, ['id'=> 'swift', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                                <span class="text-danger">{{ $errors->first('swift') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-4 col-md-8">
                                                        <button class="btn btn-circle green" id="konitaBankInfoSubmit" type="submit">
                                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                        </button>
                                                        <a href="{{ URL::to('/configuration'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>	
                                    </div>
                                </div>

                            </div>
                            <!-- EOF:: konita bank account tab -->
                            <!-- START:: signatory info tab -->
                            <div class="tab-pane" id="tab_15_2">
                                <div class="portlet-body form">
                                    {!! Form::open(array('group' => 'form', 'url' => '#','files' => true,'class' => 'form-horizontal','id' => 'signatoryInfoFormData')) !!}
                                    {{csrf_field()}}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-7">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="designation">@lang('label.DESIGNATION') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('designation', null, ['id'=> 'designation', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="seal">@lang('label.SEAL') :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        {!! Form::file('seal', null, ['id'=> 'seal', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                                        <span class="text-danger">{{ $errors->first('seal') }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-8">
                                                <button class="btn btn-circle green" id="signatoryInfoSubmit" type="submit">
                                                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>	


                            </div>
                            <!-- EOF:: signatory info tab -->
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>

@stop