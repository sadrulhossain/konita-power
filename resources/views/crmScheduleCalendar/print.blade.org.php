<!DOCTYPE html>

<html lang="en">
    <head>
        <!--meta http-equiv="Content-Type" content="text/html; charset=UTF-8"-->
        <link rel="stylesheet" href="{{asset('public/css/calendar.base.css')}}">
        <!--link rel="stylesheet" href="{{asset('public/css/calendar.font.css')}}"-->
        <link rel="stylesheet" href="{{asset('public/assets/global/plugins/fullcalendar/fullcalendar.min.css')}}">
        
        <style>

            .calendar-example {
                line-height: 1.3;
            }

            .calendar-example h2 {
                font-size: 22px;
            }

            .calendar-example a[data-goto] {
                color: #444;
            }

        </style>
        <script src="{{asset('public/assets/global/plugins/moment.min.js')}}"></script>
        <script src="{{asset('public/assets/global/plugins/jquery.min.js')}}"></script>
        <script src="{{asset('public/assets/global/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
        <!--script src="./FullCalendar - JavaScript Event Calendar_files/index.js"></script-->
    </head>

    <body>
        
        <div class="page-content">
            <div class="page-content__container container">
                <div class="sidebar-layout" style="padding-top:1em">
                    <div class="sidebar-layout__main" style="font-size:14px">
                        
                        @for($i=1;$i<=12;$i++)
                        <div id="calendar-{{$i}}" class="calendar-example fc fc-unthemed fc-ltr">
                        </div>
                        <br /><br /><br /><br /><br /><br /><br /><br />
                        @endfor
                        
                    </div>
                </div>
            </div>
        </div>
        
        <style type="text/css">
            @media print {
            table tbody tr td a div span:after {
              color: #000 !important;
              background: transparent !important;
            }
        </style>
        
        <script type="text/javascript">

        $(function () {

            @for($i=1;$i<=12;$i++)

            $('#calendar-{{$i}}').fullCalendar({

                defaultDate: "{{ $trgYrInfo->year.'-'.$i.'-01' }}",
                header: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                editable: false,
                selectable: false,
                eventLimit: false, // allow "more" link when too many events
                navLinks: false,
                events: [
                    @foreach($ftebEventArr as $event)
                    {
                        title: '{!! $event->event_code." (".$event->formation_code.") by ".$event->shortname !!}',
                        start: '{{$event->start_date}}',
                        description: '{!! $event->start_date." to ".$event->end_date ." by ".$event->full_name !!}',
                        end: '<?php echo  date('Y-m-d', strtotime($event->end_date . ' +1 day')); ?>',
                        color: '#e43a45'
                    },
                    @endforeach

                    @foreach($itebEventArr as $event)
                    {
                        title: "{!! $event['institute'].' ('.$event['event'].') by '.$event['evaluator'] !!}",
                        start: "{{$event['start_date']}}",
                        description:"{!! $event['start_date'].' to '.$event['end_date'] .' by '.$event['full_evaluator'] !!}",
                        end: "<?php echo  date('Y-m-d', strtotime($event['end_date'] . ' +1 day')); ?>",
                        color: '#3a87ad'
                    },
                    @endforeach
                    ]
            });
            @endfor

        });

        document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
    //            window.close();
            });


    </script>
        
    </body>
</html>

