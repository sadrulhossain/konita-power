
<div class="table-responsive col-md-12 webkit-scrollbar">
    <table class="table table-bordered table-hover" id="dataTable">
        <thead>
            <tr class="info">
                <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                <th  class="text-center vcenter">@lang('label.PHOTO')</th>
                <th  class="vcenter">@lang('label.SALES_PERSON')</th>
                <th  class="text-center vcenter">@lang('label.SALES_TARGET')</th>
                <th class="text-center vcenter">@lang('label.SALES_ACHIEVEMENT')</th>
                <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
            </tr>

        </thead>
        <tbody>
            @if(!$salesPersonArr->isEmpty())
            <?php $sl = 0; ?>
            @foreach($salesPersonArr as $salesPerson)
            <tr>
                <td class="text-center vcenter">{!! ++$sl !!}</td>
                <td class="text-center vcenter">
                    @if(!empty($salesPerson->photo) && File::exists('public/uploads/user/'.$salesPerson->photo))
                    <img width="50" height="50" src="{{URL::to('/')}}/public/uploads/user/{{$salesPerson->photo}}" alt="{{ $salesPerson->full_name}}"/>
                    @else
                    <img width="50" height="50" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $salesPerson->full_name}}"/>
                    @endif
                </td>
                <td class="vcenter">{!! $salesPerson->full_name !!}</td>
                <td class="text-right vcenter">{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</td>
                <td class="text-right vcenter">{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</td>
                <td class="td-actions text-center vcenter">
                    <div>
                        @if(!empty($userAccessArr[20][7]))
                        <button class="btn btn-xs purple-sharp tooltips set-sales-target"  data-view-id="2" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" data-placement="top" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                            <i class="fa fa-calculator"></i>
                        </button>
                        @endif
                        @if(!empty($userAccessArr[20][5]))
                        <button class="btn btn-xs grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" data-placement="top" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                            <i class="fa fa-bars"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="vcenter">@lang('label.NO_SALES_PERSON_FOUND')</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        $(".tooltips").tooltip();
        
        @if(!$salesPersonArr->isEmpty())
        $("#dataTable").dataTable({
            "language": {
                "search": "Search Keywords : ",
            },
            "attr": {
                "class": "form-control"
            }
        });
        @endif
    });
</script>