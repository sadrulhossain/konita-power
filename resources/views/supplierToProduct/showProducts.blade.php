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
                        @if(!empty($relatedBrandArr[$request->product_id]))
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentBrandArr[$request->supplier_id][$request->product_id])) {
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
                    @if(!empty($relatedBrandArr[$request->product_id]))
                    <?php $sl = 0; ?>
                    @foreach($relatedBrandArr[$request->product_id] as $brandId)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($brandRelatedToSupplier[$request->product_id]) && array_key_exists($brandId, $brandRelatedToSupplier[$request->product_id])) {
                        $checked = 'checked';
                    }

                    $brandDisabled = $brandTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveBrandArr) && in_array($brandId, $inactiveBrandArr)) {
                        if($checked == 'checked'){
                            $checkCondition = 1;
                        }
                        $brandDisabled = 'disabled';
                        $brandTooltips = __('label.INACTIVE');
                    }
                    if (!empty($dependentBrandArr[$request->supplier_id][$request->product_id])) {
                        if (in_array($brandId, $dependentBrandArr[$request->supplier_id][$request->product_id]) && ($checked != '')) {
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



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();

<?php if (!empty($relatedBrandArr[$request->product_id])) { ?>
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

    });
</script>