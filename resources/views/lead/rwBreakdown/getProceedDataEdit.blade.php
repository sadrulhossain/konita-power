<!--already save data-->
@if(!empty($finalGsmDetailsArr))
@if($request->gsm_flag == 0)
<div class="row">
    <div class="col-md-12 margin-bottom-10 text-center">
        <span class="bold quantity-border-style">@lang('label.INQUIRY_QUANTITY'): {{$target->quantity}}</span>
    </div>
</div>
@endif
{!! Form::open(array('group' => 'form', 'url' => '#','id'=>'previewForm')) !!}
{!! Form::hidden('inquiry_id', $request->inquiry_id) !!}
{!! Form::hidden('product_id', $request->product_id) !!} 
{!! Form::hidden('brand_id', $request->brand_id) !!} 
{!! Form::hidden('grade_id', $request->grade_id) !!} 
{!! Form::hidden('format', $request->format) !!} 
{!! Form::hidden('input_unit_id', $request->input_unit_id) !!}
@foreach($finalGsmDetailsArr as $gsmId=>$item)
{!! Form::hidden('gsmValue['.$gsmId.']', !empty($gsmArr[$gsmId])?$gsmArr[$gsmId]:'') !!}
@if($request->gsm_flag == 1)
<div class="row">
    <div class="col-md-12 margin-bottom-10 text-center">
        <span class="bold quantity-border-style">@lang('label.INQUIRY_QUANTITY'): {{$request->qtyVal[$gsmId]}}</span>
    </div>
</div>
{!! Form::hidden('qty['.$gsmId.']', $request->qtyVal[$gsmId]) !!}
{!! Form::hidden('gsm_flag', $request->gsm_flag) !!}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="border-styel">
            <div class="rw-info-style">
                <p class="rw-info-p">{{!empty($gsmArr[$gsmId])?$gsmArr[$gsmId]:''}}</p>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="field_wrapper_rw_{{$gsmId}}">
                        <div class="bold">@lang('label.RW')</div>
                        <?php
                        $jCount = 1;
                        $showCloseBtn2 = 1;
                        ?>
                        @foreach($item as $key=>$values)
                        <!--row generate foreach-->



                        @if($showCloseBtn2 == '1')
                        @if(!empty($rwParameterArr))
                        @foreach($rwParameterArr as $rwId=>$item)
                        <?php $readOnly = (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($request->input_unit_id) && $rwId != $request->input_unit_id) ? 'readonly' : ''; ?>
                        <div class="col-md-2">
                            <label class="placeholder" >{{$item}}</label>
                            {!! Form::text('rw_breakdown['.$gsmId.']['.$jCount.']['.$rwId.']', !empty($values[$rwId])?$values[$rwId]:null, 
                            ['id' => 'rwBreakdown_'.$gsmId.'_'.$jCount.'_'.$rwId, 'data-gsm' => $gsmId, 'data-key' => $jCount, 'data-rw-id' => $rwId
                            ,'class' => 'rw-unit-proceed-edit form-control pro-ip-width-150', 'autocomplete' => 'off', $readOnly]) !!} 
                        </div>
                        @endforeach
                        <div class="col-md-2">
                            <label class="placeholder" >@lang('label.QUANTITY')&nbsp;({{!empty($measureUnitInfo->unitName)?$measureUnitInfo->unitName:__('label.MT')}})</label>
                            {!! Form::text('rw_breakdown['.$gsmId.']['.$jCount.'][quantity]', !empty($values['quantity'])?$values['quantity']:null, ['class' => 'form-control quantity-Inquiry pro-ip-width-150', 'autocomplete' => 'off']) !!} 
                        </div>
                        @endif
                        <div class="margin-top-20">
                            <a href="javascript:void(0);" class="add_button_rw tooltips" title="Add field" data-gsm="{{$gsmId}}"> <span class="btn btn-inline green-haze"><i class="fa fa-plus"></i></span></a><br/>    
                        </div>
                        @else
                        <div class="margin-top-10">
                            @if(!empty($rwParameterArr))
                            @foreach($rwParameterArr as $rwId=>$item)
                            <?php $readOnly = (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($request->input_unit_id) && $rwId != $request->input_unit_id) ? 'readonly' : ''; ?>
                            <div class="col-md-2">

                                {!! Form::text('rw_breakdown['.$gsmId.']['.$jCount.']['.$rwId.']', !empty($values[$rwId])?$values[$rwId]:null, 
                                ['id' => 'rwBreakdown_'.$gsmId.'_'.$jCount.'_'.$rwId, 'data-gsm' => $gsmId, 'data-key' => $jCount, 'data-rw-id' => $rwId
                                , 'class' => 'rw-unit-proceed-edit form-control  pro-ip-width-150', 'autocomplete' => 'off', $readOnly]) !!} 
                            </div>
                            @endforeach
                            <div class="col-md-2">
                                {!! Form::text('rw_breakdown['.$gsmId.']['.$jCount.'][quantity]', !empty($values['quantity'])?$values['quantity']:null, ['class' => 'form-control quantity-Inquiry pro-ip-width-150', 'autocomplete' => 'off']) !!} 
                            </div>
                            @endif
                            <a href="javascript:void(0);" class="remove_button_rw">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a>
                        </div>
                        @endif

                        <?php
                        $jCount++;
                        $showCloseBtn2++;
                        ?>
                        @endforeach<!-- End row generate foreach-->
                        <input type="hidden" name="rw_row" id="rwRowId_{{$gsmId}}" value="{{$jCount}}">
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
        <a href="{{ URL::to('/lead') }}" class="btn btn-circle btn-outline grey-salsa">
            <i class="fa fa-close"></i> @lang('label.CANCEL')
        </a>

    </div>
