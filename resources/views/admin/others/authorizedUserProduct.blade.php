<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PRODUCT_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr  class="info">
                                    <th  class="vcenter">@lang('label.SL')</th>
                                    <th  class="vcenter">@lang('label.NAME')</th>
                                    <th  class="vcenter">@lang('label.CODE')</th>
                                    <th  class="vcenter">@lang('label.CATEGORY')</th>
                                    <th  class="vcenter">@lang('label.HS_CODE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($productDataArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($productDataArr as $item)
                                <tr>
                                    <td class="vcenter">{{++$sl}}</td>
                                    <td class="vcenter">{{$item['product_name']}}</td>
                                    <td class="vcenter">{{$item['product_code']}}</td>
                                    <td class="vcenter">{{$item['category']}}</td>
                                    <td class="text-center vcenter">
                                        @if(!empty($hsCodeArr[$item['id']]))
                                        <?php
                                        $lastValue = end($hsCodeArr[$item['id']]);
                                        ?>
                                        @foreach($hsCodeArr[$item['id']] as $key => $code)
                                        {{$code}}
                                        @if($lastValue !=$code)
                                        <span>,</span>
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
