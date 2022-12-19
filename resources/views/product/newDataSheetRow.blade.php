<?php
$v3 = 'nd' . uniqid();
?>

    <div class="row tech-data-sheet-div">
        <div class="col-md-4">
            <label class="control-label" for="{!! 'title_'.$brandId.'_'.$v3 !!}">@lang('label.TITLE'):<span class="text-danger">*</span></label>
            {!! Form::text('title['.$brandId.']['.$v3.']', null, ['id'=> 'title_'.$brandId.'_'.$v3, 'class' => 'form-control','autocomplete' => 'off']) !!}
            <span class="text-danger">{!! $errors->first('title['.$brandId.']['.$v3.']') !!}</span>
        </div>
        <div class="col-md-6">
            <label class="control-label">@lang('label.ATTACHMENT'):</label>
            <br/>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <span class="btn green btn-file">
                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                    <span class="fileinput-exists">@lang('label.CHANGE')</span>
                    {!! Form::file('data_sheet_file['.$brandId.']['.$v3.']',['id'=> 'dataSheetFile_'.$brandId.'_'.$v3]) !!}
                </span>
                <span class="fileinput-filename"></span> &nbsp;
                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
            </div>
            <div class="clearfix">
                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.TECNICAL_DATA_SHEET_FILE_FORMAT_SIZE')
            </div>
        </div>
        <div class="col-md-1 margin-top-27">
            <button class="btn btn-danger remove tooltips" title="Remove" type="button">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.remove').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
