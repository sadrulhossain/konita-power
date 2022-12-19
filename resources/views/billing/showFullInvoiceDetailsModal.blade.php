<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="text-right">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
        <h3 class="modal-title text-center">
            @lang('label.FULL_INVOICE')
        </h3>
    </div>
    <div class="modal-body">
        <!--Endof_BL_history data-->
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-offset-4 col-md-4">
                    <h4 class="text-center bold uppercase margin-top-0">@lang('label.FULL_INVOICE')</h4>
                </div>
                <div class="col-md-4 text-right">
                    <!--print-->
                    @if(!empty($userAccessArr[41][6]))
                    <a class="btn btn-xs blue-soft tooltips vcenter" target="_blank" href="{{ URL::to('billing/billingFullLedgerDetailsPrint?view=print&invoice_id=' . $invoiceInfo->id) }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class=" col-md-12 margin-top-10 margin-bottom-10">
                                <div class="margin-bottom-20">
                                    <span>@lang('label.DATE'): {{!empty($invoiceInfo->date)?Helper::formatdate($invoiceInfo->date):''}}</span><br/>
                                    <span>@lang('label.INVOICE_NO'): {{!empty($invoiceInfo->invoice_no)?$invoiceInfo->invoice_no:''}}</span><br/>
                                </div>
                                <div>
                                    <span>@lang('label.ATTN'):</span><br/>
                                    <span>{{!empty($invoiceInfo->supplier_contact_person)?$invoiceInfo->supplier_contact_person:''}}</span><br/>
                                    <span class="bold">{{!empty($supplierInfo->name)?$supplierInfo->name:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->address)?$supplierInfo->address:''}}</span><br/>
                                    <span>{{!empty($supplierInfo->countryName)?$supplierInfo->countryName:''}}</span><br/>   
                                </div>
                                <div class="margin-top-20">
                                    <span>{{!empty($invoiceInfo->subject)?$invoiceInfo->subject:''}}</span> 
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                <th class="vcenter">@lang('label.ORDER_NO')</th>
                                                <th class="vcenter">@lang('label.BUYER')</th>
                                                <th class="vcenter">@lang('label.BL_NO')</th>
                                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                                <th class="vcenter">@lang('label.BRAND')</th>
                                                <th class="vcenter">@lang('label.GRADE')</th>
                                                <th class="vcenter">@lang('label.GSM')</th>
                                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                                <th class="vcenter text-center">@lang('label.COMMISSION')</th>
                                                <th class="vcenter text-center">@lang('label.TOTAL_COMMISSION')</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @if(!empty($billingArr))
                                            <?php
                                            $sl = 0;
                                            ?>
                                            @foreach($billingArr as $inquiryId=>$target)
                                            <?php
                                            $rowspan = !empty($rowspanOrder[$inquiryId]) ? $rowspanOrder[$inquiryId] : 0;
                                            
                                            ?>
                                        <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!!++$sl!!}</td>
                                        <td class="vcenter" rowspan="{{$rowspan}}">{{ $target['order_no'] }}</td>
                                        <td class="vcenter" rowspan="{{$rowspan}}">{{ $target['buyer_name'] }}</td>

                                        <?php
                                        $i = 0;
                                        ?>
                                        @foreach($billingArr2[$inquiryId] as $deliveryId=> $item)

                                        <?php
                                        if ($i > 0) {
                                            echo '<tr>';
                                        }
                                        $rowspanBl = !empty($rowspanArr[$inquiryId][$deliveryId]) ? $rowspanArr[$inquiryId][$deliveryId] : 0;
                                        $blNo = wordwrap(!empty($item['bl_no']) ? $item['bl_no'] : '', 8, "\n", true);
                                        ?>
                                        <td class="text-center vcenter" rowspan="{{$rowspanBl}}">
                                            {{ $blNo }} 
                                        </td>
                                        <?php
                                        $j = 0;
                                        ?>
                                        @foreach($item['bl_details'] as $deliveryDetailsId=>$deliveryDetails)

                                        <?php
                                        if ($j > 0) {
                                            echo '<tr>';
                                        }
                                        $totalKonitaComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn'] : 0;
                                        $konitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                                        ?>
                                        <td>
                                            {{!empty($deliveryDetails['product_name'])?$deliveryDetails['product_name']:''}}
                                        </td>
                                        <td>
                                            {{!empty($deliveryDetails['brand_name'])?$deliveryDetails['brand_name']:''}}
                                        </td>
                                        <td>
                                            {{!empty($deliveryDetails['grade_name'])?$deliveryDetails['grade_name']:''}}
                                        </td>
                                        <td>
                                            {{!empty($deliveryDetails['gsm'])?$deliveryDetails['gsm']:''}}
                                        </td>
                                        <td class="text-right vcenter">
                                            {{ !empty($deliveryDetails['shipment_qty'])?$deliveryDetails['shipment_qty']:'' }}&nbsp;@lang('label.UNIT')

                                        </td>

                                        <td class="text-right vcenter">
                                            ${{ $konitaCmsn }} 
                                        </td>

                                        <td class="text-right vcenter">
                                            ${{ $totalKonitaComsn }}
                                        </td>
                                        <?php
                                        $j++;
                                        ?>
                                        </tr>
                                        @endforeach
                                        <?php
                                        $i++;
                                        ?>
                                        </tr>
                                        @endforeach
                                        </tr>
                                        @endforeach
                                        <!--sub_total-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10">@lang('label.SUBTOTAL')</td>
                                            <td class="vcenter bold  text-right">
                                                <span>$</span>{{!empty($invoiceInfo->sub_total)?Helper::numberFormat2Digit($invoiceInfo->sub_total):Helper::numberFormat2Digit(0)}}
                                            </td>
                                        </tr>
                                        <!--admon cost-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10">@lang('label.ADMIN_COST')</td>
                                            <td class="vcenter bold text-right">
                                                <span>- $</span>{{!empty($invoiceInfo->admin_cost)?Helper::numberFormat2Digit($invoiceInfo->admin_cost):Helper::numberFormat2Digit(0)}}
                                            </td>
                                        </tr>
                                        <!--net_receivable-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10">@lang('label.NET_RECEIVABLE')</td>
                                            <td class="vcenter bold  text-right">
                                                <span>$</span>{{!empty($invoiceInfo->net_receivable)?Helper::numberFormat2Digit($invoiceInfo->net_receivable):Helper::numberFormat2Digit(0)}}
                                            </td>
                                        </tr>
                                        <!--gift-->
                                        <tr>
                                            <?php
                                            $gift = '--';
                                            $textAlignment = 'center';
                                            if (($invoiceInfo->gift != 0.00) && (!empty($invoiceInfo->gift))) {
                                                $gift = '$' . Helper::numberFormat2Digit($invoiceInfo->gift);
                                                $textAlignment = 'right';
                                            }
                                            ?>
                                            <td class="vcenter text-right bold" colspan="10">@lang('label.GIFT')&nbsp;{{!empty($invoiceInfo->gift_title)?'('.$invoiceInfo->gift_title.')':''}}</td>
                                            <td class="vcenter bold text-{{ $textAlignment }}">{!! $gift !!}</td>
                                        </tr>
                                        <!--total amount-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10">@lang('label.TOTAL_AMOUNT')</td>
                                            <td class="vcenter bold  text-right">
                                                <span>$</span>{{!empty($invoiceInfo->total_amount)?Helper::numberFormat2Digit($invoiceInfo->total_amount):Helper::numberFormat2Digit(0)}}
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="18">@lang('label.NO_DATA_FOUND')</td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--koniat bank information-->
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="col-md-6">
                            <div>
                                <span class="bold">@lang('label.BANK_INFORMATION')</span>
                            </div>
                            <div>
                                <span class="bold">@lang('label.BANK_NAME'): </span>{{!empty($konitaBankInfo->bank_name)?$konitaBankInfo->bank_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NO'): </span>{{!empty($konitaBankInfo->account_no)?$konitaBankInfo->account_no:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.ACCOUNT_NAME'): </span>{{!empty($konitaBankInfo->account_name)?$konitaBankInfo->account_name:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.BRANCH'): </span>{{!empty($konitaBankInfo->branch)?$konitaBankInfo->branch:''}}
                            </div>
                            <div>
                                <span class="bold">@lang('label.SWIFT'): </span>{{!empty($konitaBankInfo->swift)?$konitaBankInfo->swift:''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<!-- END:: Contact Person Information-->