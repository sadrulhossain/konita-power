<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

    <body>
        <!--        <div class="header">
                    @if(Request::get('view') == 'pdf')
                    <img src="{!! base_path() !!}/public/img/logo_small_print.png" alt="RTMS Logo" /> @else
                    <img src="{!! asset('public/img/logo_small_print.png') !!}" alt="RTMS Logo" /> @endif
                    <p>@lang('label.ACADEMIC_REPORT')</p>
                </div>-->
        <!--Endof_BL_history data-->

        <?php
        $basePath = URL::to('/');
        ?>
        <div class="portlet-body margin-bottom-75">
            <div class="row margin-bottom-10">
                <!--header-->
                <div class="col-md-12">
                    <table class="table borderless">
                        <tr>
                            <td width='40%'>
                                <span>
                                    <img src="{{$basePath}}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                </span>
                            </td>
                            <td class="text-right font-size-11" width='60%'>
                                <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.', ':''}}</span>
                                <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End of Header-->
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="inv-border-bottom">@lang('label.BUYER_PAYMENT_VOUCHER')</span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12 text-right">
                    <span class="bold">@lang('label.DATE'): {!! !empty($target->created_at) ? Helper::formatDate($target->created_at) : '' !!}</span>

                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table borderless">
                        <tr>
                            <td class="no-border v-top text-left " colspan="2">
                                <span>@lang('label.BUYER'): </span><span class="bold">{{$target->name ?? ''}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-border v-top" width="70%">
                                <span>@lang('label.CONTACT_PERSON'): </span><span class="bold">{{$target->buyer_contact_person ?? ''}}</span>
                            </td>
                            <td class="no-border v-top" width="30%" style="padding-left: 30px;" >
                                <span>@lang('label.PHONE'): </span><span class="bold">{{$target->buyer_contact_number ?? ''}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row margin-bottom-10">
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
                                <td class="vcenter text-right" width="30%">${!! !empty($target->commission_due) ? Helper::numberFormat2Digit($target->commission_due) : '0.00' !!}</td>
                            </tr>
                            <tr>
                                <td class="vcenter" width="70%">@lang('label.THIS_PAYMENT')</td>
                                <td class="vcenter bold text-right" width="30%">(-) ${!! !empty($target->amount) ? Helper::numberFormat2Digit($target->amount) : '0.00' !!}</td>
                            </tr>
                            <tr>
                                <td class="vcenter" width="70%">@lang('label.NET_DUE')</td>
                                <td class="vcenter text-right" width="30%">${!! !empty($target->net_due) ? Helper::numberFormat2Digit($target->net_due) : '0.00' !!}</td>
                            </tr>
                            <tr>
                                <th class="vcenter text-left" colspan="2">
                                    <span>
                                        @lang('label.TOTAL_PAYABLE')
                                        &nbsp;(@lang('label.IN_WORDS')):
                                    </span>
                                    <span class="capitalize">
                                        &nbsp;<i>{!! !empty($target->amount) ? Helper::numberToWord($target->amount) : Helper::numberToWord(0) !!}
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
                            <tr>
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
        <!--footer-->
        <div class="row margin-top-20">
            <div class="col-md-12">
                <table class="table borderless">
                    <tr>
                        <td class="no-border text-left ">
                            @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                        </td>
                        <td class="no-border text-right">
                            @lang('label.GENERATED_FROM_KTI')
                        </td>
                    </tr>
                </table>
            </div>
        </div>



        <!--//end of footer-->
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>