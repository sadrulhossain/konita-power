<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.DETAILS_OF_QUOTATION_REQUEST')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            <div>
                <?php
                $printUrl = '';
                if (Auth::user()->group_id != '0') {
                    $printUrl = 'quotationRequest/buyerQuotationReqDetails/';
                } else {
                    $printUrl = 'buyerQuotationRequest/quotationReqDetails/';
                }
                ?>
                @if(!empty($userAccessArr[88][6]) || Auth::user()->group_id == '0')
                <a href="{{ URL::to($printUrl.$quotationInfoArr->quotation_id.'?view=print') }}" target="_blank" class="btn btn-md btn-success pull-right margin-right-10">
                    <i class="fa fa-print text-white"></i> @lang('label.PRINT')
                </a>
                @endif
            </div>

        </div>
    </div>
    <div class="modal-body">
        <!--BASIC INFORMATION-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BASIC_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    @if(Auth::user()->group_id != '0')
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <table class="table table-borderless margin-bottom-0">
                            <tr >
                                <td class="bold" width="20%">@lang('label.BUYER')</td>
                                <td width="50%">{!! !empty($buyerInfo->name) ? $buyerInfo->name : __('label.N_A') !!}</td>
                            </tr>    
                            <tr >
                                <td class="bold" width="20%">@lang('label.ADDRESS')</td>
                                <td width="50%">{!! !empty($buyerInfo->head_office_address) ? $buyerInfo->head_office_address :__('label.N_A') !!}</td>
                            </tr>     
                        </table>
                    </div>
                    @endif
                    <div class="col-md-12 col-lg-12 col-sm-6">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="20%">@lang('label.QUOTATION_REQUEST')</td>
                                <td width="50%" class="text-justify">
                                    {!! !empty($quotationInfoArr->description)?$quotationInfoArr->description:__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="20%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($quotationInfoArr->read_status == '1')
                                    <span class="label label-sm label-blue-madison">{!! __('label.READ') !!}</span>
                                    @else
                                    <span class="label label-sm label-grey-cascade">{!! __('label.PENDING') !!}</span>
                                    @endif
                                </td>
                            </tr>     
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <!--END OF BASIC INFORMATION-->


        <!--PRODUCT DETAILS-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.PRODUCT_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                        <th class="text-center vcenter width-50">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productArr))
                                    <?php $sl = 0; ?>
                                    @foreach($productArr as $pKey => $pInfo)
                                    <?php
                                    $product = !empty($productListArr[$pInfo['product_id']]) && $pInfo['product_id'] != 0 ? $productListArr[$pInfo['product_id']] : '';
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $product ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $pInfo['gsm'] ?? '' !!}</td>
                                        <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . ' ' . $pInfo['unit']!!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="vcenter" colspan="4">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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