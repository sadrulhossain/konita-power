@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bank"></i>@lang('label.CREATE_BENEFICIARY_BANK')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'beneficiaryBank','class' => 'form-horizontal', 'id' => 'beneficiaryBankId')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('supplier_id', $supplierList,null, ['id'=> 'supplierId', 'class' => 'form-control js-source-states']) !!} 
                                <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="accountNo">@lang('label.ACCOUNT_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('account_no', null, ['id'=> 'accountNo', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('account_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="customerId">@lang('label.CUSTOMER_ID') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('customer_id', null, ['id'=> 'customerId', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label  col-md-4" for="branch">@lang('label.BRANCH') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('branch', null, ['id'=> 'branch', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('branch') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label  col-md-4" for="swiftCode">@lang('label.SWIFT_CODE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('swift_code', null, ['id'=> 'swiftCode', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('swift_code') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
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
                        <a href="{{ URL::to('/beneficiaryBank'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>		
    </div>
</div>
@stop