</div>
{!! Form::close() !!}
@endif
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
<?php
if (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1) {
    ?>
    $(document).on('keyup', '.rw-unit-proceed-edit', function(){
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

// ******************START RW  VALUE   multiple input fields ****************************
var maxFieldRw = 100; //Input fields increment limitation
var addButtonRw = $('.add_button_rw'); //Add button selector
var fieldHTMLRw = ''; //New input field html 

//Once add button is clicked
$(addButtonRw).click(function () {
var gsm = $(this).attr('data-gsm');
//Check maximum number of input fields
var rowCount = $('#rwRowId_' + gsm).val();
//Initial field counter is 1
if (rowCount != ''){
var i = rowCount;
} else{
var i = 1;
}

if (i < maxFieldRw) {
i++; //Increment field counter
var field = '<div class="margin-top-10">\n\
<?php
if (!empty($rwParameterArr)) {
    foreach ($rwParameterArr as $rwId => $item) {
        ?><div class="col-md-2"><input type="text" name="rw_breakdown[' + gsm + '][' + i + '][' + {{$rwId}} + ']"  value="" id="rwBreakdown_' + gsm + '_' + i + '_' + {{$rwId}} + '" data-gsm="' + gsm + '" data-key="' + i + '" data-rw-id="' + {{$rwId}} + '"  class="rw-unit-proceed-edit form-control  pro-ip-width-150" autocomplete="off"/></div>\n\
    <?php } ?><div class="col-md-2"><input type="text" name="rw_breakdown[' + gsm + '][' + i + '][quantity]"  value=""  class="form-control quantity-Inquiry pro-ip-width-150" autocomplete="off"/></div><?php } ?>\n\
    <a href="javascript:void(0);" class="remove_button_rw">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a></div>';
fieldHTMLRw = field;
$('#field_wrapper_rw_' + gsm).append(fieldHTMLRw); //Add field html

<?php
if (!empty($rwParameterArr) && sizeof($rwParameterArr) > 1 && !empty($request->input_unit_id)) {
    ?>
    var inputUnitId = <?php echo!empty($request->input_unit_id) ? $request->input_unit_id : 0; ?>;
    $('.rw-unit-proceed-edit').each(function(){
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
e.preventDefault();
$(this).parent('div').remove(); //Remove field html
//i--; //Decrement field counter
});
//*************RW VALUE  ENDOF MULTIPLE FIELDS SCRIPT******************
});
</script>