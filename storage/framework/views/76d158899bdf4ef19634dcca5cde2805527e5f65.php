<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.SALES_STATUS_REPORT'); ?>
            </div>
            <div class="actions">
                <span class="text-right">
                    <?php if(Request::get('generate') == 'true'): ?>
                    <?php if(!$targetArr->isEmpty()): ?>
                    <?php if(!empty($userAccessArr[51][6])): ?>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=print')); ?>"  title="<?php echo app('translator')->get('label.PRINT'); ?>">
                        <i class="fa fa-print"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[51][9])): ?>
                    <a class="btn btn-sm btn-inline blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to($request->fullUrl().'&view=pdf')); ?>"  title="<?php echo app('translator')->get('label.DOWNLOAD'); ?>">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?> 
                    <?php endif; ?> 
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'salesStatusReport/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('creation_from_date', Request::get('creation_from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('creation_from_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('creation_to_date', Request::get('creation_to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('creation_to_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId"><?php echo app('translator')->get('label.BUYER'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('buyer_id', $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states', 'id' => 'buyerId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('buyer_id')); ?></span>
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
            <div class="row">

                <!--SUMMARY-->
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg-color">
                                <thead>
                                    <tr>
                                        <td class="vcenter bold"><?php echo app('translator')->get('label.SALES_PARAMETER'); ?></td>
                                        <td class="vcenter bold"><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_VOLUME'); ?></td>
                                        <td class="vcenter bold"><?php echo app('translator')->get('label.TOTAL'); ?>&nbsp;<?php echo app('translator')->get('label.SALES_AMOUNT'); ?></td>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td class="vcenter"><?php echo app('translator')->get('label.UPCOMING'); ?></td>
                                        <td class="text-right vcenter"><?php echo e(Helper::numberFormat2Digit($upcomingSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                        <td class="text-right vcenter">$<?php echo e(Helper::numberFormat2Digit($upcomingSalesAmount)); ?></td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.PIPE_LINE'); ?></td>
                                        <td class="text-right vcenter"><?php echo e(Helper::numberFormat2Digit($pipeLineSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                        <td class="text-right vcenter">$<?php echo e(Helper::numberFormat2Digit($pipeLineSalesAmount)); ?></td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.CONFIRMED'); ?></td>
                                        <td class="text-right vcenter"><?php echo e(Helper::numberFormat2Digit($confirmedSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                        <td class="text-right vcenter">$<?php echo e(Helper::numberFormat2Digit($confirmedSalesAmount)); ?></td>
                                    </tr>
                                    <tr class="tooltips">
                                        <!--Total Sales Volume-->
                                        <td><?php echo app('translator')->get('label.ACCOMPLISHED'); ?></td>
                                        <td class="text-right vcenter"><?php echo e(Helper::numberFormat2Digit($accomplishedSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                        <td class="text-right vcenter">$<?php echo e(Helper::numberFormat2Digit($accomplishedSalesAmount)); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
                <!--END OF SUMMARY-->


                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SUPPLIER'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.INQUIRY_DATE'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
                                    <?php if(!empty($userAccessArr[51][5])): ?>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SHIPMENT_DETAILS'); ?></th>
                                    <?php endif; ?>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$targetArr->isEmpty()): ?>
                                <?php
                                $sl = 0;
                                $totalSalesVolume = $totalSalesAmount = 0;
                                ?>
                                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                //inquiry rowspan
                                $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                                ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo ++$sl; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo !empty($target->order_no)?$target->order_no:''; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo !empty($target->purchase_order_no)?$target->purchase_order_no:''; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo $target->buyerName; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                        <?php if(!empty($supplierList[$target->supplier_id])): ?>
                                        <?php echo $supplierList[$target->supplier_id]; ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo Helper::formatDate($target->inquiry_date); ?></td>
                                    <td class="vcenter text-center" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                        <?php if($target->status == '1'): ?>
                                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.UPCOMING'); ?></span>
                                        <?php endif; ?>
                                        <?php if($target->order_status == '1'): ?>
                                        <span class="label label-sm label-primary"><?php echo app('translator')->get('label.PIPE_LINE'); ?></span>
                                        <?php elseif($target->order_status == '2' || $target->order_status == '3'): ?>
                                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.CONFIRMED'); ?></span>
                                        <?php elseif($target->order_status == '4'): ?>
                                        <span class="label label-sm label-danger"><?php echo app('translator')->get('label.ACCOMPLISHED'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(!empty($userAccessArr[51][5])): ?>
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
                                    ?>
                                    <td class="vcenter"><?php echo e(!empty($item['gsm']) ? $item['gsm'] : ''); ?></td>
                                    <td class="vcenter text-right"><?php echo e(Helper::numberFormat2Digit($item['sales_volume'])); ?>&nbsp;<?php echo e($item['unit_name']); ?></td>
                                    <td class="vcenter text-right">$<?php echo e(Helper::numberFormat2Digit($item['sales_amount'])); ?></td>
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
                                    <td class="bold text-right" colspan="<?php echo e(!empty($userAccessArr[51][5]) ? 12 : 11); ?>"><?php echo app('translator')->get('label.TOTAL'); ?></td>
                                    <td class="bold text-right"><?php echo e(Helper::numberFormat2Digit($totalSalesVolume)); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?></td>
                                    <td class="bold text-right">$<?php echo e(Helper::numberFormat2Digit($totalSalesAmount)); ?></td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td colspan="<?php echo e(!empty($userAccessArr[51][5]) ? 14 : 13); ?>" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

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

<script type="text/javascript">
    $(function () {
        //table header fix
        $("#fixTable").tableHeadFixer();
        //        $('.sample').floatingScrollbar();

        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/salesStatusReport/getShipmentDetails')); ?>",
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
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/report/salesStatus/index.blade.php ENDPATH**/ ?>