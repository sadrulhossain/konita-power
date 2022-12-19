<html>
    <head>
        <title>@lang('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />

        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            @page { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
                color-adjust: exact !important;  /*Firefox*/
            }
        </style>

        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>

    </head>
    <body>
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
            <div class="row margin-bottom-20">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header">
                            @if(!empty($request->sales_person_id) && $request->sales_person_id != 0)
                            @lang('label.INVOLVED_ORDER_LIST')
                            @else
                            @lang('label.ORDER_LIST_WITH_TYPE', ['type' => $typeList[$request->type_id]])
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki" style="padding: 5px;">
                        <span class="bold margin-bottom-20">
                            {{__('label.SUPPLIER')}} : {{ $supplierInfo->name ?? __('label.N_A') }}
                            @if(!empty($request->sales_person_id) && $request->sales_person_id != 0)
                            &nbsp;| &nbsp;{{__('label.SALES_PERSON') .' (' . __('label.ACTIVELY_ENGAGED') . ')'}} : {{ !empty($salesPersonInfo->name) ? $salesPersonInfo->name. (!empty($salesPersonInfo->designation) ? ' (' . $salesPersonInfo->designation . ')' : '') : __('label.N_A') }}
                            @endif
                        </span>
                    </div>
                    <table class="table table-bordered margin-top-10">
                        <thead>
                            <tr>
                                <th class="text-center vcenter font-size-11">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.ORDER_NO')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.PO_NO')</th>
                                @if(empty($request->sales_person_id) || $request->sales_person_id == 0)
                                <th class="text-center vcenter font-size-11">@lang('label.SALES_PERSON')</th>
                                @endif
                                <th class="text-center vcenter font-size-11">@lang('label.BUYER')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.PRODUCT')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.BRAND')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.GRADE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.UNIT_PRICE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.TOTAL_PRICE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.INQUIRY_DATE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.PI_DATE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LC_DATE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LC_NO')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LC_TRANSMITTED_COPY_DONE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LC_ISSUE_DATE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.LSD_DATE')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.SHIPMENT_DETAILS')</th>
                                <th class="text-center vcenter font-size-11">@lang('label.STATUS')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$inquiryInfoArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($inquiryInfoArr as $inquiry)
                            <tr>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! ++$sl !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->order_no ?? '' !!}</td>
                                <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->purchase_order_no ?? '' !!}</td>
                                @if(empty($request->sales_person_id) || $request->sales_person_id == 0)
                                <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->sales_person ?? '' !!}</td>
                                @endif
                                <td class="vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->buyer ?? '' !!}</td>
                                @if(!empty($inquiryDetailsArr[$inquiry->id]['product']))
                                <?php $i = 0 ?>
                                @foreach($inquiryDetailsArr[$inquiry->id]['product'] as $productId => $product)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter font-size-11" rowspan="{!! $productRowSpanArr[$inquiry->id][$productId] !!}">
                                    {!! $product['product_name'] ?? '' !!}
                                </td>

                                @if(!empty($product['brand']))
                                <?php $j = 0 ?>
                                @foreach($product['brand'] as $brandId => $brand)
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter font-size-11" rowspan="{!! $brandRowSpanArr[$inquiry->id][$productId][$brandId] !!}">
                                    {!! $brand['brand_name'] ?? '' !!}
                                </td>

                                @if(!empty($brand['grade']))
                                <?php $k = 0 ?>
                                @foreach($brand['grade'] as $gradeId => $grade)
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter font-size-11">{!! $grade['grade_name'] ?? '' !!}</td>
                                <td class="text-right vcenter font-size-11">{!! $grade['quantity'] ?? '' !!}</td>
                                <td class="text-right vcenter font-size-11">{!! $grade['unit_price'] ?? '' !!}</td>
                                <td class="text-right vcenter font-size-11">{!! $grade['total_price'] ?? '' !!}</td>

                                @if($i == 0 && $j == 0 && $k == 0)
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->creation_date) ? Helper::formatDate($inquiry->creation_date) : '' !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->pi_date) ? Helper::formatDate($inquiry->pi_date) : ''  !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_date) ? Helper::formatDate($inquiry->lc_date) : ''  !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $inquiry->lc_no ?? '' !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                                    @if($inquiry->lc_transmitted_copy_done == '1')
                                    <span class="label label-sm bold label-blue-steel">@lang('label.YES')</span>
                                    @else
                                    <span class="label label-sm bold label-red-flamingo">@lang('label.NO')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! !empty($inquiry->lc_issue_date) ? Helper::formatDate($inquiry->lc_issue_date) : ''  !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">{!! $lsdArr[$inquiry->id] ?? '' !!}</td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                                    @if(!empty($deliveryArr) && array_key_exists($inquiry->id, $deliveryArr))
                                    @foreach($deliveryArr[$inquiry->id] as $deliveryId => $delivery)
                                    <button type="button" class="cursor-default btn btn-xs {{$delivery['btn_color']}} btn-circle {{$delivery['btn_rounded']}} tooltips vcenter shipment-details" data-html="true" 
                                            title="
                                            <div class='text-left'>
                                            @lang('label.BL_NO'): &nbsp;{!! $delivery['bl_no'] !!}<br/>
                                            @lang('label.STATUS'): &nbsp;{!! $delivery['status'] !!}<br/>
                                            @lang('label.PAYMENT_STATUS'): &nbsp;{!! $delivery['payment_status'] !!}<br/>
                                            </div>
                                            " 
                                            >
                                        <i class="fa fa-{{$delivery['icon']}}"></i>
                                    </button>
                                    @endforeach
                                    @else
                                    <button class="btn btn-xs btn-circle red-soft tooltips vcenter" title="@lang('label.NO_SHIPMENT_YET')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    @endif
                                </td>
                                <td class="text-center vcenter font-size-11" rowspan="{!! $inquryRowSpanArr[$inquiry->id] !!}">
                                    @if($inquiry->status == '1')
                                    <span class="label label-sm bold label-yellow-casablanca">@lang('label.INQUIRY')</span>
                                    @elseif($inquiry->status == '2')
                                    @if($inquiry->order_status == '1')
                                    <span class="label label-sm bold label-blue-chambray">@lang('label.PENDING')</span>
                                    @elseif($inquiry->order_status == '2')
                                    <span class="label label-sm bold label-blue-steel">@lang('label.CONFIRMED')</span>
                                    @elseif($inquiry->order_status == '3')
                                    <span class="label label-sm bold label-purple-sharp">@lang('label.PROCESSING_DELIVERY')</span>
                                    @elseif($inquiry->order_status == '4')
                                    <span class="label label-sm bold label-green-seagreen">@lang('label.ACCOMPLISHED')</span>
                                    @elseif($inquiry->order_status == '5')
                                    <span class="label label-sm bold label-yellow">@lang('label.PAYMENT_DONE')</span>
                                    @elseif($inquiry->order_status == '6')
                                    <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                                    @endif
                                    @elseif($inquiry->status == '3')
                                    <span class="label label-sm bold label-red-flamingo">@lang('label.CANCELLED')</span>
                                    @endif
                                </td>
                                @endif

                                <?php
                                if ($k < ($brandRowSpanArr[$inquiry->id][$productId][$brandId] - 1)) {
                                    echo '</tr>';
                                }
                                $k++;
                                ?>
                                @endforeach
                                @endif

                                <?php
                                if ($j < ($productRowSpanArr[$inquiry->id][$productId] - 1)) {
                                    echo '</tr>';
                                }
                                $j++;
                                ?>
                                @endforeach
                                @endif

                                <?php
                                if ($i < ($inquryRowSpanArr[$inquiry->id] - 1)) {
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
                                <td class="vcenter text-danger font-size-11" colspan="{!! (empty($request->sales_person_id) || $request->sales_person_id == 0) ? 20 : 19 !!}">@lang('label.NO_DATA_FOUND')</td>
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
                    @lang('label.GENERATED_ON') {{ Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}.
                </td>
                <td class="no-border text-right font-size-11">
                    @lang('label.GENERATED_FROM_KTI')
                </td>
            </tr>
        </table>

        <!--//end of footer-->
        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>
        <script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!--<script src="{{asset('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>-->



        <script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
        <script type="text/javascript">
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>