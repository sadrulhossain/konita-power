@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-credit-card"></i>@lang('label.PAYMENT_STATUS')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id' => 'setPaymentStatusForm')) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Begin Filter-->
                        <div class="col-md-offset-3 col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') </label>
                                <div class="col-md-8">
                                    {!! Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']) !!}
                                    <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="button" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                    <i class="fa fa-check"></i> @lang('label.GENERATE')
                                </button>
                            </div>
                        </div>
                        <!-- End Filter -->
                    </div>
                </div>
                <div id="showPyamentStatus"></div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //start :: get payment status
        $(document).on("change", "#supplierId", function () {
            $("#showPyamentStatus").html('');
            return false;
        });
        $(document).on("click", ".filter-submit", function (e) {
            e.preventDefault();
            var supplierId = $("#supplierId").val();

            if (supplierId == '0') {
                $("#showPyamentStatus").html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('paymentStatus/getPaymentStatus') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    supplier_id: supplierId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showPyamentStatus").html(res.html);
                    $(".js-source-states").select2({dropdownParent: $('body'),width: '100%'});
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

                    $("#showPyamentStatus").html('');
                    App.unblockUI();
                }
            }); //ajax
        });
        //end :: get payment status

        //set payment status form function
        $(document).on("click", "#setPyamentStatus", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
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

                    // Serialize the form data
                    var form_data = new FormData($('#setPaymentStatusForm')[0]);
                    $.ajax({
                        url: "{{URL::to('paymentStatus/setPaymentStatus')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $('#setPyamentStatus').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(location.reload(), 1000);
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
                            $('#setPyamentStatus').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof set payment status form

        
    });
</script>

@stop