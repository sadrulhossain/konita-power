<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.RECEIVE'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id' => 'receiveForm')); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Begin Filter-->
                        <div class="col-md-offset-2 col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="supplierId"><?php echo app('translator')->get('label.SUPPLIER'); ?> </label>
                                <div class="col-md-8">
                                    <?php echo Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']); ?>

                                    <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="button" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                    <i class="fa fa-check"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                                </button>
                            </div>
                        </div>
                        <!-- End Filter -->
                    </div>
                </div>
                <div id="showReceiveData"></div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>

<!-- View Modal start -->
<div class="modal fade" id="modalViewReceivePreview" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showReceivePreview">
        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //start :: receive against invoice
        $(document).on("change", "#supplierId", function () {
            $("#showReceiveData").html('');
            return false;
        });
        $(document).on("click", ".filter-submit", function (e) {
            e.preventDefault();
            var supplierId = $("#supplierId").val();

            if (supplierId == '0') {
                $("#showReceiveData").html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('receive/getReceiveData')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    supplier_id: supplierId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showReceiveData").html(res.html);
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

                    $("#showReceiveData").html('');
                    App.unblockUI();
                }
            }); //ajax
        });

        //preview submit form function
        $(document).on("click", "#previewReceive", function (e) {
            e.preventDefault();

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var form_data = new FormData($('#receiveForm')[0]);
            $.ajax({
                url: "<?php echo e(URL::to('receive/previewReceiveData')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showReceivePreview").html(res.html);
                    $(".tooltips").tooltip();
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
                }
            });


        });
        //endof preview form

        //receive save submit form function
        $(document).on("click", "#setReceive", function (e) {

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
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };
                    // Serialize the form data
                    var formData = new FormData($('#setReceiveForm')[0]);
                    $('#setReceive').prop('disabled', true);
                    $.ajax({
                        url: "<?php echo e(URL::to('receive/setReceiveData')); ?>",
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
                            $('#setReceive').prop('disabled', true);
                            App.blockUI({boxed: true});
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
                            $('#setReceive').prop('disabled', false);
                            App.unblockUI();
                        }
                    }); //ajax
                }
            });



        });
        //endof receive save form
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/receive/create.blade.php ENDPATH**/ ?>