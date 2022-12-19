<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.CONFIRMED_ORDER_LIST'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'confirmedOrder/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

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
                        <label class="control-label col-md-4" for="salespersonsId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('salespersons_id')); ?></span>
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
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId"><?php echo app('translator')->get('label.BRAND'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo"><?php echo app('translator')->get('label.ORDER_NO'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('order_no', $uniqueNoArr, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="purchaseOrderNo"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('purchase_order_no', $purchaseOrderNoArr, Request::get('purchase_order_no'), ['class' => 'form-control js-source-states','id'=>'purchaseOrderNo']); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="lcNo"><?php echo app('translator')->get('label.LC_NO'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::text('lc_no',  Request::get('lc_no'), ['class' => 'form-control tooltips', 'title' => __('label.LC_NO'), 'placeholder' => __('label.LC_NO'), 'list'=>'lcNo', 'autocomplete'=>'off']); ?> 
                            <datalist id="lcNo">
                                <?php if(!$lcNoArr->isEmpty()): ?>
                                <?php $__currentLoopData = $lcNoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lc->lc_no); ?>"></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                        </div>
                    </div>  
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->

            <!--product wise Quantity Summary-->
            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <button class="btn btn-sm blue-soft  tooltips vcenter" href="#quantitySummaryModal" id="quantitySummary"
                            data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.PRODUCT_WISE_TOTAL_QUANTITY'); ?>"
                            data-product_id="<?php echo e($request->product_id); ?>" data-brand_id="<?php echo e($request->brand_id); ?>"
                            data-buyer_id="<?php echo e($request->buyer_id); ?>" data-order_no="<?php echo e($request->order_no); ?>"
                            data-from_date="<?php echo e($request->from_date); ?>" data-to_date="<?php echo e($request->to_date); ?>"
                            data-purchase_order_no="<?php echo e($request->purchase_order_no); ?>" data-lc_no="<?php echo e($request->lc_no); ?>"
                            data-salespersons_id="<?php echo e($request->salespersons_id); ?>">
                        <i class="fa fa-balance-scale"></i> <span class="bold"><?php echo app('translator')->get('label.PRODUCT_WISE_TOTAL_QUANTITY'); ?></span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BUYER'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.CREATION_DATE'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.PI_DATE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.LC_NO'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.LC_DATE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.LC_ISSUE_DATE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <?php if(!empty($userAccessArr[27][5])): ?>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SHIPMENT_DETAILS'); ?></th>
                            <?php endif; ?>
                            <th class="td-actions text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $iconCAS = '';
                        $btnColorCAS = 'yellow-mint';
                        if (!empty($commissionAlreadySetList)) {
                            if (in_array($target->id, $commissionAlreadySetList)) {
                                $iconCAS = '<br/><span class="badge badge-primary tooltips" title="' . __('label.COMMISSION_ALREADY_SET') . '"><i class="fa fa-usd"></i></span>';
                                $btnColorCAS = 'yellow-gold';
                            }
                        }
                        $iconFH = '';
                        if (!empty($hasFollowupList)) {
                            if (in_array($target->id, $hasFollowupList)) {
                                $iconFH = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips followup-history vcenter"'
                                        . ' href="#followUpModal"  data-toggle="modal" title="' . __('label.VIEW_FOLLOWUP_HISTORY') . '" 
                                            data-inquiry-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                            }
                        }
                        $iconMsg = '';
                        if (!empty($hasMessageList)) {
                            if (in_array($target->id, $hasMessageList)) {
                                $iconMsg = '<br/><i class="fa fa-comment text-blue-madison" ></i>';
                            }
                        }
                        //inquiry rowspan
                        $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                        ?>
                        <tr>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo ++$sl.$iconCAS.$iconFH.$iconMsg; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->order_no; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->purchase_order_no; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->buyerName; ?></td>
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
                            ?>
                            <td class="vcenter"><?php echo e(!empty($gsm)?$gsm:''); ?></td>
                            <td class="vcenter text-right"><?php echo e($item['quantity']); ?>&nbsp;<?php echo e($item['unit_name']); ?></td>

                            <?php if($i == 0 && $j == 0 && $k == 0): ?>
                            <!--:::::::: rowspan part :::::::-->
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo Helper::formatDate($target->creation_date); ?></td>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo !empty($target->pi_date)?Helper::formatDate($target->pi_date):''; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->lc_no; ?></td>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <?php echo !empty($target->lc_date) ? Helper::formatDate($target->lc_date) : ''; ?>

                            </td>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <?php if($target->lc_transmitted_copy_done == '1'): ?>
                                <span class="label label-sm label-info"><?php echo app('translator')->get('label.YES'); ?></span>
                                <?php elseif($target->lc_transmitted_copy_done == '0'): ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <?php echo !empty($target->lc_issue_date)?Helper::formatDate($target->lc_issue_date):''; ?>

                            </td>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <?php if($target->order_status == '2'): ?>
                                <span class="label label-sm label-primary"><?php echo app('translator')->get('label.CONFIRMED'); ?></span>
                                <?php elseif($target->order_status == '3'): ?>
                                <span class="label label-sm label-info"><?php echo app('translator')->get('label.PROCESSING_DELIVERY'); ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if(!empty($userAccessArr[27][5])): ?>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
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
                            </td>
                            <?php endif; ?>
                            <td class="td-actions text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <div class="width-inherit">
                                    <?php if(!empty($userAccessArr[27][5])): ?>
                                    <button class="btn btn-xs yellow tooltips vcenter order-details" title="Veiw Order Details" href="#modalOrderDetails" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <!--VIEW_LSD_INFORMATION-->
                                    <button class="btn btn-xs blue tooltips vcenter lsd-info" title="<?php echo app('translator')->get('label.VIEW_LSD_INFORMATION'); ?>" href="#lsdInfo" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-server"></i>
                                    </button>
                                    <?php if(!empty($deliveryArr) && array_key_exists($target->id, $deliveryArr)): ?>
                                    <!--                                    <button class="btn btn-xs red-haze tooltips vcenter lead-time" title="<?php echo app('translator')->get('label.VIEW_LEAD_TIME'); ?>" href="#modalLeadTime" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </button>-->
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if(!empty($userAccessArr[27][3])): ?>
                                    <?php if(empty($deliveryArr[$target->id]) || sizeof($deliveryArr[$target->id]) == 1): ?>
                                    <button class="btn btn-xs btn-primary edit-lc-info tooltips vcenter" href="#modalEditLcInfo" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="Edit LC Information">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <!--commission setup-->
                                    <?php if(!empty($userAccessArr[27][18])): ?>
                                    <button class="btn btn-xs <?php echo e($btnColorCAS); ?>  tooltips vcenter commission-setup-modal" href="#commissionSetUpModal" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.COMMISSION_SETUP'); ?>">
                                        <i class="fa fa-sitemap"></i>
                                    </button>
                                    <?php endif; ?>

                                    <?php if(!empty($userAccessArr[27][16])): ?>
                                    <?php if(in_array($target->order_status, ['2','3'])): ?>
                                    <a class="btn btn-xs purple-sharp tooltips vcenter" href="<?php echo e(URL::to('confirmedOrder/getShipmentInfoView/' . $target->id)); ?>" data-placement="top" data-rel="tooltip" title="Set Shipment Information">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if(!empty($userAccessArr[27][14])): ?>
                                    <?php if(in_array($target->order_status, ['3'])): ?>
                                    <button class="btn btn-xs green-seagreen mark-order-accomplished tooltips vcenter" href="#modalMarkOrderAccomplished" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="Mark Order as Accomplished">
                                        <i class="fa fa-check-circle"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[27][13])): ?>
                                    <?php if(!array_key_exists($target->id, $hasInvoiceList)): ?>
                                    <button class="btn btn-xs red-intense order-cancel tooltips vcenter" href="#modalOrderCancellation" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="Cancel Order">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[27][17])): ?>
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="<?php echo app('translator')->get('label.FOLLOW_UP'); ?>" data-inquiry-id ="<?php echo e($target->id); ?>" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    <?php if(Auth::user()->allowed_for_messaging == '1'): ?>
                                    <button class="btn btn-xs purple-sharp tooltips vcenter order-messaging" title="<?php echo app('translator')->get('label.VIEW_MESSAGES'); ?>" href="#modalOrderMessaging" data-id="<?php echo $target->id; ?>" data-buyer-id="<?php echo $target->buyer_id; ?>" data-toggle="modal">
                                        <i class="fa fa-commenting"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <!--RW BREAKDOWN--> 
                                    <?php if(!empty($userAccessArr[27][19])): ?>
                                    <?php if(!empty($rwBreakdownStatusArr[$target->id])): ?>
                                    <a class="btn btn-xs green-soft tooltips vcenter" title="<?php echo app('translator')->get('label.RW_BREAKDOWN_EDIT'); ?>" href="<?php echo e(URL::to('confirmedOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? ''))); ?>">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <!--rw breakdown view-->
                                    <a class="btn btn-xs yellow-casablanca tooltips vcenter rw-breakdown-view" title="<?php echo app('translator')->get('label.RW_BREAKDOWN_VIEW'); ?>" href="#rwBreakdownViewModal"  data-toggle="modal" data-inquiry-id ="<?php echo e($target->id); ?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php else: ?>
                                    <a class="btn btn-xs yellow tooltips vcenter" title="<?php echo app('translator')->get('label.RW_BREAKDOWN'); ?>" href="<?php echo e(URL::to('confirmedOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? ''))); ?>">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <!--ENDOF RW BREAKDOWN--> 
                                    <!--PO Generate-->
                                    <?php if(!empty($userAccessArr[27][20])): ?>
                                    <?php if(!empty($rwBreakdownStatusArr[$target->id])): ?>
                                    <a class="btn btn-xs btn blue-sharp tooltips vcenter" title="<?php echo app('translator')->get('label.PO_GENERATE'); ?>" href="<?php echo e(URL::to('confirmedOrder/poGenerate/' . $target->id)); ?>">
                                        <i class="fa fa-sticky-note-o"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <!--ENDOF PO Generate-->

                                    <!--PI GENERATE-->
                                    <?php if(!empty($userAccessArr[27][22])): ?>
                                    <?php if(!empty($rwBreakdownStatusArr[$target->id])): ?>
                                    <?php if($target->pi_required == '1'): ?>
                                    <a class="btn btn-xs btn blue-hoki tooltips vcenter" title="<?php echo app('translator')->get('label.PI_GENERATE'); ?>" href="<?php echo e(URL::to('confirmedOrder/piGenerate/' . $target->id)); ?>">
                                        <i class="fa fa-file-o"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <!--END OF PI GENERATE-->


                                </div>
                            </td>
                            <!--:::::::: endof rowspan part :::::::-->
                            <?php endif; ?>
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
                        <?php else: ?>
                        <tr>
                            <td colspan="18" class="vcenter"><?php echo app('translator')->get('label.NO_CONFIRMED_ORDER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>

<!-- Modal start -->


<!--order details-->
<div class="modal fade" id="modalOrderDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderDetails"></div>
    </div>
</div>

<!--order messaging-->
<div class="modal fade" id="modalOrderMessaging" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderMessaging"></div>
    </div>
</div>


<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>

<!--lsd info details-->
<div class="modal fade" id="lsdInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="lsdInfoDetails"></div>
    </div>
</div>

<!--order cancellation-->
<div class="modal fade" id="modalOrderCancellation" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showOrderCancellation"></div>
    </div>
</div>

<!--edit lc info-->
<div class="modal fade" id="modalEditLcInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showEditLcInfo"></div>
    </div>
</div>

<!--mark order as accomplished-->
<div class="modal fade" id="modalMarkOrderAccomplished" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showMarkOrderAccomplished"></div>
    </div>
</div>

<!--followUp modal-->
<div class="modal fade" id="followUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showFollowUpModal">
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

<!--End commissionSetUpModal-->

<!--RW BREAKDOWN VIEW MODAL-->
<div class="modal fade" id="rwBreakdownViewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showRwBreakdownViewModal">
        </div>
    </div>
</div>

<!-- Start quantity Summary Modal-->
<div class="modal fade" id="quantitySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowQuantitySummaryModal">
        </div>
    </div>
</div>

<!-- Start Lead Time Modal-->
<div class="modal fade" id="modalLeadTime" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showLeadTime">
        </div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        //order messaging modal
        $(".order-messaging").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            var buyerId = $(this).attr("data-buyer-id");
            var countTotal = $('span.badge-user-total-message').text();
            var countCommon = $('span.badge-user-common-message').text();
            var countOrder = $('span.badge-user-order-message').text();

            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getOrderMessaging')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    buyer_id: buyerId,
                },
                beforeSend: function () {
                    $("#showOrderMessaging").html('');
                },
                success: function (res) {
                    if (typeof countTotal != 'undefined') {
                        countTotal = countTotal - 1;
                        var s = countTotal > 1 ? 's' : '';
                        if (countTotal == 0) {
                            $('span.badge-user-total-message').remove();
                        } else {
                            $('span.badge-user-total-message').text(countTotal);
                            $('h3.h3-user-total-message').text("You Have " + countTotal + "Unread Message" + s);
                        }
                        if (countOrder > 0) {
                            countOrder = countOrder - 1;
                            $('span.badge-user-order-message').text(countOrder);
                        }
                    }

                    $("#showOrderMessaging").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //Send Message
        $(document).on("click", ".send-message", function (e) {
//            e.preventDefault();
            var formData = new FormData($('#setMessageFrom')[0]);

            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/setMessage')); ?>",
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
                    $('.send-message').prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('.send-message').prop('disabled', false);
                    $('#message').val('');
                    $('.message-body').html(res.messageBody);
                    App.unblockUI();

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
                    $('.send-message').prop('disabled', false);
                    App.unblockUI();
                }
            });
        });

        //edit lc info modal 
        $(".edit-lc-info").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/edit')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showEditLcInfo").html('');
                },
                success: function (res) {
                    $("#showEditLcInfo").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //order cancellation modal 
        $(".order-cancel").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/orderCancellationModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showOrderCancellation").html('');
                },
                success: function (res) {
                    $("#showOrderCancellation").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //order details modal
        $(".order-details").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getOrderDetails')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showOrderDetails").html('');
                },
                success: function (res) {
                    $("#showOrderDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //mark order as accomplished modal 
        $(".mark-order-accomplished").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/markOrderAccomplishedModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showMarkOrderAccomplished").html('');
                },
                success: function (res) {
                    $("#showMarkOrderAccomplished").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showMarkOrderAccomplished'), width: '100%'});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //followUp modal
        $(document).on("click", ".followup-history", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-inquiry-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/getFollowUpModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    history_status: historyStatus,
                },
                beforeSend: function () {
                    $("#showFollowUpModal").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showFollowUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showFollowUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //endof followUp modal

        //After Click to Save New Follow Up
        $(document).on("click", "#saveHistory", function (e) {
            e.preventDefault();
            var formData = new FormData($('#submitForm')[0]);

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
                        url: "<?php echo e(URL::to('confirmedOrder/setFollowUpSave')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        data_id: 'inquiry_id',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveHistory').prop('disabled', true);
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
                            $('#saveHistory').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        // EOF Function for Set Lead Follow Up

        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('id');

            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/getCommissionSetupModal')); ?>",
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
                        url: "<?php echo e(URL::to('confirmedOrder/commissionSetupSave')); ?>",
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

        //add new lsd  row
        $(document).on("click", ".add-lsd-info", function () {
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/newLsdRow')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                success: function (res) {
                    $("#addLsdRow").prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //view lsd info modal
        $(".lsd-info").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/lsdInfo')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#lsdInfoDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //RW BREAKDOWN VIEW MODAL
        $(document).on("click", ".rw-breakdown-view", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/leadRwBreakdownView')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showRwBreakdownViewModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //END OF RW BREAKDOWN VIEW MODAL

        //Quantity Summary  modal
        $(document).on("click", "#quantitySummary", function (e) {
            var productId = $(this).data('product_id');
            var brandId = $(this).data('brand_id');
            var buyerId = $(this).data('buyer_id');
            var orderNo = $(this).data('order_no');
            var piFromDate = $(this).data('from_date');
            var piToDate = $(this).data('to_date');
            var purchaseOrderNo = $(this).data('purchase_order_no');
            var lcNo = $(this).data('lc_no');
            var salespersonsId = $(this).data('salespersons_id');

            $.ajax({
                url: "<?php echo e(URL::to('confirmedOrder/quantitySummaryView')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    buyer_id: buyerId,
                    order_no: orderNo,
                    pi_from_date: piFromDate,
                    pi_to_date: piToDate,
                    purchase_order_no: purchaseOrderNo,
                    lc_no: lcNo,
                    salespersons_id: salespersonsId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#ShowQuantitySummaryModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowQuantitySummaryModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getShipmentDetails')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                beforeSend: function () {
                    $("#showShipmentDetails").html('');
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //show lead time modal
        $(".lead-time").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/confirmedOrder/getLeadTime')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                success: function (res) {
                    $("#showLeadTime").html(res.html);
                    $(".tooltips").tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
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
                }
            }); //ajax
        });

    });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/confirmedOrder/index.blade.php ENDPATH**/ ?>