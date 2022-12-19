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
        <div class="row">
            <div class="col-md-12">
                <table class="table borderless">
                    <tr>
                        <td width='40%'>
                            <span> 
                                @if(Request::get('view') == 'pdf')
                                <img src="{!! base_path() !!}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                @else
                                <img src="{{URL::to('/')}}/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                @endif

                            </span>
                        </td>
                        <td class="text-right font-size-11" width='60%'>
                            <span>{{!empty($konitaInfo->name)?$konitaInfo->name:''}}</span><br/>
                            <span>{{!empty($konitaInfo->address)?$konitaInfo->address:''}}</span><br/>
                            <span>@lang('label.PHONE'): </span><span>{{!empty($phoneNumber)?$phoneNumber:''}}</span><br/>
                            <span>@lang('label.EMAIL'): </span><span>{{!empty($konitaInfo->email)?$konitaInfo->email.'.':''}}</span>
                            <span>@lang('label.WEBSITE'): </span><span>{{!empty($konitaInfo->website)?$konitaInfo->website:''}}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <h4 class="text-center bold uppercase header">@lang('label.PRODUCT_PRICING')</h4>
                <table class="table table-bordered table-hover module-access-view" id="dataTable">
                    <thead>
                        <tr  class="info">
                            <th  class="vcenter" width="">@lang('label.BRAND')</th>
                            <th  class="vcenter">@lang('label.GRADE')</th>
                            @if($authorised->authorised_for_realization_price == '1')
                            <th  class="text-center vcenter">@lang('label.REALIZATION_PRICE')</th>
                            @endif
                            <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')</th>
                            <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')</th>
                            <th  class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                            <th  class="text-center vcenter">@lang('label.REMARKS')</th>
                            @if($authorised->authorised_for_realization_price == '1')
                            <th  class="text-center vcenter">@lang('label.SPECIAL_NOTE')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="access-check">
                        @if(!empty($targetArr))
                        <?php
                        $product_id = null;
                        ?>
                        @foreach($targetArr as $productId=>$productData)
                        <?php
                        if ($productId != $product_id) {
                            $product_id = $productId;
                            $productName = !empty($productArr[$product_id]) ? $productArr[$product_id] : '';
                            echo '<tr class="bg-grey-steel">
                                    <th colspan="' . ($authorised->authorised_for_realization_price == '1' ? 8 : 6) . '" '
                            . 'class="text-center">' . $productName . '</th>
                                </tr>';
                        }
                        ?>
                        @foreach($productData as $brandId=>$brandData)
                        <?php
                        $rowSpan = !empty($rowspanArr[$productId][$brandId]) ? count($rowspanArr[$productId][$brandId]) : 0;
                        $i = 0;
                        ?>
                        <tr>
                            <td class="vcenter text-left " rowspan="{{$rowSpan}}">
                                {{!empty($brandArr[$brandId])?$brandArr[$brandId]:''}}
                            </td>
                            @foreach($brandData as $gradeId=>$target)
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }
                            ?>
                            <td class="vcenter text-left">{{$target['grade_name']}}</td>
                            @if($authorised->authorised_for_realization_price == '1')
                            <td class="vcenter text-right">
                                ${{$target['realization_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                            </td>
                            @endif
                            <td class="vcenter text-right">
                                ${{$target['minimum_selling_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                            </td>
                            <td class="vcenter text-right">
                                ${{$target['target_selling_price']}}&nbsp;<span>/{{$target['unit_name']}}</span>
                            </td>
                            <td class="vcenter text-center">
                                {{Helper::formatDate($target['effective_date'])}}
                            </td>
                            <td class="vcenter text-left">
                                {{!empty($target['remarks']) ? $target['remarks'] : __('label.N_A') }}
                            </td>
                            @if($authorised->authorised_for_realization_price == '1')
                            <td class="vcenter text-left">
                                {{!empty($target['special_note']) ? $target['special_note'] : __('label.N_A')}}
                            </td>
                            @endif
                        </tr>
                        <?php
                        $i++;
                        ?>
                        @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                        @else
                        <tr>
                            <td colspan="{{$authorised->authorised_for_realization_price == '1' ? 8 : 6}}">@lang('label.NO_DATA_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <!--end of koniat bank information-->
            </div>
        </div>
        <table class="table borderless">
            <tr>
                <td class="no-border text-left ">@lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right">@lang('label.GENERATED_FROM_KTI')</td>
            </tr>

        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>