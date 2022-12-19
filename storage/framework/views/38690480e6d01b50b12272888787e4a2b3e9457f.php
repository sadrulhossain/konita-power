<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i><?php echo app('translator')->get('label.NEW_OPPORTUNITY_LIST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[69][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('crmNewOpportunity/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_OPPORTUNITY'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'crmNewOpportunity/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="buyer"><?php echo app('translator')->get('label.BUYER'); ?></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('buyer',  $buyerArr, Request::get('buyer'), ['class' => 'form-control js-source-states','id'=>'buyer']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="sourceId"><?php echo app('translator')->get('label.SOURCE'); ?></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('source_id',  $sourceList, Request::get('source_id'), ['class' => 'form-control js-source-states','id'=>'sourceId']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="product"><?php echo app('translator')->get('label.PRODUCT'); ?></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('product', $productArr, Request::get('product'), ['class' => 'form-control js-source-states','id'=>'product']); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="brand"><?php echo app('translator')->get('label.BRAND'); ?></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('brand', $brandArr, Request::get('brand'), ['class' => 'form-control js-source-states','id'=>'brand']); ?>

                                </div>
                            </div>
                        </div>
                        <?php if(Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1'): ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="createdBy"><?php echo app('translator')->get('label.CREATED_BY'); ?></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('created_by',  $employeeList, Request::get('created_by'), ['class' => 'form-control js-source-states','id'=>'createdBy']); ?>

                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if(!(Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1')): ?>
                        <div class="col-md-4 text-center">
                            <div class="form">
                                <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                    <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if(Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1'): ?>
                        <div class="col-md-4 text-center">
                            <div class="form">
                                <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                    <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.BUYER'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.SOURCE'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.DATE_OF_CREATION'); ?></th>
                            <?php if(Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1'): ?>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.CREATED_BY'); ?></th>
                            <?php endif; ?>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.REMARKS'); ?></th>
                            <th class="vcenter text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
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
                        if ($target->buyer_has_id == '0') {
                            $buyer = $target->buyer;
                        } elseif ($target->buyer_has_id == '1') {
                            $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                        }
                        ?>
                        <tr>
                            <td class="vcenter text-center"><?php echo e(++$sl); ?></td>
                            <td class="vcenter"><?php echo $buyer ?? ''; ?></td>
                            <td class="vcenter"><?php echo $target->source ?? ''; ?></td>
                            <td class="vcenter text-center"><?php echo !empty($target->created_at) ? Helper::formatDate($target->created_at) : ''; ?></td>
                            <?php if(Auth::user()->group_id == '1' || Auth::user()->for_crm_leader == '1'): ?>
                            <td class="vcenter"><?php echo $target->opportunity_creator ?? ''; ?></td>
                            <?php endif; ?>
                            <td class="vcenter"><?php echo $target->remarks ?? ''; ?></td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <?php if(!empty($userAccessArr[69][3])): ?>
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="<?php echo e(URL::to('crmNewOpportunity/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[69][4])): ?>
                                    <?php echo e(Form::open(array('url' => 'crmNewOpportunity/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                    <?php endif; ?>

                                    <?php if(!empty($crmLeader)): ?>
                                    <?php if(!empty($userAccessArr[69][7])): ?>
                                    <button class="btn btn-xs green-soft tooltips vcenter assign-opportunity" title="<?php echo app('translator')->get('label.CLICK_TO_ASSIGN_OPPORTUNITY'); ?>" href="#modalAssignOpportunity" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-external-link-square"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[69][5])): ?>
                                    <button class="btn btn-xs yellow tooltips vcenter opportunity-details" title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_OPPORTUNITY_DETAILS'); ?>" href="#modalOpportunityDetails" data-id="<?php echo $target->id; ?>" data-toggle="modal">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7"><?php echo app('translator')->get('label.NO_NEW_OPPORTUNITY_FOUND'); ?></td>
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

<!--opportunity details-->
<div class="modal fade" id="modalOpportunityDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOpportunityDetails"></div>
    </div>
</div>

<!--assign opportunity-->
<div class="modal fade" id="modalAssignOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAssignOpportunity"></div>
    </div>
</div>

<!-- Modal end -->

<script type="text/javascript">
    $(function () {
        //opportunity details modal
        $(".opportunity-details").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/crmNewOpportunity/getOpportunityDetails')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId
                },
                beforeSend: function () {
                    $("#showOpportunityDetails").html('');
                },
                success: function (res) {
                    $("#showOpportunityDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //opportunity assignment modal
        $(".assign-opportunity").on("click", function (e) {
            e.preventDefault();
            var opportunityId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('/crmNewOpportunity/getOpportunityToMemberToRelate')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    opportunity_id: opportunityId,
                },
                beforeSend: function () {
                    $("#showAssignOpportunity").html('');
                },
                success: function (res) {
                    $("#showAssignOpportunity").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //After Click to Save new opportunity assignment 
        $(document).on("click", "#saveOpportunityAssignment", function (e) {
            e.preventDefault();
            var formData = new FormData($('#opportunityAssignmentForm')[0]);
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
                        url: "<?php echo e(URL::to('crmNewOpportunity/relateOpportunityToMember')); ?>",
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
                            $('#saveOpportunityAssignment').prop('disabled', true);
                            App.blockUI({
                                boxed: true,
                            });
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location = "<?php echo e(URL::to('/crmNewOpportunity'.Helper::queryPageStr($qpArr))); ?>";

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
                            $('#saveOpportunityAssignment').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/crmNewOpportunity/index.blade.php ENDPATH**/ ?>