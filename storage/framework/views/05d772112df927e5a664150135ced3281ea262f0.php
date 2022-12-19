<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" ><?php echo app('translator')->get('label.TOTAL_NUM_OF_BUYERS'); ?>: <?php echo !$buyerArr->isEmpty()?count($buyerArr):0; ?></span>
        <?php if(!empty($userAccessArr[17][5])): ?>
        <button class="label label-primary tooltips" href="#modalRelatedBuyer" id="relateBuyer"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_RELATED_BUYERS'); ?>">
            <?php echo app('translator')->get('label.BUYERS_RELATED_TO_THIS_SALES_PERSON'); ?>: <?php echo !empty($buyerRelatedToSalesPerson)?count($buyerRelatedToSalesPerson):0; ?>&nbsp; <i class="fa fa-search-plus"></i>
        </button>

        <button class="label label-purple-sharp tooltips" href="#modalUnassignedBuyer" id="unassignedBuyer"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_UNASSIGNED_BUYERS'); ?>">
            <?php echo app('translator')->get('label.UNASSIGNED_BUYERS'); ?>: <?php echo !empty($unassignedBuyerArr)?count($unassignedBuyerArr):0; ?>&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover relation-view">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <?php if(!$buyerArr->isEmpty()): ?>
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentBuyerArr[$request->sales_person_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-brand-check', $allCheckDisabled]); ?>

                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span><?php echo app('translator')->get('label.CHECK_ALL'); ?></span>
                            </div>
                        </th>
                        <?php endif; ?>
                        <th class=" text-center vcenter"><?php echo app('translator')->get('label.LOGO'); ?></th>
                        <th class="vcenter"><?php echo app('translator')->get('label.BUYER_NAME'); ?></th>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.NO_OF_RELATED_SALES_PERSONS'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!$buyerArr->isEmpty()): ?>
                    <?php $sl = 0; ?>
                    <?php $__currentLoopData = $buyerArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($buyerRelatedToSalesPerson) && array_key_exists($buyer->id, $buyerRelatedToSalesPerson)) {
                        $checked = 'checked';
                    }

                    $buyerDisabled = $buyerTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveBuyerArr) && in_array($buyer->id, $inactiveBuyerArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $buyerDisabled = 'disabled';
                        $buyerTooltips = __('label.INACTIVE');
                    }
                    if (!empty($dependentBuyerArr[$request->sales_person_id])) {
                        if (in_array($buyer->id, $dependentBuyerArr[$request->sales_person_id]) && ($checked != '')) {
                            $buyerDisabled = 'disabled';
                            $checkCondition = 1;
                            $buyerTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('buyer['.$buyer->id.']', $buyer->id, $checked, ['id' => $buyer->id, 'data-id'=> $buyer->id,'class'=> 'md-check buyer-check', $buyerDisabled]); ?>

                                <label for="<?php echo $buyer->id; ?>">
                                    <span class="inc tooltips" data-placement="right" title="<?php echo e($buyerTooltips); ?>"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="<?php echo e($buyerTooltips); ?>"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="<?php echo e($buyerTooltips); ?>"></span>
                                </label>
                            </div>
                            <?php if($checkCondition == '1'): ?>
                            <?php echo Form::hidden('buyer['.$buyer->id.']', $buyer->id); ?>

                            <?php endif; ?>
                        </td>
                        <td class="text-center vcenter">
                            <?php if(!empty($buyer->logo)): ?>
                            <img alt="<?php echo e($buyer->name); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/buyer/<?php echo e($buyer->logo); ?>" width="40" height="40"/>
                            <?php else: ?>
                            <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="40" height="40"/>
                            <?php endif; ?>
                        </td>
                        <td class="vcenter"><?php echo $buyer->name ?? ''; ?></td>
                        <td class="text-center vcenter">
                            <?php if(!empty($salesPersonToBuyerCountList[$buyer->id])): ?>
                            <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list"  
                                    title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST'); ?>" href="#modalRelatedSalesPersonList" data-id="<?php echo $buyer->id; ?>" data-toggle="modal">
                                <?php echo $salesPersonToBuyerCountList[$buyer->id]; ?>

                            </button>
                            <?php else: ?>
                            <span class="label label-sm label-gray-mint sales-person-count-<?php echo e($buyer->id); ?> tooltips" title="<?php echo app('translator')->get('label.NO_RELATED_SALES_PERSON'); ?>"><?php echo 0; ?></span>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td class="vcenter text-danger" colspan="20"><?php echo app('translator')->get('label.NO_BUYER_FOUND'); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            <?php if(!$buyerArr->isEmpty()): ?>
            <?php if(!empty($userAccessArr[17][7])): ?>
            <button class="btn btn-circle green btn-submit" id="saveSalesPersonToBuyerRel" type="button">
                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
            </button>
            <?php endif; ?>
            <?php if(!empty($userAccessArr[17][1])): ?>
            <a href="<?php echo e(URL::to('/salesPersonToBuyer')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!--related sales person list-->
<div class="modal fade" id="modalRelatedSalesPersonList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSalesPersonList"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php if (!$buyerArr->isEmpty()) { ?>
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

        //related sales person list modal
        $(document).on("click", ".related-sales-person-list", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo e(URL::to('salesPersonToBuyer/getRelatedSalesPersonList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showRelatedSalesPersonList").html('');
                },
                success: function (res) {
                    $("#showRelatedSalesPersonList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });


    });


</script><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/salesPersonToBuyer/showBuyers.blade.php ENDPATH**/ ?>