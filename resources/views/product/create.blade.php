@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.CREATE_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','files' => true,'id'=>'productCreateForm')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productCatId">@lang('label.PRODUCT_CATEGORY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_category_id', $productCategoryArr, null, ['class' => 'form-control js-source-states', 'id' => 'productCatId']) !!}
                                <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                <div id="productName"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productCode">@lang('label.PRODUCT_CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('product_code', null, ['id'=> 'productCode', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('product_code') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="measureUnitId">@lang('label.PRODUCT_MEASUREMENT_UNIT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('measure_unit_id', $measureUnitArr, null, ['class' => 'form-control js-source-states', 'id' => 'measureUnitId']) !!}
                                <span class="text-danger">{{ $errors->first('measure_unit_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="hsCodeId">@lang('label.HS_CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php $v3 = 'a' . uniqid(); ?>
                                <div class="row margin-bottom-10">
                                    <div class="col-md-11 col-sm-10 col-xs-10 col-lg-10">
                                        {!! Form::text('hs_code['.$v3.']',  null, ['class'=>'form-control hs-code-control', 'id' => 'hsCodeId_'.$v3,'autocomplete' => 'off']) !!}
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1 col-lg-1">
                                        <button class="btn btn-inline green-haze add-hs-code-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW')" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="newHsCodeRow"></div>
                                <span class="text-danger">{{ $errors->first('hs_code') }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="competitorsProduct">@lang('label.COMPETITORS_PRODUCT') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('competitors_product',1,null, ['id' => 'competitorsProduct', 'class'=> 'md-check']) !!}
                                <label for="competitorsProduct">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_COMPETITORS_PRODUCT')</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn green btn-submit" type="button">
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {

        //add new ets row
        $(".add-hs-code-row").on("click", function (e) {
            e.preventDefault();
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


            $.ajax({
                url: "{{URL::to('product/newHsCodeRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#newHsCodeRow").prepend(res.html);
                },
            });
        });
        //remove ets row
        $('.remove-hs-code-row').on('click', function () {
            $(this).parent().parent().remove();
            return false;
        });

        /*$('#name').keyup(function(e) {
         e.preventDefault();
         var maxlength = 1;
         var value = $(this).val();
         
         if (value == '') {
         $('#productName').html('');
         return false;
         }
         
         if (value.length >= maxlength) {
         $.ajax({
         type: 'post',
         dataType: 'json',
         url: "{{URL::to('product/loadProductNameCreate')}}",
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         data: {
         'product_name': value
         },
         beforeSend: function() {
         App.blockUI({boxed: true});
         },
         success: function(res) {
         //we need to check if the value is the same
         //Receiving the result of search here
         $('#productName').html(res.html);
         
         $("#searchResult li").bind("click", function() {
         setText(this);
         $('#searchResult').css('border', '0px');
         });
         App.unblockUI();
         }
         });
         }
         });
         
         //For Click Outside of loaded element
         $(document).mouseup(function(e) {
         var container = $("#searchResult"); // YOUR CONTAINER SELECTOR
         if (!container.is(e.target) // if the target of the click isn't the container...
         && container.has(e.target).length === 0) // ... nor a descendant of the container
         {
         container.hide();
         }
         });*/

        /*function setText(element) {
         var value = $(element).text();
         if (value == '') {
         $("#searchResult").click(function(event) {
         event.stopPropagation();
         });
         } else {
         $("#name").val(value);
         $("#searchResult").empty();
         }
         
         }*/




// ******************START GSM VALUE   multiple input fields ****************************
        var maxField = 100; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = ''; //New input field html 
//Initial field counter is 1
<?php if (!empty($iCount)) { ?>
            var x = <?php echo $iCount; ?>
<?php } else { ?>
            var x = 1;
<?php } ?>

//Once add button is clicked
        $(addButton).click(function () {
//Check maximum number of input fields
            if (x < maxField) {
                x++; //Increment field counter
                var field = '<div><input type="number" name="hs_code[' + x + ']" size="15x2" value=""  id="hsCodeId_' + x + '" class="form-control-hs integer-only hs-code-control" autocomplete="off"/>\n\
    <a href="javascript:void(0);" class="remove_button">&nbsp;<span class="btn btn-inline red"><i class="fa fa-close"></i></span></a></div>';
                fieldHTML = field;
                $(wrapper).append(fieldHTML); //Add field html

            }
        });
//Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
//*************GSM VALUE  ENDOF MULTIPLE FIELDS SCRIPT******************

        $(document).on("click", ".btn-submit", function () {
            swal({
                title: "Are you sure?",
                text: "@lang('label.DO_YOU_WANT_TO_CONTINUE_IT')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.YES_CONTINUE_IT')",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
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

                    var formData = new FormData($("#productCreateForm")[0]);
                    $.ajax({
                        url: "{{ URL::to('/product/store')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(window.location.replace('{{ URL::to("/product")}}'), 1000);
                            App.unblockUI();
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
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });
        });
    });
</script>

@stop