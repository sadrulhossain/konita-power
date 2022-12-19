@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.BUYER_ANALYTICS')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[18][1]))
                <a href="{{ URL::to('/buyer'.Helper::queryPageStr($qpArr)) }}" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;@lang('label.CLICK_TO_GO_BACK')
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body"><!--BASIC ORDER INFORMATION-->
            <div class="row div-box-default">
                <div class="col-md-2 col-lg-2 col-sm-2">
                    <table class="table table-borderless">
                        <tr >
                            <td class=" text-center">
                                @if (!empty($target->logo))
                                <img alt="{{$target->name}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$target->logo}}" width="100" height="100"/>
                                @else
                                <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="100" height="100"/>
                                @endif
                            </td>
                        </tr>  
                    </table>
                </div>
                <div class="col-md-5 col-lg-5 col-sm-5">
                    <table class="table table-borderless">
                        <tr >
                            <td class="bold" width="30%">@lang('label.BUYER')</td>
                            <td width="70%">{!! $target->name ?? __('label.N_A') !!}</td>
                        </tr>  
                        <tr>
                            <td class="bold" width="30%">@lang('label.BUYER_CATEGORY')</td>
                            <td width="70%">{!! $buyerCatArr[$target->buyer_category_id] ?? __('label.N_A') !!}</td>
                        </tr> 
                        <tr >
                            <td class="bold" width="30%">@lang('label.CODE')</td>
                            <td width="70%">{!! $target->code ?? __('label.N_A') !!}</td>
                        </tr>  
                    </table>
                </div>
                <div class="col-md-5 col-lg-5 col-sm-5">
                    <table class="table table-borderless">
                        <tr>
                            <td class="bold" width="30%">@lang('label.COUNTRY')</td>
                            <td width="70%">{!! $countryList[$target->country_id] ?? __('label.N_A') !!}</td>
                        </tr>
                        @if($target->country_id == '18')
                        <tr>
                            <td class="bold" width="30%">@lang('label.DIVISION')</td>
                            <td width="70%">{!! $divisionList[$target->division_id] ?? __('label.N_A') !!}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="bold" width="30%">@lang('label.STATUS')</td>
                            <td width="70%">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--END OF BASIC BUYER INFORMATION-->

            <div class="tabbable tabbable-tabdrop margin-top-20" id="tabs">
                <ul class="nav nav-pills">
                    <li class="active bg-yellow-casablanca">
                        <a class="bold tab-color" href="#tab_5_1" data-toggle="tab" aria-expanded="false">@lang('label.COMPETITORS_PRODUCTS')</a>
                    </li>
                    <li class="bg-yellow-casablanca">
                        <a class="bold tab-color" href="#tab_5_2" data-toggle="tab" aria-expanded="false">@lang('label.IMPORT_VOLUME')</a>
                    </li>
                    <li class="bg-yellow-casablanca">
                        <a class="bold tab-color" href="#tab_5_3" data-toggle="tab" aria-expanded="true"> @lang('label.ASSIGN_FINISHED_GOODS')</a>
                    </li>
                    <li class="bg-yellow-casablanca">
                        <a class="bold tab-color" href="#tab_5_4" data-toggle="tab" aria-expanded="true"> @lang('label.OTHERS')</a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_5_1">
                        {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')) !!}
                        {!! Form::hidden('buyer_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="info">
                                                <th class="vcenter">@lang('label.SL_NO')</th>
                                                <th class="vcenter">
                                                    @if(sizeof($competitorsProductArr) == 0)
                                                    #
                                                    @elseif(sizeof($competitorsProductArr) >= 1)
                                                    <div class="md-checkbox" >
                                                        {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
                                                        <label for="checkAll">
                                                            <span class=""></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>@lang('label.CHECK_ALL')
                                                        </label>

                                                    </div>
                                                    @endif
                                                </th>
                                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                                <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                                                <th class="vcenter">@lang('label.PRODUCT_CODE')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($competitorsProductArr->isNotEmpty())
                                            <?php $sl = 0 ?>
                                            @foreach($competitorsProductArr as $productInfo)
                                            <?php
                                            $checked = '';
                                            if (!empty($prevRelatedComProducts)) {
                                                if (in_array($productInfo->id, $prevRelatedComProducts)) {
                                                    $checked = 'checked';
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td class="vcenter">{!! ++$sl!!}</td>
                                                <td class="text-center vcenter">
                                                    <div class="md-checkbox has-success">
                                                        {!! Form::checkbox('product_id['.$productInfo->id.']', $productInfo->id, false, ['id' => 'compProduct_'.$productInfo->id, 'data-id'=> $productInfo->id,'class'=> 'md-check competitors-product-check',$checked]) !!}
                                                        <label for="{!! 'compProduct_'.$productInfo->id !!}">
                                                            <span class="inc"></span>
                                                            <span class="check mark-caheck"></span>
                                                            <span class="box mark-caheck"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="vcenter">{!! $productInfo->name !!}</td>
                                                <td class="vcenter">{!! $productInfo->product_category !!}</td>
                                                <td class="vcenter">{!! $productInfo->product_code !!}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="6">
                                                    @lang('label.NO_COMPETITORS_PRODUCT_FOUND')
                                                </td>
                                            </tr>
                                            @endif      
                                        </tbody>
                                    </table>
                                </div>
                                @if($competitorsProductArr->isNotEmpty())
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            @if(!empty($userAccessArr[18][7]))
                                            <button class="btn btn-success" id="competitorProductBtn" type="button">
                                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                            </button>
                                            @endif
                                            @if(!empty($userAccessArr[18][1]))
                                            <a href="{{ URL::to('/buyer'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="tab_5_2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="info">
                                                <th class="vcenter">@lang('label.SL_NO')</th>
                                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                                <th class="text-center vcenter">@lang('label.PRODUCT_TYPE')</th>
                                                <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                                                <th class="vcenter">@lang('label.SET_VOLUME')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if($productsArr->isNotEmpty())
                                            <?php
                                            $sl = 0;
                                            ?>
                                            @foreach($productsArr as $productData)
                                            <tr>
                                                <td class="vcenter">{!! ++$sl!!}</td>
                                                <td class="vcenter">{!! $productData->name !!}</td>
                                                <td class="text-center vcenter">
                                                    @if($productData->competitors_product == '0')
                                                    <span class="label label-success">@lang('label.KTI')</span>
                                                    @else
                                                    <span class="label label-danger">@lang('label.COMPETITORS_PRODUCT')</span>
                                                    @endif
                                                </td>
                                                <td class="vcenter">{!! $productData->category_name !!}</td>
                                                <td class="text-center vcenter">
                                                    @if(!empty($userAccessArr[18][2]))
                                                    <button class="btn btn-xs purple tooltips gsm-volume-set" href="#setGsmVolume" data-toggle="modal" 
                                                            title="@lang('label.SET_GSM_VOLUME_FOR_THIS_PRODUCT')" type="button"
                                                            data-placement="top" data-rel="tooltip" data-buyer-id ="{{ $target->id }}"
                                                            data-product-id="{!! $productData->id !!}">
                                                        <i class="fa fa-th-large"></i>
                                                    </button>
                                                    @endif
                                                    @if(!empty($userAccessArr[18][5]))
                                                    @if(!empty($tempGsmValues))
                                                    @if(array_key_exists($productData->id,$tempGsmValues))
                                                    <button class="btn btn-xs btn-primary tooltips volume-details" href="#detailsVolume" data-toggle="modal" 
                                                            title="@lang('label.CLICK_HERE_TO_VIEW_DETAILS_VOLUME')" type="button"
                                                            data-placement="top" data-rel="tooltip" data-buyer-id ="{{ $target->id }}"
                                                            data-product-id="{!! $productData->id !!}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-xs red-flamingo tooltips delete-gsm vcenter" title="@lang('label.CLICK_TO_DELETE_GSM_DATA')" data-buyer-id="{{ $target->id }}" data-product-id="{{$productData->id}}">
                                                        <i class="fa fa-times-circle"></i>
                                                    </button>
                                                    @endif
                                                    @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5">
                                                    @lang('label.NO_PRODUCT_FOUND')
                                                </td>
                                            </tr>
                                            @endif											

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_5_3">
                        {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitGoodsForm')) !!}
                        {!! Form::hidden('buyer_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="info">
                                                <th class="vcenter">@lang('label.SL_NO')</th>
                                                <th class="vcenter">
                                                    @if(sizeof($finishedGoodsArr) == 0)
                                                    #
                                                    @elseif(sizeof($finishedGoodsArr) >= 1)
                                                    <div class="md-checkbox" >
                                                        {!! Form::checkbox('goods_check_all',1,false, ['id' => 'goodsCheckAll', 'class'=> 'md-check']) !!}
                                                        <label for="goodsCheckAll">
                                                            <span class=""></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>@lang('label.CHECK_ALL')
                                                        </label>

                                                    </div>
                                                    @endif
                                                </th>
                                                <th class="vcenter">@lang('label.NAME')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($finishedGoodsArr))
                                            <?php $sl = 0 ?>
                                            @foreach($finishedGoodsArr as $goodsId => $goodsName)
                                            <?php
                                            $checkedGoods = '';
                                            if (!empty($prevRelatedFinishedGoods)) {
                                                if (in_array($goodsId, $prevRelatedFinishedGoods)) {
                                                    $checkedGoods = 'checked';
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td class="vcenter">{!! ++$sl!!}</td>
                                                <td class="text-center vcenter" width="20%">
                                                    <div class="md-checkbox has-success">
                                                        {!! Form::checkbox('finished_goods['.$goodsId.']', $goodsId, false, ['id' => 'finishedGoods_'.$goodsId, 'data-id'=> $goodsId,'class'=> 'md-check goods-check',$checkedGoods]) !!}
                                                        <label for="{!! 'finishedGoods_'.$goodsId !!}">
                                                            <span class="inc"></span>
                                                            <span class="check mark-caheck"></span>
                                                            <span class="box mark-caheck"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="vcenter">{!! $goodsName !!}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5">
                                                    @lang('label.NO_FINISHED_GOODS_FOUND')
                                                </td>
                                            </tr>
                                            @endif      
                                        </tbody>
                                    </table>
                                </div>
                                @if(!empty($finishedGoodsArr))
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            @if(!empty($userAccessArr[18][7]))
                                            <button class="btn btn-success" id="finishedGoodsBtn" type="button">
                                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                            </button>
                                            @endif
                                            @if(!empty($userAccessArr[18][1]))
                                            <a href="{{ URL::to('/buyer/'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="tab_5_4">
                        <div class="form-body  seperate-div">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitOthersForm')) !!}
                                        {!! Form::hidden('buyer_id',$target->id) !!}
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="">@lang('label.FSC_CERTIFIED') :</label>
                                            <div class="col-md-7">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        {{ Form::radio('fsc_certified','1',($target->fsc_certified == '1') ? true : false,['class' => 'form-control md-radiobtn','id'=> 'fscCertifiedYes']) }} 
                                                        <label for="fscCertifiedYes">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> @lang('label.YES')</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        {{ Form::radio('fsc_certified','0',($target->fsc_certified == '0') ? true : false, ['class' => 'form-controlmd-radiobtn', 'id'=> 'fscCertifiedNo'])}}
                                                        <label for="fscCertifiedNo">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> @lang('label.NO')</label>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('fsc_certified') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="">@lang('label.ISO_CERTIFIED') :</label>
                                            <div class="col-md-7">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        {{ Form::radio('iso_certified','1',($target->iso_certified == '1') ? true:false,['class' => 'form-control md-radiobtn','id'=> 'isoCertifiedYes']) }} 
                                                        <label for="isoCertifiedYes">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> @lang('label.YES')
                                                        </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        {{ Form::radio('iso_certified','0',($target->iso_certified == '0') ? true:false, ['class' => 'form-controlmd-radiobtn', 'id'=> 'isoCertifiedNo'])}}
                                                        <label for="isoCertifiedNo">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> @lang('label.NO')
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('fsc_certified') }}</span>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="machineType">@lang('label.PACKAGING_MACHINE_TYPE') :</label>
                                            <div class="col-md-7">
                                                {!! Form::select('machine_type[]', $packagingMachineArr,  !empty($machineTypes) ? $machineTypes : null, ['class' => 'form-control mt-multiselect btn btn-default', 'multiple','id' => 'machineType','data-width' => '100%','data-label'=>"center", 'data-select-all'=>"true",'data-filter'=>"true",
                                                'data-action-onchange'=>"true"]) !!}
                                                <span class="text-danger">{{ $errors->first('machine_type') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="brandOfMachine">@lang('label.BRAND_OF_MACHINE') :</label>
                                            <div class="col-md-7">
                                                {!! Form::text('machine_brand', !empty($target->machine_brand) ? $target->machine_brand : null, ['id'=> 'brandOfMachine', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                                <span class="text-danger">{{ $errors->first('machine_brand') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="machineLength">@lang('label.MACHINE_LENGTH') :</label>
                                            <div class="col-md-7">
                                                {!! Form::text('machine_length', !empty($target->machine_length) ? $target->machine_length : null, ['id'=> 'machineLength', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                                <span class="text-danger">{{ $errors->first('machine_length') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-5" for="customerType">@lang('label.TYPE') :</label>
                                            <div class="col-md-7">
                                                {!! Form::select('customer_type[]', $customerTypeArr, !empty($customerTypes) ? $customerTypes : null, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'customerType','multiple','data-width' => '100%','data-label'=>"center", 'data-select-all'=>"true",'data-filter'=>"true",
                                                'data-action-onchange'=>"true"]) !!}
                                                <span class="text-danger">{{ $errors->first('buyer_category_id') }}</span> 
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            @if(!empty($userAccessArr[18][7]))
                                            <button class="btn btn-success" id="othersInfoBtn" type="button">
                                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                                            </button>
                                            @endif
                                            @if(!empty($userAccessArr[18][1]))
                                            <a href="{{ URL::to('/buyer/'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal start -->
<div class="modal fade" id="setGsmVolume" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showGsmVolume">
        </div>
    </div>
</div>
<!-- Modal end-->

<!-- Modal start -->
<div class="modal fade" id="detailsVolume" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="showVolumes">
        </div>
    </div>
</div>
<!-- Modal end-->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", ".volume-details", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var productId = $(this).data('product-id');
            $.ajax({
                url: "{{ URL::to('/buyer/volumeDetails')}}",
                type: "POST",
                dataType: "json",
                data: {
                    buyer_id: buyerId,
                    product_id: productId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showVolumes").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showVolumes").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //Set Individual Products GSM & Volume
        $(document).on("click", ".gsm-volume-set", function (e) {
            e.preventDefault();
            var buyerId = $(this).data('buyer-id');
            var productId = $(this).data('product-id');
            $.ajax({
                url: "{{ URL::to('/buyer/getGsmVolume')}}",
                type: "POST",
                dataType: "json",
                data: {
                    buyer_id: buyerId,
                    product_id: productId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $("#showGsmVolume").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showGsmVolume").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        // this code for  database 'check all' if all checkbox items are checked
        if ($('.competitors-product-check:checked').length == $('.competitors-product-check').length) {
            $('#checkAll').prop("checked", true);
        } else {
            $('#checkAll').prop("checked", false);
        }

        $("#checkAll").change(function () {
            if (this.checked) {
                $(".competitors-product-check").each(function () {
                    this.checked = true;
                });
            } else {
                $(".competitors-product-check").each(function () {
                    this.checked = false;
                });
            }
        });

        $('.competitors-product-check').change(function () {
            if (this.checked == false) { //if this item is unchecked
                $('#checkAll')[0].checked = false; //change 'check all' checked status to false
            }

            //check 'check all' if all checkbox items are checked
            if ($('.competitors-product-check:checked').length == $('.competitors-product-check').length) {
                $('#checkAll')[0].checked = true; //change 'check all' checked status to true
            }
        });


        //ASSIGN BUYER'S COMPETITOR'S PRODUCT
        $(document).on('click', '#competitorProductBtn', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            swal({
                title: 'Are you sure?',
                text: "@lang('label.YOU_WANT_TO_ADD_COMPETITORS_PRODUCT')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{route('buyer.competitorsproduct')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
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
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('Something went wrong', 'Error', options);
                            }
                            App.unblockUI();
                        }
                    });
                }

            });

        });//EOF Competitor's Product

        // this code for  database 'check all' if all checkbox items are checked
        if ($('.goods-check:checked').length == $('.goods-check').length) {
            $('#goodsCheckAll').prop("checked", true);
        } else {
            $('#goodsCheckAll').prop("checked", false);
        }




        $("#goodsCheckAll").change(function () {
            if (this.checked) {
                $(".goods-check").each(function () {
                    this.checked = true;
                });
            } else {
                $(".goods-check").each(function () {
                    this.checked = false;
                });
            }
        });

        $('.goods-check').change(function () {
            if (this.checked == false) { //if this item is unchecked
                $('#goodsCheckAll')[0].checked = false; //change 'check all' checked status to false
            }

            //check 'check all' if all checkbox items are checked
            if ($('.goods-check:checked').length == $('.goods-check').length) {
                $('#goodsCheckAll')[0].checked = true; //change 'check all' checked status to true
            }
        });


        //ASSIGN FINISHED GOODS PRODUCT
        $(document).on('click', '#finishedGoodsBtn', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitGoodsForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            swal({
                title: 'Are you sure?',
                text: "@lang('label.YOU_WANT_TO_ADD_FINISHED_GOODS')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{route('buyer.finishedproduct')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
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
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('Something went wrong', 'Error', options);
                            }
                            App.unblockUI();
                        }
                    });
                }

            });

        });//EOF Assign Finished Goods Product

        //ASSIGN FINISHED GOODS PRODUCT
        $(document).on('click', '#othersInfoBtn', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitOthersForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            swal({
                title: 'Are you sure?',
                text: "@lang('label.YOU_WANT_TO_ADD_OTHERS_INFORMATION')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{route('buyer.othersinfo')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
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
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('Something went wrong', 'Error', options);
                            }
                            App.unblockUI();
                        }
                    });
                }

            });

        });//EOF Assign Finished Goods Product

        $('#customerType').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            selectAllText: "@lang('label.SELECT_BOTH')",
            nonSelectedText: "@lang('label.SELECT_CUSTOMER_TYPE_OPT')",
        });

        $('#machineType').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            selectAllText: "@lang('label.SELECT_BOTH')",
            nonSelectedText: "@lang('label.SELECT_PACKAGING_MACHINE_TYPE_OPT')",
        });

        //delete gsm data
        $(document).on("click", ".delete-gsm", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-buyer-id");
            var productId = $(this).attr("data-product-id");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "@lang('label.ARE_YOU_SURE_YOU_WANT_TO_DELETE_GSM_DATA')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('buyer/removeGsm')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            buyer_id: buyerId,
                            product_id: productId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.hash = '#tab_5_2';
                            location.reload();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
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
                }
            });
        });

    });
</script>
@stop