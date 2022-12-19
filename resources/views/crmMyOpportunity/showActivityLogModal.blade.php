<div class="modal-content" >
    <div class="modal-header clone-modal-header" >
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ACTIVITY_LOG')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal' ,'id' => 'submitActivityForm')) !!}
    <div class="modal-body">
        @if($request->history_status == '1')
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 padding-top-10 webkit-scrollbar">
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <div class="portlet-body">
                            @if(!empty($finalArr))
                            <div class="mt-timeline-2">
                                <div class="mt-timeline-line border-grey-steel"></div>
                                <ul class="mt-container">
                                    @foreach($finalArr as $date => $infoArr)
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach($infoArr as $logItem)

                                    <li class="mt-item">
                                        <?php
                                        $bgColor = !empty($logItem['background']) ? $logItem['background'] : '';
                                        $bgFont = !empty($logItem['font']) ? $logItem['font'] : '';
                                        $labelColor = !empty($logItem['label']) ? $logItem['label'] : 'label-danger';
                                        $ribbonColor = !empty($logItem['ribbon']) ? $logItem['ribbon'] : 'ribbon-color-danger';
                                        $iconShape = !empty($logItem['icon']) ? $logItem['icon'] : '';
                                        ?>
                                        <div class="mt-timeline-icon border-grey-steel {{$bgColor}}">
                                            <i class="{{$iconShape}}"></i>
                                        </div>
                                        <div class="mt-timeline-content">
                                            <div class="mt-content-container track-history">
                                                <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                    @if(!empty($logItem['updated_by']))
                                                    <?php
                                                    $updatedBy = $logItem['updated_by'];
                                                    $col1 = '3';
                                                    $col2 = '9';
                                                    if (!empty($logItem['updated_at'])) {
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
                                                                    @if(!empty($logItem['updated_at']))
                                                                    <br/><i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} bold font-size-14">{!! Helper::formatDateTime($logItem['updated_at']) !!}</span>
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
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $logItem['date'] !!}</span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($logItem['updated_by']))
                                                    @if(!empty($userArr))
                                                    @if(array_key_exists($updatedBy, $userArr))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$iconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    <div class="portlet-body portlet-body-padding">
                                                        <p class="track-text font-size-14">
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.TYPE') :</span> {!! $logItem['activity_type'] !!}<br/>
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.PRIORITY') :</span> {!! $logItem['priority'] !!}<br/>
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.CONTACT_PERSON') :</span> {!! $logItem['contact_person'] !!}<br/>
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.REMARKS') : </span> {!! $logItem['remarks'] !!}
                                                        </p>
                                                    </div>
                                                    @if(empty($logItem['updated_by']))
                                                    <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                        <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                        <i class="{{$iconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                    </div>
                                                    @endif
                                                    @if(!empty($logItem['has_schedule']))
                                                    <div class="portlet-body portlet-title-border-up">
                                                        <p class="track-text font-size-14">
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.SCHEDULE') :</span><i class="fa fa-clock-o {{ $bgFont }}"> </i> {!! $logItem['schedule_date_time'] !!}<br/>
                                                            <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.PURPOSE') :</span> {!! $logItem['schedule_purpose'] !!}<br/>
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
                                        @lang('label.FOLLOW_UP_HISTORY_IS_NOT_AVAILABLE') <a href="#tab_15_2" id="setLogWhenEmpty" data-toggle="tab"> @lang('label.CLICK_TO_SET_FOLLOW_UP') </a>
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
                        <li class="active" id="logHistory">
                            <a href="#tab_15_1" id="logBtn" data-toggle="tab"> @lang('label.LOG_HISTORY') </a>
                        </li>

                        <li id="setActivityLog">
                            <a href="#tab_15_2" id="activitySetBtn" data-toggle="tab"> @lang('label.SET_ACTIVITY_LOG') </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Start:: Activity Log tab -->
                        <div class="tab-pane active" id="tab_15_1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive max-height-500 padding-top-10 webkit-scrollbar">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            <div class="portlet-body">
                                                @if(!empty($finalArr))
                                                <div class="mt-timeline-2">
                                                    <div class="mt-timeline-line border-grey-steel"></div>
                                                    <ul class="mt-container">
                                                        @foreach($finalArr as $date => $infoArr)
                                                        <?php
                                                        $i = 0;
                                                        ?>
                                                        @foreach($infoArr as $logItem)

                                                        <li class="mt-item">
                                                            <?php
                                                            $bgColor = !empty($logItem['background']) ? $logItem['background'] : '';
                                                            $bgFont = !empty($logItem['font']) ? $logItem['font'] : '';
                                                            $labelColor = !empty($logItem['label']) ? $logItem['label'] : 'label-danger';
                                                            $ribbonColor = !empty($logItem['ribbon']) ? $logItem['ribbon'] : 'ribbon-color-danger';
                                                            $iconShape = !empty($logItem['icon']) ? $logItem['icon'] : '';
                                                            ?>
                                                            <div class="mt-timeline-icon border-grey-steel {{$bgColor}}">
                                                                <i class="{{$iconShape}}"></i>
                                                            </div>
                                                            <div class="mt-timeline-content">
                                                                <div class="mt-content-container track-history">
                                                                    <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                                        @if(!empty($logItem['updated_by']))
                                                                        <?php
                                                                        $updatedBy = $logItem['updated_by'];
                                                                        $col1 = '3';
                                                                        $col2 = '9';
                                                                        if (!empty($logItem['updated_at'])) {
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
                                                                                        @if(!empty($logItem['updated_at']))
                                                                                        <br/><i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} bold font-size-14">{!! Helper::formatDateTime($logItem['updated_at']) !!}</span>
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
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $logItem['date'] !!}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if(!empty($logItem['updated_by']))
                                                                        @if(!empty($userArr))
                                                                        @if(array_key_exists($updatedBy, $userArr))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        @endif
                                                                        <div class="portlet-body portlet-body-padding">
                                                                            <p class="track-text font-size-14">
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.TYPE') :</span> {!! $logItem['activity_type'] !!}<br/>
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.PRIORITY') :</span> {!! $logItem['priority'] !!}<br/>
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.CONTACT_PERSON') :</span> {!! $logItem['contact_person'] !!}<br/>
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.REMARKS') : </span> {!! $logItem['remarks'] !!}
                                                                            </p>
                                                                        </div>
                                                                        @if(empty($logItem['updated_by']))
                                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (!empty($logItem['status'])) ? $logItem['status'] : __('label.N_A') !!} 
                                                                        </div>
                                                                        @endif
                                                                        @if(!empty($logItem['has_schedule']))
                                                                        <div class="portlet-body portlet-title-border-up">
                                                                            <p class="track-text font-size-14">
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.SCHEDULE') :</span><i class="fa fa-clock-o {{ $bgFont }}"> </i> {!! $logItem['schedule_date_time'] !!}<br/>
                                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.PURPOSE') :</span> {!! $logItem['schedule_purpose'] !!}<br/>
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
                                                            @lang('label.ACTIVITY_LOG_IS_NOT_AVAILABLE') <a href="#tab_15_2" id="setLogWhenEmpty" data-toggle="tab"> @lang('label.CLICK_TO_SET_ACTIVITY_LOG') </a>
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
                        <!-- EOF:: Activity Log tab -->

                        <!-- START:: set follow up tab -->
                        <div class="tab-pane" id="tab_15_2">
                            {!! Form::open(array('group' => 'form', 'url' => '', 'id' => '', 'class' => 'form-horizontal','files' => true)) !!}
                            {{csrf_field()}}
                            {!! Form::hidden('opportunity_id', Request::get('opportunity_id')) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="contactPersonKey">@lang('label.CONTACT_PERSON') :</label>
                                        <div class="col-md-9">
                                            {!! Form::select('contact_person_key', $contactPersonArr, Request::get('contact_person_key'), ['class' => 'form-control js-source-states ','id'=>'contactPersonKey']) !!}
                                            <span class="text-danger">{{ $errors->first('contact_person_key') }}</span>
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="col-md-2">
                                    <button type="button" class="btn blue add-contact"  id="" data-opportunity-id="{{ $request->opportunity_id}}">
                                        @lang('label.ADD_CONTACT')
                                    </button>
                                </div>-->
                            </div>

                            <!--Start :: Contact Person Info-->
                            <div id="contactForm"></div>
                            <!--End :: Contact Person Info-->

                            <div class="row margin-top-10">
                                <div class="col-md-6 col-lg-6 col-sm-6 form-body confirm-order-border">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{{trans('label.DATE')}} :</label>
                                        <div class="col-md-9">
                                            <div class="input-group date datepicker2">
                                                {!! Form::text('date', Request::get('date'), ['id'=> 'date', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                                <span class="input-group-btn">
                                                    <button class="btn default reset-date" type="button" remove="date">
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
                                        <label class="control-label col-md-3" for="activityType">@lang('label.TYPE') :<span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            {!! Form::select('activity_type', $activityTypeArr, Request::get('activity_type'), ['class' => 'form-control js-source-states','id'=>'activityType']) !!}
                                            <span class="text-danger">{{ $errors->first('activity_type') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="activityPriority">@lang('label.PRIORITY') :</label>
                                        <div class="col-md-9">
                                            {!! Form::select('activity_priority', $priorityArr, Request::get('activity_priority'), ['class' => 'form-control js-source-states','id'=>'activityPriority']) !!}
                                            <span class="text-danger">{{ $errors->first('activity_priority') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-6 form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="activityStatus">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            {!! Form::select('activity_status', $activityStatusArr, Request::get('activity_status'), ['class' => 'form-control js-source-states','id'=>'activityStatus']) !!}
                                            <span class="text-danger">{{ $errors->first('activity_status') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="remarks">@lang('label.REMARKS') :<span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            {!! Form::textarea('remarks', null, ['id'=> 'remarks', 'class' => 'form-control','cols'=>'20','rows' => '3']) !!} 
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="hasSchedule">@lang('label.SCHEDULE') :</label>
                                        <div class="col-md-9 checkbox-center md-checkbox has-success">
                                            {!! Form::checkbox('has_schedule',1,null, ['id' => 'hasSchedule', 'class'=> 'md-check']) !!}
                                            <label for="hasSchedule">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck"></span>
                                                <span class="box mark-caheck"></span>
                                            </label>
                                            <span class="text-success">@lang('label.PUT_TICK_IF_HAS_SCHEDULE')</span>
                                        </div>
                                    </div>
                                    <div id="scheduleForm" style="display: none;">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">{{trans('label.SCHEDULE_DATE_TIME')}} :<span class="required"> *</span></label>
                                            <div class="col-md-6">
                                                <div class="input-group date datetime-picker">
                                                    {{ Form::text('schedule_date_time', null, array('id'=> 'scheduleDateTime', 'class' => 'form-control', 'placeholder' => 'Enter Schedule Date Time', 'size' => '13', 'readonly' => true,)) }}
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-reset" type="button">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="schedulePurpose">@lang('label.PURPOSE') :<span class="text-danger"> *</span></label>
                                            <div class="col-md-6">
                                                {!! Form::textarea('schedule_purpose', null, ['id'=> 'schedulePurpose', 'class' => 'form-control','cols'=>'20','rows' => '3']) !!} 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--PRODUCT DETAILS-->
                            <div class="row div-box-default product-details-block">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <span class="bold assign-condition text-green">(@lang('label.PLEASE_SELECT_AT_LEAST_ONE_PRODUCT'))</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 margin-top-20">
                                            <div class="table-responsive webkit-scrollbar">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="active">
                                                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                                            <th class="text-center vcenter">@lang('label.CHECK')</th>
                                                            <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                                            <th class="text-center vcenter">@lang('label.BRAND')</th>
                                                            <th class="text-center vcenter">@lang('label.GRADE')</th>
                                                            <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                                            <th class="text-center vcenter">@lang('label.GSM')</th>
                                                            <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                                            <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                                            <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($productArr))
                                                        <?php $sl = 0; ?>
                                                        @foreach($productArr as $pKey => $pInfo)
                                                        <?php
                                                        //product
                                                        if ($pInfo['product_has_id'] == '0') {
                                                            $product = $pInfo['product'];
                                                        } elseif ($pInfo['product_has_id'] == '1') {
                                                            $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                                        }
                                                        //brand
                                                        if ($pInfo['brand_has_id'] == '0') {
                                                            $brand = $pInfo['brand'];
                                                        } elseif ($pInfo['brand_has_id'] == '1') {
                                                            $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                                        }
                                                        //grade
                                                        if ($pInfo['grade_has_id'] == '0') {
                                                            $grade = $pInfo['grade'];
                                                        } elseif ($pInfo['grade_has_id'] == '1') {
                                                            $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                                        }

                                                        //Origin
                                                        $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                                        $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                                        $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                                        $checked = !empty($pInfo['final']) && $pInfo['final'] == 1 ? "checked" : null;
                                                        ?>
                                                        <tr>
                                                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                            <td class="text-center vcenter">
                                                                <div class="checkbox-center md-checkbox has-success">
                                                                    {!! Form::checkbox('final_product['.$pKey.'][final]',1,$checked, ['id' => 'finalProduct_'.$pKey, 'class'=> 'md-check final_product']) !!}
                                                                    <label for="finalProduct_{{$pKey}}">
                                                                        <span class="inc"></span>
                                                                        <span class="check mark-caheck"></span>
                                                                        <span class="box mark-caheck"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="vcenter">{!! $product ?? __('label.N_A') !!}</td>
                                                            <td class="vcenter">{!! $brand ?? __('label.N_A') !!}</td>
                                                            <td class="vcenter">{!! $grade ?? __('label.N_A') !!}</td>
                                                            <td class="text-center vcenter">{!! $country !!}</td>
                                                            <td class="vcenter">{!! (!empty($pInfo['gsm'])) ? $pInfo['gsm'] : '' !!}</td>
                                                            <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                                            <td class="text-right vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                                            <td class="text-right vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                                        </tr>
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td class="vcenter" colspan="10">@lang('label.NO_DATA_FOUND')</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END OF PRODUCT DETAILS-->
                            {!! Form::close() !!}
                            <!-- START:: set follow up tab -->
                        </div>
                    </div>
                    <!-- EOF:: set follow up tab -->
                </div>
            </div>
        </div>
        @endif

        <div class="row margin-top-20 opportunity-details-block">
            <div class="col-md-12 text-right">
                <button class="btn purple-sharp btn-hide-opportunity-details-block margin-left-right-5 tooltips" type="button" data-placement="top" title="@lang('label.CLICK_TO_HIDE_OPPORTUNITY_DETAILS')">
                    @lang('label.HIDE_OPPORTUNITY_DETAILS')
                </button>
            </div>
        </div>
        <!--BASIC INFORMATION-->
        <div class="row div-box-default opportunity-details-block margin-top-20">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.BASIC_OPPORTUNITY_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless margin-bottom-0">
                            <?php
                            if ($target->buyer_has_id == '0') {
                                $buyer = $target->buyer;
                            } elseif ($target->buyer_has_id == '1') {
                                $buyer = !empty($buyerList[$target->buyer]) && $target->buyer != 0 ? $buyerList[$target->buyer] : '';
                            }
                            ?>
                            <tr >
                                <td class="bold" width="50%">@lang('label.BUYER')</td>
                                <td width="50%">{!! !empty($buyer)?$buyer:__('label.N_A') !!}</td>
                            </tr>
                            <tr >
                                <td class="bold" width="50%">@lang('label.CREATED_BY')</td>
                                <td width="50%">{!! !empty($target->opportunity_creator)?$target->opportunity_creator:__('label.N_A') !!}</td>
                            </tr>     
                            <tr >
                                <td class="bold" width="50%">@lang('label.ASSIGNED_TO')</td>
                                <td width="50%">{!! !empty($assignedPersonList[$target->id])?$assignedPersonList[$target->id]:__('label.N_A') !!}</td>
                            </tr> 
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="50%">@lang('label.SOURCE')</td>
                                <td width="50%">
                                    {!! !empty($target->source)?$target->source:__('label.N_A') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold" width="50%">@lang('label.STATUS')</td>
                                <td width="50%">
                                    @if($target->status == '0')
                                    <span class="label label-sm label-blue-madison">{!! __('label.NEW_OPPORTUNITY') !!}</span>
                                    @elseif($target->status == '1')
                                    @if($target->revoked_status == '0')
                                    @if($target->last_activity_status == '0')
                                    <span class="label label-sm label-blue-steel">{!! __('label.IN_PROGRESS') !!}</span>
                                    @elseif($target->last_activity_status == '1')
                                    <span class="label label-sm label-red-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '2')
                                    <span class="label label-sm label-blue-chambray">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '3')
                                    <span class="label label-sm label-blue-hoki">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '4')
                                    <span class="label label-sm label-blue-soft">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '5')
                                    <span class="label label-sm label-green-steel">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '6')
                                    <span class="label label-sm label-yellow-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '8')
                                    <span class="label label-sm label-red-pink">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '9')
                                    <span class="label label-sm label-purple-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '10')
                                    <span class="label label-sm label-green-sharp">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '11')
                                    <span class="label label-sm label-grey-mint">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @elseif($target->last_activity_status == '12')
                                    <span class="label label-sm label-yellow-casablanca">{!! $activityStatusList[$target->last_activity_status] !!}</span>
                                    @endif
                                    @elseif($target->revoked_status == '1')
                                    <span class="label label-sm label-red-thunderbird">{!! __('label.REVOKED') !!}</span>
                                    @endif
                                    @elseif($target->status == '2')
                                    @if($target->dispatch_status == '0')
                                    <span class="label label-sm label-green-soft">{!! __('label.BOOKED') !!}</span>
                                    @elseif($target->dispatch_status == '1')
                                    @if($target->approval_status == '0')
                                    <span class="label label-sm label-purple">{!! __('label.DISPATCHED') !!}</span>
                                    @elseif($target->approval_status == '1')
                                    <span class="label label-sm label-green-seagreen">{!! __('label.APPROVED') !!}</span>
                                    @elseif($target->approval_status == '2')
                                    <span class="label label-sm label-red-mint">{!! __('label.DENIED') !!}</span>
                                    @endif
                                    @endif
                                    @elseif($target->status == '3')
                                    <span class="label label-sm label-red-flamingo">{!! __('label.CANCELLED') !!}</span>
                                    @elseif($target->status == '4')
                                    <span class="label label-sm label-grey-cascade">{!! __('label.VOID') !!}</span>
                                    @endif
                                </td>
                            </tr>     
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <table class="table table-borderless margin-bottom-0">
                            <tr>
                                <td class="bold" width="25%">@lang('label.ADDRESS')</td>
                                <td width="75%">
                                    {!! !empty($target->address)?$target->address:__('label.N_A') !!}
                                </td>
                            </tr>    
                            <tr>
                                <td class="bold" width="25%">@lang('label.REMARKS')</td>
                                <td width="75%">
                                    {!! !empty($target->remarks)?$target->remarks:__('label.N_A') !!}
                                </td>
                            </tr>    
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF BASIC INFORMATION-->

        <!--CONTACT PERSON DETAILS-->
        <div class="row div-box-default opportunity-details-block">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter">@lang('label.NAME')</th>
                                        <th class="text-center vcenter">@lang('label.DESIGNATION')</th>
                                        <th class="text-center vcenter">@lang('label.EMAIL')</th>
                                        <th class="text-center vcenter">@lang('label.PHONE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($contactArr))
                                    <?php $sl = 0; ?>
                                    @foreach($contactArr as $cKey => $cInfo)
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $cInfo['name'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['designation'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['email'] ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $cInfo['phone'] ?? __('label.N_A') !!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="vcenter" colspan="6">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF CONTACT PERSON DETAILS-->

        <!--PRODUCT DETAILS-->
        <div class="row div-box-default opportunity-details-block">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 border-bottom-1-green-seagreen">
                        <h4><strong>@lang('label.PRODUCT_INFORMATION')</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 margin-top-20">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                        <th class="text-center vcenter">@lang('label.BRAND')</th>
                                        <th class="text-center vcenter">@lang('label.GRADE')</th>
                                        <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                                        <th class="text-center vcenter">@lang('label.GSM')</th>
                                        <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                        <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                        <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productArr))
                                    <?php $sl = 0; ?>
                                    @foreach($productArr as $pKey => $pInfo)
                                    <?php
                                    //product
                                    if ($pInfo['product_has_id'] == '0') {
                                        $product = $pInfo['product'];
                                    } elseif ($pInfo['product_has_id'] == '1') {
                                        $product = !empty($productList[$pInfo['product']]) && $pInfo['product'] != 0 ? $productList[$pInfo['product']] : '';
                                    }
                                    //brand
                                    if ($pInfo['brand_has_id'] == '0') {
                                        $brand = $pInfo['brand'];
                                    } elseif ($pInfo['brand_has_id'] == '1') {
                                        $brand = !empty($brandList[$pInfo['brand']]) && $pInfo['brand'] != 0 ? $brandList[$pInfo['brand']] : '';
                                    }
                                    //grade
                                    if ($pInfo['grade_has_id'] == '0') {
                                        $grade = $pInfo['grade'];
                                    } elseif ($pInfo['grade_has_id'] == '1') {
                                        $grade = !empty($gradeList[$pInfo['grade']]) && $pInfo['grade'] != 0 ? $gradeList[$pInfo['grade']] : '';
                                    }

                                    //Origin
                                    $country = !empty($pInfo['origin']) && !empty($countryList[$pInfo['origin']]) ? $countryList[$pInfo['origin']] : __('label.N_A');

                                    $unit = !empty($pInfo['unit']) ? ' ' . $pInfo['unit'] : '';
                                    $perUnit = !empty($pInfo['unit']) ? ' / ' . $pInfo['unit'] : '';
                                    $statusTitle = __('label.FINAL_PRODUCT');
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">
                                            {!! $product ?? __('label.N_A') !!}
                                            @if(!empty($pInfo['final']) && $pInfo['final'] == '1')
                                            <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ $statusTitle }}">

                                            </button>
                                            @endif
                                        </td>
                                        <td class="vcenter">{!! $brand ?? __('label.N_A') !!}</td>
                                        <td class="vcenter">{!! $grade ?? __('label.N_A') !!}</td>
                                        <td class="text-center vcenter">{!! $country !!}</td>
                                        <td class="vcenter">{!! (!empty($pInfo['gsm'])) ? $pInfo['gsm'] : '' !!}</td>
                                        <td class="text-right vcenter">{!! (!empty($pInfo['quantity']) ? Helper::numberFormat2Digit($pInfo['quantity']) : '0.00') . $unit!!}</td>
                                        <td class="text-right vcenter">{!! '$' . (!empty($pInfo['unit_price']) ? Helper::numberFormat2Digit($pInfo['unit_price']) : '0.00') . $perUnit!!}</td>
                                        <td class="text-right vcenter">{!! '$' . (!empty($pInfo['total_price']) ? Helper::numberFormat2Digit($pInfo['total_price']) : '0.00')!!}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="vcenter" colspan="5">@lang('label.NO_DATA_FOUND')</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF PRODUCT DETAILS-->
    </div>
    <div class="modal-footer">
        <button class="btn blue-steel btn-show-opportunity-details-block tooltips" type="button" data-placement="top" title="@lang('label.CLICK_TO_SHOW_OPPORTUNITY_DETAILS')">
            @lang('label.SHOW_OPPORTUNITY_DETAILS')
        </button>
        <button class="btn purple-sharp btn-hide-opportunity-details-block tooltips" type="button" data-placement="top" title="@lang('label.CLICK_TO_HIDE_OPPORTUNITY_DETAILS')">
            @lang('label.HIDE_OPPORTUNITY_DETAILS')
        </button>
        @if($request->history_status != '1')
        <button type="button" class="btn green"  id="saveActivityLog">
            <i class="fa fa-check"></i> @lang('label.SAVE')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    {!! Form::close() !!}
</div>

<!-- END:: Contact Person Information-->
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    
    if ($("#setActivityLog").hasClass('active')) {
        $("#saveActivityLog").show();
    } else {
        $("#saveActivityLog").hide();
    }

    //******* Start :: Show/hide opportunity details *********//
    $('.opportunity-details-block').hide();
    $('.btn-hide-opportunity-details-block').hide();
    $('.btn-show-opportunity-details-block').show();
    $('.product-details-block').hide();

    $('.btn-show-opportunity-details-block').on('click', function () {
        $('.opportunity-details-block').show();
        $('.btn-hide-opportunity-details-block').show();
        $('.btn-show-opportunity-details-block').hide();
    });
    $('.btn-hide-opportunity-details-block').on('click', function () {
        $('.opportunity-details-block').hide();
        $('.btn-hide-opportunity-details-block').hide();
        $('.btn-show-opportunity-details-block').show();
    });

    //******* End :: Show/hide opportunity details ***********//

    $("#logBtn").on("click", function () {
        $("#saveActivityLog").hide();
    });
    $("#activitySetBtn").on("click", function () {
        $("#saveActivityLog").show();
    });

    $('#setLogWhenEmpty').on('click', function () {
        $('#logHistory').removeClass();
        $('#setActivityLog').addClass('active');
        $("#saveActivityLog").show();
    });

    //START:: Ajax for Allow User for CRM  
    $('#hasSchedule').on('click', function () {
        if ($(this).prop("checked") == true) {
            $('#scheduleForm').show();
        } else {
            $('#scheduleForm').hide();
        }
    });
    //END:: Ajax for Allow User for CRM

    //START:: Product details hide/show for activity BOOKED status 
    $(document).on("change", '#activityStatus', function () {
        $status = $(this).val();
        if ($status == 7) {
            $('.product-details-block').show();
        } else {
            $('.product-details-block').hide();
        }
    });
    //END:: Product details hide/show for activity BOOKED status 

//    $(document).on('click', '.primary-contact', function () {
//        var key = $(this).attr('data-key');
//        if ($(this).prop('checked')) {
//            $('.primary-contact').prop('checked', false);
//            $('#contactPrimary_' + key).prop('checked', true);
//        }
//    });
//
//    //add new contact row
//    $(document).on("click", ".add-new-contact-row", function (e) {
//        e.preventDefault();
//        $.ajaxSetup({
//            headers: {
//                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            }
//        });
//        var options = {
//            closeButton: true,
//            debug: false,
//            positionClass: "toast-bottom-right",
//            onclick: null,
//        };
//
//
//        $.ajax({
//            url: "{{URL::to('crmMyOpportunity/newContactRow')}}",
//            type: "POST",
//            dataType: 'json', // what to expect back from the PHP script, if anything
//            cache: false,
//            contentType: false,
//            processData: false,
//            success: function (res) {
//                $("#newContactTbody").prepend(res.html);
//                $(".tooltips").tooltip();
//                rearrangeSL('contact');
//            },
//        });
//    });
//    //remove contact row
//    $(document).on('click', '.remove-contact-row', function () {
//        $(this).parent().parent().remove();
//        rearrangeSL('contact');
//        return false;
//    });

});
</script>
