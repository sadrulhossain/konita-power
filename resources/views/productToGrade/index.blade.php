@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_PRODUCT_TO_GRADE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'productToGradeRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="brandId">@lang('label.BRAND') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('brand_id', $brandArr, Request::get('brand_id'), ['class' => 'form-control js-source-states', 'id' => 'brandId']) !!}
                                <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showGrades">
                            @if(!empty(Request::get('product_id')) && !empty(Request::get('brand_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_GRADES'): {!! !empty($gradeArr)?count($gradeArr):0 !!}</span>
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
                                                    @if(!empty($gradeArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if (!empty($dependentGradeArr[$request->get('product_id')][$request->get('brand_id')])) {
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
                                                @if(!empty($gradeArr))
                                                <?php $sl = 0; ?>
                                                @foreach($gradeArr as $grade)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($gradeRelateToProduct) && array_key_exists($grade['id'], $gradeRelateToProduct)) {
                                                    $checked = 'checked';
                                                }

                                                $gradeDisabled = $gradeTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveGradeArr) && in_array($grade['id'], $inactiveGradeArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $gradeDisabled = 'disabled';
                                                    $gradeDisabled = __('label.INACTIVE');
                                                }
                                                if (!empty($dependentGradeArr[$request->get('product_id')][$request->get('brand_id')])) {
                                                    if (in_array($grade['id'], $dependentGradeArr[$request->get('product_id')][$request->get('brand_id')]) && ($checked != '')) {
                                                        $gradeDisabled = 'disabled';
                                                        $checkCondition = 1;
                                                        $gradeTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter" width="20%">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('grade['.$grade['id'].']', $grade['id'], $checked, ['id' => $grade['id'], 'data-id'=> $grade['id'],'class'=> 'md-check grade-check', $gradeDisabled]) !!}
                                                            <label for="{!! $grade['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $gradeTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('grade['.$grade['id'].']', $grade['id']) !!}
                                                        @endif
                                                    </td>
                                                    <td class="vcenter" width="80%">{!! $grade['name'] ?? '' !!}</td>
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
                                        @if(!empty($gradeArr))
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="modalRelatedGrade" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedGrades">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.tooltips').tooltip();
<?php if (!empty($gradeArr)) { ?>
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

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $(document).on('change', '#productId', function () {
            var productId = $('#productId').val();
            $('#showGrades').html('');
            if (productId == '0') {
                $('#brandId').html("<option class='form-control js-source-states' value='0'>@lang('label.SELECT_BRAND_OPT')</option>");
                return false;
            }
            $.ajax({
                url: '{{URL::to("productToGrade/getBrands")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#brandId').html(res.html);
                    App.unblockUI();
                }, error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });
        });
        $(document).on('change', '#brandId', function () {
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            if (productId == '0' || brandId == '0') {
                $('#showGrades').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("productToGrade/getGradesToRelate")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showGrades').html(res.html);
                    App.unblockUI();
                }, error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });
        });
        $(document).on("click", "#relateGrade", function (e) {
            e.preventDefault();
            var productId = $("#productId").val();
            var brandId = $("#brandId").val();
            $.ajax({
                url: "{{ URL::to('/productToGrade/getRelatedGrades')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId
                },
                beforeSend: function () {
                    $("#showRelatedGrades").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedGrades").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //insert sales person to buyer
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var oTable = $('.relation-view').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#productToGradeRelateForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };
                            // Serialize the form data
                            var form_data = new FormData($('#productToGradeRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('productToGrade/relateProductToGrade')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var productId = $('#productId').val();
                                    var brandId = $('#brandId').val();
                                    location = "productToGrade?product_id=" + productId + "&brand_id=" + brandId;
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                }
                            });
                        }
                    });
        });


    });
</script>
@stop