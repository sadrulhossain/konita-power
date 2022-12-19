@if(!empty($finalArr))
<div class="mt-timeline-2">
    <ul class="mt-container">
        @foreach($finalArr as $date => $infoArr)
        <?php
        $i = 0;
        ?>
        @foreach($infoArr as $history)
        <?php
        $color = !empty($history['user_group_id']) ? 'green-seagreen' : 'blue-steel';
        $side = !empty($history['user_group_id']) ? 'left' : 'right';
        $bgColor = 'bg-' . $color . ' bg-font-' . $color;
        $bgFont = 'font-' . $color;
        $sideClass = 'message-block-' . $side;
        $sideMarginClass = 'message-block-margin-' . $side;
        ?>

        <li class="mt-item {{$sideClass}} padding-bottom-20">
            <div class="mt-timeline-content">
                <div class="mt-content-container track-history {{$sideMarginClass}} message-history">
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
                        <?php $msgr = $userArr[$updatedBy]; ?>
                        <div class="portlet-title portlet-title-border">
                            <div class="caption">
                                <div class="row">
                                    <div class="col-md-{{$col1}} col-sm-{{$col1}} col-xs-{{$col1}}">
                                        @if(!empty($msgr['photo']) && File::exists('public/uploads/user/'.$msgr['photo']))
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/uploads/user/{{$msgr['photo']}}" alt="{{ $msgr['full_name'] }}"/>
                                        @else
                                        <?php $noImg = $history['user_group_id'] != 0 ? "unknown" : "no_image"; ?>
                                        <img width="30" height="30" src="{{URL::to('/')}}/public/img/{{$noImg}}.png" alt="{{ $msgr['full_name'] }}"/>
                                        @endif
                                    </div>
                                    <div class="col-md-{{$col2}} col-sm-{{$col2}} col-xs-{{$col2}}">
                                        <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $msgr['full_name'] !!}</span>
                                        @if(!empty($msgr['designation']))
                                        <span class="caption-subject {{ $bgFont }} font-size-10">{!! $msgr['designation'] !!}</span>
                                        @endif
                                        <br/>
                                        @if(!empty($history['updated_at']))
                                        <i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} font-size-14">{!! Helper::formatDateTime($history['updated_at']) !!}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                        @endif
                        <div class="portlet-body portlet-body-padding">
                            <p class="track-text  {{ $bgFont }} font-size-14">
                                {!! $history['message'] !!}
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
    </ul>
</div>
@else
<div class="col-md-12 text-center">
    <div class="alert alert-danger">
        <p>
            <i class="fa fa-warning"></i>
            @lang('label.NO_MESSAGE_IS_AVAILABLE')
        </p>
    </div>
</div>
@endif