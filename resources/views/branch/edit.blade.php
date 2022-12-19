@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.EDIT_BRANCH')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('branch.update', $target->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'files' => true] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="countryId">@lang('label.COUNTRY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('country_id', $countryArr, null, ['class' => 'form-control js-source-states', 'id' => 'countryId']) !!}
                                <span class="text-danger">{{ $errors->first('country_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="divisionId">@lang('label.DIVISION') :</label>
                            <div class="col-md-8" id="showDivision">
                                {!! Form::select('division_id', $divisionArr, null, ['class' => 'form-control js-source-states', 'id' => 'divisionId']) !!}
                                <span class="text-danger">{{ $errors->first('division_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="districtId">@lang('label.DISTRICT') :</label>
                            <div class="col-md-8" id="showDistrict">
                                {!! Form::select('district_id', $districtArr, null, ['class' => 'form-control js-source-states', 'id' => 'districtId']) !!}
                                <span class="text-danger">{{ $errors->first('district_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="thanaId">@lang('label.THANA') :</label>
                            <div class="col-md-8" id="showThana">
                                {!! Form::select('thana_id', $thanaArr, null, ['class' => 'form-control js-source-states', 'id' => 'thanaId']) !!}
                                <span class="text-danger">{{ $errors->first('thana_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="locationDetails">@lang('label.LOCATION_DETAILS') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('location_details', null, ['id'=> 'locationDetails', 'class' => 'form-control','size' => '30x5']) }}
                                <span class="text-danger">{{ $errors->first('location_details') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="branchContactNo">@lang('label.BRANCH_CONTACT_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('branch_contact_no',null, ['id'=> 'branchContactNo', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('branch_contact_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/branch'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //country wise division
        $(document).on('change', '#countryId', function () {
            var countryId = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '{{URL::to("branch/getDivisionToEdit/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: countryId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDivision').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });
        
        //division wise district
        $(document).on('change', '#divisionId', function () {
            var divisionId = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '{{URL::to("branch/getDistrictToEdit/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    division_id: divisionId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDistrict').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });
        
        //division wise district
        $(document).on('change', '#districtId', function () {
            var districtId = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '{{URL::to("branch/getThanaToEdit/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    district_id: districtId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showThana').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });
    });
</script>

@stop