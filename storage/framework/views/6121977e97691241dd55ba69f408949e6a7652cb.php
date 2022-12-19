<html>
    <head>
        <title><?php echo app('translator')->get('label.KTI_SALES_MANAGEMENT_TRACKING_SYSTEM'); ?></title>
        <?php if(Request::get('view') == 'print'): ?>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')); ?>" rel="stylesheet" type="text/css" /> 
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')); ?>" rel="stylesheet" type="text/css" /> 
        <style type="text/css" media="print">
            @page  { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>
        <?php elseif(Request::get('view') == 'pdf'): ?>
        <link rel="shortcut icon" href="<?php echo base_path(); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'); ?>" rel="stylesheet" type="text/css"/> 
        <?php endif; ?>
    </head>
    <body>
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
                <h4 class="text-center bold uppercase"><?php echo app('translator')->get('label.FULL_INVOICE'); ?></h4>
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
                                                <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.ORDER_NO'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.BUYER'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.BL_NO'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.PRODUCT'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.BRAND'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.GSM'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.QUANTITY'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.COMMISSION'); ?></th>
                                                <th class="vcenter"><?php echo app('translator')->get('label.TOTAL_COMMISSION'); ?></th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php if(!empty($billingArr)): ?>
                                            <?php
                                            $sl = 0;
                                            ?>
                                            <?php $__currentLoopData = $billingArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiryId=>$target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $rowspan = !empty($rowspanOrder[$inquiryId]) ? $rowspanOrder[$inquiryId] : 0;
                                            ?>
                                        <td class="text-center vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo ++$sl; ?></td>
                                        <td class="vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo e($target['order_no']); ?></td>
                                        <td class="vcenter" rowspan="<?php echo e($rowspan); ?>"><?php echo e($target['buyer_name']); ?></td>

                                        <?php
                                        $i = 0;
                                        ?>
                                        <?php $__currentLoopData = $billingArr2[$inquiryId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryId=> $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php
                                        if ($i > 0) {
                                            echo '<tr>';
                                        }

                                        $rowspanBl = !empty($rowspanArr[$inquiryId][$deliveryId]) ? $rowspanArr[$inquiryId][$deliveryId] : 0;
                                        $blNo = wordwrap(!empty($item['bl_no']) ? $item['bl_no'] : '', 8, "\n", true);
                                        ?>
                                        <td class="text-center vcenter" rowspan="<?php echo e($rowspanBl); ?>">
                                            <?php echo e($blNo); ?> 
                                        </td>
                                        <?php
                                        $j = 0;
                                        ?>
                                        <?php $__currentLoopData = $item['bl_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryDetailsId=>$deliveryDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php
                                        if ($j > 0) {
                                            echo '<tr>';
                                        }
                                        $totalKonitaComsn = !empty($shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn']) ? $shipmentComsnArr[$deliveryDetailsId]['total_konita_cmsn'] : 0;
                                        $konitaCmsn = (!empty($prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn']) ? $prevComsnArr[$inquiryId][$deliveryDetails['inquiry_details_id']]['konita_cmsn'] : (!empty($prevComsnArr[$inquiryId][0]['konita_cmsn']) ? $prevComsnArr[$inquiryId][0]['konita_cmsn'] : 0));
                                        ?>
                                        <td>
                                            <?php echo e(!empty($deliveryDetails['product_name'])?$deliveryDetails['product_name']:''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(!empty($deliveryDetails['brand_name'])?$deliveryDetails['brand_name']:''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(!empty($deliveryDetails['grade_name'])?$deliveryDetails['grade_name']:''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(!empty($deliveryDetails['gsm'])?$deliveryDetails['gsm']:''); ?>

                                        </td>
                                        <td class="text-right vcenter">
                                            <?php echo e(!empty($deliveryDetails['shipment_qty'])?$deliveryDetails['shipment_qty']:''); ?>&nbsp;<?php echo app('translator')->get('label.UNIT'); ?>

                                        </td>

                                        <td class="text-right vcenter">
                                            $<?php echo e($konitaCmsn); ?> 
                                        </td>

                                        <td class="text-right vcenter">
                                            $<?php echo e($totalKonitaComsn); ?>

                                        </td>
                                        <?php
                                        $j++;
                                        ?>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $i++;
                                        ?>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <!--sub_total-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.SUBTOTAL'); ?></td>
                                            <td class="vcenter bold  text-right">
                                                <span>$</span><?php echo e(!empty($invoiceInfo->sub_total)?Helper::numberFormat2Digit($invoiceInfo->sub_total):Helper::numberFormat2Digit(0)); ?>

                                            </td>
                                        </tr>
                                        <!--admon cost-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.ADMIN_COST'); ?></td>
                                            <td class="vcenter bold text-right">
                                                <span>- $</span><?php echo e(!empty($invoiceInfo->admin_cost)?Helper::numberFormat2Digit($invoiceInfo->admin_cost):Helper::numberFormat2Digit(0)); ?>

                                            </td>
                                        </tr>
                                        <!--net_receivable-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.NET_RECEIVABLE'); ?></td>
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
                                            <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.GIFT'); ?>&nbsp;<?php echo e(!empty($invoiceInfo->gift_title)?'('.$invoiceInfo->gift_title.')':''); ?></td>
                                            <td class="vcenter bold text-<?php echo e($textAlignment); ?>"><?php echo $gift; ?></td>
                                        </tr>
                                        <!--total amount-->
                                        <tr>
                                            <td class="vcenter text-right bold" colspan="10"><?php echo app('translator')->get('label.TOTAL_AMOUNT'); ?></td>
                                            <td class="vcenter bold  text-right">
                                                <span>$</span><?php echo e(!empty($invoiceInfo->total_amount)?Helper::numberFormat2Digit($invoiceInfo->total_amount):Helper::numberFormat2Digit(0)); ?>

                                            </td>
                                        </tr>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="18"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
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
</html><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/billing/print/fullInvoice.blade.php ENDPATH**/ ?>