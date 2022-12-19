<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.SALES_VOLUME_REPORT'); ?>
            </div>
            <div class="actions">
                <span class="text-right">
                    <?php if(Request::get('generate') == 'true'): ?>
                    <?php if(!$targetArr->isEmpty()): ?>
                    <?php if(!empty($userAccessArr[49][6])): ?>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=print')); ?>"  title="<?php echo app('translator')->get('label.PRINT'); ?>">
                        <i class="fa fa-print"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[49][9])): ?>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=pdf')); ?>"  title="<?php echo app('translator')->get('label.DOWNLOAD'); ?>">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    <?php endif; ?>
                    <button class="btn green-seagreen btn-sm btn-chart-view tooltips" type="button" data-placement="left" title="<?php echo app('translator')->get('label.CLICK_TO_SEE_GRAPHICAL_VIEW'); ?>">
                        <i class="fa fa-line-chart"></i>
                    </button>
                    <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="<?php echo app('translator')->get('label.CLICK_TO_SEE_TABULAR_VIEW'); ?>">
                        <i class="fa fa-list"></i><!--
                    </button>-->
                        <?php endif; ?> 
                        <?php endif; ?> 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'salesVolumeReport/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('pi_from_date', Request::get('pi_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('pi_from_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('pi_to_date', Request::get('pi_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('pi_to_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="salespersonsId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('salespersons_id')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId"><?php echo app('translator')->get('label.BUYER'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId"><?php echo app('translator')->get('label.PRODUCT'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId"><?php echo app('translator')->get('label.BRAND'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId"><?php echo app('translator')->get('label.SUPPLIER'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('supplier_id', $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <?php if(Request::get('generate') == 'true'): ?>
            <div class="row tabular-view">

                <!--SUMMARY-->
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.COMMISSION_BREAKDOWN'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.KONITA_CMSN'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.KONITA_CMSN'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['konita_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['principal_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['principal_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['sales_person_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['sales_person_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.BUYER_COMMISSION'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.BUYER_COMMISSION'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['buyer_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['buyer_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.REBATE_COMMISSION'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.REBATE_COMMISSION'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['rebate_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['rebate_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?>">
                                        <!--LC Transnitted Yes-->
                                        <td><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?></td>
                                        <td class="text-right">$<?php echo e(Helper::numberFormat2Digit($lcTransmitted)); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.LC_NOT_TRANSMITTED'); ?>">
                                        <!--LC Transnitted No-->
                                        <td><?php echo app('translator')->get('label.LC_NOT_TRANSMITTED'); ?></td>
                                        <td class="text-right">$<?php echo e(Helper::numberFormat2Digit($notLcTransmitted)); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter" colspan="2"><?php echo app('translator')->get('label.INCOME_BREAKDOWN'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_VOLUME'); ?>">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_VOLUME'); ?></td>
                                        <td class="text-right"><?php echo e(Helper::numberFormat2Digit($totalSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_AMOUNT'); ?>">
                                        <!--Total Sales Amount-->
                                        <td><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_AMOUNT'); ?></td>
                                        <td class="text-right">$<?php echo e(Helper::numberFormat2Digit($totalSalesAmount)); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.KONITA_CMSN'); ?>+<?php echo app('translator')->get('label.REBATE_COMMISSION'); ?>">
                                        <!--Total Konita Net Commission-->
                                        <td><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.KONITA_NET_CMSN'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['total_konita_net_csmn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_net_csmn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.KONITA_CMSN'); ?> + <?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?>
                                        + <?php echo app('translator')->get('label.BUYER_COMMISSION'); ?> + <?php echo app('translator')->get('label.REBATE_COMMISSION'); ?>">
                                        <!--Total Konita Commission-->
                                        <td><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.KONITA_CMSN'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['total_konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?>">
                                        <!--Total Admin cost-->
                                        <td><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.ADMIN_COST'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['total_admin_cost']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost']) : '0.00'); ?></td>
                                    </tr>

                                    <tr class="tooltips" title="<?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?> = <?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.KONITA_CMSN'); ?>
                                        + <?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.ADMIN_COST'); ?>">
                                        <!--Total Commission-->
                                        <td><?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?></td>
                                        <td class="text-right">$<?php echo e(!empty($comsnIncomeArr['total_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn']) : '0.00'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <?php if(!empty($countryWiseAccount)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <th class="vcenter text-center"><?php echo app('translator')->get('label.COUNTRY'); ?></th>
                                        <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_VOLUME'); ?></th>
                                        <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_AMOUNT'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $countryWiseAccount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $countryId=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="vcenter text-center">
                                            <?php echo e(!empty($countryList[$countryId])?$countryList[$countryId]:''); ?>

                                        </td>
                                        <td class="vcenter text-right">
                                            <?php echo e(!empty($val['total_sales_volyme'])?Helper::numberFormat2Digit($val['total_sales_volyme']):0); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?>
                                        </td>
                                        <td class="vcenter text-right">
                                            $<?php echo e(!empty($val['total_sales_amount'])?Helper::numberFormat2Digit($val['total_sales_amount']):0); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div> 
                        <?php endif; ?>
                    </div>

                </div>
                <!--END OF SUMMARY-->


                <div class="col-md-12">
                    <div class="tableFixHead max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SUPPLIER'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?></th>
                                    <?php if(!empty($userAccessArr[49][5])): ?>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SHIPMENT_DETAILS'); ?></th>
                                    <?php endif; ?>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SALES_VOLUME'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SALES_AMOUNT'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.KONITA_CMSN'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.ADMIN_COST'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$targetArr->isEmpty()): ?>
                                <?php
                                $sl = 0;
                                $totalSalesVolume = 0;
                                $totalSalesAmount = 0;
                                $totalKonitaCmsn = 0;
                                $totalAdminCost = 0;
                                $totalCmsn = 0;
                                ?>
                                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $netCmsn = !empty($profitArr[$target->id]['net_commission']) ? $profitArr[$target->id]['net_commission'] : 0;
                                $expenditureCmsn = !empty($profitArr[$target->id]['expenditure']) ? $profitArr[$target->id]['expenditure'] : 0;
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo ++$sl; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                        <?php echo $target->order_no; ?>

                                        <?php if($expenditureCmsn > $netCmsn): ?>
                                        <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle red-soft tooltips" title="<?php echo app('translator')->get('label.NO_PROFIT'); ?>">

                                        </button>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->purchase_order_no; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->buyerName; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo !empty($supplierList[$target->supplier_id]) ? $supplierList[$target->supplier_id] : ''; ?></td>
                                    <td class="vcenter text-center" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                        <?php if($target->lc_transmitted_copy_done == '1'): ?>
                                        <span class="label label-sm label-info"><?php echo app('translator')->get('label.YES'); ?></span>
                                        <?php elseif($target->lc_transmitted_copy_done == '0'): ?>
                                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(!empty($userAccessArr[49][5])): ?>
                                    <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                        <?php if(in_array($target->order_status, ['2', '3', '4'])): ?>
                                        <?php if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr)): ?>
                                        <?php $__currentLoopData = $deliveryArr[$target->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId => $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <button class="btn btn-xs <?php echo e($delivery['btn_color']); ?> btn-circle <?php echo e($delivery['btn_rounded']); ?> tooltips vcenter shipment-details" data-html="true" 
                                                title="
                                                <div class='text-left'>
                                                <?php echo app('translator')->get('label.BL_NO'); ?>: &nbsp;<?php echo $delivery['bl_no']; ?><br/>
                                                <?php echo app('translator')->get('label.STATUS'); ?>: &nbsp;<?php echo $delivery['status']; ?><br/>
                                                <?php echo app('translator')->get('label.PAYMENT_STATUS'); ?>: &nbsp;<?php echo $delivery['payment_status']; ?><br/>
                                                <?php echo app('translator')->get('label.CLICK_TO_SEE_DETAILS'); ?>
                                                </div>
                                                " 
                                                href="#modalShipmentDetails" data-id="<?php echo $deliveryId; ?>" data-toggle="modal">
                                            <i class="fa fa-<?php echo e($delivery['icon']); ?>"></i>
                                        </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-xs cursor-default btn-circle red-soft tooltips vcenter" title="<?php echo app('translator')->get('label.NO_SHIPMENT_YET'); ?>">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array($target->order_status, ['0', '1'])): ?>
                                        <button type="button" class="btn btn-xs cursor-default btn-circle grey-cascade tooltips vcenter" title="<?php echo app('translator')->get('label.NOT_MATURED_YET'); ?>">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>

                                    <?php if(!empty($target->inquiryDetails)): ?>
                                    <?php $i = 0; ?>
                                    <?php $__currentLoopData = $target->inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId=> $productData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['product']); ?>">
                                        <?php echo e(!empty($productArr[$productId])?$productArr[$productId]:''); ?>

                                    </td>
                                    <?php if(!empty($productData)): ?>
                                    <?php $j = 0; ?>
                                    <?php $__currentLoopData = $productData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandId=> $brandData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['brand']); ?>">
                                        <?php echo e(!empty($brandArr[$brandId])?$brandArr[$brandId]:''); ?>

                                    </td>
                                    <?php if(!empty($brandData)): ?>
                                    <?php $k = 0; ?>
                                    <?php $__currentLoopData = $brandData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeId=> $gradeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($k > 0) {
                                        echo '<tr>';
                                    }
                                    $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                                    ?>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['grade']); ?>">
                                        <?php echo e(!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''); ?>

                                    </td>
                                    <?php if(!empty($gradeData)): ?>
                                    <?php $l = 0; ?>
                                    <?php $__currentLoopData = $gradeData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gsm=> $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($l > 0) {
                                        echo '<tr>';
                                    }

                                    $totalSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                    $totalSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                    $konitaCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn'] : (!empty($commissionArr[$target->id][0]['total_konita_cmsn']) ? $commissionArr[$target->id][0]['total_konita_cmsn'] : 0));
                                    $adminCost = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost'] : (!empty($commissionArr[$target->id][0]['admin_cost']) ? $commissionArr[$target->id][0]['admin_cost'] : 0));
                                    $totalCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn'] : (!empty($commissionArr[$target->id][0]['total_cmsn']) ? $commissionArr[$target->id][0]['total_cmsn'] : 0));
                                    ?>
                                    <td class="vcenter"><?php echo e(!empty($item['gsm']) ? $item['gsm'] : ''); ?></td>
                                    <td class="vcenter text-right"><?php echo e(Helper::numberFormat2Digit($item['sales_volume'])); ?>&nbsp;<?php echo e($item['unit_name']); ?></td>
                                    <td class="vcenter text-right">$<?php echo e(Helper::numberFormat2Digit($item['sales_amount'])); ?></td>
                                    <td class="vcenter text-right">$<?php echo e(Helper::numberFormat2Digit($konitaCommission)); ?></td>
                                    <td class="vcenter text-right">$<?php echo e(Helper::numberFormat2Digit($adminCost)); ?></td>
                                    <td class="vcenter text-right">$<?php echo e(Helper::numberFormat2Digit($totalCommission)); ?></td> 

                                    <?php
                                    if ($l < ($rowSpan['grade'] - 1)) {
                                        echo '</tr>';
                                    }

                                    $i++;
                                    $j++;
                                    $k++;
                                    $l++;
                                    ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="bold text-right" colspan="<?php echo e(!empty($userAccessArr[49][5]) ? 11 : 10); ?>"><?php echo app('translator')->get('label.TOTAL'); ?></td>
                                    <td class="bold text-right"><?php echo e(Helper::numberFormat2Digit($totalSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                    <td class="bold text-right">$<?php echo e(Helper::numberFormat2Digit($totalSalesAmount)); ?></td>
                                    <td class="bold text-right">$<?php echo e(Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn'])); ?></td>
                                    <td class="bold text-right">$<?php echo e(Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost'])); ?></td>
                                    <td class="bold text-right">$<?php echo e(Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn'])); ?></td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td colspan="<?php echo e(!empty($userAccessArr[49][5]) ? 16 : 15); ?>" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row chart-view">
                <div class="col-md-6">
                    <div id="countryWiseSalesVolumeChart" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="countryWiseSalesAmountChart" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="commissionBreakdownPie" class="chart-block"></div>
                </div>
                <div class="col-md-6">
                    <div id="incomeBreakdownChart" class="chart-block"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>



<script src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
//default setting
    $(".btn-chart-view").show();
    $(".btn-tabular-view").hide();
    $(".btn-print").show();
    $(".btn-pdf").show();
    $(".chart-view").hide();
    $(".tabular-view").show();
//when click tabular view button
    $(document).on("click", ".btn-tabular-view", function () {
        $(".btn-chart-view").show();
        $(".btn-tabular-view").hide();
        $(".btn-print").show();
        $(".btn-pdf").show();
        $(".chart-view").hide();
        $(".tabular-view").show();
    });
//when click graphical view button
    $(document).on("click", ".btn-chart-view", function () {
        $(".btn-chart-view").hide();
        $(".btn-tabular-view").show();
        $(".btn-print").hide();
        $(".btn-pdf").hide();
        $(".chart-view").show();
        $(".tabular-view").hide();
    });
//table header fix
    $("#fixTable").tableHeadFixer();
//        $('.sample').floatingScrollbar();

//shipment details modal
    $(".shipment-details").on("click", function (e) {
        e.preventDefault();
        var shipmentId = $(this).attr("data-id");
        $.ajax({
            url: "<?php echo e(URL::to('/salesVolumeReport/getShipmentDetails')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                shipment_id: shipmentId
            },
            success: function (res) {
                $("#showShipmentDetails").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });
    var colors = ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE'
                , '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3'
                , '#E87E04', '#D91E18', '#8E44AD', '#555555'];
//***************start :: country wise sales volume chart**********
    var countryWiseSalesVolumeChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "<?php echo app('translator')->get('label.SALES_VOLUME'); ?>",
                data: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $volume = $val['total_sales_volyme'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "<?php echo app('translator')->get('label.SALES_VOLUME'); ?> (<?php echo app('translator')->get('label.COUNTRY_WISE'); ?>)",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $country = $countryList[$countryId] ?? 0;
        echo '"' . $country . '", ';
    }
}
?>
            ],
            title: {
                text: "<?php echo app('translator')->get('label.COUNTRY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.VOLUME'); ?> (<?php echo app('translator')->get('label.UNIT'); ?>)"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  parseFloat(val).toFixed(2) + "<?php echo app('translator')->get('label.UNIT'); ?>"
                }
            }
        }
    };
    var countryWiseSalesVolumeChart = new ApexCharts(document.querySelector("#countryWiseSalesVolumeChart"), countryWiseSalesVolumeChartOptions);
    countryWiseSalesVolumeChart.render();
//***************end :: country wise sales volume chart**********

//***************start :: country wise sales amount chart**********
    var countryWiseSalesAmountChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "<?php echo app('translator')->get('label.SALES_AMOUNT'); ?>",
                data: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $amount = $val['total_sales_amount'] ?? 0;
        echo "'$amount',";
    }
}
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "<?php echo app('translator')->get('label.SALES_AMOUNT'); ?> (<?php echo app('translator')->get('label.COUNTRY_WISE'); ?>)",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
<?php
if (!empty($countryWiseAccount)) {
    foreach ($countryWiseAccount as $countryId => $val) {
        $country = !empty($countryList[$countryId]) ? $countryList[$countryId] : '';
        echo '"' . $country . '", ';
    }
}
?>
            ],
            title: {
                text: "<?php echo app('translator')->get('label.COUNTRY'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.AMOUNT'); ?> ($)"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + parseFloat(val).toFixed(2)
                }
            }
        }
    };
    var countryWiseSalesAmountChart = new ApexCharts(document.querySelector("#countryWiseSalesAmountChart"), countryWiseSalesAmountChartOptions);
    countryWiseSalesAmountChart.render();
