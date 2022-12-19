@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SUPPLIER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[13][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('supplier/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_SUPPLIER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'supplier/filter','class' => 'form-horizontal')) !!}
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
                            <label class="control-label col-md-4" for="supplierClassifiationId">@lang('label.CLASSIFICATION')</label>
                            <div class="col-md-8">
                                {!! Form::select('supplier_classification_id',  $supplierClassificationArr, Request::get('supplier_classification_id'), ['class' => 'form-control js-source-states','id'=>'supplierClassifiationId']) !!}
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
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.LOGO')</th>
                            <th class="vcenter">@lang('label.CLASSIFICATION')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.CODE')</th>
                            <th class="vcenter">@lang('label.ADDRESS')</th>
                            <th class="vcenter">@lang('label.COUNTRY')</th>
                            <th class="vcenter">@lang('label.SIGN_OFF_DATE')</th>
                            <th class="text-center vcenter">@lang('label.FSC_CERTIFIED')</th>
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
                                <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/supplier/{{$target->logo}}" width="40" height="40"/>
                                @else
                                <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->supplier_classification }}</td>
                            <td class="vcenter">
                                @if(!empty($userAccessArr[13][5]))
                                <a class="tooltips" title="@lang('label.CLICK_TO_VIEW_PROFILE')"
                                   href="{{ URL::to('supplier/' . $target->id . '/profile'.Helper::queryPageStr($qpArr)) }}">
                                    {{ $target->name }}
                                </a>
                                @else
                                {{ $target->name }}
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->code }}</td>
                            <td class="vcenter">{{ $target->address }}</td>
                            <td class="vcenter">{{ $target->country }}</td>
                            <td class="vcenter">{{ isset($target->sign_off_date) ? Helper::formatDate($target->sign_off_date) : '' }}</td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($target->fsc_certified))
                                    <span class="label label-info fsc-padding">
                                        @lang('label.YES')
                                    </span>
                                    @if(isset($target->fsc_attachment))
                                    <label>&nbsp;</label>
                                    <a href="{{URL::to('public/uploads/supplierFscCertificate/'.$target->fsc_attachment)}}" class="btn fsc-padding purple red-stripe tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_FSC_CERTIFICATE')" target="_blank">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </a>

                                    @endif
                                    @else
                                    <span class="label label-warning purple-stripe">
                                        @lang('label.NO')
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
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[13][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('supplier/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[13][4]))
                                    {{ Form::open(array('url' => 'supplier/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                    @if(!empty($userAccessArr[13][5]))
                                    <button class="btn btn-xs btn-info tooltips vcenter" href="#contactPersonDetails" id="contactPersonData"  data-toggle="modal" title="@lang('label.SHOW_CONTACT_PERSON_DETAILS')" data-supplier-id="{{$target->id}}">
                                        <i class="fa fa-phone"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_SUPPLIER_FOUND')</td>
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
<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        $(document).on("click", "#contactPersonData", function (e) {
            e.preventDefault();
            var supplierId = $(this).data('supplier-id');
            $.ajax({
                url: "{{ route('supplier.detailsOfContactPerson')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId
                },
                success: function (res) {
                    $("#showDetailsContactPerson").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

    });
</script>    
@stop