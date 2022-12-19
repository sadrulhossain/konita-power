<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.REASSIGN_OPPORTUNITY_TO_MEMBER')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'transferOpportunityForm')) !!}
    {!! Form::hidden('opportunity', json_encode($request->opportunity)) !!}
    {!! Form::hidden('member_id', $request->member_id) !!}
    {{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="newMemberId">@lang('label.NEW_MEMBERS') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('new_member_id', $memberList, null, ['class' => 'form-control js-source-states ','id'=>'newMemberId']) !!}
                                    <span class="text-danger">{{ $errors->first('new_member_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!$opportunityRelatedToMemberArr->isEmpty())
        @foreach($opportunityRelatedToMemberArr as $opportunity)
        <?php
        $nameId = !empty($opportunity->id) ? $opportunity->id: '';
        ?>
        @endforeach
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="bg-blue-hoki bg-font-blue-hoki">
                    <h5 style="padding: 10px;">
                        <strong>
                            <?php
                            $se = !empty($opportunityRelatedToMemberArr) && count($opportunityRelatedToMemberArr) > 1 ? 'se' : '';
                            $ies = !empty($opportunityRelatedToMemberArr) && count($opportunityRelatedToMemberArr) > 1 ? 'ies' : 'y';
                            ?>
                            @lang('label.NEW_MEMBER_NEED_TO_BE_RELATED_TO_THESE_SELECTED_OPPORTUNITIES_OF_PREV_ASSIGNED_PERSON', ['assigned_to' => $assignedPersonList[$nameId], 'se' => $se, 'ies' => $ies])
                        </strong>
                    </h5>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover relation-view">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter text-center">@lang('label.BUYER')</th>
                                <th class="vcenter text-center">@lang('label.SOURCE')</th>
                                <th class="vcenter text-center">@lang('label.DATE_OF_CREATION')</th>
                                <th class="vcenter text-center">@lang('label.CREATED_BY')</th>
                                <th class="vcenter text-center">@lang('label.REMARKS')</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $sl = 0; ?>
                            @foreach($opportunityRelatedToMemberArr as $opportunity)
                            <?php
                            if ($opportunity->buyer_has_id == '0') {
                                $buyer = $opportunity->buyer;
                            } elseif ($opportunity->buyer_has_id == '1') {
                                $buyer = !empty($buyerList[$opportunity->buyer]) && $opportunity->buyer != 0 ? $buyerList[$opportunity->buyer] : '';
                            }
                            ?>
                            <tr>
                                <td class = "text-center vcenter">{!!++$sl!!}</td>
                                <td class = "vcenter">{!!$buyer ?? ''!!}</td>
                                <td class = "vcenter">{!!$opportunity->source ?? ''!!}</td>
                                <td class = "vcenter text-center">{!!!empty($opportunity->created_at) ? Helper::formatDate($opportunity->created_at) : ''!!}</td>
                                <td class = "vcenter">{!!$opportunity->opportunity_creator ?? ''!!}</td>
                                <td class = "vcenter">{!!$opportunity->remarks ?? ''!!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class = "modal-footer">
        <div class = "row">
            <div class = "col-md-12">
                @if(!empty($userAccessArr[78][24]))
                <button class = "btn btn-inline green btn-submit" type = "button" id = 'submitTransferBuyer'>
                    <i class = "fa fa-check"></i> @lang('label.SUBMIT')
                </button>
                @endif
                @if(!empty($userAccessArr[78][1]))
                <button type = "button" data-dismiss = "modal" data-placement = "left" class = "btn dark btn-inline tooltips" title = "@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                @endif
            </div>
        </div>
    </div>
    {!!Form::close()!!}
</div>