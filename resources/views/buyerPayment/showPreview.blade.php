<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.BUYER_PAYMENT_VOUCHER')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <span> 
                                <img src="{{URL::to('/')}}/public/img/konita_small_logo.png" style="width: 300px; height: 80px;">
                            </span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                            <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                            <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                            <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.',':''}}</span>
                            <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                        </div>
                    </div>
                    <!--End of Header-->
                </div>

                <div class="row margin-top-10">
                    <div class="col-md-12 text-center">
                        <span class="bold uppercase inv-border-bottom">@lang('label.BUYER_PAYMENT_VOUCHER')</span>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12 text-right">
                        <span class="bold">@lang('label.DATE'): {!! date("d F Y") !!}</span>
                        
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <span>@lang('label.BUYER'): </span><span class="bold">{{!empty($buyerInfo->name)?$buyerInfo->name:''}}</span>
                    </div>
                    <div class="col-md-6">
                        <span>@lang('label.CONTACT_PERSON'): </span><span class="bold">{{$buyerContactInfo['name']}}</span>
                    </div>
                    <div class="col-md-6">
                        <span>@lang('label.PHONE'): </span><span class="bold">{{$buyerContactInfo['phone']}}</span>
                    </div>
                </div>
                
                <div class="row margin-top-20">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th class="vcenter text-center" width="70%">@lang('label.DESCRIPTION')</th>
                                    <th class="vcenter text-center" width="30%">@lang('label.AMOUNTS')</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="vcenter" width="70%">@lang('label.TOTAL_BALANCE')</td>
                                    <td class="vcenter text-right" width="30%">${!! !empty($request->commission_due) ? Helper::numberFormat2Digit($request->commission_due) : Helper::numberFormat2Digit(0) !!}</td>
                                </tr>
                                <tr>
                                    <td class="vcenter" width="70%">@lang('label.THIS_PAYMENT')</td>
                                    <td class="vcenter bold text-right" width="30%">(-) ${!! !empty($request->payment) ? Helper::numberFormat2Digit($request->payment) : Helper::numberFormat2Digit(0) !!}</td>
                                </tr>
                                <tr>
                                    <td class="vcenter" width="70%">@lang('label.NET_DUE')</td>
                                    <td class="vcenter text-right" width="30%">${!! !empty($netDue) ? Helper::numberFormat2Digit($netDue) : Helper::numberFormat2Digit(0) !!}</td>
                                </tr>
                                <tr>
                                    <th class="vcenter" colspan="2">
                                        <span>
                                            @lang('label.TOTAL_PAYABLE')
                                            &nbsp;(@lang('label.IN_WORDS')):
                                        </span>
                                        <span class="capitalize">
                                            &nbsp;<i>{!! !empty($request->payment) ? Helper::numberToWord($request->payment) : Helper::numberToWord(0) !!}
                                                @lang('label.DOLLARS_ONLY')</i>
                                        </span>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th class="vcenter text-left">@lang('label.REMARKS')</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="vcenter">{!! $request->remarks ?? 'None' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($userAccessArr[63][6]))
        <button type="button" class="btn green-seagreen" id="setPaymentWithPrint">
            <i class="fa fa-print"></i>&nbsp;@lang('label.SUBMIT_N_PRINT')
        </button>
        @endif
        <button type="button" class="btn btn-primary" id="setPayment">
            <i class="fa fa-check"></i>&nbsp;@lang('label.SUBMIT')
        </button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>


