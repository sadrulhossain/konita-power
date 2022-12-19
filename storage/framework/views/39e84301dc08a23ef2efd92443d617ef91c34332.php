<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.CREATE_NEW_INQUIRY'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'leadForm')); ?>

            <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>


            <?php echo Form::hidden('per_unit',null, ['id' => 'perUnit']); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="buyerId"><?php echo app('translator')->get('label.BUYER'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('buyer_id', $buyerList, null, ['class' => 'form-control js-source-states country-id', 'id' => 'buyerId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('buyer_id')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="buyerContactPerson"><?php echo app('translator')->get('label.BUYER_CONTACT_PERSON'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('buyer_contact_person', $buyerContPersonList, null, ['class' => 'form-control js-source-states country-id', 'id' => 'buyerContactPerson']); ?>

                                <span class="text-danger"><?php echo e($errors->first('buyer_contact_person')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="salespersonsId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('salespersons_id', $salesPersonList, null, ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('salespersons_id')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="creationDate"><?php echo app('translator')->get('label.INQUIRY_DATE'); ?> :<span class="text-danger"> *</span></label>   
                                <?php
                                $currentDate = date('d F Y');
                                ?>
                                <div class="input-group date datepicker2" data-date-end-date="+0d">
                                    <?php echo Form::text('creation_date', $currentDate, ['id'=> 'creationDate', 'class' => 'form-control', 'placeholder' =>'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="creationDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger"><?php echo e($errors->first('creation_date')); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="productId"><?php echo app('translator')->get('label.PRODUCT'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('product_id', $productList, null, ['class' => 'form-control js-source-states', 'id' => 'productId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('product_id')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="brandId"><?php echo app('translator')->get('label.BRAND'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('brand_id', $brandList, null, ['class' => 'form-control js-source-states', 'id' => 'brandId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('brand_id')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div id="showGrade">
                                <div class="col-md-3">
                                    <label class="control-label" for="gradeId"><?php echo app('translator')->get('label.GRADE'); ?> :</label>
                                    <?php echo Form::select('grade_id', $gradeList, null, ['class' => 'form-control js-source-states', 'id' => 'gradeId']); ?>

                                    <?php echo Form::hidden('grade_value', '0', ['id' => 'gradeValue']); ?>

                                    <span class="text-danger"><?php echo e($errors->first('grade_id')); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="gsm"><?php echo app('translator')->get('label.GSM'); ?> :<span class="text-danger"> *</span></label>
                                <div class="input-group bootstrap-touchspin col-md-12">
                                    <?php echo Form::text('gsm', null, ['id'=> 'gsm', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('gsm')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="quantity"><?php echo app('translator')->get('label.QUANTITY'); ?> :<span class="text-danger"> *</span></label>
                                <div class="input-group bootstrap-touchspin">
                                    <?php echo Form::text('quantity', null, ['id'=> 'quantity', 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']); ?> 
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold" id="quantityUnit"></span>
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('quantity')); ?></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="unitPrice"><?php echo app('translator')->get('label.UNIT_PRICE'); ?> :</label>
                                <div class="input-group bootstrap-touchspin">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('unit_price', null, ['id'=> 'unitPrice', 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']); ?> 
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold" id="unitPricePerUnit"></span>
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('unit_price')); ?></span>
                                <span id="product-pricing"></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="totalPrice"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?> :</label>
                                <div class="input-group bootstrap-touchspin">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('total_price',!empty($totalPrice)?$totalPrice: null, ['id'=> 'totalPrice', 'class' => 'form-control','readonly']); ?> 
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('total_price')); ?></span>
                            </div>
                        </div>

                        <!--START Head office address-->
                        <div class="form">
                            <div class="col-md-3 margin-top-10">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio">
                                        <input type="radio" name="shipment_address_status" id="shipmentAddress1" value="1" checked> <?php echo app('translator')->get('label.HEAD_OFFICE'); ?>
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input type="radio" name="shipment_address_status" id="shipmentAddress2" value="2"> <?php echo app('translator')->get('label.FACTORY'); ?>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="form" id="addressShow">
                            <div class="col-md-3">
                                <label class="control-label" for="address"><?php echo app('translator')->get('label.ADDRESS'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::text('head_office_address', null, ['id'=> 'address', 'class' => 'form-control','autocomplete' => 'off','readonly']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('head_office_address')); ?></span>
                            </div>
                        </div>
                        <div class="form" id="factoryShow" style="display: none">
                            <div class="col-md-3">
                                <label class="control-label" for="factoryId"><?php echo app('translator')->get('label.FACTORY'); ?> :<span class="text-danger"> *</span></label>
                                <?php echo Form::select('factory_id', $factoryList, null, ['class' => 'form-control js-source-states', 'id' => 'factoryId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('factory_id')); ?></span>
                                <span id="buyerFactoryAddress"></span>
                            </div>
                        </div>
                        <!--endof Head office address div-->
                        <div class="form">
                            <div class="col-md-3 margin-top-27">
                                <span class="btn green tooltips" type="button" id="addItem"  title="Add Item">
                                    <i class="fa fa-plus text-white"></i>&nbsp;<span><?php echo app('translator')->get('label.ADD_ITEM'); ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--new lead item list-->
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 table-responsive webkit-scrollbar">
                        <p><b><u><?php echo app('translator')->get('label.NEW_LEAD_ITEM_LIST'); ?>:</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                    <th class="text-right vcenter"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                    <th class="text-right vcenter"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <tr id="hideNodata">
                                    <td colspan="8"><?php echo app('translator')->get('label.NO_DATA_SELECT_YET'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="editRowId" value="">
                    <input type="hidden" id="total" value="">
                </div>
            </div>

            <div class="form-body">
                <!--                <div class="row">
                                    <div class="col-md-12 md-checkbox has-success">
                                        <?php echo Form::checkbox('add_first_followup',1,null, ['id' => 'addFirstFollowup', 'class'=> 'md-check']); ?>

                                        <label for="addFirstFollowup">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                        <span class="text-success"><?php echo app('translator')->get('label.ADD_FIRST_FOLLOWUP_FOR_THIS_INQUIRY'); ?></span>
                                    </div>
                                </div>-->
                <div class="row margin-top-10 first-followup-block">
                    <!--                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 margin-bottom-10">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="opportunityId"><?php echo app('translator')->get('label.OPPORTUNITY'); ?>:</label>
                                                <div class="col-md-8">
                                                    <button class="btn btn-sm bold blue-steel tooltips vcenter choose-opportunity" title="<?php echo app('translator')->get('label.CLICK_TO_CHOOSE_OPPORTUNITY'); ?>" href="#modalChooseOpportunity" data-id="0" data-toggle="modal">
                                                        <?php echo app('translator')->get('label.CHOOSE_OPPORTUNITY'); ?>
                                                    </button>
                                                    <button class="btn btn-sm bold yellow tooltips vcenter opportunity-details" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_OPPORTUNITY_DETAILS'); ?>" href="#modalOpportunityDetails" data-id="0" data-toggle="modal">
                                                        <i class="fa fa-bars"></i>
                                                    </button>
                                                    <button class="btn btn-sm bold red-intense tooltips vcenter clear-opportunity-choice" type="button" title="<?php echo app('translator')->get('label.CLICK_TO_CLEAR_OPPORTUNITY_CHOICE'); ?>">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <?php echo Form::hidden('opportunity_id', 0, ['id'=>'opportunityId']); ?>

                                                </div>
                                            </div>
                                        </div>-->
                    <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-2 col-lg-6 col-md-6 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h5><strong><?php echo app('translator')->get('label.FIRST_FOLLOWUP'); ?></strong></h5>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4" for="followupStatus"><?php echo app('translator')->get('label.STATUS'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('followup_status', $followupStatusList, null, ['class' => 'form-control js-source-states ','id'=>'followupStatus']); ?>

                                <span class="text-danger"><?php echo e($errors->first('followup_status')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4" for="followupRremarks"><?php echo app('translator')->get('label.REMARKS'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::textarea('followup_remarks', null, ['id'=> 'followupRremarks', 'class' => 'form-control']); ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions margin-top-20">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id='submitButton' disabled>
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/lead'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>

<!-- Modal start -->

<!--opportunity details-->
<div class="modal fade" id="modalOpportunityDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityDetails"></div>
    </div>
</div>

<!--assign opportunity-->
<div class="modal fade" id="modalChooseOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showChooseOpportunity"></div>
    </div>
</div>

<!-- Modal end -->
<script type="text/javascript">
    $(document).ready(function () {
        //add first followup
//        $(".first-followup-block").hide();
//        
//        $(document).on("click", "#addFirstFollowup", function(){
//            if ($(this).prop('checked')) {
//                $('.first-followup-block').slideDown(800);
//                $(".first-followup-block span.select2").css("width", "100%");
//            } else {
//                $('.first-followup-block').slideUp(800);
//            }
//        });


        //***************** Start :: choose opportunity *********************
        //initialy hide opportunity details
        $('.opportunity-details').hide();
        $('.clear-opportunity-choice').hide();

        //clear opportunity choice
        $('.clear-opportunity-choice').on('click', function () {
            $('.opportunity-details').attr('data-id', 0);
            $('.choose-opportunity').attr('data-id', 0);
            $('#opportunityId').val(0);
            $('.opportunity-details').hide();
            $('.clear-opportunity-choice').hide();
        });

        //choose opportunity modal
        $(".choose-opportunity").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/lead/getChooseOpportunity')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    selected_opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showChooseOpportunity").html('');
                },
                success: function (res) {
                    $("#showChooseOpportunity").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set oppornunity choice
        $(document).on("click", "#saveOpportunityChoice", function (e) {
            e.preventDefault();
            var formData = new FormData($('#chooseOpportunityForm')[0]);
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
                confirmButtonText: 'Yes,Confirm as done',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('lead/setChooseOpportunity')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveOpportunityChoice').prop('disabled', true);
                            App.blockUI({
                                boxed: true,
                            });
                        },
                        success: function (res) {
                            $('#saveOpportunityChoice').prop('disabled', false);
                            App.unblockUI();
                            $('#modalChooseOpportunity').modal('hide');
                            var opportunityId = res.opportunityId;
                            $('.opportunity-details').show();
                            $('.clear-opportunity-choice').show();
                            $('.opportunity-details').attr('data-id', opportunityId);
                            $('.choose-opportunity').attr('data-id', opportunityId);
                            $('#opportunityId').val(opportunityId);

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
                            $('#saveOpportunityChoice').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        //show opportunity details
        $(".opportunity-details").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/lead/getOpportunityDetails')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showOpportunityDetails").html('');
                },
                success: function (res) {
                    $("#showOpportunityDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //***************** End :: choose opportunity ***********************

        //SHOW TOTAL PRICE MULTIPLICATION
        $('#unitPrice').keyup(function (e) {
            var totalPrice = 0;
            var totalQuantity = $('#quantity').val();

            $.each($('#unitPrice'), function () {
                totalPrice = totalQuantity * $(this).val();
            });
            $('#totalPrice').val(totalPrice.toFixed(2));

        });

        $('#quantity').keyup(function (e) {
            var totalPrice = 0;
            var unitPrice = $('#unitPrice').val();

            $.each($('#quantity'), function () {
                totalPrice = unitPrice * $(this).val();
            });

            $('#totalPrice').val(totalPrice.toFixed(2));
        });

        //ENDOF MULTIPLICATION SCRIPT

        //hide & show
        $(document).on('change', '#shipmentAddress2', function (e) {
            $('#factoryShow').show('100');
            $('#addressShow').hide('100');
            $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
        });

        $(document).on('change', '#shipmentAddress1', function (e) {
            $('#factoryShow').hide('100');
            $('#addressShow').show('100');
            $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
        });

        //factory Under Show Factory Address
        $(document).on('change', '#factoryId', function (e) {
            var factoryId = $('#factoryId').val();

            $.ajax({
                url: "<?php echo e(URL::to('lead/getFactoryAddress')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    factory_id: factoryId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#buyerFactoryAddress').html(res.address);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });


        //buyer under buyer contact persons
        $(document).on('change', '#buyerId', function (e) {
            var buyerId = $('#buyerId').val();
            $('#buyerFactoryAddress').html('');
            $.ajax({
                url: "<?php echo e(URL::to('lead/getBuyerContPerson')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#buyerContactPerson').html(res.html);
                    $('#factoryId').html(res.factory);
                    $('#salespersonsId').html(res.salesPerson);

                    if (res.headOffice != '') {
                        $('#address').val(res.headOffice);
                        $('#address').prop('readonly', true)
                    } else {
                        $('#address').val('');
                        $('#address').prop('readonly', true)
                    }


                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });

        //buyer and Sales Person under product**
        $(document).on('change', '#buyerId,#salespersonsId', function (e) {
            var buyerId = $('#buyerId').val();
            var salespersonsId = $('#salespersonsId').val();
            $("#brandId").html("<option value='0'><?php echo app('translator')->get('label.SELECT_BRAND_OPT'); ?></option>");

            $.ajax({
                url: "<?php echo e(URL::to('lead/getLeadProduct')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#productId').html(res.html);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });


        //product  under Brand**

        $(document).on('change', '#productId', function (e) {
            var productId = $('#productId').val();
            var buyerId = $('#buyerId').val();
            var salespersonsId = $('#salespersonsId').val();
            $("#product-pricing").html('');
            $.ajax({
                url: "<?php echo e(URL::to('lead/getLeadBrand')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#brandId').html(res.html);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });


        //***** GET Product && Brand Wise Grade ********
        $(document).on('change', '#productId,#brandId', function (e) {
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            $.ajax({
                url: "<?php echo e(URL::to('lead/getLeadGrade')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showGrade').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });

        //Endof GET Product && Brand Wise Grade


        //product measurement unit**

        $(document).on('change', '#productId', function (e) {
            var productId = $('#productId').val();

            $.ajax({
                url: "<?php echo e(URL::to('lead/getLeadProductUnit')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    var unit = res.measureUnitName;
                    var perUnit = res.perMeasureUnitName;
                    $("span#quantityUnit").text(unit);
                    $("span#unitPricePerUnit").text(perUnit);
                    $("#perUnit").val(perUnit);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });



        //Function for Save lead Data
        $(document).on("click", "#submitButton", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#leadForm')[0]);
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
                        url: "<?php echo e(route('lead.store')); ?>",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#submitButton').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('<?php echo e(route("lead.index")); ?>'
                                            ), 7000);
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
                            $('#submitButton').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });

        });


        // get vaule of target selling price and minimum selling price
        //set unit price status
        $('#unitPrice').keyup(function (e) {
            var priceStatus = 0;
            var minimumPrice = $('#minimumSellingPrice').val();


            $.each($('#unitPrice'), function () {
                priceStatus = $(this).val() - minimumPrice;
            });

            var minus = '';
            $('span#priceStatus').css("color", "#333");
            if (priceStatus < 0) {
                priceStatus = (-1) * priceStatus;
                minus = '-';
                $('span#priceStatus').css("color", "red");
                $('span#priceStatus').text(minus + "$" + priceStatus);
            } else {

                $('span#priceStatus').css("color", "#333");
                $('#priceStatus').text(priceStatus);
            }
        });
        // productPricing
        $(document).on('change', '#brandId', function (e) {
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var unitPrice = $('#unitPrice').val()

            $.ajax({
                url: "<?php echo e(URL::to('lead/getProductPricing')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    unit_price: unitPrice,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#product-pricing').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });

        $(document).on('change', '#gradeId', function (e) {
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var gradeId = $('#gradeId').val();
            var unitPrice = $('#unitPrice').val()

            $.ajax({
                url: "<?php echo e(URL::to('lead/getProductPricing')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    grade_id: gradeId,
                    unit_price: unitPrice,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#product-pricing').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });

        //endof product priceing






        //************************ NEW ADD ITEM SCRIPT *****************************
        var count = 1;
        $('#addItem').on('click', function () {
            $('.edit-show').attr("disabled", false);
            $('.remove-show').attr("disabled", false);

            var buyerId = $('#buyerId').val();
            var buyerContactPerson = $('#buyerContactPerson').val();
            var salespersonsId = $('#salespersonsId').val();
            var creationDate = $('#creationDate').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var gradeId = $('#gradeId').val();

            if (gradeId == null) {
                gradeId = '';
            }

            var gradeValue = $('#gradeValue').val();
            var gsm = $('#gsm').val();
            var quantity = $('#quantity').val();
            var unitPrice = $('#unitPrice').val();
            var totalPrice = $('#totalPrice').val();
            var shipmentAddress1 = $('#shipmentAddress1').val();
            var shipmentAddress2 = $('#shipmentAddress2').val();
            var address = $('#address').val();
            var factoryId = $('#factoryId').val();
            var countNumber = count++;

            if (unitPrice == '') {
                unitPrice = 0.00;
            }



            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (buyerId == '0') {
                toastr.error("Please select  Buyer", "Error", options);
                return false;
            }

            if (buyerContactPerson == '0') {
                toastr.error("Please select  Buyer Contact Person", "Error", options);
                return false;
            }

            if (salespersonsId == '0') {
                toastr.error("Please select  Sales Persons", "Error", options);
                return false;
            }

            if (creationDate == '') {
                toastr.error("Please select  Creation Date", "Error", options);
                return false;
            }

            if (productId == '0') {
                toastr.error("Please select  Product", "Error", options);
                return false;
            }


            if (brandId == '0') {
                toastr.error("Please select Brand", "Error", options);
                return false;
            }

            if (gradeValue == '1') {
                if (gradeId == '0') {
                    toastr.error("Please select Grade", "Error", options);
                    return false;
                }
            }

            if (gsm == '') {
                toastr.error("Please insert gsm", "Error", options);
                return false;
            }

            if (quantity == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }

            var grade = 0;
            if (gradeId != '') {
                grade = gradeId;
            }

            var prevItemVal = $("#prevItem_" + productId + "_" + brandId + "_" + grade + "_" + gsm).val();

            if (typeof prevItemVal !== 'undefined') {
                toastr.error("This item has already been added", "Error", options);
                return false;
            }

            //when i edit one row then delete previous row
            var editRow = $("#editRowId").val();
            if (editRow != '') {
                $('#rowId_' + editRow).remove();
            }


            $.ajax({
                url: "<?php echo e(URL::to('lead/getProductBrandData')); ?>",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    grade_id: gradeId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                }
            }).done(function (result) {

                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                row = '<tr id="rowId_' + productId + '_' + countNumber + '" class="item-list">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + productId + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + productId + '_' + countNumber + '"  name="quantity[]"  value="' + parseFloat(quantity).toFixed(2) + '">\n\
                    <input type="hidden" id="unitPrice_' + productId + '_' + countNumber + '"  name="unit_price[]"  value="' + parseFloat(unitPrice).toFixed(2) + '">\n\
                    <input type="hidden" id="totalPrice_' + productId + '_' + countNumber + '"  name="total_price[]" class="item-amount"  value="' + totalPrice + '">\n\
                    <input type="hidden" id="productId_' + productId + '_' + countNumber + '" name="product_id[]"  value="' + productId + '">\n\
                    <input type="hidden" id="brandId_' + productId + '_' + countNumber + '" name="brand_id[]"  value="' + brandId + '">\n\
                    <input type="hidden" id="gradeId_' + productId + '_' + countNumber + '" name="grade_id[]"  value="' + gradeId + '">\n\
                    <input type="hidden" id="gsm_' + productId + '_' + countNumber + '" name="gsm[]"  value="' + gsm + '">\n\
\n\
                    <input type="hidden" id="prevItem_' + productId + '_' + brandId + '_' + grade + '_' + gsm + '" name="prev_item[' + productId + '][' + brandId + '][' + grade + '][' + gsm + ']"  value="1">\n\
                    ' + result.productName + '</td>\n\
                <td>' + result.brandName + '</td>\n\
                <td>' + result.gradeName + '</td>\n\
                <td>' + gsm + '</td>\n\
                <td class="text-center">' + parseFloat(quantity).toFixed(2) + ' ' + result.productUnit + '</td>\n\
                <td class="text-right">$' + parseFloat(unitPrice).toFixed(2) + ' ' + '/' + result.productUnit + '</td>\n\
                <td class="text-right">$' + totalPrice + '</td>\n\
                <td class="text-center">\n\
                    <button type="button" class="btn btn-xs btn-primary vcenter edit-show" id="editBtn' + productId + '_' + countNumber + '" title="Edit Product" onclick="editProduct(' + productId + ',' + countNumber + ');"><i class="fa fa-edit text-white"></i></button>\n\
                    <button type="button" onclick="removeItem(' + productId + ',' + countNumber + ');" class="btn btn-xs btn-danger vcenter remove-show" id="deleteBtn' + productId + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
                </td></tr>';
                // get total amount

                if (rowCount == 1) {
                    row += '<tr id="netTotalRow">\n\
                    <td colspan="6" class="text-right">Total</td>\n\
                    <td class="text-right interger-decimal-only">$<span id="netTotal"><span/></td>\n\
                    <td></td>\n\
                    </tr>';
                    $('#itemRows').append(row);
                } else {
                    $('#itemRows tr:last').before(row);
                }

                var netTotal = 0;
                $(".item-amount").each(function () {
                    netTotal += parseFloat($(this).val());
                });

                $('#netTotal').text(netTotal.toFixed(2));
                $('#productId').focus();
                $('#submitButton').attr("disabled", false);

                App.unblockUI();
            });
        });


        //if buyer && salesperson change then remove existing item from table
        $(document).on('change', '#buyerId,#salespersonsId', function () {
            $('tr#netTotalRow').remove();
            $('tr.item-list').remove();
            $('tr#hideNodata').show();
            $('#submitButton').attr("disabled", true);
        });


        //************** END OF NEW ITEM ADD ********************
    });

    //****************** Remove Item *****************
    function removeItem(productId, countNumber) {

        $('#rowId_' + productId + '_' + countNumber).remove();
        var rowCount = $('tbody#itemRows tr').length;
        if (rowCount == 2) {
            $('tr#netTotalRow').remove();
            $('#hideNodata').show();
        }

        var netTotal = 0;
        $(".item-amount").each(function () {
            netTotal += parseFloat($(this).val());
        });

        $('#netTotal').text(netTotal);
        $('#submitButton').attr("disabled", false);

    }

    //*************Endof remove Item *****************

    //****************** edit item ***********************
    function editProduct(editId, countNumber) {
        var quantity1 = $('#quantity_' + editId + '_' + countNumber).val();
        var unitPrice1 = $('#unitPrice_' + editId + '_' + countNumber).val();
        var totalPrice1 = $('#totalPrice_' + editId + '_' + countNumber).val();
        var productId = $('#productId_' + editId + '_' + countNumber).val();
        var brandId = $('#brandId_' + editId + '_' + countNumber).val();
        var gradeId = $('#gradeId_' + editId + '_' + countNumber).val();
        var gsm = $('#gsm_' + editId + '_' + countNumber).val();
        if (gradeId == '') {
            gradeId = 0;
        }

        var editRowId = $('#editRowId').val();
        var quantity = parseFloat(quantity1).toFixed(2);
        var unitPrice = parseFloat(unitPrice1).toFixed(2);
        var totalPrice = parseFloat(totalPrice1).toFixed(2);


        var buyerId = $('#buyerId').val();
        var salespersonsId = $('#salespersonsId').val();


        //ajax call product wise brand
        $.ajax({
            url: "<?php echo e(URL::to('lead/getLeadBrand')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                buyer_id: buyerId,
                salespersons_id: salespersonsId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#brandId').html(res.html);
                $('#brandId').val(brandId).select2();

                $('.tooltips').tooltip();
                $(".js-source-states").select2({dropdownParent: $('body')});
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax


        //Get Product & Brand wise Grade

        $.ajax({
            url: "<?php echo e(URL::to('lead/getLeadGrade')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
//                $('#gradeId').html(res.html);
                $('#showGrade').html(res.html);
                $('#gradeId').val(gradeId).select2();

                $('.tooltips').tooltip();
                $(".js-source-states").select2({dropdownParent: $('body')});
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax


        //Product priceing
        $.ajax({
            url: "<?php echo e(URL::to('lead/getProductPricing')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
                grade_id: gradeId,
                unit_price: unitPrice,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#product-pricing').html(res.html);
                $('.tooltips').tooltip();
                $(".js-source-states").select2({dropdownParent: $('body')});
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

        //Product Measurement Unit************
        $.ajax({
            url: "<?php echo e(URL::to('lead/getLeadProductUnit')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                var unit = res.measureUnitName;
                var perUnit = res.perMeasureUnitName;
                $("span#quantityUnit").text(unit);
                $("span#unitPricePerUnit").text(perUnit);
                $("#perUnit").val(perUnit);
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

        //end of ajax call



        $('#gsm').val(gsm);
        $('#quantity').val(quantity);
        $('#unitPrice').val(unitPrice);
        $('#totalPrice').val(totalPrice);
        $('#productId').val(productId).select2();
        $('#brandId').val(brandId).select2();
        $('#gradeId').val(gradeId).select2();

        $("#editRowId").val(editId + '_' + countNumber);

        $('#editBtn' + editId + '_' + countNumber).attr('disabled', true);
        $('#deleteBtn' + editId + '_' + countNumber).attr('disabled', true);
//        alert(editRowId);return false;



        if (editRowId != '') {
            $('#editBtn' + editRowId).prop('disabled', true);
            $('#deleteBtn' + editRowId).prop('disabled', true);
        }
    }

    //*********************END OF EDIT ITEM ******************


</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/lead/create.blade.php ENDPATH**/ ?>