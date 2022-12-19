<div class="col-md-12">
    <div class="table-responsive webkit-scrollbar max-height-500">
        <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
            <thead>
                <tr  class="info">
                    @if(!empty($brandArr))
                    <th  class="vcenter" >
                        <div class="md-checkbox">
                            {!! Form::checkbox('all_brand',1,false, ['id' => 'allBrand', 'class'=> 'md-check all-brand-check']) !!}
                            <label for="allBrand">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span>
                            </label>
                        </div>   
                    </th>
                    @endif
                    <th  class="text-center vcenter" width="">@lang('label.BRAND')</th>
                    <th  class="vcenter">@lang('label.GRADE')</th>
                    @if($authorised->authorised_for_realization_price == '1')
                    <th  class="text-center vcenter">@lang('label.REALIZATION_PRICE')</th>
                    @endif
                    <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')</th>
                    <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')</th>
                    <th  class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                    <th  class="text-center vcenter">@lang('label.REMARKS')</th>
                    @if($authorised->authorised_for_realization_price == '1')
                    <th  class="text-center vcenter">@lang('label.SPECIAL_NOTE')</th>
                    @endif
                </tr>
            </thead>
            <tbody class="access-check">
                @if(!empty($brandArr))
                @foreach($brandArr as $brand)
                <?php
                $checked = '';
                $disabled = 'disabled';
                if (in_array($brand['id'], $pricingHistoryBrandArr)) {
                    $checked = 'checked';
                    $disabled = '';
                }

                $rowspan = isset($brandWiseGrade[$brand['id']]) ? count($brandWiseGrade[$brand['id']]) : 1;
                ?>
                <tr>
                    <td class="vcenter" rowspan="{{ $rowspan }}">
                        <div class="md-checkbox module-check">
                            {!! Form::checkbox('brand['.$brand['id'].']',$brand['id'],$checked, ['id' => 'brandId_'.$brand['id'], 'class'=> 'md-check brand']) !!}
                            <label for="{{ 'brandId_'.$brand['id'] }}">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span>
                            </label>
                        </div>
                    </td>
                    <td class="vcenter" rowspan="{{ $rowspan }}">
                        <div class="text-center">
                            @if(!empty($brand['logo']) && File::exists('public/uploads/brand/'.$brand['logo']))
                            <img class="pictogram-min-space tooltips" width="20" height="20" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['name']}}" title="{{ $brand['name'] }}"/>
                            @else 
                            <img width="20" height="20" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                            @endif
                        </div>
                        <div class="text-center">
                            {!! $brand['name'] !!}
                        </div>
                        {!! Form::hidden('brand_name['.$brand['id'].']', $brand['name']) !!}

                    </td>
                    @if(isset($brandWiseGrade[$brand['id']]))
                    <?php $i = 0; ?>
                    @foreach($brandWiseGrade[$brand['id']] as $gradeId)
                    <?php
                    if ($i > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter">
                        {!! $gradeArr[$gradeId] ?? '' !!}
                        {!! Form::hidden('grade['.$brand['id'].']['.$gradeId.']', $gradeId) !!}
                        {!! Form::hidden('grade_name['.$brand['id'].']['.$gradeId.']', $gradeArr[$gradeId] ?? '') !!}
                    </td>
                    @if($authorised->authorised_for_realization_price == '1')
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('realization_price['.$brand['id'].']['.$gradeId.']', !empty($realizationPriceArr[$brand['id']][$gradeId]) ? $realizationPriceArr[$brand['id']][$gradeId] : null, ['id'=> 'realizationPrice_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right realization-price realization-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                    </td>
                    @endif
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('minimum_selling_price['.$brand['id'].']['.$gradeId.']', !empty($minimumSellingPriceArr[$brand['id']][$gradeId]) ? $minimumSellingPriceArr[$brand['id']][$gradeId] : null, ['id'=> 'minimumSellingPrice_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right minimum-selling-price minimum-selling-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                        <span class="ms-lt"></span>
                    </td>
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('target_selling_price['.$brand['id'].']['.$gradeId.']', !empty($targetSellingPriceArr[$brand['id']][$gradeId]) ? $targetSellingPriceArr[$brand['id']][$gradeId] : null, ['id'=> 'targetSellingPrice_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right target-selling-price target-selling-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                    </td>
                    <td class="text-center vcenter width-200">
                        <div class="input-group date datepicker2 width-inherit">
                            {!! Form::text('effective_date['.$brand['id'].']['.$gradeId.']', !empty($effectiveDateArr[$brand['id']][$gradeId]) ? Helper::formatDate($effectiveDateArr[$brand['id']][$gradeId]) : null, ['id'=> 'effectiveDate_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control text-input-width-100-per effective-date effective-date-'.$brand['id'], 'placeholder' => 'DD MM yyyy', 'readonly' => '', $disabled]) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date reset-date-{{$brand['id']}}"  id="{{ 'resetDate_'.$brand['id'].'_'.$gradeId }}" type="button" remove="{!! 'effectiveDate_'.$brand['id'].'_'.$gradeId !!}" {!! $disabled !!}>
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set date-set-{{$brand['id']}}" id="{{ 'setDate_'.$brand['id'].'_'.$gradeId }}" type="button" {!! $disabled !!}>
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </td>
                    <td class="text-center vcenter width-200">
                        {!! Form::text('remarks['.$brand['id'].']['.$gradeId.']', !empty($remarksArr[$brand['id']][$gradeId]) ? $remarksArr[$brand['id']][$gradeId] : null, ['id'=> 'remarks_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control width-inherit remarks remarks-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                    </td>
                    @if($authorised->authorised_for_realization_price == '1')
                    <td class="text-center vcenter width-200">
                        {!! Form::text('special_note['.$brand['id'].']['.$gradeId.']', !empty($specialNoteArr[$brand['id']][$gradeId]) ? $specialNoteArr[$brand['id']][$gradeId] : null, ['id'=> 'specialNote_'.$brand['id'].'_'.$gradeId, 'class' => 'form-control width-inherit special-note special-note-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                    </td>
                    @endif
                    <?php
                    if ($i > 0) {
                        echo '</tr>';
                    }

                    $i++;
                    ?>
                    @endforeach
                    @else
                    <td class="vcenter"></td>
                    {!! Form::hidden('grade['.$brand['id'].'][0]', 0) !!}
                    @if($authorised->authorised_for_realization_price == '1')
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('realization_price['.$brand['id'].'][0]', !empty($realizationPriceArr[$brand['id']][0]) ? $realizationPriceArr[$brand['id']][0] : null, ['id'=> 'realizationPrice_'.$brand['id'].'_0', 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right realization-price realization-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                    </td>
                    @endif
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('minimum_selling_price['.$brand['id'].'][0]', !empty($minimumSellingPriceArr[$brand['id']][0]) ? $minimumSellingPriceArr[$brand['id']][0] : null, ['id'=> 'minimumSellingPrice_'.$brand['id'].'_0', 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right minimum-selling-price minimum-selling-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                        <span class="ms-lt"></span>
                    </td>
                    <td class="text-center vcenter width-200">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                            {!! Form::text('target_selling_price['.$brand['id'].'][0]', !empty($targetSellingPriceArr[$brand['id']][0]) ? $targetSellingPriceArr[$brand['id']][0] : null, ['id'=> 'targetSellingPrice_'.$brand['id'].'_0', 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right target-selling-price target-selling-price-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                            <span class="input-group-addon bootstrap-touchspin-postfix bold">{!! !empty($unit->name)?__('label.PER').' '.$unit->name:'' !!}</span>
                        </div>
                    </td>
                    <td class="text-center vcenter width-200">
                        <div class="input-group date datepicker2 width-inherit">
                            {!! Form::text('effective_date['.$brand['id'].'][0]', !empty($effectiveDateArr[$brand['id']][0]) ? Helper::formatDate($effectiveDateArr[$brand['id']][0]) : null, ['id'=> 'effectiveDate_'.$brand['id'].'_0', 'class' => 'form-control text-input-width-100-per effective-date effective-date-'.$brand['id'], 'placeholder' => 'DD MM yyyy', 'readonly' => '', $disabled]) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date reset-date-{{$brand['id']}}"  id="{{ 'resetDate_'.$brand['id'].'_0' }}" type="button" remove="{!! 'effectiveDate_'.$brand['id'].'_0' !!}" {!! $disabled !!}>
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set date-set-{{$brand['id']}}" id="{{ 'setDate_'.$brand['id'].'_0' }}" type="button" {!! $disabled !!}>
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </td>
                    <td class="text-center vcenter width-200">
                        {!! Form::text('remarks['.$brand['id'].'][0]', !empty($remarksArr[$brand['id']][0]) ? $remarksArr[$brand['id']][0] : null, ['id'=> 'remarks_'.$brand['id'].'_0', 'class' => 'form-control width-inherit remarks remarks-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                    </td>
                    @if($authorised->authorised_for_realization_price == '1')
                    <td class="text-center vcenter width-200">
                        {!! Form::text('special_note['.$brand['id'].'][0]', !empty($specialNoteArr[$brand['id']][0]) ? $specialNoteArr[$brand['id']][0] : null, ['id'=> 'specialNote_'.$brand['id'].'_0', 'class' => 'form-control width-inherit special-note special-note-'.$brand['id'],'autocomplete' => 'off', $disabled]) !!}
                    </td>
                    @endif
                    @endif
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="vcenter" colspan="10">
                        <span class="text-danger">@lang('label.NO_BRAND_FOUND_RELATED_TO_THIS_PRODUCT'). </span>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    
//    $(".table-head-fixer-color").tableHeadFixer();
    
    //Click on module for all module wise individual acceess
    $(".brand").click(function () {
        var brandId = $(this).val();
        if ($(this).prop('checked')) {
            idWiseDisabledStatusCheck(brandId, false);
        } else {
            idWiseDisabledStatusCheck(brandId, true);
        }

        //if all brand are checked then check all will be shown checked
        if ($('.brand:checked').length == $('.brand').length) {
            $('.all-brand-check').prop("checked", true);
        } else {
            $('.all-brand-check').prop("checked", false);
        }
    });


    $(".all-brand-check").click(function () {
        if ($(this).prop('checked')) {
            $('.brand').prop("checked", true);
            classWiseDisabledStatusCheck(false);
        } else {
            $('.brand').prop("checked", false);
            classWiseDisabledStatusCheck(true);
        }

    });

    //if all brand are checked then check all will be shown checked
    if ($('.brand:checked').length == $('.brand').length) {
        $('.all-brand-check').prop("checked", true);
    } else {
        $('.all-brand-check').prop("checked", false);
    }

    
});

function idWiseDisabledStatusCheck(brandId, status) {
    $('.realization-price-' + brandId).prop("disabled", status);
    $('.target-selling-price-' + brandId).prop("disabled", status);
    $('.minimum-selling-price-' + brandId).prop("disabled", status);
    $('.effective-date-' + brandId).prop("disabled", status);
    $('.reset-date-' + brandId).prop("disabled", status);
    $('.date-set-' + brandId).prop("disabled", status);
    $('.remarks-' + brandId).prop("disabled", status);
    $('.special-note-' + brandId).prop("disabled", status);
}

function classWiseDisabledStatusCheck(status) {
    $('.realization-price').prop("disabled", status);
    $('.target-selling-price').prop("disabled", status);
    $('.minimum-selling-price').prop("disabled", status);
    $('.effective-date').prop("disabled", status);
    $('.reset-date').prop("disabled", status);
    $('.date-set').prop("disabled", status);
    $('.remarks').prop("disabled", status);
    $('.special-note').prop("disabled", status);
}
</script>