<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="table-responsive webkit-scrollbar max-height-500">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <th class="vcenter" rowspan="2" colspan="2"><?php echo app('translator')->get('label.INVOICE_NO'); ?></th>
                        <th class="vcenter text-center" colspan="5"><?php echo app('translator')->get('label.INVOICED_PAYMENT'); ?></th>
                        <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                        <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                        <th class="vcenter text-center" colspan="4"><?php echo app('translator')->get('label.BL_PAYMENT'); ?></th>
                    </tr>
                    <tr class="active">
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.BILLED'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.RECEIVED'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.DUE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.COLLECTION_AMOUNT'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.RECEIVED'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.DUE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.COLLECTION_AMOUNT'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($invoiceDetailsArr)): ?>
                    <?php $sl = 0; ?>
                    <?php $__currentLoopData = $invoiceDetailsArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceId => $invoiceDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo ++$sl; ?></td>
                        <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('full_pay['.$invoiceId.']',1,false, ['id' => 'fullPay_'.$invoiceId, 'data-id' => $invoiceId, 'class'=> 'md-check full-pay-check']); ?>

                                <label for="<?php echo 'fullPay_'.$invoiceId; ?>">
                                    <span class="inc checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                    <span class="check mark-caheck checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                    <span class="box mark-caheck checkbox-text-center tooltips" title="<?php echo app('translator')->get('label.TICK_TO_PAY_IN_FULL'); ?>"></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo $invoiceDetails['invoice_no']; ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo '$'.Helper::numberFormat2Digit($invoiceDetails['total_billed']); ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo '$'.Helper::numberFormat2Digit($invoiceDetails['billed']); ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo !empty($invoiceCollection[$invoiceId]['received']) ?'$'.Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['received']) : '$'.Helper::numberFormat2Digit(0); ?></td>
                        <td class="text-right vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>"><?php echo !empty($invoiceCollection[$invoiceId]['due']) ?'$'.Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['due']) : '$'.Helper::numberFormat2Digit(0); ?></td>
                        <td class="text-center vcenter" rowspan="<?php echo e($invoiceRowSpan[$invoiceId]); ?>">
                            <div class="input-group bootstrap-touchspin width-150">
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                <?php echo Form::text('invoice_collection_amount['.$invoiceId.']', null, ['id'=> 'invoiceCollectionAmount_'.$invoiceId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId, 'data-due-amount' => ($invoiceCollection[$invoiceId]['due'] ?? 0.00), 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right invoice-collection-amount','autocomplete' => 'off']); ?>

                            </div>
                            <span class="pull-right remaining-amount-<?php echo e($invoiceId); ?>"></span>
                        </td>

                        <?php echo Form::hidden('invoice_no['.$invoiceId.']', $invoiceDetails['invoice_no']); ?>

                        <?php echo Form::hidden('billed['.$invoiceId.']', $invoiceDetails['billed']); ?>

                        <?php echo Form::hidden('invoice_due['.$invoiceId.']', $invoiceCollection[$invoiceId]['due'] ?? 0.00); ?>


                        <?php if(!empty($inquiryDetailsArr[$invoiceId])): ?>
                        <?php $i = 0; ?>
                        <?php $__currentLoopData = $inquiryDetailsArr[$invoiceId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId => $inquiryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter" rowspan="<?php echo e($inquiryRowSpan[$invoiceId][$inquiryId]); ?>"><?php echo $inquiryDetails['order_no']; ?></td>

                        <?php echo Form::hidden('order_no['.$inquiryId.']', $inquiryDetails['order_no']); ?>


                        <?php if(!empty($deliveryDetailsArr[$invoiceId][$inquiryId])): ?>
                        <?php $j = 0; ?>
                        <?php $__currentLoopData = $deliveryDetailsArr[$invoiceId][$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId => $deliveryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>

                        <?php echo Form::hidden('total_konita_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_konita_commission']); ?>

                        <?php echo Form::hidden('total_sales_person_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_sales_person_commission']); ?>

                        <?php echo Form::hidden('total_company_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_company_commission']); ?>

                        <?php echo Form::hidden('total_buyer_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_buyer_commission']); ?>

                        <?php echo Form::hidden('total_rebate_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_rebate_commission']); ?>

                        <?php echo Form::hidden('total_principle_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_principle_commission']); ?>


                        <td class="vcenter"><?php echo $deliveryDetails['bl_no']; ?></td>

                        <?php echo Form::hidden('bl_no['.$deliveryId.']', $deliveryDetails['bl_no']); ?>

                        <?php echo Form::hidden('due['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $blCollection[$invoiceId][$inquiryId][$deliveryId]['due'] ?? 0.00); ?>


                        <td class="text-right vcenter"><?php echo '$'.Helper::numberFormat2Digit($deliveryDetails['total_konita_commission']); ?></td>
                        <td class="text-right vcenter"><?php echo !empty($blCollection[$invoiceId][$inquiryId][$deliveryId]['received']) ?'$'.Helper::numberFormat2Digit($blCollection[$invoiceId][$inquiryId][$deliveryId]['received']) : '$'.Helper::numberFormat2Digit(0); ?></td>
                        <td class="text-right vcenter"><?php echo !empty($blCollection[$invoiceId][$inquiryId][$deliveryId]['due']) ?'$'.Helper::numberFormat2Digit($blCollection[$invoiceId][$inquiryId][$deliveryId]['due']) : '$'.Helper::numberFormat2Digit(0); ?></td>
                        <td class="text-center vcenter">
                            <?php $disabled = $blCollection[$invoiceId][$inquiryId][$deliveryId]['disabled'] ?? ''; ?>
                            <div class="input-group bootstrap-touchspin width-150">
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                <?php echo Form::text('collection_amount['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', null, ['id'=> 'collectionAmount_'.$invoiceId.'_'.$inquiryId.'-'.$deliveryId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId.'-'.$inquiryId.'-'.$deliveryId, 'data-delivery-id' => $deliveryId, 'data-due-amount' => ($blCollection[$invoiceId][$inquiryId][$deliveryId]['due'] ?? 0.00), 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right collection-amount collection-amount-'.$invoiceId.' collection-amount-'.$invoiceId.'-'.$deliveryId, 'autocomplete' => 'off', $disabled]); ?>

                            </div>
                            <span class="pull-right remaining-amount-<?php echo e($invoiceId.'-'.$inquiryId.'-'.$deliveryId); ?> remaining-delivery-amount-<?php echo e($invoiceId); ?>"></span>
                        </td>
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
            </table    >
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row margin-top-20">
        <div class="col-md-offset-4 col-md-8">
            <button class="btn green btn-submit" id="previewReceive"  href="#modalViewReceivePreview" type="button" data-toggle="modal">
                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.PREVIEW'); ?>
            </button>
            <a href="<?php echo e(URL::to('/receive')); ?>" class="btn btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>

        </div>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script>
