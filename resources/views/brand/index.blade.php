@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.BRAND_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[11][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('brand/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_BRAND')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'brand/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
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
                        <label class="control-label col-md-4" for="origin">@lang('label.ORIGIN')</label>
                        <div class="col-md-8">
                            {!! Form::select('origin',  $originArr, Request::get('origin'), ['class' => 'form-control js-source-states','id'=>'origin']) !!}
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
                    <div class="form">
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
                        <tr class="center info">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.LOGO')</th>
                            <th class="text-center vcenter">@lang('label.NAME')</th>
                            <th class="text-center vcenter">@lang('label.ORIGIN')</th>
                            <th width="40%">@lang('label.DESCRIPTION')</th>
                            <th class="text-center vcenter">@lang('label.CERTIFICATE')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
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
                            <td class=" text-center vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">
                                @if(!empty($target->logo))
                                <img class="pictogram-min-space tooltips" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $target->logo }}" alt="{{ $target->name}}" title="{{ $target->name }}"/>
                                @else 
                                <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter">{!! $target->origin !!}</td>
                            <td class="vcenter">{{ $target->description }}</td>

                            <td class="td-actions text-center vcenter">

                                <div class="width-inherit">
                                    @if(!empty($prevCertificateArr[$target->id]))
                                    @foreach($prevCertificateArr[$target->id] as $key => $item)
                                    <label>&nbsp;</label>
                                    <a href="{{URL::to('public/uploads/brandCertificate/'.$item)}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_CERTIFICATE')" target="_blank">
                                        <i class="" aria-hidden="true"><img src="{{URL::to('/')}}/public/img/certificate/{{$certificateArr[$key]}}" width="20px" height="20px"></i>
                                    </a>
                                    @endforeach
                                    @else
                                    <span class="label label-warning purple-stripe">
                                        @lang('label.N/A')
                                    </span>
                                    @endif
                                </div>

                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="vcenter">
                                <div class="text-center">
                                    @if(!empty($userAccessArr[11][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('brand/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[11][4]))
                                    {{ Form::open(array('url' => 'brand/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
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
                            <td colspan="9">@lang('label.NO_BRAND_FOUND')</td>
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