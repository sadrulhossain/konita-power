<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="text-right">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
        <h3 class="modal-title text-center">
            @lang('label.COMMISSION_DETAILS')
        </h3>
    </div>
    <div class="modal-body">
        <!--Endof_BL_history data-->
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.BRAND')</th>
                                    <th class="vcenter">@lang('label.GRADE')</th>
                                    <th class="vcenter">@lang('label.GSM')</th>
                                    <th class="vcenter text-center">@lang('label.KONITA_CMSN')</th>
                                    <th class="vcenter text-center">@lang('label.PRINCIPLE_COMMISSION')</th>
                                    <th class="vcenter text-center">@lang('label.SALES_PERSON_COMMISSION')</th>
                                    <th class="vcenter text-center">@lang('label.BUYER_COMMISSION')</th>
                                    <th class="vcenter text-center">@lang('label.REBATE_COMMISSION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($targetArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($targetArr as $inquiryId => $orderNo)
                                <?php
                                $rowsapn = !empty($rowspanArr[$inquiryId]['order_row_span']) ? $rowspanArr[$inquiryId]['order_row_span'] : 1;
                                ?>
                                <tr>
                                    <td class="vcenter text-center" rowspan="{{$rowsapn}}">{{++$sl}}</td>
                                    <td class="vcenter" rowspan="{{$rowsapn}}">{{!empty($orderNo['order_no']) ? $orderNo['order_no'] : ''}}</td>
                                    <?php
                                    $i = 0;
                                    ?>
                                    @if(!empty($inqDetailsArr[$inquiryId]))

                                    @foreach($inqDetailsArr[$inquiryId] as $inquiryDetailsId => $item)
                                    <?php
                                    if($i > 0){
                                        echo '<tr>';
                                    }
                                    $konitaComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                                    $principalComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['principle_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['principle_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['principle_cmsn']) ? $prevComsnArr[$inquiryId][0]['principle_cmsn'] : 0));
                                    $salesPersonComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['sales_person_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['sales_person_cmsn']) ? $prevComsnArr[$inquiryId][0]['sales_person_cmsn'] : 0));
                                    $buyerComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['buyer_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['buyer_cmsn']) ? $prevComsnArr[$inquiryId][0]['buyer_cmsn'] : 0));
                                    $rebateComsn = (!empty($prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][$inquiryDetailsId]['rebate_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['rebate_cmsn']) ? $prevComsnArr[$inquiryId][0]['rebate_cmsn'] : 0));
                                    ?>
                                    <td class="vcenter">{{!empty($item['product_name']) ? $item['product_name'] : ''}}</td>
                                    <td class="vcenter">{{!empty($item['brand_name']) ? $item['brand_name'] : ''}}</td>
                                    <td class="vcenter">{{!empty($item['grade_name']) ? $item['grade_name'] : ''}}</td>
                                    <td class="vcenter">{{!empty($item['gsm']) ? $item['gsm'] : ''}}</td>
                                    <td class="vcenter text-right">${{$konitaComsn}}</td>
                                    <td class="vcenter text-right">${{$principalComsn}}</td>
                                    <td class="vcenter text-right">${{$salesPersonComsn}}</td>
                                    <td class="vcenter text-right">${{$buyerComsn}}</td>
                                    <td class="vcenter text-right">${{$rebateComsn}}</td>
                                    <?php
                                    $i++;
                                    ?>
                                </tr>
                                    @endforeach
                                    @endif
                                    
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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