$(function () {
    $("tooltips").tooltip();
    //when pay in full
    $(".full-pay-check").click(function () {
        //get invoice id
        var invoiceId = $(this).attr("data-id");
        //if pay in full
        if ($(this).prop('checked')) {
            var invElId = "#invoiceCollectionAmount_" + invoiceId;
            //get invoice due
            var invoiceDue = $(invElId).attr("data-due-amount");
            //set span text $0.00
            $('span.remaining-amount-' + invoiceId).text("Due : $0.00");
            $('span.remaining-amount-' + invoiceId).css("color", "green");
            $(invElId).val(invoiceDue);
            $(invElId).prop('readonly', true);


            $(".collection-amount-" + invoiceId).each(function () {
                var deliveryId = $(this).attr("data-delivery-id");
                var due = $(this).attr("data-due-amount");
                var delvId = $(this).attr("data-id");
                //if bl collection amount amount is disabled
                //or bl due 0
                if ($(this).prop('disabled') == false) {
                    $('span.remaining-amount-' + delvId).text("Due : $0.00");
                    $('span.remaining-amount-' + delvId).css("color", "green");
                    $(this).val(due);
                    $(this).prop('readonly', true);
                }
            });
        } else {
            //clear all number and span text
            $('span.remaining-amount-' + invoiceId).text('');
            $('span.remaining-delivery-amount-' + invoiceId).text('');
            $("#invoiceCollectionAmount_" + invoiceId).val('');
            $(".collection-amount-" + invoiceId).val('');
            $("#invoiceCollectionAmount_" + invoiceId).prop('readonly', false);
            $(".collection-amount-" + invoiceId).prop('readonly', false);
        }

    });
    //end :: when pay in full
    
    $('.invoice-collection-amount').keyup(function (e) {
        findRemainingAmount(e, this);
    });

    $('.collection-amount').keyup(function (e) {
        findRemainingAmount(e, this);
    });

    function findRemainingAmount(e, selector) {
        e.preventDefault();
        var amount = $(selector).val();
        var id = $(selector).attr("data-id");
        var due = $(selector).attr("data-due-amount");

        if (amount == '') {
            $('span.remaining-amount-' + id).text('');
            return false;
        }

        var remaining = due - amount;

        if (amount.length > 0) {
            if (remaining >= 0) {
                $('span.remaining-amount-' + id).text("Due : $" + remaining.toFixed(2));
                $('span.remaining-amount-' + id).css("color", "green");
                return false;
            } else {
                remaining = remaining * (-1);
                $('span.remaining-amount-' + id).text("Surplus : $" + remaining.toFixed(2));
                $('span.remaining-amount-' + id).css("color", "red");
                return false;
            }
        }
    }
});

</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/receive/showReceiveData.blade.php ENDPATH**/ ?>