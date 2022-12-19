<?php $__env->startSection('data_count'); ?>
<div class="shipment-info-page col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i><?php echo app('translator')->get('label.SET_SHIPMENT_INFO'); ?>&nbsp;&nbsp;
                <h6 class="caption-sub-caption bold"><?php echo app('translator')->get('label.ORDER_NO'); ?> : <?php echo !empty($target->order_no)?$target->order_no:''; ?>&nbsp;|&nbsp;<?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?>: <?php echo !empty($target->purchase_order_no)?$target->purchase_order_no:''; ?></h6>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('/confirmedOrder')); ?>" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;<?php echo app('translator')->get('label.CLICK_TO_GO_BACK'); ?>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row margin-bottom-10">
                <div class="col-md-12 add-new-shipment-col">
                    <?php if(empty($draftDeliveryList) && in_array($target->order_status, ['2', '3'])): ?>
                    <button class="btn btn-lg btn-radius-50 green-seagreen add-new-shipment tooltips" href="#modalAddNewShipment" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.ADD_NEW_SHIPMENT'); ?>">
                        <i class="fa fa-plus"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if(!empty($draftDeliveryList)): ?>
                    <?php
                    $crActive = 'active';
                    $blActive = '';
                    $tooltipText = __('label.ADD_CARRIER_INFORMATION');
                    $blTooltipText = __('label.ADD_CARRIER_INFO_BEFORE_PROCCEED_TO_BL_INFO');
                    $blAddHide = 'disabled';
                    if (!empty($draftDeliveryList->shipping_line) && !empty($draftDeliveryList->container_no)) {
                        $crActive = 'done';
                        $blActive = 'active';
                        $tooltipText = __('label.EDIT_CARRIER_INFORMATION');
                        $blTooltipText = __('label.ADD_BL_INFORMATION');
                        $blAddHide = '';
                    }
                    ?>
                    <div class="row margin-bottom-30">
                        <div class="col-md-11 col-lg-11 col-sm-12 col-xs-12">
                            <div class="mt-element-step">
                                <div class="row step-line">
                                    <div class="col-md-4 mt-step-col first done">
                                        <a class="a-tag-decoration-none edit-ets-eta-info tooltips vcenter" href="#modalEditEtsEtaInfo" data-id="<?php echo $draftDeliveryList->id; ?>" data-shipment-status="<?php echo $draftDeliveryList->shipment_status; ?>" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-calendar"></i> 
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font"><?php echo app('translator')->get('label.ETS_ETA_INFORMATION'); ?></div>
                                        <div class="mt-step-content font-grey-cascade"><?php echo app('translator')->get('label.EDIT_ETS_ETA_INFORMATION'); ?></div>
                                    </div>
                                    <div class="col-md-4 mt-step-col <?php echo e($crActive); ?>">
                                        <a class="a-tag-decoration-none set-carrier-info tooltips vcenter" href="#modalSetCarrierInfo" data-id="<?php echo $draftDeliveryList->id; ?>" data-shipment-status="<?php echo $draftDeliveryList->shipment_status; ?>" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-ship"></i>
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font"><?php echo app('translator')->get('label.CARRIER_INFORMATION'); ?></div>
                                        <div class="mt-step-content font-grey-cascade"><?php echo e($tooltipText); ?></div>
                                    </div>
                                    <div class="col-md-4 mt-step-col last <?php echo e($blActive); ?>">
                                        <a class="a-tag-decoration-none <?php echo e($blAddHide); ?> set-bl-info tooltips vcenter" href="#modalSetBlInfo" data-id="<?php echo $draftDeliveryList->id; ?>" data-shipment-status="<?php echo $draftDeliveryList->shipment_status; ?>" data-toggle="modal">
                                            <div class="mt-step-number bg-white">
                                                <i class="fa fa-th-large"></i>
                                            </div>
                                        </a>
                                        <div class="mt-step-title font-grey-cascade step-font"><?php echo app('translator')->get('label.BL_INFORMATION'); ?></div>
                                        <div class="mt-step-content font-grey-cascade"><?php echo e($blTooltipText); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 draft-shipment-details">
                            <button class="btn btn-lg btn-radius-50 yellow view-shipment-full-detail tooltips" href="#modalViewShipmentFullDetail" data-id="<?php echo $draftDeliveryList->id; ?>" data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_SHIPMENT_DETAILS_DRAFT'); ?>">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--order info btn-->
            <a class="btn order-details-btn grey-mint btn-lg tooltips" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_ORDER_INFORMATON'); ?>">
                <i class="fa fa-th"></i>
            </a>
            <!--order info btn-->
            <div class="order-details-div">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless">
                            <tr class="border-bottom-1-green-seagreen">
                                <td class="bold"><h4><?php echo app('translator')->get('label.ORDER_INFO'); ?></h4></td>
                                <td class="text-right">
                                    <a class="order-detail-close btn btn-danger btn-sm tooltips" title="<?php echo app('translator')->get('label.CLOSE'); ?>"><i class="fa fa-close"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="order-details-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.CLIENT'); ?></td>
                                    <td colspan="5" class="active"><?php echo !empty($target->buyer_name)?$target->buyer_name:''; ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.SUPPLIER'); ?></td>
                                    <td colspan="5" class="active"><?php echo !empty($target->supplier_name)?$target->supplier_name:''; ?></td>
                                </tr>
                                <tr >
                                    <td class="fit bold info"><?php echo app('translator')->get('label.SALES_PERSON'); ?></td>
                                    <td colspan="5" class="active"><?php echo !empty($target->salesPersonName)?$target->salesPersonName:''; ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                    <td colspan="5" class="active">
                                        <?php if($target->order_status == '2'): ?>
                                        <span class="label label-sm label-primary"><?php echo app('translator')->get('label.CONFIRMED'); ?></span>
                                        <?php elseif($target->order_status == '3'): ?>
                                        <span class="label label-sm label-info"><?php echo app('translator')->get('label.PROCESSING_DELIVERY'); ?></span>
                                        <?php elseif($target->order_status == '4'): ?>
                                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACCOMPLISHED'); ?></span>
                                        <?php elseif($target->order_status == '5'): ?>
                                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.PAYMENT_DONE'); ?></span>
                                        <?php elseif($target->order_status == '6'): ?>
                                        <span class="label label-sm label-danger"><?php echo app('translator')->get('label.CANCELLED'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr >
                                    <td class="fit bold info"><?php echo app('translator')->get('label.CREATION_DATE'); ?></td>
                                    <td colspan="5" class="active"><?php echo !empty($target->creation_date) ? Helper::formatDate($target->creation_date) : __('label.N_A'); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.PO_DATE'); ?></td>
                                    <td colspan="5" class="active"><?php echo !empty($target->po_date) ? Helper::formatDate($target->po_date) : __('label.N_A'); ?></td>
                                </tr>
                            </table>
                            <?php if(!$inquiryDetails->isEmpty()): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="info">
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                        <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_QUANTITY'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.ALREADY_DELIVERED'); ?></th>
                                        <th class="text-center vcenter"><?php echo app('translator')->get('label.DUE_DELIVERY'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $countItem = 0; ?>
                                    <?php $__currentLoopData = $inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                    $textAlignDueQty = 'text-center';
                                    $dueQuantity = '--';
                                    if (!empty($dueQuantityArr[$item->id]) && $dueQuantityArr[$item->id] > 0) {
                                        $textAlignDueQty = 'text-right';
                                        $dueQuantity = Helper::numberFormat2Digit($dueQuantityArr[$item->id]) . $unit;
                                    }
                                    ?>
                                    <tr class="active">
                                        <td class="text-center vcenter"><?php echo ++$countItem; ?></td>
                                        <td class="vcenter"><?php echo $item->product_name ?? ''; ?></td>
                                        <td class="vcenter"><?php echo $item->brand_name ?? ''; ?></td>
                                        <td class="vcenter"><?php echo $item->grade_name ?? ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($item->gsm) ? $item->gsm : ''; ?></td>
                                        <td class="text-right vcenter"><?php echo (!empty($item->quantity) ? $item->quantity : 0.00).$unit; ?></td>
                                        <td class="text-right vcenter">
                                            <?php echo ((!empty($quantitySumArr[$item->id]) && $quantitySumArr[$item->id] != 0) ? Helper::numberFormat2Digit($quantitySumArr[$item->id]) : Helper::numberFormat2Digit(0)).$unit; ?>

                                            <?php echo (!empty($surplusQuantityArr[$item->id]) && $surplusQuantityArr[$item->id] > 0) ? '<br/><span class="text-danger">(Additional ' . Helper::numberFormat2Digit($surplusQuantityArr[$item->id]).$unit . ')</span>' : ''; ?>

                                        </td>
                                        <td class="<?php echo e($textAlignDueQty); ?> vcenter"><?php echo $dueQuantity; ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--order info btn : end-->
            
            <div class="row">
                <div class="col-md-12">
                    <h4 class="border-bottom-1-green-seagreen bold"><?php echo app('translator')->get('label.SHIPMENT_LIST_OF_THIS_ORDER'); ?></h4>
                    <?php if(!$shippedDeliveryList->isEmpty()): ?>
                    <?php $__currentLoopData = $shippedDeliveryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shippedDelivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row margin-bottom-10">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 padding-17 col-padding-default">
                                <span class="text-center vcenter bold"><?php echo app('translator')->get('label.BL_NO'); ?> : <?php echo !empty($shippedDelivery->bl_no)?$shippedDelivery->bl_no:'--'; ?></span>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 padding-17 col-padding-default">
                                <span class="text-center vcenter bold"><?php echo app('translator')->get('label.DATE_OF_BL'); ?> : <?php echo !empty($shippedDelivery->bl_date)?Helper::formatDate($shippedDelivery->bl_date):''; ?></span>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-chambray edit-ets-eta-info tooltips" href="#modalEditEtsEtaInfo" data-id="<?php echo $shippedDelivery->id; ?>" data-shipment-status="<?php echo $shippedDelivery->shipment_status; ?>" data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_ETS_ETA_INFO'); ?>">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-dark set-carrier-info tooltips" href="#modalSetCarrierInfo" data-id="<?php echo $shippedDelivery->id; ?>" data-shipment-status="<?php echo $shippedDelivery->shipment_status; ?>" data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_CARRIER_INFO'); ?>">
                                    <i class="fa fa-ship"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-madison set-bl-info tooltips" href="#modalSetBlInfo" data-id="<?php echo $shippedDelivery->id; ?>" data-shipment-status="<?php echo $shippedDelivery->shipment_status; ?>" data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_BL_INFO'); ?>">
                                    <i class="fa fa-th-large"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-3 col-xs-3 col-padding-default">
                                <button class="btn btn-lg btn-radius-50 blue-steel view-shipment-full-detail tooltips" href="#modalViewShipmentFullDetail" data-id="<?php echo $shippedDelivery->id; ?>" data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_SHIPMENT_DETAILS'); ?>">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <div class="col-md-12 div-padding-default text-danger">
                        <?php echo app('translator')->get('label.NO_SHIPMENT_HAS_BEEN_MADE_YET'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>	
    </div>
</div>

<!-- Modal start -->

<!--add new shipment-->
<div class="modal fade" id="modalAddNewShipment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAddnewShipment"></div>
    </div>
</div>

<!--edit ets eta info-->
<div class="modal fade" id="modalEditEtsEtaInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showEditEtsEtaInfo"></div>
    </div>
</div>

<!--set carrier info-->
<div class="modal fade" id="modalSetCarrierInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showSetCarrierInfo"></div>
    </div>
</div>

<!--set bl info-->
<div class="modal fade" id="modalSetBlInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetBlInfo"></div>
    </div>
</div>

<!--view shipment full detail-->
<div class="modal fade" id="modalViewShipmentFullDetail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentFullDetail"></div>
    </div>
</div>


<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //initially hide order information
        $(".order-details-div").hide();

        //show order information
        $(".order-details-btn").on("click", function () {
            $(".order-details-div").show(1000);
            //$(".shipment-info-page").css("cursor", "pointer");
        });

        // if order information visible,
        // hide on click page body
        $(".order-details-div, .order-detail-close").on("click", function () {
            $(".order-details-div").hide(1000);
        });


        //        if($(".order-details-div").show()){
        //            
        //        }


        //add new shipment modal
        $(".add-new-shipment").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getNewShipmentAdd')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showAddnewShipment").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //edit ets eta info modal
        $(".edit-ets-eta-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/editEtsEtaInfo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showEditEtsEtaInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set carrier info modal
        $(".set-carrier-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getCarrierInfo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showSetCarrierInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set bl info modal
        $(".set-bl-info").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            var shipmentStatus = $(this).attr("data-shipment-status");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getBlInfo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    shipment_status: shipmentStatus,
                },
                success: function (res) {
                    $("#showSetBlInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //shipment full details modal
        $(".view-shipment-full-detail").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getShipmentFullDetail')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentFullDetail").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/confirmedOrder/orderShipment/getShipmentInfoView.blade.php ENDPATH**/ ?>