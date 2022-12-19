<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ADD_NEW_SHIPMENT_INFO')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'addEtsEtaForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}
                {{csrf_field()}}
                <div class="row margin-bottom-20">
                    <div class="col-md-12">
                        <div class="border-styel">
                            <div class="rw-info-style">
                                <p class="rw-info-p margin-0">@lang('label.ETS_INFO')</p>
                            </div>
                            <?php $v3 = 's' . uniqid() ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-4 col-sm-6">
                                    <label for="etsDate_{{$v3}}">@lang('label.ETS_DATE')<span class="text-danger"> *</span></label>
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('ets_date['.$v3.']', null, ['id'=> 'etsDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etsDate_{{$v3}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label for="etsNotificationDate_{{$v3}}">@lang('label.ETS_NOTIFICATION_DATE')<span class="text-danger"> *</span></label>
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('ets_notification_date['.$v3.']', null, ['id'=> 'etsNotificationDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etsNotificationDate_{{$v3}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 margin-top-23">
                                    <button class="btn btn-inline green-haze add-new-ets-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_ETS_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="newEtsRow"></div>
                        </div>
                    </div>
                </div>
                <div class="row margin-bottom-20">
                    <div class="col-md-12">
                        <div class="border-styel">
                            <div class="rw-info-style">
                                <p class="rw-info-p margin-0">@lang('label.ETA_INFO')</p>
                            </div>
                            <?php $v3 = 'a' . uniqid() ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-4 col-sm-6">
                                    <label for="etaDate_{{$v3}}">@lang('label.ETA_DATE')</label>
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('eta_date['.$v3.']', null, ['id'=> 'etaDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etaDate_{{$v3}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label for="etaNotificationDate_{{$v3}}">@lang('label.ETA_NOTIFICATION_DATE')</label>
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('eta_notification_date['.$v3.']', null, ['id'=> 'etaNotificationDate_'.$v3, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etaNotificationDate_{{$v3}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 margin-top-23">
                                    <button class="btn btn-inline green-haze add-new-eta-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_ETA_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="newEtaRow"></div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn green-seagreen tooltips vcenter" type="button" id="submitAddEtsEta">
                @lang('label.SUBMIT')
            </button>
            <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>


<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    //add new ets row
    $(".add-new-ets-row").on("click", function (e) {
        e.preventDefault();
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


        $.ajax({
            url: "{{URL::to('confirmedOrder/newEtsRow')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $("#newEtsRow").append(res.html);
            },
        });
    });
    //remove ets row
    $('.remove-ets-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });

    //add new ets row
    $(".add-new-eta-row").on("click", function (e) {
        e.preventDefault();
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


        $.ajax({
            url: "{{URL::to('confirmedOrder/newEtaRow')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                $("#newEtaRow").append(res.html);
            },
        });
    });
    //remove ets row
    $('.remove-eta-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });


    //cancel order
    $("#submitAddEtsEta").on("click", function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Confirm",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
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

                var formData = new FormData($("#addEtsEtaForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/confirmedOrder/saveEtsEtaInfo')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('#submitAddEtsEta').prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
                        App.unblockUI();
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
                        $('#submitAddEtsEta').prop('disabled', false);
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});
</script>
