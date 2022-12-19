<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center bold">
            @lang('label.CRM_STATUS_WISE_OPPORTUNITY_LIST', [
            'status' => $request->status_text_cap,
            'duration' => $request->duration_text
            ])
        </h4>
    </div>
    <div class="modal-body">
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.BUYER')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.SOURCE')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.CREATED_BY')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.ASSIGNED_TO')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.LAST_UPDATED')</th>
                                <th class="text-center vcenter" colspan="8">@lang('label.PRODUCT_INFORMATION')</th>
                            </tr>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                <th class="text-center vcenter">@lang('label.BRAND')</th>
                                <th class="text-center vcenter">@lang('label.GRADE')</th>
                                <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                <th class="text-center vcenter">@lang('label.GSM')</th>
                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$targetArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($targetArr as $target)
                            <?php
                            if ($target->buyer_has_id == '0') {
                                $buyer = $target->buyer;
                            } elseif ($target->buyer_has_id == '1') {
                                $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                            }

                            $checked = ($target->id == $request->selected_opportunity_id) ? 'checked' : '';
                            ?>
                            <tr>
                                <td class="text-center vcenter" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! $buyer ?? '' !!}</td>
                                <td class="vcenter" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! $target->source ?? '' !!}</td>
                                <td class="vcenter" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! $target->opportunity_creator ?? '' !!}</td>
                                <td class="vcenter" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! $assignedPersonList[$target->id] ?? '' !!}</td>
                                <td class="vcenter text-center" rowspan="{!! !empty($productRowSpanArr[$target->id]) ? $productRowSpanArr[$target->id] : 0 !!}">{!! !empty($target->updated_at) ? Helper::formatDateTime($target->updated_at) : '' !!}</td>

                                @if(!empty($productArr[$target->id]))
                                <?php $i = 0; ?>
                                @foreach($productArr[$target->id] as $pKey => $pInfo)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                //product
                                if ($pInfo['product_has_id'] == '0') {
                                    $product = $pInfo['product'];
                                } elseif ($pInfo['product_has_id'] == '1') {
                                    $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                }
                                //brand
                                if ($pInfo['brand_has_id'] == '0') {
                                    $brand = $pInfo['brand'];
                                } elseif ($pInfo['brand_has_id'] == '1') {
                                    $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                }
                                //grade
                                if ($pInfo['grade_has_id'] == '0') {
                                    $grade = $pInfo['grade'];
                                } elseif ($pInfo['grade_has_id'] == '1') {
                                    $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                }
                                //Origin
                                $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                ?>

                                <td class="vcenter">{!! $product ?? '' !!}</td>
                                <td class="vcenter">{!! $brand ?? '' !!}</td>
                                <td class="vcenter">{!! $grade ?? '' !!}</td>
                                <td class="text-center vcenter">{!! $country !!}</td>
                                <td class="vcenter">{!! (!empty($pInfo['gsm'])) ? $pInfo['gsm'] : '' !!}</td>
                                <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                <td class="text-right vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                <td class="text-right vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>

                                <?php
                                if ($i < ($productRowSpanArr[$target->id] - 1)) {
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
                                <td class="vcenter text-danger" colspan="11">@lang('label.NO_DATA_FOUND')</td>
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