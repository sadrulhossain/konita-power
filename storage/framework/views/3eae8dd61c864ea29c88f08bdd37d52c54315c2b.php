<td class="vcenter text-center"><?php echo $crmStatusSummarySl; ?></td>
<td class="vcenter bold text-green-seagreen"><?php echo $statusTextCap; ?></td>
<td class="vcenter text-center">
    <?php if(!empty($crmWeeklyCount[$statusText]) && $crmWeeklyCount[$statusText] != 0): ?>
    <button class="btn btn-xs bold <?php echo e($crmColor); ?> crm-status-btn-circle crm-status-btn-rounded tooltips crm-opportunity-list" accesskey=""href="#crmOpportunityListModal"  data-toggle="modal" title="<?php echo __('label.VIEW_CRM_STATUS_WISE_OPPORTUNITY_LIST', ['status'=> $statusTextCap, 'duration' => __('label.THIS_WEEK')]); ?>" 
            data-status ="<?php echo $status; ?>" data-revoked-status ="<?php echo $revokedStatus; ?>"
            data-last-ctivity-status ="<?php echo $lastActivityStatus; ?>" data-dispatch-status ="<?php echo $dispatchStatus; ?>"
            data-approval-status ="<?php echo $approvalStatus; ?>" data-duration ="0" data-status-text-cap="<?php echo $statusTextCap; ?>" data-duration-text="<?php echo __('label.THIS_WEEK'); ?>">
        <?php echo $crmWeeklyCount[$statusText]; ?>

    </button>
    <?php else: ?>
    <span class="badge bold badge-<?php echo e($crmColor); ?>"><?php echo 0; ?></span>
    <?php endif; ?>
</td>
<td class="vcenter text-center">
    <?php if(!empty($crmDailyCount[$statusText]) && $crmDailyCount[$statusText] != 0): ?>
    <button class="btn btn-xs bold <?php echo e($crmColor); ?> crm-status-btn-circle crm-status-btn-rounded tooltips crm-opportunity-list" accesskey=""href="#crmOpportunityListModal"  data-toggle="modal" title="<?php echo __('label.VIEW_CRM_STATUS_WISE_OPPORTUNITY_LIST', ['status'=> $statusTextCap, 'duration' => __('label.TODAY')]); ?>" 
            data-status ="<?php echo $status; ?>" data-revoked-status ="<?php echo $revokedStatus; ?>"
            data-last-ctivity-status ="<?php echo $lastActivityStatus; ?>" data-dispatch-status ="<?php echo $dispatchStatus; ?>"
            data-approval-status ="<?php echo $approvalStatus; ?>" data-duration ="1" data-status-text-cap="<?php echo $statusTextCap; ?>" data-duration-text="<?php echo __('label.TODAY'); ?>">
        <?php echo $crmDailyCount[$statusText]; ?>

    </button>
    <?php else: ?>
    <span class="badge bold badge-<?php echo e($crmColor); ?>"><?php echo 0; ?></span>
    <?php endif; ?>
</td><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/admin/others/crmStatusTableRows.blade.php ENDPATH**/ ?>