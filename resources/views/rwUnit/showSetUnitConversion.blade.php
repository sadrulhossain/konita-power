<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_UNIT_CONVERSION')
        </h4>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveUnitConversionForm')) !!}
        {!! Form::hidden('base_unit_id',$request->unit_id) !!}
        {{csrf_field()}}
        <div class="form-body">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="vcenter">{!! $unitList[$request->unit_id] !!}</th>
                                <th class="vcenter text-center width-150">1</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-md-offset-2 col-md-8 text-center">
                <span class=""><i class="fa fa-angle-double-down bold"></i></span>
            </div>
            <div class="col-md-offset-2 col-md-8 margin-top-10">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            @if(!empty($unitList))
                            @foreach($unitList as $unitId => $unit)
                            @if($unitId != $request->unit_id)
                            <tr>
                                <td class="vcenter">{!! $unit !!}</td>
                                <td class="vcenter text-center width-150">
                                    {!! Form::text('conv_rate['.$unitId.']', !empty($prevDataArr[$unitId]) ? $prevDataArr[$unitId] : null, ['id'=> 'convRate_'.$unitId, 'class' => 'form-control text-input-width-100-per integer-decimal-only text-right']) !!}
                                    {!! Form::hidden('conv_unit['.$unitId.']', $unit) !!}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        {!! Form::close() !!}
        <!-- END FORM-->
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="saveUnitConversion">@lang('label.CONFIRM_SUBMIT')</button>

        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    //save technical data sheets for product
    $("#saveUnitConversion").on("click", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You can not undo this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, save',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: true,
            closeOnCancel: true},
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var options = {
                            closeButton: true,
                            debug: false,
                            positionClass: "toast-bottom-right",
                            onclick: null,
                        };

                        // Serialize the form data
                        var formData = new FormData($('#saveUnitConversionForm')[0]);
                        $.ajax({
                            url: "{{URL::to('rwUnit/setConversion')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
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
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                } else {
                                    toastr.error('Error', 'Something went wrong', options);
                                }
                                App.unblockUI();
                            }
                        });
                    }
                });

    });

});
</script>