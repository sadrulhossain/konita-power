<?php
$basePath = URL::to('/');
if (Request::get('view') == 'pdf') {
    $basePath = base_path();
}
?>
@if($request->view == 'print' || $request->view == 'pdf')
<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        @if(Request::get('view') == 'print')
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 
        @elseif(Request::get('view') == 'pdf')
        <link rel="shortcut icon" href="{!! base_path() !!}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'}}" rel="stylesheet" type="text/css"/>
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css"/> 
        @endif
    </head>
    <body>      
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="row margin-bottom-20">
                        <div class="col-md-12">
                            <table class="table borderless">
                                <tbody>
                                    <tr>
                                        <td class="no-border" width="40%">
                                            <span> 
                                                <img src="{{$basePath}}/public/img/konita_small_logo.png" style="width: 300px; height: 80px;">
                                            </span>
                                        </td>
                                        <td class="text-right no-border" width="60%">
                                            <span class="font-size-11">{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                                            <span class="font-size-11">{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                                            <span class="font-size-11">@lang('label.PHONE'): </span><span class="font-size-11">{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                                            <span class="font-size-11">@lang('label.EMAIL'): </span><span class="font-size-11">{{!empty($konitaInfo->email)?$konitaInfo->email.',':''}}</span>
                                            <span class="font-size-11">@lang('label.WEBSITE'): </span><span class="font-size-11">{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="row margin-top-10">
                        <div class="col-md-12 text-center">
                            <span class="bold uppercase inv-border-bottom">@lang('label.SALES_PERSON_LEDGER')</span>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <div class="">
                                <h5 style="padding: 10px;" class="font-size-11">
                                    {{__('label.SALES_PERSON')}} : <strong>{{ $salesPersonNameArr[$request->sales_person_id] ?? __('label.N_A') }} |</strong> 
                                    {{__('label.FROM_DATE')}} : <strong>{{ !empty($request->from_date) ? Helper::formatDate($request->from_date) : __('label.N_A') }} |</strong> 
                                    {{__('label.TO_DATE')}} : <strong>{{ !empty($request->to_date) ? Helper::formatDate($request->to_date) : __('label.N_A') }} </strong>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-top-20">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter bold font-size-11">@lang('label.DATE')</th>
                                        <th class="text-center vcenter bold font-size-11">@lang('label.DESCRIPTION')</th>
                                        <th class="text-center vcenter bold font-size-11">@lang('label.COMMISSION')</th>
                                        <th class="text-center vcenter bold font-size-11">@lang('label.PAID_AMOUNT')</th>
                                        <th class="text-center vcenter bold font-size-11">@lang('label.BALANCE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty(Request::get('from_date')))
                                    <tr>
                                        <th class="text-center vcenter bold font-size-11" colspan="4">@lang('label.PREVIOUS_BALANCE')</th>
                                        <th class="vcenter text-right font-size-11">${!! !empty($previousBalance) ? Helper::numberFormat2Digit($previousBalance) : Helper::numberFormat2Digit(0) !!}</th>
                                    </tr>
                                    @endif

                                    @if(!empty($ledgerArr))
                                    @foreach($ledgerArr as $dateTime => $delivery)
                                    @foreach($delivery as $deliveryId => $amount)
                                    <?php
                                    $billTextAlign = !empty($amount['billed']) ? 'text-right' : 'text-center';
                                    $recievedTextAlign = !empty($amount['received']) ? 'text-right' : 'text-center';
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter font-size-11">{!! !empty($dateTime) ? Helper::formatDate($dateTime) : '--' !!}</td>
                                        <td class="vcenter  font-size-11">
                                            @if($deliveryId != 'payment')
                                            @lang('label.RECEIVED_THROUGH_BL_NO')&nbsp;
                                            <span class="bold">
                                                {!! $blNoList[$deliveryId] ?? '' !!}&nbsp;
                                                (@lang('label.ORDER_NO')&nbsp;{!! $orderNoList[$deliveryId] ?? '' !!})
                                            </span>.
                                            @else
                                            @lang('label.PAID_TO_SALES_PERSON')
                                            @endif
                                        </td>
                                        <td class="{{$billTextAlign}} vcenter font-size-11">{!! !empty($amount['billed']) ? '$'.Helper::numberFormat2Digit($amount['billed']) : '--' !!}</td>
                                        <td class="{{$recievedTextAlign}} vcenter font-size-11">{!! !empty($amount['received']) ? '$'.Helper::numberFormat2Digit($amount['received']) : '--' !!}</td>
                                        <td class="text-right vcenter font-size-11">${!! !empty($balanceArr[$dateTime][$deliveryId]) ? Helper::numberFormat2Digit($balanceArr[$dateTime][$deliveryId]) : Helper::numberFormat2Digit(0) !!}</td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                    <tr>
                                        <th class="text-right vcenter bold font-size-11" colspan="4">@lang('label.NET_BALANCE')=</th>
        <!--                                    <th class="vcenter blue-grey text-right">${!! !empty($totalBilled) ? Helper::numberFormat2Digit($totalBilled) : Helper::numberFormat2Digit(0) !!}</th>
                                        <th class="vcenter blue-grey text-right">${!! !empty($totalReceived) ? Helper::numberFormat2Digit($totalReceived) : Helper::numberFormat2Digit(0) !!}</th>-->
                                        <th class="vcenter text-right font-size-11">${!! !empty($totalBalance) ? Helper::numberFormat2Digit($totalBalance) : Helper::numberFormat2Digit(0) !!}</th>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="vcenter text-danger font-size-11" colspan="5">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table borderless">
                                <tr>
                                    <td class="no-border text-left font-size-11">
                                        @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                                    </td>
                                    <td class="no-border text-right font-size-11">
                                        @lang('label.GENERATED_FROM_KTI')
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
        </div>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>
@endif