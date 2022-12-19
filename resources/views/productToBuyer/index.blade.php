@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.RELATE_PRODUCT_TO_BUYER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'productToBuyerRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_id', $productArr, null, ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showBuyers"></div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="modalRelatedBuyer" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedBuyer">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">

    $(document).on('change', '#productId', function () {
        getBuyersToRelate();
    });

    $(document).on("click", "#relateBuyer", function (e) {
        e.preventDefault();
        var productId = $("#productId").val();
        $.ajax({
            url: "{{ URL::to('/productToBuyer/getRelatedBuyers')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId
            },
            success: function (res) {
                $("#showRelatedBuyer").html(res.html);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

    //insert sales person to buyer
    $(document).on("click", ".btn-submit", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You can not undo this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, save',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: true,
            closeOnCancel: false},
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
                        var form_data = new FormData($('#productToBuyerRelateForm')[0]);
                        $.ajax({
                            url: "{{URL::to('productToBuyer/relateProductToBuyer')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function (res) {
                                toastr.success(res.message, res.heading, options);
                                getBuyersToRelate();
                                setTimeout(2000);
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
                    } else {
                        swal('Cancelled', '', 'error');
                    }
                });


    });

    function getBuyersToRelate() {
        var productId = $('#productId').val();
        if (productId == '0') {
            $('#showBuyers').html('');
            return false;
        }
        $.ajax({
            url: '{{URL::to("productToBuyer/getBuyersToRelate/")}}',
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
                $('#showBuyers').html(res.html);
                App.unblockUI();
            },
        });
    }

</script>
@stop