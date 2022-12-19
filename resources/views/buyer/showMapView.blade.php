<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.LOCATION_OF')&nbsp;{!! $target->name !!} 
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            @if(!empty($target->gmap_embed_code))
            {!! $target->gmap_embed_code !!}
            @else
            <div class="col-md-12">
                <h4 class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"> </span> @lang('label.MAP_NOT_AVAILABLE') </h4>
            </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->