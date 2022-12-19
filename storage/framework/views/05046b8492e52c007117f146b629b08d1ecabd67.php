<html>
    <head>
        <title><?php echo app('translator')->get('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM'); ?></title>
        <?php if(Request::get('view') == 'print'): ?>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')); ?>" rel="stylesheet" type="text/css" /> 
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')); ?>" rel="stylesheet" type="text/css" /> 
        <?php elseif(Request::get('view') == 'pdf'): ?>
        <link rel="shortcut icon" href="<?php echo base_path(); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'); ?>" rel="stylesheet" type="text/css"/> 
        <?php endif; ?>
    </head>
    <body>
        <!--        <div class="header">
                    <?php if(Request::get('view') == 'pdf'): ?>
                    <img src="<?php echo base_path(); ?>/public/img/logo_small_print.png" alt="RTMS Logo" /> <?php else: ?>
                    <img src="<?php echo asset('public/img/logo_small_print.png'); ?>" alt="RTMS Logo" /> <?php endif; ?>
                    <p><?php echo app('translator')->get('label.ACADEMIC_REPORT'); ?></p>
                </div>-->
        <!--Endof_BL_history data-->

        <?php
        $basePath = URL::to('/');
        if (Request::get('view') == 'pdf') {
            $basePath = base_path();
        }
        ?>
        <div class="portlet-body">

            <div class="row margin-bottom-10">
                <!--header-->
                <div class="col-md-12">
                    <table class="table borderless">
                        <tr>
                            <td width='40%'>
                                <span>
                                    <img src="<?php echo e($basePath); ?>/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                </span>
                            </td>
                            <td class="text-right font-size-11" width='60%'>
                                <span><?php echo e(!empty($konitaInfo->name)?$konitaInfo->name:''); ?></span><br/>
                                <span><?php echo e(!empty($konitaInfo->address)?$konitaInfo->address:''); ?></span><br/>
                                <span><?php echo app('translator')->get('label.PHONE'); ?>: </span><span><?php echo e(!empty($phoneNumber)?$phoneNumber:''); ?></span><br/>
                                <span><?php echo app('translator')->get('label.EMAIL'); ?>: </span><span><?php echo e(!empty($konitaInfo->email)?$konitaInfo->email.', ':''); ?></span>
                                <span><?php echo app('translator')->get('label.WEBSITE'); ?>: </span><span><?php echo e(!empty($konitaInfo->website)?$konitaInfo->website:''); ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End of Header-->
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="inv-border-bottom"><?php echo app('translator')->get('label.RELATED_SALES_PERSON_LIST'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-6">
                    <?php echo app('translator')->get('label.BUYER'); ?>: <strong><?php echo $buyerInfo->name ?? __('label.N_A'); ?></strong>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.EMPLOYEE_ID'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.DEPARTMENT'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.BRANCH'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$relatedSalesPersonInfoArr->isEmpty()): ?>
                            <?php $sl = 0; ?>
                            <?php $__currentLoopData = $relatedSalesPersonInfoArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                <td class="text-center vcenter" width="50px">
                                    <?php if(!empty($item->photo) && File::exists('public/uploads/user/' . $item->photo)): ?>
                                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($item->photo); ?>" alt="<?php echo e($item->name); ?>"/>
                                    <?php else: ?>
                                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($item->name); ?>"/>
                                    <?php endif; ?>
                                </td>
                                <td class="vcenter"><?php echo $item->employee_id ?? ''; ?></td>
                                <td class="vcenter">
                                    <?php echo $item->name ?? ''; ?>

                                </td>
                                <td class="vcenter"><?php echo $item->designation ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->department ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->branch ?? ''; ?></td>
                                <td class="vcenter"><?php echo $item->phone ?? ''; ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td class="vcenter text-danger" colspan="8"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
        <table class="table borderless">
            <tr>
                <td class="no-border text-left ">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo e(Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name); ?>

                </td>
                <td class="no-border text-right">
                    <?php echo app('translator')->get('label.GENERATED_FROM_KTI'); ?>
                </td>
            </tr>
        </table>

        <!--//end of footer-->
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/salesPersonToBuyer/print/showRelatedSalesPerson.blade.php ENDPATH**/ ?>