<div class="row payment-div margin-top-20">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-offset-2 col-md-5">
                <div class="form-group">
                    <label class="col-md-12 text-right bold">@lang('label.DATE'): {!! date("d F Y") !!}</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="commissionDue">@lang('label.TOTAL_BALANCE') :</label>
                    <div class="col-md-7 margin-top-8 bold text-right">
                        {!! Form::hidden('commission_due', !empty($commissionDue) ? $commissionDue : 0, ['class' => 'commission-due']) !!}
                        ${!! !empty($commissionDue) ? Helper::numberFormat2Digit($commissionDue) : Helper::numberFormat2Digit(0) !!}
                    </div>
                </div>
                @if(empty($hasPendingPayment))
                @if(!empty($commissionDue) || $commissionDue != 0)
                <div class="form-group">
                    <label class="control-label col-md-5" for="payInFull">@lang('label.PAY_IN_FULL') :</label>
                    <div class="col-md-7 checkbox-center md-checkbox has-success">
                        {!! Form::checkbox('pay_in_full',1,null, ['id' => 'payInFull', 'class'=> 'md-check']) !!}
                        <label for="payInFull">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success">@lang('label.TICK_TO_PAY_IN_FULL')</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="payment">@lang('label.PAYMENT') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        <div class="input-group bootstrap-touchspin">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('payment', null, ['id'=> 'payment', 'class' => 'form-control payment integer-decimal-only text-right','autocomplete' => 'off']) !!} 
                        </div>
                        {!! Form::hidden('net_due', null, ['id' => 'netDue']) !!}
                        <span class="net-due pull-right"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="buyerContactPerson">@lang('label.BUYER_CONTACT_PERSON') :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        {!! Form::select('buyer_contact_person', $buyerContPersonList, null, ['class' => 'form-control js-source-states country-id', 'id' => 'buyerContactPerson']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="payment">@lang('label.REMARKS') :</label>
                    <div class="col-md-7">
                        {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control', 'size' => '5x3']) !!} 
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
        
        @if(!empty($hasPendingPayment))
        <div class="row margin-top-10 margin-bottom-10">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                <div class="alert alert-danger">
                    <p>
                        <i class="fa fa-warning"></i>
                        @lang('label.THERE_IS_PAYMENT_PENDING_FOR_APPROVAL')&nbsp;
                        @if(!empty($userAccessArr[64][1]))
                        <a href="{{ URL::to('/buyerPaymentVoucher?buyer_id='.$request->buyer_id.'&approval_status=1') }}">
                            @lang('label.PLEASE_APPROVE_THE_PAYMENT_OR_DENY')
                        </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-4 col-md-8">
                    @if(empty($hasPendingPayment))
                    @if(!empty($commissionDue) || $commissionDue != 0)
                    <button class="btn green " id="previewPayment" type="button">
                        <i class="fa fa-eye"></i> @lang('label.PREVIEW')
                    </button>
                    @endif
                    @endif
                    <a href="{{ URL::to('/buyerPayment') }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>

                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>

<script>
$(function () {
    $('#payInFull').on("click", function () {
        if ($(this).prop('checked')) {
            var commissionDue = $('.commission-due').val();
            var payment = parseFloat(commissionDue).toFixed(2);
            var netDue = parseFloat(0).toFixed(2);
            $('span.net-due').text("Due : $0.00");
            $('span.net-due').css("color", "green");
            $('#netDue').val(netDue);
            $('#payment').val(payment);
            $('#payment').prop('readonly', true);
        } else {
            $('span.net-due').text("");
            $('#netDue').val('');
            $('#payment').val('');
            $('#payment').prop('readonly', false);
        }
    });

    $('.payment').on("keyup", function (e) {
        e.preventDefault();
        var payment = $(this).val();
        var commissionDue = $(".commission-due").val();

        if (payment == '') {
            $('span.net-due').text('');
            return false;
        }

        var netDue = commissionDue - payment;
        netDue = parseFloat(netDue).toFixed(2);

        if (payment.length > 0) {
            $("#netDue").val(netDue);
            if (netDue >= 0) {
                $('span.net-due').text("Net Due : $" + netDue);
                $('span.net-due').css("color", "green");
                return false;
            } else {
                netDue = netDue * (-1);
                $('span.net-due').text("Surplus : $" + netDue);
                $('span.net-due').css("color", "red");
                return false;
            }
        }
    });
});
</script>