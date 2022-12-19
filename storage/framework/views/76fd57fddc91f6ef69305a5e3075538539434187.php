<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="text-right">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        </div>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.COMMISSION_DETAILS'); ?>
        </h3>
    </div>
    <div class="modal-body">
        <!--Endof_BL_history data-->
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.KONITA_CMSN'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.BUYER_COMMISSION'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.REBATE_COMMISSION'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($targetArr)): ?>
                                <?php
                                $sl = 0;
                                ?>
                                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId => $orderNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $rowsapn = !empty($rowspanArr[$inquiryId]['order_row_span']) ? $rowspanArr[$inquiryId]['order_row_span'] : 1;
                                ?>
                                <tr>
                                    <td class="vcenter text-center" rowspan="<?php echo e($rowsapn); ?>"><?php echo e(++$sl); ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowsapn); ?>"><?php echo e(!empty($orderNo['order_no']) ? $orderNo['order_no'] : ''); ?></td>
                                    <?php
                                    $i = 0;
                                    ?>
                                    <?php if(!empty($inqDetailsArr[$inquiryId])): ?>

                                    <?php $__currentLoopData = $inqDetailsArr[$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryDetailsId => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if($i > 0){
                                        echo '<tr>';
                                    }
                                    $konitaComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                                    $principalComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['principle_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['principle_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['principle_cmsn']) ? $prevComsnArr[$inquiryId][0]['principle_cmsn'] : 0));
                                    $salesPersonComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][0]['sales_person_cmsn'] : 0));
                                    $buyerComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][0]['buyer_cmsn'] : 0));
                                    $rebateComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][0]['rebate_cmsn'] : 0));
                                    ?>
                                    <td class="vcenter"><?php echo e(!empty($item['product_name']) ? $item['product_name'] : ''); ?></td>
                                    <td class="vcenter"><?php echo e(!empty($item['brand_name']) ? $item['brand_name'] : ''); ?></td>
                                    <td class="vcenter"><?php echo e(!empty($item['grade_name']) ? $item['grade_name'] : ''); ?></td>
                                    <td class="vcenter"><?php echo e(!empty($item['gsm']) ? $item['gsm'] : ''); ?></td>
                                    <td class="vcenter text-right">$<?php echo e($konitaComsn); ?></td>
                                    <td class="vcenter text-right">$<?php echo e($principalComsn); ?></td>
                                    <td class="vcenter text-right">$<?php echo e($salesPersonComsn); ?></td>
                                    <td class="vcenter text-right">$<?php echo e($buyerComsn); ?></td>
                                    <td class="vcenter text-right">$<?php echo e($rebateComsn); ?></td>
                                    <?php
                                    $i++;
                                    ?>
                                </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<!-- END:: Contact Person Information--><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/showCmsnDetailsModal.blade.php ENDPATH**/ ?>