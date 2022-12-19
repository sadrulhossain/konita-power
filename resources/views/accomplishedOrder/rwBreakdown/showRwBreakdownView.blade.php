<div class="row margin-top-10">
    <h5 class="rw-border-style"></h5>
    <!-- Begin Proced-->
    {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'proceedForm','class' => 'form-horizontal')) !!}
    {!! Form::hidden('inquiry_id', $request->inquiry_id) !!} 
    {!! Form::hidden('product_id', $request->product_id) !!} 
    {!! Form::hidden('brand_id', $request->brand_id) !!} 
    {!! Form::hidden('grade_id', $request->grade_id) !!} 
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-md-4" for="gsmId">@lang('label.GSM') :<span class="text-danger"> *</span></label>
            <div class="col-md-8">
                {!! Form::hidden('gsm_flag', !empty($gsmFlag) ? $gsmFlag : 0) !!}
                @if($gsmFlag == 1)
                @if(!$gsmArrInfo->isEmpty())
                @php $i = 0; @endphp
                <div class="field_wrapper">
                    @foreach($gsmArrInfo as $info)
                    <div>
                        {!! Form::text('gsmVal['.++$i.']', !empty($info->gsm) ? $info->gsm : null, ['class'=>'form-control-gsm','size' =>'24x2', 'id' => 'gsmId_1','readonly']) !!}
                    </div><br/>
                    {!! Form::hidden('qtyVal['.$i.']', !empty($info->quantity) ? $info->quantity : null) !!}
                    <?php
                    $arr[$i] = $info->quantity;
                    ?>
                    @endforeach
                </div>
                @endif
                @elseif($gsmFlag == 0)
                @if(!empty($gsmInfo))
                <?php
                $iCount = 1;
                $showCloseBtn = 1;
                ?>

                <div class="field_wrapper">
                    @foreach($gsmInfo as $gsmId=>$gsmVal)
                    <div>
                        {!! Form::text('gsmVal['.$gsmId.']',  !empty($gsmVal)?$gsmVal:null, ['class'=>'form-control-gsm gsm-val-control','size' =>'24x2', 'id' => 'gsmId_'.$iCount,'autocomplete' => 'off','readonly']) !!}

                    </div><br />

                    @endforeach 

                </div>
                @else
                <div class="field_wrapper">
                    <div>
                        {!! Form::text('gsmVal[]',  null, ['class'=>'form-control-gsm gsm-val-control','size' =>'24x2','readonly']) !!}
                    </div>

                </div>
                @endif
                @endif
            </div>
        </div>



        <div class="form-group">
            <label class="control-label col-md-4" for="rwParameterId">@lang('label.RW_PARAMETERS') :<span class="text-danger"> *</span></label>
            <div class="col-md-8">
                {!! Form::select('rw_parameters[]', $rwParameterList, !empty($rwUnitIdArr)?$rwUnitIdArr:null, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'rwParameterId','multiple','data-width' => '100%','data-label'=>"center", 'data-select-all'=>"true",'data-filter'=>"true",
                'data-action-onchange'=>"true"]) !!}
                <span class="text-danger">{{ $errors->first('rw_parameters') }}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4" for="inputUnitId">@lang('label.IPUT_UNIT') :<span class="text-danger"> *</span></label>
            <div class="col-md-3">
                {!! Form::select('input_unit_id', $inputUnitArr, !empty($rwBreakdownInfo->input_unit_id) ? $rwBreakdownInfo->input_unit_id : null, ['class' => 'form-control js-source-states', 'id' => 'inputUnitId']) !!}
                <span class="text-danger">{{ $errors->first('input_unit_id') }}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4" for="formatId">@lang('label.FORMAT') :<span class="text-danger"> *</span></label>
            <div class="col-md-3">
                {!! Form::select('format', $formatArr, !empty($rwBreakdownInfo->format) ? $rwBreakdownInfo->format : null, ['class' => 'form-control js-source-states', 'id' => 'formatId']) !!}
                <span class="text-danger">{{ $errors->first('format') }}</span>
            </div>
        </div>

        @if(empty($gsmDetailsInfo))
        <div class="form-group">
            <label class="control-label col-md-4" for="rwProceedId">@lang('label.RW_PARAMETERS') :</label>
            <div class="col-md-8 checkbox-center md-checkbox has-success">
                <input type="hidden" name="rwProceedCheck" value="0">
                {!! Form::checkbox('rwProceedCheck',1, null, ['id'=> 'rwProceedId', 'class' => 'form-control-checkbox']) !!} 
                <label for="rwProceedId">
                    <span class="inc"></span>
                    <span class="check mark-caheck"></span>
                    <span class="box mark-caheck"></span>
                </label>
                <span class="text-danger">@lang('label.PUT_TICK_TO_MENTION_GSM_HAS_RW_PARAMETERS')</span>
            </div>
        </div>
        @endif
        <div class="col-md-offset-4 col-md-8">
            @if(empty($gsmDetailsInfo))

            <!--proceed-->
            <button class="btn btn-circle green" type="button" id="submitProceed" style="display: none">
                <i class="fa fa-check"></i> @lang('label.PROCEED')
            </button>  

            <!--save-->
            <button class="btn btn-circle green submit-gsm-save" type="button" id="submitGsmSave" data-status="1">
                <i class="fa fa-check"></i> @lang('label.SAVE')
            </button> 

            <!--cancel-->
            <a href="{{ URL::to('/accomplishedOrder') }}" class="btn btn-circle btn-outline grey-salsa">
                <i class="fa fa-close"></i> @lang('label.CANCEL')
            </a>
            @else
            <div id="submitProceedIdShow">
                <button class="btn btn-circle green" type="button" id="submitProceedEdit">
                    <i class="fa fa-check"></i> @lang('label.UPDATE')
                </button>  
                <a href="{{ URL::to('/accomplishedOrder') }}" class="btn btn-circle btn-outline grey-salsa">
                    <i class="fa fa-close"></i> @lang('label.DISCARD_CHANGES')
                </a>
            </div>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
    <!-- End Filter -->
