<div class="modal-content" >
    <div class="modal-header clone-modal-header" >
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.FOLLOWUP_HISTORY')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal' ,'id' => 'submitForm')) !!}
    <div class="modal-body">
        @if($request->history_status == '1')
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 padding-top-10 webkit-scrollbar">
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <div class="portlet-body">
                            @if(!empty($finalArr) || !empty($finalLogArr))
                            <div class="mt-timeline-2">
                                <div class="mt-timeline-line border-grey-steel"></div>
                                <ul class="mt-container">
                                    @if(!empty($finalArr))
                                    @foreach($finalArr as $followUpDate=>$infoArr)
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($infoArr as $history)

                                    <li class="mt-item">
                                        <?php
                                        $bgColor = !empty($fStatArr[$history['status']]['color']) ? 'bg-' . $fStatArr[$history['status']]['color'] . ' bg-font-' . $fStatArr[$history['status']]['color'] : '';
                                        $bgFont = !empty($fStatArr[$history['status']]['color']) ? 'font-' . $fStatArr[$history['status']]['color'] : '';
                                        $labelColor = !empty($fStatArr[$history['status']]['color']) ? 'label-' . $fStatArr[$history['status']]['color'] : 'label-danger';
                                        $ribbonColor = !empty($fStatArr[$history['status']]['color']) ? 'ribbon-color-' . $fStatArr[$history['status']]['color'] : 'ribbon-color-danger';
                                        $iconShape = !empty($fStatArr[$history['status']]['icon']) ? $fStatArr[$history['status']]['icon'] : '';
                                        ?>
                                        <div class="mt-timeline-icon border-grey-steel {{$bgColor}}">
                                            <i class="{{$iconShape}}"></i>
                                        </div>
                                        <div class="mt-timeline-content">
                                            <div class="mt-content-container track-history">
                                                <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                    @if(!empty($history['updated_by']))
                                                    <?php
                                                    $updatedBy = $history['updated_by'];
                                                    $col1 = '3';
                                                    $col2 = '9';
                                                    if (!empty($history['updated_at'])) {
                                                        $col1 = '2';
                                                        $col2 = '10';
                                                    }
                                                    ?>
                                                    @if(!empty($userArr))
                                                    @if(array_key_exists($updatedBy, $userArr))
                                                    <?php $user = $userArr[$updatedBy]; ?>
                                                    <div class="portlet-title portlet-title-border">
                                                        <div class="caption">
                                                            <div class="row">
                                                                <div class="col-md-{{$col1}}">
                                                                    @if(!empty($user['photo']) && File::exists('public/uploads/user/'.$user['photo']))
                                                                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}" alt="{{ $user['full_name'] }}"/>
                                                                    @else
                                                                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $user['full_name'] }}"/>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-{{$col2}}">
                                                                    <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $user['full_name'] !!}</span><br/>
                                                                    <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! __('label.EMPLOYEE_ID').' : '.$user['employee_id'] !!}</span>
                                                                    @if(!empty($history['updated_at']))
                                                                    <br/><i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} bold font-size-14">{!! Helper::formatDateTime($history['updated_at']) !!}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    <div class="portlet-title portlet-no-css">
                                                        <div class="caption">
                                                            <i class=" icon-calendar {{ $bgFont }}"></i>
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $history['follow_up_date'] !!}</span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($history['updated_by']))
                                                    @if(!empty($userArr))
                                                    @if(array_key_exists($updatedBy, $userArr))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']])) ? $statusList[$history['status']] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    <div class="portlet-title portlet-no-css">
                                                        <div class="caption">
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.REMARKS')</span>
                                                        </div>
                                                    </div>
                                                    @if(empty($history['updated_by']))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']])) ? $statusList[$history['status']] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    <div class="portlet-body portlet-body-padding">
                                                        <p class="track-text font-size-14">
                                                            {!! $history['remarks'] !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endforeach
                                    @endif
                                    @if(!empty($finalLogArr))
                                    @foreach($finalLogArr as $date => $logInfoArr)
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($logInfoArr as $logItem)

                                    <li class="mt-item">
                                        <?php
                                        $logBgColor = !empty($logItem['background']) ? $logItem['background'] : '';
                                        $logBgFont = !empty($logItem['font']) ? $logItem['font'] : '';
                                        $logLabelColor = !empty($logItem['label']) ? $logItem['label'] : 'label-danger';
                                        $logRibbonColor = !empty($logItem['ribbon']) ? $logItem['ribbon'] : 'ribbon-color-danger';
                                        $logIconShape = !empty($logItem['icon']) ? $logItem['icon'] : '';
                                        ?>
                                        <div class="mt-timeline-icon border-grey-steel {{$logBgColor}}">
                                            <i class="{{$logIconShape}}"></i>
                                        </div>
                                        <div class="mt-timeline-content">
                                            <div class="mt-content-container track-history">
                                                <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                    @if(!empty($logItem['updated_by']))
                                                    <?php
                                                    $updatedBy = $logItem['updated_by'];
                                                    $logCol1 = '3';
                                                    $logCol2 = '9';
                                                    if (!empty($logItem['updated_at'])) {
                                                        $logCol1 = '2';
                                                        $logCol2 = '10';
                                                    }
                                                    ?>
                                                    @if(!empty($userArr))
                                                    @if(array_key_exists($updatedBy, $userArr))
                                                    <?php $user = $userArr[$updatedBy]; ?>
                                                    <div class="portlet-title portlet-title-border">
                                                        <div class="caption">
                                                            <div class="row">
                                                                <div class="col-md-{{$logCol1}}">
                                                                    @if(!empty($user['photo']) && File::exists('public/uploads/user/'.$user['photo']))
                                                                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}" alt="{{ $user['full_name'] }}"/>
                                                                    @else
                                                                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $user['full_name'] }}"/>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-{{$logCol2}}">
                                                                    <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! $user['full_name'] !!}</span><br/>
                                                                    <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! __('label.EMPLOYEE_ID').' : '.$user['employee_id'] !!}</span>
                                                                    @if(!empty($logItem['updated_at']))
                                                                    <br/><i class="fa fa-clock-o {{ $logBgFont }}"> </i><span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! Helper::formatDateTime($logItem['updated_at']) !!}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    <div class="portlet-title portlet-no-css">
                                                        <div class="caption">
                                                            <i class=" icon-calendar {{ $logBgFont }}"></i>
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! $logItem['date'] !!}</span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($logItem['updated_by']))
                                                    @if(!empty($userArr))
                                                    @if(array_key_exists($updatedBy, $userArr))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$logRibbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$logIconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    <div class="portlet-body portlet-body-padding">
                                                        <p class="track-text font-size-14">
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.TYPE') :</span> {!! $logItem['activity_type'] !!}<br/>
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.PRIORITY') :</span> {!! $logItem['priority'] !!}<br/>
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.CONTACT_PERSON') :</span> {!! $logItem['contact_person'] !!}<br/>
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.REMARKS') : </span> {!! $logItem['remarks'] !!}
                                                        </p>
                                                    </div>
                                                    @if(empty($logItem['updated_by']))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$logRibbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$logIconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    @if(!empty($logItem['has_schedule']))
                                                    <div class="portlet-body portlet-title-border-up">
                                                        <p class="track-text font-size-14">
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.SCHEDULE') :</span><i class="fa fa-clock-o {{ $logBgFont }}"> </i> {!! $logItem['schedule_date_time'] !!}<br/>
                                                            <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.PURPOSE') :</span> {!! $logItem['schedule_purpose'] !!}<br/>
                                                        </p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                    ?>
                                    @endforeach
                                    @endforeach
                                    @endif
                                    <li class="mt-item">
                                        <div class="mt-timeline-icon bg-grey-mint bg-font-grey-mint">
                                            <i class="icon-arrow-up"></i>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <div class="col-md-12 text-center">
                                <div class="alert alert-danger">
                                    <p>
                                        <i class="fa fa-warning"></i>
                                        @lang('label.FOLLOW_UP_HISTORY_IS_NOT_AVAILABLE') <a href="#tab_15_2" id="setFollowUpWhenEmpty" data-toggle="tab"> @lang('label.CLICK_TO_SET_FOLLOW_UP') </a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="active" id="history">
                            <a href="#tab_15_1" id="historyBtn" data-toggle="tab"> @lang('label.HISTORY') </a>
                        </li>

                        <li id="setFollowUp">
                            <a href="#tab_15_2" id="followupBtn" data-toggle="tab"> @lang('label.SET_FOLLOW_UP') </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Start:: history tab -->
                        <div class="tab-pane active" id="tab_15_1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive max-height-500 padding-top-10 webkit-scrollbar">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            <div class="portlet-body">
                                                @if(!empty($finalArr) || !empty($finalLogArr))
                                                <div class="mt-timeline-2">
                                                    <div class="mt-timeline-line border-grey-steel"></div>
                                                    <ul class="mt-container">
                                                        @if(!empty($finalArr))
                                                        @foreach($finalArr as $followUpDate=>$infoArr)
                                                        <?php
                                                        $i = 0;
                                                        ?>
                                                        @foreach($infoArr as $history)

                                                        <li class="mt-item">
                                                            <?php
                                                            $bgColor = !empty($fStatArr[$history['status']]['color']) ? 'bg-' . $fStatArr[$history['status']]['color'] . ' bg-font-' . $fStatArr[$history['status']]['color'] : '';
                                                            $bgFont = !empty($fStatArr[$history['status']]['color']) ? 'font-' . $fStatArr[$history['status']]['color'] : '';
                                                            $labelColor = !empty($fStatArr[$history['status']]['color']) ? 'label-' . $fStatArr[$history['status']]['color'] : 'label-danger';
                                                            $ribbonColor = !empty($fStatArr[$history['status']]['color']) ? 'ribbon-color-' . $fStatArr[$history['status']]['color'] : 'ribbon-color-danger';
                                                            $iconShape = !empty($fStatArr[$history['status']]['icon']) ? $fStatArr[$history['status']]['icon'] : '';
                                                            ?>
                                                            <div class="mt-timeline-icon border-grey-steel {{$bgColor}}">
                                                                <i class="{{$iconShape}}"></i>
                                                            </div>
                                                            <div class="mt-timeline-content">
                                                                <div class="mt-content-container track-history">
                                                                    <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                                        @if(!empty($history['updated_by']))
                                                                        <?php
                                                                        $updatedBy = $history['updated_by'];
                                                                        $col1 = '3';
                                                                        $col2 = '9';
                                                                        if (!empty($history['updated_at'])) {
                                                                            $col1 = '2';
                                                                            $col2 = '10';
                                                                        }
                                                                        ?>
                                                                        @if(!empty($userArr))
                                                                        @if(array_key_exists($updatedBy, $userArr))
                                                                        <?php $user = $userArr[$updatedBy]; ?>
                                                                        <div class="portlet-title portlet-title-border">
                                                                            <div class="caption">
                                                                                <div class="row">
                                                                                    <div class="col-md-{{$col1}}">
                                                                                        @if(!empty($user['photo']) && File::exists('public/uploads/user/'.$user['photo']))
                                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}" alt="{{ $user['full_name'] }}"/>
                                                                                        @else
                                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $user['full_name'] }}"/>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="col-md-{{$col2}}">
                                                                                        <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $user['full_name'] !!}</span><br/>
                                                                                        <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! __('label.EMPLOYEE_ID').' : '.$user['employee_id'] !!}</span>
                                                                                        @if(!empty($history['updated_at']))
                                                                                        <br/><i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} bold font-size-14">{!! Helper::formatDateTime($history['updated_at']) !!}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        @endif
                                                                        <div class="portlet-title portlet-no-css">
                                                                            <div class="caption">
                                                                                <i class=" icon-calendar {{ $bgFont }}"></i>
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $history['follow_up_date'] !!}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if(!empty($history['updated_by']))
                                                                        @if(!empty($userArr))
                                                                        @if(array_key_exists($updatedBy, $userArr))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']])) ? $statusList[$history['status']] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        @endif
                                                                        <div class="portlet-title portlet-no-css">
                                                                            <div class="caption">
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.REMARKS')</span>
                                                                            </div>
                                                                        </div>
                                                                        @if(empty($history['updated_by']))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']])) ? $statusList[$history['status']] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        <div class="portlet-body portlet-body-padding">
                                                                            <p class="track-text font-size-14">
                                                                                {!! $history['remarks'] !!}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endforeach
                                                        @endif
                                                        @if(!empty($finalLogArr))
                                                        @foreach($finalLogArr as $date => $logInfoArr)
                                                        <?php
                                                        $i = 0;
                                                        ?>
                                                        @foreach($logInfoArr as $logItem)

                                                        <li class="mt-item">
                                                            <?php
                                                            $logBgColor = !empty($logItem['background']) ? $logItem['background'] : '';
                                                            $logBgFont = !empty($logItem['font']) ? $logItem['font'] : '';
                                                            $logLabelColor = !empty($logItem['label']) ? $logItem['label'] : 'label-danger';
                                                            $logRibbonColor = !empty($logItem['ribbon']) ? $logItem['ribbon'] : 'ribbon-color-danger';
                                                            $logIconShape = !empty($logItem['icon']) ? $logItem['icon'] : '';
                                                            ?>
                                                            <div class="mt-timeline-icon border-grey-steel {{$logBgColor}}">
                                                                <i class="{{$logIconShape}}"></i>
                                                            </div>
                                                            <div class="mt-timeline-content">
                                                                <div class="mt-content-container track-history">
                                                                    <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                                        @if(!empty($logItem['updated_by']))
                                                                        <?php
                                                                        $updatedBy = $logItem['updated_by'];
                                                                        $logCol1 = '3';
                                                                        $logCol2 = '9';
                                                                        if (!empty($logItem['updated_at'])) {
                                                                            $logCol1 = '2';
                                                                            $logCol2 = '10';
                                                                        }
                                                                        ?>
                                                                        @if(!empty($userArr))
                                                                        @if(array_key_exists($updatedBy, $userArr))
                                                                        <?php $user = $userArr[$updatedBy]; ?>
                                                                        <div class="portlet-title portlet-title-border">
                                                                            <div class="caption">
                                                                                <div class="row">
                                                                                    <div class="col-md-{{$logCol1}}">
                                                                                        @if(!empty($user['photo']) && File::exists('public/uploads/user/'.$user['photo']))
                                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}" alt="{{ $user['full_name'] }}"/>
                                                                                        @else
                                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $user['full_name'] }}"/>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="col-md-{{$logCol2}}">
                                                                                        <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! $user['full_name'] !!}</span><br/>
                                                                                        <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! __('label.EMPLOYEE_ID').' : '.$user['employee_id'] !!}</span>
                                                                                        @if(!empty($logItem['updated_at']))
                                                                                        <br/><i class="fa fa-clock-o {{ $logBgFont }}"> </i><span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! Helper::formatDateTime($logItem['updated_at']) !!}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        @endif
                                                                        <div class="portlet-title portlet-no-css">
                                                                            <div class="caption">
                                                                                <i class=" icon-calendar {{ $logBgFont }}"></i>
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">{!! $logItem['date'] !!}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if(!empty($logItem['updated_by']))
                                                                        @if(!empty($userArr))
                                                                        @if(array_key_exists($updatedBy, $userArr))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$logRibbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$logIconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        @endif
                                                                        <div class="portlet-body portlet-body-padding">
                                                                            <p class="track-text font-size-14">
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.TYPE') :</span> {!! $logItem['activity_type'] !!}<br/>
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.PRIORITY') :</span> {!! $logItem['priority'] !!}<br/>
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.CONTACT_PERSON') :</span> {!! $logItem['contact_person'] !!}<br/>
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.REMARKS') : </span> {!! $logItem['remarks'] !!}
                                                                            </p>
                                                                        </div>
                                                                        @if(empty($logItem['updated_by']))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$logRibbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$logIconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        @if(!empty($logItem['has_schedule']))
                                                                        <div class="portlet-body portlet-title-border-up">
                                                                            <p class="track-text font-size-14">
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.SCHEDULE') :</span><i class="fa fa-clock-o {{ $logBgFont }}"> </i> {!! $logItem['schedule_date_time'] !!}<br/>
                                                                                <span class="caption-subject {{ $logBgFont }} bold font-size-14">@lang('label.PURPOSE') :</span> {!! $logItem['schedule_purpose'] !!}<br/>
                                                                            </p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                        $i++;
                                                        ?>
                                                        @endforeach
                                                        @endforeach
                                                        @endif
                                                        <li class="mt-item">
                                                            <div class="mt-timeline-icon bg-grey-mint bg-font-grey-mint">
                                                                <i class="icon-arrow-up"></i>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @else
                                                <div class="col-md-12 text-center">
                                                    <div class="alert alert-danger">
                                                        <p>
                                                            <i class="fa fa-warning"></i>
                                                            @lang('label.FOLLOW_UP_HISTORY_IS_NOT_AVAILABLE') <a href="#tab_15_2" id="setFollowUpWhenEmpty" data-toggle="tab"> @lang('label.CLICK_TO_SET_FOLLOW_UP') </a>
                                                        </p>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- EOF:: history tab -->

                        <!-- START:: set follow up tab -->
                        <div class="tab-pane" id="tab_15_2">
                            {!! Form::hidden('inquiry_id', Request::get('inquiry_id')) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-7">
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="followUpDate">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                <?php $followUpDate = date('d F Y'); ?>
                                                <div class="input-group date datepicker2" style="z-index: 9994 !important">
                                                    {!! Form::text('follow_up_date', $followUpDate, ['id'=> 'followUpDate', 'class' => 'form-control', 'placeholder' => 'DD MM yyyy', 'readonly' => '']) !!} 
                                                    <span class="input-group-btn">
                                                        <button class="btn default reset-date" type="button" remove="followUpDate">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                {!! Form::select('status', $statusList, Request::get('status'), ['class' => 'form-control js-source-states ','id'=>'status']) !!}
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="remarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-8">
                                                {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control']) !!} 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- START:: set follow up tab -->
                        </div>
                    </div>
                    <!-- EOF:: set follow up tab -->
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                @if($request->history_status != '1')
                <button type="button" class="btn green"  id="saveHistory">
                    <i class="fa fa-check"></i> @lang('label.SAVE')
                </button>
                @endif
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<!-- END:: Contact Person Information-->
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    if ($("#setFollowUp").hasClass('active')) {
        $("#saveHistory").show();
    } else {
        $("#saveHistory").hide();
    }

    $("#historyBtn").on("click", function () {
        $("#saveHistory").hide();
    });
    $("#followupBtn").on("click", function () {
        $("#saveHistory").show();
    });

    $('#setFollowUpWhenEmpty').on('click', function () {
        $('#history').removeClass();
        $('#setFollowUp').addClass('active');
        $("#saveHistory").show();
    });
});
</script>
