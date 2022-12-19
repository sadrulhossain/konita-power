@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.BUYER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[18][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('buyer/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_BUYER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">

            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'buyer/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
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
                        <label class="control-label col-md-4" for="buyerCatId">@lang('label.CLASSIFICATION')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer_category_id',  $buyerCatArr, Request::get('buyer_category_id'), ['class' => 'form-control js-source-states','id'=>'buyerCatId']) !!}
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="countryId">@lang('label.COUNTRY')</label>
                        <div class="col-md-8">
                            {!! Form::select('country_id',  $countryList, Request::get('country_id'), ['class' => 'form-control js-source-states','id'=>'countryId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="divisionId">@lang('label.DIVISION')</label>
                        <div class="col-md-8">
                            {!! Form::select('division_id',  $divisionList, Request::get('division_id'), ['class' => 'form-control js-source-states','id'=>'divisionId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="salesStatusId">@lang('label.SALES_STATUS')</label>
                        <div class="col-md-8">
                            {!! Form::select('sales_status_id', $salesStatusList, Request::get('sales_status_id'), ['class' => 'form-control js-source-states', 'id' => 'salesStatusId']) !!}
                            <span class="text-danger">{{ $errors->first('sales_status_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center">
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
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.LOGO')</th>
                            <th class="vcenter">@lang('label.BUYER_CATEGORY')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.COUNTRY')</th>
                            <th class="vcenter">@lang('label.DIVISION')</th>
                            <th class="vcenter">@lang('label.CODE')</th>
                            <th class="vcenter">@lang('label.HEAD_OFFICE')</th>
                            <th class="vcenter">@lang('label.PRIMARY_FACTORY')</th>
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
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">
                                @if (!empty($target->logo))
                                <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$target->logo}}" width="40" height="40"/>
                                @else
                                <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                                @endif
                                @if(!empty($target->show_all_brands))
                                <i class="fa fa-asterisk font-red tooltips" title="@lang('label.ALLOWED_TO_VIEW_ALL_BRANDS')"></i>
                                @endif
                                
                            </td>
                            <td class="vcenter">{{ $target->buyer_category }}</td>
                            <td class="vcenter">
                                @if(!empty($userAccessArr[18][5]))
                                <a class="tooltips" title="@lang('label.CLICK_TO_VIEW_PROFILE')"
                                   href="{{ URL::to('buyer/' . $target->id . '/profile'.Helper::queryPageStr($qpArr)) }}">
                                    {{ $target->name }}
                                </a>
                                @else
                                {{ $target->name }}
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->country }}</td>
                            <td class="vcenter">{{ $target->division }}</td>
                            <td class="vcenter">{{ $target->code }}</td>
                            <td class="vcenter">{{ $target->head_office_address }}</td>
                            <td class="vcenter">{{ $factoryAddressList[$target->id] ?? ''}}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[18][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('buyer/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[18][4]))
                                    {{ Form::open(array('url' => 'buyer/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @if(!empty($userAccessArr[18][5]))
                                    <button class="btn btn-xs btn-info tooltips vcenter" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="@lang('label.SHOW_CONTACT_PERSON_DETAILS')" data-buyer-id = {{$target->id }}>
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    @endif

                                    @if(!empty($userAccessArr[18][1]))
                                    <button class="btn btn-xs purple tooltips vcenter" href="#mapView" id="mapModal"  data-toggle="modal" title="@lang('label.SHOW_MAP_ON_ADDRESS')" data-buyer-id = {{$target->id }}>
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[18][8]))
                                    <?php
                                    $disabled = 'cursor-default';
                                    $href = '';
                                    $btnName = '';
                                    $btnType = 'type="button"';
                                    $btnColor = 'grey-mint';
                                    $btnLabel = __('label.NO_PRODUCT_ASSIGNED_YET');
                                    if (!empty($hasRelatedProductArr)) {
                                        if (array_key_exists($target->id, $hasRelatedProductArr)) {
                                            if (sizeOf($hasMachineArr) > 0) {
                                                $disabled = '';
                                                $btnName = 'set-machine-type';
                                                $href = '#modalSetMachineType';
                                                $btnType = '';
                                                $btnColor = 'green-sharp';
                                                $btnLabel = __('label.CLICK_TO_SET_MACHINE_TYPE');
                                            } else {
                                                $btnLabel = __('label.NO_PRODUCT_HAS_MACHINE');
                                            }
                                        }
                                    }
                                    ?>
                                    <button {{$btnType}} class="btn btn-xs {{$btnColor}} {{$disabled}} tooltips {{$btnName}} vcenter" href="{{$href}}" id=""  data-toggle="modal" title="{{$btnLabel}}" data-buyer-id="{{$target->id}}">
                                        <i class="fa fa-wrench"></i>
                                    </button>
                                    <a class="btn btn-xs btn-warning tooltips vcenter " title="@lang('label.CLICK_HERE_TO_MAKE_BUYER_ANALYTICS')"
                                       href="{{ URL::to('buyer/' . $target->id . '/manageBuyer'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                    @endif
                                    @if(empty($salesPersonToBuyerCountList[$target->id]))
                                    @if(!empty($userAccessArr[17][7]))
                                    <button class="btn btn-xs blue-hoki tooltips vcenter assign-sales-person assign-sales-person-{{$target->id}}"  
                                            title="@lang('label.CLICK_TO_ASSIGN_SALES_PERSON')" href="#modalAssignSalesPerson" data-id="{!! $target->id !!}" data-toggle="modal">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_BUYER_FOUND')</td>
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

<div class="modal fade" id="modalSetMachineType" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetMachineType">

        </div>
    </div>
</div>

<!--assign sales person-->
<div class="modal fade" id="modalAssignSalesPerson" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {!! Form::open(array('group' => 'form', 'url' => '', 'id' => 'setAssignSalesPersonForm', 'class' => 'form-horizontal','files' => true)) !!}
        <div id="showAssignSalesPerson"></div>
        {!! Form::close() !!}
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var buyerId = $(this).data('buyer-id');
            $.ajax({
                url: "{{ route('buyer.detailsOfContactPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
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


        $(".assign-sales-person").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/salesPersonToBuyer/getAssignSalesPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showAssignSalesPerson").html('');
                },
                success: function (res) {
                    $("#showAssignSalesPerson").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //set assign sales person
        $(document).on('click', '#setAssignSalesPerson', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Submit",
                cancelButtonText: "No, Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    // data
                    var formData = new FormData($("#setAssignSalesPersonForm")[0]);
                    $.ajax({
                        url: "{{URL::to('/salesPersonToBuyer/setAssignSalesPerson')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $("#setAssignSalesPerson").prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            $("#modalAssignSalesPerson").modal('hide');
                            var buyer = res.buyer;
                            var count = res.count;
                            $(".assign-sales-person-" + buyer).hide();
                            $(".sales-person-count-" + buyer).hide();
                            $(".sales-person-list-" + buyer).html(count);
                            $(".sales-person-list-" + buyer).show();

                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }

                            App.unblockUI();
                            $("#setAssignSalesPerson").prop('disabled', false);
                        }
                    }); //ajax
                }
            });
        });

        //Show Google Map based on Address
        $(document).on("click", "#mapModal", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ route('buyer.locationView')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#mapBlock").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
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

        //Show Google Map based on Address
        $(document).on("click", ".set-machine-type", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('buyer/getMachineType') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showSetMachineType").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetMachineType").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
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