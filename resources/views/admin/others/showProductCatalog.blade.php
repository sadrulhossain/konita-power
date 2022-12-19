<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="{{ URL::to('productCatalog?view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
            <i class="fa fa-print"></i>&nbsp;@lang('label.PRINT')
        </a>
        <h3 class="modal-title text-center">
            @lang('label.PRODUCT_CATALOG')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
				@if(!empty($target->show_all_brands))
                @if(!empty($productInfoArr))
                <div class="col-md-12 margin-top-10 text-center">
                    <div class="alert alert-info bold">
                        <p>
                            <i class="fa fa-info-circle"></i>
                            @lang('label.ASTERIC_SIGN_REFERS_TO_ALREADY_PURCHASED_BRANDS')
                        </p>
                    </div>
                </div>
                @endif
				@endif
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center">@lang('label.SL')</th>
                                    <th class="vcenter text-center">@lang('label.PRODUCT')</th>
                                    <th class="vcenter text-center" colspan="2">@lang('label.BRAND')</th>
                                    <th class="vcenter text-center">@lang('label.PURCHASED_VOLUME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($productInfoArr))
                                <?php $sl = 0; ?>
                                @foreach($productInfoArr as $productId => $product)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$productRowSpanArr[$productId]['brand']}}">{!! $product['product_name'] ?? __('label.N_A') !!}</td>

                                    @if(!empty($product['brand']))
                                    <?php $i = 0; ?>
                                    @foreach($product['brand'] as $brandId => $brand)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="text-center vcenter" width="30px">
                                        @if(!empty($brand['logo']) && File::exists('public/uploads/brand/' . $brand['logo']))
                                        <img class="pictogram-min-space tooltips" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['brand_name']}}" title="{{ $brand['brand_name'] }}"/>
                                        @else 
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {!! $brand['brand_name'] ?? __('label.N_A') !!}
                                        @if(!empty($brandWiseVolumeRateArr[$productId]))
                                        @if(array_key_exists($brandId, $brandWiseVolumeRateArr[$productId]))
                                        @if(!empty($target->show_all_brands))
                                        <span class="text-green bold">*</span>
                                        @endif
										<br/>
                                        <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]['volume_rate']) : '0.00'; ?>
                                        <span class="text-green bold">
                                            (@lang('label.PERCENTAGE_OF_TOTAL_PURCHASED_VOLUME', ['percentage' => $percentage]))
                                        </span>
                                        @endif
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        {!! $brand['origin'] ?? __('label.N_A') !!}
                                    </td>
                                    <td class="vcenter">
                                        <div class="width-inherit">
                                            @if(!empty($brand['certificate']))
                                            @foreach($brand['certificate'] as $key => $file)
                                                <a class="btn btn-xs bg-yellow-casablanca bg-font-yellow-casablanca tooltips" href="{{URL::to('public/uploads/brandCertificate/'.$file)}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW') {{(!empty($certificateArr[$key])) && (!empty($file)) ? $certificateArr[$key].' '.__('label.CERTIFICATE') :'' }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a>
                                            <label>&nbsp;</label>
                                            @endforeach
                                            @else
                                            <span class="label label-warning purple-stripe">
                                                @lang('label.N/A')
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="vcenter">
                                        <div class="width-inherit">
                                            @if(!empty($previousDataSheetArr[$productId][$brandId]))
                                            @foreach($previousDataSheetArr[$productId][$brandId] as $dataSheetId => $dataSheet)
                                            <a class="btn btn-xs bg-blue bg-font-blue tooltips " href="{{URL::to('public/uploads/techDataSheet/'.$dataSheet['file'])}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW') {{(!empty($dataSheet['title'])) && (!empty($dataSheet['file'])) ? $dataSheet['title'] :'' }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                            <label>&nbsp;</label>
                                            @endforeach
                                            @else
                                            <span class="label label-warning purple-stripe">
                                                @lang('label.N/A')
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <?php
                                    $volume = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? Helper::numberFormat2Digit($brandWiseVolumeRateArr[$productId][$brandId]['volume']) . (!empty($product['unit']) ? ' ' . $product['unit'] : '') : '--';
                                    $volumeAlign = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? 'right' : 'center';
                                    $volumeColor = (!empty($brandWiseVolumeRateArr[$productId][$brandId]['volume']) && $brandWiseVolumeRateArr[$productId][$brandId]['volume'] != 0) ? 'green' : 'danger';
                                    ?>
                                    <td class="vcenter text-{{$volumeAlign}}"><span class="text-{{$volumeColor}}">{!! $volume !!}</span></td>
                                    <?php
                                    if ($i < ($productRowSpanArr[$productId]['brand'] - 1)) {
                                        echo '</tr>';
                                    }
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endif
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
