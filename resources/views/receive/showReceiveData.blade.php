<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="table-responsive webkit-scrollbar max-height-500">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                        <th class="vcenter" rowspan="2" colspan="2">@lang('label.INVOICE_NO')</th>
                        <th class="vcenter text-center" colspan="5">@lang('label.INVOICED_PAYMENT')</th>
                        <th class="vcenter" rowspan="2">@lang('label.ORDER_NO')</th>
                        <th class="vcenter" rowspan="2">@lang('label.BL_NO')</th>
                        <th class="vcenter text-center" colspan="4">@lang('label.BL_PAYMENT')</th>
                    </tr>
                    <tr class="active">
                        <th class="vcenter text-center">@lang('label.BILLED')</th>
                        <th class="vcenter text-center">@lang('label.NET_RECEIVABLE')</th>
                        <th class="vcenter text-center">@lang('label.RECEIVED')</th>
                        <th class="vcenter text-center">@lang('label.DUE')</th>
                        <th class="vcenter text-center">@lang('label.COLLECTION_AMOUNT')</th>
                        <th class="vcenter text-center">@lang('label.NET_RECEIVABLE')</th>
                        <th class="vcenter text-center">@lang('label.RECEIVED')</th>
                        <th class="vcenter text-center">@lang('label.DUE')</th>
                        <th class="vcenter text-center">@lang('label.COLLECTION_AMOUNT')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($invoiceDetailsArr))
                    <?php $sl = 0; ?>
                    @foreach($invoiceDetailsArr as $invoiceId => $invoiceDetails)
                    <tr>
                        <td class="text-center vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! ++$sl !!}</td>
                        <td class="text-center vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('full_pay['.$invoiceId.']',1,false, ['id' => 'fullPay_'.$invoiceId, 'data-id' => $invoiceId, 'class'=> 'md-check full-pay-check']) !!}
                                <label for="{!! 'fullPay_'.$invoiceId !!}">
                                    <span class="inc checkbox-text-center tooltips" title="@lang('label.TICK_TO_PAY_IN_FULL')"></span>
                                    <span class="check mark-caheck checkbox-text-center tooltips" title="@lang('label.TICK_TO_PAY_IN_FULL')"></span>
                                    <span class="box mark-caheck checkbox-text-center tooltips" title="@lang('label.TICK_TO_PAY_IN_FULL')"></span>
                                </label>
                            </div>
                        </td>
                        <td class="vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! $invoiceDetails['invoice_no'] !!}</td>
                        <td class="text-right vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! '$'.Helper::numberFormat2Digit($invoiceDetails['total_billed']) !!}</td>
                        <td class="text-right vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! '$'.Helper::numberFormat2Digit($invoiceDetails['billed']) !!}</td>
                        <td class="text-right vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! !empty($invoiceCollection[$invoiceId]['received']) ?'$'.Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['received']) : '$'.Helper::numberFormat2Digit(0) !!}</td>
                        <td class="text-right vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">{!! !empty($invoiceCollection[$invoiceId]['due']) ?'$'.Helper::numberFormat2Digit($invoiceCollection[$invoiceId]['due']) : '$'.Helper::numberFormat2Digit(0) !!}</td>
                        <td class="text-center vcenter" rowspan="{{$invoiceRowSpan[$invoiceId]}}">
                            <div class="input-group bootstrap-touchspin width-150">
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                {!! Form::text('invoice_collection_amount['.$invoiceId.']', null, ['id'=> 'invoiceCollectionAmount_'.$invoiceId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId, 'data-due-amount' => ($invoiceCollection[$invoiceId]['due'] ?? 0.00), 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right invoice-collection-amount','autocomplete' => 'off']) !!}
                            </div>
                            <span class="pull-right remaining-amount-{{ $invoiceId }}"></span>
                        </td>

                        {!! Form::hidden('invoice_no['.$invoiceId.']', $invoiceDetails['invoice_no']) !!}
                        {!! Form::hidden('billed['.$invoiceId.']', $invoiceDetails['billed']) !!}
                        {!! Form::hidden('invoice_due['.$invoiceId.']', $invoiceCollection[$invoiceId]['due'] ?? 0.00) !!}

                        @if(!empty($inquiryDetailsArr[$invoiceId]))
                        <?php $i = 0; ?>
                        @foreach($inquiryDetailsArr[$invoiceId] as $inquiryId => $inquiryDetails)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td class="vcenter" rowspan="{{$inquiryRowSpan[$invoiceId][$inquiryId]}}">{!! $inquiryDetails['order_no'] !!}</td>

                        {!! Form::hidden('order_no['.$inquiryId.']', $inquiryDetails['order_no']) !!}

                        @if(!empty($deliveryDetailsArr[$invoiceId][$inquiryId]))
                        <?php $j = 0; ?>
                        @foreach($deliveryDetailsArr[$invoiceId][$inquiryId] as $deliveryId => $deliveryDetails)
                        <?php
                        if ($j > 0) {
                            echo '<tr>';
                        }
                        ?>

                        {!! Form::hidden('total_konita_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_konita_commission']) !!}
                        {!! Form::hidden('total_sales_person_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_sales_person_commission']) !!}
                        {!! Form::hidden('total_company_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_company_commission']) !!}
                        {!! Form::hidden('total_buyer_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_buyer_commission']) !!}
                        {!! Form::hidden('total_rebate_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_rebate_commission']) !!}
                        {!! Form::hidden('total_principle_commission['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $deliveryDetails['total_principle_commission']) !!}

                        <td class="vcenter">{!! $deliveryDetails['bl_no'] !!}</td>

                        {!! Form::hidden('bl_no['.$deliveryId.']', $deliveryDetails['bl_no']) !!}
                        {!! Form::hidden('due['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', $blCollection[$invoiceId][$inquiryId][$deliveryId]['due'] ?? 0.00) !!}

                        <td class="text-right vcenter">{!! '$'.Helper::numberFormat2Digit($deliveryDetails['total_konita_commission']) !!}</td>
                        <td class="text-right vcenter">{!! !empty($blCollection[$invoiceId][$inquiryId][$deliveryId]['received']) ?'$'.Helper::numberFormat2Digit($blCollection[$invoiceId][$inquiryId][$deliveryId]['received']) : '$'.Helper::numberFormat2Digit(0) !!}</td>
                        <td class="text-right vcenter">{!! !empty($blCollection[$invoiceId][$inquiryId][$deliveryId]['due']) ?'$'.Helper::numberFormat2Digit($blCollection[$invoiceId][$inquiryId][$deliveryId]['due']) : '$'.Helper::numberFormat2Digit(0) !!}</td>
                        <td class="text-center vcenter">
                            <?php $disabled = $blCollection[$invoiceId][$inquiryId][$deliveryId]['disabled'] ?? ''; ?>
                            <div class="input-group bootstrap-touchspin width-150">
                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                {!! Form::text('collection_amount['.$invoiceId.']['.$inquiryId.']['.$deliveryId.']', null, ['id'=> 'collectionAmount_'.$invoiceId.'_'.$inquiryId.'-'.$deliveryId, 'style' => ' min-width: 100px', 'data-id' => $invoiceId.'-'.$inquiryId.'-'.$deliveryId, 'data-delivery-id' => $deliveryId, 'data-due-amount' => ($blCollection[$invoiceId][$inquiryId][$deliveryId]['due'] ?? 0.00), 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right collection-amount collection-amount-'.$invoiceId.' collection-amount-'.$invoiceId.'-'.$deliveryId, 'autocomplete' => 'off', $disabled]) !!}
                            </div>
                            <span class="pull-right remaining-amount-{{ $invoiceId.'-'.$inquiryId.'-'.$deliveryId }} remaining-delivery-amount-{{$invoiceId}}"></span>
                        </td>
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
            </table    >
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row margin-top-20">
        <div class="col-md-offset-4 col-md-8">
            <button class="btn green btn-submit" id="previewReceive"  href="#modalViewReceivePreview" type="button" data-toggle="modal">
                <i class="fa fa-check"></i> @lang('label.PREVIEW')
            </button>
            <a href="{{URL::to('/receive') }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>

        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script>
$(function () {
    $("tooltips").tooltip();
    //when pay in full
    $(".full-pay-check").click(function () {
        //get invoice id
        var invoiceId = $(this).attr("data-id");
        //if pay in full
        if ($(this).prop('checked')) {
            var invElId = "#invoiceCollectionAmount_" + invoiceId;
            //get invoice due
            var invoiceDue = $(invElId).attr("data-due-amount");
            //set span text $0.00
            $('span.remaining-amount-' + invoiceId).text("Due : $0.00");
            $('span.remaining-amount-' + invoiceId).css("color", "green");
            $(invElId).val(invoiceDue);
            $(invElId).prop('readonly', true);


            $(".collection-amount-" + invoiceId).each(function () {
                var deliveryId = $(this).attr("data-delivery-id");
                var due = $(this).attr("data-due-amount");
                var delvId = $(this).attr("data-id");
                //if bl collection amount amount is disabled
                //or bl due 0
                if ($(this).prop('disabled') == false) {
                    $('span.remaining-amount-' + delvId).text("Due : $0.00");
                    $('span.remaining-amount-' + delvId).css("color", "green");
                    $(this).val(due);
                    $(this).prop('readonly', true);
                }
            });
        } else {
            //clear all number and span text
            $('span.remaining-amount-' + invoiceId).text('');
            $('span.remaining-delivery-amount-' + invoiceId).text('');
            $("#invoiceCollectionAmount_" + invoiceId).val('');
            $(".collection-amount-" + invoiceId).val('');
            $("#invoiceCollectionAmount_" + invoiceId).prop('readonly', false);
            $(".collection-amount-" + invoiceId).prop('readonly', false);
        }

    });
    //end :: when pay in full
    
    $('.invoice-collection-amount').keyup(function (e) {
        findRemainingAmount(e, this);
    });

    $('.collection-amount').keyup(function (e) {
        findRemainingAmount(e, this);
    });

    function findRemainingAmount(e, selector) {
        e.preventDefault();
        var amount = $(selector).val();
        var id = $(selector).attr("data-id");
        var due = $(selector).attr("data-due-amount");

        if (amount == '') {
            $('span.remaining-amount-' + id).text('');
            return false;
        }

        var remaining = due - amount;

        if (amount.length > 0) {
            if (remaining >= 0) {
                $('span.remaining-amount-' + id).text("Due : $" + remaining.toFixed(2));
                $('span.remaining-amount-' + id).css("color", "green");
                return false;
            } else {
                remaining = remaining * (-1);
                $('span.remaining-amount-' + id).text("Surplus : $" + remaining.toFixed(2));
                $('span.remaining-amount-' + id).css("color", "red");
                return false;
            }
        }
    }
});

</script>
