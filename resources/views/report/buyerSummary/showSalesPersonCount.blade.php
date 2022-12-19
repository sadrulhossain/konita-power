@if(!empty($salesPersonToBuyerCountList[$item['id']]))
<button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list"  
        title="@lang('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST')" href="#modalRelatedSalesPersonList" data-id="{!! $item['id'] !!}" data-toggle="modal">
    {!! $salesPersonToBuyerCountList[$item['id']] !!}
</button>
@else
<span class="label label-sm label-gray-mint  tooltips" title="@lang('label.NO_RELATED_SALES_PERSON')">{!! 0 !!}</span>
@if(!empty($userAccessArr[17][7]))
<button class="btn btn-xs blue-hoki tooltips vcenter assign-sales-person"  
        title="@lang('label.CLICK_TO_ASSIGN_SALES_PERSON')" href="#modalAssignSalesPerson" data-id="{!! $item['id'] !!}" data-toggle="modal">
    <i class="fa fa-share"></i>
</button>
@endif
@endif