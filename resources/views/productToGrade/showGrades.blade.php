<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_GRADES'): {!! !$gradeArr->isEmpty()?count($gradeArr):0 !!}</span>
        @if(!empty($userAccessArr[45][5]))
        <button class='label label-primary tooltips' href="#modalRelatedGrade" id="relateGrade"  data-toggle="modal" title="@lang('label.SHOW_RELATED_GRADES')">
            @lang('label.GRADE_RELATED_TO_THIS_BRAND'): {!! !empty($gradeRelateToProduct)?count($gradeRelateToProduct):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                        @if(!$gradeArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentGradeArr[$request->product_id][$request->brand_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter" width="20%">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-grade-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif
                        <th class="vcenter" width="80%">@lang('label.GRADE')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$gradeArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($gradeArr as $grade)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($gradeRelateToProduct) && array_key_exists($grade->id, $gradeRelateToProduct)) {
                        $checked = 'checked';
                    }

                    $gradeDisabled = $gradeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveGradeArr) && in_array($brand->id, $inactiveGradeArr)) {
                        if($checked == 'checked'){
                            $checkCondition = 1;
                        }
                        $gradeDisabled = 'disabled';
                        $gradeDisabled = __('label.INACTIVE');
                    }
                    if (!empty($dependentGradeArr[$request->product_id][$request->brand_id])) {
                        if (in_array($grade->id, $dependentGradeArr[$request->product_id][$request->brand_id]) && ($checked != '')) {
                            $gradeDisabled = 'disabled';
                            $checkCondition = 1;
                            $gradeDisabled = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter" width="20%">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('grade['.$grade->id.']', $grade->id, $checked, ['id' => $grade->id, 'data-id'=> $grade->id,'class'=> 'md-check grade-check', $gradeDisabled]) !!}
                                <label for="{!! $grade->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('grade['.$grade->id.']', $grade->id) !!}
                            @endif
                        </td>
                        <td class="vcenter" width="80%">{!! $grade->name ?? '' !!}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_GRADE_FOUND')</td>
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
            @if(!$gradeArr->isEmpty())
            @if(!empty($userAccessArr[45][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[45][1]))
            <a href="{{ URL::to('/productToGrade') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php if (!$gradeArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>


        $(".grade-check").on("click", function () {
            if ($('.grade-check:checked').length == $('.grade-check').length) {
                $('.all-grade-check').prop("checked", true);
            } else {
                $('.all-grade-check').prop("checked", false);
            }
        });
        $(".all-grade-check").click(function () {
            if ($(this).prop('checked')) {
                $('.grade-check').prop("checked", true);
            } else {
                $('.grade-check').prop("checked", false);
            }

        });
        if ($('.grade-check:checked').length == $('.grade-check').length) {
            $('.all-grade-check').prop("checked", true);
        } else {
            $('.all-grade-check').prop("checked", false);
        }

    });


</script>