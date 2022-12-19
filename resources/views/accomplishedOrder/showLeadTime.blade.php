<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            {{ !empty($orderInfo->order_no) ? __('label.LEAD_TIME_OF_THIS_ORDER', ['order_no' => $orderInfo->order_no]) : __('label.LEAD_TIME') }}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @lang('label.LC_ISSUE_DATE'): <strong>{!! !empty($orderInfo->lc_issue_date) ? Helper::formatDate($orderInfo->lc_issue_date) : __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover  table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.BL_NO')</th>
                                <th class="text-center vcenter">@lang('label.ETS_DATE')</th>
                                <th class="text-center vcenter">@lang('label.ETA_DATE')</th>
                                <th class="text-center vcenter">@lang('label.DELIVERY_TIME')</th>
                                <th class="text-center vcenter">@lang('label.TRANSIT_TIME')</th>
                                <th class="text-center vcenter">@lang('label.TOTAL_LEAD_TIME')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($leadTimeArr))
                            <?php $sl = 0; ?>
                            @foreach($leadTimeArr as $deliveryId => $leadTime)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $leadTime['bl_no'] !!}</td>
                                <td class="text-center vcenter">{!! $leadTime['ets_date'] !!}</td>
                                <td class="text-center vcenter">{!! $leadTime['eta_date'] !!}</td>
                                <td class="text-center vcenter">{!! $leadTime['delivery_time'] !!}</td>
                                <td class="text-center vcenter">{!! $leadTime['transit_time'] !!}</td>
                                <td class="text-center vcenter">{!! $leadTime['total_lead_time'] !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="7">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