//***************end :: country wise sales volume chart**********


//***************start :: income breakdown chart**********
    var incomeBreakdownChartOptions = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: "<?php echo app('translator')->get('label.AMOUNT'); ?>",
                data: [
<?php
$totalKonitaNetComsn = $comsnIncomeArr['total_konita_net_csmn'] ?? 0;
$totalKonitaComsn = $comsnIncomeArr['total_konita_cmsn'] ?? 0;
$totalAdminCost = $comsnIncomeArr['total_admin_cost'] ?? 0;
$totalComsn = $comsnIncomeArr['total_cmsn'] ?? 0;
echo $totalKonitaNetComsn . ', ' . $totalKonitaComsn . ', ' . $totalAdminCost . ', ' . $totalComsn;
?>
                ]
            }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            },
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function (val) {
                return parseFloat(val).toFixed(2)
            },
            textAnchor: 'middle',
            distributed: true,
            offsetX: 0,
            offsetY: -10,
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: colors,
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        legend: {
            show: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: "<?php echo app('translator')->get('label.INCOME_BREAKDOWN'); ?>",
            align: 'left'
        },
        xaxis: {
            labels: {
                show: true,
                rotate: -60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                    return trimString(val);
                },
                format: undefined,
            },
            categories: [
                "<?php echo app('translator')->get('label.KONITA_NET_CMSN'); ?>", "<?php echo app('translator')->get('label.KONITA_CMSN'); ?>", "<?php echo app('translator')->get('label.ADMIN_COST'); ?>", "<?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?>",
            ],
            title: {
                text: "<?php echo app('translator')->get('label.BREAKDOWN'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 700,
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.AMOUNT'); ?> ($)"
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.20,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 1.85,
                stops: [85, 50, 100]
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + parseFloat(val).toFixed(2)
                }
            }
        }
    };
    var incomeBreakdownChart = new ApexCharts(document.querySelector("#incomeBreakdownChart"), incomeBreakdownChartOptions);
    incomeBreakdownChart.render();
