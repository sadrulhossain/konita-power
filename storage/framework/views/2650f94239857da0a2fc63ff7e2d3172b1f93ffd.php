<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.INVOICE'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'billing/filter','class' => 'form-horizontal')); ?>

            <?php echo csrf_field(); ?> 
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="invoiceNO"><?php echo app('translator')->get('label.INVOICE_NO'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::text('invoice_no', Request::get('invoice_no'), ['class' => 'form-control','id'=>'invoiceNO']); ?>

                            <span class="text-danger"><?php echo e($errors->first('invoice_no')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="orderNo"><?php echo app('translator')->get('label.ORDER_NO'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('order_no', $orderNoList, Request::get('order_no'), ['class' => 'form-control js-source-states','id'=>'orderNo']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierId"><?php echo app('translator')->get('label.SUPPLIER'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('supplier_id',  $supplierList, Request::get('supplier_id'), ['class' => 'form-control js-source-states','id'=>'supplierId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                        </div>
                    </div>
                </div> 


            </div>
            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="approvalStatus"><?php echo app('translator')->get('label.APPROVAL_STATUS'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('approval_status',  $approvalStatusList, Request::get('approval_status'), ['class' => 'form-control js-source-states','id'=>'approvalStatus']); ?>

                            <span class="text-danger"><?php echo e($errors->first('approval_status')); ?></span>
                        </div>
                    </div>
                </div>     
                <div class="col-md-4 text-center">
                    <div class="form-group">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover" id="fixedHeadTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="text-center vcenter" ><?php echo app('translator')->get('label.INVOICE_NO'); ?></th>
                                    <th class="text-center vcenter" ><?php echo app('translator')->get('label.DATE'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SUPPLIER'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.KONITA_BANK'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.APPROVAL_STATUS'); ?></th>
                                    <th class="text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$targetArr->isEmpty()): ?>
                                <?php
                                $i = 0;
                                ?>
                                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center vcenter"><?php echo e(++$i); ?></td>
                                    <td class="vcenter"><?php echo e($target->invoice_no); ?></td>
                                    <td class="text-center vcenter"><?php echo e(Helper::formatDate($target->date)); ?></td>
                                    <td class="vcenter"><?php echo e($target->supplierName); ?></td>
                                    <td class="vcenter"><?php echo e($target->bank_name); ?></td>
                                    <td class="text-center vcenter">
                                        <?php if($target->approval_status == '1'): ?>
                                        <span class="label label-sm label-green-seagreen"><?php echo app('translator')->get('label.APPROVED'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-sm label-blue-steel"><?php echo app('translator')->get('label.PENDING_FOR_APPROVAL'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <?php if($target->approval_status == '0'): ?>
                                            <?php if(!empty($userAccessArr[41][25])): ?>
                                            <button class="btn btn-xs green-seagreen tooltips approve vcenter" data-toggle="modal" title="<?php echo app('translator')->get('label.CLICK_TO_APPROVE'); ?>" data-id ="<?php echo e($target->id); ?>">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if(!empty($userAccessArr[41][26])): ?>
                                            <button class="btn btn-xs red-soft tooltips deny vcenter" data-toggle="modal" title="<?php echo app('translator')->get('label.CLICK_TO_DENY'); ?>" data-id ="<?php echo e($target->id); ?>">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <!--view details-->
                                            <?php if(!empty($userAccessArr[41][5])): ?>
                                            <button class="btn btn-xs yellow invoice-details tooltips vcenter" href="#invoiceDetailsModal" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.INVOICE_DETAILS'); ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-xs blue full-invoice-details tooltips vcenter" href="#fullInvoiceDetailsModal" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.FULL_INVOICE_DETAILS'); ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <?php endif; ?>
                                            <!--print-->
                                            <?php if(!empty($userAccessArr[41][6])): ?>
                                            <a class="btn btn-xs blue-soft tooltips vcenter" target="_blank" href="<?php echo e(URL::to('billing/billingLedgerPrint?view=print&invoice_id=' . $target->id)); ?>"  title="<?php echo app('translator')->get('label.PRINT'); ?>">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <?php endif; ?>
                                            <!--pdf-->
                                            <?php if(!empty($userAccessArr[41][9])): ?>
                                            <a class="btn btn-xs blue-sharp tooltips vcenter" target="_blank" href="<?php echo e(URL::to('billing/billingLedgerPdf?view=pdf&invoice_id=' . $target->id)); ?>"  title="<?php echo app('translator')->get('label.DOWNLOAD'); ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php endif; ?>

                                            <!--Commission Details-->
                                            <?php if(!empty($userAccessArr[41][5])): ?>
                                            <button class="btn btn-xs yellow-gold  cmsn-details tooltips vcenter" href="#cmsnDetailsModal" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.COMMISSION_DETAILS'); ?>">
                                                <i class="fa fa-sitemap"></i>
                                            </button>
                                            <?php endif; ?>
                                            <!--Delete-->
                                            <?php if(!array_key_exists($target->id, $alreadyReceivedInvoiceList)): ?>
                                            <?php if(!empty($userAccessArr[41][4])): ?>
                                            <?php echo e(Form::open(array('url' => 'billing/ledger/' . $target->id . '?url=' .urlencode(Helper::getUrlRequestText(URL::to($request->fullUrl()))), 'class' => 'delete-form-inline'))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                            <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php echo e(Form::close()); ?>

                                            <?php endif; ?>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInvoiceDetailsModal">
        </div>
    </div>
</div>
<div class="modal fade" id="fullInvoiceDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showFullInvoiceDetailsModal">
        </div>
    </div>
</div>

<!--commission details-->
<div class="modal fade" id="cmsnDetailsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCmsnDetailsModal">
        </div>
    </div>
</div>
<!--endof commission details-->
<!-- Modal end--> 

<script type="text/javascript">
    $(document).ready(function () {
        $('#fixedHeadTable').tableHeadFixer();
        //approve payment
        $(document).on("click", ".approve", function (e) {
            e.preventDefault();
            var invoiceId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to approve this invoice?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('billing/approve')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            invoice_id: invoiceId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
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

        //deny payment
        $(document).on("click", ".deny", function (e) {
            e.preventDefault();
            var invoiceId = $(this).attr("data-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure you want to deny this invoice?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Deny',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('billing/deny')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            invoice_id: invoiceId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
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


//   invoice details modal script
        $(document).on("click", ".invoice-details", function () {
            var invoiceId = $(this).attr('data-id');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('billing/billingLedgerDetails')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showInvoiceDetailsModal").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            }); //ajax

        });
        
        $(document).on("click", ".full-invoice-details", function () {
            var invoiceId = $(this).attr('data-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('billing/billingFullLedgerDetails')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showFullInvoiceDetailsModal").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            }); //ajax

        });



//   cmsn details modal script
        $(document).on("click", ".cmsn-details", function () {
            var invoiceId = $(this).attr('data-id');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "<?php echo e(URL::to('billing/commissionDetails')); ?>",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                data: {
                    invoice_id: invoiceId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showCmsnDetailsModal").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            }); //ajax

        });

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/ledgerView.blade.php ENDPATH**/ ?>