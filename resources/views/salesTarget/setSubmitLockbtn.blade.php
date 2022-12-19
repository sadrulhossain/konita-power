<?php
$disabled = '';
if (!empty($salesTarget) && $salesTarget->lock_status == '1') {
    $disabled = 'disabled';
}
?>

@if(!$productList->isEmpty())
<button type="button" class="btn btn-primary" id="saveSalesTarget" {{ $disabled }}>@lang('label.CONFIRM_SUBMIT')</button>
@if(!empty($userAccessArr[20][10]))
<button type="button" class="btn purple-sharp" id="lockSalesTarget" {{ $disabled }}>@lang('label.SAVE_AND_LOCK')</button>
@endif
@endif
<button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>