<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.RECEIVABLE'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    <?php echo Form::open(array('group' => 'form', 'url' => 'billing/getBillingCreateData','class' => 'form-horizontal')); ?>

                    <?php echo csrf_field(); ?>
                    <div class="col-md-offset-2 col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplierId"><?php echo app('translator')->get('label.SUPPLIER'); ?> </label>
                            <div class="col-md-8">
                                <?php echo Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                            </button>
                        </div>
                    </div>

                    <?php echo Form::close(); ?>

                    <!-- End Filter -->
                </div>
            </div>
            <?php if($request->generate == 'true'): ?>
            <div class="row" id="divHide">
                <?php echo Form::open(array('group' => 'form', 'url' => '#','id'=>'previewForm','class' => 'form-horizontal')); ?>

                <?php echo Form::hidden('supplier_id',$request->supplier_id); ?> 
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center vcenter" rowspan="3"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter" rowspan="3"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                    <th class="vcenter" rowspan="3"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                    <th class="text-center vcenter"colspan="14"><?php echo app('translator')->get('label.SHIPMENT'); ?></th>
                                </tr>
                                <tr>
                                    <!--<th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CHECK'); ?></th>-->
                                    <th class="vcenter" colspan="2" rowspan="2"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.GSM'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.TOTAL_QUANTITY'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RECEIVED_QUANTITY'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RECEIVED_TOTAL_PRICE'); ?></th>
                                    <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.COMMISSION'); ?></th>
                                    <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?></th>
                                </tr>

                                <tr>
                                    <th class="vcenter"><?php echo app('translator')->get('label.KONITA_COMMISSION'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRINCIPLE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.KONITA_COMMISSION'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRINCIPLE'); ?></th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php if(!empty($billingArr)): ?>
                                <?php
                                $sl = 0;
                                ?>
                                <?php $__currentLoopData = $billingArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $rowspan = !empty($rowspanOrder[$inquiryId]) ? $rowspanOrder[$inquiryId] : 0;
                                $iconCmsn = $commissionSet = '';

                                if (array_key_exists($inquiryId, $commissionArr)) {
                                    $iconCmsn = '<br/><span class="badge badge-primary tooltips"'
                                            . 'title="' . __('label.COMMISSION_ALREADY_SET') . '"><i class="fa fa-usd"></i></span>';
                                } else {
                                    //commission Set
                                    if (!empty($userAccessArr[41][18])) {
                                        $commissionSet = '<br/><button class="btn btn-xs yellow-mint  btn-circle btn-rounded tooltips commission-setup-modal vcenter"'
                                                . ' href="#commissionSetUpModal"  data-toggle="modal" title="' . __('label.COMMISSION_SETUP') . '" 
                                            data-inquiry-id ="' . $inquiryId . '" type="button">
                                        <i class="fa fa-sitemap"></i>
                                    </button>';
                                    }
                                }
                                ?>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo ++$sl; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo $target['order_no'].$commissionSet.$iconCmsn; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo e($target['buyer_name']); ?></td>

                            <?php
                            $i = 0;
                            ?>
                            <?php $__currentLoopData = $billingArr2[$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId=> $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }

                            $rowspanBl = !empty($rowspanArr[$inquiryId][$deliveryId]) ? $rowspanArr[$inquiryId][$deliveryId] : 0;

                            $shipmentQty = !empty($sipmentQtyArr[$deliveryId]) ? $sipmentQtyArr[$deliveryId] : 0;
                            $totalKonitaCmsn = !empty($totalKonitaCmsnArr[$deliveryId]) ? $totalKonitaCmsnArr[$deliveryId] : 0;
                            $totalPrincipleCmsn = !empty($totalPrincipleCmsnArr[$deliveryId]) ? $totalPrincipleCmsnArr[$deliveryId] : 0;
                            ?>

                            <td class="text-center vcenter" rowspan="<?php echo e($rowspanBl); ?>">
                                <div class="md-checkbox has-success">
                                    <?php echo Form::checkbox('checkbox['.$inquiryId.']['.$deliveryId.']', null,false, ['id' => $deliveryId,'data-qty'=>$shipmentQty,'data-id'=> $deliveryId,'data-totalKonitaCmsn'=>$totalKonitaCmsn,'data-totalPrincipleCmsn'=>$totalPrincipleCmsn,'class'=> 'md-check sp-check']); ?>

                                    <label for="<?php echo $deliveryId; ?>">
                                        <span class="inc checkbox-text-center"></span>
                                        <span class="check mark-caheck checkbox-text-center"></span>
                                        <span class="box mark-caheck checkbox-text-center"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowspanBl); ?>">
                                <?php echo Form::text('bl_no['.$inquiryId.']['.$deliveryId.']',!empty($item['bl_no'])?$item['bl_no']:null, ['id'=> 'blNo'.$deliveryId, 'class' => 'form-control w-200','readonly']); ?> 
                            </td>
                            <?php
                            $j = 0;
                            ?>
                            <?php $__currentLoopData = $item['bl_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryDetailsId=>$deliveryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            $totalKonitaComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn'] : 0;
                            $totalPrincipleComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_principal_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_principal_cmsn'] : 0;

                            $konitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                            $principalCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['principal_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['principal_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['principal_cmsn']) ? $prevComsnArr[$inquiryId][0]['principal_cmsn'] : 0));
                            $companyKonitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['company_konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['company_konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['company_konita_cmsn'] : 0));
                            $salesPersonCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['sales_person_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][0]['sales_person_cmsn'] : 0));
                            $buyerCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['buyer_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][0]['buyer_cmsn'] : 0));
                            $rebateCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['rebate_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][0]['rebate_cmsn'] : 0));

                            $comsnBreakdownTitle = 'Konita:$' . $companyKonitaCmsn
                                    . '&#13;Principal:$' . $principalCmsn
                                    . '&#13;Salesperson:$' . $salesPersonCmsn
                                    . '&#13;Buyer:$' . $buyerCmsn
                                    . '&#13;Rebate:$' . $rebateCmsn;
                            ?>
                            <td>
                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][company_konita_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['company_konita_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['company_konita_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_company_konita_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_company_konita_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_company_konita_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][sales_person_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['sales_person_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['sales_person_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_sales_person_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_sales_person_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_sales_person_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][buyer_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['buyer_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['buyer_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_buyer_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_buyer_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_buyer_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][rebate_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['rebate_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['rebate_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_rebate_cmsn]',!empty($shipmentComsnArr[$deliveryDetailsId]['total_rebate_cmsn'])?$shipmentComsnArr[$deliveryDetailsId]['total_rebate_cmsn']:null); ?>

                                <?php echo Form::hidden('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][measure_unit_id]',!empty($deliveryDetails['measure_unit_id'])?$deliveryDetails['measure_unit_id']:null); ?>


                                <?php echo e(!empty($deliveryDetails['product_name'])?$deliveryDetails['product_name']:''); ?>

                            </td>
                            <td>
                                <?php echo e(!empty($deliveryDetails['brand_name'])?$deliveryDetails['brand_name']:''); ?>

                            </td>
                            <td>
                                <?php echo e(!empty($deliveryDetails['grade_name'])?$deliveryDetails['grade_name']:''); ?>

                            </td>
                            <td>
                                <?php echo e(!empty($deliveryDetails['gsm'])?$deliveryDetails['gsm']:''); ?>

                            </td>
                            <td class="text-right vcenter">
                                <?php echo e(!empty($deliveryDetails['total_quantity'])?$deliveryDetails['total_quantity']:0); ?>&nbsp;<?php echo e(!empty($deliveryDetails['unit_name'])?$deliveryDetails['unit_name']:''); ?>

                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-150">
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipmentQty]',!empty($deliveryDetails['shipment_qty'])?$deliveryDetails['shipment_qty']:null, ['id'=> 'shipmentQty'.$deliveryDetailsId, 'data-shipmentQty'=> $deliveryDetailsId, 'class' => 'form-control  shipment_qty text-right','readonly']); ?>

                                    <span class="input-group-addon bootstrap-touchspin-prefix bold"><?php echo e(!empty($deliveryDetails['unit_name'])?$deliveryDetails['unit_name']:''); ?></span>
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-150">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][unit_price]',!empty($deliveryDetails['unit_price'])?$deliveryDetails['unit_price']:null, ['id'=> 'unitPrice'.$deliveryDetailsId, 'class' => 'form-control  shipment_qty text-right','readonly']); ?>

                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">
                                        <?php echo e(!empty($deliveryDetails['unit_name'])?'/'.' '.$deliveryDetails['unit_name']:''); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][shipment_total_price]',!empty($deliveryDetails['total_price'])?$deliveryDetails['total_price']:null, ['id'=> 'shipmentTotalPrice'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']); ?> 
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][konita_cmsn]',$konitaCmsn, ['id'=> 'konitaCmsn'.$deliveryDetailsId
                                    ,'title'=> $comsnBreakdownTitle
                                    ,'class' => 'form-control text-right tooltips','readonly']); ?> 
                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][principle_cmsn]',$principalCmsn, ['id'=> 'principleCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']); ?> 
                                </div>
                            </td>

                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_konita_cmsn]',$totalKonitaComsn, ['id'=> 'totalKonitaCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']); ?>

                                </div>
                            </td>
                            <td class="text-right vcenter">
                                <div class="input-group bootstrap-touchspin w-110">
                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                    <?php echo Form::text('deliveryArr['.$inquiryId.']['.$deliveryId.']['.$deliveryDetailsId.'][total_principle_cmsn]',$totalPrincipleComsn, ['id'=> 'totalPrincipleCmsn'.$deliveryDetailsId, 'class' => 'form-control text-right','readonly']); ?>

                                </div>
                            </td>
                            <?php
                            $j++;
                            ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $i++;
                            ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.TOTAL'); ?></td>
                                <td class="vcenter bold" colspan="5">
                                    <span id="totalQty">0</span>
                                </td>
                                <td class="vcenter bold">$
                                    <span id="totalKonitaCmsnId">0</span>
                                    <input type="hidden" name="total_konita_cmsn" value="" id="totalKonitaCmsnInput"/>
                                </td>
                                <td class="vcenter bold">$
                                    <span id="totalPrincipleCmsnId">0</span>
                                    <input type="hidden" name="total_principle_cmsn" value="" id="totalPrincipleCmsnInput"/>
                                </td>
                            </tr>
