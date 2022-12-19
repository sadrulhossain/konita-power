
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.SET_PRODUCT_PRICING')
        </h3>
    </div>
    <div class="modal-body">
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','files' => true,'id'=>'setProductPricingFrom')) !!}
        {{csrf_field()}}

        {!! Form::hidden('authorised_for_realization_price', $authorised->authorised_for_realization_price) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-offset-1 col-md-7">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('product_id', $productList, Request::get('product_id'), ['class' => 'form-control product-id js-source-states', 'id' => 'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row margin-top-20 show-set-product-pricing">

            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="modal-footer pricing-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
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

    //get product pricing setup view
    $(".product-id").on("change", function (e) {
        e.preventDefault();
        var productId = $(this).val();
        if(productId == '0'){
            $(".show-set-product-pricing").html('');
            $(".pricing-footer").html('<button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="Close This Popup">Close</button>');
        }
        $.ajax({
            url: "{{ URL::to('/dashboard/getProductPricingSetup')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $(".show-set-product-pricing").html(res.html);
                $(".pricing-footer").html(res.footer);
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax
    });
    //end :: get product pricing setup view


    //save new pricing for product
    $(document).on("click", "#saveProductPricing", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "@lang('label.PRICES_WILL_BE_SET_FOR_THIS_PRODUCT')",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Set Prices',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        },
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
                        var formData = new FormData($('#setProductPricingFrom')[0]);
                        $.ajax({
                            url: "{{URL::to('product/setProductPricing')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            beforeSend: function() {
                              $('#saveProductPricing').prop('disabled', true);  
                            },
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
                                location.reload();
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
                                $('#saveProductPricing').prop('disabled', false);
                                App.unblockUI();
                            }
                        });
                    }
                });

    });
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