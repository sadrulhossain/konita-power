<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">
                @if(!empty($request->inquiry_id))
                @lang('label.ORDER_MESSAGE')
                @else
                @lang('label.MESSAGE')
                @endif
            </h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
    <div class="modal-body">
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-300 padding-top-10 webkit-scrollbar">
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <div class="portlet-body message-body">
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
                        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','files' => true,'id'=>'setMessageFrom')) !!}
        {{csrf_field()}}
        {!! Form::hidden('user_group_id', $user->group_id, ['id' => 'userGroupId', 'class' => 'user-group-id']) !!}
        {!! Form::hidden('inquiry_id', $request->inquiry_id, ['id' => 'inquiryId', 'class' => 'inquiry-id']) !!}
        {!! Form::hidden('buyer_id', $request->buyer_id, ['id' => 'buyerId', 'class' => 'buyer-id']) !!}

        <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-1">
                @if(!empty($user->photo) && File::exists('public/uploads/user/' . $user->photo))
                <img width="30" height="30" src="{{URL::to('/')}}/public/uploads/user/{{$user->photo}}" alt="{{ $user->first_name}}"/>
                @else
                <?php $noImg = $user->group_id != 0 ? "unknown" : "no_image"; ?>
                <img width="30" height="30" src="{{URL::to('/')}}/public/img/{{$noImg}}.png" alt="{{ $user->first_name}}"/>
                @endif
            </div>
            <div class="col-md-10 col-sm-10 col-xs-10">
                {{ Form::textarea('message', null, ['id'=> 'message', 'class' => 'form-control','size' => '20x2','autocomplete' => 'off']) }}
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
                <button type="button" data-placement="top" class="btn btn-sm purple-sharp send-message tooltips" 
                        title="@lang('label.SEND')">
                    <i class="glyphicon glyphicon-send"></i>
                </button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
});
</script>