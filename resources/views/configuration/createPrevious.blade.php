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
                                <a href="#tab_15_2" data-toggle="tab"> @lang('label.SIGNATORY_INFORMATION') </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Start:: konita bank account tab -->
                            <div class="tab-pane active" id="tab_15_1">
                                {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'konitaBankAccountForm')) !!}

                                {{csrf_field()}}
                                <div class="col-md-12">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="col-md-1">
                                                <button  type="button" class="btn purple-soft add-bank-account tooltips" title="@lang('label.CLICK_HERE_TO_ADD_MORE_BANK_INFO')">
                                                    @lang('label.ADD_BANK_INFO')&nbsp; <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="" id="newBankAccount"> </div>
                                            <?php
                                            $v3 = 'z' . uniqid();
                                            ?>
                                            <div class="col-md-12 contact-person-div">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row ">
                                                            <div class="col-md-2 contact-div">
                                                                {!! Form::text('bank_info['.$v3.'][name_of_bank]', null, ['id'=> 'nameOfBank'.$v3,'class' => 'focus-input']) !!} 
                                                                <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME_OF_BANK') <span class="text-danger"> *</span></label>
                                                            </div>
                                                            <div class="col-md-2 contact-div">
                                                                {!! Form::text('bank_info['.$v3.'][account_no]', null, ['id'=> 'accountNo'.$v3,'class' => ' integer-only focus-input']) !!}
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
                                        </div>

                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-8">
                                                <button class="btn btn-circle green"  id="bankInfoSubmit" type="submit">
                                                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                    <!-- END:: Contact Person Data -->
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#submitSupplier").prop('disabled', false);
        $(document).on("click", ".add-bank-account", function () {
            $.ajax({
                url: "{{ route('configuration.newBankAccount') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newBankAccount").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });
        //Function for Save konita bank info Data
        $(document).on("click", "#bankInfoSubmit", function (e) {
            e.preventDefault();
            // Serialize the form data
            var formData = new FormData($('#konitaBankAccountForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "@lang('label.DO_YOU_WANT_TO_SUBMIT')",
                text: false,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.SUBMIT')",
                closeOnConfirm: true
            }, function (isConfirm) {
                $.ajax({
                    url: "{{ route('configuration.store') }}",
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
                        setTimeout(
                                window.location.replace('{{ route("configuration.index")}}'), 3000);
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
                        $("#submitSupplier").prop('disabled', false);
                        App.unblockUI();
                    }
                });
            });
        }); //EOF-save konita bank info
        
        //function for save signatory info data
        $(document).on("click", "#signatoryInfoSubmit", function (e) {
            e.preventDefault();
            // Serialize the form data
            var formData = new FormData($('#signatoryInfoFormData')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "@lang('label.DO_YOU_WANT_TO_SUBMIT')",
                text: false,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.SUBMIT')",
                closeOnConfirm: true
            }, function (isConfirm) {
                $.ajax({
                    url: "{{ route('configuration.store') }}",
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
                        setTimeout(
                                window.location.replace('{{ route("configuration.index")}}'), 3000);
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
                        $("#submitSupplier").prop('disabled', false);
                        App.unblockUI();
                    }
                });
            });
        });//EOF- save signatory inof data

    });
</script>
@stop