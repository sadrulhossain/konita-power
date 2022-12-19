<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RELATED_BUYER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? '' !!}</strong>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.BUYER_NAME')</th>
                                <th class="vcenter">@lang('label.BUYER_CATEGORY')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($buyerArr))
                            @php $sl = 0 @endphp
                            @foreach($buyerArr as $buyer)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $buyer['name'] !!}</td>
                                <td class="vcenter">{!! $buyer['buyer_category_name'] !!}</td>
                                
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
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
});
</script>
