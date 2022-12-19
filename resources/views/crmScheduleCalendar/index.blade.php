@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CRM_ACTIVITY_SCHEDULE')
            </div>
        </div>
        <div class="portlet-body">
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div id="showCalendar" class="has-toolbar"> </div>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>
<script type="text/javascript">

    $(function () {
    $('#showCalendar').fullCalendar({



    header: {
    left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
    },
            defaultDate: "{{ date('Y-m-d') }}",
            navLinks: true,
            editable: true,
            eventLimit: false,
            eventRender: function(eventObj, $el) {
            $el.popover({
            title: eventObj.popTitle,
                    content: eventObj.description,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body',
                    html: true
            });
            },
            eventClick: function(calEvent, jsEvent, view, element) {
            var opportunityId = calEvent.opportunity;
                    var activityKey = calEvent.activityKey;
                    var doneColor = calEvent.scheduledone;
//            console.log(count);
//            console.log(view);
//            console.log(view.options);
//            console.log(view.options.events[count].color);
//            view.options.events[count].color
//            return false;
                    if (calEvent.scheduleStatus != 1) {
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
<?php
if (!empty($userAccessArr[80][17])) {
    ?>
                swal({
                title: "@lang('label.ARE_YOU_SURE_YOU_WANT_TO_MARK_THIS_SCHEDULE_AS_DONE')",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, Done',
                        cancelButtonText: 'No, Cancel',
                        closeOnConfirm: true,
                        closeOnCancel: true
                }, function (isConfirm) {
                if (isConfirm) {
                $.ajax({
                url: "{{ URL::to('crmScheduleCalendar/scheduleDone')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                        opportunity_id: opportunityId,
                                activity_key: activityKey
                        },
                        success: function (res) {
                        toastr.success(res.message, res.heading, options);
                                location.reload();
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
                        App.unblockUI();
                        }
                });
                }
                });
    <?php
}
?>
            }
            },
            events: [
                    @if (!empty($activityEventArr))
                    @foreach($activityEventArr as $key => $event)
            {
            title: "{!! $event['title'].'\n'.Helper::trimString($event['purpose'])  !!}",
                    start: "{!! $event['start_date'] !!}",
                    description:"{!! 'Buyer : '.$opportunityArr[$event['opportunity_id']]. '<br/>Schedule Created By : ' . $event['schedule_creator'] . '<br/>Status : <span class=\"label label-sm bold label-' .$event['color'].' \">' . $event['status'] .'</span><br />Schedule Date/Time : '.Helper::formatDateTime($event['start_date']).'<br />Schedule Purpose : '.$event['purpose'] !!}",
                    popTitle: "{!! 'Date: '.$event['title'] !!}",
                    color: "{!! !empty($event['schedule_status']) && $event['schedule_status'] == 1 ? '#1BA39C' : '#525E64' !!}",
                    opportunity: "{!! $event['opportunity_id'] !!}",
                    activityKey: "{!! $key !!}",
                    scheduleStatus: "{!! $event['schedule_status'] !!}",
                    scheduledone: "{!! $event['schedule_done_color'] !!}",
            },
                    @endforeach
                    @endif
            ]
    });
    }
    );
</script>
@stop