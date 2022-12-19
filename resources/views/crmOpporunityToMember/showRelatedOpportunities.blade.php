<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_OPPORTUNITY_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-6">
                @lang('label.MEMBER'): <strong>{!! $member->name ?? '' !!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="3">@lang('label.SL_NO')</th>
                                <th class=" text-center vcenter" rowspan="3">@lang('label.BUYER')</th>
                                <th class="vcenter text-center" rowspan="3">@lang('label.SOURCE')</th>
                                <th class="vcenter text-center" rowspan="3">@lang('label.DATE_OF_CREATION')</th>
                                <th class="vcenter text-center" rowspan="3">@lang('label.CREATED_BY')</th>
                                <th class="vcenter text-center" rowspan="3">@lang('label.REMARKS')</th>
                                <th class="vcenter text-center" colspan="3">@lang('label.CONTACT_PERSON')</th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter">@lang('label.NAME')</th>
                                <th class="vcenter">@lang('label.PHONE')</th>
                                <th class="vcenter">@lang('label.PRIMARY_CONTACT')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($opportunityInfoArr))
                            @php $sl = 0 @endphp
                            @foreach($opportunityInfoArr as $opportunityInfo)
                            <?php
                            if ($opportunityInfo['buyer_has_id'] == '0') {
                                $buyer = $opportunityInfo['buyer'];
                            } elseif ($opportunityInfo['buyer_has_id'] == '1') {
                                $buyer = $buyerList[$opportunityInfo['buyer']] ?? '';
                            }
                            ?>
                            <tr>
                                <td class="text-center vcenter" rowspan="{{ !empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{ !empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! $buyer ?? '' !!}</td>
                                <td class="vcenter" rowspan="{{ !empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! $opportunityInfo['source'] ?? '' !!}</td>
                                <td class="vcenter" rowspan="{{ !empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! !empty($opportunityInfo['created_at']) ? Helper::formatDate($opportunityInfo['created_at']) : '' !!}</td>
                                <td class="vcenter"  rowspan="{{!empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! $opportunityInfo['opportunity_creator'] ?? '' !!}</td>
                                <td class="vcenter"  rowspan="{{!empty($contactArr[$opportunityInfo['id']]) ? count($contactArr[$opportunityInfo['id']]) : '1'  }}">{!! $opportunityInfo['remarks'] ?? '' !!}</td>

                                @if(!empty($contactArr[$opportunityInfo['id']]))
                                <?php $i = 0; ?>
                                @foreach ($contactArr[$opportunityInfo['id']] as $contact)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                $primaryStatus = $contact['primary'] == 1 ? 'Primary' : '';
                                ?>
                                <td class="vcenter">{!! !empty($contact['name']) ? $contact['name'] : '' !!}</td>
                                <td class="text-center vcenter">        
                                    @if (!empty($contact['phone']))
                                    {!! $contact['phone']; !!}
                                    @endif
                                </td>
                                <td class="text-center vcenter">
                                    @if($contact['primary'] == '1')
                                    <span class="label label-sm label-success">@lang('label.YES')</span>
                                    @else
                                    <span class="label label-sm label-warning">@lang('label.NO')</span>
                                    @endif
                                </td>

                                <?php
                                if ($i < (count($contactArr[$opportunityInfo['id']]) - 1)) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @else
                                <td></td>
                                <td></td>
                                <td></td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="20" class="text-danger">
                                    @lang('label.NO_RELATED_OPPORTUNITY_FOUND_FOR_THIS_MEMBER')
                                </td>
                            </tr>
                            @endif      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" d    ata-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $('.relation-view-2').tableHeadFixer();
});
</script>
