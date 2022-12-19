@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-sticky-note-o"></i>
                @lang('label.PI_GENERATE')
            </div>

        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        @lang('label.NO_FORMAT_AVAILABLE') 
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
@stop
