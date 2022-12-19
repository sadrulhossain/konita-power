@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-home"></i>@lang('label.BRANCH_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[6][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('branch/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_BRANCH')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'branch/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                            <datalist id="search">
                                @if(!empty($nameArr))
                                @foreach($nameArr as $name)
                                <option value="{{$name->name}}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="country">@lang('label.COUNTRY')</label>
                        <div class="col-md-8">
                            {!! Form::select('country',  $country, Request::get('country'), ['class' => 'form-control js-source-states','id'=>'country']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="division">@lang('label.DIVISION')</label>
                        <div class="col-md-8">
                            {!! Form::select('division',  $division, Request::get('division'), ['class' => 'form-control js-source-states','id'=>'division']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="district">@lang('label.DISTRICT')</label>
                        <div class="col-md-8">
                            {!! Form::select('district',  $district, Request::get('district'), ['class' => 'form-control js-source-states','id'=>'district']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="thana">@lang('label.THANA')</label>
                        <div class="col-md-8">
                            {!! Form::select('thana',  $thana, Request::get('thana'), ['class' => 'form-control js-source-states','id'=>'thana']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form ">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.NAME')</th>
                            <th class="text-center vcenter">@lang('label.COUNTRY')</th>
                            <th class="text-center vcenter">@lang('label.DIVISION')</th>
                            <th class="text-center vcenter">@lang('label.DISTRICT')</th>
                            <th class="text-center vcenter">@lang('label.THANA')</th>
                            <th class="text-center vcenter" colspan="2">@lang('label.LOCATION_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.BRANCH_CONTACT_NO')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">{{ $target->name }}</td>
                            <td class="text-center vcenter">{{ (!empty($countryList)&& !empty($target->country_id))?$countryList[$target->country_id]: '' }}</td>
                            <td class="text-center vcenter">{{ (!empty($divisionList)&& !empty($target->division_id))?$divisionList[$target->division_id]: '' }}</td>
                            <td class="text-center vcenter">{{ (!empty($districtList)&& !empty($target->district_id))?$districtList[$target->district_id]: '' }}</td>
                            <td class="text-center vcenter">{{ (!empty($thanaList)&& !empty($target->thana_id))?$thanaList[$target->thana_id]: '' }}</td>
                            <td class="text-center vcenter" colspan="2">{{ $target->location_details }}</td>
                            <td class="text-center vcenter">{{ $target->branch_contact_no }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[6][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('branch/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[6][4]))
                                    {{ Form::open(array('url' => 'branch/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="11">@lang('label.NO_BRANCH_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
@stop