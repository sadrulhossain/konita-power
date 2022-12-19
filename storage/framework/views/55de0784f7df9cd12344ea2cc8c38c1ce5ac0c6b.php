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
        <div class="row">
            <div class="col-md-12">
                <table class="table borderless">
                    <tr>
                        <td width='40%'>
                            <span> 
                                <?php if(Request::get('view') == 'pdf'): ?>
                                <img src="<?php echo base_path(); ?>/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/public/img/konita_small_logo.png" style="width: 280px; height: 80px;">
                                <?php endif; ?>

                            </span>
                        </td>
                        <td class="text-right font-size-11" width='60%'>
                            <span><?php echo e(!empty($konitaInfo->name)?$konitaInfo->name:''); ?></span><br/>
                            <span><?php echo e(!empty($konitaInfo->address)?$konitaInfo->address:''); ?></span><br/>
                            <span><?php echo app('translator')->get('label.PHONE'); ?>: </span><span><?php echo e(!empty($phoneNumber)?$phoneNumber:''); ?></span><br/>
                            <span><?php echo app('translator')->get('label.EMAIL'); ?>: </span><span><?php echo e(!empty($konitaInfo->email)?$konitaInfo->email.',':''); ?></span>
                            <span><?php echo app('translator')->get('label.WEBSITE'); ?>: </span><span><?php echo e(!empty($konitaInfo->website)?$konitaInfo->website:''); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <h4 class="text-center bold uppercase"><?php echo app('translator')->get('label.INVOICE'); ?></h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class=" col-md-12 margin-top-10 margin-bottom-10">
                                <div class="margin-bottom-20">
                                    <span><?php echo app('translator')->get('label.DATE'); ?>: <?php echo e(!empty($invoiceInfo->date)?Helper::formatdate($invoiceInfo->date):''); ?></span><br/>
                                    <span><?php echo app('translator')->get('label.INVOICE_NO'); ?>: <?php echo e(!empty($invoiceInfo->invoice_no)?$invoiceInfo->invoice_no:''); ?></span><br/>
                                </div>
                                <div>
                                    <span><?php echo app('translator')->get('label.ATTN'); ?>:</span><br/>
                                    <span><?php echo e(!empty($invoiceInfo->supplier_contact_person)?$invoiceInfo->supplier_contact_person:''); ?></span><br/>
                                    <span class="bold"><?php echo e(!empty($supplierInfo->name)?$supplierInfo->name:''); ?></span><br/>
                                    <span><?php echo e(!empty($supplierInfo->address)?$supplierInfo->address:''); ?></span><br/>
                                    <span><?php echo e(!empty($supplierInfo->countryName)?$supplierInfo->countryName:''); ?></span><br/>   
                                </div>
                                <div class="margin-top-20">
                                    <span><?php echo e(!empty($invoiceInfo->subject)?$invoiceInfo->subject:''); ?></span> 
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                                <th class="vcenter text-center"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                                <th class="vcenter text-center"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                                <!--<th class="vcenter text-center"><?php echo app('translator')->get('label.COMMISSION'); ?></th>-->
                                                <th class="vcenter text-center"><?php echo app('translator')->get('label.TOTAL'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($orderNoHistoryArr)): ?>
                                            <?php
                                            $sl = 0;
                                            ?>
                                            <?php $__currentLoopData = $orderNoHistoryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inqueryId=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="vcenter text-center"><?php echo e(++$sl); ?></td>  
                                                <td class="vcenter"><?php echo e(!empty($orderWiseBuyerList[$inqueryId])?$orderWiseBuyerList[$inqueryId]:''); ?></td>  
                                                <td class="vcenter text-center"><?php echo e(!empty($orderNoList[$inqueryId])?$orderNoList[$inqueryId]:''); ?></td> 
                                                <td class="vcenter text-right">
                                                    <?php echo e(!empty($item['qty'])?$item['qty']:null); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?>
                                                </td>  
