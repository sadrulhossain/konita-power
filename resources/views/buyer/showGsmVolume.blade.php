<div class="modal-content" id="modalContainer">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.SET_GSM_VOLUME_OF_PRODUCT')&nbsp; '{{ $product->name }}'
        </h3>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveGsmForm')) !!}
        {!! Form::hidden('buyer_id',$buyerId,['id' => 'buyerId']) !!}
        {!! Form::hidden('product_id',$productId) !!}
        {{csrf_field()}}
        <div class="form-body">
        </div>
        @if(!empty($prevBuyerGsmValues))
        <?php $counter = 0; ?>
        @foreach($prevBuyerGsmValues as $identifier => $setVolumes)
        <div class="form-group">
            <div class="col-md-12">
                <div class="row tech-data-sheet-div padding-10">
                    <div class="col-md-5">
                        <label class="control-label" for="{!! 'gsm_'.$identifier !!}">@lang('label.GSM'):<span class="text-danger">*</span></label>
                        {!! Form::text('gsm['.$identifier.']', $setVolumes['gsm'], ['id'=> 'gsm_'.$identifier, 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!}
                        <span class="text-danger">{!! $errors->first('gsm['.$identifier.']') !!}</span>
                    </div>
                    <div class="col-md-5">
                        <label class="control-label" for="{!! 'volume_'.$identifier !!}">@lang('label.VOLUME'):<span class="text-danger">*</span></label>
                        {!! Form::text('volume['.$identifier.']', $setVolumes['volume'], ['id'=> 'volume_'.$identifier, 'class' => 'form-control','autocomplete' => 'off']) !!}
                        <span class="text-danger">{!! $errors->first('volume['.$identifier.']') !!}</span>
                    </div>

                    <div class="col-md-2 margin-top-27 pull-left">
                        @if($counter == 0)
                        <button  type="button" class="btn btn-inline green-haze pull-right tooltips" id="addGsm" title="@lang('label.CLICK_HERE_TO_ADD_MORE_GSM_VOLUME')">
                            <i class="fa fa-plus"></i>
                        </button>
                        @else
                        <button class="btn btn-danger remove tooltips pull-right gsm-remove" title="@lang('label.REMOVE')" type="button">
                            <i class="fa fa-remove"></i>
                        </button>
                        @endif
                    </div>
                    <br/>
                </div>
            </div>
        </div>
        <?php $counter++; ?>
        @endforeach
        <!------- -->
        @else
        <?php
        $v3 = 'z' . uniqid();
        ?>
        <div class="form-group">
            <div class="col-md-12">
                <div class="row tech-data-sheet-div padding-10">

                    <div class="col-md-5">
                        <label class="control-label" for="{!! 'gsm_'.$v3 !!}">@lang('label.GSM'):<span class="text-danger">*</span></label>
                        {!! Form::text('gsm['.$v3.']', null, ['id'=> 'gsm_'.$v3, 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!}
                        <span class="text-danger">{!! $errors->first('gsm['.$v3.']') !!}</span>
                    </div>
                    <div class="col-md-5">
                        <label class="control-label" for="{!! 'volume_'.$v3 !!}">@lang('label.VOLUME'):<span class="text-danger">*</span></label>
                        {!! Form::text('volume['.$v3.']', null, ['id'=> 'volume_'.$v3, 'class' => 'form-control','autocomplete' => 'off']) !!}
                        <span class="text-danger">{!! $errors->first('volume['.$v3.']') !!}</span>
                    </div>
                    <div class="col-md-2 margin-top-27 pull-left">
                        <button  type="button" class="btn btn-inline green-haze pull-right tooltips" id="addGsm" title="@lang('label.CLICK_HERE_TO_ADD_MORE_GSM_VOLUME')">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <br/>
                </div>
            </div>
        </div>
        @endif
        <div class="form-group" id="newGsmDiv">
        </div>
        {!! Form::close() !!}
    </div>
    <div class="modal-footer">
        @if(!empty($prevBuyerGsmValues))
        <button class="btn red-flamingo tooltips delete-gsm" data-placement="top"  title="@lang('label.CLICK_TO_DELETE_GSM_DATA')" data-buyer-id="{{$buyerId}}" data-product-id="{{$productId}}">
            @lang('label.DELETE_GSM_DATA')
        </button>
        @endif
        @if(!empty($userAccessArr[18][7]))
        <button class="btn btn-success" id="saveGsm" type="button">
            <i class="fa fa-check"></i> @lang('label.SUBMIT')
        </button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function () {
    $(".tooltips").tooltip();

    $("#addGsm").on("click", function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('buyer.addGsmVolume')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $("#newGsmDiv").prepend(res.html);
            },
        });

    });
    //If click remove
    $(document).on('click', '.remove', function () {
        $(this).parent().parent().remove();
        return false;
    });

    //Save given GSM & VOLUME
    $(document).on('click', '#saveGsm', function (e) {
        e.preventDefault();
        var form_data = new FormData($('#saveGsmForm')[0]);
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        swal({
            title: 'Are you sure?',
            text: "@lang('label.YOU_WANT_TO_SET_VOLUME')",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Save',
            cancelButtonText: 'No, Cancel',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{route('buyer.savegsmvolume')}}",
                    type: "POST",
                    datatype: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        $('#modalContainer').hide();
                        location.hash = '#tab_5_2';
                        location.reload();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, 'Error', options);
                        } else {
                            toastr.error('Something went wrong', 'Error', options);
                        }
                        App.unblockUI();
                    }
                });
            }

        });

    });//EOF Assign Finished Goods Product

    $('.remove').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
