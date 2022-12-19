<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.CHOICE_OF_REDIRECTION')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
    <div class="modal-body">
        <div class="row margin-top-10 margin-bottom-10">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                <div class="alert alert-{{$countArr['total_color']}}">
                    <p>
                        <i class="fa fa-info-circle"></i>
                        @lang('label.YOU_HAVE_SOME_TYPE_NOTIFICATION', ['ref' => $countArr['ref'], 'n' =>$countArr['total_text'], 's' => $countArr['total_s']])
                    </p>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <table class="table table-borderless margin-bottom-0" border="0">
                                <tr>
                                    <td class="text-center vcenter">
                                        {!! __('label.CONFIRMED') . '&nbsp;&nbsp;<span class="badge badge-purple">' . $countArr['confirmed'] . '</span>' !!}
                                    </td>
                                    <td class="text-center vcenter">
                                        {!! __('label.ACCOMPLISHED') . '&nbsp;&nbsp;<span class="badge badge-purple">' . $countArr['accomplished'] . '</span>' !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <table class="table table-borderless margin-bottom-0" border="0">
                    <tr>
                        <td class="text-center vcenter">
                            <a class="btn btn-md btn-circle-10 padding-top-bottom-10 blue-soft tooltips" target="_new" title="@lang('label.CLICK_TO_REDIRECT_TO_CONFIRMED_ORDER')" href="{!! URL::to('/confirmedOrder') !!}">
                                @lang('label.REDIRECT_TO_CONFIRMED_ORDER')
                            </a>
                        </td>
                        <td class="text-center vcenter">
                            <a class="btn btn-md btn-circle-10 padding-top-bottom-10 green-seagreen tooltips" target="_new" title="@lang('label.CLICK_TO_REDIRECT_TO_ACCOMPLISHED_ORDER')"  href="{!! URL::to('/accomplishedOrder') !!}">
                                @lang('label.REDIRECT_TO_ACCOMPLISHED_ORDER')
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
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