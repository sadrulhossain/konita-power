<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUMBER_OF_OPPORTUNITIES'): {!! !$opportunitiesArr->isEmpty()?count($opportunitiesArr):0 !!}</span>
        @if(!empty($userAccessArr[70][5]))
        <button class='label label-primary tooltips' href="#modalRelatedOpportunity" id="relateOpportunity"  data-toggle="modal" title="@lang('label.SHOW_RELATED_OPPORTUNITIES')">
            @lang('label.OPPORTUNITY_RELATED_TO_THIS_MEMBER'): {!! !empty($opportunityRelatedToMember) ? count($opportunityRelatedToMember):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        @endif
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
                        <th class="vcenter text-center">@lang('label.CREATED_BY')</th>
                        <th class="vcenter text-center">@lang('label.REMARKS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$opportunitiesArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($opportunitiesArr as $opportunity)
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
                        <td class="vcenter">{!! $opportunity->opportunity_creator ?? '' !!}</td>
                        <td class="vcenter">{!! $opportunity->remarks ?? '' !!}</td>
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
            @if(!empty($userAccessArr[70][7]))
            <button class="btn btn-circle green btn-submit" id="saveSupplierToProductRel" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[70][1]))
            <a href="{{ URL::to('/crmOpportunityToMember') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php
if (!$opportunitiesArr->isEmpty()) {
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