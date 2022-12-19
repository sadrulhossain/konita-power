<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('label.BUYER'); ?></th>
                        <th class="text-center vcenter" colspan="9"><?php echo app('translator')->get('label.SHIPMENT'); ?></th>
                    </tr>
                    <tr class="active">
                        <th class="vcenter text-center" colspan="2"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                        <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                        <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                        <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_QUANTITY'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.RECEIVED_QUANTITY'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.RECEIVED_TOTAL_PRICE'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($inquiryArr)): ?>
                    <?php $sl = 0; ?>
                    <?php $__currentLoopData = $inquiryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId => $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="vcenter text-center" rowspan="<?php echo e($inquiryRowSpan[$inquiryId]); ?>"><?php echo ++$sl; ?></td>
                        <td class="vcenter" rowspan="<?php echo e($inquiryRowSpan[$inquiryId]); ?>"><?php echo $inquiry['order_no']; ?></td>
                        <td class="vcenter" rowspan="<?php echo e($inquiryRowSpan[$inquiryId]); ?>"><?php echo $inquiry['buyer_name']; ?></td>

                        <?php if(!empty($deliveryArr[$inquiryId])): ?>
                        <?php $i = 0; ?>
                        <?php $__currentLoopData = $deliveryArr[$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId => $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>

                        <td class="vcenter" rowspan="<?php echo e($deliveryRowSpan[$inquiryId][$deliveryId]); ?>">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('delivery['.$inquiryId.']['.$deliveryId.']', 1,false, ['id' => 'delivery_'.$inquiryId.'_'.$deliveryId, 'class'=> 'md-check delivery']); ?>

                                <label for="<?php echo 'delivery_'.$inquiryId.'_'.$deliveryId; ?>">
                                    <span class="inc checkbox-text-center"></span>
                                    <span class="check mark-caheck checkbox-text-center"></span>
                                    <span class="box mark-caheck checkbox-text-center"></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter text-center" rowspan="<?php echo e($deliveryRowSpan[$inquiryId][$deliveryId]); ?>"><?php echo $delivery['bl_no']; ?></td>
                        
                        <?php if(!empty($deliveryDetailsArr[$inquiryId][$deliveryId])): ?>
                        <?php $j = 0; ?>
                        <?php $__currentLoopData = $deliveryDetailsArr[$inquiryId][$deliveryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryDetailsId => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        
                        <td class="vcenter"><?php echo $details['product_name']; ?></td>
                        <td class="vcenter"><?php echo $details['brand_name']; ?></td>
                        <td class="vcenter"><?php echo $details['grade_name']; ?></td>
                        <td class="vcenter text-right"><?php echo Helper::numberFormat2Digit($details['total_quantity']).$details['unit']; ?></td>
                        <td class="vcenter text-right"><?php echo Helper::numberFormat2Digit($details['shipment_quantity']).$details['unit']; ?></td>
                        <td class="vcenter text-right"><?php echo '$'.Helper::numberFormat2Digit($details['unit_price']).$details['per_unit']; ?></td>
                        <td class="vcenter text-right"><?php echo '$'.Helper::numberFormat2Digit($details['total_price']); ?></td>
                        
                        
                        <?php
                        if ($j > 0) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <?php
                        if ($i > 0) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td class="text-success" colspan="50"><?php echo app('translator')->get('label.NO_UNPAID_SHIPMENT_FOUND'); ?></td>
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
            <button class="btn green btn-submit" id="setPyamentStatus" type="button">
                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
            </button>
            <a href="<?php echo e(URL::to('/paymentStatus')); ?>" class="btn btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>

        </div>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/paymentStatus/showPaymentStatus.blade.php ENDPATH**/ ?>