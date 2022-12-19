@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_BUYER_FACTORY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['files'=> true, 'class' => 'form-horizontal', 'id' => 'buyerFactoryEditForm'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.BASIC_DATA')</h3>
                        <div class="col-md-offset-1 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('buyer_id', $buyerArr, null, ['class' => 'form-control js-source-states', 'id' => 'buyerId']) !!}
                                    <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="address">@lang('label.ADDRESS') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {{ Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off']) }}
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="primaryFactory">@lang('label.PRIMARY_FACTORY') :</label>
                                <div class="col-md-8">
                                    {!! Form::checkbox('primary_factory',1, null , ['id'=> 'primaryFactory','class' => 'make-switch','data-on-text'=> "Yes",'data-off-text'=>"No"]) !!} 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="gMapEmbedCode">@lang('label.GMAP_EMBED_CODE') :</label>
                                <div class="col-md-8">
                                    {{ Form::textarea('gmap_embed_code', null, ['id'=> 'gMapEmbedCode', 'class' => 'form-control','size' => '30x5','autocomplete' => 'off','placeholder' => __('label.GMAP_EMBED_CODE_PLACEHOLDER')]) }}
                                    <span class="text-danger">{{ $errors->first('gmap_embed_code') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- START:: Contact Person Data -->
                    <div class="col-md-12">
                        <h3 class="form-section title-section bold">@lang('label.CONTACT_PERSON')</h3>
                        <div class="form-body">
                            <div class="form-group">
                                {!! Form::hidden('add_btn', '1', ['id' => 'addBtn']) !!}
                                <div class="col-md-1">
                                    <button  type="button" class="btn purple-soft add-contact-person tooltips" title="@lang('label.CLICK_HERE_TO_ADD_MORE_CONTACT_PERSON')">
                                        @lang('label.ADD_MORE_CONTACT_PERSON') &nbsp;<i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="" id="newContactPerson"> </div>
                                <?php
                                $v4 = 'z' . uniqid();
                                ?>
                                @if(!empty($prevContactPersonArr))
                                <?php
                                $count = 1;
                                ?>
                                @foreach($prevContactPersonArr as $identifier => $contactPerson)
                                <div class="col-md-12 contact-person-div">
                                    <div class="row">
                                        @if($count > 1)
                                        <button class="btn btn-danger remove tooltips pull-right block-remove" data-count="{{ $count }}" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button" 
                                                id="deleteBtn_"{{$count}}>
                                            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                               <div class="col-md-3 contact-div">
                                                    {!! Form::text('contact_name['.$identifier.']', $contactPerson['name'], ['id'=> 'contactName'.$identifier,'class' => 'focus-input']) !!} 
                                                    <label class="floating-label" id="spanName_{{$identifier}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::select('designation_id['.$identifier.']',$designationList, $contactPerson['designation_id'], ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$identifier,'data-width' => '100%']) !!} 
                                                    <label class="floating-label" id="spanDeg_{{$identifier}}">@lang('label.DESIGNATION') </label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::email('contact_email['.$identifier.']', $contactPerson['email'], ['id'=> 'contactEmail'.$identifier,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanEmail_{{$identifier}}">@lang('label.EMAIL') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::textarea('special_note['.$identifier.']', $contactPerson['note'], ['id'=> 'specialNote'.$identifier, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!} 
                                                    <label class="floating-label" id="spanNote_{{$identifier}}">@lang('label.CONTACT_NOTE')</label>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                @if(is_array($contactPerson['phone']))
                                                <?php
                                                $i = 1;
                                                ?>
                                                @foreach($contactPerson['phone'] as $key => $contact) 
                                                @if(!empty($contact))
                                                @if($i==1)
                                                <div class="col-md-3  contact-div">
                                                    {!! Form::text('contact_phone['.$identifier.']['.$key.']', $contact, ['id'=> 'contactPhone'.$identifier,'class' => 'integer-only focus-input']) !!} 
                                                    <label class="floating-label" id="spanPhone_{{$identifier}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-1 margin-top-10">
                                                    <button class="btn btn-inline green-haze add-phone-number tooltips" data-key="{{$identifier}}" data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                @else
                                                <div>
                                                    <div class="col-md-3  contact-div">
                                                        {!! Form::text('contact_phone['.$identifier.']['.$key.']', $contact, ['id'=> 'contactPhone'.$identifier,'class' => 'integer-only focus-input']) !!} 
                                                        <label class="floating-label" id="spanPhone_{{$identifier}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>

                                                    </div>
                                                    <div class="col-md-1 margin-top-10">
                                                        <button class="btn btn-inline btn-danger remove-phone-number-row  tooltips"  title="Remove" type="button">
                                                            <i class="fa fa-remove"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endif
                                                <?php
                                                $i++;
                                                ?>
                                                @endif
                                                @endforeach
                                                @else
                                                <div class="col-md-3  contact-div">
                                                    {!! Form::text('contact_phone['.$identifier.']['.$v4.']',!empty($contactPerson['phone'])?$contactPerson['phone']:null, ['id'=> 'contactPhone'.$identifier,'class' => 'integer-only focus-input']) !!} 
                                                    <label class="floating-label" id="spanPhone_{{$identifier}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-1 margin-top-10">
                                                    <button class="btn btn-inline green-haze add-phone-number tooltips" data-key="{{$identifier}}" data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                @endif
                                                <div id="addPhoneNumberRow{{$identifier}}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $count++;
                                ?>
                                @endforeach
                                @else
                                <div class="">
                                    <?php
                                    $v3 = 'z' . uniqid();
                                
                                    ?>
                                    <div class="col-md-12 contact-person-div">
                                        <!--                                    <div class="row">
                                                                                <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
                                                                                    &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
                                                                                </button>
                                                                            </div>-->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::text('contact_name['.$v3.']', null, ['id'=> 'contactName'.$v3,'class' => 'focus-input']) !!} 
                                                    <label class="floating-label" id="spanName_{{$v3}}">@lang('label.NAME') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::select('designation_id['.$v3.']',$designationList, null, ['class' => 'form-control designation-id js-source-states', 'id' => 'designationId'.$v3,'data-width' => '100%']) !!} 
                                                    <label class="floating-label" id="spanDeg_{{$v3}}">@lang('label.DESIGNATION') </label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::email('contact_email['.$v3.']', null, ['id'=> 'contactEmail'.$v3,'class' => 'focus-input']) !!}
                                                    <label class="floating-label" id="spanEmail_{{$v3}}">@lang('label.EMAIL') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-3 contact-div">
                                                    {!! Form::textarea('special_note['.$v3.']', null, ['id'=> 'specialNote'.$v3, 'class' => 'focus-input', 'autocomplete' => 'off', 'size' => '40x1' ]) !!} 
                                                    <label class="floating-label" id="spanNote_{{$v3}}">@lang('label.SPECIAL_NOTE')</label>
                                                </div>

                                            </div>
                                                <br/>
                                                <div class="row">
                                                <div class="col-md-3  contact-div">
                                                    {!! Form::text('contact_phone['.$v3.']['.$v4.']', null, ['id'=> 'contactPhone'.$v3,  'class' => 'integer-only focus-input']) !!} 
                                                    <label class="floating-label" id="spanPhone_{{$v3}}">@lang('label.PHONE') <span class="text-danger"> *</span></label>
                                                </div>
                                                <div class="col-md-1 margin-top-10">
                                                    <button class="btn btn-inline green-haze add-phone-number tooltips" data-key="{{$v3}}" data-placement="right" title="@lang('label.ADD_NEW_PHONE_NUMBER')" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div id="addPhoneNumberRow{{$v3}}"></div>

                                            </div>
                                                <br/>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END:: Contact Person Data -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="submitEditBuyerFact">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/buyerFactory'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", ".add-contact-person", function () {
            $.ajax({
                url: "{{ route('buyerFactory.editContactPerson') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newContactPerson").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });

        //Function for Update Supplier Data
        $(document).on("click", "#submitEditBuyerFact", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#buyerFactoryEditForm')[0]);
            $.ajax({
                url: "{{ route('buyerFactory.update') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    // similar behavior as an HTTP redirect
                    setTimeout(
                            window.location.replace('{{ route("buyerFactory.index")}}'
                                    ), 7000);

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

        });


        $(document).on('click', '.remove', function () {
            var rowCount = $(this).data('count');
            if (rowCount > 1) {
                $(this).parent().parent().remove();
                return false;
            }
        });

        $("#primaryFactory").bootstrapSwitch({
            offColor: 'danger'
        });
        $('#primaryFactory').on('switchChange.bootstrapSwitch', function () {
            var buyerId = $('#buyerId').val();
            if ($(this).prop("checked") == true) {
                $.ajax({
                    url: "{{ route('buyerFactory.buyerPrimaryFactoryEdit') }}",
                    type: "POST",
                    data: {
                        buyer_id: buyerId,
                    },
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        swal({
                            title: res['name'] + " @lang('label.IS_ALREADY_ADDED_AS_PRIMARY_FACTORY_UNDER_THIS_BUYER')",
                            text: "@lang('label.DO_YOU_WANT_TO_OVERWRITE_IT')",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "@lang('label.YES_OVERWRITE_IT')",
                            closeOnConfirm: false
                        });
                    },
                });
            }

        });
         //auto select name as buyer 
       $(document).on("change", "#buyerId", function () {
        var buyerId = $('#buyerId').val(); 
        $.ajax({
                url: "{{ route('buyerFactory.getBuyerName') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                 data: {
                        buyer_id: buyerId,
                    },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#name").val(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });
        
          //add multiple phone number
        $(document).on("click", ".add-phone-number", function () {
            var key = $(this).attr("data-key");

            $.ajax({
                url: "{{ URL::to('buyerFactory/addPhoneNumber')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    key: key,
                },
                success: function (res) {
                    $("#addPhoneNumberRow"+key).prepend(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        
        //remove  row
        $('.remove-phone-number-row').on('click', function () {
            $(this).parent().parent().remove();
            return false;
        });

    });
</script>
@stop