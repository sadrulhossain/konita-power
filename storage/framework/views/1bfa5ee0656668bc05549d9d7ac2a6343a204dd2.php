<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-exchange"></i><?php echo app('translator')->get('label.TRANSFER_BUYER_TO_SALES_PERSON'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'transferBuyerToSalesPersonForm')); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="salesPersonId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('sales_person_id', $salesPersonList, Request::get('sales_person_id'), ['class' => 'form-control js-source-states', 'id' => 'salesPersonId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('sales_person_id')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showBuyers">

                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
</div>
<!-- Modal start -->

<!--related product list-->
<div class="modal fade" id="modalRelatedProduct" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedProduct">
        </div>
    </div>
</div>


<div class="modal fade" id="modalTransferBuyerToSalesPerson" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showTransferBuyerToSalesPerson">
        </div>
    </div>
</div>

<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.tooltips').tooltip();

<?php if (!empty($buyerArr)) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".buyer-check").on("click", function () {
            if ($('.buyer-check:checked').length == $('.buyer-check').length) {
                $('.all-buyer-check').prop("checked", true);
            } else {
                $('.all-buyer-check').prop("checked", false);
            }
        });
        $(".all-buyer-check").click(function () {
            if ($(this).prop('checked')) {
                $('.buyer-check').prop("checked", true);
            } else {
                $('.buyer-check').prop("checked", false);
            }

        });
        if ($('.buyer-check:checked').length == $('.buyer-check').length) {
            $('.all-buyer-check').prop("checked", true);
        } else {
            $('.all-buyer-check').prop("checked", false);
        }

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $(document).on('change', '#salesPersonId', function () {
            var salesPersonId = $('#salesPersonId').val();
            if (salesPersonId == '0') {
                $('#showBuyers').html('');
                return false;
            }
            $.ajax({
                url: '<?php echo e(URL::to("transferBuyerToSalesPerson/getBuyersToRelate/")); ?>',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showBuyers').html(res.html);
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

        $(document).on("click", "#relateProduct", function (e) {
            e.preventDefault();
            var salesPersonId = $("#salesPersonId").val();
            $.ajax({
                url: "<?php echo e(URL::to('/transferBuyerToSalesPerson/getRelatedProducts')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId
                },
                success: function (res) {
                    $("#showRelatedProduct").html(res.html);
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
                            var form_data = new FormData($('#transferBuyerForm')[0]);
                            $.ajax({
                                url: "<?php echo e(URL::to('transferBuyerToSalesPerson/relateSalesPersonToBuyer')); ?>",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    $('.btn-submit').prop('disabled', true);
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    setTimeout(location.reload(), 1000);
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

                                    $('.btn-submit').prop('disabled', false);
                                }
                            });
                        }
                    });
        });
        //preview submit form function
        $(document).on("click", "#getSalesPersonToTransfer", function (e) {
            e.preventDefault();

            // Serialize the form data
            var form_data = new FormData($('#transferBuyerToSalesPersonForm')[0]);
            $.ajax({
                url: "<?php echo e(URL::to('transferBuyerToSalesPerson/getSalesPersonToTransfer')); ?>",
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
                    $("#showTransferBuyerToSalesPerson").html(res.html);
                    $(".tooltips").tooltip();
                    $(".js-source-states").select2({dropdownParent: $('body')});
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



    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/transferBuyerToSalesPerson/index.blade.php ENDPATH**/ ?>