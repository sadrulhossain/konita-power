@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-file-text-o"></i>
                @lang('label.REQUEST_FOR_QUOTATION')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#','id'=>'setQuotationRequestForm','class' => 'form-horizontal','files' => true)) !!}
            {!! Form::hidden('buyer_id', $target->id,['id' => 'buyerId']) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12" for="description">@lang('label.QUOTATION_REQUEST') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-12">
                                        {{ Form::textarea('description', $request->description, ['id' => 'description', 'class' => 'form-control summer-note', 'size' =>'30x5']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3" for="selectProduct">@lang('label.SELECT_PRODUCT') :</label>
                            <div class="col-md-9 md-checkbox has-success">
                                {!! Form::checkbox('select_product',1,!empty($request->description)?'1':null, ['id' => 'selectProduct', 'class'=> 'md-check']) !!}
                                <label for="selectProduct">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-success">@lang('label.PUT_TICK_IF_YOU_SELECT_PRODUCT')</span>
                            </div>
                            
                        </div>
                        <div class="row margin-top-10 product-details-div">
                            <div class="col-md-12">
                                <div class="table-responsive webkit-scrollbar">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                <th class="text-center vcenter">@lang('label.PRODUCT') <span class="text-danger">*</span></th>
                                                <th class="text-center vcenter">@lang('label.GSM')</th>
                                                <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                                <th class="text-center vcenter">@lang('label.UNIT')</th>
                                                <th class="text-center vcenter"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $v4 = 'p' . uniqid();
                                            ?>
                                            
                                            <tr>
                                                <td class="text-center vcenter initial-product-sl width-50">1</td>
                                                <td class="text-center vcenter width-240">
                                                    <div class="input-group bootstrap-touchspin width-inherit">
                                                        <span class="product-select-span">
                                                            {!! Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control selected-product js-source-states product-item']) !!}
                                                        </span>
                                                        <!--            {!! Form::text('product['.$v4.'][product_name]',null, ['id'=> 'productProductName_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-text display-none','autocomplete' => 'off']) !!} 
                                                                    <span class="input-group-addon label-blue-steel padding-0 border-0 bootstrap-touchspin-postfix bold">
                                                                        <button class="btn btn-sm blue-steel product-text-btn product-text-btn-{{$v4}} bold tooltips" data-key="{{$v4}}" title="@lang('label.CLICK_TO_TYPE_AS_TEXT')" type="button">
                                                                            <i class="fa fa-text-height bold"></i> 
                                                                        </button>
                                                                        <button class="btn btn-sm blue-steel product-select-btn product-select-btn-{{$v4}} bold tooltips display-none"  data-key="{{$v4}}" title="@lang('label.CLICK_TO_SELECT_FROM_DROPDOWN')" type="button">
                                                                            <i class="fa fa-angle-down bold"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>-->
                                                        {!! Form::hidden('product['.$v4.'][product_has_id]', '1', ['class' => 'product-has-id', 'id' => 'productHasId_'.$v4]) !!}
                                                </td>
                                                
                                                <td class="text-center vcenter width-100">
                                                    {!! Form::text('product['.$v4.'][gsm]', null, ['id'=> 'productGsm_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-gsm']) !!}
                                                </td>
                                                <td class="text-center vcenter width-100">
                                                    {!! Form::text('product['.$v4.'][quantity]', null, ['id'=> 'productQuantity_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit text-right integer-decimal-only product-quantity']) !!}
                                                </td>
                                                <td class="text-center vcenter width-80">
                                                    {!! Form::text('product['.$v4.'][unit]', null, ['id'=> 'productUnit_'.$v4, 'data-key' => $v4, 'class' => 'form-control width-inherit product-unit','readonly']) !!}
                                                </td>
                                                
                                                <td class="text-center vcenter width-50">
                                                    <button class="btn btn-inline green-haze add-new-product-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_PRODUCT_INFO')" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody id="newProductTbody"></tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-inline green submit-quotation-request" type="button">
                            <i class="fa fa-check"></i> @lang('label.SEND_REQUEST')
                        </button>
                        <a href="{{ URL::to('/buyerQuotationRequest'.Helper::queryPageStr($qpArr)) }}" class="btn btn-inline btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>	
</div>



<script type="text/javascript">
    $(function () {

        //product input
        $('.product-text-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('.product-select-span-' + key).addClass('display-none');
            $('.product-select-btn-' + key).removeClass('display-none');
            $('#productProductName_' + key).removeClass('display-none');
            $('#productHasId_' + key).val('0');
        });
        $('.product-select-btn').on('click', function () {
            var key = $(this).attr('data-key');
            $(this).addClass('display-none');
            $('#productProductName_' + key).addClass('display-none');
            $('.product-text-btn-' + key).removeClass('display-none');
            $('.product-select-span-' + key).removeClass('display-none');
            $('.product-select-span-' + key + ' span.select2').css('width', '100%');
            $('#productHasId_' + key).val('1');
        });

        $('.summer-note').summernote({
            height: 100, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true      // set focus to editable area after initializing summernote
        });

        //add new product row
        $(document).on("click", ".add-new-product-row", function (e) {
            e.preventDefault();
            var buyerId = $("#buyerId").val();
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
                url: "{{URL::to('buyerQuotationRequest/newProductRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {buyer_id: buyerId},
                success: function (res) {
                    $("#newProductTbody").append(res.html);
                    $(".tooltips").tooltip();
                    rearrangeSL('product');
                    var v4 = res.v4;
                    var quotationId = $("#quotationId").val();
                    addNewTermsRow(v4, quotationId);
                },
            });
        });


        //remove product row
        $(document).on('click', '.remove-product-row', function () {
            var key = $(this).attr('data-key');
            $(this).parent().parent().remove();
            $('.new-terms-tr-' + key).remove();
            rearrangeSL('product');
            rearrangeSL('term');
            getSubtotal();
            return false;
        });

        //load per unit
        $(document).on('keyup', '.product-unit', function () {
            var key = $(this).attr('data-key');
            var unit = $(this).val();
            $('span.product-per-unit-' + key).text('/' + unit);
        });


        //After Click to Save new po generate
        $(document).on("click", ".submit-quotation-request", function (e) {
            e.preventDefault();
            var status = $(this).attr('data-status');
            var formData = new FormData($('#setQuotationRequestForm')[0]);
            formData.append('status', status);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('buyerQuotationRequest/quotationDataSave')}}",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            window.location = '{!! URL::to("buyerQuotationRequest") !!}';

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
                    });
                }
            });
        });
        // EOF Function for set po generate




    });

    function rearrangeSL(type) {
        var sl = 0;
        $('.initial-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
        $('.new-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
    }


    //product under product unit
    $(document).on('change', '.product-item', function (e) {
        var productId = $(this).val();
        var buyerId = $('#buyerId').val();
        var dataKey = $(this).attr('data-key');
        
        var productName = $("#productId_" + dataKey).find(':selected').text();
        if (productId != 0) {
            $('.product-name-' + dataKey).text(productName);
        } else {
            $('.product-name-' + dataKey).text('');
        }

        $.ajax({
            url: "{{ URL::to('buyerQuotationRequest/getProductUnit')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                product_key: dataKey,
                buyer_id: buyerId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#productUnit_' + dataKey).val(res.unit);
                $('.product-unit').trigger('keyup');
                $(".js-source-states").select2({dropdownParent: $('body')});
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax

    });

    /************** START:: If product is selected ****************/
    $('.product-details-div').addClass('display-none');
    $('#selectProduct').on('click', function () {
        if ($(this).prop("checked") == true) {
            $('.product-details-div').removeClass('display-none');
            ;
            $('.selected-product').select2();
        } else {
            $('.product-details-div').addClass('display-none');
        }
    });
    /************** END:: If product is selected ****************/
</script>
@stop
