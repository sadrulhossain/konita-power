<?php
$v3 = 'z' . uniqid();
  $v4 = 'z' . uniqid();
?>
<div class="col-md-12 contact-person-div">
    <div class="row">
        <button class="btn btn-danger remove tooltips pull-right block-remove" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button">
            &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
        </button>
    </div>
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
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('click', '.remove', function () {
        $(this).parent().parent().remove();
        return false;
    });
    
//         //add multiple phone number
//        $(document).on("click", ".add-phone", function () {
//            var key = $(this).attr("data-key");
//
//            $.ajax({
//                url: "{{ URL::to('buyer/addPhoneNumber')}}",
//                type: "POST",
//                dataType: "json",
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                data: {
//                    key: key,
//                },
//                success: function (res) {
//                    $("#addPhoneNumber"+key).prepend(res.html);
//                },
//                error: function (jqXhr, ajaxOptions, thrownError) {
//                }
//            }); //ajax
//        });
});
</script>