<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.BUYER_PROFILE'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[18][1])): ?>
                <a href="<?php echo e(URL::to('/buyer'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;<?php echo app('translator')->get('label.CLICK_TO_GO_BACK'); ?>
                </a>
                <?php endif; ?>
                <?php if(!empty($userAccessArr[18][6])): ?>
                <a class="btn green-haze pull-right tooltips vcenter margin-left-right-5" target="_blank" href="<?php echo e(URL::to('buyer/'.$target->id.'/printProfile')); ?>"  title="<?php echo app('translator')->get('label.CLICK_HERE_TO_PRINT'); ?>">
                    <i class="fa fa-print"></i>&nbsp;<?php echo app('translator')->get('label.PRINT'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <!--Start :: Basic Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.BASIC_INFORMATION'); ?></strong></h4>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 margin-top-10 text-center">
                    <?php if(!empty($target->logo) && File::exists('public/uploads/buyer/' . $target->logo)): ?>
                    <img alt="<?php echo e($target->name); ?>" src="<?php echo e(URL::to('/')); ?>/public/uploads/buyer/<?php echo e($target->logo); ?>" width="150" height="150"/>
                    <?php else: ?>
                    <img alt="unknown" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" width="150" height="150"/>
                    <?php endif; ?>
                    <?php if(!empty($target->name)): ?>
                    <h5 class="bold text-center margin-top-10">
                        <?php echo $target->name . (!empty($target->code) ? ' (' . $target->code . ')' : ''); ?>

                    </h5>
                    <?php endif; ?>
                    <?php if(!empty($latestFollowupArr)): ?>
                    <?php if($latestFollowupArr['status'] == '1'): ?>
                    <span class="label bold label-rounded label-sm label-yellow"><?php echo app('translator')->get('label.NORMAL'); ?></span>
                    <?php elseif($latestFollowupArr['status'] == '2'): ?>
                    <span class="label bold label-rounded label-sm label-green-seagreen"><?php echo app('translator')->get('label.HAPPY'); ?></span>
                    <?php elseif($latestFollowupArr['status'] == '3'): ?>
                    <span class="label bold label-rounded label-sm label-red-soft"><?php echo app('translator')->get('label.UNHAPPY'); ?></span>
                    <?php endif; ?>
                    <?php else: ?>
                    <span class="label bold label-rounded label-sm label-gray-mint"><?php echo app('translator')->get('label.NO_FOLLOWUP_YET'); ?></span>
                    <?php endif; ?>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.CATEGORY'); ?></td>
                                    <td class="active"colspan="5"><?php echo $target->category ?? __('label.N_A'); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.FSC_CERTIFIED'); ?></td>
                                    <td class="active"colspan="5">
                                        <?php if($target->fsc_certified == '1'): ?>
                                        <span class="label label-sm label-blue-steel"><?php echo app('translator')->get('label.YES'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-sm label-red-flamingo"><?php echo app('translator')->get('label.NO'); ?></span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.COUNTRY'); ?></td>
                                    <td class="active"colspan="5"><?php echo $target->country ?? __('label.N_A'); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.ISO_CERTIFIED'); ?></td>
                                    <td class="active"colspan="5">
                                        <?php if($target->iso_certified == '1'): ?>
                                        <span class="label label-sm label-blue-steel"><?php echo app('translator')->get('label.YES'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-sm label-red-flamingo"><?php echo app('translator')->get('label.NO'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.DIVISION'); ?></td>
                                    <td class="active"colspan="5"><?php echo $target->division ?? __('label.N_A'); ?></td>

                                    <td class="fit bold info"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                    <td class="active"colspan="5">
                                        <?php if($target->status == '1'): ?>
                                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.DATE_OF_ENTRY'); ?></td>
                                    <td class="active"colspan="5"><?php echo !empty($target->created_at) ? Helper::formatDate($target->created_at) : __('label.N_A'); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.TYPE'); ?></td>
                                    <td class="active"colspan="5">
                                        <?php if(!empty($typeArr)): ?>
                                        <?php $__currentLoopData = $typeArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($type == 1): ?>
                                        <span class="label bold label-sm label-yellow-casablanca"><?php echo app('translator')->get('label.BONDED'); ?></span>
                                        <?php elseif($type == 2): ?>
                                        <span class="label bold label-sm label-purple-sharp"><?php echo app('translator')->get('label.COMMERCIAL'); ?></span>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <span class="label bold label-sm label-red-soft"><?php echo app('translator')->get('label.N_A'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.DATE_OF_BUSINESS_STARTED'); ?></td>
                                    <td class="active"colspan="5"><?php echo !empty($businessInitationDate->start) ? Helper::formatDate($businessInitationDate->start) : __('label.N_A'); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.BRAND_OF_MACHINE'); ?></td>
                                    <td class="active"colspan="5"><?php echo $target->machine_brand ?? __('label.N_A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.HEAD_OFFICE'); ?></td>
                                    <td class="active"colspan="11"><?php echo $target->head_office_address ?? __('label.N_A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.PRIMARY_FACTORY'); ?></td>
                                    <td class="active"colspan="11">
                                        <?php if(!empty($primaryFactory->name)): ?>
                                        <span class="bold"><?php echo $primaryFactory->name; ?></span>
                                        <?php if(!empty($primaryFactory->address)): ?>
                                        <br/><span><?php echo $primaryFactory->address; ?></span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Basic Information-->

            <!--Start :: Contact Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.CONTACT_PERSON_INFORMATION'); ?></strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.NAME'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PHONE'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SPECIAL_NOTE'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($contactPersonArr)): ?>
                                <?php $sl = 0; ?>
                                <?php $__currentLoopData = $contactPersonArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                    <td class="vcenter"><?php echo $contact['name'] ?? __('label.N_A'); ?></td>
                                    <td class="vcenter"><?php echo !empty($contact['designation_id']) && !empty($contactDesignationList[$contact['designation_id']]) ? $contactDesignationList[$contact['designation_id']] : __('label.N_A'); ?></td>
                                    <td class="vc text-primary"><?php echo $contact['email'] ?? __('label.N_A'); ?></td>
                                    <td class="vcenter">
                                        <?php if(is_array($contact['phone'])): ?>

                                        <?php
                                        $lastValue = end($contact['phone']);
                                        ?>
                                        <?php $__currentLoopData = $contact['phone']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyP => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($p ?? __('label.N_A')); ?>

                                        <?php if($lastValue !=$p): ?>
                                        <span>,</span>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <?php echo $contact['phone'] ?? __('label.N_A'); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter"><?php echo $contact['specoal_note'] ?? __('label.N_A'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>                    
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Contact Person Information-->

            <!--Start :: Sales Person Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.ACTIVELY_ENGAGED_SALES_PERSON_INFORMATION'); ?></strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.NAME'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.EMPLOYEE_ID'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PHONE'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL_ORDER_INVOLVEMENT'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$activelyEngagedSalesPersonArr->isEmpty()): ?>
                                <?php $sl = 0; ?>
                                <?php $__currentLoopData = $activelyEngagedSalesPersonArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                    <td class="text-center vcenter" width="30px">
                                        <?php if(!empty($sp->photo) && File::exists('public/uploads/user/' . $sp->photo)): ?>
                                        <img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($sp->photo); ?>" alt="<?php echo e($sp->name); ?>"/>
                                        <?php else: ?>
                                        <img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($sp->name); ?>"/>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter"><?php echo $sp->name ?? __('label.N_A'); ?></td>
                                    <td class="vcenter"><?php echo $sp->employee_id ?? __('label.N_A'); ?></td>
                                    <td class="vcenter"><?php echo $sp->designation ?? __('label.N_A'); ?></td>
                                    <td class="vcenter text-primary"><?php echo $sp->email ?? __('label.N_A'); ?></td>
                                    <td class="vcenter"><?php echo $sp->phone ?? __('label.N_A'); ?></td>
                                    <!--<td class="text-center vcenter width-100"><?php echo !empty($activelyEngagedSalesPersonOrderList[$sp->id]) ? $activelyEngagedSalesPersonOrderList[$sp->id] : 0; ?></td>-->
                                    <td class="text-center vcenter width-100">
                                        <?php if(!empty($activelyEngagedSalesPersonOrderList[$sp->id])): ?>
                                        <button class="btn btn-xs bold green-seagreen tooltips vcenter involved-order-list"  
                                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_INVOLVED_ORDER_LIST'); ?>" 
                                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                                data-sales-person-id="<?php echo $sp->id; ?>" data-type-id="0" data-toggle="modal">
                                            <?php echo $activelyEngagedSalesPersonOrderList[$sp->id]; ?>

                                        </button>
                                        <?php else: ?>
                                        <span class="label label-sm bold label-gray-mint tooltips" title="<?php echo app('translator')->get('label.NO_ORDER_INVOLVEMENT'); ?>"><?php echo 0; ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>                    
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Sales Person Information-->

            <!--Start :: Product Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.PRODUCT_INFORMATION'); ?></strong></h4>
                </div>
                <?php if(!empty($productInfoArr)): ?>
                <div class="col-md-12 margin-top-10 text-center">
                    <span class="text-green bold">
                        (<?php echo app('translator')->get('label.ASTERIC_SIGN_REFERS_TO_BRAND_IN_BUSINESS'); ?>)
                    </span>
                </div>
                <?php endif; ?>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <thead>
                                <tr  class="info">
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.IMPORT_VOLUME'); ?></th>
                                    <th class="vcenter text-center" colspan="2"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.MACHINE_TYPE'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.MACHINE_LENGTH'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($productInfoArr)): ?>
                                <?php $sl = 0; ?>
                                <?php $__currentLoopData = $productInfoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center vcenter" rowspan="<?php echo e($productRowSpanArr[$productId]['brand']); ?>"><?php echo ++$sl; ?></td>
                                    <td class="vcenter" rowspan="<?php echo e($productRowSpanArr[$productId]['brand']); ?>"><?php echo $product['product_name'] ?? __('label.N_A'); ?></td>

                                    <?php
                                    $volume = __('label.N_A');
                                    $textAlignment = 'center';
                                    if (!empty($importVolArr[$productId]['volume']) && $importVolArr[$productId]['volume'] != 0) {
                                        $unit = !empty($importVolArr[$productId]['unit']) ? ' ' . $importVolArr[$productId]['unit'] : '';
                                        $volume = Helper::numberFormat2Digit($importVolArr[$productId]['volume']) . $unit;
                                        $textAlignment = 'right';
                                    }
                                    ?>
                                    <td class="text-<?php echo e($textAlignment); ?> vcenter" rowspan="<?php echo e($productRowSpanArr[$productId]['brand']); ?>"><?php echo $volume; ?></td>

                                    <?php if(!empty($product['brand'])): ?>
                                    <?php $i = 0; ?>
                                    <?php $__currentLoopData = $product['brand']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandId => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="text-center vcenter" width="30px">
                                        <?php if(!empty($brand['logo']) && File::exists('public/uploads/brand/' . $brand['logo'])): ?>
                                        <img class="pictogram-min-space tooltips" width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/brand/<?php echo e($brand['logo']); ?>" alt="<?php echo e($brand['brand_name']); ?>" title="<?php echo e($brand['brand_name']); ?>"/>
                                        <?php else: ?> 
                                        <img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" alt=""/>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter">
                                        <?php echo $brand['brand_name'] ?? __('label.N_A'); ?>

                                        <?php if(!empty($brandWiseVolumeRateArr[$productId])): ?>
                                        <?php if(array_key_exists($brandId, $brandWiseVolumeRateArr[$productId])): ?>
                                        <span class="text-green bold">*</span><br/>
                                        <?php $percentage = !empty($brandWiseVolumeRateArr[$productId][$brandId]) ? Helper::numberFormatDigit2($brandWiseVolumeRateArr[$productId][$brandId]) : '0.00'; ?>
                                        <span class="text-green bold">
                                            (<?php echo app('translator')->get('label.PERCENTAGE_OF_TOTAL_SALES_VOLUME', ['percentage' => $percentage]); ?>)
                                        </span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center vcenter">
                                        <?php if(!empty($brand['machine_type'])): ?>
                                        <?php if($brand['machine_type'] == '1'): ?>
                                        <span class="label label-sm label-yellow"><?php echo app('translator')->get('label.MANUAL'); ?></span>
                                        <?php elseif($brand['machine_type'] == '2'): ?>
                                        <span class="label label-sm label-green-seagreen"><?php echo app('translator')->get('label.AUTOMATIC'); ?></span>
                                        <?php elseif($brand['machine_type'] == '3'): ?>
                                        <span class="label label-sm label-purple-sharp"><?php echo app('translator')->get('label.BOTH_MANUAL_N_AUTOMATIC'); ?></span>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <span class="label label-sm label-red-soft"><?php echo app('translator')->get('label.N_A'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter"><?php echo $brand['machine_length'] ?? __('label.N_A'); ?></td>
                                    <?php
                                    if ($i < ($productRowSpanArr[$productId]['brand'] - 1)) {
                                        echo '</tr>';
                                    }
                                    $i++;
                                    ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="7"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                </tr>                    
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End :: Product Information-->

            <!--Start :: Others Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.OTHERS_INFORMATION'); ?></strong></h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <!--Start :: Finished Goods Information-->
                    <div class="row">
                        <div class="col-md-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr  class="info">
                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.FINISHED_GOODS'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($finishedGoodsArr)): ?>
                                        <?php $sl = 0; ?>
                                        <?php $__currentLoopData = $finishedGoodsArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $goods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                            <td class="vcenter"><?php echo $finishedGoodsList[$goods] ?? __('label.N_A'); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="2"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                        </tr>                    
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End :: Finished Goods Information-->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <!--Start :: Competitors' Product Information-->
                    <div class="row">
                        <div class="col-md-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr  class="info">
                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.COMPETITORS_PRODUCT'); ?></th>
                                            <th class="vcenter text-center"><?php echo app('translator')->get('label.IMPORT_VOLUME'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($competitorsProductArr)): ?>
                                        <?php $sl = 0; ?>
                                        <?php $__currentLoopData = $competitorsProductArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                            <td class="vcenter"><?php echo $competitorsProductList[$product] ?? __('label.N_A'); ?></td>
                                            <?php
                                            $volume = __('label.N_A');
                                            $textAlignment = 'center';
                                            if (!empty($importVolArr[$product]['volume']) && $importVolArr[$product]['volume'] != 0) {
                                                $unit = !empty($importVolArr[$product]['unit']) ? ' ' . $importVolArr[$product]['unit'] : '';
                                                $volume = Helper::numberFormat2Digit($importVolArr[$product]['volume']) . $unit;
                                                $textAlignment = 'right';
                                            }
                                            ?>
                                            <td class="text-<?php echo e($textAlignment); ?> vcenter"><?php echo $volume; ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="3"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                        </tr>                    
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End :: Competitors' Product Information-->
                </div>
            </div>
            <!--End :: Others Information-->

            <!--Start :: Business Information-->
            <div class="row padding-10">
                <div class="col-md-12 border-bottom-1-green-seagreen">
                    <h4><strong><?php echo app('translator')->get('label.BUSINESS_INFORMATION'); ?></strong></h4>
                </div>
                <div class="col-md-12 margin-top-10">
                    <div class="table-responsive webkit-scrollbar">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.TOTAL_ORDERS'); ?></td>
                                    <td class="active text-center"colspan="5"><?php echo $inquiryCountArr['total'] ?? 0; ?></td>
                                    <td class="fit bold info"colspan="1"><?php echo app('translator')->get('label.MOST_FREQUENT_CAUSE_OF_FAILURE'); ?></td>
                                    <td class="active"colspan="11">
                                        <?php if(!empty($mostFrequentCancelCauseArr)): ?>
                                        <?php $__currentLoopData = $mostFrequentCancelCauseArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $causeId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $labelColor = ($key == 0 || $key % 2 == 0) ? 'red-soft' : 'red-mint';
                                        ?>
                                        <span class="label margin-2 bold label-sm label-<?php echo e($labelColor); ?>"><?php echo $cancelCauseList[$causeId]; ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <span class="label margin-2 bold label-sm label-gray-mint"><?php echo app('translator')->get('label.N_A'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div id="orderSummaryPie" class="chart-block"></div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="12">
                                                <?php echo app('translator')->get('label.SALES_N_SHIPMENT_SUMMARY'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SALES_VOLUME'); ?></td>
                                            <td class="active text-right"colspan="5"><?php echo !empty($overAllSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_volume) : '0.00'; ?> <?php echo app('translator')->get('label.UNIT'); ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SALES_AMOUNT'); ?></td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($overAllSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($overAllSalesSummaryArr->total_amount) : '0.00'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SALES_VOLUME'); ?> (<?php echo app('translator')->get('label.LAST_1_YEAR'); ?>)</td>
                                            <td class="active text-right"colspan="5"><?php echo !empty($lastOneYearSalesSummaryArr->total_volume) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_volume) : '0.00'; ?> <?php echo app('translator')->get('label.UNIT'); ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SALES_AMOUNT'); ?> (<?php echo app('translator')->get('label.LAST_1_YEAR'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($lastOneYearSalesSummaryArr->total_amount) ? Helper::numberFormat2Digit($lastOneYearSalesSummaryArr->total_amount) : '0.00'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SHIPPED_VOLUME'); ?></td>
                                            <td class="active text-right"colspan="5"><?php echo !empty($buyerPaymentArr['shipped_quantity']) ? Helper::numberFormat2Digit($buyerPaymentArr['shipped_quantity']) : '0.00'; ?> <?php echo app('translator')->get('label.UNIT'); ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.SHIPMENT_PAYABLE'); ?></td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($buyerPaymentArr['payable']) ? Helper::numberFormat2Digit($buyerPaymentArr['payable']) : '0.00'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($inquiryCountArr['total']) && $inquiryCountArr['total'] != 0): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10 text-center">
                        <?php if(!empty($inquiryCountArr['upcoming']) && $inquiryCountArr['upcoming'] != 0): ?>
                        <button class="btn btn-xs bold blue-soft tooltips vcenter involved-order-list"  
                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_UPCOMING_ORDER_LIST'); ?>" 
                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                data-sales-person-id="0" data-type-id="1" data-toggle="modal">
                            <?php echo app('translator')->get('label.UPCOMING_ORDER_LIST'); ?>
                        </button>
                        <?php endif; ?>

                        <?php if(!empty($inquiryCountArr['pipeline']) && $inquiryCountArr['pipeline'] != 0): ?>
                        <button class="btn btn-xs bold purple tooltips vcenter involved-order-list"  
                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_PIPELINE_ORDER_LIST'); ?>" 
                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                data-sales-person-id="0" data-type-id="2" data-toggle="modal">
                            <?php echo app('translator')->get('label.PIPELINE_ORDER_LIST'); ?>
                        </button>
                        <?php endif; ?>

                        <?php if(!empty($inquiryCountArr['confirmed']) && $inquiryCountArr['confirmed'] != 0): ?>
                        <button class="btn btn-xs bold yellow-casablanca tooltips vcenter involved-order-list"  
                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_CONFIRMED_ORDER_LIST'); ?>" 
                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                data-sales-person-id="0" data-type-id="3" data-toggle="modal">
                            <?php echo app('translator')->get('label.CONFIRMED_ORDER_LIST'); ?>
                        </button>
                        <?php endif; ?>

                        <?php if(!empty($inquiryCountArr['accomplished']) && $inquiryCountArr['accomplished'] != 0): ?>
                        <button class="btn btn-xs bold green-seagreen tooltips vcenter involved-order-list"  
                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_ACCOMPLISHED_ORDER_LIST'); ?>" 
                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                data-sales-person-id="0" data-type-id="4" data-toggle="modal">
                            <?php echo app('translator')->get('label.ACCOMPLISHED_ORDER_LIST'); ?>
                        </button>
                        <?php endif; ?>

                        <?php if(!empty($inquiryCountArr['failed']) && $inquiryCountArr['failed'] != 0): ?>
                        <button class="btn btn-xs bold red-flamingo tooltips vcenter involved-order-list"  
                                title="<?php echo app('translator')->get('label.CLICK_TO_VIEW_CANCELLED_ORDER_LIST'); ?>" 
                                href="#modalInvolvedOrderList" data-buyer-id="<?php echo $target->id; ?>" 
                                data-sales-person-id="0" data-type-id="5" data-toggle="modal">
                            <?php echo app('translator')->get('label.CANCELLED_ORDER_LIST'); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-top-10">
                            <div id="salesVolumeLastFiveYears" class="chart-block"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-top-10">
                            <div id="salesAmountLastFiveYears" class="chart-block"></div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="6">
                                                <?php echo app('translator')->get('label.PAYMENT_SUMMARY'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.PAID_AMOUNT'); ?> (<?php echo app('translator')->get('label.FROM_BUYER_TO_SUPPLIER'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($buyerPaymentArr['paid']) ? Helper::numberFormat2Digit($buyerPaymentArr['paid']) : '0.00'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.PAYMENT_DUE'); ?> (<?php echo app('translator')->get('label.FROM_BUYER_TO_SUPPLIER'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($buyerPaymentArr['due']) ? Helper::numberFormat2Digit($buyerPaymentArr['due']) : '0.00'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.INVOICED_AMOUNT'); ?> (<?php echo app('translator')->get('label.TO_SUPPLIER'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($invoicedAmount) ? Helper::numberFormat2Digit($invoicedAmount) : '0.00'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.RECEIVED_AMOUNT'); ?> (<?php echo app('translator')->get('label.FROM_SUPPLIER'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($received->total_collection) ? Helper::numberFormat2Digit($received->total_collection) : '0.00'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 margin-top-10">
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="12">
                                                <?php echo app('translator')->get('label.INCOME_SUMMARY'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.NET_INCOME'); ?> (<?php echo app('translator')->get('label.EXPECTED'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($netIncome) ? Helper::numberFormat2Digit($netIncome) : '0.00'; ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.NET_INCOME'); ?> (<?php echo app('translator')->get('label.RECEIVED_AT_ACTUAL'); ?>)</td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($received->net_income) ? Helper::numberFormat2Digit($received->net_income) : '0.00'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive webkit-scrollbar">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="fit bold info" colspan="18">
                                                <?php echo app('translator')->get('label.BUYER_COMMISSION_SUMMARY'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.TOTAL_AMOUNT'); ?></td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($commissionReceived) ? Helper::numberFormat2Digit($commissionReceived) : '0.00'; ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.PAID'); ?></td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($commissionPaid) ? Helper::numberFormat2Digit($commissionPaid) : '0.00'; ?></td>
                                            <td class="fit bold info"><?php echo app('translator')->get('label.DUE'); ?></td>
                                            <td class="active text-right"colspan="5">$<?php echo !empty($commissionDue) ? Helper::numberFormat2Digit($commissionDue) : '0.00'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End :: Business Information-->
        </div>
    </div>
</div>


<!-- Modal start -->
<!--related sales person list-->
<div class="modal fade" id="modalInvolvedOrderList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInvolvedOrderList"></div>
    </div>
</div>

<!-- Modal end -->

<script src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

//related sales person list modal
    $(".involved-order-list").on("click", function (e) {
        e.preventDefault();
        var buyerId = $(this).attr("data-buyer-id");
        var salesPersonId = $(this).attr("data-sales-person-id");
        var typeId = $(this).attr("data-type-id");
        $.ajax({
            url: "<?php echo e(URL::to('/buyer/getInvolvedOrderList')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                buyer_id: buyerId,
                sales_person_id: salesPersonId,
                type_id: typeId,
            },
            beforeSend: function () {
                $("#showInvolvedOrderList").html('');
            },
            success: function (res) {
                $("#showInvolvedOrderList").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

    //start :: order summary pie
    var orderSummaryPieOptions = {
<?php
$upcoming = $inquiryCountArr['upcoming'] ?? 0;
$pipeline = $inquiryCountArr['pipeline'] ?? 0;
$confirmed = $inquiryCountArr['confirmed'] ?? 0;
$accomplished = $inquiryCountArr['accomplished'] ?? 0;
$cancelled = $inquiryCountArr['failed'] ?? 0;
?>
        series: [
<?php
echo $upcoming . ', ' . $pipeline . ', ' . $confirmed . ', ' . $accomplished . ', ' . $cancelled;
?>
        ],
        labels: ["<?php echo app('translator')->get('label.UPCOMING'); ?>", "<?php echo app('translator')->get('label.PIPE_LINE'); ?>"
                    , "<?php echo app('translator')->get('label.CONFIRMED'); ?>", "<?php echo app('translator')->get('label.ACCOMPLISHED'); ?>"
                    , "<?php echo app('translator')->get('label.CANCELLED'); ?>"],
        chart: {
            width: 380,
            type: 'donut',
        },
        dataLabels: {
            enabled: true
        },
        colors: ["#4C87B9", "#8E44AD", "#F2784B", "#1BA39C", "#EF4836"],
        fill: {
            type: 'gradient',
        },
        legend: {
            fontSize: '12px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 600,
            formatter: function (val, opts) {
                var indx = opts.w.globals.series[opts.seriesIndex];
                return val + ': ' + indx
            },
            labels: {
                colors: ['#FFFFFF'],
                useSeriesColors: true
            },
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: '#fff',
                fillColors: [],
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return  val
                },

            }
        },
        responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
    };
    var orderSummaryPie = new ApexCharts(document.querySelector("#orderSummaryPie"), orderSummaryPieOptions);
    orderSummaryPie.render();
    //end :: order summary pie

    //start :: sales volume last five years
    var salesVolumeLastFiveYearsOptions = {
        series: [
            {
                name: "<?php echo app('translator')->get('label.SALES_VOLUME'); ?>",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $volume = $salesSummaryArr[$year]['volume'] ?? 0;
        echo "'$volume',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#1BA39C'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "<?php echo app('translator')->get('label.SALES_VOLUME'); ?> (<?php echo app('translator')->get('label.LAST_5_YEAR'); ?>)",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "<?php echo app('translator')->get('label.YEARS'); ?>"
            }
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.VOLUME'); ?> (<?php echo app('translator')->get('label.UNIT'); ?>)",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return val + " <?php echo app('translator')->get('label.UNIT'); ?>"
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesVolumeLastFiveYears = new ApexCharts(document.querySelector("#salesVolumeLastFiveYears"), salesVolumeLastFiveYearsOptions);
    salesVolumeLastFiveYears.render();
    //end :: sales volume last five years

    //start :: sales amount last five years
    var salesAmountLastFiveYearsOptions = {
        series: [
            {
                name: "<?php echo app('translator')->get('label.SALES_AMOUNT'); ?>",
                data: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        $amount = $salesSummaryArr[$year]['amount'] ?? 0;
        echo "'$amount',";
    }
}
?>
                ]
            },
        ],
        chart: {
            height: 250,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#8E44AD'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
        },
        title: {
            text: "<?php echo app('translator')->get('label.SALES_AMOUNT'); ?> (<?php echo app('translator')->get('label.LAST_5_YEAR'); ?>)",
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
<?php
if (!empty($yearArr)) {
    foreach ($yearArr as $year => $yearName) {
        echo "'$yearName',";
    }
}
?>
            ],
            title: {
                text: "<?php echo app('translator')->get('label.YEARS'); ?>"
            }
        },
        yaxis: {
            title: {
                text: "<?php echo app('translator')->get('label.SALES_AMOUNT'); ?> ($)",
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        return "$" + val
                                + growthOrDecline(val, series[seriesIndex][dataPointIndex - 1])
                    }

                },
            ]
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            floating: true,
            offsetY: 15,
            offsetX: -5
        }
    };

    var salesAmountLastFiveYears = new ApexCharts(document.querySelector("#salesAmountLastFiveYears"), salesAmountLastFiveYearsOptions);
    salesAmountLastFiveYears.render();
    //end :: sales amount last five years

});

function growthOrDecline(thisYear, prevYear) {
    var rateText = '';
    var rate = 0;
    var defaultPrevYear = 1;

    if (thisYear >= prevYear) {
        if (prevYear > 0) {
            defaultPrevYear = prevYear;
        }
        rate = ((thisYear - prevYear) * 100) / defaultPrevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-green-seagreen'>&nbsp;(+" + rate + "% form previous year)</span>";
    } else if (thisYear < prevYear) {
        rate = ((prevYear - thisYear) * 100) / prevYear;
        rate = parseFloat(rate).toFixed(2);
        rateText = "<span class='text-danger'>&nbsp;(-" + rate + "% form previous year)</span>";
    } else {
        rateText = "";
    }

    return rateText;
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/buyer/profile/show.blade.php ENDPATH**/ ?>