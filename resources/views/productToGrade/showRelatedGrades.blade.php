<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_GRADE_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-6">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
            </div>
            <div class="col-md-6">
                @lang('label.BRAND'): <strong>{!! $brand->name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" width="10%">@lang('label.SL_NO')</th>
                                <th class="vcenter" width="90%">@lang('label.GRADE')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($gradeArr))
                            @php $sl = 0 @endphp
                            @foreach($gradeArr as $grade)

                            <?php
                            $gradeStatusColor = 'green-seagreen';
                            $gradeStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveGradeArr) && in_array($grade['id'], $inactiveGradeArr)) {
                                $gradeStatusColor = 'red-soft';
                                $gradeStatusTitle = __('label.INACTIVE');
                            }
                            ?>
                            <tr>
                                <td class="text-center vcenter" width="10%">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    {!! $grade['name'] ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$gradeStatusColor}} tooltips" title="{{ $gradeStatusTitle }}">
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_GRADE_FOUND_RELATED_TO_THIS_BRAND')
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
