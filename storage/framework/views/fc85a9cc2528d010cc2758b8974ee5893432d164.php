<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title text-center bold">
            <?php echo app('translator')->get('label.RELATED_SALES_PERSON_LIST'); ?>
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo app('translator')->get('label.BUYER'); ?>: <strong><?php echo $buyerInfo->name ?? __('label.N_A'); ?></strong>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover  table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.EMPLOYEE_ID'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.DEPARTMENT'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.BRANCH'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$relatedSalesPersonInfoArr->isEmpty()): ?>
                            <?php $sl = 0; ?>
                            <?php $__currentLoopData = $relatedSalesPersonInfoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                <td class="text-center vcenter" width="50px">
                                    <?php if(!empty($item->photo) && File::exists('public/uploads/user/' . $item->photo)): ?>
                                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($item->photo); ?>" alt="<?php echo e($item->name); ?>"/>
                                    <?php else: ?>
                                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($item->name); ?>"/>
                                    <?php endif; ?>
                                </td>
                                <td class="vcenter"><?php echo $item->employee_id ?? ''; ?></td>
                                <td class="vcenter">
                                    <?php echo $item->name ?? ''; ?>

                                    <?php if(array_key_exists($item->sales_person_id, $activeSalesPersonArr)): ?>
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="<?php echo e(__('label.ACTIVE')); ?>">

                                    </button>
                                    <?php endif; ?>
                                </td>
                                <td class="vcenter"><?php echo $item->designation ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->department ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->branch ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->phone ?? ''; ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td class="vcenter text-danger" colspan="8"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

});
</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/report/idlyEngagedBuyer/showRelatedSalesPersonList.blade.php ENDPATH**/ ?>