<!--                            <tr>
                                <td class="vcenter text-right bold" colspan="12"><?php echo app('translator')->get('label.GIFT'); ?></td>
                                <td class="vcenter">
                                    <?php echo Form::text('gift_title',null, ['id'=> 'giftTitle','class' => 'form-control tooltips','placeholder'=>__('label.TITLE'),'title'=>__('label.TITLE')]); ?>

                                </td>
                                <td class="vcenter text-right bold" colspan="2">
                                    <div class="input-group bootstrap-touchspin">
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                        <?php echo Form::text('gift',null, ['id'=> 'gift', 'class' => 'form-control integer-decimal-only text-right','autocomplete'=>'off']); ?>

                                    </div>
                                </td>
                            </tr>-->
                            <tr>
                                <td class="vcenter text-right bold" colspan="15"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></td>
                                <td class="vcenter bold" colspan="2">$
                                    <span id="netReceivableId">0</span>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td colspan="18"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(!empty($billingArr)): ?>
                    <div class="col-md-4 margin-top-20">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="bankId"><?php echo app('translator')->get('label.BANK'); ?>: <span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('konita_bank_id',  $konitaBankList, null, ['class' => 'form-control js-source-states','id'=>'bankId']); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
                <!--submit button-->
                <div class="col-md-12">
                    <div class="col-md-offset-4 col-md-8">
                        <?php if(!empty($billingArr)): ?>
                        <button class="btn btn-circle green" href="#previewModal" type="button" data-toggle="modal" id="submitPreview">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SAVE_AND_PREVIEW'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('billing/billingCreate')); ?>" class="btn btn-circle btn-outline grey-salsa">
                            <i class="fa fa-close"></i> <?php echo app('translator')->get('label.CANCEL'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>
            <?php endif; ?>
        </div>	
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="previewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showPreviewModal">
        </div>
    </div>
</div>

<!-- Start commissionSetUpModal-->
<div class="modal fade" id="commissionSetUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowcommissionSetUpModal">
        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {

        //TOTAL QTY&KONITA CMSN & PRINCIPLE CMSN SCRIPT
        $('.sp-check').on("change", function () {
            var deliveryId = $(this).attr('data-id');
            var sum = 0;
            var totalKonitaCmsn = 0;
            var totalPrincipleCmsn = 0;
            // var gift = $('#gift').val(); //gift value
            var netReceivable = 0;

            $(".sp-check").each(function (index) {
                if ($(this).is(":checked")) {
                    sum += parseFloat($(this).attr("data-qty"));
                    totalKonitaCmsn += parseFloat($(this).attr("data-totalkonitacmsn"));
                    totalPrincipleCmsn += parseFloat($(this).attr("data-totalPrincipleCmsn"));
                }
            });

            $('#totalQty').html(sum);
            $('#totalKonitaCmsnId').html(totalKonitaCmsn);
            $('#totalPrincipleCmsnId').html(totalPrincipleCmsn);

            $('#totalKonitaCmsnInput').val(totalKonitaCmsn);
            $('#totalPrincipleCmsnInput').val(totalPrincipleCmsn);

            //total konita commission
            netReceivable = (totalKonitaCmsn - totalPrincipleCmsn);
            $('#netReceivableId').html(netReceivable.toFixed(2));

        });


//        $(document).keyup('#gift', function () {
//            var gift = $('#gift').val();
//            var totalKonitaCmsnVal = $('#totalKonitaCmsnInput').val();
//            var totalPrincipleCmsnVal = $('#totalPrincipleCmsnInput').val();
//
//            var netReceivable = 0;
//            netReceivable = (totalKonitaCmsnVal - totalPrincipleCmsnVal - gift);
//
//            $('#netReceivableId').html(netReceivable.toFixed(2));
//
//        });

        //ENDOF SUM cSCRIPT



        //buyer and Sales Person under product**
        $(document).on('change', '#supplierId', function (e) {
            $('#divHide').html('');
        });

        //preview submit form function
        $(document).on("click", "#submitPreview", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#previewForm')[0]);
            $.ajax({
                url: "<?php echo e(URL::to('billing/billingPreviewData')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showPreviewModal").html('');
                },
                success: function (res) {
                    $("#showPreviewModal").html(res.html);
                    $(".js-source-states").select2({dropdownParent: $('#showPreviewModal'), width: '100%'});
                    $(".js-source-states").select2({});
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }

                    $("#showPreviewModal").html('');
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof preview form


        //invoice save submit form function
        $(document).on("click", "#submitInvoiceSave", function (e) {

            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#invoiceSaveForm')[0]);
            $('#submitInvoiceSave').prop('disabled', true);
            $.ajax({
                url: "<?php echo e(URL::to('billing/billingInvoiceStore')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#submitInvoiceSave').prop('disabled', true);
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(
                            window.location.replace('<?php echo e(URL::to("billing/billingLedgerView")); ?>'
                                    ), 1000);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $('#submitInvoiceSave').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });
        //endof invoce save form

        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('inquiry-id');

            $.ajax({
                url: "<?php echo e(URL::to('billing/getCommissionSetupModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                    $("#ShowcommissionSetUpModal").html('');
                },
                success: function (res) {
                    $("#ShowcommissionSetUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowcommissionSetUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //After Click to Save new commission 
        $(document).on("click", "#cmsnSaveBtn", function (e) {
            e.preventDefault();
            var formData = new FormData($('#cmsnSubmitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('billing/commissionSetupSave')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#cmsnSaveBtn').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#cmsnSaveBtn').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof commission setup modal

    });
</script>    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/index.blade.php ENDPATH**/ ?>