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
                        <span class="inv-border-bottom font-size-11 header">@lang('label.SALES_VOLUME_REPORT')</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 margin-bottom-10">
                    <span>@lang('label.FROM_DATE'): {{$request->pi_from_date}} &nbsp;|&nbsp;@lang('label.TO_DATE'): {{$request->pi_to_date}}</span>
                    <span>
                        @if(!empty($request->salespersons_id))
                        &nbsp;|&nbsp;@lang('label.SALES_PERSON'): {{!empty($salesPersonArr[$request->salespersons_id])?$salesPersonArr[$request->salespersons_id]:''}}
                        @endif
                    </span>
                    <span>
                        @if(!empty($request->supplier_id))
                        &nbsp;|&nbsp;@lang('label.SUPPLIER'): {{!empty($supplierList[$request->supplier_id])? $supplierList[$request->supplier_id]:''}}
                        @endif
                    </span>
                </div>

                <!--SUMMARY-->
                <div class="col-md-12">

                    <table class="table borderless">
                        <tr>
                            <!--step 1-->
                            <td class="no-border v-top">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center vcenter font-size-11" colspan="2">@lang('label.COMMISSION_BREAKDOWN')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.KONITA_CMSN')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['konita_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.PRINCIPLE_COMMISSION')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['principal_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['principal_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.SALES_PERSON_COMMISSION')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['sales_person_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['sales_person_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.BUYER_COMMISSION')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['buyer_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['buyer_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.REBATE_COMMISSION')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['rebate_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['rebate_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--LC Transnitted Yes-->
                                            <td class="font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                                            <td class="text-right font-size-11">${{Helper::numberFormat2Digit($lcTransmitted)}}</td>
                                        </tr>
                                        <tr>
                                            <!--LC Transnitted No-->
                                            <td class="font-size-11">@lang('label.LC_NOT_TRANSMITTED')</td>
                                            <td class="text-right font-size-11">${{Helper::numberFormat2Digit($notLcTransmitted)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <!--step 2-->
                            <td class="no-border v-top">
                                <table class="table table-bordered table-hover table-bg-color">
                                    <thead>
                                        <tr>
                                            <th class="text-center vcenter font-size-11" colspan="2">@lang('label.INCOME_BREAKDOWN')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!--Total Sales Volume-->
                                            <td class="font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</td>
                                            <td class="text-right font-size-11">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                        </tr>
                                        <tr>
                                            <!--Total Sales Amount-->
                                            <td class="font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</td>
                                            <td class="text-right font-size-11">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Konita Net Commission-->
                                            <td class="font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.KONITA_NET_CMSN')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['total_konita_net_csmn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_net_csmn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Konita Commission-->
                                            <td class="font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.KONITA_CMSN')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['total_konita_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn']) : '0.00'}}</td>
                                        </tr>
                                        <tr>
                                            <!--Total Admin cost-->
                                            <td class="font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.ADMIN_COST')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['total_admin_cost']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost']) : '0.00'}}</td>
                                        </tr>

                                        <tr>
                                            <!--Total Commission-->
                                            <td class="font-size-11">@lang('label.TOTAL_COMMISSION')</td>
                                            <td class="text-right font-size-11">${{!empty($comsnIncomeArr['total_cmsn']) ? Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn']) : '0.00'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <!--step 3-->
                            <td class="no-border v-top">
                                @if(!empty($countryWiseAccount))
                                <table class="table table-bordered table-hover table-bg-color">
                                    <thead>
                                        <tr>
                                            <th class="vcenter text-center font-size-11">@lang('label.COUNTRY')</th>
                                            <th class="vcenter text-center font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_VOLUME')</th>
                                            <th class="vcenter text-center font-size-11">@lang('label.TOTAL')&nbsp;@lang('label.SALES_AMOUNT')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($countryWiseAccount as $countryId=>$val)
                                        <tr>
                                            <td class="vcenter text-center font-size-11">
                                                {{!empty($countryList[$countryId])?$countryList[$countryId]:''}}
                                            </td>
                                            <td class="vcenter text-right font-size-11">
                                                {{!empty($val['total_sales_volyme'])?Helper::numberFormat2Digit($val['total_sales_volyme']):0}}&nbsp;@lang('label.UNIT')
                                            </td>
                                            <td class="vcenter text-right font-size-11">
                                                ${{!empty($val['total_sales_amount'])?Helper::numberFormat2Digit($val['total_sales_amount']):0}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
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
                                <th class="vcenter text-center font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                <th class="vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="vcenter font-size-11">@lang('label.BRAND')</th>
                                <th class="vcenter font-size-11">@lang('label.GRADE')</th>
                                <th class="vcenter font-size-11">@lang('label.GSM')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.SALES_VOLUME')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.SALES_AMOUNT')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.KONITA_CMSN')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.ADMIN_COST')</th>
                                <th class="vcenter text-center font-size-11">@lang('label.TOTAL_COMMISSION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = 0;
                            $totalSalesVolume = 0;
                            $totalSalesAmount = 0;
                            $totalKonitaCmsn = 0;
                            $totalAdminCost = 0;
                            $totalCmsn = 0;
                            ?>
                            @foreach($targetArr as $key=>$target)
                            <?php
                            $netCmsn = !empty($profitArr[$target->id]['net_commission']) ? $profitArr[$target->id]['net_commission'] : 0;
                            $expenditureCmsn = !empty($profitArr[$target->id]['expenditure']) ? $profitArr[$target->id]['expenditure'] : 0;
                            //inquiry rowspan
                            $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                            ?>
                            <tr>
                                <td class="text-center vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! ++$sl !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    {!! $target->order_no !!}
                                    @if($expenditureCmsn > $netCmsn)
                                    <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle red-soft">

                                    </button>
                                    @endif
                                </td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! $target->purchase_order_no !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! $target->buyerName !!}</td>
                                <td class="vcenter font-size-11" rowspan="{{$rowSpan['inquiry']}}">{!! !empty($supplierList[$target->supplier_id]) ? $supplierList[$target->supplier_id] : '' !!}</td>
                                <td class="vcenter text-center font-size-11" rowspan="{{$rowSpan['inquiry']}}">
                                    @if($target->lc_transmitted_copy_done == '1')
                                    <span class="label label-sm">@lang('label.YES')</span>
                                    @elseif($target->lc_transmitted_copy_done == '0')
                                    <span class="label label-sm">@lang('label.NO')</span>
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
                                $konitaCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_konita_cmsn'] : (!empty($commissionArr[$target->id][0]['total_konita_cmsn']) ? $commissionArr[$target->id][0]['total_konita_cmsn'] : 0));
                                $adminCost = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['admin_cost'] : (!empty($commissionArr[$target->id][0]['admin_cost']) ? $commissionArr[$target->id][0]['admin_cost'] : 0));
                                $totalCommission = (!empty($commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn']) ? $commissionArr[$target->id][$item['inquiry_details_id']]['total_cmsn'] : (!empty($commissionArr[$target->id][0]['total_cmsn']) ? $commissionArr[$target->id][0]['total_cmsn'] : 0));
                                ?>
                                <td class="vcenter font-size-11">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                <td class="vcenter text-right font-size-11">{{Helper::numberFormat2Digit($item['sales_volume'])}}&nbsp;{{$item['unit_name']}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($item['sales_amount'])}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($konitaCommission)}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($adminCost)}}</td>
                                <td class="vcenter text-right font-size-11">${{Helper::numberFormat2Digit($totalCommission)}}</td> 

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
                                <td class="bold text-right font-size-11" colspan="10">@lang('label.TOTAL')</td>
                                <td class="bold text-right font-size-11">{{Helper::numberFormat2Digit($totalSalesVolume)}}&nbsp;@lang('label.UNIT')</td>
                                <td class="bold text-right font-size-11">${{Helper::numberFormat2Digit($totalSalesAmount)}}</td>
                                <td class="bold text-right font-size-11">${{Helper::numberFormat2Digit($comsnIncomeArr['total_konita_cmsn'])}}</td>
                                <td class="bold text-right font-size-11">${{Helper::numberFormat2Digit($comsnIncomeArr['total_admin_cost'])}}</td>
                                <td class="bold text-right font-size-11">${{Helper::numberFormat2Digit($comsnIncomeArr['total_cmsn'])}}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="14" class="vcenter font-size-11">@lang('label.NO_DATA_FOUND')</td>
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