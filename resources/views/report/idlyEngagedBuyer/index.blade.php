@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-th"></i>@lang('label.BUYER_ENGAGEMENT_REPORT_IDLE')
            </div>
            <div class="actions">
                <span class="text-right">
                    @if(Request::get('generate') == 'true')
                    @if(!empty($userAccessArr[73][6]))
                    <a class="btn btn-sm btn-inline blue-soft btn-print tooltips vcenter" data-placement="left" target="_blank" href="{{ URL::to($request->fullUrl() . '&view=print') }}"  title="@lang('label.PRINT')">
                        <i class="fa fa-print"></i>
                    </a>
                    @endif
                    @endif
                </span>
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'idlyEngagedBuyerReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-5"  for="idleFor">@lang('label.IDLE_FOR_ATLEAST')<span class="text-danger"> *</span></label>
                        <div class="col-md-7">
                            <div class="input-group bootstrap-touchspin">
                                {!! Form::text('idle_for', Request::get('idle_for'), ['id'=> 'idleFor', 'class' => 'form-control text-right integer-only','autocomplete' => 'off']) !!}
                                <span class="input-group-addon bootstrap-touchspin-postfix bold">@lang('label.MONTH_S')</span>
                            </div>
                            <span class="text-danger">{{ $errors->first('idle_for') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="row margin-top-10">
                @if(Request::get('generate') == 'true')
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.IDLE_FOR_ATLEAST')}} : <strong>{{  Request::get('idle_for') . ' ' . __('label.MONTH_S') }} |</strong> 
                            {{__('label.TOTAL_NO_OF_BUYERS')}} : <strong>{{  !empty($buyerInfoArr) ? count($buyerInfoArr) : 0 }} </strong> 
                        </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="max-height-500 tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="fixTable">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.LOGO')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.BUYER_NAME')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.COUNTRY')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.DIVISION')</th>
                                    <th class="text-center vcenter" colspan="2">@lang('label.PRIMARY_CONTACT_PERSON')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.STATUS')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.IDLE_TIME')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.LATEST_BUYER_FOLLOWUP')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.NO_OF_RELATED_SALES_PERSONS')</th>
                                </tr>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.NAME')</th>
                                    <th class="text-center vcenter">@lang('label.PHONE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($buyerInfoArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($buyerInfoArr as $item)
                                <tr>
                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                    <td class="text-center vcenter" width="40px">
                                        @if (!empty($item['logo']) && File::exists('public/uploads/buyer/' . $item['logo']))
                                        <img alt="{{$item['name']}}" src="{{URL::to('/')}}/public/uploads/buyer/{{$item['logo']}}" width="40" height="40"/>
                                        @else
                                        <img alt="unknown" src="{{URL::to('/')}}/public/img/no_image.png" width="40" height="40"/>
                                        @endif
                                    </td>
                                    <td class="vcenter">
                                        @if(!empty($userAccessArr[73][5]))
                                        <a class="tooltips" title="@lang('label.CLICK_TO_VIEW_PROFILE')"
                                           href="{{ URL::to('idlyEngagedBuyerReport/' . $item['id'] . '/profile' . Helper::getUrlRequestText(URL::to($request->fullUrl())) ) }}">
                                            {{ $item['name'] }}
                                        </a>
                                        @else
                                        {{ $item['name'] }}
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $item['country_name'] ?? '' !!}</td>
                                    <td class="vcenter">{!! $item['division_name'] ?? '' !!}</td>
                                    <td class="vcenter">{!! $contactArr[$item['id']]['name'] ?? '' !!}</td>

                                    @if(is_array($contactArr[$item['id']]['phone']))
                                    <td class="vcenter">
                                        <?php
                                        $lastValue = end($contactArr[$item['id']]['phone']);
                                        ?>
                                        @foreach($contactArr[$item['id']]['phone'] as $key => $contact)
                                        {{$contact}}
                                        @if($lastValue !=$contact)
                                        <span>,</span>
                                        @endif
                                        @endforeach
                                    </td>
                                    @else
                                    <td class="vcenter">{!! $contactArr[$item['id']]['phone'] ?? '' !!}</td>
                                    @endif
                                    <td class="text-center vcenter">
                                        @if($item['status'] == '1')
                                        <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>
                                    <td class="vcenter text-center">{!! $idleTimeArr[$item['id']] ?? '' !!}</td>
                                    <td class="text-center vcenter">
                                        @if(array_key_exists($item['id'], $latestFollowupArr))
                                        @if($latestFollowupArr[$item['id']]['status'] == '1')
                                        <span class="label label-sm label-yellow">@lang('label.NORMAL')</span>
                                        @elseif($latestFollowupArr[$item['id']]['status'] == '2')
                                        <span class="label label-sm label-green-seagreen">@lang('label.HAPPY')</span>
                                        @elseif($latestFollowupArr[$item['id']]['status'] == '3')
                                        <span class="label label-sm label-red-soft">@lang('label.UNHAPPY')</span>
                                        @endif
                                        @else
                                        <span class="label label-sm label-gray-mint">@lang('label.NO_FOLLOWUP_YET')</span>
                                        @endif
                                    </td>
                                    <td class="text-center vcenter">
                                        @if(!empty($salesPersonToBuyerCountList[$item['id']]))
                                        <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list"  
                                                title="@lang('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST')" href="#modalRelatedSalesPersonList" data-id="{!! $item['id'] !!}" data-toggle="modal">
                                            {!! $salesPersonToBuyerCountList[$item['id']] !!}
                                        </button>
                                        @else
                                        <span class="label label-sm label-gray-mint sales-person-count-{{$item['id']}} tooltips" title="@lang('label.NO_RELATED_SALES_PERSON')">{!! 0 !!}</span>
                                        <button class="btn btn-xs green-seagreen tooltips vcenter related-sales-person-list sales-person-list sales-person-list-{{$item['id']}}"  
                                                title="@lang('label.CLICK_TO_VIEW_RELATED_SALES_PERSON_LIST')" href="#modalRelatedSalesPersonList" data-id="{!! $item['id'] !!}" data-toggle="modal">

                                        </button>
                                        @if(!empty($userAccessArr[17][7]))
                                        <button class="btn btn-xs blue-hoki tooltips vcenter assign-sales-person assign-sales-person-{{$item['id']}}"  
                                                title="@lang('label.CLICK_TO_ASSIGN_SALES_PERSON')" href="#modalAssignSalesPerson" data-id="{!! $item['id'] !!}" data-toggle="modal">
                                            <i class="fa fa-share"></i>
                                        </button>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="{!! 11 !!}">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>	
    </div>
