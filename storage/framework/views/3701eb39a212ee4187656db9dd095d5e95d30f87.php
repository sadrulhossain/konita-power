<?php $__env->startSection('data_count'); ?>	
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.CONFIGURATION'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs ">
                            <li class="active" >
                                <a href="#tab_15_1" data-toggle="tab"> <?php echo app('translator')->get('label.SIGNATORY_INFORMATION'); ?> </a>
                            </li>

                            <li>
                                <a href="#tab_15_2" data-toggle="tab"> <?php echo app('translator')->get('label.COMPANY_INFORMATION'); ?> </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Start:: signatory info tab -->
                            <div class="tab-pane active" id="tab_15_1">
                                <div class="portlet-body form">
                                    <?php echo Form::open(array('group' => 'form', 'url' => '#','files' => true,'class' => 'form-horizontal','id' => 'signatoryInfoFormData')); ?>

                                    <?php echo e(csrf_field()); ?>

                                    <?php $colMd = '2' ?>
                                    <?php if(empty($target)): ?>
                                    <?php $colMd = '4' ?>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-7">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">
                                                        <?php echo Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="designation"><?php echo app('translator')->get('label.DESIGNATION'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">
                                                        <?php echo Form::text('designation', null, ['id'=> 'designation', 'class' => 'form-control','autocomplete'=>'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('designation')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5" for="seal"><?php echo app('translator')->get('label.SEAL'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-7">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                                                <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt=""> 
                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> <?php echo app('translator')->get('label.SELECT_IMAGE'); ?> </span>
                                                                    <span class="fileinput-exists"> <?php echo app('translator')->get('label.CHANGE'); ?> </span>
                                                                    <?php echo Form::file('seal',['id'=> 'seal']); ?>

                                                                </span>
                                                                <span class="help-block text-danger"><?php echo e($errors->first('seal')); ?></span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.SIGNATORY_INFO_IMAGE_FOR_IMAGE_DESCRIPTION'); ?>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <?php else: ?>
                                    <div class="row ">
                                        <div class="col-md-7">
                                            <?php echo Form::hidden('id',$target->id); ?>

                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-offset-1 col-md-7">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-7">
                                                                <?php echo Form::text('name', $target->name, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']); ?> 
                                                                <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="designation"><?php echo app('translator')->get('label.DESIGNATION'); ?> :<span class="text-danger"> *</span></label>
                                                            <div class="col-md-7">
                                                                <?php echo Form::text('designation', $target->designation, ['id'=> 'designation', 'class' => 'form-control','autocomplete'=>'off']); ?> 
                                                                <span class="text-danger"><?php echo e($errors->first('designation')); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5" for="seal"><?php echo app('translator')->get('label.SEAL'); ?> :&nbsp;&nbsp;</label>
                                                            <div class="col-md-7">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                                                        <?php if(!empty($target->seal)): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/public/img/signatoryInfo/<?php echo e($target->seal); ?>" alt="<?php echo e($target->name); ?>"/>
                                                                        <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt=""> 
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                                                    <div>
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> <?php echo app('translator')->get('label.SELECT_IMAGE'); ?> </span>
                                                                            <span class="fileinput-exists"> <?php echo app('translator')->get('label.CHANGE'); ?> </span>
                                                                            <?php echo Form::file('seal',['id'=> 'seal']); ?>

                                                                        </span>
                                                                        <span class="help-block text-danger"><?php echo e($errors->first('seal')); ?></span>
                                                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix margin-top-10">
                                                                    <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.SIGNATORY_INFO_IMAGE_FOR_IMAGE_DESCRIPTION'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 ">
                                            <div class="table-responsive form-actions">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="center">
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                            <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                                            <th class="vcenter"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.IMAGE'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!$targetArr->isEmpty()): ?>
                                                        <?php
                                                        $sl = 0;
                                                        ?>
                                                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="text-center vcenter"> <?php echo e(++$sl); ?></td>
                                                            <td class="vcenter"> <?php echo e($target->name); ?></td>
                                                            <td class="vcenter"><?php echo e($target->designation); ?> </td>
                                                            <td class="text-center vcenter"> <img src="<?php echo e('public/img/signatoryInfo/'.$target->seal); ?>" style="width:50px; height: 50px;">

                                                            </td>


                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                        <tr>
                                                            <td colspan="8"><?php echo app('translator')->get('label.NO_KONITA_BANK_ACCOUNT_FOUND'); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-<?php echo e($colMd); ?> col-md-8">
                                                <?php if(!empty($userAccessArr[8][2])): ?>
                                                <button class="btn btn-circle green" id="signatoryInfoSubmit" type="submit">
                                                    <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo Form::close(); ?>

                                </div>	
                            </div>
                            <!-- EOF:: signatory info tab -->
                            <!-- START:: company info tab -->
                            <div class="tab-pane" id="tab_15_2">

                                <div class="portlet-body form">
                                    <?php echo Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal','files' => true,'id'=>'companyInfoFormData')); ?>

                                    <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>

                                    <?php echo e(csrf_field()); ?>

                                    <?php if(empty($companyInfo)): ?>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-7">

                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="companyName"><?php echo app('translator')->get('label.COMPANY_NAME'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        <?php echo Form::text('name', __('label.KONITA_TRADE_INTERNATIONAL'), ['id'=> 'companyName','class' => 'form-control','autocomplete' => 'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="address"><?php echo app('translator')->get('label.ADDRESS'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        <?php echo Form::textarea('address',  __('label.KONITA_ADDRESS'), ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('address')); ?></span>
                                                    </div>
                                                </div>
                                                <?php $v3 = 'a' . uniqid() ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="phone_number_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.PHONE_NUMBER'); ?> :</label>
                                                    <div class="col-md-7">
                                                        <?php echo Form::text('phone_number['.$v3.']',null, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('phone_number')); ?></span>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-inline green-haze add-phone-number  tooltips"  data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_PHONE_NUMBER'); ?>" type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="addPhoneNumberRow"></div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="email"><?php echo app('translator')->get('label.EMAIL'); ?> :<span class="text-danger"> *</span></label>
                                                    <div class="col-md-8">
                                                        <?php echo Form::email('email', null, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="website"><?php echo app('translator')->get('label.WEBSITE'); ?> :</label>
                                                    <div class="col-md-8">
                                                        <?php echo Form::text('website', null, ['id'=> 'website', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                        <span class="text-danger"><?php echo e($errors->first('website')); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-4 col-md-8">
                                                    <button class="btn green btn-submit" id="saveCompanyInfo" type="button">
                                                        <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                                                    </button>
                                                    <a href="<?php echo e(URL::to('/product'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="row ">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="companyName"><?php echo app('translator')->get('label.COMPANY_NAME'); ?> :<span class="text-danger"> *</span></label>
                                                <div class="col-md-8">
                                                    <?php echo Form::text('name', $companyInfo->name, ['id'=> 'companyName','class' => 'form-control','autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="address"><?php echo app('translator')->get('label.ADDRESS'); ?> :<span class="text-danger"> *</span></label>
                                                <div class="col-md-8">
                                                    <?php echo Form::textarea('address', $companyInfo->address, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('address')); ?></span>
                                                </div>
                                            </div>
                                            <?php
                                            $v3 = 'a' . uniqid();
                                            $i = 1;
                                            $jsonDecodedPhoneNumber = [];
                                            $jsonDecodedPhoneNumber = json_decode($companyInfo->phone_number, true);
   
                                            ?>
                                            <?php $__currentLoopData = $jsonDecodedPhoneNumber; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($i == '1'): ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="phone_number_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.PHONE_NUMBER'); ?> :</label>
                                                <div class="col-md-7">
                                                    <?php echo Form::number('phone_number['.$v3.']',$item, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('phone_number')); ?></span>
                                                </div>

                                                <div class="col-md-1">
                                                    <button class="btn btn-inline green-haze add-phone-number  tooltips"  data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_PHONE_NUMBER'); ?>" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="phone_number_<?php echo e($v3); ?>"></label>
                                                <div class="col-md-7">
                                                    <?php echo Form::number('phone_number['.$v3.']',$item, ['id'=> 'phone_number_'.$v3, 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('phone_number')); ?></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-inline btn-danger remove-phone-number-row  tooltips"  title="Remove" type="button">
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                </div>

                                            </div>
                                            <?php endif; ?>
                                            <?php
                                            $i++;
                                            $v3++;
                                            ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <div id="addPhoneNumberRow"></div>

                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="email"><?php echo app('translator')->get('label.EMAIL'); ?> :<span class="text-danger"> *</span></label>
                                                <div class="col-md-8">
                                                    <?php echo Form::email('email', $companyInfo->email, ['id'=> 'email', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="website"><?php echo app('translator')->get('label.WEBSITE'); ?> :</label>
                                                <div class="col-md-8">
                                                    <?php echo Form::text('website', $companyInfo->website, ['id'=> 'website', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                                    <span class="text-danger"><?php echo e($errors->first('website')); ?></span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="table-responsive form-actions">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="center">
                                                            <th class="vcenter"><?php echo app('translator')->get('label.COMPANY_NAME'); ?></th>
                                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.ADDRESS'); ?></th>
                                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.PHONE_NUMBER'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.WEBSITE'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($companyInfo)): ?>                                                
                                                        <tr>
                                                            <td class="vcenter"> <?php echo e($companyInfo->name); ?></td>
                                                            <td class="vcenter text-center"><?php echo e($companyInfo->address); ?> </td>

                                                            <td class="vcenter text-center">
                                                                <?php $__currentLoopData = $jsonDecodedPhoneNumber; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo e($item); ?>,
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </td>
                                                            <td class="vcenter text-center"><?php echo e($companyInfo->email); ?> </td>
                                                            <td class="vcenter text-center"><?php echo e($companyInfo->website); ?> </td>

                                                            </td>
                                                        </tr>
                                                        <?php else: ?>
                                                        <tr>
                                                            <td colspan="8"><?php echo app('translator')->get('label.NO_COMPANY_INFORMATION_FOUND'); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-8">
                                                <button class="btn green btn-submit" id="saveCompanyInfo" type="button">
                                                    <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <?php echo Form::close(); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                            <!-- EOF:: company info tab -->
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on("click", ".add-bank-account", function () {
            $.ajax({
                url: "<?php echo e(route('configuration.index')); ?>",
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


        //function for save signatory info data
        $(document).on("click", "#signatoryInfoSubmit", function (e) {
            e.preventDefault();
            // Serialize the form data
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "<?php echo app('translator')->get('label.DO_YOU_WANT_TO_SUBMIT'); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo app('translator')->get('label.SUBMIT'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var formData = new FormData($('#signatoryInfoFormData')[0]);
                    $.ajax({
                        url: "<?php echo e(route('configuration.store')); ?>",
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
                            setTimeout(window.location.replace('<?php echo e(route("configuration.index")); ?>'), 3000);
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
                }

            });
        });//EOF- save signatory inof data

        //add multiple phone number
        $(document).on("click", ".add-phone-number", function () {
            $.ajax({
                url: "<?php echo e(URL::to('configuration/addPhoneNumber')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                success: function (res) {
                    $("#addPhoneNumberRow").prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //After Click to Save company information 
        $(document).on("click", "#saveCompanyInfo", function (e) {
            e.preventDefault();
            var formData = new FormData($('#companyInfoFormData')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('configuration/saveCompanyInfo')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }

                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //remove  row
        $('.remove-phone-number-row').on('click', function () {
            $(this).parent().parent().remove();
            return false;
        });

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/configuration/index.blade.php ENDPATH**/ ?>