<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center bold">
            @lang('label.IN_BUSINESS_BRAND_LIST')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @lang('label.BUYER'): <strong>{!! $buyerInfo['name'] ?? __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover  table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.LOGO')</th>
                                <th class="text-center vcenter">@lang('label.BRAND_NAME')</th>
                                <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                <th class="text-center vcenter">@lang('label.DESCRIPTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($inBusinessBrandArr))
                            <?php $sl = 0; ?>
                            @foreach($inBusinessBrandArr as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter" width="40px">
                                    @if(!empty($item['logo']) && File::exists('public/uploads/brand/' . $item['logo']))
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{$item['logo']}}" alt="{{ $item['name']}}"/>
                                    @else
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $item['name']}}"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $item['name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $item['country'] ?? '' !!}</td>
                                <td class="vcenter">{!! $item['description'] ?? '' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="5">@lang('label.NO_DATA_FOUND')</td>
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