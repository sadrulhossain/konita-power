<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RW_BREAKDOWN_PREVIEW')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'rwBreakDownSaveForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}

                {!! Form::hidden('product_id', $request->product_id) !!} 
                {!! Form::hidden('brand_id', $request->brand_id) !!} 
                {!! Form::hidden('grade_id', $request->grade_id) !!}
                {!! Form::hidden('format', $request->format) !!}
                {!! Form::hidden('input_unit_id', $request->input_unit_id) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive min-height-rw">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="vcenter">@lang('label.PRODUCT')</th>
                                            <th class="vcenter">@lang('label.BRAND')</th>
                                            <th class="vcenter">@lang('label.GRADE')</th>
                                            <th class="vcenter">@lang('label.CORE_AND_DIA')</th>
                                            <th class="vcenter">
                                                <?php
                                                $checked = '';
                                                if (!empty($rwBreakdownInfo->has_bf) && $rwBreakdownInfo->has_bf == '1') {
                                                    $checked = 'checked';
                                                }
                                                ?>


                                                <div class="col-md-4 md-checkbox has-success tooltips" title="@lang('label.PUT_TICK_IF_HAS_BF')">
                                                    {!! Form::checkbox('has_bf',1,null, ['id' => 'hasBf', 'class'=> 'md-check',$checked]) !!}
                                                    <label for="hasBf">
                                                        <span class="inc tooltips"></span>
                                                        <span class="check mark-caheck tooltips"></span>
                                                        <span class="box mark-caheck tooltips"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    @lang('label.BF')  
                                                </div>
                                            </th>
                                            @if($request->format == 1)
                                            <th class="vcenter">@lang('label.QUANTITY')&nbsp;({{!empty($measureUnitInfo->unitName)?$measureUnitInfo->unitName:__('label.MT')}})</th>
                                            @endif
                                            <th class="vcenter">@lang('label.GSM')</th>
                                            @if(!empty($rwParameter))
                                            @foreach($rwParameter as $rwId=>$item)
                                            <th class="vcenter">@lang('label.RW')&nbsp;({{$item}})</th>
                                            @endforeach
                                            @endif
                                            @if($request->format == 2)
                                            <th class="vcenter">@lang('label.QUANTITY')&nbsp;({{!empty($measureUnitInfo->unitName)?$measureUnitInfo->unitName:__('label.MT')}})</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum}}">{{$target->productName}}</td>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum}}">{{$target->brandName}}</td>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum}}">
                                        {{!empty($target->gradeName)?$target->gradeName:''}}
                                    </td>
                                    <td class="vcenter" rowspan="{{$gsmDataCountSum}}">
                                        <div>{!! Form::select('rw_parameter[]', $rwParameterList,!empty($rwUnitIdArr2)?$rwUnitIdArr2:null, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'rwParaId','multiple','data-width' => '100%','data-label'=>"center", 'data-select-all'=>"true",'data-filter'=>"true",
                                            'data-action-onchange'=>"true"]) !!}</div>
                                        <div class="core-dia-style-3">
                                            {!! Form::text('core_and_dia', !empty($rwBreakdownInfo->core_and_dia)?$rwBreakdownInfo->core_and_dia:null, ['class' => 'core-dia-style', 'autocomplete' => 'off']) !!}
                                            <span class="core-dia-style-2 bold" id="rwUnitName">{{!empty($rwInfo)?$rwInfo:''}}</span>
                                        </div>
                                    </td>
                                    @if(!empty($gsmDataArr))
                                    <?php $j = 1; ?>
                                    @foreach($gsmDataArr as $gsmId=>$item)

                                    <?php $i = 0; ?>

                                    <td class="vcenter" rowspan="{{$gsmDataCountArr[$gsmId]}}">
                                        {!! Form::text('bf['.$j.']',!empty($bfArr[$gsmId])?$bfArr[$gsmId]:null,['class'=>'form-control w-100 bf','autocomplete'=>'off','disabled']) !!}
                                    </td>
                                    @if($request->format == 2)
                                    <td class="vcenter" rowspan="{{$gsmDataCountArr[$gsmId]}}">{{!empty($gsmValueArr1[$gsmId])?$gsmValueArr1[$gsmId]:''}}
                                        {!! Form::hidden('gsm['.$j.']', $gsmValueArr1[$gsmId]) !!}
                                    </td>
                                    @endif
                                    

                                    @foreach($item as $key=>$values)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <!--rw Data foreach-->
                                    @if(!empty($rwParameter))
                                    @if($request->format == 1)
                                    <!--QUANTITY td-->
                                    <td class="vcenter">
                                        {!! Form::text('gsmDetails['.$j.']['.$i.'][quantity]', $values['quantity'], ['class' => 'form-control w-100', 'readonly']) !!}
                                    </td>
                                    <td class="vcenter">{{!empty($gsmValueArr1[$gsmId])?$gsmValueArr1[$gsmId]:''}}
                                        {!! Form::hidden('gsm['.$j.']', $gsmValueArr1[$gsmId]) !!}
                                    </td>
                                    @endif
                                    @foreach($rwParameter as $rwId=>$item)
                                    <td class="vcenter">
                                        {!! Form::text('gsmDetails['.$j.']['.$i.']['.$rwId.']', $values[$rwId], ['class' => 'form-control w-100', 'readonly']) !!}
                                    </td>
                                    @endforeach
                                    @if($request->format == 2)
                                    <!--QUANTITY td-->
                                    <td class="vcenter">
                                        {!! Form::text('gsmDetails['.$j.']['.$i.'][quantity]', $values['quantity'], ['class' => 'form-control w-100', 'readonly']) !!}
                                    </td>
                                    @endif
                                    @endif
                                    <!--END rw Data foreach-->
                                    </tr>
                                    <?php
                                    $i++;
                                    ?>
                                    @endforeach
                                    </tr>
                                    <?php
                                    $j++;
                                    ?>
                                    @endforeach
                                    @endif
                                    </tr>
                                    
                                    <tr>
                                        <td class="vcenter bold text-right" colspan="{{ $request->format == 1 ? 5 : (6 + count($rwParameter))}}">@lang('label.TOTAL_QUANTITY')</td> 
                                        <td class="vcenter bold" colspan="{{$request->format == 1 ? (count($rwParameter) + 2) : 1}}">{{$totalQuantity}}&nbsp;{{!empty($measureUnitInfo->unitName)?$measureUnitInfo->unitName:__('label.MT')}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <button class="btn btn-inline green submit-rw-save" type="button" id="submitRwSave" data-status="1">
                    <i class="fa fa-check"></i> @lang('label.SAVE')
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- END:: Contact Person Information-->
<script type="text/javascript">
    $(document).ready(function () {
        //multiselect
        $('#rwParaId').multiselect({
            buttonWidth: '212px',
            includeSelectAllOption: true,
            selectAllText: "@lang('label.SELECT_BOTH')",
            nonSelectedText: "@lang('label.SELECT_RW_UNIT_OPT')",
        });


      //CHECK BF
        $("#hasBf").click(function () {
            if ($(this).is(":checked")) {
                $(".bf").prop("disabled", false);
            } else {
                $(".bf").prop("disabled", true);
            }
        });

<?php if (!empty($rwBreakdownInfo->has_bf) && $rwBreakdownInfo->has_bf == '1') { ?>
            $(".bf").prop("disabled", false);
<?php } else { ?>
            $(".bf").prop("disabled", true);
<?php }
?>
        //END OF CHECK BF





    });
</script>