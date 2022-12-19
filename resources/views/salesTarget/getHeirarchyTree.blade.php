@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-sitemap"></i>@lang('label.HEIRARCHY_WISE_SALES_TARGET_INFO_TREE')
                &nbsp;|&nbsp;{{ date('F Y') }}
            </div>
            <div class="actions">
                <a href="{{ URL::to('salesTarget') }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="userHierarchyTree" id="userHierarchyTree"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Treant JS --> 
<script src="{{asset('public/js/treant-js-master/vendor/raphael.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/treant-js-master/Treant.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/treant-js-master/vendor/perfect-scrollbar/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/treant-js-master/vendor/jquery.mousewheel.js')}}" type="text/javascript"></script>

<script type="text/javascript">

$(function () {
    $(".tooltips").tooltip();

    var config = {
        container: "#userHierarchyTree",
        rootOrientation: "NORTH",
        nodeAlign: "CENTER",

        scrollbar: "fancy",
        connectors: {
            type: 'step',
            style: {
                stroke: "#525E64",
                'stroke-width': 2,
            }
        },
        node: {
            HTMLclass: 'nodeExample1',
//            drawLineThrough: true,
            collapsable: true,
        }
    };

    var img_<?php echo Auth::user()->id; ?> = "{{URL::to('/')}}/public/img/unknown.png";
<?php
if (!empty($userArr[Auth::user()->id]['photo']) && File::exists('public/uploads/user/' . $userArr[Auth::user()->id]['photo'])) {
    ?>
        img_<?php echo Auth::user()->id; ?> = "{{URL::to('/')}}/public/uploads/user/{{$userArr[Auth::user()->id]['photo']}}";
    <?php
}
?>
    var user_<?php echo Auth::user()->id; ?> = {
        HTMLclass: 'gray-mint',
        text: {
            name: "<?php echo $userArr[Auth::user()->id]['name'] ?? ''; ?>",
            title: "<?php echo ($userArr[Auth::user()->id]['designation'] ?? ''); ?>",
            target: "<?php echo ' ' . (!empty($salesTarget[Auth::user()->id]) ? Helper::numberFormatDigit2($salesTarget[Auth::user()->id]) : Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT'); ?>",
            achievement: "<?php echo ' ' . (!empty($salesAchievement[Auth::user()->id]) ? Helper::numberFormatDigit2($salesAchievement[Auth::user()->id]) : Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT'); ?>",
        },
        image: img_<?php echo Auth::user()->id; ?>
    };

<?php
if (!empty($hierarchyArr)) {
    foreach ($hierarchyArr as $supervisorId => $user) {
        foreach ($user as $userId) {
            ?>
                var img_<?php echo $userId; ?> = "{{URL::to('/')}}/public/img/unknown.png";
            <?php
            if (!empty($userArr[$userId]['photo']) && File::exists('public/uploads/user/' . $userArr[$userId]['photo'])) {
                ?>
                    img_<?php echo $userId; ?> = "{{URL::to('/')}}/public/uploads/user/{{$userArr[$userId]['photo']}}";
                <?php
            }
            ?>
                var user_<?php echo $userId; ?> = {
                    parent: user_<?php echo $supervisorId; ?>,
                    HTMLclass: 'gray-mint',
                    text: {
                        name: "<?php echo $userArr[$userId]['name'] ?? ''; ?>",
                        title: "<?php echo ($userArr[$userId]['designation'] ?? ''); ?>",
                        target: "<?php echo ' ' . (!empty($salesTarget[$userId]) ? Helper::numberFormatDigit2($salesTarget[$userId]) : Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT'); ?>",
                        achievement: "<?php echo ' ' . (!empty($salesAchievement[$userId]) ? Helper::numberFormatDigit2($salesAchievement[$userId]) : Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT'); ?>",
                    },
                    image: img_<?php echo $userId; ?>
                };

            <?php
        }
    }
}
?>

    var chart_config = [
        config, user_<?php echo Auth::user()->id; ?>,
<?php
if (!empty($hierarchyArr)) {
    foreach ($hierarchyArr as $supervisorId => $user) {
        foreach ($user as $userId) {
            echo 'user_' . $userId . ', ';
        }
    }
}
?>
    ];


    var userHierarchyTree = new Treant(chart_config);

    //end tree

    $(".collapse-switch").append("<i class='collapse-switch-i fa fa-minus'></i>");
    $(document).on("click", ".collapse-switch", function () {
        if ($(this).parent().hasClass("collapsed")) {
            $(".collapse-switch").children().removeClass("fa-minus").addClass("fa-plus");
        } else {
            $(".collapse-switch").children().removeClass("fa-plus").addClass("fa-minus");
        }
    });
    
    $(".node-target").prepend("<i class='fa fa-bullseye tooltips' title='Target'></i>");
    $(".node-achievement").prepend("<i class='fa fa-trophy tooltips' title='Achievement'></i>");
//    if($(".nodeExample1").hasClass("collapsed")){
//        $(this + " .collapse-switch").append("<i class='collapse-switch-i fa fa-plus'></i>");
//    }else{
//        $(this + " .collapse-switch").append("<i class='collapse-switch-i fa fa-minus'></i>");
//    }

});




</script>
@stop