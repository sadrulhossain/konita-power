<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RW_BREAKDOWN_VIEW')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <!--first loop-->
                        <?php
                        $flag = 1;
                        ?>
                        @if(!empty($targetArr))
                        @foreach($targetArr as $id=>$target)
                        @if(!empty($target['gsm_details']))
                        <?php
                        $flag = 0;
                        ?>
                        @break
                        @endif
                        @endforeach
                        @endif

                        <!--final loop-->
                        @if(!empty($targetArr))
                        @foreach($targetArr as $id=>$target)
                        @if(!empty($target['gsm_details']))
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="vcenter">@lang('label.PRODUCT')</th>
                                            <th class="vcenter">@lang('label.BRAND')</th>
                                            <th class="vcenter">@lang('label.GRADE')</th>
                                            <th class="vcenter">@lang('label.CORE_AND_DIA')</th>
                                            <th class="text-center vcenter">@lang('label.BF')</th>
                                            @if($target['format'] == 1)
                                            <th class="text-center vcenter">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                            @endif
                                            <th class="text-center vcenter">@lang('label.GSM')</th>
                                            @if(!empty($rwParameter[$id]))
                                            @foreach($rwParameter[$id] as $rwId=>$rwName)
                                            <th class="text-center vcenter">@lang('label.RW')&nbsp;({{$rwName}})</th>
                                            @endforeach
                                            @endif
                                            @if($target['format'] == 2)
                                            <th class="text-center vcenter">@lang('label.QUANTITY')&nbsp;({{$target['unit_name']}})</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['product_name']}}</td>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['brand_name']}}</td>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">{{$target['grade_name']}}</td>

                                    <td class="vcenter" rowspan="{{$gsmDataCountSum[$id]}}">
                                        {{$target['core_and_dia']}}&nbsp;{{!empty($rwInfo[$id])?$rwInfo[$id]:''}}
                                    </td>

                                    @if(!empty($target['gsm_details']))
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($target['gsm_details'] as $gsmId=>$gsmVal)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }

                                    $j = 0;
                                    ?>
                                    <td class="vcenter text-right" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                        {{!empty($target['bf_info'][$gsmId])?$target['bf_info'][$gsmId]:''}}
                                    </td>
                                    @if($target['format'] == 2)
                                    <td class="vcenter text-right" rowspan="{{$gsmDataCountArr[$id][$gsmId]}}">
                                        {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                                    </td>
                                    @endif
                                    @foreach($gsmVal as $values)
                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    @if(!empty($rwParameter[$id]))
                                    @if($target['format'] == 1)
                                    <td class="vcenter text-right">
                                        {{!empty($values['quantity'])?$values['quantity']:''}}
                                    </td>
                                    <td class="vcenter text-right">
                                        {{!empty($target['gsm_info'][$gsmId])?$target['gsm_info'][$gsmId]:''}}
                                    </td>
                                    @endif
                                    @foreach($rwParameter[$id] as $rwId=>$rwName)
                                    <td class="vcenter text-right">
                                        {{!empty($values[$rwId])?$values[$rwId]:''}}
                                    </td>
                                    @endforeach
                                    @endif
                                    @if($target['format'] == 2)
                                    <td class="vcenter text-right">
                                        {{!empty($values['quantity'])?$values['quantity']:''}}
                                    </td>
                                    @endif
                                    </tr>
                                    <?php
                                    $j++;
                                    ?>
                                    @endforeach
                                    </tr>
                                    <?php
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endif
                                    </tr>
                                    <tr>
                                        <td class="bold text-right" colspan="{{6+count($rwParameter[$id])}}">@lang('label.TOTAL_QUANTITY')</td>
                                        <td class="bold text-right">{{!empty($totalQuantity[$id])?$totalQuantity[$id]:''}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        @if($flag == 1)
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <td class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endif
                        @endforeach
                        @endif
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
<!-- END:: Contact Person Information-->