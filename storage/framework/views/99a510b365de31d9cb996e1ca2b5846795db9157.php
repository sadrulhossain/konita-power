<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.TRANSFER_BUYER_TO_SALES_PERSON'); ?>
        </h3>
    </div>
    <?php echo Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'transferBuyerForm')); ?>

    <?php echo Form::hidden('buyer', json_encode($request->buyer)); ?>

    <?php echo Form::hidden('sales_person_id', $request->sales_person_id); ?>


    <?php echo e(csrf_field()); ?>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="newSalesPersonId"><?php echo app('translator')->get('label.NEW_SALES_PERSON'); ?> :</label>
                                <div class="col-md-8">
                                    <?php echo Form::select('new_sales_person_id', $salesPersonList, null, ['class' => 'form-control js-source-states ','id'=>'newSalesPersonId']); ?>

                                    <span class="text-danger"><?php echo e($errors->first('new_sales_person_id')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($productArr)): ?>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="bg-blue-hoki bg-font-blue-hoki">
                    <h5 style="padding: 10px;">
                        <strong>
                            <?php echo app('translator')->get('label.NEW_SALES_PERSON_NEED_TO_BE_RELATED_TO_THESE_PRODUCTS_AND_BRANDS'); ?>
                        </strong>
                    </h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT_NAME'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT_CATEGORY'); ?></th>
                                <th class=" text-center vcenter" colspan="2"><?php echo app('translator')->get('label.BRAND'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">

                            <?php $sl = 0 ?>
                            <?php $__currentLoopData = $productArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $rowspan = !empty($brandRelatedToBuyer[$product['id']]) ? count($brandRelatedToBuyer[$product['id']]) : 1;
                            ?>

                            <tr>
                                <td class="text-center vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo ++$sl; ?></td>
                                <td class="vcenter" rowspan="<?php echo e($rowspan); ?>">
                                    <?php echo $product['name'] ?? ''; ?>

                                </td>
                                <td class="vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo $product['product_category_name'] ?? ''; ?></td>
                                <?php if(!empty($brandRelatedToBuyer[$product['id']])): ?>
                                <?php $i = 0; ?>
                                <?php $__currentLoopData = $brandRelatedToBuyer[$product['id']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedBrandId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>

                                <td class="text-center vcenter">
                                    <?php if(!empty($brandInfo[$relatedBrandId]['logo']) && File::exists('public/uploads/brand/' . $brandInfo[$relatedBrandId]['logo'])): ?>
                                    <img class="pictogram-min-space" width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/brand/<?php echo e($brandInfo[$relatedBrandId]['logo']); ?>" alt="<?php echo e($brandInfo[$relatedBrandId]['name']); ?>"/>
                                    <?php else: ?> 
                                    <img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt="<?php echo e($brandInfo[$relatedBrandId]['name']); ?>"/>
                                    <?php endif; ?>
                                </td>
                                <td class="vcenter">
                                    <?php echo $brandInfo[$relatedBrandId]['name'] ?? ''; ?>

                                </td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>      
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12"> 
                <button class="btn btn-inline green btn-submit" type="button" id='submitTransferBuyer'>
                    <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>

</div><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/transferBuyerToSalesPerson/showTransferBuyerToSalesPerson.blade.php ENDPATH**/ ?>