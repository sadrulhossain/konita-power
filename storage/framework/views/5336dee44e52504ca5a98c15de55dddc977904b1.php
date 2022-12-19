<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.BUYER_LIST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[18][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('buyer/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_BUYER'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">

            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'buyer/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search"><?php echo app('translator')->get('label.NAME'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']); ?> 
                            <datalist id="search">
                                <?php if(!empty($nameArr)): ?>
                                <?php $__currentLoopData = $nameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($name->name); ?>"></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerCatId"><?php echo app('translator')->get('label.CLASSIFICATION'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('buyer_category_id',  $buyerCatArr, Request::get('buyer_category_id'), ['class' => 'form-control js-source-states','id'=>'buyerCatId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="countryId"><?php echo app('translator')->get('label.COUNTRY'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('country_id',  $countryList, Request::get('country_id'), ['class' => 'form-control js-source-states','id'=>'countryId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="divisionId"><?php echo app('translator')->get('label.DIVISION'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('division_id',  $divisionList, Request::get('division_id'), ['class' => 'form-control js-source-states','id'=>'divisionId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="salesStatusId"><?php echo app('translator')->get('label.SALES_STATUS'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('sales_status_id', $salesStatusList, Request::get('sales_status_id'), ['class' => 'form-control js-source-states', 'id' => 'salesStatusId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('sales_status_id')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.LOGO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BUYER_CATEGORY'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.COUNTRY'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.DIVISION'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.CODE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.HEAD_OFFICE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PRIMARY_FACTORY'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="td-actions text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="vcenter"><?php echo e(++$sl); ?></td>
                            <td class="vcenter">
                                <?php if(!empty($target->logo)): ?>
                                <img alt="<?php echo e($target->name); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/buyer/<?php echo e($target->logo); ?>" width="40" height="40"/>
                                <?php else: ?>
                                <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="40" height="40"/>
                                <?php endif; ?>
                                <?php if(!empty($target->show_all_brands)): ?>
                                <i class="fa fa-asterisk font-red tooltips" title="<?php echo app('translator')->get('label.ALLOWED_TO_VIEW_ALL_BRANDS'); ?>"></i>
                                <?php endif; ?>
                                
                            </td>
                            <td class="vcenter"><?php echo e($target->buyer_category); ?></td>
                            <td class="vcenter">
                                <?php if(!empty($userAccessArr[18][5])): ?>
                                <a class="tooltips" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_PROFILE'); ?>"
                                   href="<?php echo e(URL::to('buyer/' . $target->id . '/profile'.Helper::queryPageStr($qpArr))); ?>">
                                    <?php echo e($target->name); ?>

                                </a>
                                <?php else: ?>
                                <?php echo e($target->name); ?>

                                <?php endif; ?>
                            </td>
                            <td class="vcenter"><?php echo e($target->country); ?></td>
                            <td class="vcenter"><?php echo e($target->division); ?></td>
                            <td class="vcenter"><?php echo e($target->code); ?></td>
                            <td class="vcenter"><?php echo e($target->head_office_address); ?></td>
                            <td class="vcenter"><?php echo e($factoryAddressList[$target->id] ?? ''); ?></td>
                            <td class="text-center vcenter">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <?php if(!empty($userAccessArr[18][3])): ?>
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="<?php echo e(URL::to('buyer/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[18][4])): ?>
                                    <?php echo e(Form::open(array('url' => 'buyer/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[18][5])): ?>
                                    <button class="btn btn-xs btn-info tooltips vcenter" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_CONTACT_PERSON_DETAILS'); ?>" data-buyer-id = <?php echo e($target->id); ?>>
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    <?php endif; ?>

                                    <?php if(!empty($userAccessArr[18][1])): ?>
                                    <button class="btn btn-xs purple tooltips vcenter" href="#mapView" id="mapModal"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_MAP_ON_ADDRESS'); ?>" data-buyer-id = <?php echo e($target->id); ?>>
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[18][8])): ?>
                                    <?php
                                    $disabled = 'cursor-default';
                                    $href = '';
                                    $btnName = '';
                                    $btnType = 'type="button"';
                                    $btnColor = 'grey-mint';
                                    $btnLabel = __('label.NO_PRODUCT_ASSIGNED_YET');
                                    if (!empty($hasRelatedProductArr)) {
                                        if (array_key_exists($target->id, $hasRelatedProductArr)) {
                                            if (sizeOf($hasMachineArr) > 0) {
                                                $disabled = '';
                                                $btnName = 'set-machine-type';
                                                $href = '#modalSetMachineType';
                                                $btnType = '';
                                                $btnColor = 'green-sharp';
                                                $btnLabel = __('label.CLICK_TO_SET_MACHINE_TYPE');
                                            } else {
                                                $btnLabel = __('label.NO_PRODUCT_HAS_MACHINE');
                                            }
                                        }
                                    }
                                    ?>
                                    <button <?php echo e($btnType); ?> class="btn btn-xs <?php echo e($btnColor); ?> <?php echo e($disabled); ?> tooltips <?php echo e($btnName); ?> vcenter" href="<?php echo e($href); ?>" id=""  data-toggle="modal" title="<?php echo e($btnLabel); ?>" data-buyer-id="<?php echo e($target->id); ?>">
                                        <i class="fa fa-wrench"></i>
                                    </button>
                                    <a class="btn btn-xs btn-warning tooltips vcenter " title="<?php echo app('translator')->get('label.CLICK_HERE_TO_MAKE_BUYER_ANALYTICS'); ?>"
                                       href="<?php echo e(URL::to('buyer/' . $target->id . '/manageBuyer'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if(empty($salesPersonToBuyerCountList[$target->id])): ?>
                                    <?php if(!empty($userAccessArr[17][7])): ?>
                                    <button class="btn btn-xs blue-hoki tooltips vcenter assign-sales-person assign-sales-person-<?php echo e($target->id); ?>"  
                                            title="<?php echo app('translator')->get('label.CLICK_TO_ASSIGN_SALES_PERSON'); ?>" href="#modalAssignSalesPerson" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="vcenter"><?php echo app('translator')->get('label.NO_BUYER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="contactPersonDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetailsContactPerson">
        </div>
    </div>
</div>

<div class="modal fade" id="mapView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="mapBlock">

        </div>
    </div>
</div>

<div class="modal fade" id="modalSetMachineType" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetMachineType">

        </div>
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
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var buyerId = $(this).data('buyer-id');
            $.ajax({
                url: "<?php echo e(route('buyer.detailsOfContactPerson')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
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
                }
            }); //ajax
        });


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

        //Show Google Map based on Address
        $(document).on("click", "#mapModal", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(route('buyer.locationView')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#mapBlock").html(res.html);
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
                }
            }); //ajax

        }); // EOF -- Click on Modal Button

        //Show Google Map based on Address
        $(document).on("click", ".set-machine-type", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('buyer/getMachineType')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showSetMachineType").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetMachineType").html(res.html);
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
                }
            }); //ajax

        }); // EOF -- Click on Modal Button

    });
</script>    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/buyer/index.blade.php ENDPATH**/ ?>