@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_PRODUCT_TO_BRAND')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'id' => 'productToBrandRelateForm')) !!}
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showBrands">
                            @if(!empty(Request::get('product_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUM_OF_BRANDS'): {!! !empty($brandArr)?count($brandArr):0 !!}</span>
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
                                                    @if(!empty($brandArr))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if (!empty($dependentBrandArr[$request->get('product_id')])) {
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
                                                @if(!empty($brandArr))
                                                <?php $sl = 0; ?>
                                                @foreach($brandArr as $brand)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($brandRelateToProduct) && array_key_exists($brand['id'], $brandRelateToProduct)) {
                                                    $checked = 'checked';
                                                }

                                                $gradeChecked = '';
                                                if (!empty($brandRelateToProductHasGrade[$brand['id']])) {
                                                    if ($brandRelateToProductHasGrade[$brand['id']] == '1') {
                                                        $gradeChecked = 'checked';
                                                    }
                                                }

                                                $brandDisabled = $brandTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveBrandArr) && in_array($brand['id'], $inactiveBrandArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $brandDisabled = 'disabled';
                                                    $brandTooltips = __('label.INACTIVE');
                                                }
                                                if (!empty($dependentBrandArr) && in_array($brand['id'], $dependentBrandArr[$request->get('product_id')]) && ($checked != '')) {
                                                    $brandDisabled = 'disabled';
                                                    $checkCondition = 1;
                                                    $brandTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                                                }

                                                $hasGradeDisabled = $HasGradeTooltips = '';
                                                if (!empty($dependentHasGradeArr[$request->get('product_id')]) && in_array($brand['id'], $dependentHasGradeArr[$request->get('product_id')]) && ($checked != '')) {
                                                    $hasGradeDisabled = 'disabled';
                                                    $HasGradeTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('brand['.$brand['id'].']', $brand['id'], $checked, ['id' => $brand['id'], 'data-id'=> $brand['id'],'class'=> 'md-check brand-check', $brandDisabled]) !!}
                                                            <label for="{!! $brand['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('brand['.$brand['id'].']', $brand['id']) !!}
                                                        @endif
                                                    </td>
                                                    <td class="text-center vcenter">
                                                        @if(!empty($brand['logo']))
                                                        <img class="pictogram-min-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['name']}}"/>
                                                        @else 
                                                        <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                                        @endif
                                                    </td>
                                                    <td class="vcenter">{!! $brand['name'] ?? '' !!}</td>

                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('has_grade['.$brand['id'].']', 1, $gradeChecked, ['id' => 'hasGrade_'.$brand['id'], 'class'=> 'md-check has-grade-check', $hasGradeDisabled]) !!}
                                                            <label for="{!! 'hasGrade_'.$brand['id'] !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $HasGradeTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($hasGradeDisabled == 'disabled')
                                                        {!! Form::hidden('has_grade['.$brand['id'].']', '1') !!}
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
                                        @if(!empty($brandArr))
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
<div class="modal fade" id="modalRelatedBrand" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedBrand">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.tooltips').tooltip();
<?php if (!empty($brandArr)) { ?>
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

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $(document).on('change', '#productId', function () {
            var productId = $('#productId').val();
            if (productId == '0') {
                $('#showBrands').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("productToBrand/getBrandsToRelate")}}',
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
                    $('#showBrands').html(res.html);
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
        $(document).on("click", "#relateBrand", function (e) {
            e.preventDefault();
            var productId = $("#productId").val();
            $.ajax({
                url: "{{ URL::to('/productToBrand/getRelatedBrands')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showRelatedBrand").html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#showRelatedBrand").html(res.html);
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
                $("#productToBrandRelateForm").append(
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
                            var form_data = new FormData($('#productToBrandRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('productToBrand/relateProductToBrand')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var productId = $('#productId').val();
                                    location = "productToBrand?product_id=" + productId;
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