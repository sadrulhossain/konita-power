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
            <div class="row margin-bottom-20">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="inv-border-bottom font-size-11 header"><?php echo app('translator')->get('label.SALES_SUMMARY_REPORT'); ?>
                            <?php
                            echo ' (' . Helper::formatDate($fromDate) . ' - ' . Helper::formatDate($toDate) . ')';
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                        <thead>
                            <tr>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SUPPLIER'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SALES_VOLUME'); ?></th>
                                <th class="text-center vcenter"><?php echo app('translator')->get('label.NET_INCOME'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($supplierList)): ?>
                            <?php
                            $sl = 0;
                            ?>
                            <?php $__currentLoopData = $supplierList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplierId => $supplierName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                <td class="vcenter"><?php echo $supplierName; ?></td>
                                <td class="text-right vcenter"><?php echo (!empty($salesSummaryArr[$supplierId]['volume']) ? Helper::numberFormat2Digit($salesSummaryArr[$supplierId]['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT'); ?></td>
                                <td class="text-right vcenter"><?php echo '$' . (!empty($salesSummaryArr[$supplierId]['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr[$supplierId]['net_income']) : Helper::numberFormat2Digit(0)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th class="text-right vcenter" colspan="2"><?php echo app('translator')->get('label.TOTAL'); ?></th>
                                <th class="text-right vcenter"><?php echo (!empty($salesSummaryArr['total']['volume']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['volume']) : Helper::numberFormat2Digit(0)) . ' ' . __('label.UNIT'); ?></th>
                                <th class="text-right vcenter"><?php echo '$' . (!empty($salesSummaryArr['total']['net_income']) ? Helper::numberFormat2Digit($salesSummaryArr['total']['net_income']) : Helper::numberFormat2Digit(0)); ?></th>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td class="vcenter text-danger" colspan="4"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
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
                <td class="no-border text-left font-size-11">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo e(Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name); ?>.
                </td>
                <td class="no-border text-right font-size-11">
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
</html><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/report/supplierWiseSalesSummary/print/index.blade.php ENDPATH**/ ?>