@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[15][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('product/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PRODUCT')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'product/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'productName','autocomplete' => 'off']) !!} 
                            <datalist id="productName">
                                @if (!$nameArr->isEmpty())
                                @foreach($nameArr as $item)
                                <option value="{{$item->name}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCode">@lang('label.CODE')</label>
                        <div class="col-md-8">
                            {!! Form::text('product_code',  Request::get('product_code'), ['class' => 'form-control tooltips', 'title' => 'Product Code', 'placeholder' => 'Product Code', 'list' => 'productCode', 'autocomplete' => 'off']) !!} 
                            <datalist id="productCode">
                                @if (!$productCodeArr->isEmpty())
                                @foreach($productCodeArr as $productCode)
                                <option value="{{$productCode->product_code}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCategory">@lang('label.CATEGORY')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_category',  $productCategoryArr, Request::get('product_category'), ['class' => 'form-control js-source-states','id'=>'productCategory']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">   
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="measureUnit">@lang('label.UNIT')</label>
                        <div class="col-md-8">
                            {!! Form::select('measure_unit',  $measureUnitArr, Request::get('measure_unit'), ['class' => 'form-control js-source-states','id'=>'measureUnit']) !!}
                        </div>
                    </div>
                </div>
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
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.CODE')</th>
                            <th class="vcenter">@lang('label.CATEGORY')</th>
                            <th class="text-center vcenter">@lang('label.UNIT')</th>
                            <th class="text-center vcenter">@lang('label.COMPETITORS_PRODUCT')</th>
                            <th class="text-center vcenter">@lang('label.HS_CODE')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
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
                        <?php
                        $iconP = '';
                        if (!empty($pricedBrandArr)) {
                            if (array_key_exists($target->id, $pricedBrandArr)) {
                                $noOfBrandRelated = !empty($relatedBrandArr[$target->id]) ? count($relatedBrandArr[$target->id]) : 0;
                                $noOfBrandPriced = !empty($pricedBrandArr[$target->id]) ? count($pricedBrandArr[$target->id]) : 0;
                                $sP = $noOfBrandRelated > 1 ? 's' : '';
                                $labelP = __('label.PRICING_SET_FOR_NO_OF_PRICED_OUT_OF_NO_OF_RELATED', ['no_of_brand_related' => $noOfBrandRelated, 'no_of_brand_priced' => $noOfBrandPriced, 's' => $sP]);
                                $iconP = '<br/><span class="badge badge-primary tooltips" title="' . $labelP . '"><i class="fa fa-usd"></i></span>';
                            }
                        }                  
                        ?>
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl.$iconP !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="vcenter">{!! $target->product_code !!}</td>
                            <td class="vcenter">{!! $target->product_category !!}</td>
                            <td class="text-center vcenter">
                                <span class="label label-info">{!! $target->measure_unit !!}</span>
                            </td>
                            <td class="text-center vcenter">
                                @if($target->competitors_product == '1')
                                <span class="label label-success">@lang('label.YES')</span>
                                @elseif($target->competitors_product == '0')
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if(!empty($hsCodeArr[$target->id]))
                                <?php
                                $lastValue = end($hsCodeArr[$target->id]);
                                ?>
                                @foreach($hsCodeArr[$target->id] as $key => $code)
                                {{$code}}
                                @if($lastValue !=$code)
                                <span>,</span>
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[15][8]))
                                    <button class="btn btn-xs green-soft tooltips vcenter set-product-pricing" href="#modalSetProductPricing" data-id="{!! $target->id !!}"  data-toggle="modal" title="@lang('label.SET_PRODUCT_PRICING')">
                                        <i class="fa fa-calculator"></i>
                                    </button>
                                    <button class="btn btn-xs purple-sharp tooltips vcenter set-product-quality" href="#modalSetProductQuality" data-id="{!! $target->id !!}"  data-toggle="modal" title="@lang('label.SET_PRODUCT_QUALITY')">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[15][1]))
                                    <button class="btn btn-xs yellow-mint tooltips vcenter track-product-history" href="#modalTrackProductHistory" id="trackProductHistory" data-id="{!! $target->id !!}"  data-toggle="modal" title="@lang('label.VIEW_PRODUCT_PRICING_HISTORY')">
                                        <i class="fa fa-th"></i>
                                    </button>
                                    <button class="btn btn-xs bg-yellow-casablanca bg-font-yellow-casablanca tooltips vcenter brand-details" href="#modalBrandDetails" id="brandInfo" data-id="{!! $target->id !!}" data-name="{!! $target->name !!}"  data-toggle="modal" title="@lang('label.VIEW_ASSIGNED_BRANDS')">
                                        <i class="fa fa-users"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[15][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('product/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[15][4]))
                                    {!! Form::open(array('url' => 'product/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="14" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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

<!--set product pricing modal-->
<div class="modal fade" id="modalSetProductPricing" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showSetProductPricing">
        </div>
    </div>
</div>

<!--set product quality modal-->
<div class="modal fade" id="modalSetProductQuality" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductQuality">
        </div>
    </div>
</div>

<!--product pricing history modal-->
<div class="modal fade" id="modalTrackProductHistory" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showTrackProductHistory">
        </div>
    </div>
</div>

<!--product pricing history modal-->
<div class="modal fade" id="modalBrandDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showBrandDetails">
        </div>
    </div>
</div>

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //set product pricing modal
        $(".set-product-pricing").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/product/getProductPricing')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductPricing").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductPricing").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //set product pricing modal
        $(".set-product-quality").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/product/getProductQuality')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductQuality").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductQuality").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //track product history modal
        $(".track-product-history").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/product/trackProductPricingHistory')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showTrackProductHistory").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showTrackProductHistory").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //track product history modal
        $(".brand-details").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            var productName = $(this).attr("data-name");
            $.ajax({
                url: "{{ URL::to('/product/brandDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    product_name: productName,
                },
                beforeSend: function () {
                    $("#showBrandDetails").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showBrandDetails").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


    });

    //get brand wise product pricing history
    $(document).on("click", ".get-brand-wise-pricing-history", function (e) {
        e.preventDefault();
        var productId = $(this).attr("data-product-id");
        var brandId = $(this).attr("data-brand-id");
        $.ajax({
            url: "{{ URL::to('/product/getBrandWisePricingHistory')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
            },
            beforeSend: function () {
                $("#getBrandWisePricingHistory").html('');
            },
            success: function (res) {
                $("#getBrandWisePricingHistory").html(res.html);
                $(".get-brand-wise-pricing-history").children().removeClass('col-padding-box-blue-chambary').addClass('col-padding-box-blue-hoki');
                $("#brand_" + brandId).removeClass('col-padding-box-blue-hoki').addClass('col-padding-box-blue-chambary');
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

</script>

@stop