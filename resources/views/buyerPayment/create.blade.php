@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-credit-card"></i>@lang('label.BUYER_PAYMENT')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id' => 'setBuyerPaymentForm')) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Begin Filter-->
                        <div class="col-md-offset-2 col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER') </label>
                                <div class="col-md-8">
                                    {!! Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
                                    <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
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
                <div id="showPayment"></div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<!-- View Modal start -->
<div class="modal fade" id="modalPreview" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showPreview">
        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //start :: get payment status
        $(document).on("change", "#buyerId", function () {
            $("#showPayment").html('');
            return false;
        });
        $(document).on("click", ".filter-submit", function (e) {
            e.preventDefault();
            var buyerId = $("#buyerId").val();

            if (buyerId == '0') {
                $("#showPayment").html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('buyerPayment/getPayment') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    buyer_id: buyerId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showPayment").html(res.html);
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

                    $("#showPayment").html('');
                    App.unblockUI();
                }
            }); //ajax
        });
        //end :: get payment status

        //preview submit form function
        $(document).on("click", "#previewPayment", function (e) {
            e.preventDefault();

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var form_data = new FormData($('#setBuyerPaymentForm')[0]);
            $.ajax({
                url: "{{URL::to('buyerPayment/previewPayment')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#previewPayment").prop('disabled', true);
                },
                success: function (res) {
                    $("#modalPreview").modal('show');
                    $("#showPreview").html(res.html);
                    $(".tooltips").tooltip();
                    $("#previewPayment").prop('disabled', false);
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
                    $("#previewPayment").prop('disabled', false);
                }
            });


        });

        //set payment form function
        $(document).on("click", "#setPayment", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Submit',
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
                    var form_data = new FormData($('#setBuyerPaymentForm')[0]);
                    $.ajax({
                        url: "{{URL::to('buyerPayment/setPayment')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $('#setPayment').prop('disabled', true);
                            $('#setPaymentWithPrint').prop('disabled', true);
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
                            $('#setPayment').prop('disabled', false);
                            $('#setPaymentWithPrint').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof set payment form

        //set payment and print form function
        $(document).on("click", "#setPaymentWithPrint", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Submit',
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
                    var form_data = new FormData($('#setBuyerPaymentForm')[0]);
                    $.ajax({
                        url: "{{URL::to('buyerPayment/setPayment')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $('#setPayment').prop('disabled', true);
                            $('#setPaymentWithPrint').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            setTimeout(location.reload(), 1000);
                            var buyerId = res.buyerId;
                            var commissionDue = res.commissionDue;
                            var payment = res.payment;
                            var remarks = res.remarks;
                            var contactName = res.contactName;
                            var contactNumber = res.contactNumber;
                            var locWin = 'buyerPayment/setPaymentWithPrint?buyer_id='+buyerId+'&commission_due='+commissionDue+'&payment='+payment+'&remarks='+remarks+'&contact_name='+contactName+'&contact_number='+contactNumber;
                             window.open(locWin,'_blank');
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
                            $('#setPayment').prop('disabled', false);
                            $('#setPaymentWithPrint').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof set payment and print form


    });
</script>

@stop