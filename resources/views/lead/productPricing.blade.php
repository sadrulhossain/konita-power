{!! Form::hidden('target_selling_price',$targetSellingPrice , ['id' => 'targetSellingPrice']) !!}
{!! Form::hidden('minimum_selling_price',$minimumSellingPrice , ['id' => 'minimumSellingPrice']) !!}
<span class="bold">@lang('label.TARGET_PRICE'):&nbsp;{{$targetSellingPrice }}&nbsp;|</span>
<span class="bold">@lang('label.MINIMUM_PRICE'):&nbsp;{{$minimumSellingPrice }}&nbsp;|</span>
<?php
$color = '';
if(!empty($status)){
    if($status<0){
          $color = 'text-danger';
    }
} ?>
<span class="bold" >@lang('label.STATUS'):&nbsp;<span class="{{$color}}" id="priceStatus" >{{$status}}</span></span>