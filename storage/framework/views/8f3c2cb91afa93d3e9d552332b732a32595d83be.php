<div class="col-md-3">
    <label class="control-label" for="gradeId"><?php echo app('translator')->get('label.GRADE'); ?> :</label>
    <?php echo Form::select('grade_id', $gradeList, null, ['class' => 'form-control js-source-states', 'id' => 'gradeId']); ?>

    <?php echo Form::hidden('grade_value', $gradeVal, ['id' => 'gradeValue']); ?>

    <span class="text-danger"><?php echo e($errors->first('grade_id')); ?></span>
</div><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/lead/showGrade.blade.php ENDPATH**/ ?>