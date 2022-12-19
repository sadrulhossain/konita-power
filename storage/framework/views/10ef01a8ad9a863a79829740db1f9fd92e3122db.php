<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.RECEIVE_DETAILS'); ?>
        </h3>
    </div>
    <?php echo Form::open(array('group' => 'form', 'url' => '', 'id' =>'setReceiveForm', 'class' => 'form-horizontal')); ?>

    <?php echo Form::hidden('supplier_id', $request->supplier_id); ?>

    <?php echo Form::hidden('receive', $receive); ?>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <?php echo app('translator')->get('label.SUPPLIER'); ?>:&nbsp;<strong> <?php echo $supplier->name ?? __('label.N_A'); ?></strong>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.INVOICE_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                                <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.COLLECTION_AMOUNT'); ?></th>
                                <th class="vcenter text-center" colspan="4"><?php echo app('translator')->get('label.COMMISSION'); ?></th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.KONITA_CMSN'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.BUYER_COMMISSION'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.REBATE_COMMISSION'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($receiveList)): ?>
                            <?php $sl = 0; ?>
                            <?php $__currentLoopData = $receiveList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceId => $invoiceDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo ++$sl; ?></td>
                                <td class="vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo $invoiceDetails['invoice_no']; ?></td>
                                
                                <?php echo Form::hidden('billed['.$invoiceId.']', $request->billed[$invoiceId]); ?>

                                
                                <?php if(!empty($receiveList2[$invoiceId])): ?>
                                <?php $i = 0; ?>
                                <?php $__currentLoopData = $receiveList2[$invoiceId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId => $inquiryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter" rowspan="<?php echo e($inquiryRowSpan[$invoiceId][$inquiryId]); ?>"><?php echo $inquiryDetails['order_no']; ?></td>

                                <?php if(!empty($receiveList3[$invoiceId][$inquiryId])): ?>
                                <?php $j = 0; ?>
                                <?php $__currentLoopData = $receiveList3[$invoiceId][$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId => $deliveryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"><?php echo $deliveryDetails['bl_no']; ?></td>
                                <td class="text-center vcenter">
                                    <div class="input-group bootstrap-touchspin">
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                        <?php echo Form::text('collection_amount['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', !empty($deliveryDetails['collection_amount']) ? Helper::numberFormat2Digit($deliveryDetails['collection_amount']) : null, ['id'=> 'collectionAmount_'.$invoiceId.'_'.$inquiryId.'_'.$deliveryId, 'style' => ' min-width: 100px', 'class' => 'form-control integer-decimal-only text-input-width text-right collection-amount', 'readonly', 'autocomplete' => 'off']); ?>

                                    </div>
                                </td>

                                <td class="vcenter text-right"><?php echo !empty($deliveryDetails['company_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['company_commission']) : __('label.N_A'); ?></td>
                                <td class="vcenter text-right"><?php echo !empty($deliveryDetails['sales_person_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['sales_person_commission']) : __('label.N_A'); ?></td>
                                <td class="vcenter text-right"><?php echo !empty($deliveryDetails['buyer_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['buyer_commission']) : __('label.N_A'); ?></td>
                                <td class="vcenter text-right"><?php echo !empty($deliveryDetails['rebate_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['rebate_commission']) : __('label.N_A'); ?></td>
                                
                                
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
                                <td class="text-success" colspan="20"><?php echo app('translator')->get('label.PAYMENT_OF_ALL_INVOICES_HAS_BEEN_COLLECTED'); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>

    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="setReceive"><?php echo app('translator')->get('label.CONFIRM_SUBMIT'); ?></button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

  
<?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/receive/showReceivePreview.blade.php ENDPATH**/ ?>