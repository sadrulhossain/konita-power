<?php $__env->startSection('data_count'); ?>	
<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.CREATE_BUYER'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'buyerForm')); ?>

            <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <h3 class="form-section title-section bold"><?php echo app('translator')->get('label.BUYER_INFORMATION'); ?></h3>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyerCatId"><?php echo app('translator')->get('label.BUYER_CATEGORY'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('buyer_category_id', $buyerCatArr, null, ['class' => 'form-control js-source-states', 'id' => 'buyerCatId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('buyer_category_id')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="countryId"><?php echo app('translator')->get('label.COUNTRY'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('country_id', $countryList, '18', ['class' => 'form-control js-source-states', 'id' => 'countryId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('country_id')); ?></span>
                            </div>
                        </div>
                        <div class="form-group" id="division">
                            <label class="control-label col-md-4" for="divisionId"><?php echo app('translator')->get('label.DIVISION'); ?> :</label>
                            <div class="col-md-8" id="showDivision">
                                <?php echo Form::select('division_id', $divisionList, null, ['class' => 'form-control js-source-states', 'id' => 'divisionId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('division_id')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="code"><?php echo app('translator')->get('label.CODE'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::text('code', null, ['id'=> 'code', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('code')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="headOfficeAddress"><?php echo app('translator')->get('label.HEAD_OFFICE_ADDRESS'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::textarea('head_office_address', null, ['id'=> 'headOfficeAddress', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off'])); ?>

                                <span class="text-danger"><?php echo e($errors->first('head_office_address')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="showAllBrands"><?php echo app('translator')->get('label.ALLOW_ALL_BRANDS'); ?> :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                <?php echo Form::checkbox('show_all_brands',1,null, ['id' => 'showAllBrands', 'class'=> 'md-check']); ?>

                                <label for="showAllBrands">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success"><?php echo app('translator')->get('label.PUT_TICK_IF_ALLOWED_ALL_BRANDS'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="userName"><?php echo app('translator')->get('label.USERNAME'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('username', null, ['id'=> 'userName', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('username')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="password"><?php echo app('translator')->get('label.PASSWORD'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span>
                                    <?php echo app('translator')->get('label.COMPLEX_PASSWORD_INSTRUCTION'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="confPassword"><?php echo app('translator')->get('label.CONF_PASSWORD'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('conf_password')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']); ?>

                                <span class="text-danger"><?php echo e($errors->first('status')); ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo"><?php echo app('translator')->get('label.LOGO'); ?> :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    <div>
                                        <span class="btn red btn-outline btn-file">
                                            <span class="fileinput-new"> <?php echo app('translator')->get('label.SELECT_IMAGE'); ?> </span>
                                            <span class="fileinput-exists"> <?php echo app('translator')->get('label.CHANGE'); ?> </span>
                                            <?php echo Form::file('logo',['id'=> 'logo']); ?>

                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-success"><?php echo app('translator')->get('label.NOTE'); ?></span>&nbsp;<span class="text-danger bold"><?php echo app('translator')->get('label.SUPPLIER_IMAGE_FOR_IMAGE_DESCRIPTION'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="gMapEmbedCode"><?php echo app('translator')->get('label.GMAP_EMBED_CODE'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo e(Form::textarea('gmap_embed_code', null, ['id'=> 'gMapEmbedCode', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off','placeholder' => __('label.GMAP_EMBED_CODE_PLACEHOLDER')])); ?>

                                <span class="text-danger"><?php echo e($errors->first('gmap_embed_code')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 map-view width-420">

                            </div>
                        </div>

                    </div>

                    <!-- START:: Contact Person Data -->
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold"><?php echo app('translator')->get('label.CONTACT_PERSON'); ?></h3>
                        <div class="form-body">
                            <div class="form-group">
                                <div class="col-md-1">
                                    <button  type="button" class="btn purple-soft add-contact-person tooltips" title="<?php echo app('translator')->get('label.CLICK_HERE_TO_ADD_MORE_CONTACT_PERSON'); ?>">
                                        <?php echo app('translator')->get('label.ADD_CONTACT_PERSON'); ?>&nbsp; <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="" id="newContactPerson"> </div>
                                <?php
                                $v3 = 'z' . uniqid();
                                $v4 = 'z' . uniqid();
                                ?>
                                <div class="col-md-12 contact-person-div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 contact-div">
                                                    <?php echo Form::text('contact_name['.$v3.']', null, ['id'=> 'contactName'.$v3,'class' => 'focus-input']); ?> 
                                                    <label class="floating-label" id="spanName_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.NAME'); ?> <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    <?php echo Form::select('designation_id['.$v3.']',$designationList, null, ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$v3,'data-width' => '100%']); ?> 
                                                    <label class="floating-label" id="spanDeg_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.DESIGNATION'); ?> </label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    <?php echo Form::email('contact_email['.$v3.']', null, ['id'=> 'contactEmail'.$v3,'class' => 'focus-input']); ?>

                                                    <label class="floating-label" id="spanEmail_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.EMAIL'); ?> <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    <?php echo Form::textarea('special_note['.$v3.']', null, ['id'=> 'specialNote'.$v3, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]); ?> 
                                                    <label class="floating-label" id="spanNote_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.SPECIAL_NOTE'); ?></label>
                                                </div>

                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-3  contact-div">
                                                    <?php echo Form::text('contact_phone['.$v3.']['.$v4.']', null, ['id'=> 'contactPhone'.$v3,  'class' => 'integer-only focus-input']); ?> 
                                                    <label class="floating-label" id="spanPhone_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.PHONE'); ?> <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-1 margin-top-10">
                                                    <button class="btn btn-inline green-haze add-phone-number tooltips" data-key="<?php echo e($v3); ?>" data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_PHONE_NUMBER'); ?>" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div id="addPhoneNumberRow<?php echo e($v3); ?>"></div>

                                            </div>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END:: Contact Person Data -->
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green" type="button" id='submitBuyer'>
                                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                            </button>
                            <a href="<?php echo e(URL::to('/buyer'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                        </div>
                    </div>
                </div>
                <?php echo Form::close(); ?>

            </div>	
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('keyup', '#gMapEmbedCode', function () {
            var map = $(this).val();
            $('.map-view').html(map);
            $('.map-view').children().addClass('width-inherit max-height-220');
            $('.map-view').children().children().addClass('width-inherit max-height-220');
            $('.map-view').children().children().children().addClass('width-inherit max-height-220');
        });


        //country wise division
        $(document).on('change', '#countryId', function () {
            var countryId = $(this).val();
            if (countryId == '18') {
                $("#division").show(100);
            } else {
                $("#division").hide(100);
            }
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '<?php echo e(URL::to("buyer/getDivision/")); ?>',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: countryId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDivision').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });

        $(document).on("click", ".add-contact-person", function () {
            $.ajax({
                url: "<?php echo e(route('buyer.createContactPerson')); ?>",
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

        //Function for Save Supplier Data
        $(document).on("click", "#submitBuyer", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#buyerForm')[0]);

            $.ajax({
                url: "<?php echo e(route('buyer.store')); ?>",
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
                            window.location.replace('<?php echo e(URL::to("buyer".Helper::queryPageStr($qpArr))); ?>'
                                    ), 7000);
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
                    App.unblockUI();
                }
            });

        });

        //add multiple phone number
        $(document).on("click", ".add-phone-number", function () {
            var key = $(this).attr("data-key");

            $.ajax({
                url: "<?php echo e(URL::to('buyer/addPhoneNumber')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    key: key,
                },
                success: function (res) {
                    $("#addPhoneNumberRow" + key).prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/buyer/create.blade.php ENDPATH**/ ?>