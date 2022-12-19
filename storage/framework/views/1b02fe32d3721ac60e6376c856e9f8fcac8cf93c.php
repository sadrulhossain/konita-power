<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h3 class="modal-title text-center"><?php echo app('translator')->get('label.CONFIRM_ORDER'); ?></h3>
    </div>
    <div class="modal-body">
        <div class="row div-box-default">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong><?php echo app('translator')->get('label.BASIC_INQUIRY_INFORMATION'); ?></strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.BUYER_NAME'); ?></td>
                                <td width="50%"><?php echo !empty($inquiry->buyerName)?$inquiry->buyerName:''; ?></td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.SALES_PERSON'); ?></td>
                                <td width="50%"><?php echo !empty($inquiry->salesPersonName)?$inquiry->salesPersonName:''; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless">
                            <tr >
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.CREATION_DATE'); ?></td>
                                <td width="50%"><?php echo !empty($inquiry->creation_date)?Helper::formatDate($inquiry->creation_date):''; ?></td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                <td width="50%">
                                    <?php if($inquiry->order_status == '1'): ?>
                                    <span class="label label-sm label-info"><?php echo app('translator')->get('label.PENDING'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-2 margin-top-15">
            <?php echo Form::open(array('group' => 'form', 'url' => '', 'id' => 'confirmOrderFrom', 'class' => 'form-horizontal','files' => true)); ?>

            <?php echo e(csrf_field()); ?>

            <?php echo Form::hidden('inquiry_id', $request->inquiry_id); ?>

            <?php
            $previousOrderNo = !empty($prevOrderNo->order_no) ? $prevOrderNo->order_no : __('label.NO_PREVIOUS_ORDER_NO_FOUND');
            ?>
            <div class="col-md-6 col-lg-6 col-sm-6 form-body confirm-order-border">
                <div class="form-group">
                    <label class="control-label col-md-5" for="poNo"><?php echo app('translator')->get('label.PO_NO'); ?> :<span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <?php echo Form::text('purchase_order_no',!empty($inquiry->purchase_order_no)?$inquiry->purchase_order_no:null, ['id'=> 'poNo', 'class' => 'form-control']); ?> 
                        <span class="text-danger"><?php echo e($errors->first('purchase_order_no')); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="poDate"><?php echo app('translator')->get('label.PO_DATE'); ?> :<span class="text-danger">*</span></label>
                    <?php if(empty($inquiry->po_date)): ?>
                    <div class="col-md-7">
                        <div class="input-group date datepicker2">
                            <?php echo Form::text('po_date', null, ['id'=> 'poDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="poDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-md-7 bold margin-top-8">
                        <?php echo Helper::formatDate($inquiry->po_date); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="orderNo"><?php echo app('translator')->get('label.ORDER_NO'); ?> :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        <?php if(!empty($prevOrderNo->order_no)): ?>
                        <!--<div class="input-group bootstrap-touchspin">--> 
                        <?php echo Form::text('order_no', !empty($inquiry->purchase_order_no)?$inquiry->purchase_order_no:null, ['id'=> 'orderNo', 'class' => 'form-control tooltips','title' =>$previousOrderNo, 'autocomplete' => 'off']); ?>  
<!--                            <span class="input-group-addon bootstrap-touchspin-prefix bold tooltips" title="Click To Copy"><a class="a-tag-decoration-none" id="copyOrderNo" data-prev-order-no="<?php echo e($previousOrderNo); ?>">click</a></span>
                    </div>-->
                        <?php else: ?>
                        <?php echo Form::text('order_no', !empty($inquiry->purchase_order_no)?$inquiry->purchase_order_no:null, ['id'=> 'orderNo', 'class' => 'form-control tooltips','title' =>$previousOrderNo, 'autocomplete' => 'off']); ?>  
                        <?php endif; ?>
                        <span class="text-danger"><?php echo e($errors->first('order_no')); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="piDate"><?php echo app('translator')->get('label.PI_DATE'); ?> :<span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <?php
                        $prevPiDate = !empty($inquiry->order_no) ? Helper::formatDate($inquiry->pi_date) : null;
                        ?>
                        <div class="input-group date datepicker2">
                            <?php echo Form::text('pi_date', $prevPiDate, ['id'=> 'poDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="piDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('pi_date')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="supplierId"><?php echo app('translator')->get('label.SUPPLIER'); ?> :<span class="text-danger"> *</span></label>
                    <div class="col-md-7">
                        <?php echo Form::select('supplier_id', $supplierList, !empty($inquiry->supplier_id)?$inquiry->supplier_id:null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']); ?>

                        <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcNo"><?php echo app('translator')->get('label.LC_NO'); ?> :</label>
                    <div class="col-md-7">
                        <?php echo Form::text('lc_no', null, ['id'=> 'lcNo', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                        <span class="text-danger"><?php echo e($errors->first('lc_no')); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcDate"><?php echo app('translator')->get('label.LC_DATE'); ?> :</label>
                    <div class="col-md-7">
                        <div class="input-group date datepicker2">
                            <?php echo Form::text('lc_date', null, ['id'=> 'lcDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="lcDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('lc_date')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6 form-body">
                <div class="form-group">
                    <label class="control-label col-md-5" for="note"><?php echo app('translator')->get('label.NOTE_'); ?> :</label>
                    <div class="col-md-7">
                        <?php echo e(Form::textarea('note', null, ['id'=> 'note', 'class' => 'form-control','size' => '20x3'])); ?>

                        <span class="text-danger"><?php echo e($errors->first('note')); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-5" for="lcTransmittedCopyDone"><?php echo app('translator')->get('label.LC_TRANSMITTED_COPY_DONE'); ?> :</label>
                    <div class="col-md-7 checkbox-center md-checkbox has-success">
                        <?php echo Form::checkbox('lc_transmitted_copy_done',1,null, ['id' => 'lcTransmittedCopyDone', 'class'=> 'md-check']); ?>

                        <label for="lcTransmittedCopyDone">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success"><?php echo app('translator')->get('label.PUT_TICK_TO_MARK_AS_DONE'); ?></span>
                    </div>
                </div>
                <div class="col-md-12"  id="show" style="display: none">
                    <div class="form-group">
                        <label class="control-label col-md-5" for="lcIssueDate"><?php echo app('translator')->get('label.LC_ISSUE_DATE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('lc_issue_date', date('d F Y'), ['id'=> 'lcIssueDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="lcIssueDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <div>
                                <span class="text-danger"><?php echo e($errors->first('lc_issue_date')); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5" for="bank"><?php echo app('translator')->get('label.BANK'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <?php echo Form::select('bank', $bankList, null, ['class' => 'form-control js-source-states', 'id' => 'bank']); ?>

                            <span class="text-danger"><?php echo e($errors->first('bank')); ?></span>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="control-label col-md-5" for="branch"><?php echo app('translator')->get('label.BRANCH'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <?php echo Form::text('branch', null, ['id'=> 'branch', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                            <span class="text-danger"><?php echo e($errors->first('branch')); ?></span>
                        </div>
                    </div>
                    <!--lsd code starts here -->
                    <div id="lsdRow">
                        <div class="row margin-top-20">
                            <div class="col-md-12 ">
                                <div class="border-styel  margin-bottom-10">
                                    <?php $v3 = 'a' . uniqid() ?>
                                    <div class="row">
                                        <div class="col-md-10 form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 tooltips" title="Latest Shipment Date" for="lsd_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.LSD'); ?> :<span class="text-danger"> *</span></label>
                                                <div class="col-md-8 margin-bottom-10">
                                                    <div class="input-group date datepicker2">
                                                        <?php echo Form::text('lsd['.$v3.']', date('d F Y'), ['id'=> 'lsd_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?> 
                                                        <span class="input-group-btn">
                                                            <button class="btn default reset-date" type="button" remove="lsd_<?php echo e($v3); ?>">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-danger"><?php echo e($errors->first('lsd')); ?></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="lc_expiry_date_<?php echo e($v3); ?>"><?php echo app('translator')->get('label.LC_EXPIRY_DATE'); ?> :<span class="text-danger"> *</span></label>
                                                <div class="col-md-8">
                                                    <div class="input-group date datepicker2">
                                                        <?php echo Form::text('lc_expiry_date['.$v3.']', date('d F Y'), ['id'=> 'lc_expiry_date_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?> 
                                                        <span class="input-group-btn">
                                                            <button class="btn default reset-date" type="button" remove="lc_expiry_date_<?php echo e($v3); ?>">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-danger"><?php echo e($errors->first('lc_expiry_date')); ?></span>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-inline green-haze add-lsd-info lsd-row-icon tooltips"  data-placement="right" title="<?php echo app('translator')->get('label.ADD_NEW_ROW_OF_LC_DATE_INFO'); ?>" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="addLsdRow"></div>
                            </div>
                        </div>
                    </div>
                    <!--LSD code ends here -->
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
        <div class="row padding-2 margin-top-15">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$inquiryDetails->isEmpty()): ?>
                            <?php $__currentLoopData = $inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                            $perUnit = !empty($item->unit_name) ? ' / ' . $item->unit_name : '';
                            ?>
                            <tr>
                                <td class="vcenter"><?php echo e(!empty($item->product_name)?$item->product_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->brand_name)?$item->brand_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->grade_name)?$item->grade_name:''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($item->gsm)?$item->gsm:''); ?></td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->quantity) ? $item->quantity . $unit : __('label.N_A')); ?>

                                </td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->unit_price) ? '$' . $item->unit_price . $perUnit : __('label.N_A')); ?>

                                </td>
                                <td class="vcenter text-right">
                                    <?php echo e(!empty($item->total_price) ? '$' . $item->total_price : __('label.N_A')); ?>

                                </td>

                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php if(!empty($userAccessArr[26][12])): ?>
        <button class="btn green-seagreen confirm-order tooltips vcenter ">
            <i class="fa fa-check"></i>&nbsp;<?php echo app('translator')->get('label.CONFIRM_SUBMIT'); ?>
        </button>
        <?php endif; ?>
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline  tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //copy tooltips information
    var temp = 'hi';
    $('#copyOrderNo').on('click', function (e) {
        var copyText = $("#orderNo").attr('data-prev-order-no');
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
//        alert("Copied the text: " + copyText);
    });//EOF - copy tooltip info

    $("#lsdRow").hide();
    $("#lcTransmittedCopyDone").click(function () {
        if ($(this).is(":checked")) {
            $("#show").show();
            $("#lsdRow").show();
            $(".js-source-states").select2({dropdownParent: $('body')});
        } else {
            $("#show").hide();
            $("#lsdRow").hide();
        }
    });

    $("#poNo").on('keyup', function () {
        var poNo = $(this).val();
//        var orderNo = $('#orderNo').val();
        $("#orderNo").val('test-'+poNo);

    });



    //cancel order
    $('.confirm-order').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "This order will be marked as confirmed!",
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

                // data
                var formData = new FormData($("#confirmOrderFrom")[0]);
                $.ajax({
                    url: "<?php echo e(URL::to('pendingOrder/confirmOrder')); ?>",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
<?php if (!empty($userAccessArr[27][1])) { ?>
                            setTimeout(window.location.replace('<?php echo e(route("confirmedOrder.index")); ?>'), 3000);
<?php } else { ?>
                            location.reload();
<?php } ?>
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

                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});
</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/pendingOrder/showConfirmOrder.blade.php ENDPATH**/ ?>