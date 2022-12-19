<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.OPPORTUNITY_DETAILS')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
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
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless margin-bottom-0">
                            <?php
                            if ($target->buyer_has_id == '0') {
                                $buyer = $target->buyer;
                            } elseif ($target->buyer_has_id == '1') {
                                $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                            }
                            ?>
                            <tr >
                                <td class="bold" width="50%">@lang('label.BUYER')</td>
                                <td width="50%">{!! !empty($buyer) ? $buyer : __('label.N_A') !!}</td>
                            </tr> 
                            <tr >
                                <td class="bold" width="50%">@lang('label.CREATED_BY')</td>
                                <td width="50%">{!! !empty($target->opportunity_creator)?$target->opportunity_creator:__('label.N_A') !!}</td>
                            </tr>  
                            <tr >
                                <td class="bold" width="50%">@lang('label.ASSIGNED_TO')</td>
                                <td width="50%">{!! !empty($assignedPersonList[$target->id])?$assignedPersonList[$target->id]:__('label.N_A') !!}</td>
                            </tr> 
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="50%">@lang('label.SOURCE')</td>
                                <td width="50%">
                                    {!! !empty($target->source)?$target->source:__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($target->status == '0')
                                    <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                    @elseif($target->status == '1')
                                    @if($target->revoked_status == '0')
                                    @if($target->last_activity_status == '0')
                                    <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                    @elseif($target->last_activity_status == '1')
                                    <span class="label label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '2')
                                    <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '3')
                                    <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '4')
                                    <span class="label label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '5')
                                    <span class="label label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '6')
                                    <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '8')
                                    <span class="label label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '9')
                                    <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '10')
                                    <span class="label label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '11')
                                    <span class="label label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '12')
                                    <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @endif
                                    @elseif($target->revoked_status == '1')
                                    <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                    @endif
                                    @elseif($target->status == '2')
                                    @if($target->dispatch_status == '0')
                                    <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                    @elseif($target->dispatch_status == '1')
                                    @if($target->approval_status == '0')
                                    <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                    @elseif($target->approval_status == '1')
                                    <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                    @elseif($target->approval_status == '2')
                                    <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                    @endif
                                    @endif
                                    @elseif($target->status == '3')
                                    <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                    @elseif($target->status == '4')
                                    <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                    @endif
                                </td>
                            </tr>     
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="25%">@lang('label.ADDRESS')</td>
                                <td width="75%">
                                    {!! !empty($target->address)?$target->address:__('label.N_A') !!}
                                </td>
                            </tr>    
                            <tr>
                                <td class="bold" width="25%">@lang('label.REMARKS')</td>
                                <td width="75%">
                                    {!! !empty($target->remarks)?$target->remarks:__('label.N_A') !!}
                                </td>
                            </tr>    
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC INFORMATION-->

        <!--CONTACT PERSON DETAILS-->
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter">@lang('label.NAME')</th>
                                        <th class="text-center vcenter">@lang('label.DESIGNATION')</th>
                                        <th class="text-center vcenter">@lang('label.EMAIL')</th>
                                        <th class="text-center vcenter">@lang('label.PHONE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($contactArr))
                                    <?php $sl = 0; ?>
                                    @foreach($contactArr as $cKey => $cInfo)
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $cInfo['name'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['designation'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['email'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['phone'] ?? __('label.N_A') !!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="vcenter" colspan="6">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF CONTACT PERSON DETAILS-->

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
                                        <th class="text-center vcenter">@lang('label.BRAND')</th>
                                        <th class="text-center vcenter">@lang('label.GRADE')</th>
                                        <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                        <th class="text-center vcenter">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productArr))
                                    <?php $sl = 0; ?>
                                    @foreach($productArr as $pKey => $pInfo)
                                    <?php
                                    //product
                                    if ($pInfo['product_has_id'] == '0') {
                                        $product = $pInfo['product'];
                                    } elseif ($pInfo['product_has_id'] == '1') {
                                        $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                    }
                                    //brand
                                    if ($pInfo['brand_has_id'] == '0') {
                                        $brand = $pInfo['brand'];
                                    } elseif ($pInfo['brand_has_id'] == '1') {
                                        $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                    }
                                    //grade
                                    if ($pInfo['grade_has_id'] == '0') {
                                        $grade = $pInfo['grade'];
                                    } elseif ($pInfo['grade_has_id'] == '1') {
                                        $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                    }

                                    //Origin
                                    $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                    $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                    $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                    $statusTitle = __('label.FINAL_PRODUCT');
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">
                                            {!! $product ?? __('label.N_A') !!}
                                            @if(!empty($pInfo['final']) && $pInfo['final'] == '1')
                                            <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ $statusTitle }}">

                                            </button>
                                            @endif
                                        </td>
                                        <td class="vcenter">{!! $brand ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $grade ?? __('label.N_A') !!}</td>
                                        <td class="text-center vcenter">{!! $country !!}</td>
                                        <td class="vcenter">{!! (!empty($pInfo['gsm'])) ? $pInfo['gsm'] : '' !!}</td>
                                        <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                        <td class="text-right vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                        <td class="text-right vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="vcenter" colspan="5">@lang('label.NO_DATA_FOUND')</td>
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