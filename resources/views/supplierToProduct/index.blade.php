@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.RELATE_SUPPLIER_TO_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'supplierToProductRelateForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="supplierId">@lang('label.SUPPLIER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('supplier_id', $supplierArr, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                                <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="showAssignedProducts">
                            @if(!empty(Request::get('supplier_id')))
                            <div class="row relation-div-box-default">
                                <div class="col-md-12 border-bottom-1-green-seagreen">
                                    <h5>
                                        <strong>@lang('label.ASSIGNED_PRODUCT_S') : {!! !empty($assignedProductList) ? count($assignedProductList) : 0 !!}</strong>
                                        @if(!empty($assignedProductList) && empty($dependentProductArr[$request->get('supplier_id')]))
                                        <a class="btn btn-xs bold btn-circle red-intense pull-right remove-all-assignments tooltips " title="@lang('label.CLICK_TO_REMOVE_ALL')" 
                                           data-selected-product-id="{{ $request->product_id }}" 
                                           data-supplier-id="{{ $request->get('supplier_id') }}">
                                            <i class="fa fa-times-circle"></i>&nbsp;@lang('label.REMOVE_ALL')
                                        </a>
                                        @endif
                                    </h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive webkit-scrollbar" style="max-height: 100px;">
                                        <table class="table">
                                            <tbody>
                                                @if(!empty($assignedProductList))
                                                <?php
                                                $statusColor = 'green-seagreen';
                                                $statusTitle = __('label.ACTIVE');
                                                if (!empty($inactiveProductArr) && in_array($productId, $inactiveProductArr)) {
                                                    $statusColor = 'red-soft';
                                                    $statusTitle = __('label.INACTIVE');
                                                }
                                                ?>


                                                <?php $sl = 0; ?>
                                                @foreach($assignedProductList as $productId => $name)
                                                <tr>
                                                    <td>{{ ++$sl.'.' }}</td>
                                                    <td>
                                                        {{ $name ?? '' }}
                                                        <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle {{$statusColor}} tooltips" title="{{ $statusTitle }}">
                                                        </button>
                                                    </td>
                                                    <td>
                                                        @if(!empty($dependentProductArr[$request->get('supplier_id')]) && in_array($productId, $dependentProductArr[$request->get('supplier_id')]))
                                                        <span class="label label-sm label-purple-sharp tooltips vcenter" title="@lang('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES')">
                                                            <i class="fa fa-info-circle"></i>
                                                        </span>
                                                        @else
                                                        <a class="btn btn-xs red-intense remove-product tooltips vcenter" title="@lang('label.REMOVE')" 
                                                           data-assigned-product-id="{{ $productId }}" data-selected-product-id="{{ $request->get('product_id') }}" 
                                                           data-supplier-id="{{ $request->get('supplier_id') }}">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="10" class="text-danger">@lang('label.NO_PRODUCT_IS_RELATED_TO_THIS_SALES_PERSON')</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <div id="showBrands">
                            @if(!empty(Request::get('product_id')) && !empty(Request::get('supplier_id')))
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    @if(!empty($userAccessArr[21][5]))
                                    <button class='label label-primary tooltips' href="#modalRelatedProduct" id="relateProduct"  data-toggle="modal" title="@lang('label.SHOW_RELATED_PRODUCTS')">
                                        @lang('label.PRODUCT_RELATED_TO_THIS_SUPPLIER'): {!! !empty($productRelatedToSupplier) ? count($productRelatedToSupplier):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover relation-view">
                                            <thead>
                                                <tr class="active">
                                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>

                                                    @if(!empty($relatedBrandArr[Request::get('product_id')]))
                                                    <?php
                                                    $allCheckDisabled = '';
                                                    if (!empty($dependentBrandArr[$request->get('supplier_id')][$request->get('product_id')])) {
                                                        $allCheckDisabled = 'disabled';
                                                    }
                                                    ?>
                                                    <th class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-brand-check', $allCheckDisabled]) !!}
                                                            <label for="checkAll">
                                                                <span class="inc"></span>
                                                                <span class="check mark-caheck"></span>
                                                                <span class="box mark-caheck"></span>
                                                            </label>
                                                            &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                                                        </div>
                                                    </th>
                                                    @endif
                                                    <th class="text-center vcenter">@lang('label.LOGO')</th>
                                                    <th class="vcenter">@lang('label.BRAND_NAME')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($relatedBrandArr[Request::get('product_id')]))
                                                <?php $sl = 0; ?>
                                                @foreach($relatedBrandArr[Request::get('product_id')] as $brandId)
                                                <?php
                                                //check and show previous value
                                                $checked = '';
                                                if (!empty($brandRelatedToSupplier[Request::get('product_id')]) && array_key_exists($brandId, $brandRelatedToSupplier[Request::get('product_id')])) {
                                                    $checked = 'checked';
                                                }

                                                $brandDisabled = $brandTooltips = '';
                                                $checkCondition = 0;
                                                if (!empty($inactiveBrandArr) && in_array($brandId, $inactiveBrandArr)) {
                                                    if ($checked == 'checked') {
                                                        $checkCondition = 1;
                                                    }
                                                    $brandDisabled = 'disabled';
                                                    $brandTooltips = __('label.INACTIVE');
                                                }
                                                if (!empty($dependentBrandArr[$request->get('supplier_id')][$request->get('product_id')])) {
                                                    if (in_array($brandId, $dependentBrandArr[$request->get('supplier_id')][$request->get('product_id')]) && ($checked != '')) {
                                                        $brandDisabled = 'disabled';
                                                        $checkCondition = 1;
                                                        $brandTooltips = __('label.ALREADY_ASSIGNED_IN_FURTHER_PROCESSES');
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    <td class="vcenter">
                                                        <div class="md-checkbox has-success">
                                                            {!! Form::checkbox('brand['.$brandId.']', $brandId, $checked, ['id' => $brandId, 'data-id'=> $brandId,'class'=> 'md-check brand-check', $brandDisabled]) !!}
                                                            <label for="{!! $brandId !!}">
                                                                <span class="inc tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                                <span class="check mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                                <span class="box mark-caheck tooltips" data-placement="right" title="{{ $brandTooltips }}"></span>
                                                            </label>
                                                        </div>
                                                        @if($checkCondition == '1')
                                                        {!! Form::hidden('brand['.$brandId.']', $brandId) !!}
                                                        @endif
                                                    </td>
                                                    <td class="text-center vcenter">
                                                        @if(!empty($brandInfo[$brandId]['logo']))
                                                        <img class="pictogram-min-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $brandInfo[$brandId]['logo'] }}" alt="{{ $brandInfo[$brandId]['name']}}"/>
                                                        @else 
                                                        <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                                        @endif
                                                    </td>
                                                    <td class="vcenter">{!! $brandInfo[$brandId]['name'] ?? '' !!}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td class="vcenter text-danger" colspan="20">@lang('label.NO_BRAND_FOUND')</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        @if(!empty($relatedBrandArr[$request->product_id]))
                                        @if(!empty($userAccessArr[21][7]))
                                        <button class="btn btn-circle green btn-submit" id="saveSupplierToProductRel" type="button">
                                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                        </button>
                                        @endif
                                        @if(!empty($userAccessArr[21][1]))
                                        <a href="{{ URL::to('/supplierToProduct') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="modalRelatedProduct" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedProduct">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(function () {
        $('.tooltips').tooltip();

<?php if (!empty($relatedBrandArr[Request::get('product_id')])) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>


        $(".brand-check").on("click", function () {
            if ($('.brand-check:checked').length == $('.brand-check').length) {
                $('.all-brand-check').prop("checked", true);
            } else {
                $('.all-brand-check').prop("checked", false);
            }
        });
        $(".all-brand-check").click(function () {
            if ($(this).prop('checked')) {
                $('.brand-check').prop("checked", true);
            } else {
                $('.brand-check').prop("checked", false);
            }

        });
        if ($('.brand-check:checked').length == $('.brand-check').length) {
            $('.all-brand-check').prop("checked", true);
        } else {
            $('.all-brand-check').prop("checked", false);
        }


        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $(document).on('change', '#supplierId', function () {
            var supplierId = $('#supplierId').val();
            var productId = $('#productId').val();
            if (supplierId == '0') {
                $('#showAssignedProducts').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("supplierToProduct/getAssignedProducts/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId,
                    product_id: productId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showAssignedProducts').html(res.html);
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
            });
        });

        //show brand on change supplier and product
        $(document).on('change', '#supplierId, #productId', function () {
            var supplierId = $('#supplierId').val();
            var productId = $('#productId').val();
            if (supplierId == '0' || productId == '0') {
                $('#showBrands').html('');
                return false;
            }
            $.ajax({
                url: '{{URL::to("supplierToProduct/getProductsToRelate/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId,
                    product_id: productId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showBrands').html(res.html);
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
            });
        });

        $(document).on("click", "#relateProduct", function (e) {
            e.preventDefault();
            var supplierId = $("#supplierId").val();
            $.ajax({
                url: "{{ URL::to('/supplierToProduct/getRelatedProducts')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    supplier_id: supplierId
                },
                success: function (res) {
                    $("#showRelatedProduct").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //insert suppliers' product to brand
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var oTable = $('.relation-view').dataTable();
            var x = oTable.$('input,select,textarea').serializeArray();
            $.each(x, function (i, field) {
                $("#supplierToProductRelateForm").append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', field.name)
                        .val(field.value));
            });
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
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

                            // Serialize the form data
                            var form_data = new FormData($('#supplierToProductRelateForm')[0]);
                            $.ajax({
                                url: "{{URL::to('supplierToProduct/relateSupplierToProduct')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    var supplierId = $('#supplierId').val();
                                    var productId = $('#productId').val();
                                    location = "supplierToProduct?supplier_id=" + supplierId + "&product_id=" + productId;
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
                                }
                            });
                        }
                    });


        });

        //remove product from assigned productlist
        $(document).on("click", ".remove-product", function (e) {
            e.preventDefault();
            var productId = $(this).attr('data-assigned-product-id');
            var selectedProductId = $("#productId").val();
            var supplierId = $(this).attr('data-supplier-id');
            swal({
                title: 'Are you sure?',
                text: "This product will be permanetly removed from assigned product list!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
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

                            $.ajax({
                                url: "{{URL::to('supplierToProduct/removeAssignedProduct')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything

                                data: {
                                    product_id: productId,
                                    supplier_id: supplierId,
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    location = "supplierToProduct?supplier_id=" + supplierId + "&product_id=" + selectedProductId;
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
                                }
                            });
                        }
                    });


        });
        
        //remove all  assignment
        $(document).on("click", ".remove-all-assignments", function (e) {
            e.preventDefault();
            var selectedProductId = $("#productId").val();
            var supplierId = $(this).attr('data-supplier-id');
            swal({
                title: 'Are you sure?',
                text: "This product will be permanetly removed all asignments!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Remove All',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
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

                            $.ajax({
                                url: "{{URL::to('supplierToProduct/removeAllAssignment')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything

                                data: {
                                    supplier_id: supplierId,
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    location = "supplierToProduct?supplier_id=" + supplierId + "&product_id=" + selectedProductId;
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
                                }
                            });
                        }
                    });


        });



    });
</script>
@stop