</div>

<!--RW BREAKDOWN DATA INPUT DIV START-->
<div class="row">
    <div class="col-md-12 margin-top-30">
        <div id="showProceedData">
            <!--already save data-->
            @if(!empty($gsmDetailsInfo))
            @if($gsmFlag == 0)
            <div class="row">
                <div class="col-md-12 margin-bottom-10 text-center">
                    <span class="bold quantity-border-style">@lang('label.INQUIRY_QUANTITY'): {{$target->quantity}}</span>
                </div>
            </div>
            @endif
            {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'previewForm')) !!}
            {!! Form::hidden('inquiry_id', $target->id) !!}
            {!! Form::hidden('product_id', $request->product_id) !!} 
            {!! Form::hidden('brand_id', $request->brand_id) !!} 
            {!! Form::hidden('grade_id', $request->grade_id) !!} 
            {!! Form::hidden('input_unit_id', !empty($rwBreakdownInfo->input_unit_id) ? $rwBreakdownInfo->input_unit_id : null) !!} 
            {!! Form::hidden('format', !empty($rwBreakdownInfo->format) ? $rwBreakdownInfo->format : null) !!} 
            @foreach($gsmDetailsInfo as $gsm_id=>$gsmValues)
            {!! Form::hidden('gsmValue['.$gsm_id.']', !empty($gsmInfo[$gsm_id])?$gsmInfo[$gsm_id]:'') !!}
            @if($gsmFlag == 1)
            <div class="row">
                <div class="col-md-12 margin-bottom-10 text-center">
                    <span class="bold quantity-border-style">@lang('label.INQUIRY_QUANTITY'): {{$arr[$gsm_id]}}</span>
                </div>
            </div>
            {!! Form::hidden('qty['.$gsm_id.']', $arr[$gsm_id]) !!}
            {!! Form::hidden('gsm_flag', $gsmFlag) !!}
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="border-styel">
                        <div class="rw-info-style">
                            <p class="rw-info-p">{{!empty($gsmInfo[$gsm_id])?$gsmInfo[$gsm_id]:''}}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="field_wrapper_rw_{{$gsm_id}}">
                                    <div class="bold">@lang('label.RW')</div>
                                    <?php
                                    $jCount = 1;
                                    $showCloseBtn2 = 1;
                                    ?>
                                    @foreach($gsmValues as $key=>$values)
                                    <!--row generate foreach-->
                                    @if($showCloseBtn2 == '1')
                                    @if(!empty($rwParameterArr))
                                    @foreach($rwParameterArr as $rwId=>$item)
                                    <?php $readOnly = (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($rwBreakdownInfo->input_unit_id) && $rwId != $rwBreakdownInfo->input_unit_id) ? 'readonly' : ''; ?>
                                    <div class="col-md-2">
                                        <label class="placeholder" >{{$item}}</label>
                                        {!! Form::text('rw_breakdown['.$gsm_id.']['.$jCount.']['.$rwId.']', !empty($values[$rwId])?$values[$rwId]:null, 
                                        ['id' => 'rwBreakdown_'.$gsm_id.'_'.$jCount.'_'.$rwId, 'data-gsm' => $gsm_id, 'data-key' => $jCount, 'data-rw-id' => $rwId
                                        ,'class' => 'rw-unit form-control pro-ip-width-150', 'autocomplete' => 'off', $readOnly]) !!} 
                                    </div>
                                    @endforeach
                                    <div class="col-md-2">
                                        <label class="placeholder" >@lang('label.QUANTITY')&nbsp;({{!empty($measureUnitInfo->unitName)?$measureUnitInfo->unitName:__('label.MT')}})</label>
                                        {!! Form::text('rw_breakdown['.$gsm_id.']['.$jCount.'][quantity]', !empty($values['quantity'])?$values['quantity']:null, ['class' => 'form-control quantity-Inquiry  pro-ip-width-150', 'autocomplete' => 'off']) !!} 
                                    </div>
                                    @endif
                                    <div class="margin-top-20">
                                        <a href="javascript:void(0);" class="add_button_rw tooltips" title="Add field" data-gsm="{{$gsm_id}}"> <span class="btn btn-inline green-haze"><i class="fa fa-plus"></i></span></a><br/>    
                                    </div>
                                    @else
                                    <div class="margin-top-10">
                                        @if(!empty($rwParameterArr))
                                        @foreach($rwParameterArr as $rwId=>$item)
                                        <?php $readOnly = (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($rwBreakdownInfo->input_unit_id) && $rwId != $rwBreakdownInfo->input_unit_id) ? 'readonly' : ''; ?>
                                        <div class="col-md-2">
                                            {!! Form::text('rw_breakdown['.$gsm_id.']['.$jCount.']['.$rwId.']', !empty($values[$rwId])?$values[$rwId]:null, 
                                            ['id' => 'rwBreakdown_'.$gsm_id.'_'.$jCount.'_'.$rwId, 'data-gsm' => $gsm_id, 'data-key' => $jCount, 'data-rw-id' => $rwId
                                            ,'class' => 'rw-unit form-control pro-ip-width-150', 'autocomplete' => 'off', $readOnly]) !!} 
                                        </div>
                                        @endforeach
                                        <div class="col-md-2">
                                            {!! Form::text('rw_breakdown['.$gsm_id.']['.$jCount.'][quantity]', !empty($values['quantity'])?$values['quantity']:null, ['class' => 'form-control quantity-Inquiry  pro-ip-width-150', 'autocomplete' => 'off']) !!} 
                                        </div>
                                        @endif
                                        <a href="javascript:void(0);" class="remove_button_rw" data-gsm="{{$gsm_id}}">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a>
                                    </div>
                                    @endif

                                    <?php
                                    $jCount++;
                                    $showCloseBtn2++;
                                    ?>
                                    @endforeach<!-- End row generate foreach-->
                                    <input type="hidden" name="rw_row" id="rwRowId_{{$gsm_id}}" value="{{$jCount}}">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div><br><br>
            @endforeach

            <div class="row">
                <!--submit button-->
                <div class="col-md-offset-4 col-md-8">
                    <button class="btn btn-circle green" href="#previewModal" type="button" data-toggle="modal" id="submitPreview">
                        <i class="fa fa-check"></i> @lang('label.PREVIEW')
                    </button>
                    <a href="{{ URL::to('/accomplishedOrder') }}" class="btn btn-circle btn-outline grey-salsa">
                        <i class="fa fa-close"></i> @lang('label.CANCEL')
                    </a>

                </div>
            </div>
            {!! Form::close() !!}
            @endif
        </div>
    </div>
