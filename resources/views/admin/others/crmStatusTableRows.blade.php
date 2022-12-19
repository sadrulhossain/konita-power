<td class="vcenter text-center">{!! $crmStatusSummarySl !!}</td>
<td class="vcenter bold text-green-seagreen">{!! $statusTextCap !!}</td>
<td class="vcenter text-center">
    @if(!empty($crmWeeklyCount[$statusText]) && $crmWeeklyCount[$statusText] != 0)
    <button class="btn btn-xs bold {{$crmColor}} crm-status-btn-circle crm-status-btn-rounded tooltips crm-opportunity-list" accesskey=""href="#crmOpportunityListModal"  data-toggle="modal" title="{!! __('label.VIEW_CRM_STATUS_WISE_OPPORTUNITY_LIST', ['status'=> $statusTextCap, 'duration' => __('label.THIS_WEEK')]) !!}" 
            data-status ="{!! $status !!}" data-revoked-status ="{!! $revokedStatus !!}"
            data-last-ctivity-status ="{!! $lastActivityStatus !!}" data-dispatch-status ="{!! $dispatchStatus !!}"
            data-approval-status ="{!! $approvalStatus !!}" data-duration ="0" data-status-text-cap="{!! $statusTextCap !!}" data-duration-text="{!! __('label.THIS_WEEK') !!}">
        {!! $crmWeeklyCount[$statusText] !!}
    </button>
    @else
    <span class="badge bold badge-{{$crmColor}}">{!! 0 !!}</span>
    @endif
</td>
<td class="vcenter text-center">
    @if(!empty($crmDailyCount[$statusText]) && $crmDailyCount[$statusText] != 0)
    <button class="btn btn-xs bold {{$crmColor}} crm-status-btn-circle crm-status-btn-rounded tooltips crm-opportunity-list" accesskey=""href="#crmOpportunityListModal"  data-toggle="modal" title="{!! __('label.VIEW_CRM_STATUS_WISE_OPPORTUNITY_LIST', ['status'=> $statusTextCap, 'duration' => __('label.TODAY')]) !!}" 
            data-status ="{!! $status !!}" data-revoked-status ="{!! $revokedStatus !!}"
            data-last-ctivity-status ="{!! $lastActivityStatus !!}" data-dispatch-status ="{!! $dispatchStatus !!}"
            data-approval-status ="{!! $approvalStatus !!}" data-duration ="1" data-status-text-cap="{!! $statusTextCap !!}" data-duration-text="{!! __('label.TODAY') !!}">
        {!! $crmDailyCount[$statusText] !!}
    </button>
    @else
    <span class="badge bold badge-{{$crmColor}}">{!! 0 !!}</span>
    @endif
</td>