<?php echo Form::hidden('target_selling_price',$targetSellingPrice , ['id' => 'targetSellingPrice']); ?>

<?php echo Form::hidden('minimum_selling_price',$minimumSellingPrice , ['id' => 'minimumSellingPrice']); ?>

<span class="bold"><?php echo app('translator')->get('label.TARGET_PRICE'); ?>:&nbsp;<?php echo e($targetSellingPrice); ?>&nbsp;|</span>
<span class="bold"><?php echo app('translator')->get('label.MINIMUM_PRICE'); ?>:&nbsp;<?php echo e($minimumSellingPrice); ?>&nbsp;|</span>
<?php
$color = '';
if(!empty($status)){
    if($status<0){
          $color = 'text-danger';
    }
} ?>
<span class="bold" ><?php echo app('translator')->get('label.STATUS'); ?>:&nbsp;<span class="<?php echo e($color); ?>" id="priceStatus" ><?php echo e($status); ?></span></span><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/lead/productPricing.blade.php ENDPATH**/ ?>