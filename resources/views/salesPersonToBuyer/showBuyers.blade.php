<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_BUYERS'): {!! !$buyerArr->isEmpty()?count($buyerArr):0 !!}</span>
        @if(!empty($userAccessArr[17][5]))
        <button class="label label-primary tooltips" href="#modalRelatedBuyer" id="relateBuyer"  data-toggle="modal" title="@lang('label.SHOW_RELATED_BUYERS')">
            @lang('label.BUYERS_RELATED_TO_THIS_SALES_PERSON'): {!! !empty($buyerRelatedToSalesPerson)?count($buyerRelatedToSalesPerson):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>

        <button class="label label-purple-sharp tooltips" href="#modalUnassignedBuyer" id="unassignedBuyer"  data-toggle="modal" title="@lang('label.SHOW_UNASSIGNED_BUYERS')">
            @lang('label.UNASSIGNED_BUYERS'): {!! !empty($unassignedBuyerArr)?count($unassignedBuyerArr):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                        @if(!$buyerArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentBuyerArr[$request->sales_person_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-brand-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif
                        <th class=" text-center vcenter">@lang('label.LOGO')</th>
                        <th class="vcenter">@lang('label.BUYER_NAME')</th>
                        <th class="text-center vcenter">@lang('label.NO_OF_RELATED_SALES_PERSONS')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$buyerArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($buyerArr as $buyer)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($buyerRelatedToSalesPerson) && array_key_exists($buyer->id, $buyerRelatedToSalesPerson)) {
                        $checked = 'checked';
                    }

                    $buyerDisabled = $buyerTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveBuyerArr) && in_array($buyer->id, $inactiveBuyerArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $buyerDisabled = 'disabled';
                        $buyerTooltips = __('label.INACTIVE');
                    }
                    if (!empty($dependentBuyerArr[$request->sales_person_id])) {
                        if (in_array($buyer->id, $dependentBuyerArr[$request->sales_person_id]) && ($checked != '')) {
                            $buyerDisabled = 'disabled';
                            $checkCondition = 1;
                            $buyerTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('buyer['.$buyer->id.']', $buyer->id, $checked, ['id' => $buyer->id, 'data-id'=> $buyer->id,'class'=> 'md-check buyer-check', $buyerDisabled]) !!}
                                <label for="{!! $buyer->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $buyerTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $buyerTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $buyerTooltips }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('buyer['.$buyer->id.']', $buyer->id) !!}
                            @endif
                        </td>
                        <td class="text-center vcenter">
                            @if (!empty($buyer->logo))
                            <img alt="{{$buyer->name}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$buyer->logo}}" width="40" height="40"/>
                            @else
                            <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                            @endif
                        </td>
                        <td class="vcenter">{!! $buyer->name ?? '' !!}</td>
                        <td class="text-center vcenter">
                            @if(!empty($salesPersonToBuyerCountList[$buyer->id]))
                            <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list"  
                                    title="@lang('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST')" href="#modalRelatedSalesPersonList" data-id="{!! $buyer->id !!}" data-toggle="modal">
                                {!! $salesPersonToBuyerCountList[$buyer->id] !!}
                            </button>
                            @else
                            <span class="label label-sm label-gray-mint sales-person-count-{{$buyer->id}} tooltips" title="@lang('label.NO_RELATED_SALES_PERSON')">{!! 0 !!}</span>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_BUYER_FOUND')</td>
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
            @if(!$buyerArr->isEmpty())
            @if(!empty($userAccessArr[17][7]))
            <button class="btn btn-circle green btn-submit" id="saveSalesPersonToBuyerRel" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[17][1]))
            <a href="{{ URL::to('/salesPersonToBuyer') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>

<!--related sales person list-->
<div class="modal fade" id="modalRelatedSalesPersonList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSalesPersonList"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php if (!$buyerArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".buyer-check").on("click", function () {
            if ($('.buyer-check:checked').length == $('.buyer-check').length) {
                $('.all-buyer-check').prop("checked", true);
            } else {
                $('.all-buyer-check').prop("checked", false);
            }
        });
        $(".all-buyer-check").click(function () {
            if ($(this).prop('checked')) {
                $('.buyer-check').prop("checked", true);
            } else {
                $('.buyer-check').prop("checked", false);
            }

        });
        if ($('.buyer-check:checked').length == $('.buyer-check').length) {
            $('.all-buyer-check').prop("checked", true);
        } else {
            $('.all-buyer-check').prop("checked", false);
        }

        //related sales person list modal
        $(document).on("click", ".related-sales-person-list", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('salesPersonToBuyer/getRelatedSalesPersonList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showRelatedSalesPersonList").html('');
                },
                success: function (res) {
                    $("#showRelatedSalesPersonList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });


    });


</script>