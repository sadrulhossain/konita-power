<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center bold">
            @lang('label.ASSIGN_SALES_PERSON')
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @lang('label.BUYER'): <strong>{!! $buyerInfo->name ?? __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-30">
            <div class="col-md-12 text-center">
                <span class="bold assign-condition"></span>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                {!! Form::hidden('buyer_id', $request->buyer_id) !!}
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">
                                    <div class="md-checkbox has-success">
                                        {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
                                        <label for="checkAll">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                        &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                    </div>
                                </th>
                                <th class="text-center vcenter">@lang('label.PHOTO')</th>
                                <th class="text-center vcenter">@lang('label.EMPLOYEE_ID')</th>
                                <th class="text-center vcenter">@lang('label.NAME')</th>
                                <th class="text-center vcenter">@lang('label.DESIGNATION')</th>
                                <th class="text-center vcenter">@lang('label.DEPARTMENT')</th>
                                <th class="text-center vcenter">@lang('label.BRANCH')</th>
                                <th class="text-center vcenter">@lang('label.PHONE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$salesPersonInfoArr->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($salesPersonInfoArr as $item)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">
                                    <div class="md-checkbox has-success">
                                        {!! Form::checkbox('sales_person['.$item->id.']', $item->id, false, ['id' => $item->id, 'data-id'=> $item->id,'class'=> 'md-check sp-check']) !!}
                                        <label for="{!! $item->id !!}">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center vcenter" width="40px">
                                    @if(!empty($item->photo) && File::exists('public/uploads/user/' . $item->photo))
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$item->photo}}" alt="{{ $item->name}}"/>
                                    @else
                                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $item->name}}"/>
                                    @endif
                                </td>
                                <td class="vcenter">{!! $item->employee_id ?? '' !!}</td>
                                <td class="vcenter">{!! $item->name ?? '' !!}</td>
                                <td class="vcenter">{!! $item->designation ?? '' !!}</td>
                                <td class="vcenter">{!! $item->department ?? '' !!}</td>
                                <td class="vcenter">{!! $item->branch ?? '' !!}</td>
                                <td class="vcenter">{!! $item->phone ?? '' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter text-danger" colspan="9">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!$salesPersonInfoArr->isEmpty())
        <button class="btn green set-assign-sales-person" id="setAssignSalesPerson" type="button">
            <i class="fa fa-check"></i> @lang('label.ASSIGN')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

    $('span.assign-condition').text("(Please, select at least one sales person. You can select at most 2 sales persons.)");
    $('span.assign-condition').css("color", "green");
<?php if (!$salesPersonInfoArr->isEmpty()) { ?>
        $('.relation-view').dataTable({
            "language": {
                "search": "Search Keywords : ",
            },
            "paging": true,
            "info": true,
            "order": false
        });
<?php } ?>

    $(".sp-check").on("click", function () {
        if ($('.sp-check:checked').length == $('.sp-check').length) {
            $('.all-check').prop("checked", true);
        } else {
            $('.all-check').prop("checked", false);
        }

        if ($('.sp-check:checked').length == 0) {
            $('span.assign-condition').text("(Please, select at least one sales person. You can select at most 2 sales persons.)");
            $('span.assign-condition').css("color", "green");
        } else if ($('.sp-check:checked').length == 1) {
            $('span.assign-condition').text("(You have selected one sales person. You can select one more.)");
            $('span.assign-condition').css("color", "green");
        } else if ($('.sp-check:checked').length == 2) {
            $('span.assign-condition').text("(You have selected 2 sales persons. You cannot select anymore.)");
            $('span.assign-condition').css("color", "green");
        } else if ($('.sp-check:checked').length > 2) {
            $('span.assign-condition').text("(You have selected more than 2 sales persons.)");
            $('span.assign-condition').css("color", "red");
        }
    });
    $(".all-check").click(function () {
        if ($(this).prop('checked')) {
            $('.sp-check').prop("checked", true);
            $('span.assign-condition').text("(You have selected more than 2 sales persons.)");
            $('span.assign-condition').css("color", "red");
        } else {
            $('.sp-check').prop("checked", false);
            if ($('.sp-check:checked').length == 0) {
                $('span.assign-condition').text("(Please, select at least one sales person. You can select at most 2 sales persons.)");
                $('span.assign-condition').css("color", "green");
            } else if ($('.sp-check:checked').length == 1) {
                $('span.assign-condition').text("(You have selected one sales person. You can select one more.)");
                $('span.assign-condition').css("color", "green");
            } else if ($('.sp-check:checked').length == 2) {
                $('span.assign-condition').text("(You have selected 2 sales persons. You cannot select anymore.)");
                $('span.assign-condition').css("color", "green");
            } else if ($('.sp-check:checked').length > 2) {
                $('span.assign-condition').text("(You have selected more than 2 sales persons.)");
                $('span.assign-condition').css("color", "red");
            }
        }



    });
    if ($('.sp-check:checked').length == $('.sp-check').length) {
        $('.all-check').prop("checked", true);
    } else {
        $('.all-check').prop("checked", false);
    }

    if ($('.sp-check:checked').length == 0) {
        $('span.assign-condition').text("(Please, select at least one sales person. You can select at most 2 sales persons.)");
        $('span.assign-condition').css("color", "green");
    } else if ($('.sp-check:checked').length == 1) {
        $('span.assign-condition').text("(You have selected one sales person. You can select one more.)");
        $('span.assign-condition').css("color", "green");
    } else if ($('.sp-check:checked').length == 2) {
        $('span.assign-condition').text("(You have selected 2 sales persons. You cannot select anymore.)");
        $('span.assign-condition').css("color", "green");
    } else if ($('.sp-check:checked').length > 2) {
        $('span.assign-condition').text("(You have selected more than 2 sales persons.)");
        $('span.assign-condition').css("color", "red");
    }
    
    

});
</script>