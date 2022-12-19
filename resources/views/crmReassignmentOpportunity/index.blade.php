@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-external-link-square"></i>@lang('label.REASSIGN_OPPORTUNITY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'opportunityToReassignMemberForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="memberId">@lang('label.MEMBERS') :<span class="text-danger"> *</span></label>
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
                           
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<!-- START:: Modal Related to Transfer Opporunities to another Memner -->
<div class="modal fade" id="modalTransferOpportunityToMember" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showTransferOpportunityToMember">
        </div>
    </div>
</div>

<!-- END:: Modal Related to Transfer Opporunities to another Memner -->

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
                url: '{{URL::to("crmReassignmentOpportunity/getOpportunityToRelate/")}}',
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

        //START:: Ajax Transfer Member to Opportunity
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();

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
                            var form_data = new FormData($('#transferOpportunityForm')[0]);
                            $.ajax({
                                url: "{{URL::to('crmReassignmentOpportunity/relateMemberToOpportunity')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    $('.btn-submit').prop('disabled', true);
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    setTimeout(location.reload(), 1000);
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

                                    $('.btn-submit').prop('disabled', false);
                                }
                            });
                        }
                    });
        });
         //END:: Ajax Transfer Member to Opportunity
        
        
        //preview submit form function
        $(document).on("click", "#getMemberToTransfer", function (e) {
            e.preventDefault();

            // Serialize the form data
            var form_data = new FormData($('#opportunityToReassignMemberForm')[0]);
            $.ajax({
                url: "{{URL::to('crmReassignmentOpportunity/getMemberToTransfer')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#showTransferOpportunityToMember").html(res.html);
                    $(".tooltips").tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showTransferOpportunityToMember')});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });


        });
        //endof preview form

    });
</script>
@stop