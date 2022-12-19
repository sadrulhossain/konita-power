<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUMBER_OF_OPPORTUNITIES'): {!! !$opportunitiesArr->isEmpty()?count($opportunitiesArr):0 !!}</span>
<!--        <span class='label label-primary'>
            @lang('label.OPPORTUNITY_RELATED_TO_THIS_MEMBER'): {!! !empty($opportunityRelatedToMemberArr) ? count($opportunityRelatedToMemberArr):0 !!}
        </span>-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover relation-view">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false,['id' => 'checkAll', 'class'=> 'md-check all-opportunity-check']) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        <th class="vcenter text-center">@lang('label.BUYER')</th>
                        <th class="vcenter text-center">@lang('label.SOURCE')</th>
                        <th class="vcenter text-center">@lang('label.DATE_OF_CREATION')</th>
                        <th class="vcenter text-center">@lang('label.LAST_UPDATED')</th>
                        <th class="vcenter text-center">@lang('label.CREATED_BY')</th>
                        <th class="vcenter text-center">@lang('label.REMARKS')</th>
                        <th class="vcenter text-center">@lang('label.STATUS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$opportunityRelatedToMemberArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($opportunityRelatedToMemberArr as $opportunity)
                    <?php
                    if ($opportunity->buyer_has_id == '0') {
                        $buyer = $opportunity->buyer;
                    } elseif ($opportunity->buyer_has_id == '1') {
                        $buyer = !empty($buyerList[$opportunity->buyer]) && $opportunity->buyer != 0 ? $buyerList[$opportunity->buyer] : '';
                    }
                    //check and show previous value
                    $checked = '';
                    if (!empty($opportunityRelatedToMember) && array_key_exists($opportunity->id, $opportunityRelatedToMember)) {
                        $checked = 'checked';
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('opportunity['.$opportunity->id.']', $opportunity->id, $checked, ['id' => $opportunity->id, 'data-id'=> $opportunity->id,'class'=> 'md-check opportunity-check']) !!}
                                <label for="{!! $opportunity->id !!}">
                                    <span class="inc tooltips" data-placement="right" title=""></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title=""></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title=""></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter">{!! $buyer ?? '' !!}</td>
                        <td class="vcenter">{!! $opportunity->source ?? '' !!}</td>
                        <td class="vcenter text-center">{!! !empty($opportunity->created_at) ? Helper::formatDate($opportunity->created_at) : '' !!}</td>
                        <td class="vcenter text-center">{!! !empty($opportunity->updated_at) ? Helper::formatDate($opportunity->updated_at) : '' !!}</td>
                        <td class="vcenter">{!! $opportunity->opportunity_creator ?? '' !!}</td>
                        <td class="vcenter">{!! $opportunity->remarks ?? '' !!}</td>
                        <td class="vcenter text-center">
                                @if($opportunity->status == '0')
                                <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                @elseif($opportunity->status == '1')
                                @if($opportunity->revoked_status == '0')
                                @if($opportunity->last_activity_status == '0')
                                <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                @elseif($opportunity->last_activity_status == '1')
                                <span class="label label-sm label-red-soft">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '2')
                                <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '3')
                                <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '4')
                                <span class="label label-sm label-blue-soft">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '5')
                                <span class="label label-sm label-green-steel">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '6')
                                <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '8')
                                <span class="label label-sm label-red-pink">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '9')
                                <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '10')
                                <span class="label label-sm label-green-sharp">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '11')
                                <span class="label label-sm label-grey-mint">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @elseif($opportunity->last_activity_status == '12')
                                <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$opportunity->last_activity_status] !!}</span>
                                @endif
                                @elseif($opportunity->revoked_status == '1')
                                <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                @endif
                                @elseif($opportunity->status == '2')
                                @if($opportunity->dispatch_status == '0')
                                <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                @elseif($opportunity->dispatch_status == '1')
                                @if($opportunity->approval_status == '0')
                                <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                @elseif($opportunity->approval_status == '1')
                                <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                @elseif($opportunity->approval_status == '2')
                                <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                @endif
                                @endif
                                @elseif($opportunity->status == '3')
                                <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                @elseif($opportunity->status == '4')
                                <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                @endif
                            </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_OPPORTUNITY_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!empty($userAccessArr[78][7]))
            <button class="btn btn-circle green" href="#modalTransferOpportunityToMember" type="button" data-toggle="modal" id="getMemberToTransfer">
                <i class="fa fa-exchange"></i> @lang('label.REASSIGN')
            </button>
            @endif
            @if(!empty($userAccessArr[78][1]))
            <a href="{{ URL::to('/crmReassignmentOpportunity') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php
if (!$opportunityRelatedToMemberArr->isEmpty()) {
    ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });

            if ($('.opportunity-check:checked').length == $('.opportunity-check').length) {
                $('.all-opportunity-check').prop("checked", true);
            } else {
                $('.all-opportunity-check').prop("checked", false);
            }
    <?php
}
?>


        $(".opportunity-check").on("click", function () {
            if ($('.opportunity-check:checked').length == $('.opportunity-check').length) {
                $('.all-opportunity-check').prop("checked", true);
            } else {
                $('.all-opportunity-check').prop("checked", false);
            }
        });
        $(".all-opportunity-check").click(function () {
            if ($(this).prop('checked')) {
                $('.opportunity-check').prop("checked", true);
            } else {
                $('.opportunity-check').prop("checked", false);
            }

        });



    });
</script>