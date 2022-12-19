<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title"><?php echo app('translator')->get('label.ACCOMPLISHED_ORDER_DETAILS'); ?></h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>

        </div>
    </div>
    <div class="modal-body">

        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.ORDER_INFORMATION'); ?></strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.ORDER_NO'); ?></td>
                                <td width="50%"><?php echo !empty($orderInfo->order_no)?$orderInfo->order_no:''; ?></td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BUYER'); ?></td>
                                <td width="50%"><?php echo !empty($orderInfo->buyer_name)?$orderInfo->buyer_name:''; ?></td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SALES_PERSON'); ?></td>
                                <td width="50%"><?php echo !empty($orderInfo->salesPersonName)?$orderInfo->salesPersonName:''; ?></td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SUPPLIER'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($orderInfo->supplier_name)?$orderInfo->supplier_name:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                <td width="50%">
                                    <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACCOMPLISHED'); ?></span>
                                </td>
                            </tr>     
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($orderInfo->purchase_order_no)?$orderInfo->purchase_order_no:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PO_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($orderInfo->po_date)?Helper::formatDate($orderInfo->po_date):''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.CREATION_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($orderInfo->creation_date)?Helper::formatDate($orderInfo->creation_date):''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PI_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($orderInfo->pi_date)?Helper::formatDate($orderInfo->pi_date):''; ?>

                                </td>
                            </tr>        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->
        <!--LC INFORMATION-->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive  div-box-default">
                    <table class="table table-borderless">
                        <tr>                          
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.LC_NO'); ?></td>
                            <td colspan='5' class="vcenter"><?php echo !empty($orderInfo->lc_no)?$orderInfo->lc_no:''; ?></td>

                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.LC_DATE'); ?></td>
                            <td colspan='5' class="vcenter"><?php echo !empty($orderInfo->lc_date)?Helper::formatDate($orderInfo->lc_date):''; ?></td>
                        </tr>

                        <tr>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?></td>
                            <td colspan='5' class="vcenter">
                                <?php if($orderInfo->lc_transmitted_copy_done == '1'): ?>
                                <span class="label label-sm label-info"><?php echo app('translator')->get('label.YES'); ?></span>
                                <?php elseif($orderInfo->lc_transmitted_copy_done == '0'): ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.LC_ISSUE_DATE'); ?></td>
                            <td colspan='5' class="vcenter"><?php echo !empty($orderInfo->lc_issue_date)?Helper::formatDate($orderInfo->lc_issue_date):''; ?></td>
                        </tr>
                        <tr>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.BANK'); ?></td>
                            <td colspan='5' class="vcenter">
                                <?php echo !empty($orderInfo->lc_opening_bank)?$orderInfo->lc_opening_bank:''; ?>

                            </td>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.BRANCH'); ?></td>
                            <td colspan='5' class="vcenter"><?php echo !empty($orderInfo->bank_barnch)?$orderInfo->bank_barnch:''; ?></td>
                        </tr>
                        <tr>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.NOTE_'); ?></td>
                            <td colspan='5' class="vcenter"><?php echo !empty($orderInfo->note)?$orderInfo->note:''; ?></td>
                        </tr>
                        <tr>
                            <td class="fit bold  vcenter"><?php echo app('translator')->get('label.REMARKS'); ?></td>
                            <td colspan='11' class="vcenter"><?php echo !empty($orderInfo->order_accomplish_remarks)?$orderInfo->order_accomplish_remarks:''; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--END OF LC INFORMATION-->

        <!--product details-->
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$inquiryDetails->isEmpty()): ?>
                            <?php $__currentLoopData = $inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                            $perUnit = !empty($item->unit_name) ? ' / ' . $item->unit_name : '';
                            ?>
                            <tr>
                                <td class="vcenter"><?php echo e(!empty($item->product_name)?$item->product_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->brand_name)?$item->brand_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->grade_name)?$item->grade_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->gsm)?$item->gsm:''); ?></td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->quantity) ? $item->quantity . $unit : __('label.N_A')); ?>

                                </td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->unit_price) ? '$' . $item->unit_price . $perUnit : __('label.N_A')); ?>

                                </td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->total_price) ? '$' . $item->total_price : __('label.N_A')); ?>

                                </td>

                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/accomplishedOrder/showOrderDetails.blade.php ENDPATH**/ ?>