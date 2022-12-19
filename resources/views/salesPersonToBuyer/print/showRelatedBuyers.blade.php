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
        <!--        <div class="header">
                    @if(Request::get('view') == 'pdf')
                    <img src="{!! base_path() !!}/public/img/logo_small_print.png" alt="RTMS Logo" /> @else
                    <img src="{!! asset('public/img/logo_small_print.png') !!}" alt="RTMS Logo" /> @endif
                    <p>@lang('label.ACADEMIC_REPORT')</p>
                </div>-->
        <!--Endof_BL_history data-->

        <?php
        $basePath = URL::to('/');
        if (Request::get('view') == 'pdf') {
            $basePath = base_path();
        }
        ?>
        <div class="portlet-body">

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
                        <span class="inv-border-bottom">@lang('label.RELATED_BUYER_LIST')</span>
                    </div>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-6">
                    @lang('label.SALES_PERSON'): <strong>{!! $salesPerson->name ?? '' !!}</strong>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class=" text-center vcenter" rowspan="2">@lang('label.LOGO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BUYER_NAME')</th>
                                <th class="vcenter" rowspan="2">@lang('label.CODE')</th>
                                <th class="vcenter" rowspan="2">@lang('label.BUYER_CATEGORY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.HEAD_OFFICE_ADDRESS')</th>
                                <th class="vcenter text-center" colspan="2">@lang('label.CONTACT_PERSON')</th>
                            </tr>
                            <tr class="active">
                                <th class="vcenter">@lang('label.NAME')</th>
                                <th class="vcenter">@lang('label.PHONE')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">
                            @if(!empty($buyerArr))
                            @php $sl = 0 @endphp
                            @foreach($buyerArr as $buyer)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter"  style="max-width: 50px">
                                    @if (!empty($buyer['logo']))
                                    <img alt="{{$buyer['name']}}" src="{{$basePath}}/public/uploads/buyer/{{$buyer['logo']}}" width="40" height="40"/>
                                    @else
                                    <img alt="unknown" src="{{$basePath}}/public/img/no_image.png" width="40" height="40"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $buyer['name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $buyer['code'] ?? '' !!}</td>
                                <td class="vcenter">{!! $buyer['buyer_category_name'] ?? '' !!}</td>
                                <td class="vcenter">{!! $buyer['head_office_address'] ?? '' !!}</td>
                                <td class="vcenter">{!! $contactArr[$buyer['id']]['name'] ?? '' !!}</td>

                                @if(is_array($contactArr[$buyer['id']]['phone']))
                                <td class="vcenter">
                                    <?php
                                    $lastValue = end($contactArr[$buyer['id']]['phone']);
                                    ?>
                                    @foreach($contactArr[$buyer['id']]['phone'] as $key => $contact)
                                    {{$contact}}
                                    @if($lastValue !=$contact)
                                    <span>,</span>
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td class="vcenter">{!! $contactArr[$buyer['id']]['phone'] ?? '' !!}</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="20" class="text-danger">
                                    @lang('label.NO_RELATED_BUYER_FOUND_FOR_THIS_SALES_PERSON')
                                </td>
                            </tr>
                            @endif      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
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

        <!--//end of footer-->
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>