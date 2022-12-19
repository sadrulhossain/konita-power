@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-external-link-square"></i>@lang('label.ASSIGN_OPPORTUNITY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'opportunityToMemberRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="memberId">@lang('label.ASSIGN_TO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('member_id', $memberArr, Request::get('member_id'), ['class' => 'form-control js-source-states', 'id' => 'memberId']) !!}
                                <span class="text-danger">{{ $errors->first('member_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showOpportunities">
                            @if(!empty(Request::get('member_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="label label-success" >@lang('label.TOTAL_NUMBER_OF_OPPORTUNITIES'): {!! !$opportunitiesArr->isEmpty()?count($opportunitiesArr):0 !!}</span>
                                    @if(!empty($userAccessArr[70][5]))
                                    <button class='label label-primary tooltips' href="#modalRelatedOpportunity" id="relateOpportunity"  data-toggle="modal" title="@lang('label.SHOW_RELATED_OPPORTUNITIES')">
                                        @lang('label.OPPORTUNITY_RELATED_TO_THIS_MEMBER'): {!! !empty($opportunityRelatedToMember) ? count($opportunityRelatedToMember):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover relation-view">
                                            <thead>
                                                <tr class="active">
                                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                    <th class="text-center vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-opportunity-check']) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    <th class="vcenter text-center">@lang('label.BUYER')</th>
                                                    <th class="vcenter text-center">@lang('label.SOURCE')</th>
                                                    <th class="vcenter text-center">@lang('label.DATE_OF_CREATION')</th>
                                                    <th class="vcenter text-center">@lang('label.CREATED_BY')</th>
                                                    <th class="vcenter text-center">@lang('label.REMARKS')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!$opportunitiesArr->isEmpty())
                                                <?php
                                                $sl = 0;
                                                ?>
                                                @foreach($opportunitiesArr as $opportunity)
                                                <?php
                                                if ($opportunity->buyer_has_id == '0') {
                                                    $buyer = $opportunity->buyer;
                                                } elseif ($opportunity->buyer_has_id == '1') {
                                                    $buyer = !empty($buyerList[$opportunity->buyer]) && $opportunity->buyer != 0 ? $buyerList[$opportunity->buyer] : '';
                                                }
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($opportunityRelatedToMember) && array_key_exists($opportunity->id, $opportunityRelatedToMember)) {
                                                    $checked = 'checked';
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('opportunity['.$opportunity->id.']', $opportunity->id, $checked, ['id' => $opportunity->id, 'data-id'=> $opportunity->id,'class'=> 'md-check opportunity-check']) !!}
                                                            <label for="{!! $opportunity->id !!}">
                                                                <span class="inc tooltips" data-placement="right" title=""></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title=""></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title=""></span>
                                                            </label>
                                                        </div>

                                                    </td>
                                                    <td class="vcenter">{!! $buyer ?? '' !!}</td>
                                                    <td class="vcenter">{!! $opportunity->source ?? '' !!}</td>
                                                    <td class="vcenter text-center">{!! !empty($opportunity->created_at) ? Helper::formatDate($opportunity->created_at) : '' !!}</td>
                                                    <td class="vcenter">{!! $opportunity->opportunity_creator ?? '' !!}</td>
                                                    <td class="vcenter">{!! $opportunity->remarks ?? '' !!}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td class="vcenter text-danger" colspan="20">@lang('label.NO_OPPORTUNITY_FOUND')</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">

                                        @if(!empty($userAccessArr[70][7]))
                                        <button class="btn btn-circle green btn-submit" id="saveSupplierToProductRel" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[70][1]))
                                        <a href="{{ URL::to('/crmOpportunityToMember') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="modalRelatedOpportunity" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedOpportunity">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.tooltips').tooltip();

<?php
if (!empty($request->member_id)) {
    if (!$opportunitiesArr->isEmpty()) {
        ?>
                $('.relation-view').dataTable({
                    "language": {
                        "search": "Search Keywords : ",
                    },
                    "paging": true,
                    "info": true,
                    "order": false
                });

                if ($('.opportunity-check:checked').length == $('.opportunity-check').length) {
                    $('.all-opportunity-check').prop("checked", true);
                } else {
                    $('.all-opportunity-check').prop("checked", false);
                }
        <?php
    }
}
?>


        $(".opportunity-check").on("click", function () {
            if ($('.opportunity-check:checked').length == $('.opportunity-check').length) {
                $('.all-opportunity-check').prop("checked", true);
            } else {
                $('.all-opportunity-check').prop("checked", false);
            }
        });

        $(".all-opportunity-check").click(function () {
            if ($(this).prop('checked')) {
                $('.opportunity-check').prop("checked", true);
            } else {
                $('.opportunity-check').prop("checked", false);
            }

        });



        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };



        //START:: On change of Member show oppotunities to Relate
        $(document).on('change', '#memberId', function () {
            var memberId = $('#memberId').val();
            if (memberId == '0') {
                $('#showOpportunities').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("crmOpportunityToMember/getOpportunityToRelate/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    member_id: memberId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showOpportunities').html(res.html);
                    App.unblockUI();
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
                    App.unblockUI();
                }
            });
        });

        //insert Opportunity to Member
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var oTable = $('.relation-view').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#opportunityToMemberRelateForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
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
                            var form_data = new FormData($('#opportunityToMemberRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('crmOpportunityToMember/relateOpportunityToMember')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var memberId = $('#memberId').val();
                                    location = "crmOpportunityToMember?member_id=" + memberId;
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
                                }
                            });
                        }
                    });


        });


        //START:: Show Related Opportunities
        $(document).on("click", "#relateOpportunity", function (e) {
            e.preventDefault();
            var memberId = $("#memberId").val();
            $.ajax({
                url: "{{ URL::to('/crmOpportunityToMember/getRelatedOpportunities')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    member_id: memberId
                },
                success: function (res) {
                    $("#showRelatedOpportunity").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        //END:: Show Related Opportunities

    });
</script>
@stop