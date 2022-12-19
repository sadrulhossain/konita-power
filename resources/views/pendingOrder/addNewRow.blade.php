<div class="border-styel  margin-bottom-10">
    <?php $v3 = 'b' . uniqid() ?>
    <div class="row margin-bottom-10">
        <div class="col-md-10 form-body">

            <div class="form-group">
                <label class="control-label col-md-4" for="lsd_{{$v3}}">@lang('label.REVISED_LSD') :<span class="text-danger"> *</span></label>
                <div class="col-md-8 margin-bottom-10">
                    <div class="input-group date datepicker2">
                        {!! Form::text('lsd['.$v3.']', date('d F Y'), ['id'=> 'lsd_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                        <span class="input-group-btn">
                            <button class="btn default reset-date" type="button" remove="lsd_{{$v3}}">
                                <i class="fa fa-times"></i>
                            </button>
                            <button class="btn default date-set" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="text-danger">{{ $errors->first('lsd') }}</span>
                    </div>
                </div>
            </div> 
            <div class="form-group">
                <label class="control-label col-md-4" for="lc_expiry_date_{{$v3}}">@lang('label.LC_EXPIRY_DATE') :<span class="text-danger"> *</span></label>
                <div class="col-md-8">
                    <div class="input-group date datepicker2">
                        {!! Form::text('lc_expiry_date['.$v3.']', date('d F Y'), ['id'=> 'lc_expiry_date_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!} 
                        <span class="input-group-btn">
                            <button class="btn default reset-date" type="button" remove="lc_expiry_date_{{$v3}}">
                                <i class="fa fa-times"></i>
                            </button>
                            <button class="btn default date-set" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="text-danger">{{ $errors->first('lc_expiry_date') }}</span>
                    </div>
                </div>
            </div> 

        </div>
        <div class="col-md-2">
            <button class="btn btn-inline btn-danger remove-lsd-row lsd-row-icon tooltips"  title="Remove" type="button">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>


</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove lsd row
    $('.remove-lsd-row').on('click', function () {
        $(this).parent().parent().parent().remove();
        return false;
    });
});
</script>
