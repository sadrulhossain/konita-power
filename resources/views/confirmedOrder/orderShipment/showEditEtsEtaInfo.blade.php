<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            {!! ($request->shipment_status == 1) ? __('label.ETS_ETA_INFORMATION') : __('label.VIEW_ETS_ETA_INFO')!!}
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            @if($request->shipment_status == 1)
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'editEtsEtaInfoForm')) !!}
                {!! Form::hidden('shipment_id', $previousEtsEtaInfo->id) !!}
                {{csrf_field()}}
                <div class="row margin-bottom-20">
                    <div class="col-md-12">
                        <div class="border-styel">
                            <div class="rw-info-style">
                                <p class="rw-info-p margin-0">@lang('label.ETS_INFO')</p>
                            </div>
                            <?php $v3 = 's' . uniqid() ?>
                            @if(!empty($previousEtsInfoArr))
                            <?php $etsCounter = 0; ?>
                            @foreach($previousEtsInfoArr as  $etsKey => $etsInfo)
                            <div class="row margin-bottom-10">
                                <div class="col-md-4 col-sm-6">
                                    @if($etsCounter == 0)
                                    <label for="etsDate_{{$etsKey}}">@lang('label.ETS_DATE')<span class="text-danger"> *</span></label>
                                    @endif
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('ets_date['.$etsKey.']', $etsInfo['ets_date'], ['id'=> 'etsDate_'.$etsKey, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etsDate_{{$etsKey}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    @if($etsCounter == 0)
                                    <label for="etsNotificationDate_{{$etsKey}}">@lang('label.ETS_NOTIFICATION_DATE')<span class="text-danger"> *</span></label>
                                    @endif
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('ets_notification_date['.$etsKey.']', $etsInfo['ets_notification_date'], ['id'=> 'etsNotificationDate_'.$etsKey, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etsNotificationDate_{{$etsKey}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 margin-top-23">
                                    @if($etsCounter == 0)
                                    <button class="btn btn-inline green-haze add-new-ets-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_ETS_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <?php $etsCounter++; ?>
                            @endforeach
                            @endif
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
                            @if(!empty($previousEtaInfoArr))
                            <?php $etaCounter = 0; ?>
                            @foreach($previousEtaInfoArr as  $etaKey => $etaInfo)
                            <div class="row margin-bottom-10">
                                <div class="col-md-4 col-sm-6">
                                    @if($etaCounter == 0)
                                    <label for="etaDate_{{$etaKey}}">@lang('label.ETA_DATE')</label>
                                    @endif
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('eta_date['.$etaKey.']', $etaInfo['eta_date'], ['id'=> 'etaDate_'.$etaKey, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etaDate_{{$etaKey}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    @if($etaCounter == 0)
                                    <label for="etaNotificationDate_{{$etaKey}}">@lang('label.ETA_NOTIFICATION_DATE')</label>
                                    @endif
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('eta_notification_date['.$etaKey.']', $etaInfo['eta_notification_date'], ['id'=> 'etaNotificationDate_'.$etaKey, 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="etaNotificationDate_{{$etaKey}}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 margin-top-23">
                                    @if($etaCounter == 0)
                                    <button class="btn btn-inline green-haze add-new-eta-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_ETA_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <?php $etaCounter++; ?>
                            @endforeach
                            @endif
                            <div id="newEtaRow"></div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @else
            <!-- ets data -->
            <div class="col-md-6">
                <h4 class="border-bottom-1-green-seagreen bold">@lang('label.ETS_INFO')</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.ETS_DATE')</th>
                                <th class="text-center vcenter">@lang('label.ETS_NOTIFICATION_DATE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($prevRevEtsInfoArr))
                            <?php
                            $slets = 0;
                            ?>
                            @foreach($prevRevEtsInfoArr as $ets)
                            <tr>
                                <td class="text-center vcenter"> {{ ++$slets }}</td>
                                <td class="text-center vcenter">{!! !empty($ets['ets_date'])?Helper::formatDate($ets['ets_date']):'--' !!}</td>
                                <td class="text-center vcenter">{!! !empty($ets['ets_notification_date'])?Helper::formatDate($ets['ets_notification_date']):'--' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter" colspan="2">@lang('label.NO_ETS_INFO_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end: ets data -->

            <!-- eta data -->
            <div class="col-md-6">
                <h4 class="border-bottom-1-green-seagreen bold">@lang('label.ETA_INFO')</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.ETA_DATE')</th>
                                <th class="text-center vcenter">@lang('label.ETA_NOTIFICATION_DATE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($prevRevEtaInfoArr))
                            <?php
                            $sleta = 0;
                            ?>
                            @foreach($prevRevEtaInfoArr as $eta)
                            <tr>
                                <td class="text-center vcenter"> {{ ++$sleta }}</td>
                                <td class="text-center vcenter">{!! !empty($eta['eta_date'])?Helper::formatDate($eta['eta_date']):'--' !!}</td>
                                <td class="text-center vcenter">{!! !empty($eta['eta_notification_date'])?Helper::formatDate($eta['eta_notification_date']):'--' !!}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter" colspan="2">@lang('label.NO_ETA_INFO_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end: eta data -->
            @endif
        </div>
        <div class="modal-footer">
            @if($request->shipment_status == 1)
            <button class="btn green-seagreen tooltips vcenter" type="button" id="submitEditEtsEtaInfo">
                @lang('label.SUBMIT')
            </button>
            @endif
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
    $("#submitEditEtsEtaInfo").on("click", function (e) {
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

                var formData = new FormData($("#editEtsEtaInfoForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/confirmedOrder/updateEtsEtaInfo')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        $('#submitEditEtsEtaInfo').prop('disabled', true);
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
                        $('#submitEditEtsEtaInfo').prop('disabled', false);
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});
</script>
