<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[17][6]))
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('salesPersonToBuyer/getUnassignedBuyersPrint?view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
        </a>
        @endif
        <h3 class="modal-title text-center">
            @lang('label.UNASSIGNED_BUYER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'modalForm')) !!}
            {!! Form::hidden('unassigned_list', 1) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="salesPersonId">@lang('label.SALES_PERSON') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('sales_person_id', $salesPersonArr, Request::get('sales_person_id'), ['class' => 'form-control js-source-states', 'id' => 'modalSalesPersonId']) !!}
                                <span class="text-danger">{{ $errors->first('sales_person_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive max-height-500 webkit-scrollbar">
                            <table class="table table-bordered table-hover relation-view-2">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                        <th class="vcenter" rowspan="2">
                                            <div class="md-checkbox has-success">
                                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-buyer-check']) !!}
                                                <label for="checkAll">
                                                    <span class="inc"></span>
                                                    <span class="check mark-caheck"></span>
                                                    <span class="box mark-caheck"></span>
                                                </label>
                                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                            </div>
                                        </th>
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
                                    $buyerDisabled = '';
                                    if (!empty($inactiveBuyerArr) && in_array($buyer['id'], $inactiveBuyerArr)) {
                                        $statusColor = 'red-soft';
                                        $statusTitle = __('label.INACTIVE');
                                        $buyerDisabled = 'disabled';
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter width-120">
                                            <div class="md-checkbox has-success width-inherit">
                                                {!! Form::checkbox('buyer['.$buyer['id'].']', $buyer['id'], false, ['id' => $buyer['id'], 'data-id'=> $buyer['id'],'class'=> 'md-check buyer-check',$buyerDisabled]) !!}
                                                <label for="{!! $buyer['id'] !!}">
                                                    <span class="inc tooltips" data-placement="right" title=""></span>
                                                    <span class="check mark-caheck tooltips" data-placement="" title=""></span>
                                                    <span class="box mark-caheck tooltips" data-placement="" title=""></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center vcenter">
                                            @if (!empty($buyer['logo']) && File::exists('public/uploads/buyer/' . $buyer['logo']))
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
                                            @lang('label.NO_UNASSIGNED_BUYER_FOUND')
                                        </td>
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
                            @if(!empty($buyerArr))
                            @if(!empty($userAccessArr[17][7]))
                            <button class="btn btn-circle green modal-submit" id="saveSalesPersonToBuyerRel" type="button">
                                <i class="fa fa-check"></i> @lang('label.ASSIGN_BUYER')
                            </button>
                            @endif
                            @if(!empty($userAccessArr[17][1]))
                            <a href="{{ URL::to('/salesPersonToBuyer') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
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

    $(".buyer-check").on("click", function () {
        if ($('.buyer-check:checked').length == $('.buyer-check').length) {
            $('.all-buyer-check').prop("checked", true);
        } else {
            $('.all-buyer-check').prop("checked", false);
        }
    });
    $(".all-buyer-check").on("click", function () {
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
});
</script>