</div>

<!-- Modal start -->
<!--related sales person list-->
<div class="modal fade" id="modalRelatedSalesPersonList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showRelatedSalesPersonList"></div>
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

<!--in business brand list-->
<div class="modal fade" id="modalInBusinessBrandList" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showInBusinessBrandList"></div>
    </div>
</div>

<!-- Modal end -->

<script type="text/javascript">
    $(function () {
        //table header fix
        $("#fixTable").tableHeadFixer();
        //        $('.sample').floatingScrollbar();
        $(".sales-person-list").hide();

        //country wise division
        $(document).on('change', '#countryId', function () {
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: '{{URL::to("idlyEngagedBuyerReport/getDivision/")}}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#divisionId').html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    $('.js-source-states').select2();
                    App.unblockUI();
                }

            });
        });


        $(document).on("change", "#productCategoryId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
//        alert(productCategoryId);
//        return false;
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getProductList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#productId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#productId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
            if (productId == '0') {
                $('#brandId').html("<option class='form-control js-source-states' value='0'>@lang('label.SELECT_BRAND_OPT')</option>");
                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'>@lang('label.SELECT_MACHINE_TYPE_OPT')</option>");
                return false;
            }
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getBrandList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#brandId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#brandId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
            if (productId == '0' || brandId == '0') {
                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'>@lang('label.SELECT_MACHINE_TYPE_OPT')</option>");
                return false;
            }
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getMachineTypeList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#machineTypeId").html(res.html);
                    $("#buyerId").html(res.buyerSearch);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(document).on("change", "#buyerCategoryId, #machineTypeId, #businessStatusId, #salesStatusId, #divisionId", function (e) {
            e.preventDefault();
            var buyerCategoryId = $('#buyerCategoryId').val();
            var productCategoryId = $('#productCategoryId').val();
            var productId = $('#productId').val();
            var brandId = $('#brandId').val();
            var machineTypeId = $('#machineTypeId').val();
            var businessStatusId = $('#businessStatusId').val();
            var salesStatusId = $('#salesStatusId').val();
            var countryId = $('#countryId').val();
            var divisionId = $('#divisionId').val();
//            if (productId == '0' || brandId == '0') {
//                $('#machineTypeId').html("<option class='form-control js-source-states' value='0'>@lang('label.SELECT_MACHINE_TYPE_OPT')</option>");
//                return false;
//            }
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getBuyerSearchList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_category_id: buyerCategoryId,
                    product_category_id: productCategoryId,
                    product_id: productId,
                    brand_id: brandId,
                    machine_type_id: machineTypeId,
                    business_status_id: businessStatusId,
                    sales_status_id: salesStatusId,
                    country_id: countryId,
                    division_id: divisionId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#buyerId").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //related sales person list modal
        $(".related-sales-person-list").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getRelatedSalesPersonList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId
                },
                beforeSend: function () {
                    $("#showRelatedSalesPersonList").html('');
                },
                success: function (res) {
                    $("#showRelatedSalesPersonList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //assign sales person modal
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

        //in business brand list modal
        $(".in-business-brand-list").on("click", function (e) {
            e.preventDefault();
            var buyerId = $(this).attr("data-id");
            var brandId = $(this).attr("data-brand-id");
            $.ajax({
                url: "{{ URL::to('/idlyEngagedBuyerReport/getInBusinessBrandList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId,
                    brand_id: brandId,
                },
                beforeSend: function () {
                    $("#showInBusinessBrandList").html('');
                },
                success: function (res) {
                    $("#showInBusinessBrandList").html(res.html);
                    $('.tooltips').tooltip();
                    //table header fix
                    $(".table-head-fixer-color").tableHeadFixer();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });



    });
</script>
@stop