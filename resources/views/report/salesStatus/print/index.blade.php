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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.SALES_STATUS_REPORT')</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 margin-bottom-10">
                    <span>@lang('label.FROM_DATE'): {{$request->creation_from_date}} &nbsp;|&nbsp;@lang('label.TO_DATE'): {{$request->creation_to_date}}</span>
                    <span>
                        @if(!empty($request->salespersons_id))
                        &nbsp;|&nbsp;@lang('label.SALES_PERSON'): {{!empty($salesPersonArr[$request->salespersons_id])?$salesPersonArr[$request->salespersons_id]:''}}
                        @endif
                    </span>
                </div>

                <!--SUMMARY-->
                <div class="col-md-12">

                    <table class="table borderless">
                        <tr>
                            <!--step 1-->
                            <td class="no-border v-top" width="50%">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="vcenter font-size-11">#</td>
                                            <td class="vcenter font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</td>
                                            <td class="vcenter font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</td>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.UPCOMING')</td>
                                            <td class="vcenter font-size-11 text-right">{{Helper::numberFormat2Digit($upcomingSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                            <td class="vcenter font-size-11 text-right">${{Helper::numberFormat2Digit($upcomingSalesAmount)}}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.PIPE_LINE')</td>
                                            <td class="vcenter font-size-11 text-right">{{Helper::numberFormat2Digit($pipeLineSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                            <td class="vcenter font-size-11 text-right">${{Helper::numberFormat2Digit($pipeLineSalesAmount)}}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter font-size-11">@lang('label.CONFIRMED')</td>
                                            <td class="vcenter font-size-11 text-right">{{Helper::numberFormat2Digit($confirmedSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                            <td class="vcenter font-size-11 text-right">${{Helper::numberFormat2Digit($confirmedSalesAmount)}}</td>
                                        </tr>
                                        <tr class="tooltips">
                                            <!--Total Sales Volume-->
                                            <td class="vcenter">@lang('label.ACCOMPLISHED')</td>
                                            <td class="vcenter text-right font-size-11">{{Helper::numberFormat2Digit($accomplishedSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                            <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($accomplishedSalesAmount)}}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                            <!--step 2-->
                            <td class="no-border v-top">
                            </td>
                            <!--step 3-->
                            <td class="no-border v-top">
                            </td>
                        </tr>
                    </table>



                </div>
                <!--END OF SUMMARY-->
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.ORDER_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.PURCHASE_ORDER_NO')</th>
                                <th class="vcenter font-size-11">@lang('label.BUYER')</th>
                                <th class="vcenter font-size-11">@lang('label.SUPPLIER')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.INQUIRY_DATE')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.STATUS')</th>
                                <th class="vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="vcenter font-size-11">@lang('label.BRAND')</th>
                                <th class="vcenter font-size-11">@lang('label.GRADE')</th>
                                <th class="vcenter font-size-11">@lang('label.GSM')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = 0;
                            $totalSalesVolume = $totalSalesAmount = 0;
                            ?>
                            @foreach($targetArr as $key=>$target)
                            <?php
                            //inquiry rowspan
                            $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                            ?>
                            <tr>
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->order_no)?$target->order_no:'' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($target->purchase_order_no)?$target->purchase_order_no:'' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if(!empty($supplierList[$target->supplier_id]))
                                    {!! $supplierList[$target->supplier_id] !!}
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! Helper::formatDate($target->inquiry_date) !!}</td>
                                <td class="vcenter text-center font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($target->status == '1')
                                    <span class="label label-sm label-warning">@lang('label.UPCOMING')</span>
                                    @endif
                                    @if($target->order_status == '1')
                                    <span class="label label-sm label-primary">@lang('label.PIPE_LINE')</span>
                                    @elseif($target->order_status == '2' || $target->order_status == '3')
                                    <span class="label label-sm label-success">@lang('label.CONFIRMED')</span>
                                    @elseif($target->order_status == '4')
                                    <span class="label label-sm label-danger">@lang('label.ACCOMPLISHED')</span>
                                    @endif
                                </td>

                                @if(!empty($target->inquiryDetails))
                                <?php $i = 0; ?>
                                @foreach($target->inquiryDetails as $productId=> $productData)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['product']}}">
                                    {{!empty($productArr[$productId])?$productArr[$productId]:''}}
                                </td>
                                @if(!empty($productData))
                                <?php $j = 0; ?>
                                @foreach($productData as $brandId=> $brandData)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['brand']}}">
                                    {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                                </td>
                                @if(!empty($brandData))
                                <?php $k = 0; ?>
                                @foreach($brandData as $gradeId=> $gradeData)
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                                ?>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['grade']}}">
                                    {{!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''}}
                                </td>
                                @if(!empty($gradeData))
                                <?php $l = 0; ?>
                                @foreach($gradeData as $gsm=> $item)
                                <?php
                                if ($l > 0) {
                                    echo '<tr>';
                                }

                                $totalSalesVolume += !empty($item['sales_volume']) ? $item['sales_volume'] : 0;
                                $totalSalesAmount += !empty($item['sales_amount']) ? $item['sales_amount'] : 0;
                                ?>
                                <td class="vcenter font-size-11">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                <td class="vcenter text-right font-size-11">{{Helper::numberFormat2Digit($item['sales_volume'])}}&nbsp;{{$item['unit_name']}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($item['sales_amount'])}}</td>
                                <?php
                                if ($l < ($rowSpan['grade'] - 1)) {
                                    echo '</tr>';
                                }

                                $i++;
                                $j++;
                                $k++;
                                $l++;
                                ?>
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td class="bold text-right font-size-11" colspan="11">@lang('label.TOTAL')</td>
                                <td class="bold text-right font-size-11">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                <td class="bold text-right font-size-11">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="13" class="vcenter font-size-11">@lang('label.NO_DATA_FOUND')</td>
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
                <td class="no-border text-left font-size-11">
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right font-size-11">
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