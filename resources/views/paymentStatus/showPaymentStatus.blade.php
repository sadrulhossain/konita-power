<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                        <th class="text-center" rowspan="2">@lang('label.ORDER_NO')</th>
                        <th class="text-center" rowspan="2">@lang('label.BUYER')</th>
                        <th class="text-center vcenter" colspan="9">@lang('label.SHIPMENT')</th>
                    </tr>
                    <tr class="active">
                        <th class="vcenter text-center" colspan="2">@lang('label.BL_NO')</th>
                        <th class="vcenter">@lang('label.PRODUCT')</th>
                        <th class="vcenter">@lang('label.BRAND')</th>
                        <th class="vcenter">@lang('label.GRADE')</th>
                        <th class="vcenter text-center">@lang('label.TOTAL_QUANTITY')</th>
                        <th class="vcenter text-center">@lang('label.RECEIVED_QUANTITY')</th>
                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                        <th class="vcenter text-center">@lang('label.RECEIVED_TOTAL_PRICE')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($inquiryArr))
                    <?php $sl = 0; ?>
                    @foreach($inquiryArr as $inquiryId => $inquiry)
                    <tr>
                        <td class="vcenter text-center" rowspan="{{ $inquiryRowSpan[$inquiryId] }}">{!! ++$sl !!}</td>
                        <td class="vcenter" rowspan="{{ $inquiryRowSpan[$inquiryId] }}">{!! $inquiry['order_no'] !!}</td>
                        <td class="vcenter" rowspan="{{ $inquiryRowSpan[$inquiryId] }}">{!! $inquiry['buyer_name'] !!}</td>

                        @if(!empty($deliveryArr[$inquiryId]))
                        <?php $i = 0; ?>
                        @foreach($deliveryArr[$inquiryId] as $deliveryId => $delivery)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>

                        <td class="vcenter" rowspan="{{ $deliveryRowSpan[$inquiryId][$deliveryId] }}">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('delivery['.$inquiryId.']['.$deliveryId.']', 1,false, ['id' => 'delivery_'.$inquiryId.'_'.$deliveryId, 'class'=> 'md-check delivery']) !!}
                                <label for="{!! 'delivery_'.$inquiryId.'_'.$deliveryId !!}">
                                    <span class="inc checkbox-text-center"></span>
                                    <span class="check mark-caheck checkbox-text-center"></span>
                                    <span class="box mark-caheck checkbox-text-center"></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter text-center" rowspan="{{ $deliveryRowSpan[$inquiryId][$deliveryId] }}">{!! $delivery['bl_no'] !!}</td>
                        
                        @if(!empty($deliveryDetailsArr[$inquiryId][$deliveryId]))
                        <?php $j = 0; ?>
                        @foreach($deliveryDetailsArr[$inquiryId][$deliveryId] as $deliveryDetailsId => $details)
                        
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>
                        
                        <td class="vcenter">{!! $details['product_name'] !!}</td>
                        <td class="vcenter">{!! $details['brand_name'] !!}</td>
                        <td class="vcenter">{!! $details['grade_name'] !!}</td>
                        <td class="vcenter text-right">{!! Helper::numberFormat2Digit($details['total_quantity']).$details['unit'] !!}</td>
                        <td class="vcenter text-right">{!! Helper::numberFormat2Digit($details['shipment_quantity']).$details['unit'] !!}</td>
                        <td class="vcenter text-right">{!! '$'.Helper::numberFormat2Digit($details['unit_price']).$details['per_unit'] !!}</td>
                        <td class="vcenter text-right">{!! '$'.Helper::numberFormat2Digit($details['total_price']) !!}</td>
                        
                        
                        <?php
                        if ($j > 0) {
                            echo '</tr>';
                        }
                        $j++;
                        ?>
                        @endforeach
                        @endif

                        <?php
                        if ($i > 0) {
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
                        <td class="text-success" colspan="50">@lang('label.NO_UNPAID_SHIPMENT_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            <button class="btn green btn-submit" id="setPyamentStatus" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            <a href="{{ URL::to('/paymentStatus') }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>

        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>