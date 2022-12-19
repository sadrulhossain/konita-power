@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-hourglass"></i>@lang('label.CREATE_FOLLOWUP_STATUS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'followupStatus','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete'=>'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="color">@lang('label.COLOR') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::select('color', $colorList, null, ['class' => 'form-control js-source-states', 'id' => 'color']) !!} 
                                <span class="text-danger">{{ $errors->first('color') }}</span>
                            </div>
                            <div class="col-md-1" id="colorDiv" style="height: 35px; width: 35px"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="icon">@lang('label.ICON') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::select('icon', $iconList, null, ['class' => 'form-control js-source-states', 'id' => 'icon']) !!} 
                                <span class="text-danger">{{ $errors->first('icon') }}</span>
                            </div>
                            <div class="col-md-1" style="font-size: 30px; padding-left: 5px;">
                                <i class="" id="iconsnap"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/followupStatus'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(function () {
        $(document).on("change", "#color", function () {
            var color = $(this).val();

            if (color == '0') {
                $("#colorDiv").removeClass().addClass("col-md-1");
            } else {
                $("#colorDiv").removeClass().addClass("col-md-1 bg-" + color);
            }
        });
        $(document).on("change", "#icon", function () {
            var icon = $(this).val();

            if (icon == '0') {
                $("#iconsnap").removeClass();
            } else {
                $("#iconsnap").removeClass().addClass(icon);
            }
        });
    });

</script>
@stop