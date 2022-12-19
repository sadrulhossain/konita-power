<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>">
            <?php echo app('translator')->get('label.CLOSE'); ?>
        </button>
        <?php if(!empty($userAccessArr[17][6])): ?>
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="<?php echo e(URL::to('salesPersonToBuyer/getRelatedBuyersPrint/'.$request->sales_person_id.'?view=print')); ?>"  title="<?php echo app('translator')->get('label.CLICK_HERE_TO_PRINT'); ?>">
            <i class="fa fa-print"></i>&nbsp;<?php echo app('translator')->get('label.PRINT'); ?>
        </a>
        <?php endif; ?>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.RELATED_BUYER_LIST'); ?>
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-6">
                <?php echo app('translator')->get('label.SALES_PERSON'); ?>: <strong><?php echo $salesPerson->name ?? ''; ?></strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class=" text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.LOGO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BUYER_NAME'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CODE'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BUYER_CATEGORY'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.HEAD_OFFICE_ADDRESS'); ?></th>
                                <th class="vcenter text-center" colspan="2"><?php echo app('translator')->get('label.CONTACT_PERSON'); ?></th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            <?php if(!empty($buyerArr)): ?>
                            <?php $sl = 0 ?>
                            <?php $__currentLoopData = $buyerArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $statusColor = 'green-seagreen';
                            $statusTitle = __('label.ACTIVE');
                            if (!empty($inactiveBuyerArr) && in_array($buyer['id'], $inactiveBuyerArr)) {
                                $statusColor = 'red-soft';
                                $statusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                <td class="text-center vcenter">
                                    <?php if(!empty($buyer['logo'])): ?>
                                    <img alt="<?php echo e($buyer['name']); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/buyer/<?php echo e($buyer['logo']); ?>" width="40" height="40"/>
                                    <?php else: ?>
                                    <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="40" height="40"/>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($buyer['name'] ?? ''); ?>

                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle <?php echo e($statusColor); ?> tooltips" title="<?php echo e($statusTitle); ?>">
                                    </button>
                                </td>

                                <td class="vcenter"><?php echo $buyer['code'] ?? ''; ?></td>
                                <td class="vcenter"><?php echo $buyer['buyer_category_name'] ?? ''; ?></td>
                                <td class="vcenter"><?php echo $buyer['head_office_address'] ?? ''; ?></td>
                                <td class="vcenter"><?php echo $contactArr[$buyer['id']]['name'] ?? ''; ?></td>

                                <?php if(is_array($contactArr[$buyer['id']]['phone'])): ?>
                                <td class="vcenter">
                                    <?php
                                    $lastValue = end($contactArr[$buyer['id']]['phone']);
                                    ?>
                                    <?php $__currentLoopData = $contactArr[$buyer['id']]['phone']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($contact); ?>

                                    <?php if($lastValue !=$contact): ?>
                                    <span>,</span>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <?php else: ?>
                                <td class="vcenter"><?php echo $contactArr[$buyer['id']]['phone'] ?? ''; ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="20" class="text-danger">
                                    <?php echo app('translator')->get('label.NO_RELATED_BUYER_FOUND_FOR_THIS_SALES_PERSON'); ?>
                                </td>
                            </tr>
                            <?php endif; ?>      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $('.relation-view-2').tableHeadFixer();
});
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/salesPersonToBuyer/showRelatedBuyers.blade.php ENDPATH**/ ?>