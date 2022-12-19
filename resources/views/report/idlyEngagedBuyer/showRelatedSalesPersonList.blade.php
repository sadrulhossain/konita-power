<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center bold">
            @lang('label.RELATED_SALES_PERSON_LIST')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @lang('label.BUYER'): <strong>{!! $buyerInfo->name ?? __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover  table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.PHOTO')</th>
                                <th class="text-center vcenter">@lang('label.EMPLOYEE_ID')</th>
                                <th class="text-center vcenter">@lang('label.NAME')</th>
                                <th class="text-center vcenter">@lang('label.DESIGNATION')</th>
                                <th class="text-center vcenter">@lang('label.DEPARTMENT')</th>
                                <th class="text-center vcenter">@lang('label.BRANCH')</th>
                                <th class="text-center vcenter">@lang('label.PHONE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$relatedSalesPersonInfoArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($relatedSalesPersonInfoArr as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter" width="50px">
                                    @if(!empty($item->photo) && File::exists('public/uploads/user/' . $item->photo))
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$item->photo}}" alt="{{ $item->name}}"/>
                                    @else
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $item->name}}"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $item->employee_id ?? '' !!}</td>
                                <td class="vcenter">
                                    {!! $item->name ?? '' !!}
                                    @if(array_key_exists($item->sales_person_id, $activeSalesPersonArr))
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ __('label.ACTIVE') }}">

                                    </button>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $item->designation ?? '' !!}</td>
                                <td class="vcenter">{!! $item->department ?? '' !!}</td>
                                <td class="vcenter">{!! $item->branch ?? '' !!}</td>
                                <td class="vcenter">{!! $item->phone ?? '' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="8">@lang('label.NO_DATA_FOUND')</td>
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

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

});
</script>