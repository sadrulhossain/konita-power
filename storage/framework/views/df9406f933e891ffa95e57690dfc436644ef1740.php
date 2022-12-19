<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h3 class="modal-title text-center">
            <?php echo ($request->shipment_status == 1) ? __('label.CARRIER_INFORMATION') : __('label.VIEW_CARRIER_INFO'); ?>

        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <?php if($request->shipment_status == 1): ?>
            <div class="col-md-12">
                <?php echo Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'setCarrierInfoForm')); ?>

                <?php echo Form::hidden('shipment_id', $previousCarrierInfo->id); ?>

                <?php echo e(csrf_field()); ?>

                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="shippingLine"><?php echo app('translator')->get('label.SHIPPING_LINE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-6 col-sm-6">
                            <?php echo Form::select('shipping_line', $shippingLineList, !empty($previousCarrierInfo->shipping_line)?$previousCarrierInfo->shipping_line:null, ['id'=> 'shippingLine', 'class' => 'form-control js-source-states']); ?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="containerNo"><?php echo app('translator')->get('label.CONTAINER_NO'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-6 col-sm-6">
                            <?php if(!empty($previousContainerNoArr)): ?>
                            <?php $counter = 0; ?>
                            <?php $__currentLoopData = $previousContainerNoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $containerNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <?php echo Form::text('container_no['.$key.']',  $containerNo, ['class'=>'form-control', 'id' => 'containerNo_'.$key,'autocomplete' => 'off']); ?>

                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <?php if($counter == 0): ?>
                                    <button class="btn btn-inline green-haze add-new-container-row tooltips" data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_ROW'); ?>" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-inline btn-danger remove-container-row tooltips" title="Remove" type="button">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $counter++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <?php $v3 = 'a' . uniqid(); ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <?php echo Form::text('container_no['.$v3.']',  null, ['class'=>'form-control', 'id' => 'containerNo_'.$v3,'autocomplete' => 'off']); ?>

                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <button class="btn btn-inline green-haze add-new-container-row tooltips" data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_ROW'); ?>" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div id="newContainerNoRow"></div>
                        </div>
                    </div>              
                </div>
                <?php echo Form::close(); ?>

            </div>
            <?php else: ?>
            <!-- carrier data -->
            <div class=" col-md-offset-1 col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="info">
                                    <th class="vcenter"><?php echo app('translator')->get('label.SHIPPING_LINE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.CONTAINER_NO'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="vcenter"><?php echo !empty($previousCarrierInfo->shipping_line) && !empty($shippingLineList)?$shippingLineList[$previousCarrierInfo->shipping_line]:'--'; ?></td>
                                    <td class="vcenter">
                                        <?php if(!empty($previousContainerNoArr)): ?>
                                        <?php $c = 0; ?>
                                        <?php $__currentLoopData = $previousContainerNoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contNo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($contNo); ?><?php echo $c != count($previousContainerNoArr)-1 ? '<br/>'  : ''; ?>

                                        <?php ++$c; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <?php echo app('translator')->get('label.N_A'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </table>
                </div>
            </div>
            <!-- end: carrier data -->
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <?php if($request->shipment_status == 1): ?>
            <button class="btn green-seagreen tooltips vcenter" type="button" id="submitCarrierInfo">
                <?php echo app('translator')->get('label.SUBMIT'); ?>
            </button>
            <?php endif; ?>
            <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        </div>
    </div>
</div>


<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $(".js-source-states").select2({dropdownParent: $('body')});

    //add new ets row
    $(".add-new-container-row").on("click", function (e) {
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
            url: "<?php echo e(URL::to('confirmedOrder/newContainerNoRow')); ?>",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $("#newContainerNoRow").prepend(res.html);
            },
        });
    });
    //remove ets row
    $('.remove-container-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });

    //cancel order
    $("#submitCarrierInfo").on("click", function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Confirm",
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

                var formData = new FormData($("#setCarrierInfoForm")[0]);
                $.ajax({
                    url: "<?php echo e(URL::to('/confirmedOrder/setCarrierInfo')); ?>",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('#submitCarrierInfo').prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
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
                        $('#submitCarrierInfo').prop('disabled', false);
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});
</script>
<?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/confirmedOrder/orderShipment/showSetCarrierInfo.blade.php ENDPATH**/ ?>