<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.DETAILS_VOLUME_OF')&nbsp; '{{$product->name}}' 
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                @lang('label.BUYER'): <strong>{!! $buyer->name ?? ''!!}</strong>
            </div>
            <div class="col-md-4">
                @lang('label.PRODUCT'): <strong>{!! $product->name ?? ''!!}</strong>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.GSM')</th>
                                <th class="vcenter">@lang('label.VOLUME')</th>
                            </tr>
                        </thead>
                        <tbody id="">
                            @if(!empty($prevBuyerGsmValues))
                            @php $sl = 0 @endphp
                            @foreach($prevBuyerGsmValues as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $item['gsm'] !!}</td>
                                <td class="vcenter">{!! $item['volume'] !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-danger">
                                    @lang('label.NO_GSM_VOLUME_SET_TO_THIS_PRODUCT')
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
        @if(!empty($prevBuyerGsmValues))
        <button class="btn red-flamingo tooltips delete-gsm"data-placement="top"  title="@lang('label.CLICK_TO_DELETE_GSM_DATA')" data-buyer-id="{{$request->buyer_id}}" data-product-id="{{$request->product_id}}">
            @lang('label.DELETE_GSM_DATA')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

});
</script>
