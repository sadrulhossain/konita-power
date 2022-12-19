<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.CREATE_NEW_OPPORTUNITY'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => '', 'id' => 'newOppornunityCreateForm','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <?php echo Form::hidden('sales_person_id', Auth::user()->id, ['id' => 'salesPersonId']); ?>

            <?php echo Form::hidden('sales_person_group_id', Auth::user()->group_id, ['id' => 'salesPersonGroupId']); ?>

            <?php echo Form::hidden('status', 1, ['id' => 'statusId']); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyer"><?php echo app('translator')->get('label.BUYER'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group bootstrap-touchspin">
                                    <span class="buyer-select-span">
                                        <?php echo Form::select('buyer_id', $buyerList, null, ['id'=> 'buyerId', 'class' => 'form-control buyer-select js-source-states']); ?>

                                    </span>
                                    <!--<?php echo Form::text('buyer_name',null, ['id'=> 'buyerName', 'class' => 'form-control buyer-text','autocomplete' => 'off']); ?>--> 
    <!--                                    <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                        <button class="btn btn-sm blue-steel buyer-text-btn bold tooltips" title="<?php echo app('translator')->get('label.CLICK_TO_TYPE_AS_TEXT'); ?>" type="button">
                                            <i class="fa fa-text-height bold"></i> 
                                        </button>
                                        <button class="btn btn-sm blue-steel buyer-select-btn bold tooltips display-none" title="<?php echo app('translator')->get('label.CLICK_TO_SELECT_FROM_DROPDOWN'); ?>" type="button">
                                            <i class="fa fa-angle-down bold"></i>
                                        </button>
                                    </span>-->                            
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('buyer')); ?></span>
                            </div>
                            <?php echo Form::hidden('buyer_has_id', '1', ['class' => 'buyer-has-id', 'id' => 'buyerHasId']); ?>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="address"><?php echo app('translator')->get('label.ADDRESS'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control', 'autocomplete' => 'off', 'size' => '20x3',]); ?> 
                                <span class="text-danger"><?php echo e($errors->first('address')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyerContactPerson"><?php echo app('translator')->get('label.BUYER_CONTACT_PERSON'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('buyer_contact_person', $buyerContPersonList, null, ['class' => 'form-control js-source-states', 'id' => 'buyerContactPerson']); ?>

                                <span class="text-danger"><?php echo e($errors->first('buyer_contact_person')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="sourceId"><?php echo app('translator')->get('label.SOURCE'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('source_id', $sourceList, null, ['class' => 'form-control js-source-states', 'id' => 'sourceId']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('source_id')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="remarks"><?php echo app('translator')->get('label.REMARKS'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control', 'autocomplete' => 'off', 'size' => '20x3',]); ?> 
                                <span class="text-danger"><?php echo e($errors->first('remarks')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Start :: Contact Person Info-->
                <!--                <div class="row margin-top-10">
                                    <div class="col-md-12">
                                        <div class="col-md-12 border-bottom-1-green-seagreen">
                                            <h5><strong><?php echo app('translator')->get('label.CONTACT_PERSON_INFORMATION'); ?></strong></h5>
                                        </div>
                                        <div class="col-md-12 margin-top-10">
                                            <div class="table-responsive webkit-scrollbar">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="active">
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.PRIMARY_CONTACT'); ?></th>
                                                            <th class="text-center vcenter"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                <?php
                $v3 = 'c' . uniqid();
                ?>
                                                        <tr>
                                                            <td class="text-center vcenter initial-contact-sl width-50">1</td>
                                                            <td class="text-center vcenter">
                                                                <?php echo Form::text('contact['.$v3.'][name]', null, ['id'=> 'contactName_'.$v3, 'class' => 'form-control']); ?>

                                                            </td>
                                                            <td class="text-center vcenter">
                                                                <?php echo Form::text('contact['.$v3.'][designation]', null, ['id'=> 'contactDesignation_'.$v3, 'class' => 'form-control']); ?>

                                                            </td>
                                                            <td class="text-center vcenter">
                                                                <?php echo Form::text('contact['.$v3.'][email]', null, ['id'=> 'contactEmail_'.$v3, 'class' => 'form-control']); ?>

                                                            </td>
                                                            <td class="text-center vcenter">
                                                                <?php echo Form::text('contact['.$v3.'][phone]', null, ['id'=> 'contactPhone_'.$v3, 'class' => 'form-control']); ?>

                                                            </td>
                                                            <td class="text-center vcenter width-50">
                                                                <div class="checkbox-center md-checkbox has-success width-inherit">
                                                                    <?php echo Form::checkbox('contact['.$v3.'][primary]',1,null, ['id' => 'contactPrimary_'.$v3, 'data-key' => $v3, 'class'=> 'md-check primary-contact']); ?>

                                                                    <label for="contactPrimary_<?php echo e($v3); ?>">
                                                                        <span class="inc"></span>
                                                                        <span class="check box-double-rounded mark-caheck"></span>
                                                                        <span class="box box-rounded mark-caheck"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center vcenter width-50">
                                                                <button class="btn btn-inline green-haze add-new-contact-row tooltips" data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_ROW_OF_CONTACT_INFO'); ?>" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody id="newContactTbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                <!--End :: Contact Person Info-->

                <!--Start :: Product Info-->
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <div class="col-md-12 border-bottom-1-green-seagreen">
                            <h5><strong><?php echo app('translator')->get('label.PRODUCT_INFORMATION'); ?></strong></h5>
                        </div>
                        <div class="col-md-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?><span class="text-danger"> *</span></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.BRAND'); ?><span class="text-danger"> *</span></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.ORIGIN'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.GSM'); ?><span class="text-danger"> *</span></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.UNIT'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                            <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                                            <th class="text-center vcenter"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $v4 = 'p' . uniqid();
                                        ?>
                                        <tr>
                                            <td class="text-center vcenter initial-product-sl width-50">1</td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="product">
                                                    <span class="product-select-span-<?php echo e($v4); ?>">
                                                        <?php echo Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-select js-source-states product-item']); ?>

                                                    </span>
                                                    <!--<?php echo Form::text('product['.$v4.'][product_name]',null, ['id'=> 'productProductName_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-text display-none','autocomplete' => 'off']); ?>--> 
<!--                                                    <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                        <button class="btn btn-sm blue-steel product-text-btn product-text-btn-<?php echo e($v4); ?> bold tooltips" data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_TYPE_AS_TEXT'); ?>" type="button">
                                                            <i class="fa fa-text-height bold"></i> 
                                                        </button>
                                                        <button class="btn btn-sm blue-steel product-select-btn product-select-btn-<?php echo e($v4); ?> bold tooltips display-none"  data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_SELECT_FROM_DROPDOWN'); ?>" type="button">
                                                            <i class="fa fa-angle-down bold"></i>
                                                        </button>
                                                    </span>-->
                                                </div>
                                                <?php echo Form::hidden('product['.$v4.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$v4]); ?>

                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="productWiseBrandId_<?php echo e($v4); ?>">
                                                    <span class="brand-select-span-<?php echo e($v4); ?>">
                                                        <?php echo Form::select('product['.$v4.'][brand_id]', $brandList, null, ['id'=> 'productBrandId_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-select js-source-states brand-item']); ?>

                                                    </span>
                                                    <!--<?php echo Form::text('product['.$v4.'][brand_name]',null, ['id'=> 'productBrandName_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-text display-none','autocomplete' => 'off']); ?>--> 
<!--                                                    <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                        <button class="btn btn-sm blue-steel brand-text-btn brand-text-btn-<?php echo e($v4); ?> bold tooltips" data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_TYPE_AS_TEXT'); ?>" type="button">
                                                            <i class="fa fa-text-height bold"></i> 
                                                        </button>
                                                        <button class="btn btn-sm blue-steel brand-select-btn brand-select-btn-<?php echo e($v4); ?> bold tooltips display-none"  data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_SELECT_FROM_DROPDOWN'); ?>" type="button">
                                                            <i class="fa fa-angle-down bold"></i>
                                                        </button>
                                                    </span>-->
                                                </div>
                                                <?php echo Form::hidden('product['.$v4.'][brand_has_id]', '1', ['class' => 'brand-has-id', 'id' => 'brandHasId_'.$v4]); ?>

                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit" id="brandWiseGrade_<?php echo e($v4); ?>">
                                                    <span class="grade-select-span-<?php echo e($v4); ?>">
                                                        <?php echo Form::select('product['.$v4.'][grade_id]', $gradeList, null, ['id'=> 'productGradeId_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-select js-source-states']); ?>

                                                    </span>
                                                    <!--                                                    <?php echo Form::text('product['.$v4.'][grade_name]',null, ['id'=> 'productGradeName_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-text display-none','autocomplete' => 'off']); ?> 
                                                                                                        <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                                                            <button class="btn btn-sm blue-steel grade-text-btn grade-text-btn-<?php echo e($v4); ?> bold tooltips" data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_TYPE_AS_TEXT'); ?>" type="button">
                                                                                                                <i class="fa fa-text-height bold"></i> 
                                                                                                            </button>
                                                                                                            <button class="btn btn-sm blue-steel grade-select-btn grade-select-btn-<?php echo e($v4); ?> bold tooltips display-none"  data-key="<?php echo e($v4); ?>" title="<?php echo app('translator')->get('label.CLICK_TO_SELECT_FROM_DROPDOWN'); ?>" type="button">
                                                                                                                <i class="fa fa-angle-down bold"></i>
                                                                                                            </button>
                                                                                                        </span>-->
                                                </div>
                                                <?php echo Form::hidden('product['.$v4.'][grade_has_id]', '1', ['class' => 'grade-has-id', 'id' => 'gradeHasId_'.$v4]); ?>

                                            </td>
                                            <td class="text-center vcenter width-240">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span id="brandWiseOrigin_<?php echo e($v4); ?>">
                                                        <!--<?php echo Form::select('product['.$v4.'][origin]', $countryList, null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4, 'class' => 'form-control js-source-states']); ?>-->
                                                    </span>
                                                </div>
                                            </td>
                                            <?php echo Form::hidden('product['.$v4.'][origin]', null, ['id'=> 'productOrigin_'.$v4, 'data-key' => $v4]); ?>

                                            <td class="text-center vcenter width-100">
                                                <?php echo Form::text('product['.$v4.'][gsm]', null, ['id'=> 'productGsm_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-gsm']); ?>

                                            </td>
                                            <td class="text-center vcenter width-100">
                                                <?php echo Form::text('product['.$v4.'][quantity]', null, ['id'=> 'productQuantity_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']); ?>

                                            </td>
                                            <td class="text-center vcenter width-80">
                                                <?php echo Form::text('product['.$v4.'][unit]', null, ['id'=> 'productUnit_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-unit','readonly']); ?>

                                            </td>
                                            <td class="text-center vcenter width-180">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('product['.$v4.'][unit_price]', null, ['id'=> 'productUnitPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-unit-price']); ?>

                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold product-per-unit-<?php echo e($v4); ?>"></span>
                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('product['.$v4.'][total_price]', null, ['id'=> 'productTotalPrice_'.$v4, 'data-key' => $v4, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per product-total-price', 'readonly']); ?>

                                                </div>
                                            </td>
                                            <td class="text-center vcenter width-50">
                                                <button class="btn btn-inline green-haze add-new-product-row tooltips" data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_ROW_OF_PRODUCT_INFO'); ?>" type="button">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody id="newProductTbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End :: Product Info-->
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-circle green" type="button" id="crmOpportunityCreateSubmit">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/crmNewOpportunity'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>

<script>
    $(function () {
        $("#addFullMenuClass").addClass("page-sidebar-closed");
        $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");
        //buyer input
//        $('.buyer-text-btn').on('click', function () {
//            $(this).addClass('display-none');
//            $('.buyer-select-span').addClass('display-none');
//            $('.buyer-select-btn').removeClass('display-none');
//            $('.buyer-text').removeClass('display-none');
//            $('#buyerHasId').val('0');
//        });
//        $('.buyer-select-btn').on('click', function () {
//            $(this).addClass('display-none');
//            $('.buyer-text').addClass('display-none');
//            $('.buyer-text-btn').removeClass('display-none');
//            $('.buyer-select-span').removeClass('display-none');
//            $('.buyer-select-span span.select2').css('width', '100%');
//            $('#buyerHasId').val('1');
//        });

        //product input
        $('.product-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.product-select-span-' + key).addClass('display-none');
            $('.product-select-btn-' + key).removeClass('display-none');
            $('#productProductName_' + key).removeClass('display-none');
            $('#productHasId_' + key).val('0');
        });
        $('.product-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productProductName_' + key).addClass('display-none');
            $('.product-text-btn-' + key).removeClass('display-none');
            $('.product-select-span-' + key).removeClass('display-none');
            $('.product-select-span-' + key + ' span.select2').css('width', '100%');
            $('#productHasId_' + key).val('1');
        });

        //brand input
        $('.brand-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.brand-select-span-' + key).addClass('display-none');
            $('.brand-select-btn-' + key).removeClass('display-none');
            $('#productBrandName_' + key).removeClass('display-none');
            $('#brandHasId_' + key).val('0');
        });
        $('.brand-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productBrandName_' + key).addClass('display-none');
            $('.brand-text-btn-' + key).removeClass('display-none');
            $('.brand-select-span-' + key).removeClass('display-none');
            $('.brand-select-span-' + key + ' span.select2').css('width', '100%');
            $('#brandHasId_' + key).val('1');
        });

        //grade input
        $('.grade-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.grade-select-span-' + key).addClass('display-none');
            $('.grade-select-btn-' + key).removeClass('display-none');
            $('#productGradeName_' + key).removeClass('display-none');
            $('#gradeHasId_' + key).val('0');
        });
        $('.grade-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productGradeName_' + key).addClass('display-none');
            $('.grade-text-btn-' + key).removeClass('display-none');
            $('.grade-select-span-' + key).removeClass('display-none');
            $('.grade-select-span-' + key + ' span.select2').css('width', '100%');
            $('#gradeHasId_' + key).val('1');
        });


        $(document).on('click', '.primary-contact', function () {
            var key = $(this).attr('data-key');
            if ($(this).prop('checked')) {
                $('.primary-contact').prop('checked', false);
                $('#contactPrimary_' + key).prop('checked', true);
            }
        });

        //add new contact row
        $(document).on("click", ".add-new-contact-row", function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "<?php echo e(URL::to('crmNewOpportunity/newContactRow')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#newContactTbody").append(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('contact');
                },
            });
        });
        //remove contact row
        $(document).on('click', '.remove-contact-row', function () {
            $(this).parent().parent().remove();
            rearrangeSL('contact');
            return false;
        });

        //add new product row
        $(document).on("click", ".add-new-product-row", function (e) {
            e.preventDefault();
            var buyerId = $('#buyerId').val();
            var statusId = $('#statusId').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "<?php echo e(URL::to('crmNewOpportunity/newProductRow')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    buyer_id: buyerId,
                    status_id: statusId
                },
                success: function (res) {
                    $("#newProductTbody").append(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('product');
                },
            });
        });
        //remove product row
        $(document).on('click', '.remove-product-row', function () {
            $(this).parent().parent().remove();
            rearrangeSL('product');
            return false;
        });

        //load per unit
        $(document).on('keyup', '.product-unit', function () {
            var key = $(this).attr('data-key');
            var unit = $(this).val();
            $('span.product-per-unit-' + key).text('/' + unit);
        });
        //load total price
        $(document).on('keyup', '.product-unit-price', function () {
            var key = $(this).attr('data-key');
            var unitPrice = $(this).val();
            var quantity = $('#productQuantity_' + key).val();
            if (quantity == '') {
                quantity = 0;
            }
            var totalPrice = unitPrice * quantity;
            $('#productTotalPrice_' + key).val(parseFloat(totalPrice).toFixed(2));
            return false;
        });
        $(document).on('keyup', '.product-quantity', function () {
            var key = $(this).attr('data-key');
            var quantity = $(this).val();
            var unitPrice = $('#productUnitPrice_' + key).val();
            if (unitPrice == '') {
                unitPrice = 0;
            }
            var totalPrice = unitPrice * quantity;
            $('#productTotalPrice_' + key).val(parseFloat(totalPrice).toFixed(2));
            return false;
        });

        //Function for Save Supplier Data
        $(document).on("click", "#crmOpportunityCreateSubmit", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#newOppornunityCreateForm')[0]);

            $.ajax({
                url: "<?php echo e(route('crmNewOpportunity.store')); ?>",
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
                    $('#crmOpportunityCreateSubmit').prop('disabled', true);
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(
                            window.location.replace('<?php echo e(route("crmNewOpportunity.index")); ?>'
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
                    $('#crmOpportunityCreateSubmit').prop('disabled', false);
                    App.unblockUI();
                }
            });

        });
    });


    function getProductKey() {
        var dataKey = '';
        $('.product-item').each(function () {
            dataKey = $(this).attr('data-key');
        });
        return dataKey;
    }
    //buyer under buyer contact persons
    $(document).on('change', '#buyerId', function (e) {
        $('.remove-product-row').each(function () {
            $(this).parent().parent().remove();
        });

        var productKey = getProductKey();
        var buyerId = $('#buyerId').val();
        var salesPersonId = $('#salesPersonId').val();
        var salesPersonGroupId = $('#salesPersonGroupId').val();
        var statusId = $('#statusId').val();

        $('#productWiseBrandId_' + productKey).html("<select class='form-control brand-select js-source-states brand-item' name='product["+productKey+"][brand_id]'><option value='0'><?php echo app('translator')->get('label.SELECT_BRAND_OPT'); ?></option></select>");
        $('#brandWiseGrade_' + productKey).html("<select class='form-control grade-select js-source-states'><option value='0'><?php echo app('translator')->get('label.SELECT_GRADE_OPT'); ?></option></select>");
        $('#brandWiseOrigin_' + productKey).text('');
        $('#productOrigin_' + productKey).val('');
        $('#productGsm_' + productKey).val('');
        $('#productQuantity_' + productKey).val('');
        $('#productUnitPrice_' + productKey).val('');
        $('#productTotalPrice_' + productKey).val('');
        $('#productUnit_' + productKey).val('');
        $('.product-per-unit-' + productKey).text('');

        $.ajax({
            url: "<?php echo e(URL::to('crmNewOpportunity/getBuyerContPerson')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                buyer_id: buyerId,
                sales_person_id: salesPersonId,
                sales_person_group_id: salesPersonGroupId,
                product_key: productKey,
                status_id: statusId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#address').val(res.buyerHeadOfficeAddress);
                $('#buyerContactPerson').html(res.html);
                $('#product').html(res.productView);
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });

    //product under product unit
    $(document).on('change', '.product-item', function (e) {
        var productId = $(this).val();
        var buyerId = $('#buyerId').val();
        var salesPersonId = $('#salesPersonId').val();
        var salesPersonGroupId = $('#salesPersonGroupId').val();
        var statusId = $('#statusId').val();
        var dataKey = $(this).attr('data-key');

        $('#brandWiseGrade_' + dataKey).html("<select class='form-control grade-select js-source-states'><option value='0'><?php echo app('translator')->get('label.SELECT_GRADE_OPT'); ?></option></select>");
        $('#brandWiseOrigin_' + dataKey).text('');
        $('#productOrigin_' + dataKey).val('');

        $.ajax({
            url: "<?php echo e(URL::to('crmNewOpportunity/getProductUnit')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                product_key: dataKey,
                sales_person_id: salesPersonId,
                sales_person_group_id: salesPersonGroupId,
                buyer_id: buyerId,
                status_id: statusId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#productWiseBrandId_' + dataKey).html(res.brand);
                $('#productUnit_' + dataKey).val(res.unit);
                $('.product-unit').trigger('keyup');
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });


    //brand under brand unit
    $(document).on('change', '.brand-item', function (e) {

        var dataKey = $(this).attr('data-key');
        var productId = $('#productId_' + dataKey).val();
        var brandId = $(this).val();
        $.ajax({
            url: "<?php echo e(URL::to('crmNewOpportunity/getGradeOrigin')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
                product_key: dataKey
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#brandWiseGrade_' + dataKey).html(res.html);
                $('#brandWiseOrigin_' + dataKey).text(res.originName);
                $('#productOrigin_' + dataKey).val(res.originId);
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });

    function rearrangeSL(type) {
        var sl = 0;
        $('.initial-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
        $('.new-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/crmNewOpportunity/create.blade.php ENDPATH**/ ?>