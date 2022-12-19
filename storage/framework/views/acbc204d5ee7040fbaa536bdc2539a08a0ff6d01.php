<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th"></i><?php echo app('translator')->get('label.BUYER_ENGAGEMENT_REPORT_IDLE'); ?>
            </div>
            <div class="actions">
                <span class="text-right">
                    <?php if(Request::get('generate') == 'true'): ?>
                    <?php if(!empty($userAccessArr[73][6])): ?>
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="<?php echo e(URL::to($request->fullUrl() . '&view=print')); ?>"  title="<?php echo app('translator')->get('label.PRINT'); ?>">
                        <i class="fa fa-print"></i>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'idlyEngagedBuyerReport/filter','class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-5"  for="idleFor"><?php echo app('translator')->get('label.IDLE_FOR_ATLEAST'); ?><span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <div class="input-group bootstrap-touchspin">
                                <?php echo Form::text('idle_for', Request::get('idle_for'), ['id'=> 'idleFor', 'class' => 'form-control text-right integer-only','autocomplete' => 'off']); ?>

                                <span class="input-group-addon bootstrap-touchspin-postfix bold"><?php echo app('translator')->get('label.MONTH_S'); ?></span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('idle_for')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <div class="row margin-top-10">
                <?php if(Request::get('generate') == 'true'): ?>
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.IDLE_FOR_ATLEAST')); ?> : <strong><?php echo e(Request::get('idle_for') . ' ' . __('label.MONTH_S')); ?> |</strong> 
                            <?php echo e(__('label.TOTAL_NO_OF_BUYERS')); ?> : <strong><?php echo e(!empty($buyerInfoArr) ? count($buyerInfoArr) : 0); ?> </strong> 
                        </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="max-height-500 tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.LOGO'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.BUYER_NAME'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.COUNTRY'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.DIVISION'); ?></th>
                                    <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.PRIMARY_CONTACT_PERSON'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.STATUS'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.IDLE_TIME'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.LATEST_BUYER_FOLLOWUP'); ?></th>
                                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.NO_OF_RELATED_SALES_PERSONS'); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($buyerInfoArr)): ?>
                                <?php
                                $sl = 0;
                                ?>
                                <?php $__currentLoopData = $buyerInfoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                    <td class="text-center vcenter" width="40px">
                                        <?php if(!empty($item['logo']) && File::exists('public/uploads/buyer/' . $item['logo'])): ?>
                                        <img alt="<?php echo e($item['name']); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/buyer/<?php echo e($item['logo']); ?>" width="40" height="40"/>
                                        <?php else: ?>
                                        <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="40" height="40"/>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter">
                                        <?php if(!empty($userAccessArr[73][5])): ?>
                                        <a class="tooltips" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_PROFILE'); ?>"
                                           href="<?php echo e(URL::to('idlyEngagedBuyerReport/' . $item['id'] . '/profile' . Helper::getUrlRequestText(URL::to($request->fullUrl())) )); ?>">
                                            <?php echo e($item['name']); ?>

                                        </a>
                                        <?php else: ?>
                                        <?php echo e($item['name']); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter"><?php echo $item['country_name'] ?? ''; ?></td>
                                    <td class="vcenter"><?php echo $item['division_name'] ?? ''; ?></td>
                                    <td class="vcenter"><?php echo $contactArr[$item['id']]['name'] ?? ''; ?></td>

                                    <?php if(is_array($contactArr[$item['id']]['phone'])): ?>
                                    <td class="vcenter">
                                        <?php
                                        $lastValue = end($contactArr[$item['id']]['phone']);
                                        ?>
                                        <?php $__currentLoopData = $contactArr[$item['id']]['phone']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($contact); ?>

                                        <?php if($lastValue !=$contact): ?>
                                        <span>,</span>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <?php else: ?>
                                    <td class="vcenter"><?php echo $contactArr[$item['id']]['phone'] ?? ''; ?></td>
                                    <?php endif; ?>
                                    <td class="text-center vcenter">
                                        <?php if($item['status'] == '1'): ?>
                                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter text-center"><?php echo $idleTimeArr[$item['id']] ?? ''; ?></td>
                                    <td class="text-center vcenter">
                                        <?php if(array_key_exists($item['id'], $latestFollowupArr)): ?>
                                        <?php if($latestFollowupArr[$item['id']]['status'] == '1'): ?>
                                        <span class="label label-sm label-yellow"><?php echo app('translator')->get('label.NORMAL'); ?></span>
                                        <?php elseif($latestFollowupArr[$item['id']]['status'] == '2'): ?>
                                        <span class="label label-sm label-green-seagreen"><?php echo app('translator')->get('label.HAPPY'); ?></span>
                                        <?php elseif($latestFollowupArr[$item['id']]['status'] == '3'): ?>
                                        <span class="label label-sm label-red-soft"><?php echo app('translator')->get('label.UNHAPPY'); ?></span>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <span class="label label-sm label-gray-mint"><?php echo app('translator')->get('label.NO_FOLLOWUP_YET'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center vcenter">
                                        <?php if(!empty($salesPersonToBuyerCountList[$item['id']])): ?>
                                        <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list"  
                                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST'); ?>" href="#modalRelatedSalesPersonList" data-id="<?php echo $item['id']; ?>" data-toggle="modal">
                                            <?php echo $salesPersonToBuyerCountList[$item['id']]; ?>

                                        </button>
                                        <?php else: ?>
                                        <span class="label label-sm label-gray-mint sales-person-count-<?php echo e($item['id']); ?> tooltips" title="<?php echo app('translator')->get('label.NO_RELATED_SALES_PERSON'); ?>"><?php echo 0; ?></span>
                                        <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list sales-person-list sales-person-list-<?php echo e($item['id']); ?>"  
                                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST'); ?>" href="#modalRelatedSalesPersonList" data-id="<?php echo $item['id']; ?>" data-toggle="modal">

                                        </button>
                                        <?php if(!empty($userAccessArr[17][7])): ?>
                                        <button class="btn btn-xs blue-hoki tooltips vcenter assign-sales-person assign-sales-person-<?php echo e($item['id']); ?>"  
                                                title="<?php echo app('translator')->get('label.CLICK_TO_ASSIGN_SALES_PERSON'); ?>" href="#modalAssignSalesPerson" data-id="<?php echo $item['id']; ?>" data-toggle="modal">
                                            <i class="fa fa-share"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td class="vcenter text-danger" colspan="<?php echo 11; ?>"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>	
    </div>
</div>

<!-- Modal start -->
<!--related sales person list-->
<div class="modal fade" id="modalRelatedSalesPersonList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSalesPersonList"></div>
    </div>
</div>

<!--assign sales person-->
<div class="modal fade" id="modalAssignSalesPerson" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo Form::open(array('group' => 'form', 'url' => '', 'id' => 'setAssignSalesPersonForm', 'class' => 'form-horizontal','files' => true)); ?>

        <div id="showAssignSalesPerson"></div>
        <?php echo Form::close(); ?>

    </div>
</div>

<!--in business brand list-->
<div class="modal fade" id="modalInBusinessBrandList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInBusinessBrandList"></div>
    </div>
</div>

<!-- Modal end -->

<script type="text/javascript">
    $(function () {
        //table header fix
        $("#fixTable").tableHeadFixer();
        //        $('.sample').floatingScrollbar();
        $(".sales-person-list").hide();

        //country wise division
        $(document).on('change', '#countryId', function () {
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '<?php echo e(URL::to("idlyEngagedBuyerReport/getDivision/")); ?>',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#divisionId').html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });


        $(document).on("change", "#productCategoryId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
//        alert(productCategoryId);
//        return false;
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getProductList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#productId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#productId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
            if (productId == '0') {
                $('#brandId').html("<option class='form-control js-source-states' value='0'><?php echo app('translator')->get('label.SELECT_BRAND_OPT'); ?></option>");
                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'><?php echo app('translator')->get('label.SELECT_MACHINE_TYPE_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getBrandList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#brandId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#brandId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
            if (productId == '0' || brandId == '0') {
                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'><?php echo app('translator')->get('label.SELECT_MACHINE_TYPE_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getMachineTypeList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#machineTypeId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#buyerCategoryId, #machineTypeId, #businessStatusId, #salesStatusId, #divisionId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
//            if (productId == '0' || brandId == '0') {
//                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'><?php echo app('translator')->get('label.SELECT_MACHINE_TYPE_OPT'); ?></option>");
//                return false;
//            }
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getBuyerSearchList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#buyerId").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //related sales person list modal
        $(".related-sales-person-list").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getRelatedSalesPersonList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showRelatedSalesPersonList").html('');
                },
                success: function (res) {
                    $("#showRelatedSalesPersonList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //assign sales person modal
        $(".assign-sales-person").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/salesPersonToBuyer/getAssignSalesPerson')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showAssignSalesPerson").html('');
                },
                success: function (res) {
                    $("#showAssignSalesPerson").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set assign sales person
        $(document).on('click', '#setAssignSalesPerson', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Submit",
                cancelButtonText: "No, Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
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

                    // data
                    var formData = new FormData($("#setAssignSalesPersonForm")[0]);
                    $.ajax({
                        url: "<?php echo e(URL::to('/salesPersonToBuyer/setAssignSalesPerson')); ?>",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $("#setAssignSalesPerson").prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            $("#modalAssignSalesPerson").modal('hide');
                            var buyer = res.buyer;
                            var count = res.count;
                            $(".assign-sales-person-" + buyer).hide();
                            $(".sales-person-count-" + buyer).hide();
                            $(".sales-person-list-" + buyer).html(count);
                            $(".sales-person-list-" + buyer).show();

                            App.unblockUI();
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
                            $("#setAssignSalesPerson").prop('disabled', false);
                        }
                    }); //ajax
                }
            });
        });

        //in business brand list modal
        $(".in-business-brand-list").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            var brandId = $(this).attr("data-brand-id");
            $.ajax({
                url: "<?php echo e(URL::to('/idlyEngagedBuyerReport/getInBusinessBrandList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId,
                    brand_id: brandId,
                },
                beforeSend: function () {
                    $("#showInBusinessBrandList").html('');
                },
                success: function (res) {
                    $("#showInBusinessBrandList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });



    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/report/idlyEngagedBuyer/index.blade.php ENDPATH**/ ?>