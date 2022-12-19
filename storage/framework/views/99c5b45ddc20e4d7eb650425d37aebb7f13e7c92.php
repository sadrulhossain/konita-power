<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.SUPPLIER_LIST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[13][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('supplier/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_SUPPLIER'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    <?php echo Form::open(array('group' => 'form', 'url' => 'supplier/filter','class' => 'form-horizontal')); ?>

                    <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="search"><?php echo app('translator')->get('label.NAME'); ?></label>
                            <div class="col-md-9">
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
                            <label class="control-label col-md-4" for="supplierClassifiationId"><?php echo app('translator')->get('label.CLASSIFICATION'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::select('supplier_classification_id',  $supplierClassificationArr, Request::get('supplier_classification_id'), ['class' => 'form-control js-source-states','id'=>'supplierClassifiationId']); ?>

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


                    <div class="col-md-1">
                        <div class="form  text-right">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                            </button>
                        </div>
                    </div>
                    <?php echo Form::close(); ?>

                    <!-- End Filter -->
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.LOGO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.CLASSIFICATION'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.CODE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.ADDRESS'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.COUNTRY'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.SIGN_OFF_DATE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.FSC_CERTIFIED'); ?></th>
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
                                <img alt="<?php echo e($target->name); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/supplier/<?php echo e($target->logo); ?>" width="40" height="40"/>
                                <?php else: ?>
                                <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="40" height="40"/>
                                <?php endif; ?>
                            </td>
                            <td class="vcenter"><?php echo e($target->supplier_classification); ?></td>
                            <td class="vcenter">
                                <?php if(!empty($userAccessArr[13][5])): ?>
                                <a class="tooltips" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_PROFILE'); ?>"
                                   href="<?php echo e(URL::to('supplier/' . $target->id . '/profile'.Helper::queryPageStr($qpArr))); ?>">
                                    <?php echo e($target->name); ?>

                                </a>
                                <?php else: ?>
                                <?php echo e($target->name); ?>

                                <?php endif; ?>
                            </td>
                            <td class="vcenter"><?php echo e($target->code); ?></td>
                            <td class="vcenter"><?php echo e($target->address); ?></td>
                            <td class="vcenter"><?php echo e($target->country); ?></td>
                            <td class="vcenter"><?php echo e(isset($target->sign_off_date) ? Helper::formatDate($target->sign_off_date) : ''); ?></td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <?php if(!empty($target->fsc_certified)): ?>
                                    <span class="label label-info fsc-padding">
                                        <?php echo app('translator')->get('label.YES'); ?>
                                    </span>
                                    <?php if(isset($target->fsc_attachment)): ?>
                                    <label>&nbsp;</label>
                                    <a href="<?php echo e(URL::to('public/uploads/supplierFscCertificate/'.$target->fsc_attachment)); ?>" class="btn fsc-padding purple red-stripe tooltips" title="<?php echo app('translator')->get('label.CLICK_HERE_TO_VIEW_FSC_CERTIFICATE'); ?>" target="_blank">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </a>

                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="label label-warning purple-stripe">
                                        <?php echo app('translator')->get('label.NO'); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center vcenter">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    <?php if(!empty($userAccessArr[13][3])): ?>
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="<?php echo e(URL::to('supplier/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[13][4])): ?>
                                    <?php echo e(Form::open(array('url' => 'supplier/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[13][5])): ?>
                                    <button class="btn btn-xs btn-info tooltips vcenter" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_CONTACT_PERSON_DETAILS'); ?>" data-supplier-id="<?php echo e($target->id); ?>">
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="vcenter"><?php echo app('translator')->get('label.NO_SUPPLIER_FOUND'); ?></td>
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
<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var supplierId = $(this).data('supplier-id');
            $.ajax({
                url: "<?php echo e(route('supplier.detailsOfContactPerson')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });
</script>    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/supplier/index.blade.php ENDPATH**/ ?>