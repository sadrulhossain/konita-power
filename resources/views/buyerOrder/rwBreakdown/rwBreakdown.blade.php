@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa fa-external-link"></i>
                @if(!empty($gsmInfo))
                @lang('label.RW_BREAKDOWN_EDIT')
                @else
                @lang('label.RW_BREAKDOWN')
                @endif
            </div>
            <div class="actions">
                <a href="{{ URL::to('/confirmedOrder'.Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>

            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Proced-->
                {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal')) !!}
                {!! Form::hidden('inquiry_id', $target->id,['id' => 'inquiryId']) !!} 

                <div class="col-md-offset-2 col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('product_id', $productList,null, ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="brandId">@lang('label.BRAND') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('brand_id', $brandList,null, ['class' => 'form-control js-source-states', 'id' => 'brandId']) !!}
                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                        </div>
                    </div>
                    <div id="showGrade">
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <!--Show Rw Breakdown View-->
            <div id="showRwBreakdownView">
            </div>
            <!--Endof show RW Breakdown-->

        </div>
    </div>	
</div>

<!-- Modal start -->
<!--preview modal start-->
<div class="modal fade" id="previewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showPreviewModal">
        </div>
    </div>
</div>

<!--preview modal End-->
<!-- Modal end-->
<script type="text/javascript">
    $(document).ready(function () {

        //get brand
        $(document).on('change', '#productId', function (e) {
            var productId = $('#productId').val();
            var inquiryId = $('#inquiryId').val();

            $('#showRwBreakdownView').html('');
            $('#showGrade').html('');
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwBreakdownGetBrand')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#brandId').html(res.html);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });

        //get grade
        $(document).on('change', '#brandId', function (e) {
            var brandId = $('#brandId').val();
            var productId = $('#productId').val();
            var inquiryId = $('#inquiryId').val();
            var pageNo = $('#pageId').val();
            $('#showGrade').html('');
            $('#showRwBreakdownView').html('');
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwBreakdownGetGrade')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    brand_id: brandId,
                    product_id: productId,
                    inquiry_id: inquiryId,
                    page: pageNo
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showGrade').html(res.grade);
                    $('#showRwBreakdownView').html(res.html);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        //get RwBreakdown View
        $(document).on('change', '#gradeId', function (e) {
            var gradeId = $('#gradeId').val();
            var brandId = $('#brandId').val();
            var productId = $('#productId').val();
            var inquiryId = $('#inquiryId').val();
            var pageNo = $('#pageId').val();
            $('#showRwBreakdownView').html('');
            $.ajax({
                url: "{{ URL::to('confirmedOrder/getRwBreakdownView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    grade_id: gradeId,
                    brand_id: brandId,
                    product_id: productId,
                    inquiry_id: inquiryId,
                    page: pageNo
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showRwBreakdownView').html(res.html);

                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body'), width: '100%'});
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });



        //Proceed submit form function
        $(document).on("click", "#submitProceed", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
// Serialize the form data
            var formData = new FormData($('#proceedForm')[0]);
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwProceedRequest') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showProceedData").html(res.html);
                    //                $(".gsm-input-data").prop('readonly', true);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            }); //ajax

        });
//endof proceed form


//Save submitGsmSave form function
        $(document).on("click", "#submitGsmSave", function (e) {
            var status = $(this).data('status');
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
// Serialize the form data
            var formData = new FormData($('#proceedForm')[0]);
            formData.append('status', status);
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwProceedRequest') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#submitGsmSave').prop('disabled', true);
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(
                            window.location.replace('{{ route("confirmedOrder.index")}}'
                                    ), 7000);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $('#submitGsmSave').prop('disabled', false);
                    App.unblockUI();
                }
            }); //ajax

        });
//endof  submitGsmSave  form

//Proceed submit form function
        $(document).on("click", "#submitProceedEdit", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
// Serialize the form data
            var formData = new FormData($('#proceedForm')[0]);
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwProceedRequestEdit') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showProceedData").html(res.html);
                    //                $(".gsm-input-data").prop('readonly', true);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            }); //ajax

        });
//endof proceed form


//preview submit form function
        $(document).on("click", "#submitPreview", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
// Serialize the form data
            var formData = new FormData($('#previewForm')[0]);
            $.ajax({
                url: "{{ URL::to('confirmedOrder/rwPreviewRequest') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showPreviewModal").html(res.html);
                    $(".gsm-input-data").prop('readonly', true);
                    $(".js-source-states").select2({dropdownParent: $('#showPreviewModal'), width: '100%'});
                    $(".js-source-states").select2();
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }

                    $("#showPreviewModal").html('');
                    App.unblockUI();
                }
            }); //ajax

        });
//endof preview form


//Function for cancellation submit form
        $(document).on("click", ".submit-rw-save", function (e) {
            var status = $(this).data('status');
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
// Serialize the form data
            var formData = new FormData($('#rwBreakDownSaveForm')[0]);
            formData.append('status', status);
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('confirmedOrder/rwBreakDownSave') }}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#submitRwSave').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('{{ route("confirmedOrder.index")}}'
                                            ), 7000);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#submitRwSave').prop('disabled', false);
                            App.unblockUI();
                        }
                    }); //ajax
                }

            });
        });

        //ENDOF RW SAVE

        //multiselect
        $('#rwParameterId').multiselect({
            buttonWidth: '212px',
            includeSelectAllOption: true,
            selectAllText: "@lang('label.SELECT_BOTH')",
            nonSelectedText: "@lang('label.SELECT_RW_UNIT_OPT')",
        });

        $(document).on("change", "#rwParameterId", function (e) {
            $('#submitProceedIdShow').show();
//$('#showProceedData').html('');
        });


        //RW parameters id wise name show
        $(document).on("change", "#rwParaId", function (e) {
            var rwParaId = $('#rwParaId').val();
// Serialize the form data

            $.ajax({
                url: "{{ URL::to('confirmedOrder/getLeadRwParametersName') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    rw_unit_id: rwParaId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#rwUnitName").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    App.unblockUI();
                }
            }); //ajax

        });
//endof rw parameters wise name show


    });
</script>    
@stop