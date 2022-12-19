<?php echo $__env->make('layouts.default.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body id="addFullMenuClass" class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
    <div class="page-wrapper">
        <?php echo $__env->make('layouts.default.topNavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="clearfix"> </div>
        <div class="page-container">
            <?php echo $__env->make('layouts.default.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="page-content-wrapper">
                <?php
                $dashboardContent = '';
                if ($controllerName == 'dashboard') {
                    $dashboardContent = 'dashboard-content';
                }
                ?>
                <div class="page-content <?php echo e($dashboardContent); ?>">
                    <?php echo $__env->yieldContent('data_count'); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        <?php echo $__env->make('layouts.default.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="quick-nav-overlay"></div>
    <?php echo $__env->make('layouts.default.footerScript', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH E:\xampp_7_4_15\htdocs\konitaPower\resources\views/layouts/default/master.blade.php ENDPATH**/ ?>