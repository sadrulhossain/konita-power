<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_BRAND_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row margin-bottom-10">
            <div class="col-md-4">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.CODE'): <strong>{!! $product->code ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.PRODUCT_CATEGORY'): <strong>{!! $product->category_name ?? ''!!}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.LOGO')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="text-center vcenter">@lang('label.HAS_GRADE')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($brandArr))
                            @php $sl = 0 @endphp
                            @foreach($brandArr as $brand)
                            <?php
                            $brandStatusColor = 'green-seagreen';
                            $brandStatusTitle = __('label.ACTIVE');
                            if (!empty($inactiveBrandArr) && in_array($brand['id'], $inactiveBrandArr)) {
                                $brandStatusColor = 'red-soft';
                                $brandStatusTitle = __('label.INACTIVE');
                            }
                            ?>

                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter">
                                    @if(!empty($brand['logo']))
                                    <img class="pictogram-min-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['name']}}"/>
                                    @else 
                                    <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                    @endif
                                </td>
                                <td class="vcenter">
                                    {!! $brand['name'] ?? ''!!}
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$brandStatusColor}} tooltips" title="{{ $brandStatusTitle }}">
                                    </button>
                                </td>
                                <td class="text-center vcenter">
                                    @if(isset($brandRelateToProductHasGrade[$brand['id']]) && $brandRelateToProductHasGrade[$brand['id']] == '1')
                                    <span class="label label-success">@lang('label.YES')</span>
                                    @else
                                    <span class="label label-warning">@lang('label.NO')</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_BRAND_FOUND_RELATED_TO_THIS_PRODUCT')
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
