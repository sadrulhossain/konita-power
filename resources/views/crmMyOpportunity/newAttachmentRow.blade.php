<div class="form-group">
    <?php
    $v3 = 'nd' . uniqid();
    ?>
    <label class="control-label col-md-3">&nbsp;</label>
    <br/>
    <div class="col-md-7">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn green btn-file">
                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                {!! Form::file('data_sheet_file['.$opportunityId.']['.$v3.']',['id'=> 'dataSheetFile_'.$opportunityId.'_'.$v3]) !!}
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
