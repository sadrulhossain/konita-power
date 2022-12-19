<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="<?php echo e(URL::to('confirmedOrder/getShipmentDetailsPrint?shipment_id='.Request::get('shipment_id').'&view=print')); ?>"  title="<?php echo app('translator')->get('label.CLICK_HERE_TO_PRINT'); ?>">
            <i class="fa fa-print"></i>&nbsp;<?php echo app('translator')->get('label.PRINT'); ?>
        </a>
        <h4 class="modal-title text-center">
            <?php echo e(($shipmentInfo->shipment_status == 1) ? __('label.SHIPMENT_DETAILS_DRAFT') : __('label.SHIPMENT_DETAILS')); ?>

        </h4>
    </div>
    <div class="modal-body order-details-row">
        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.BASIC_ORDER_INFO'); ?></strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.ORDER_NO'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->order_no)?$shipmentInfo->order_no:''; ?></td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BUYER'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->buyer_name)?$shipmentInfo->buyer_name:''; ?></td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SALES_PERSON'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->salesPersonName)?$shipmentInfo->salesPersonName:''; ?></td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SUPPLIER'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->supplier_name)?$shipmentInfo->supplier_name:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SHIPPING_TREMS'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->shipping_terms)?$shipmentInfo->shipping_terms:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                <td width="50%">
                                    <?php if($shipmentInfo->order_status == '2'): ?>
                                    <span class="label label-sm label-primary"><?php echo app('translator')->get('label.CONFIRMED'); ?></span>
                                    <?php elseif($shipmentInfo->order_status == '3'): ?>
                                    <span class="label label-sm label-info"><?php echo app('translator')->get('label.PROCESSING_DELIVERY'); ?></span>
                                    <?php elseif($shipmentInfo->order_status == '4'): ?>
                                    <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACCOMPLISHED'); ?></span>
                                    <?php elseif($shipmentInfo->order_status == '6'): ?>
                                    <span class="label label-sm label-danger"><?php echo app('translator')->get('label.CANCELLED'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>     
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->purchase_order_no)?$shipmentInfo->purchase_order_no:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PO_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->po_date)?Helper::formatDate($shipmentInfo->po_date):''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.CREATION_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->creation_date)?Helper::formatDate($shipmentInfo->creation_date):''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.PI_DATE'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->pi_date)?Helper::formatDate($shipmentInfo->pi_date):''; ?>

                                </td>
                            </tr>        
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.DESTINATION_PORT'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->destination_port)?$shipmentInfo->destination_port:''; ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BENEFICIARY_BANK'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->beneficiary_bank_name)?$shipmentInfo->beneficiary_bank_name: __('label.N_A'); ?>

                                </td>
                            </tr> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.LC_INFORMATION'); ?></strong></h4>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                        <table class="table table-borderless">
                            <tr>                          
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.LC_NO'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->lc_no)?$shipmentInfo->lc_no:__('label.N_A'); ?></td>
                            </tr>

                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?></td>
                                <td width="50%">
                                    <?php if($shipmentInfo->lc_transmitted_copy_done == '1'): ?>
                                    <span class="label label-sm label-info"><?php echo app('translator')->get('label.YES'); ?></span>
                                    <?php elseif($shipmentInfo->lc_transmitted_copy_done == '0'): ?>
                                    <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BANK'); ?></td>
                                <td width="50%">
                                    <?php echo !empty($shipmentInfo->lc_opening_bank)?$shipmentInfo->lc_opening_bank:__('label.N_A'); ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                        <table class="table table-borderless">
                            <tr>                          
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.LC_DATE'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->lc_date)?Helper::formatDate($shipmentInfo->lc_date):__('label.N_A'); ?></td>
                            </tr>

                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.LC_ISSUE_DATE'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->lc_issue_date)?Helper::formatDate($shipmentInfo->lc_issue_date):__('label.N_A'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BRANCH'); ?></td>
                                <td width="50%"><?php echo !empty($shipmentInfo->bank_barnch)?$shipmentInfo->bank_barnch:__('label.N_A'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <table class="table table-borderless">
                            <tr>
                                <td class="bold" width="25%"><?php echo app('translator')->get('label.NOTE_'); ?></td>
                                <td width="75%"><?php echo !empty($shipmentInfo->note)?$shipmentInfo->note:__('label.N_A'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF LC INFORMATION-->

        <div class="row">
            <!-- BL Information -->
            <div class="col-md-8">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4>
                                    <strong><?php echo app('translator')->get('label.BL_INFORMATION'); ?></strong>
                                    <?php if($shipmentInfo->buyer_payment_status == '1'): ?>
                                    &nbsp;<span class="label label-sm label-green-seagreen"><strong><?php echo app('translator')->get('label.PAID'); ?></strong></span>
                                    <?php endif; ?>
                                </h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-borderless">
                                    <tr>                          
                                        <td class="bold" width="50%"><?php echo app('translator')->get('label.BL_NO'); ?></td>
                                        <td width="50%"><?php echo !empty($shipmentInfo->bl_no)?$shipmentInfo->bl_no:__('label.N_A'); ?></td>
                                    </tr>

                                    <tr>
                                        <td class="bold" width="50%"><?php echo app('translator')->get('label.EXPRESS_TRACKING_NO'); ?></td>
                                        <td width="50%">
                                            <?php if(!empty($userAccessArr[27][16]) && $shipmentInfo->shipment_status == '2'): ?>
                                            <div class="plain-track">
                                                <span class="track-no" id="trackingNo"><?php echo !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no: __('label.N_A'); ?></span> &nbsp;
                                                <button class="btn btn-xs btn-primary edit-track tooltips vcenter" title="Edit Tracking No." type="button">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                            <div class="editable-track">
                                                <div class="input-group bootstrap-touchspin">
                                                    <?php echo Form::text('express_tracking_no', !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no:null, ['id'=> 'editableExpressTrackingNo', 'class' => 'form-control editable-track-no','autocomplete' => 'off', 'style' => 'width: 100px']); ?> 
                                                    <span class="input-group-addon label-green-seagreen padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                        <button class="btn btn-sm green-seagreen update-track margin-0 tooltips vcenter" data-shipment-id="<?php echo e($request->shipment_id); ?>" title="Update Tracking No." type="button">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <div class="">
                                                <?php echo !empty($shipmentInfo->express_tracking_no)?$shipmentInfo->express_tracking_no:__('label.N_A'); ?>

                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-borderless">
                                    <tr>                          
                                        <td class="bold" width="50%"><?php echo app('translator')->get('label.DATE_OF_BL'); ?></td>
                                        <td width="50%"><?php echo !empty($shipmentInfo->bl_date)?Helper::formatDate($shipmentInfo->bl_date):__('label.N_A'); ?></td>
                                    </tr>

                                    <tr>
                                        <td class="bold" width="50%"><?php echo app('translator')->get('label.LAST_SHIPMENT'); ?></td>
                                        <td width="50%">
                                            <?php if($shipmentInfo->last_shipment == '1'): ?>
                                            <span class="label label-sm label-info"><?php echo app('translator')->get('label.YES'); ?></span>
                                            <?php elseif($shipmentInfo->last_shipment == '0'): ?>
                                            <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: BL Information -->

            <!-- Carrier Information -->
            <div class="col-md-4">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong><?php echo app('translator')->get('label.CARRIER_INFORMATION'); ?></strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <table class="table table-borderless">
                                <tr>                          
                                    <td class="bold" width="50%"><?php echo app('translator')->get('label.SHIPPING_LINE'); ?></td>
                                    <td width="50%"><?php echo !empty($shipmentInfo->shipping_line_name)?$shipmentInfo->shipping_line_name:__('label.N_A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="bold" width="50%"><?php echo app('translator')->get('label.CONTAINER_NO'); ?></td>
                                    <td width="50%">
                                        <?php if(!empty($containerNo)): ?>
                                        <?php $c = 0; ?>
                                        <?php $__currentLoopData = $containerNo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($contNo); ?><?php echo $c < (count($containerNo)-1) ? ', <br/>'  : ''; ?>

                                        <?php ++$c; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <?php echo app('translator')->get('label.N_A'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End fo :: Carrier Information -->
        </div>

        <!-- Product information-->
        <?php if(!$inquiryDetails->isEmpty()): ?>
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.PRODUCT_N_SHIPMENT_INFORMATION'); ?></strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_QUANTITY'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.ALREADY_DELIVERED'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.DUE_DELIVERY'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.SHIPMENT_QTY'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>
                                    <?php $__currentLoopData = $inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <?php
                                        $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                        $perUnit = !empty($item->unit_name) ? ' /' . $item->unit_name : '';
                                        $textAlignDueQty = 'text-center';
                                        $dueQuantity = '--';
                                        if (!empty($dueQuantityArr[$item->id]) && $dueQuantityArr[$item->id] > 0) {
                                            $textAlignDueQty = 'text-right';
                                            $dueQuantity = Helper::numberFormat2Digit($dueQuantityArr[$item->id]) . $unit;
                                        }

                                        $textAlignshipmentQty = 'text-center';
                                        $shipmentQuantity = '--';
                                        if (!empty($shipmentQuantityArr[$item->id][$request->shipment_id])) {
                                            $textAlignshipmentQty = 'text-right';
                                            $shipmentQuantity = Helper::numberFormat2Digit($shipmentQuantityArr[$item->id][$request->shipment_id]) . $unit;
                                        }
                                        ?>
                                        <td class="text-center vcenter"><?php echo ++$countItem; ?></td>
                                        <td class="vcenter"><?php echo $item->product_name ?? ''; ?></td>
                                        <td class="vcenter"><?php echo $item->brand_name ?? ''; ?></td>
                                        <td class="vcenter"><?php echo $item->grade_name ?? ''; ?></td>
                                        <td class="text-right vcenter"><?php echo '$'.(!empty($item->unit_price) ? $item->unit_price : 0.00).$perUnit; ?></td>
                                        <td class="text-right vcenter"><?php echo (!empty($item->quantity) ? $item->quantity : 0.00).$unit; ?></td>
                                        <td class="text-right vcenter"><?php echo '$'.(!empty($item->total_price) ? $item->total_price : 0.00); ?></td>
                                        <td class="text-right vcenter">
                                            <?php echo ((!empty($quantitySumArr[$item->id]) && $quantitySumArr[$item->id] != 0) ? Helper::numberFormat2Digit($quantitySumArr[$item->id]) : Helper::numberFormat2Digit(0)).$unit; ?>

                                            <?php echo (!empty($surplusQuantityArr[$item->id]) && $surplusQuantityArr[$item->id] > 0) ? '<br/><span class="text-danger">(Additional ' . Helper::numberFormat2Digit($surplusQuantityArr[$item->id]).$unit . ')</span>' : ''; ?>

                                        </td>
                                        <td class="<?php echo e($textAlignDueQty); ?> vcenter"><?php echo $dueQuantity; ?></td>
                                        <td class="<?php echo e($textAlignshipmentQty); ?> vcenter"><?php echo $shipmentQuantity; ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--END OF BASIC ORDER INFORMATION-->

        <!-- commission information-->
        <?php if(!empty($commissionInfo)): ?>
        <!--        <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong><?php echo app('translator')->get('label.COMMISSION_INFORMATION'); ?></strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.KONITA_CMSN'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.BUYER_COMMISSION'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.REBATE_COMMISSION'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right vcenter"><?php echo '$' . (!empty($commissionInfo->konita_cmsn) ? Helper::numberFormat2Digit($commissionInfo->konita_cmsn) : '0.00') . "<span class='bold'>/unit</span>"; ?></td>
                                                <td class="text-right vcenter"><?php echo '$' . (!empty($commissionInfo->sales_person_cmsn) ? Helper::numberFormat2Digit($commissionInfo->sales_person_cmsn) : '0.00') . "<span class='bold'>/unit</span>"; ?></td>
                                                <td class="text-right vcenter"><?php echo '$' . (!empty($commissionInfo->buyer_cmsn) ? Helper::numberFormat2Digit($commissionInfo->buyer_cmsn) : '0.00') . "<span class='bold'>/unit</span>"; ?></td>
                                                <td class="text-right vcenter"><?php echo '$' . (!empty($commissionInfo->rebate_cmsn) ? Helper::numberFormat2Digit($commissionInfo->rebate_cmsn) : '0.00') . "<span class='bold'>/unit</span>"; ?></td>
                                                <td class="text-right vcenter"><?php echo '$' . (!empty($commissionInfo->principle_cmsn) ? Helper::numberFormat2Digit($commissionInfo->principle_cmsn) : '0.00') . "<span class='bold'>/unit</span>"; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
        <?php endif; ?>
        <!--END OF commission information-->

        <div class="row">
            <!-- ETS Information -->
            <div class="col-md-6">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong><?php echo app('translator')->get('label.ETS_INFO'); ?></strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.ETS_DATE'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.ETS_NOTIFICATION_DATE'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($etsInfo)): ?>
                                            <?php
                                            $slets = 0;
                                            ?>
                                            <?php $__currentLoopData = $etsInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center vcenter"> <?php echo e(++$slets); ?></td>
                                                <td class="text-center vcenter"><?php echo !empty($ets['ets_date'])?Helper::formatDate($ets['ets_date']):__('label.N_A'); ?></td>
                                                <td class="text-center vcenter"><?php echo !empty($ets['ets_notification_date'])?Helper::formatDate($ets['ets_notification_date']):__('label.N_A'); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                            <tr>
                                                <td class="vcenter" colspan="2"><?php echo app('translator')->get('label.NO_ETS_INFO_FOUND'); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: ETS Information -->

            <!-- ETA Information -->
            <div class="col-md-6">
                <div class="row div-box-default">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong><?php echo app('translator')->get('label.ETA_INFO'); ?></strong></h4>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.ETA_DATE'); ?></th>
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.ETA_NOTIFICATION_DATE'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($etaInfo)): ?>
                                            <?php
                                            $sleta = 0;
                                            ?>
                                            <?php $__currentLoopData = $etaInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center vcenter"> <?php echo e(++$sleta); ?></td>
                                                <td class="text-center vcenter"><?php echo !empty($eta['eta_date'])?Helper::formatDate($eta['eta_date']):__('label.N_A'); ?></td>
                                                <td class="text-center vcenter"><?php echo !empty($eta['eta_notification_date'])?Helper::formatDate($eta['eta_notification_date']):__('label.N_A'); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                            <tr>
                                                <td class="vcenter" colspan="2"><?php echo app('translator')->get('label.NO_ETA_INFO_FOUND'); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of :: ETA Information -->
        </div>

        <!-- Start of :: Lead Time Information -->
        <?php if(!empty($leadTimeArr)): ?>
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.LEAD_TIME_INFORMATION'); ?></strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.DELIVERY_TIME'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TRANSIT_TIME'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_LEAD_TIME'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center vcenter"><?php echo $leadTimeArr['delivery_time']; ?></td>
                                        <td class="text-center vcenter"><?php echo $leadTimeArr['transit_time']; ?></td>
                                        <td class="text-center vcenter"><?php echo $leadTimeArr['total_lead_time']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- End of :: Lead Time Information -->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //editable tracking no.
    $(".plain-track").show();
    $(".editable-track").hide();

    $(".edit-track").on("click", function () {
        $(".plain-track").hide();
        $(".editable-track").show();
    });

    $(".update-track").on("click", function (e) {
//        $(".plain-track").show();
//        $(".editable-track").hide();

        e.preventDefault();

        var shipmentId = $(this).attr("data-shipment-id");
        var trackingNo = $("#editableExpressTrackingNo").val();
//        alert(shipmentId+trackingNo);
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: "<?php echo e(URL::to('confirmedOrder/updateTrackingNo')); ?>",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                shipment_id: shipmentId,
                tracking_no: trackingNo,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                toastr.success(res.message, res.heading, options);
                $("#trackingNo").text(trackingNo != '' ? trackingNo : 'N/A');
                $("#editableExpressTrackingNo").val(trackingNo);
                $(".plain-track").show();
                $(".editable-track").hide();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }

                App.unblockUI();
            }
        }); //ajax
    });
    //end :: editable tracking no.
});
</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/confirmedOrder/orderShipment/showShipmentFullDetail.blade.php ENDPATH**/ ?>