<!--                                                <td class="vcenter text-right">
                                                    <span>$</span><?php echo e(!empty($item['konita_cmsn'])?$item['konita_cmsn']:0); ?>&nbsp;<span>/</span><?php echo app('translator')->get('label.UNIT'); ?>
                                                </td>  -->
                                                <td class="vcenter text-right">
                                                    <span>$</span><?php echo e(!empty($item['total_konita_cmsn'])?Helper::numberFormat2Digit($item['total_konita_cmsn']):Helper::numberFormat2Digit(0)); ?>  
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <!--sub_total-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.SUBTOTAL'); ?></td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span><?php echo e(!empty($invoiceInfo->sub_total)?Helper::numberFormat2Digit($invoiceInfo->sub_total):Helper::numberFormat2Digit(0)); ?>

                                                </td>
                                            </tr>
                                            <!--admon cost-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.ADMIN_COST'); ?></td>
                                                <td class="vcenter bold text-right">
                                                    <span>- $</span><?php echo e(!empty($invoiceInfo->admin_cost)?Helper::numberFormat2Digit($invoiceInfo->admin_cost):Helper::numberFormat2Digit(0)); ?>

                                                </td>
                                            </tr>
                                            <!--net_receivable-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span><?php echo e(!empty($invoiceInfo->net_receivable)?Helper::numberFormat2Digit($invoiceInfo->net_receivable):Helper::numberFormat2Digit(0)); ?>

                                                </td>
                                            </tr>
                                            <!--gift-->
                                            <tr>
                                                <?php
                                                $gift = '--';
                                                $textAlignment = 'center';
                                                if (($invoiceInfo->gift != 0.00) && (!empty($invoiceInfo->gift))) {
                                                    $gift = '$' . Helper::numberFormat2Digit($invoiceInfo->gift);
                                                    $textAlignment = 'right';
                                                }
                                                ?>
                                                <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.GIFT'); ?>&nbsp;<?php echo e(!empty($invoiceInfo->gift_title)?'('.$invoiceInfo->gift_title.')':''); ?></td>
                                                <td class="vcenter bold text-<?php echo e($textAlignment); ?>"><?php echo $gift; ?></td>
                                            </tr>
                                            <!--total amount-->
                                            <tr>
                                                <td class="vcenter text-right bold" colspan="4"><?php echo app('translator')->get('label.TOTAL_AMOUNT'); ?></td>
                                                <td class="vcenter bold  text-right">
                                                    <span>$</span><?php echo e(!empty($invoiceInfo->total_amount)?Helper::numberFormat2Digit($invoiceInfo->total_amount):Helper::numberFormat2Digit(0)); ?>

                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--koniat bank information-->
                <div class="row">
                    <div class="col-md-12 margin-bottom-10">
                        <div class="col-md-6">
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BANK_INFORMATION'); ?></span>
                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BANK_NAME'); ?>: </span><?php echo e(!empty($konitaBankInfo->bank_name)?$konitaBankInfo->bank_name:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.ACCOUNT_NO'); ?>: </span><?php echo e(!empty($konitaBankInfo->account_no)?$konitaBankInfo->account_no:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.ACCOUNT_NAME'); ?>: </span><?php echo e(!empty($konitaBankInfo->account_name)?$konitaBankInfo->account_name:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.BRANCH'); ?>: </span><?php echo e(!empty($konitaBankInfo->branch)?$konitaBankInfo->branch:''); ?>

                            </div>
                            <div>
                                <span class="bold"><?php echo app('translator')->get('label.SWIFT'); ?>: </span><?php echo e(!empty($konitaBankInfo->swift)?$konitaBankInfo->swift:''); ?>

                            </div>

                            <!--Signatory part-->
                            <!--                            <div class="margin-top-20">
                                                            <h5><?php echo app('translator')->get('label.REGARDS'); ?></h5>
                                                            <span>
                                                                <?php if(!empty($signatoryInfo->seal)): ?>
                                                                <?php if(Request::get('view') == 'pdf'): ?>
                                                                <img src="<?php echo base_path(); ?>/public/img/signatoryInfo/<?php echo e($signatoryInfo->seal); ?>" style="width:100px; height: 100px;">
                                                                <?php else: ?>
                                                                <img src="<?php echo e(URL::to('/')); ?>/public/img/signatoryInfo/<?php echo e($signatoryInfo->seal); ?>" style="width:100px; height: 100px;">          
                                                                <?php endif; ?>
                                                                <?php else: ?>
                                                                <?php if(Request::get('view') == 'pdf'): ?>
                                                                <img src="<?php echo base_path(); ?>/public/img/no_image.png" style="width:100px; height: 100px;">
                                                                <?php else: ?>
                                                                <img src="<?php echo e(URL::to('/')); ?>/public/img/no_image.png" style="width:100px; height: 100px;">
                                                                <?php endif; ?>
                                                                <?php endif; ?>
                                                            </span><br/>
                                                            <span><?php echo e(!empty($signatoryInfo->name)?$signatoryInfo->name:''); ?></span><br/> 
                                                            <span><?php echo e(!empty($signatoryInfo->designation)?$signatoryInfo->designation:''); ?></span>
                                                        </div>-->
                        </div>
                    </div>
                </div>
                <!--end of koniat bank information-->
            </div>
        </div>
        <table class="table borderless">
            <tr>
                <td class="no-border text-left "><?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo e(Helper::formatDate(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name); ?>

                </td>
            </tr>
            <tr>
                <td class="no-border text-left "><?php echo app('translator')->get('label.PRINT_FOOTER_TITLE_INVOICE'); ?></td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/print/index.blade.php ENDPATH**/ ?>