</div>  <!--endof dataProceed Div-->

<script type="text/javascript">
    $(document).ready(function () {
    var unitId = 0;
    var unit = '';
    var hiddenUnitArr = [];
<?php
if (!empty($rwParameterList)) {
    foreach ($rwParameterList as $unitId => $unit) {
        if (isset($unitId)) {
            ?>
                unitId = <?php echo $unitId; ?>;
                unit = '<?php echo $unit; ?>';
                hiddenUnitArr[unitId] = unit;
            <?php
        }
    }
}
?>

    $(document).on('change', '#rwParameterId', function(){
    var val = $(this).val();
    $('#inputUnitId').html('<option value="0">-- Select Input Unit --</option>');
    for (key in val) {
    $('#inputUnitId').append('<option value="' + val[key] + '">' + hiddenUnitArr[val[key]] + '</option>');
    }

    });
<?php
if (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1) {
    ?>
        $(document).on('keyup', '.rw-unit', function(){
        var gsm = $(this).attr('data-gsm');
        var key = $(this).attr('data-key');
        var rwId = $(this).attr('data-rw-id');
        var val = $(this).val();
        if (isNaN(val) || val == ''){
        val = 0;
        }
    <?php
    foreach ($rwParameterArr as $rwId => $rw) {
        ?>
            var convUnitId = <?php echo $rwId; ?>;
            if (convUnitId != rwId){
            var convUnitRate = <?php echo!empty($convUnitList[$rwId]) ? $convUnitList[$rwId] : 0; ?>;
            if (convUnitRate == 0){
            swal("@lang('label.CONVERSION_RATE_IS_NOT_FOR_UNIT', ['unit'=>$rw])");
            } else {
            var conVal = val * convUnitRate;
            var conVal = roundUp(conVal);
            $('#rwBreakdown_' + gsm + '_' + key + '_' + convUnitId).val(conVal);
            }
            }
        <?php
    }
    ?>
        return false;
        });
        function roundUp(val){
        if (isNaN(val)){
        val = 0;
        }
        val = parseInt(val);
        var fistDigit = val % 10;
        val = val - fistDigit;
        if ($.inArray(fistDigit, [0, 1, 2]) !== - 1){
        val = Number(val) + Number(0);
        } else if ($.inArray(fistDigit, [3, 4, 5, 6]) !== - 1){
        val = Number(val) + Number(5);
        } else if ($.inArray(fistDigit, [7, 8, 9]) !== - 1){
        val = Number(val) + Number(10);
        }
        return val;
        }
    <?php
}
?>

//RW PROCEED SHOW&HIDE SCRIPT
    $("#rwProceedId").click(function () {
    if ($(this).is(":checked")) {
    $("#submitProceed").show();
    $("#submitGsmSave").hide();
    } else {
    $("#submitProceed").hide();
    $("#submitGsmSave").show();
    $("#showProceedData").html('');
    }
    });
//END RW PROCEED SHOW&HIDE SCRIPT


// ******************START GSM VALUE   multiple input fields ****************************
    var maxField = 100; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = ''; //New input field html 
//Initial field counter is 1
<?php if (!empty($iCount)) { ?>
        var x = {{$iCount}};
<?php } else { ?>
        var x = 2;
<?php } ?>

    $(wrapper).on('click', '.remove_button', function (e) {
    e.preventDefault();
    var classd = $(this).closest(".gsmDivRemove");
    var siblings = classd.siblings();
    classd.remove();
    x = 1;
    siblings.each(function (index) {
    $(this).children().attr('name', 'gsmVal[' + x + ']');
    $(this).children().attr('id', 'gsmId_' + x);
    $(this).attr("id", "row" + (x));
    x++;
    });
    });
//Once add button is clicked
    $(addButton).click(function () {

    if (x < maxField) {
    var field = '<div class="gsmDivRemove" id="row' + x + '"><input type="text" name="gsmVal[' + x + ']" size="15x2" value=""  id="gsmId_' + x + '" class=" form-control-gsm  gsm-val-control" autocomplete="off"/>\n\
    <a href="javascript:void(0);" class="remove_button">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a></div>';
    fieldHTML = field;
    $(wrapper).append(fieldHTML); //Add field html
    x++; //Increment field counter
    }
    });
//*************GSM VALUE  ENDOF MULTIPLE FIELDS SCRIPT******************


//multiselect
    $('#rwParameterId').multiselect({
    buttonWidth: '212px',
            includeSelectAllOption: true,
            selectAllText: "@lang('label.SELECT_BOTH')",
            nonSelectedText: "@lang('label.SELECT_RW_UNIT_OPT')",
    });
    });
    $(document).ready(function () {
// ******************START RW  VALUE   multiple input fields ****************************
    var maxFieldRw = 100; //Input fields increment limitation
    var addButtonRw = $('.add_button_rw'); //Add button selector
    var fieldHTMLRw = ''; //New input field html 
    //
//Once add button is clicked
    $(addButtonRw).click(function () {
    var gsm = $(this).attr('data-gsm');
    var rowCount = $('#rwRowId_' + gsm).val();
    //Initial field counter is 1
    if (rowCount != ''){
    var i = rowCount;
    } else{
    var i = 1;
    }

//Check maximum number of input fields
    if (i < maxFieldRw) {
    i++; //Increment field counter
    var field = '<div class="margin-top-10">\n\
<?php
if (!empty($rwParameterArr)) {
    foreach ($rwParameterArr as $rwId => $item) {
        ?><div class="col-md-2"><input type="text" name="rw_breakdown[' + gsm + '][' + i + '][' + {{$rwId}} + ']"  value="" id="rwBreakdown_' + gsm + '_' + i + '_' + {{$rwId}} + '" data-gsm="' + gsm + '" data-key="' + i + '" data-rw-id="' + {{$rwId}} + '"  class="rw-unit form-control  pro-ip-width-150" autocomplete="off"/></div>\n\
    <?php } ?><div class="col-md-2"><input type="text" name="rw_breakdown[' + gsm + '][' + i + '][quantity]"  value=""  class="form-control quantity-Inquiry pro-ip-width-150" autocomplete="off"/></div><?php } ?>\n\
    <a href="javascript:void(0);" class="remove_button_rw">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a></div>';
    fieldHTMLRw = field;
    $('#field_wrapper_rw_' + gsm).append(fieldHTMLRw); //Add field html
<?php
if (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($request->input_unit_id)) {
    ?>
        var inputUnitId = <?php echo!empty($request->input_unit_id) ? $request->input_unit_id : 0; ?>;
        $('.rw-unit').each(function(){
        var rwId = $(this).attr('data-rw-id');
        if (rwId != inputUnitId){
        $(this).attr('readonly', 'readonly');
        }

        });
    <?php
}
?>
    }

    $('#rwRowId_' + gsm).val(i);
    });
//Once remove button is clicked

    $(document).on('click', '.remove_button_rw', function (e) {
    var gsm = $(this).attr('data-gsm');
    e.preventDefault();
    $(this).parent('div').remove(); //Remove field html
    // i--; //Decrement field counter

    });
//*************RW VALUE  ENDOF MULTIPLE FIELDS SCRIPT***************                    ***

    });



</script>    
