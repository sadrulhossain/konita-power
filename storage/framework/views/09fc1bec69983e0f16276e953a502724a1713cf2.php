<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <?php if(!empty($userAccessArr[17][6])): ?>
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="<?php echo e(URL::to('salesPersonToBuyer/getUnassignedBuyersPrint?view=print')); ?>"  title="<?php echo app('translator')->get('label.CLICK_HERE_TO_PRINT'); ?>">
            <i class="fa fa-print"></i>&nbsp;<?php echo app('translator')->get('label.PRINT'); ?>
        </a>
        <?php endif; ?>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.UNASSIGNED_BUYER_LIST'); ?>
        </h3>
    </div>
    <div class="modal-body">
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'modalForm')); ?>

            <?php echo Form::hidden('unassigned_list', 1); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="salesPersonId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('sales_person_id', $salesPersonArr, Request::get('sales_person_id'), ['class' => 'form-control js-source-states', 'id' => 'modalSalesPersonId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('sales_person_id')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive max-height-500 webkit-scrollbar">
                            <table class="table table-bordered table-hover relation-view-2">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                        <th class="vcenter" rowspan="2">
                                            <div class="md-checkbox has-success">
                                                <?php echo Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-buyer-check']); ?>

                                                <label for="checkAll">
                                                    <span class="inc"></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>
                                                </label>
                                                &nbsp;&nbsp;<span><?php echo app('translator')->get('label.CHECK_ALL'); ?></span>
                                            </div>
                                        </th>
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
                                    $buyerDisabled = '';
                                    if (!empty($inactiveBuyerArr) && in_array($buyer['id'], $inactiveBuyerArr)) {
                                        $statusColor = 'red-soft';
                                        $statusTitle = __('label.INACTIVE');
                                        $buyerDisabled = 'disabled';
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                        <td class="vcenter width-120">
                                            <div class="md-checkbox has-success width-inherit">
                                                <?php echo Form::checkbox('buyer['.$buyer['id'].']', $buyer['id'], false, ['id' => $buyer['id'], 'data-id'=> $buyer['id'],'class'=> 'md-check buyer-check',$buyerDisabled]); ?>

                                                <label for="<?php echo $buyer['id']; ?>">
                                                    <span class="inc tooltips" data-placement="right" title=""></span>
                                                    <span class="check mark-caheck tooltips" data-placement="" title=""></span>
                                                    <span class="box mark-caheck tooltips" data-placement="" title=""></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center vcenter">
                                            <?php if(!empty($buyer['logo']) && File::exists('public/uploads/buyer/' . $buyer['logo'])): ?>
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
                                            <?php echo app('translator')->get('label.NO_UNASSIGNED_BUYER_FOUND'); ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <?php if(!empty($buyerArr)): ?>
                            <?php if(!empty($userAccessArr[17][7])): ?>
                            <button class="btn btn-circle green modal-submit" id="saveSalesPersonToBuyerRel" type="button">
                                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.ASSIGN_BUYER'); ?>
                            </button>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[17][1])): ?>
                            <a href="<?php echo e(URL::to('/salesPersonToBuyer')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

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

    $(".buyer-check").on("click", function () {
        if ($('.buyer-check:checked').length == $('.buyer-check').length) {
            $('.all-buyer-check').prop("checked", true);
        } else {
            $('.all-buyer-check').prop("checked", false);
        }
    });
    $(".all-buyer-check").on("click", function () {
        if ($(this).prop('checked')) {
            $('.buyer-check').prop("checked", true);
        } else {
            $('.buyer-check').prop("checked", false);
        }

    });
    if ($('.buyer-check:checked').length == $('.buyer-check').length) {
        $('.all-buyer-check').prop("checked", true);
    } else {
        $('.all-buyer-check').prop("checked", false);
    }
});
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/salesPersonToBuyer/showUnassignedBuyers.blade.php ENDPATH**/ ?>