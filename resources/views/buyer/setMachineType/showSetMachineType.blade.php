<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title bold text-center">
            @lang('label.SET_MACHINE_TYPE_FOR_BUYER', ['buyer' => $buyer->name]) 
        </h4>
    </div>
    <div class="modal-body">
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','files' => true,'id'=>'setMachineTypeFrom')) !!}
        {{csrf_field()}}
        {!! Form::hidden('buyer_id', $request->buyer_id, ['id' => 'buyerId', 'class' => 'buyer-id']) !!}

        <div class="form-body">
            <div class="row">
                <div class="col-md-offset-1 col-md-7">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') </label>
                        <div class="col-md-8">
                            {!! Form::select('product_id', $productList, Request::get('product_id'), ['class' => 'form-control product-id js-source-states', 'id' => 'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row margin-top-20 show-set-machine-type">

            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="modal-footer machine-type-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
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
        var buyerId = $('#buyerId').val();
        if (productId == '0') {
            $(".show-set-machine-type").html('');
            $(".machine-type-footer").html('<button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="Close This Popup">Close</button>');
        }
        $.ajax({
            url: "{{ URL::to('/buyer/getBrandForMachineType')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                buyer_id: buyerId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $(".show-set-machine-type").html(res.html);
                $(".machine-type-footer").html(res.footer);
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                App.unblockUI();
            }
        }); //ajax
    });
    //end :: get product pricing setup view


    //save new pricing for product
    $(document).on("click", "#saveMachineType", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Set Machine Type',
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
                        var formData = new FormData($('#setMachineTypeFrom')[0]);
                        $.ajax({
                            url: "{{URL::to('buyer/setMachineType')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            beforeSend: function () {
                                $('#saveMachineType').prop('disabled', true);
                                App.blockUI({
                                    boxed: true
                                });
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
                                $('#saveMachineType').prop('disabled', false);
                                App.unblockUI();
                            }
                        });
                    }
                });

    });
});

function idWiseDisabledStatusCheck(brandId, status) {
    $('.machine-type-' + brandId).prop("disabled", status);
}

function classWiseDisabledStatusCheck(status) {
    $('.machine-type').prop("disabled", status);
}
</script>