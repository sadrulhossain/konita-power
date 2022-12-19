<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="bottom" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">
            @lang('label.CLOSE')
        </button>
        @if(!empty($userAccessArr[17][6]))
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('salesPersonToBuyer/getRelatedBuyersPrint/'.$request->sales_person_id.'?view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
        </a>
        @endif
        <h3 class="modal-title text-center">
            @lang('label.RELATED_BUYER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-6">
                @lang('label.SALES_PERSON'): <strong>{!! $salesPerson->name ?? '' !!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class=" text-center vcenter" rowspan="2">@lang('label.LOGO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BUYER_NAME')</th>
                                <th class="vcenter" rowspan="2">@lang('label.CODE')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BUYER_CATEGORY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.HEAD_OFFICE_ADDRESS')</th>
                                <th class="vcenter text-center" colspan="2">@lang('label.CONTACT_PERSON')</th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter">@lang('label.NAME')</th>
                                <th class="vcenter">@lang('label.PHONE')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($buyerArr))
                            @php $sl = 0 @endphp
                            @foreach($buyerArr as $buyer)
                            <?php
                            $statusColor = 'green-seagreen';
                            $statusTitle = __('label.ACTIVE');
                            if (!empty($inactiveBuyerArr) && in_array($buyer['id'], $inactiveBuyerArr)) {
                                $statusColor = 'red-soft';
                                $statusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter">
                                    @if (!empty($buyer['logo']))
                                    <img alt="{{$buyer['name']}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$buyer['logo']}}" width="40" height="40"/>
                                    @else
                                    <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                                    @endif
                                </td>
                                <td>
                                    {{ $buyer['name'] ?? '' }}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$statusColor}} tooltips" title="{{ $statusTitle }}">
                                    </button>
                                </td>

                                <td class="vcenter">{!! $buyer['code'] ?? '' !!}</td>
                                <td class="vcenter">{!! $buyer['buyer_category_name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $buyer['head_office_address'] ?? '' !!}</td>
                                <td class="vcenter">{!! $contactArr[$buyer['id']]['name'] ?? '' !!}</td>

                                @if(is_array($contactArr[$buyer['id']]['phone']))
                                <td class="vcenter">
                                    <?php
                                    $lastValue = end($contactArr[$buyer['id']]['phone']);
                                    ?>
                                    @foreach($contactArr[$buyer['id']]['phone'] as $key => $contact)
                                    {{$contact}}
                                    @if($lastValue !=$contact)
                                    <span>,</span>
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td class="vcenter">{!! $contactArr[$buyer['id']]['phone'] ?? '' !!}</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="20" class="text-danger">
                                    @lang('label.NO_RELATED_BUYER_FOUND_FOR_THIS_SALES_PERSON')
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
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $('.relation-view-2').tableHeadFixer();
});
</script>
