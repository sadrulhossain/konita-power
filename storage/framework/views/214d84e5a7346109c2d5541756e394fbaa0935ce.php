<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th-list"></i><?php echo app('translator')->get('label.PENDING_ORDER_LIST'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'pendingOrder/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId"><?php echo app('translator')->get('label.BUYER'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="salespersonsId"><?php echo app('translator')->get('label.SALES_PERSON'); ?> </label>
                        <div class="col-md-8">
                            <?php echo Form::select('salespersons_id', $salesPersonList, Request::get('salespersons_id'), ['class' => 'form-control js-source-states', 'id' => 'salespersonsId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('salespersons_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId"><?php echo app('translator')->get('label.PRODUCT'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('product_id',  $productList, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId"><?php echo app('translator')->get('label.BRAND'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('brand_id',  $brandList, Request::get('brand_id'), ['class' => 'form-control js-source-states','id'=>'brandId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?> :</label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?> </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-offset-5 col-md-2">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->

            <!--product wise Quantity Summary-->
            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <button class="btn btn-sm blue-soft  tooltips vcenter" href="#quantitySummaryModal" id="quantitySummary" 
                            data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.PRODUCT_WISE_TOTAL_QUANTITY'); ?>"
                            data-product_id="<?php echo e($request->product_id); ?>" data-brand_id="<?php echo e($request->brand_id); ?>"
                            data-buyer_id="<?php echo e($request->buyer_id); ?>" data-salespersons_id="<?php echo e($request->salespersons_id); ?>"
                            data-from_date="<?php echo e($request->from_date); ?>" data-to_date="<?php echo e($request->to_date); ?>">
                        <i class="fa fa-balance-scale"></i> <span class="bold"><?php echo app('translator')->get('label.PRODUCT_WISE_TOTAL_QUANTITY'); ?></span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PURCHASE_ORDER_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BUYER_NAME'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BUYER_CONTACT_PERSON'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.SALES_PERSON'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.UNIT_PRICE'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.TOTAL_PRICE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.CREATION_DATE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $iconCAS = '';
                        $btnColorCAS = 'yellow-mint';
                        if (!empty($commissionAlreadySetList)) {
                            if (in_array($target->id, $commissionAlreadySetList)) {
                                $iconCAS = '<br/><span class="badge badge-primary tooltips" title="' . __('label.COMMISSION_ALREADY_SET') . '"><i class="fa fa-usd"></i></span>';
                                $btnColorCAS = 'yellow-gold';
                            }
                        }

                        $iconFH = '';
                        if (!empty($hasFollowupList)) {
                            if (in_array($target->id, $hasFollowupList)) {
                                $iconFH = '<br/><button class="btn btn-xs purple-wisteria btn-circle btn-rounded tooltips followup-history vcenter"'
                                        . ' href="#followUpModal"  data-toggle="modal" title="' . __('label.VIEW_FOLLOWUP_HISTORY') . '" 
                                            data-inquiry-id ="' . $target->id . '" data-history-status="1">
                                        <i class="fa fa-eye"></i>
                                    </button>';
                            }
                        }
                        //inquiry rowspan
                        $rowSpan['inquiry'] = !empty($rowspanArr['inquiry'][$target->id]) ? $rowspanArr['inquiry'][$target->id] : 1;
                        ?>
                        <tr>
                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo ++$sl.$iconCAS.$iconFH; ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo e($target->purchase_order_no); ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo e($target->buyerName); ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo e($target->buyer_contact_person); ?></td>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo e($target->salesPersonName); ?></td>

                            <?php if(!empty($target->inquiryDetails)): ?>
                            <?php $i = 0; ?>
                            <?php $__currentLoopData = $target->inquiryDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId=> $productData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($i > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['product'] = !empty($rowspanArr['product'][$target->id][$productId]) ? $rowspanArr['product'][$target->id][$productId] : 1;
                            ?>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['product']); ?>">
                                <?php echo e(!empty($productArr[$productId])?$productArr[$productId]:''); ?>

                            </td>
                            <?php if(!empty($productData)): ?>
                            <?php $j = 0; ?>
                            <?php $__currentLoopData = $productData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandId=> $brandData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($j > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['brand'] = !empty($rowspanArr['brand'][$target->id][$productId][$brandId]) ? $rowspanArr['brand'][$target->id][$productId][$brandId] : 1;
                            ?>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['brand']); ?>">
                                <?php echo e(!empty($brandArr[$brandId])?$brandArr[$brandId]:''); ?>

                            </td>
                            <?php if(!empty($brandData)): ?>
                            <?php $k = 0; ?>
                            <?php $__currentLoopData = $brandData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeId=> $gradeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($k > 0) {
                                echo '<tr>';
                            }
                            $rowSpan['grade'] = !empty($rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId]) ? $rowspanArr['grade'][$target->id][$productId][$brandId][$gradeId] : 1;
                            ?>
                            <td class="vcenter" rowspan="<?php echo e($rowSpan['grade']); ?>">
                                <?php echo e(!empty($gradeArr[$gradeId])?$gradeArr[$gradeId]:''); ?>

                            </td>
                            <?php if(!empty($gradeData)): ?>
                            <?php $l = 0; ?>
                            <?php $__currentLoopData = $gradeData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gsm=> $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($l > 0) {
                                echo '<tr>';
                            }
                            ?>
                            <td class="vcenter"><?php echo e(!empty($gsm)?$gsm:''); ?></td>
                            <td class="vcenter text-right"><?php echo e($item['quantity']); ?>&nbsp;<?php echo e($item['unit_name']); ?></td>
                            <td class="vcenter text-right">$<?php echo e($item['unit_price']); ?>&nbsp;<span>/</span><?php echo e($item['unit_name']); ?></td>
                            <td class="vcenter text-right">$<?php echo e($item['total_price']); ?></td>

                            <?php if($i == 0 && $j == 0 && $k == 0): ?>
                            <!--:::::::: rowspan part :::::::-->

                            <td class="text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>"><?php echo e(Helper::formatDate($target->creation_date)); ?></td>
                            <td class="td-actions text-center vcenter" rowspan="<?php echo e($rowSpan['inquiry']); ?>">
                                <div class="width-inherit">
                                    <!--commission setup-->
                                    <?php if(!empty($userAccessArr[26][18])): ?>
                                    <button class="btn btn-xs <?php echo e($btnColorCAS); ?>  tooltips vcenter commission-setup-modal" href="#commissionSetUpModal" data-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.COMMISSION_SETUP'); ?>">
                                        <i class="fa fa-sitemap"></i>
                                    </button>
                                    <?php endif; ?>
                                    <!--RW BREAKDOWN--> 
                                    <?php if(!empty($userAccessArr[26][19])): ?>
                                    <?php if(!empty($rwBreakdownStatusArr[$target->id])): ?>
                                    <a class="btn btn-xs green-soft tooltips vcenter" title="<?php echo app('translator')->get('label.RW_BREAKDOWN_EDIT'); ?>" href="<?php echo e(URL::to('pendingOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? ''))); ?>">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <!--rw breakdown view-->
                                    <a class="btn btn-xs yellow-casablanca tooltips vcenter rw-breakdown-view" title="<?php echo app('translator')->get('label.RW_BREAKDOWN_VIEW'); ?>" href="#rwBreakdownViewModal"  data-toggle="modal" data-inquiry-id ="<?php echo e($target->id); ?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php else: ?>
                                    <a class="btn btn-xs yellow tooltips vcenter" title="<?php echo app('translator')->get('label.RW_BREAKDOWN'); ?>" href="<?php echo e(URL::to('pendingOrder/rwBreakdown/' . $target->id . Helper::queryPageStr($qpArr ?? ''))); ?>">
                                        <i class="fa fa fa-external-link"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <!--ENDOF RW BREAKDOWN--> 

                                    <?php if(!empty($userAccessArr[26][17])): ?>
                                    <button class="btn btn-xs purple-wisteria followup-history tooltips vcenter" href="#followUpModal"  data-toggle="modal" title="<?php echo app('translator')->get('label.FOLLOW_UP'); ?>" data-inquiry-id ="<?php echo e($target->id); ?>" data-history-status="0">
                                        <i class="fa fa-hourglass-2"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[26][12])): ?>
                                    <button class="btn btn-xs green-seagreen tooltips vcenter show-confirm-order" title="Confirm Order" href="#modalConfirmOrder" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <!--PENDING ORDER CANCELLATION-->
                                    <?php if(!empty($userAccessArr[26][13])): ?>
                                    <button class="btn btn-xs btn-warning tooltips vcenter cancellation-pending-order" href="#cancellationModal" data-toggle="modal" title="<?php echo app('translator')->get('label.PENDING_ODRED_CANCELLATION'); ?>" data-inquiry-id ="<?php echo e($target->id); ?>">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                    <?php endif; ?>


                                    <!--inquiry reassignment-->
                                    <?php if(!empty($userAccessArr[26][24])): ?>
                                    <button class="btn btn-xs grey-cascade tooltips vcenter reassign-inquiry" href="#inquiryReassignmentModal" data-inquiry-id="<?php echo $target->id; ?>" data-toggle="modal" data-placement="top" data-rel="tooltip" title="<?php echo app('translator')->get('label.INQUIRY_REASSIGNMENT'); ?>">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <!--:::::::: endof rowspan part :::::::-->
                            <?php endif; ?>
                            <?php
                            if ($l < ($rowSpan['grade'] - 1)) {
                                echo '</tr>';
                            }

                            $i++;
                            $j++;
                            $k++;
                            $l++;
                            ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="18" class="vcenter"><?php echo app('translator')->get('label.NO_PENDING_ORDER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>

<!-- Modal start -->


<!--delivery details-->
<div class="modal fade" id="modalConfirmOrder" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showConfirmOrder"></div>
    </div>
</div>

<!--followUp modal-->
<div class="modal fade" id="followUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showFollowUpModal">
        </div>
    </div>
</div>

<!--RW BREAKDOWN VIEW MODAL-->
<div class="modal fade" id="rwBreakdownViewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showRwBreakdownViewModal">
        </div>
    </div>
</div>
<!-- Start commissionSetUpModal-->
<div class="modal fade" id="commissionSetUpModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowcommissionSetUpModal">
        </div>
    </div>
</div>

<!--pending order cancellation-->
<div class="modal fade" id="cancellationModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCancellationModal">
        </div>
    </div>
</div>

<!-- Start quantity Summary Modal-->
<div class="modal fade" id="quantitySummaryModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="ShowQuantitySummaryModal">
        </div>
    </div>
</div>

<!-- Start inquiry reassignment Modal-->
<div class="modal fade" id="inquiryReassignmentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInquiryReassignment">
        </div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //confirm order modal
        $(".show-confirm-order").on("click", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/pendingOrder/getConfirmOrderModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showConfirmOrder").html('');
                },
                success: function (res) {
                    $("#showConfirmOrder").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showConfirmOrder'), width: '100%'});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //followUp modal
        $(document).on("click", ".followup-history", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr('data-inquiry-id');
            var historyStatus = $(this).attr('data-history-status');
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/getFollowUpModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    history_status: historyStatus,
                },
                beforeSend: function () {
                    $("#showFollowUpModal").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showFollowUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showFollowUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //endof followUp modal

        //After Click to Save New Follow Up
        $(document).on("click", "#saveHistory", function (e) {
            e.preventDefault();
            var formData = new FormData($('#submitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('pendingOrder/setFollowUpSave')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        data_id: 'inquiry_id',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#saveHistory').prop('disabled', true);
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
                            $('#saveHistory').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        // EOF Function for Set Lead Follow Up

        //add new lsd  row
        $(document).on("click", ".add-lsd-info", function () {
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/newLsdRow')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                },
                success: function (res) {
                    $("#addLsdRow").prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });



        //RW BREAKDOWN VIEW MODAL
        $(document).on("click", ".rw-breakdown-view", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/leadRwBreakdownView')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showRwBreakdownViewModal").html('');
                },
                success: function (res) {
                    $("#showRwBreakdownViewModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //END OF RW BREAKDOWN VIEW MODAL


        //commission set up modal
        $(document).on("click", ".commission-setup-modal", function (e) {
            var inquiryId = $(this).data('id');

            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/getCommissionSetupModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                    $("#ShowcommissionSetUpModal").html('');
                },
                success: function (res) {
                    $("#ShowcommissionSetUpModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowcommissionSetUpModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //After Click to Save new commission 
        $(document).on("click", "#cmsnSaveBtn", function (e) {
            e.preventDefault();
            var formData = new FormData($('#cmsnSubmitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('pendingOrder/commissionSetupSave')); ?>",
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('#cmsnSaveBtn').prop('disabled', true);
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
                            $('#cmsnSaveBtn').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });


        //pending order cancellation 
        $(document).on("click", ".cancellation-pending-order", function (e) {
            e.preventDefault();
            var inquiryId = $(this).data('inquiry-id');
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/pendingOrderCancelModal')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showCancellationModal").html('');
                },
                success: function (res) {
                    $("#showCancellationModal").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });


        //Function for cancellation submit form
        $(document).on("click", "#submitPendingOrderCancel", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#pendingOrderCencelForm')[0]);
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('pendingOrder/pendingOrderCancelSave')); ?>",
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
                            $('#submitPendingOrderCancel').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('<?php echo e(route("pendingOrder.index")); ?>'
                                            ), 7000);
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
                            $('#submitPendingOrderCancel').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });
        //endof pending order cancellation script

        //Quantity Summary  modal
        $(document).on("click", "#quantitySummary", function (e) {
            var productId = $(this).data('product_id');
            var brandId = $(this).data('brand_id');
            var buyerId = $(this).data('buyer_id');
            var salespersonsId = $(this).data('salespersons_id');
            var fromDate = $(this).data('from_date');
            var toDate = $(this).data('to_date');

            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/quantitySummaryView')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    brand_id: brandId,
                    buyer_id: buyerId,
                    salespersons_id: salespersonsId,
                    from_date: fromDate,
                    to_date: toDate,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#ShowQuantitySummaryModal").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#ShowQuantitySummaryModal'), width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        //inquiry reassignment modal
        $(document).on("click", ".reassign-inquiry", function (e) {
            e.preventDefault();
            var inquiryId = $(this).attr("data-inquiry-id");
            $.ajax({
                url: "<?php echo e(URL::to('pendingOrder/getInquiryReassigned')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId
                },
                beforeSend: function () {
                    $("#showInquiryReassignment").html('');
                },
                success: function (res) {
                    $("#showInquiryReassignment").html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //Function for inquiry reassignment submit form
        $(document).on("click", "#submitinquiryReassignment", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            // Serialize the form data
            var formData = new FormData($('#inquiryReassignmentForm')[0]);
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('pendingOrder/setInquiryReassigned')); ?>",
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
                            $('#submitinquiryReassignment').prop('disabled', true);
                            $('.reassign-inquiry').prop('disabled', true);
                            App.blockUI();
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(
                                    window.location.replace('<?php echo e(route("pendingOrder.index")); ?>'
                                            ), 7000);
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
                            $('#submitinquiryReassignment').prop('disabled', false);
                            $('.reassign-inquiry').prop('disabled', false);
                            App.unblockUI();
                        }
                    });//ajax
                }

            });


        });

    });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/pendingOrder/index.blade.php ENDPATH**/ ?>