<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RECEIVE_DETAILS')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '', 'id' =>'setReceiveForm', 'class' => 'form-horizontal')) !!}
    {!! Form::hidden('supplier_id', $request->supplier_id) !!}
    {!! Form::hidden('receive', $receive) !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.SUPPLIER'):&nbsp;<strong> {!! $supplier->name ?? __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.INVOICE_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.ORDER_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BL_NO')</th>
                                <th class="vcenter text-center" rowspan="2">@lang('label.COLLECTION_AMOUNT')</th>
                                <th class="vcenter text-center" colspan="4">@lang('label.COMMISSION')</th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter text-center">@lang('label.KONITA_CMSN')</th>
                                <th class="vcenter text-center">@lang('label.SALES_PERSON_COMMISSION')</th>
                                <th class="vcenter text-center">@lang('label.BUYER_COMMISSION')</th>
                                <th class="vcenter text-center">@lang('label.REBATE_COMMISSION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($receiveList))
                            <?php $sl = 0; ?>
                            @foreach($receiveList as $invoiceId => $invoiceDetails)
                            <tr>
                                <td class="text-center vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! $invoiceDetails['invoice_no'] !!}</td>
                                
                                {!! Form::hidden('billed['.$invoiceId.']', $request->billed[$invoiceId]) !!}
                                
                                @if(!empty($receiveList2[$invoiceId]))
                                <?php $i = 0; ?>
                                @foreach($receiveList2[$invoiceId] as $inquiryId => $inquiryDetails)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter" rowspan="{{$inquiryRowSpan[$invoiceId][$inquiryId]}}">{!! $inquiryDetails['order_no'] !!}</td>

                                @if(!empty($receiveList3[$invoiceId][$inquiryId]))
                                <?php $j = 0; ?>
                                @foreach($receiveList3[$invoiceId][$inquiryId] as $deliveryId => $deliveryDetails)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter">{!! $deliveryDetails['bl_no'] !!}</td>
                                <td class="text-center vcenter">
                                    <div class="input-group bootstrap-touchspin">
                                        <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                        {!! Form::text('collection_amount['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', !empty($deliveryDetails['collection_amount']) ? Helper::numberFormat2Digit($deliveryDetails['collection_amount']) : null, ['id'=> 'collectionAmount_'.$invoiceId.'_'.$inquiryId.'_'.$deliveryId, 'style' => ' min-width: 100px', 'class' => 'form-control integer-decimal-only text-input-width text-right collection-amount', 'readonly', 'autocomplete' => 'off']) !!}
                                    </div>
                                </td>

                                <td class="vcenter text-right">{!! !empty($deliveryDetails['company_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['company_commission']) : __('label.N_A')!!}</td>
                                <td class="vcenter text-right">{!! !empty($deliveryDetails['sales_person_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['sales_person_commission']) : __('label.N_A')!!}</td>
                                <td class="vcenter text-right">{!! !empty($deliveryDetails['buyer_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['buyer_commission']) : __('label.N_A')!!}</td>
                                <td class="vcenter text-right">{!! !empty($deliveryDetails['rebate_commission']) ? '$'.Helper::numberFormat2Digit($deliveryDetails['rebate_commission']) : __('label.N_A')!!}</td>
                                
                                
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
                                <td class="text-success" colspan="20">@lang('label.PAYMENT_OF_ALL_INVOICES_HAS_BEEN_COLLECTED')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="setReceive">@lang('label.CONFIRM_SUBMIT')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

  
