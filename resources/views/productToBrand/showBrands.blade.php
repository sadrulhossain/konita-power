<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_BRANDS'): {!! !$brandArr->isEmpty()?count($brandArr):0 !!}</span>
        @if(!empty($userAccessArr[32][5]))
        <button class='label label-primary tooltips' href="#modalRelatedBrand" id="relateBrand"  data-toggle="modal" title="@lang('label.SHOW_RELATED_BRANDS')">
            @lang('label.BRAND_RELATED_TO_THIS_PRODUCT'): {!! !empty($brandRelateToProduct)?count($brandRelateToProduct):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover relation-view">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        @if(!$brandArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentBrandArr[$request->product_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-brand-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif
                        <th class="text-center vcenter">@lang('label.LOGO')</th>
                        <th class="vcenter">@lang('label.BRAND_NAME')</th>
                        <th class="vcenter">@lang('label.HAS_GRADE')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$brandArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($brandArr as $brand)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($brandRelateToProduct) && array_key_exists($brand->id, $brandRelateToProduct)) {
                        $checked = 'checked';
                    }


                    $gradeChecked = '';
                    if (!empty($brandRelateToProductHasGrade[$brand->id])) {
                        if ($brandRelateToProductHasGrade[$brand->id] == '1') {
                            $gradeChecked = 'checked';
                        }
                    }

                    $brandDisabled = $brandTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveBrandArr) && in_array($brand->id, $inactiveBrandArr)) {
                        if($checked == 'checked'){
                            $checkCondition = 1;
                        }
                        $brandDisabled = 'disabled';
                        $brandTooltips = __('label.INACTIVE');
                    }
                    if (!empty($dependentBrandArr[$request->product_id]) && in_array($brand->id, $dependentBrandArr[$request->product_id]) && ($checked != '')) {
                        $brandDisabled = 'disabled';
                        $checkCondition = 1;
                        $brandTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                    }


                    $hasGradeDisabled = $HasGradeTooltips = '';
                    if (!empty($dependentHasGradeArr[$request->product_id]) && in_array($brand->id, $dependentHasGradeArr[$request->product_id]) && ($checked != '')) {
                        $hasGradeDisabled = 'disabled';
                        $HasGradeTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('brand['.$brand->id.']', $brand->id, $checked, ['id' => $brand->id, 'data-id'=> $brand->id,'class'=> 'md-check brand-check', $brandDisabled]) !!}
                                <label for="{!! $brand->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('brand['.$brand->id.']', $brand->id) !!}
                            @endif
                        </td>
                        <td class="text-center vcenter">
                            @if(!empty($brand->logo))
                            <img class="pictogram-min-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand->logo }}" alt="{{ $brand->name}}"/>
                            @else 
                            <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                            @endif
                        </td>
                        <td class="vcenter">{!! $brand->name ?? '' !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('has_grade['.$brand->id.']', 1, $gradeChecked, ['id' => 'hasGrade_'.$brand->id, 'class'=> 'md-check has-grade-check', $hasGradeDisabled]) !!}
                                <label for="{!! 'hasGrade_'.$brand->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                </label>
                            </div>
                            @if($hasGradeDisabled == 'disabled')
                            {!! Form::hidden('has_grade['.$brand->id.']', '1') !!}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_BRAND_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!$brandArr->isEmpty())
            @if(!empty($userAccessArr[32][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[32][1]))
            <a href="{{ URL::to('/productToBrand') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php if (!$brandArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>


        $(".brand-check").on("click", function () {
            if ($('.brand-check:checked').length == $('.brand-check').length) {
                $('.all-brand-check').prop("checked", true);
            } else {
                $('.all-brand-check').prop("checked", false);
            }
        });
        $(".all-brand-check").click(function () {
            if ($(this).prop('checked')) {
                $('.brand-check').prop("checked", true);
            } else {
                $('.brand-check').prop("checked", false);
            }

        });
        if ($('.brand-check:checked').length == $('.brand-check').length) {
            $('.all-brand-check').prop("checked", true);
        } else {
            $('.all-brand-check').prop("checked", false);
        }

    });


</script>