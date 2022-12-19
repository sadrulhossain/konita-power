@if(!$salesPersonArr->isEmpty())
@foreach($salesPersonArr as $salesPerson)
<div class="col-md-2 col-lg-2 col-sm-3 col-xs-6">
    <div class="thumbnail margin-bottom-15">
        @if(!empty($salesPerson->photo) && File::exists('public/uploads/user/'.$salesPerson->photo))
        <img class="tooltips fixed-height-152" data-placement="bottom" data-html="true" title="
             <div class='text-left'>
             @lang('label.NAME'): <strong>{!! $salesPerson->full_name ?? __('label.N_A') !!}</strong><br/>
             @lang('label.DESIGNATION'): <strong>{!! $salesPerson->designation ?? __('label.N_A') !!}</strong><br/>
             @lang('label.SALES_TARGET'): <strong>{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong><br/>
             @lang('label.SALES_ACHIEVEMENT'): <strong>{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong></div>" 
             width="152" height="152" src="{{URL::to('/')}}/public/uploads/user/{{$salesPerson->photo}}" alt="{{ $salesPerson->full_name}}"/>
        @else
        <img class="tooltips fixed-height-152" data-placement="bottom" data-html="true" title="
             <div class='text-left'>
             @lang('label.NAME'): <strong>{!! $salesPerson->full_name ?? __('label.N_A') !!}</strong><br/>
             @lang('label.DESIGNATION'): <strong>{!! $salesPerson->designation ?? __('label.N_A') !!}</strong><br/>
             @lang('label.SALES_TARGET'): <strong>{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong><br/>
             @lang('label.SALES_ACHIEVEMENT'): <strong>{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong></div>" 
              width="152" height="152" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $salesPerson->full_name}}"/>
        @endif
        <div class="caption">
            @if(!empty($userAccessArr[20][7]) && !empty($userAccessArr[20][5]))
            <div class="row text-center">
                <!--<div class="col-md-6">-->
                <button class="btn btn-sm btn-padding purple-sharp tooltips set-sales-target" data-view-id="1" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                    <i class="fa fa-calculator"></i>
                </button>
                <!--                                </div>
                                                <div class="col-md-6">-->
                <button class="btn btn-sm btn-padding grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                    <i class="fa fa-bars"></i>
                </button>
                <!--</div>-->
            </div>
            @elseif(empty($userAccessArr[20][7]) && !empty($userAccessArr[20][5]))
            <button class="btn btn-sm btn-block grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                <i class="fa fa-bars"></i>&nbsp;@lang('label.VIEW_TARGET')
            </button>
            @elseif(!empty($userAccessArr[20][7]) && empty($userAccessArr[20][5]))
            <button class="btn btn-sm btn-block purple-sharp tooltips set-sales-target" data-view-id="1" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                <i class="fa fa-calculator"></i>&nbsp;@lang('label.SET_TARGET')
            </button>
            @endif
        </div>
    </div>
</div>
@endforeach
@else
<div class="col-md-12 text-center">
    <span class="label label-danger">@lang('label.NO_SALES_PERSON_FOUND'). @lang('label.PLEASE_ADD_AT_LEAST_ONE_SALES_PERSON')</span>
</div>
@endif

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
</script>