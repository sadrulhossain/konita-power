@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.BUYER_FACTORY_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[19][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('buyerFactory/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_BUYER_FACTORY')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'buyerFactory/filter','class' => 'form-horizontal')) !!}
                    {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="search">@lang('label.NAME')</label>
                            <div class="col-md-9">
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
                            <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER')</label>
                            <div class="col-md-8">
                                {!! Form::select('buyer_id',  $buyerArr, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
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


                    <div class="col-md-1">
                        <div class="form  text-right">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.BUYER')</th>
                            <th class="text-center vcenter">@lang('label.NAME')</th>
                            <th class="text-center vcenter">@lang('label.ADDRESS')</th>
                            <th class="text-center vcenter">@lang('label.PRIMARY_FACTORY')</th>
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
                            <td class="vcenter">{{ $target->buyer }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter">{{ $target->address }}</td>
                            <td class="text-center vcenter">
                                @if($target->primary_factory == '1')
                                <span class="label label-sm label-success">@lang('label.YES')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[19][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('buyerFactory/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[19][4]))
                                    {{ Form::open(array('url' => 'buyerFactory/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @if(!empty($userAccessArr[19][5]))
                                    <button class="btn btn-xs btn-info tooltips vcenter" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="@lang('label.SHOW_CONTACT_PERSON_DETAILS')" data-factory-id = {{$target->id }}>
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    @endif

                                    @if(!empty($userAccessArr[19][1]))
                                    <button class="btn btn-xs purple tooltips vcenter" href="#mapView" id="mapModal"  data-toggle="modal" title="@lang('label.SHOW_MAP_ON_ADDRESS')" data-factory-id = {{$target->id }}>
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_BUYER_FACTORY_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="contactPersonDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetailsContactPerson">
        </div>
    </div>
</div>

<div class="modal fade" id="mapView" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="mapBlock">

        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#contactPersonData", function(e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var bfactoryId = $(this).data('factory-id');
            $.ajax({
                url: "{{ route('buyerFactory.detailsOfContactPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    factory_id: bfactoryId
                },
                beforeSend: function() {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function(res) {
                    $("#showDetailsContactPerson").html(res.html);
                    App.unblockUI();
                },
                error: function(jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
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
            }); //ajax
        });

        //Show Google Map based on Address
        $(document).on("click", "#mapModal", function(e) {
            e.preventDefault();
            var bfactoryId = $(this).data('factory-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ route('buyerFactory.locationView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    factory_id: bfactoryId
                },
                beforeSend: function() {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function(res) {
                    $("#mapBlock").html(res.html);
                    App.unblockUI();
                },
                error: function(jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
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
            }); //ajax

        }); // EOF -- Click on Modal Button

    });
</script>    
@stop