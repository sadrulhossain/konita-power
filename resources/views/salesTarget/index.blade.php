@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-wrench"></i>@lang('label.SET_SALES_TARGET')
            </div>
            <div class="actions">
                <a class="btn purple-wisteria btn-sm tooltips" href="{{ URL::to('salesTarget/getHeirarchyTree') }}" data-placement="left" title="@lang('label.CLICK_TO_SEE_HEIRARCHY_TREE')">
                    <i class="fa fa-sitemap"></i>
                </a>
                <button class="btn green-seagreen btn-sm btn-grid-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_GRID_VIEW')">
                    <i class="fa fa-th"></i>
                </button>
                <button class="btn green-seagreen btn-sm btn-tabular-view tooltips" type="button" data-placement="left" title="@lang('label.CLICK_TO_SEE_TABULAR_VIEW')">
                    <i class="fa fa-list"></i>
                </button>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row grid-view">
                @if(!$salesPersonArr->isEmpty())
                @foreach($salesPersonArr as $salesPerson)
                <div class="col-md-2 col-lg-2 col-sm-3 col-xs-6">
                    <div class="thumbnail margin-bottom-15">
                        @if(!empty($salesPerson->photo) && File::exists('public/uploads/user/'.$salesPerson->photo))
                        <img class="tooltips fixed-height-152" data-placement="bottom" data-html="true" title="
                             <div class='text-left'>
                             @lang('label.NAME'): <strong>{!! $salesPerson->full_name ?? __('label.N_A') !!}</strong><br/>
                             @lang('label.DESIGNATION'): <strong>{!! $salesPerson->designation ?? __('label.N_A') !!}</strong><br/>
                             @lang('label.SALES_TARGET'): <strong>{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong><br/>
                             @lang('label.SALES_ACHIEVEMENT'): <strong>{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong></div>" 
                             width="152" height="152" src="{{URL::to('/')}}/public/uploads/user/{{$salesPerson->photo}}" alt="{{ $salesPerson->full_name}}"/>
                        @else
                        <img class="tooltips fixed-height-152" data-placement="bottom" data-html="true" title="
                             <div class='text-left'>
                             @lang('label.NAME'): <strong>{!! $salesPerson->full_name ?? __('label.N_A') !!}</strong><br/>
                             @lang('label.DESIGNATION'): <strong>{!! $salesPerson->designation ?? __('label.N_A') !!}</strong><br/>
                             @lang('label.SALES_TARGET'): <strong>{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong><br/>
                             @lang('label.SALES_ACHIEVEMENT'): <strong>{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</strong></div>" 
                             width="152" height="152" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $salesPerson->full_name}}"/>
                        @endif
                        <div class="caption">
                            @if(!empty($userAccessArr[20][7]) && !empty($userAccessArr[20][5]))
                            <div class="row text-center">
                                <!--<div class="col-md-6">-->
                                <button class="btn btn-sm btn-padding purple-sharp tooltips set-sales-target" data-view-id="1" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                                    <i class="fa fa-calculator"></i>
                                </button>
                                <!--                                </div>
                                                                <div class="col-md-6">-->
                                <button class="btn btn-sm btn-padding grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                                    <i class="fa fa-bars"></i>
                                </button>
                                <!--</div>-->
                            </div>
                            @elseif(empty($userAccessArr[20][7]) && !empty($userAccessArr[20][5]))
                            <button class="btn btn-sm btn-block grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                                <i class="fa fa-bars"></i>&nbsp;@lang('label.VIEW_TARGET')
                            </button>
                            @elseif(!empty($userAccessArr[20][7]) && empty($userAccessArr[20][5]))
                            <button class="btn btn-sm btn-block purple-sharp tooltips set-sales-target" data-view-id="1" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                                <i class="fa fa-calculator"></i>&nbsp;@lang('label.SET_TARGET')
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-md-12 text-center">
                    <span class="label label-danger">@lang('label.NO_SALES_PERSON_FOUND'). @lang('label.PLEASE_ADD_AT_LEAST_ONE_SALES_PERSON')</span>
                </div>
                @endif
            </div>
            <div class="row tabular-view">
                <div class="table-responsive col-md-12 webkit-scrollbar">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead>
                            <tr class="info">
                                <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th  class="text-center vcenter">@lang('label.PHOTO')</th>
                                <th  class="vcenter">@lang('label.SALES_PERSON')</th>
                                <th  class="text-center vcenter">@lang('label.SALES_TARGET')</th>
                                <th class="text-center vcenter">@lang('label.SALES_ACHIEVEMENT')</th>
                                <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if(!$salesPersonArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($salesPersonArr as $salesPerson)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="text-center vcenter">
                                    @if(!empty($salesPerson->photo) && File::exists('public/uploads/user/'.$salesPerson->photo))
                                    <img width="50" height="50" src="{{URL::to('/')}}/public/uploads/user/{{$salesPerson->photo}}" alt="{{ $salesPerson->full_name}}"/>
                                    @else
                                    <img width="50" height="50" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $salesPerson->full_name}}"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $salesPerson->full_name !!}</td>
                                <td class="text-right vcenter">{!! (!empty($salesTarget[$salesPerson->id])?Helper::numberFormatDigit2($salesTarget[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</td>
                                <td class="text-right vcenter">{!! (!empty($salesAchievement[$salesPerson->id])?Helper::numberFormatDigit2($salesAchievement[$salesPerson->id]):Helper::numberFormatDigit2(0)) . ' ' . __('label.UNIT') !!}</td>
                                <td class="td-actions text-center vcenter">
                                    <div>
                                        @if(!empty($userAccessArr[20][7]))
                                        <button class="btn btn-xs purple-sharp tooltips set-sales-target"  data-view-id="2" data-id="{!! $salesPerson->id !!}" href="#modalSetSalesTarget"  data-toggle="modal" data-placement="top" title="@lang('label.CLICK_HERE_TO_SET_SALES_TARGET')"> 
                                            <i class="fa fa-calculator"></i>
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[20][5]))
                                        <button class="btn btn-xs grey-mint tooltips view-sales-target" data-id="{!! $salesPerson->id !!}" href="#modalViewSalesTarget"  data-toggle="modal" data-placement="top" title="@lang('label.CLICK_HERE_TO_VIEW_SALES_TARGET')"> 
                                            <i class="fa fa-bars"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="vcenter">@lang('label.NO_SALES_PERSON_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Set Target Modal start -->
<div class="modal fade" id="modalSetSalesTarget" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetSalesTarget">
        </div>
    </div>
</div>
<!-- Modal end-->

<!-- View Target Modal start -->
<div class="modal fade" id="modalViewSalesTarget" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSalesTargetDetail">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">

    $(function () {
        $(".tooltips").tooltip({
            classes: {
                "ui-tooltip": "tooltip-top-minus-60"
            }
        });

<?php if (!$salesPersonArr->isEmpty()) { ?>
            $("#dataTable").dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "attr": {
                    "class": "form-control"
                }
            });
<?php } ?>

        //initially tabular grid view and show grid view
        $(".btn-grid-view").hide();
        $(".btn-tabular-view").show();
        $(".grid-view").show();
        $(".tabular-view").hide();

        //show tabular view with tabular view btn
        $(".btn-tabular-view").on("click", function () {
            $(".btn-grid-view").show();
            $(".btn-tabular-view").hide();
            $(".grid-view").hide(1000);
            $(".tabular-view").show(1000);
        });

        //show grid view with grid view btn
        $(".btn-grid-view").on("click", function () {
            $(".btn-grid-view").hide();
            $(".btn-tabular-view").show();
            $(".grid-view").show(1000);
            $(".tabular-view").hide(1000);
        });

        $(document).on("click", ".set-sales-target", function (e) {
            e.preventDefault();
            var salesPersonId = $(this).attr("data-id");
            var viewId = $(this).attr("data-view-id");
            $.ajax({
                url: "{{ URL::to('/salesTarget/showSalesTarget')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId,
                    view_id: viewId,
                },
                success: function (res) {
                    $("#showSetSalesTarget").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        $(document).on("click", ".view-sales-target", function (e) {
            e.preventDefault();
            var salesPersonId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/salesTarget/showSalesTargetDetail')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId
                },
                success: function (res) {
                    $("#showSalesTargetDetail").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //save sales target
        $(document).on("click", "#saveSalesTarget", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };

                            // Serialize the form data
                            var form_data = new FormData($('#saveSalesTargetFrom')[0]);
                            $.ajax({
                                url: "{{URL::to('salesTarget/setSalesTarget')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    $('#saveSalesTarget').prop('disabled', true);
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    $(".modal.fade").modal('hide');
                                    var viewType = res.view;
                                    reloadView(viewType);
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                    $('#saveSalesTarget').prop('disabled', false);
                                    App.unblockUI();
                                }
                            });
                        }
                    });


        });

        //save sales target
        $(document).on("click", "#lockSalesTarget", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save & Lock',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };

                            // Serialize the form data
                            var form_data = new FormData($('#saveSalesTargetFrom')[0]);
                            $.ajax({
                                url: "{{URL::to('salesTarget/lockSalesTarget')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    $('#lockSalesTarget').prop('disabled', true);
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    $(".modal.fade").modal('hide');
                                    var viewType = res.view;
                                    reloadView(viewType);
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                                    } else if (jqXhr.status == 401) {
                                        toastr.error(jqXhr.responseJSON.message, '', options);
                                    } else {
                                        toastr.error('Error', 'Something went wrong', options);
                                    }
                                    $('#lockSalesTarget').prop('disabled', false);
                                    App.unblockUI();
                                }
                            });
                        }
                    });


        });

        $(document).on("change", "#effectiveMonth", function () {
            var effectiveMonth = $(this).val();
            var salesPersonId = $("#salesPersonId").val();
            $.ajax({
                url: '{{URL::to("salesTarget/getSalesTarget/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId,
                    effective_month: effectiveMonth,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#getSalesTarget').html(res.html);
                    $('#setSubmitLockbtn').html(res.setsubmitLock);
                    App.unblockUI();
                },
            });
        });

        $(document).on("change", "#effectiveMonthForDetail", function () {
            var effectiveMonth = $(this).val();
            var salesPersonId = $("#salesPersonId").val();
            //alert(effectiveMonth);return false;
            $.ajax({
                url: '{{URL::to("salesTarget/getSalesTargetDetail/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_person_id: salesPersonId,
                    effective_month: effectiveMonth,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#getSalesTargetDetail').html(res.html);
                    App.unblockUI();
                },
            });
        });

        function reloadView(viewType) {
            $.ajax({
                url: "{{URL::to('salesTarget/reloadView')}}",
                type: "GET",
                dataType: 'json',
                success: function (view) {
                    $(".grid-view").html(view.gridView);
                    $(".tabular-view").html(view.tabularView);
                    if (viewType == 1) {
                        $(".btn-grid-view").hide();
                        $(".btn-tabular-view").show();
                        $(".grid-view").show();
                        $(".tabular-view").hide();
                    } else if (viewType == 2) {
                        $(".btn-grid-view").show();
                        $(".btn-tabular-view").hide();
                        $(".grid-view").hide();
                        $(".tabular-view").show();
                    }
                }
            });
        }
    });
</script>
@stop