//***************end :: income breakdown chart**********commissionBreakdownPie


//***************start :: commission breakdown chart**********
    var commissionBreakdownPieOptions = {
        series: [
<?php
$conitaComsn = $comsnIncomeArr['konita_cmsn'] ?? 0;
$salesPersonComsn = $comsnIncomeArr['sales_person_cmsn'] ?? 0;
$buyerComsn = $comsnIncomeArr['buyer_cmsn'] ?? 0;
$rebateComsn = $comsnIncomeArr['rebate_cmsn'] ?? 0;
$principalComsn = $comsnIncomeArr['principal_cmsn'] ?? 0;
echo $conitaComsn . ', ' . $salesPersonComsn . ', ' . $buyerComsn . ', ' . $rebateComsn . ', ' . $principalComsn;
?>
        ],
        labels: ["<?php echo app('translator')->get('label.KONITA_CMSN'); ?>", "<?php echo app('translator')->get('label.SALES_PERSON_COMMISSION'); ?>"
                    , "<?php echo app('translator')->get('label.BUYER_COMMISSION'); ?>", "<?php echo app('translator')->get('label.REBATE_COMMISSION'); ?>"
                    , "<?php echo app('translator')->get('label.PRINCIPLE_COMMISSION'); ?>"],
        chart: {
            width: 500,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#525E64"],
        title: {
            text: "<?php echo app('translator')->get('label.COMMISSION_BREAKDOWN'); ?>",
            align: 'left'
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            fontSize: '11px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 600,
            formatter: function (val, opts) {
                var indx = opts.w.globals.series[opts.seriesIndex];
                return val + ': $' + parseFloat(indx).toFixed(2)
            },
            labels: {
                colors: ['#FFFFFF'],
                useSeriesColors: true
            },
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: '#fff',
                fillColors: [],
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  '$' + parseFloat(val).toFixed(2)
                },
            }
        },
        responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
    };
    var commissionBreakdownPie = new ApexCharts(document.querySelector("#commissionBreakdownPie"), commissionBreakdownPieOptions);
    commissionBreakdownPie.render();
//***************end :: commission breakdown chart**********commissionBreakdownPie

});
function trimString(str) {
    var dot = '';
    if (str.length > 20) {
        dot = '...';
    }

    var returnStr = str.substring(0, 20) + dot;
    return returnStr;
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/report/salesVolume/index.blade.php ENDPATH**/ ?>