@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.EDIT_USER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('user.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId">@lang('label.USER_GROUP') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('group_id', $groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']) !!}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="departmentId">@lang('label.DEPARTMENT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('department_id', $departmentList, null, ['class' => 'form-control js-source-states', 'id' => 'departmentId']) !!}
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="designationId">@lang('label.DESIGNATION') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('designation_id', $designationList, null, ['class' => 'form-control js-source-states', 'id' => 'designationId']) !!}
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="branchId">@lang('label.BRANCH') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('branch_id', $branchList, null, ['class' => 'form-control js-source-states', 'id' => 'branchId']) !!}
                                <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="allowedForSales">@lang('label.ALLOWED_FOR_SALES') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('allowed_for_sales',1,null, ['id' => 'allowedForSales', 'class'=> 'md-check']) !!}
                                <label for="allowedForSales">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_ALLOWED')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="authorisedForRealizationPrice">@lang('label.AUTHORISED_FOR_REALIZATION_PRICE') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('authorised_for_realization_price',1,null, ['id' => 'authorisedForRealizationPrice', 'class'=> 'md-check']) !!}
                                <label for="authorisedForRealizationPrice">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_AUTHORISED')</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="allowedForCrm">@lang('label.ALLOWED_FOR_CRM') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('allowed_for_crm',1,null, ['id' => 'allowedForCrm', 'class'=> 'md-check']) !!}
                                <label for="allowedForCrm">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_ALLOWED')</span>
                            </div>
                        </div>

                        <div class="form-group" id="crmLeader" style="display: none;">
                            <label class="control-label col-md-4" for="forCrmLeader">@lang('label.CRM_LEADER') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('for_crm_leader',1,null, ['id'=> 'forCrmLeader', 'class' => 'make-switch','data-on-text'=> "Yes",'data-off-text'=>"No"]) !!} 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="allowedToViewQuotation">@lang('label.ALLOWED_FOR_VIEW_QUOTATION_REQUSET') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('allowed_to_view_quotation',1,null, ['id' => 'allowedToViewQuotation', 'class'=> 'md-check']) !!}
                                <label for="allowedToViewQuotation">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_ALLOWED')</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="allowedForMessaging">@lang('label.ALLOWED_FOR_MESSAGING') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('allowed_for_messaging',1,null, ['id' => 'allowedForMessaging', 'class'=> 'md-check']) !!}
                                <label for="allowedForMessaging">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_ALLOWED')</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="employeeId">@lang('label.EMPLOYEE_ID') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('employee_id', null, ['id'=> 'employeeId', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('employee_id') }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="firstName">@lang('label.FIRST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('first_name', null, ['id'=> 'firstName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="lastName">@lang('label.LAST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('last_name', null, ['id'=> 'lastName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="nickName">@lang('label.NICK_NAME') :</label>
                            <div class="col-md-8">
                                {!! Form::text('nick_name', null, ['id'=> 'nickName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('nick_name') }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="supervisorId">@lang('label.SUPERVISOR') :</label>
                            <div class="col-md-8">
                                {!! Form::select('supervisor_id', $supervisorList, null, ['class' => 'form-control js-source-states', 'id' => 'supervisorId']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail">@lang('label.EMAIL') :</label>
                            <div class="col-md-8">
                                {!! Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control integer-only']) !!} 
                                <span class="text-danger">{{ $errors->first('user_phone') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userName">@lang('label.USERNAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('username', null, ['id'=> 'userName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('username') }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="password">@lang('label.PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span>
                                    @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="confPassword">@lang('label.CONF_PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('conf_password') }}</span>
                            </div>
                        </div>




                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo">@lang('label.PHOTO') :</label>
                            <div class="col-md-8">
                                <!-- input file -->
                                <div class="box">
                                    <input name="prev_photo" type="file" id="photo">
                                    <span class="text-danger">{{ $errors->first('photo') }}</span>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                                    </div>
                                </div>				
                            </div>				
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="col-md-4">
                                    <!-- input file -->
                                    <img class="cropped" src="" alt="" width="200px">
                                    <input type="hidden" name="crop_photo" id="cropImg" value="">

                                    <div class="box">
                                        <div class="options hide">
                                            <input type="hidden" class="img-w" value="300" min="255" max="255"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo"></label>	
                            <div class="col-md-4">
                                <!-- leftbox -->
                                @if(!empty($target->photo))
                                <img src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" id="prevImage" alt="{{ $target->name}}" width="200px"/>
                                @endif
                                <div class="result"></div>
                                <!-- crop btn -->
                                <button class="c-btn crop btn-danger hide" type="button">@lang('label.CROP')</button>
                            </div>		
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/user'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

//    crop image 
        let result = document.querySelector('.result'),
                img_result = document.querySelector('.img-result'),
                img_w = document.querySelector('.img-w'),
                img_h = document.querySelector('.img-h'),
                options = document.querySelector('.options'),
                crop = document.querySelector('.crop'),
                cropped = document.querySelector('.cropped'),
                dwn = document.querySelector('.download'),
                upload = document.querySelector('#photo'),
                cropper = '';
        var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];
        // on change show image with crop options
        upload.addEventListener('change', function (e) {
            if (e.target.files.length) {
                $('#prevImage').hide();
                // start file reader
                const reader = new FileReader();
                var file = e.target.files[0]; // Get your file here
                var fileExt = file.type.split('/')[1]; // Get the file extension
                if (fileTypes.indexOf(fileExt) !== -1) {
                    reader.onload = function (e) {
                        if (e.target.result) {
                            // create new image
                            let img = document.createElement('img');
                            img.id = 'image';
                            img.src = e.target.result
                            // clean result before
                            result.innerHTML = '';
                            // append new image
                            result.appendChild(img);
                            // show crop btn and options
                            crop.classList.remove('hide');
                            options.classList.remove('hide');
                            // init cropper
                            cropper = new Cropper(img);
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('File not supported');
                    return false;
                }
            }
        });
        // crop on click
        crop.addEventListener('click', function (e) {
            e.preventDefault();
            // get result to data uri
            let
                    imgSrc = cropper.getCroppedCanvas({
                        width: img_w.value // input value
                    }).toDataURL();
            // remove hide class of img
            cropped.classList.remove('hide');
            //	img_result.classList.remove('hide');
            // show image cropped
            cropped.src = imgSrc;
            $('#cropImg').val(imgSrc);
        });
        //START:: Condition for CRM Leader Check
        if ($('#allowedForCrm').prop("checked") == true) {
            $('#crmLeader').show();
        } else {
            $('#crmLeader').hide();
        }
         //END:: Condition for CRM Leader Check
        //START:: Ajax for Allow User for CRM  
        $('#allowedForCrm').on('click', function () {
            if ($(this).prop("checked") == true) {
                $('#crmLeader').show();
            } else {
                $('#crmLeader').hide();
            }
        });
        //END:: Ajax for Allow User for CRM

        //START:: Ajax for Allow User as CRM Leader
        $("#forCrmLeader").bootstrapSwitch({
            offColor: 'danger'
        });

        $('#forCrmLeader').on('switchChange.bootstrapSwitch', function () {
            if ($(this).prop("checked") == true) {
                $.ajax({
                    url: "{{ route('user.getCheckCrmLeader') }}",
                    type: "POST",
                    data: {
                        val: '1',
                    },
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        swal({
                            title: "'" + res['name'] + "' @lang('label.IS_ALREADY_ADDED_AS_CRM_LEADER')",
                            text: "@lang('label.DO_YOU_WANT_TO_OVERWRITE_IT')",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "@lang('label.YES_OVERWRITE_IT')",
                            closeOnConfirm: true,
                            closeOnCancel: true,
                        }, function (isConfirm) {
                            if (isConfirm) {
                                return true;
                            } else {
                                $("#forCrmLeader").bootstrapSwitch('state', false);
                            }
                        });
                    },
                });
            }

        });
        //END:: Ajax for Allow User as CRM Leader
    });
</script>
@stop