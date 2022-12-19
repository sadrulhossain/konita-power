<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.INVOICE'); ?>
        </h3>
    </div>
    <div class="modal-body">
        <?php echo Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'invoiceSaveForm')); ?>

        <?php echo e(csrf_field()); ?>

        <?php echo Form::hidden('supplier_id',  $request->supplier_id); ?>

        <?php echo Form::hidden('konita_bank_id',  $request->konita_bank_id); ?>


        <!--BL_history data-->
        <?php if(!empty($targetArr)): ?>
        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inqueryId=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $target; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId=>$deliveryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $deliveryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryDetailsId=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $totalKonitaCmsn = ((!empty($item['total_konita_cmsn']) ? $item['total_konita_cmsn'] : 0) - (!empty($item['total_principle_cmsn']) ? $item['total_principle_cmsn'] : 0));
        ?>
        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($item['shipmentQty'])?$item['shipmentQty']:null); ?>

        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][unit_price]',!empty($item['unit_price'])?$item['unit_price']:null); ?>

        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][shipment_total_price]',!empty($item['shipment_total_price'])?$item['shipment_total_price']:null); ?> 
        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:null); ?> 
        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',!empty($item['principle_cmsn'])?$item['principle_cmsn']:null); ?>

        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',!empty($item['total_konita_cmsn'])?$item['total_konita_cmsn']:null); ?>

        <?php echo Form::hidden('bl_no_history['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',!empty($item['total_principle_cmsn'])?$item['total_principle_cmsn']:null); ?>


        <!--commission history-->
        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($item['shipmentQty'])?$item['shipmentQty']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][company_konita_cmsn]',!empty($item['company_konita_cmsn'])?$item['company_konita_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_company_konita_cmsn]',!empty($item['total_company_konita_cmsn'])?$item['total_company_konita_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][sales_person_cmsn]',!empty($item['sales_person_cmsn'])?$item['sales_person_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_sales_person_cmsn]',!empty($item['total_sales_person_cmsn'])?$item['total_sales_person_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][buyer_cmsn]',!empty($item['buyer_cmsn'])?$item['buyer_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_buyer_cmsn]',!empty($item['total_buyer_cmsn'])?$item['total_buyer_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][rebate_cmsn]',!empty($item['rebate_cmsn'])?$item['rebate_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_rebate_cmsn]',!empty($item['total_rebate_cmsn'])?$item['total_rebate_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:0); ?> 
        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',!empty($totalKonitaCmsn)?$totalKonitaCmsn:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',!empty($item['principle_cmsn'])?$item['principle_cmsn']:0); ?>

        <?php echo Form::hidden('commission_history['.$inqueryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',!empty($item['total_principle_cmsn'])?$item['total_principle_cmsn']:0); ?>

        <!--END OF commission history-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <!--Endof_BL_history data-->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceDate"><?php echo app('translator')->get('label.DATE'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php
                                $currentDate = date('d F Y');
                                ?>
                                <div class="input-group date datepicker2">
                                    <?php echo Form::text('invoice_date', $currentDate, ['id'=> 'invoiceDate', 'class' => 'form-control', 'placeholder' =>'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="invoiceDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger"><?php echo e($errors->first('invoice_date')); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceNo"><?php echo app('translator')->get('label.INVOICE_NO'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('invoice_no',null, ['id'=> 'invoiceNo', 'class' => 'form-control','autocomplete'=>'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('invoice_no')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="invoiceNo"><?php echo app('translator')->get('label.ATTN'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8 margin-top-10">
                                <?php echo Form::select('supplier_contact_person_identify',  $contactPersonList, null, ['class' => 'form-control js-source-states','id'=>'identify']); ?>


                                <span class="bold"><?php echo e(!empty($supplierInfo->name)?$supplierInfo->name:''); ?></span><br/>
                                <span><?php echo e(!empty($supplierInfo->address)?$supplierInfo->address:''); ?></span><br/>
                                <span><?php echo e(!empty($supplierInfo->countryName)?$supplierInfo->countryName:''); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="subject"><?php echo app('translator')->get('label.SUBJECT'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-10 margin-top-10">
                                <?php echo Form::text('subject',__('label.SUBJECT_TITLE'), ['id'=> 'subject','rows'=>'3', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('subject')); ?></span>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                            <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                            <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                            <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                            <!--<th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.COMMISSION'); ?></th>-->
                                            <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.TOTAL'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($orderNoHistoryArr)): ?>
                                        <?php
                                        $sl = 0;
                                        ?>
                                        <?php $__currentLoopData = $orderNoHistoryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inqueryId=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="vcenter"><?php echo e(++$sl); ?></td>  
                                            <td class="vcenter"><?php echo e($item['buyer']); ?></td>  
                                            <td class="vcenter"><?php echo e($item['order_no']); ?></td> 
                                            <td class="vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <?php echo Form::text('order_no_history['.$inqueryId.'][qty]',!empty($item['total_shipmentQty'])?$item['total_shipmentQty']:null, ['class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                                        <?php echo app('translator')->get('label.UNIT'); ?>
                                                    </span>
                                                </div>
                                                <?php $__currentLoopData = $item['shipmentQty']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $measureUnitId=>$quantity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo Form::hidden('order_no_history['.$inqueryId.'][unit_wise_gty]['.$measureUnitId.']',!empty($quantity)?$quantity:null); ?>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>  
<!--                                            <td class="vcenter">
                                                <div class="input-group bootstrap-touchspin">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>-->
                                                    <?php echo Form::hidden('order_no_history['.$inqueryId.'][konita_cmsn]',!empty($item['konita_cmsn'])?$item['konita_cmsn']:0, ['class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

<!--                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                                        <?php echo app('translator')->get('label.PER'); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?>
                                                    </span>
                                                </div>-->
                                            <!--</td>-->  
                                            <td class="vcenter width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('order_no_history['.$inqueryId.'][total_konita_cmsn]',!empty($item['total_konita_cmsn'])?$item['total_konita_cmsn']:0, ['class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <!--sub_total-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.SUBTOTAL'); ?></td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('sub_total',!empty($orderWiseTotalKonitaCmsn)?$orderWiseTotalKonitaCmsn:0, ['id'=> 'subTotal', 'class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <!--admin_cost-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.ADMIN_COST'); ?></td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">(-) $</span>
                                                    <?php echo Form::text('admin_cost',!empty($orderWiseTotalPrincipleCmsn)?$orderWiseTotalPrincipleCmsn:0, ['id'=> 'adminCost', 'class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <!--net_receivable-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('net_receivable',!empty($netReceivable)?$netReceivable:0, ['id'=> 'netReceivable', 'class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <!--gift-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4">
                                                <div class="md-checkbox has-success margin-left-check">
                                                    <?php echo Form::checkbox('has_gift', null,false, ['id' => 'hasGift', 'class'=> 'md-check text-right has-gift-check']); ?>

                                                    <label for="hasGift">
                                                        <span class="inc text-right"></span>
                                                        <span class="check mark-caheck text-right"></span>
                                                        <span class="box mark-caheck text-right"></span>
                                                    </label>
                                                    &nbsp;&nbsp;<span><?php echo app('translator')->get('label.GIFT'); ?></span>
                                                </div>
                                            </td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">(+) $</span>
                                                    <?php echo Form::text('gift', null, ['id'=> 'gift', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per', 'disabled']); ?>


                                                </div>
                                            </td>
                                        </tr>
                                        <!--total amount-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.TOTAL_AMOUNT'); ?></td>
                                            <td class="vcenter bold width-150">
                                                <div class="input-group bootstrap-touchspin width-inherit">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    <?php echo Form::text('total_amount',!empty($netReceivable)?$netReceivable:0, ['id'=> 'totalAmount', 'class' => 'form-control text-right text-input-width-100-per','readonly']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--koniat bank information-->
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="col-md-6">
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BANK_INFORMATION'); ?></span>
                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BANK_NAME'); ?>: </span><?php echo e(!empty($konitaBankInfo->bank_name)?$konitaBankInfo->bank_name:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.ACCOUNT_NO'); ?>: </span><?php echo e(!empty($konitaBankInfo->account_no)?$konitaBankInfo->account_no:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.ACCOUNT_NAME'); ?>: </span><?php echo e(!empty($konitaBankInfo->account_name)?$konitaBankInfo->account_name:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BRANCH'); ?>: </span><?php echo e(!empty($konitaBankInfo->branch)?$konitaBankInfo->branch:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.SWIFT'); ?>: </span><?php echo e(!empty($konitaBankInfo->swift)?$konitaBankInfo->swift:''); ?>

                            </div>

                            <!--Signatory part-->
                            <!--                            <div class="margin-top-20">
                                                            <h5><?php echo app('translator')->get('label.REGARDS'); ?></h5>
                                                            <span>
                                                                <?php if(!empty($signatoryInfo->seal)): ?>
                                                                <img src="<?php echo e(URL::to('/')); ?>/public/img/signatoryInfo/<?php echo e($signatoryInfo->seal); ?>" style="width:100px; height: 100px;">
                                                                <?php else: ?>
                                                                <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" style="width:100px; height: 100px;">
                                                                <?php endif; ?>
                                                            </span><br/>
                                                            <span><?php echo e(!empty($signatoryInfo->name)?$signatoryInfo->name:''); ?></span><br/> 
                                                            <span><?php echo e(!empty($signatoryInfo->designation)?$signatoryInfo->designation:''); ?></span>
                                                        </div>-->
                        </div>
                    </div>
                </div>
                <!--end of konita bank information-->
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <button class="btn btn-inline green" type="button" id="submitInvoiceSave" data-status="1">
                    <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SAVE_AND_CONFIRM'); ?>
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
            </div>
        </div>
        <?php echo Form::close(); ?>

    </div>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script>
$(function () {
    $(".has-gift-check").on("click", function () {
        if ($(this).prop('checked')) {
            $("#gift").prop('disabled', false);
        } else {
            $("#gift").prop('disabled', true);
        }
    });

    $("#gift").on("keyup", function () {
        var gift = $(this).val();
        var netReceivable = $("#netReceivable").val();
        if(gift == ''){
            gift = 0;
        }
        var totalAmount = parseFloat(netReceivable) + parseFloat(gift);
        
        $("#totalAmount").val(totalAmount.toFixed(2));
    });
});
</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/showPreviewModal.blade.php ENDPATH**/ ?>