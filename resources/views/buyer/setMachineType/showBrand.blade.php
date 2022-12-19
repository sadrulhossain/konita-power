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
                    <th  class="text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                    <th  class="text-center vcenter">@lang('label.MACHINE_TYPE') <span class="text-danger">*</span></th>
                    <th  class="text-center vcenter">@lang('label.MACHINE_LENGTH') </th>
                </tr>
            </thead>
            <tbody class="access-check">
                @if(!$brandArr->isEmpty())
                @foreach($brandArr as $brand)
                <?php
                $checked = '';
                $disabled = 'disabled';
                $machineTypeId = null;
                $machineLength = null;
                if (array_key_exists($brand->brand_id, $machineTypeBrandArr)) {
                    $checked = 'checked';
                    $disabled = '';
                    $machineTypeId = !empty($machineTypeBrandArr[$brand->brand_id]['machine_type_id']) ? $machineTypeBrandArr[$brand->brand_id]['machine_type_id'] : null;
                    $machineLength = !empty($machineTypeBrandArr[$brand->brand_id]['machine_length']) ? $machineTypeBrandArr[$brand->brand_id]['machine_length'] : null;
                }
                ?>
                <tr>
                    <td class="vcenter">
                        <div class="md-checkbox module-check">
                            {!! Form::checkbox('brand['.$brand->brand_id.']',$brand->brand_id,$checked, ['id' => 'brandId_'.$brand->brand_id, 'class'=> 'md-check brand']) !!}
                            <label for="{{ 'brandId_'.$brand->brand_id }}">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span>
                            </label>
                        </div>
                    </td>
                    <td class="vcenter" width="40px">
                        @if(!empty($brand->logo) && File::exists('public/uploads/brand/'.$brand->logo))
                        <img class="pictogram-min-space tooltips" width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand->logo }}" alt="{{ $brand->name}}" title="{{ $brand->name }}"/>
                        @else 
                        <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                        @endif
                    </td>
                    <td class="vcenter">
                        {!! $brand->name !!}
                        {!! Form::hidden('brand_name['.$brand->brand_id.']', $brand->name) !!}
                    </td>
                    <td class="text-center vcenter width-200">
                        {!! Form::select('machine_type_id['.$brand->brand_id.']', $machineTypeList, $machineTypeId, ['class' => 'form-control width-inherit machine-type js-source-states machine-type-'.$brand->brand_id, 'id' => 'machineTypeId_'.$brand->brand_id, $disabled]) !!}
                    </td>
                    <td class="text-center vcenter width-200">
                        {!! Form::text('machine_length['.$brand->brand_id.']', $machineLength, ['id'=> 'machineTypeId_'.$brand->brand_id, 'class' => 'form-control width-inherit machine-length machine-length-'.$brand->brand_id,'autocomplete' => 'off', $disabled]) !!}
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="vcenter" colspan="5">
                        <span class="text-danger">@lang('label.NO_DATA_FOUND'). </span>
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
    $('.machine-type-' + brandId).prop("disabled", status);
    $('.machine-length-' + brandId).prop("disabled", status);
}

function classWiseDisabledStatusCheck(status) {
    $('.machine-type').prop("disabled", status);
    $('.machine-length').prop("disabled", status);
}
</script>