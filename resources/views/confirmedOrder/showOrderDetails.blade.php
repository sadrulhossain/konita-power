<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.CONFIRMED_ORDER_DETAILS')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

        </div>
    </div>
    <div class="modal-body">

        <!--BASIC ORDER INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.ORDER_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.ORDER_NO')</td>
                                <td width="50%">{!! !empty($orderInfo->order_no)?$orderInfo->order_no:'' !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.BUYER')</td>
                                <td width="50%">{!! !empty($orderInfo->buyer_name)?$orderInfo->buyer_name:'' !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.SALES_PERSON')</td>
                                <td width="50%">{!! !empty($orderInfo->salesPersonName)?$orderInfo->salesPersonName:'' !!}</td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.SUPPLIER')</td>
                                <td width="50%">
                                    {!! !empty($orderInfo->supplier_name)?$orderInfo->supplier_name:'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($orderInfo->order_status == '2')
                                    <span class="label label-sm label-primary">@lang('label.CONFIRMED')</span>
                                    @elseif($orderInfo->order_status == '3')
                                    <span class="label label-sm label-info">@lang('label.PROCESSING_DELIVERY')</span>
                                    @endif
                                </td>
                            </tr>     
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%">@lang('label.PURCHASE_ORDER_NO')</td>
                                <td width="50%">
                                    {!! !empty($orderInfo->purchase_order_no)?$orderInfo->purchase_order_no:'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.PO_DATE')</td>
                                <td width="50%">
                                    {!! !empty($orderInfo->po_date)?Helper::formatDate($orderInfo->po_date):'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.CREATION_DATE')</td>
                                <td width="50%">
                                    {!! !empty($orderInfo->creation_date)?Helper::formatDate($orderInfo->creation_date):'' !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.PI_DATE')</td>
                                <td width="50%">
                                    {!! !empty($orderInfo->pi_date)?Helper::formatDate($orderInfo->pi_date):'' !!}
                                </td>
                            </tr>        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC ORDER INFORMATION-->

        <!--LC INFORMATION-->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive  div-box-default">
                    <table class="table table-borderless">
                        <tr>                          
                            <td class="fit bold  vcenter">@lang('label.LC_NO')</td>
                            <td colspan='5' class="vcenter">{!! !empty($orderInfo->lc_no)?$orderInfo->lc_no:'' !!}</td>

                            <td class="fit bold  vcenter">@lang('label.LC_DATE')</td>
                            <td colspan='5' class="vcenter">{!! !empty($orderInfo->lc_date)?Helper::formatDate($orderInfo->lc_date):'' !!}</td>
                        </tr>

                        <tr>
                            <td class="fit bold  vcenter">@lang('label.LC_TRANSMITTED_COPY_DONE')</td>
                            <td colspan='5' class="vcenter">
                                @if($orderInfo->lc_transmitted_copy_done == '1')
                                <span class="label label-sm label-info">@lang('label.YES')</span>
                                @elseif($orderInfo->lc_transmitted_copy_done == '0')
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="fit bold  vcenter">@lang('label.LC_ISSUE_DATE')</td>
                            <td colspan='5' class="vcenter">{!! !empty($orderInfo->lc_issue_date)?Helper::formatDate($orderInfo->lc_issue_date):'' !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold  vcenter">@lang('label.BANK')</td>
                            <td colspan='5' class="vcenter">
                                {!! !empty($orderInfo->lc_opening_bank)?$orderInfo->lc_opening_bank:'' !!}
                            </td>
                            <td class="fit bold  vcenter">@lang('label.BRANCH')</td>
                            <td colspan='5' class="vcenter">{!! !empty($orderInfo->bank_barnch)?$orderInfo->bank_barnch:'' !!}</td>
                        </tr>
                        <tr>
                            <td class="fit bold  vcenter">@lang('label.NOTE_')</td>
                            <td colspan='5' class="vcenter">{!! !empty($orderInfo->note)?$orderInfo->note:'' !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--END OF LC INFORMATION-->

        <!--product details-->
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="vcenter">@lang('label.PRODUCT')</th>
                                <th class="vcenter">@lang('label.BRAND')</th>
                                <th class="vcenter">@lang('label.GRADE')</th>
                                <th class="vcenter">@lang('label.GSM')</th>
                                <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$inquiryDetails->isEmpty())
                            @foreach($inquiryDetails as $item)
                            <?php
                            $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                            $perUnit = !empty($item->unit_name) ? ' / ' . $item->unit_name : '';
                            ?>
                            <tr>
                                <td class="vcenter">{{ !empty($item->product_name)?$item->product_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->brand_name)?$item->brand_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->grade_name)?$item->grade_name:'' }}</td>
                                <td class="vcenter">{{ !empty($item->gsm)?$item->gsm:'' }}</td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->quantity) ? $item->quantity . $unit : __('label.N_A') }}
                                </td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->unit_price) ? '$' . $item->unit_price . $perUnit : __('label.N_A') }}
                                </td>
                                <td class="vcenter text-right">
                                    {{ !empty($item->total_price) ? '$' . $item->total_price : __('label.N_A') }}
                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
</script>